<?php
namespace Mapbender\ManagerBundle\Controller;

use Mapbender\Component\Loader\RefreshableSourceLoader;
use Mapbender\CoreBundle\Component\Source\TypeDirectoryService;
use Mapbender\CoreBundle\Entity\Source;
use Doctrine\ORM\EntityRepository;
use Mapbender\CoreBundle\Entity\SourceInstance;
use Mapbender\ManagerBundle\Form\Model\HttpOriginModel;
use Mapbender\ManagerBundle\Form\Type\HttpSourceOriginType;
use Mapbender\ManagerBundle\Utils\WeightSortedCollectionUtil;
use FOM\ManagerBundle\Configuration\Route as ManagerRoute;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

/**
 *  Mapbender repository controller
 *
 * @author  Christian Wygoda <christian.wygoda@wheregroup.com>
 * @author  Andreas Schmitz <andreas.schmitz@wheregroup.com>
 * @author  Paul Schmidt <paul.schmidt@wheregroup.com>
 * @author  Andriy Oblivantsev <andriy.oblivantsev@wheregroup.com>
 * @ManagerRoute("/repository")
 */
class RepositoryController extends ApplicationControllerBase
{
    /**
     * Renders the layer service repository.
     *
     * @ManagerRoute("/", methods={"GET"})
     * @return Response
     */
    public function indexAction()
    {
        $oid = new ObjectIdentity('class', 'Mapbender\CoreBundle\Entity\Source');
        $this->denyAccessUnlessGranted('VIEW', $oid);
        $repository = $this->getDoctrine()->getRepository('Mapbender\CoreBundle\Entity\Source');
        /** @var Source[] $allSources */
        $allSources = $repository->findBy(array(), array('id' => 'ASC'));

        $sources = array();
        $reloadableIds = array();
        $typeDirectory = $this->getTypeDirectory();
        foreach ($allSources as $source) {
            $sources[] = $source;
            if ($typeDirectory->getRefreshSupport($source)) {
                $reloadableIds[] = $source->getId();
            }
        }

        return $this->render('@MapbenderManager/Repository/index.html.twig', array(
            'title' => 'Repository',
            'sources' => $sources,
            'reloadableIds' => $reloadableIds,
            'oid' => $oid,
            'create_permission' => $this->isGranted('CREATE', $oid),
        ));
    }

    /**
     * @ManagerRoute("/new", methods={"GET"})
     * @ManagerRoute("/new/{sourceType}", methods={"POST"}, name="mapbender_manager_repository_new_submit")
     * @param Request $request
     * @param string|null $sourceType
     * @return Response
     */
    public function newAction(Request $request, $sourceType = null)
    {
        $oid = new ObjectIdentity('class', 'Mapbender\CoreBundle\Entity\Source');
        $this->denyAccessUnlessGranted('CREATE', $oid);

        $sourceTypeLabels = $this->getTypeDirectory()->getTypeLabels();
        /** @var FormInterface[] $forms */
        $forms = array();
        foreach ($sourceTypeLabels as $type => $sourceTypeLabel) {
            $formAction = $this->generateUrl('mapbender_manager_repository_new_submit', array('sourceType' => $type), UrlGeneratorInterface::RELATIVE_PATH);
            $form = $this->createForm(new HttpSourceOriginType(), new HttpOriginModel(), array(
                'action' => $formAction,
            ));
            $forms[$type] = $form;
        }

        if ($sourceType) {
            if (!array_key_exists($sourceType, $forms)) {
                throw new BadRequestHttpException();
            }
            $form = $forms[$sourceType];
            $form->handleRequest($request);
        } else {
            $form = null;
        }
        if ($form && $form->isSubmitted() && $form->isValid()) {
            $directory = $this->getTypeDirectory();
            try {
                $loader = $directory->getSourceLoaderByType($sourceType);
                $importerResponse = $loader->evaluateServer($form->getData(), false);
                $source = $importerResponse->getSource();

                $this->setAliasForDuplicate($source);
                $em = $this->getEntityManager();
                $em->beginTransaction();

                $em->persist($source);

                $em->flush();
                $this->initializeAccessControl($source);
                $em->commit();
                // @todo: provide translations
                $this->addFlash('success', "A new {$source->getType()} source has been created");
                return $this->redirectToRoute("mapbender_manager_repository_view", array(
                    "sourceId" => $source->getId(),
                ));
            } catch (\Exception $e) {
                $importerResponse = null;
                $form->addError(new FormError($this->getTranslator()->trans($e->getMessage())));
            }
        }

        $formViews = array();
        foreach ($forms as $type => $form) {
            if (!$sourceType) {
                $sourceType = $type;
            }
            $formViews[$type] = $form->createView();
        }

        return $this->render('@MapbenderManager/Repository/new.html.twig', array(
            'sourceTypes' => $sourceTypeLabels,
            'forms' => $formViews,
            'activetype' => $sourceType,
        ));
    }

    /**
     * @ManagerRoute("/source/{sourceId}", methods={"GET"})
     * @param string $sourceId
     * @return Response
     */
    public function viewAction($sourceId)
    {
        /** @var Source|null $source */
        $source = $this->getDoctrine()->getRepository("MapbenderCoreBundle:Source")->find($sourceId);
        if (!$source) {
            throw $this->createNotFoundException();
        }

        $oid = new ObjectIdentity('class', 'Mapbender\CoreBundle\Entity\Source');
        if (!$this->isGranted('VIEW', $oid)) {
            $this->denyAccessUnlessGranted('VIEW', $source);
        }
        return $this->render($source->getViewTemplate(), array(
            'source' => $source,
            'title' => $source->getType() . ' ' . $source->getTitle(),
            'wms' => $source,   // HACK: source name in legacy templates
            'wmts' => $source,  // HACK: source name in legacy templates
        ));
    }

    /**
     * Deletes a Source (POST) or renders confirmation markup (GET)
     * @ManagerRoute("/source/{sourceId}/delete", methods={"GET", "POST"})
     * @param Request $request
     * @param string $sourceId
     * @return Response
     */
    public function deleteAction(Request $request, $sourceId)
    {
        $oid = new ObjectIdentity('class', 'Mapbender\CoreBundle\Entity\Source');
        $em = $this->getEntityManager();
        /** @var Source $source */
        $source = $em->getRepository("MapbenderCoreBundle:Source")->find($sourceId);
        if (!$source) {
            // If delete action is forbidden, hide the fact that the source doesn't
            // exist behind an access denied.
            $this->denyAccessUnlessGranted('VIEW', $oid);
            $this->denyAccessUnlessGranted('DELETE', $oid);
            throw $this->createNotFoundException();
        }
        // Must have VIEW + DELETE on either any Source globally, or on this particular
        // Source
        if (!($this->isGranted('VIEW', $oid))) {
            $this->denyAccessUnlessGranted('VIEW', $source);
        }
        if (!($this->isGranted('DELETE', $oid))) {
            $this->denyAccessUnlessGranted('DELETE', $source);
        }
        if ($request->getMethod() === Request::METHOD_GET) {
            return $this->render('@MapbenderManager/Repository/confirmdelete.html.twig',  array(
                'source' => $source,
            ));
        }
        // capture ACL and entity updates in a single transaction
        $em->beginTransaction();
        /** @var MutableAclProviderInterface $aclProvider */
        $aclProvider = $this->get('security.acl.provider');
        $oid         = ObjectIdentity::fromDomainObject($source);
        $aclProvider->deleteAcl($oid);

        // update modification timestamp on affected applications
        $dtNow = new \DateTime('now');
        $instances = $source->getInstances();
        $iDesc = array();
        foreach ($instances as $instance) {
            $iDesc[] = get_class($instance) . "#{$instance->getId()}";
            $layerset = $instance->getLayerset();
            $application = $layerset->getApplication();
            $em->persist($application);
            $application->setUpdated($dtNow);
        }
        $em->remove($source);
        $em->flush();
        $em->commit();
        $this->addFlash('success', 'Your source has been deleted');
        return $this->redirect($this->generateUrl("mapbender_manager_repository_index"));
    }

    /**
     * Returns a Source update form.
     *
     * @ManagerRoute("/source/{sourceId}/update", methods={"GET", "POST"})
     * @param Request $request
     * @param string $sourceId
     * @return Response
     */
    public function updateformAction(Request $request, $sourceId)
    {
        $oid = new ObjectIdentity('class', 'Mapbender\CoreBundle\Entity\Source');
        /** @var Source|null $source */
        $source = $this->getDoctrine()->getRepository("MapbenderCoreBundle:Source")->find($sourceId);
        if (!$source) {
            // If edit action is forbidden, hide the fact that the source doesn't
            // exist behind an access denied.
            $this->denyAccessUnlessGranted('VIEW', $oid);
            $this->denyAccessUnlessGranted('EDIT', $oid);
            throw $this->createNotFoundException();
        }
        $canUpdate = $this->getTypeDirectory()->getRefreshSupport($source);
        if (!$canUpdate) {
            throw $this->createNotFoundException();
        }
        // Must have VIEW + EDIT on either any Source globally, or on this particular
        // Source
        if (!$this->isGranted('VIEW', $oid)) {
            $this->denyAccessUnlessGranted('VIEW', $source);
        }
        if (!$this->isGranted('EDIT', $oid)) {
            $this->denyAccessUnlessGranted('EDIT', $source);
        }

        /** @var RefreshableSourceLoader $loader */
        $loader = $this->getTypeDirectory()->getSourceLoaderByType($source->getType());
        $formModel = HttpOriginModel::extract($source);
        $formModel->setOriginUrl($loader->getRefreshUrl($source));
        $form = $this->createForm(new HttpSourceOriginType(), $formModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getEntityManager();
            $em->beginTransaction();
            try {
                $loader->refresh($source, $formModel);
                $em->persist($source);

                $em->flush();
                $em->commit();

                $this->addFlash('success', "Your {$source->getType()} source has been updated");
                return $this->redirectToRoute("mapbender_manager_repository_view", array(
                    "sourceId" => $source->getId(),
                ));
            } catch (\Exception $e) {
                $form->addError(new FormError($this->getTranslator()->trans($e->getMessage())));
            }
        }

        return $this->render('@MapbenderManager/Repository/updateform.html.twig', array(
            'form' => $form->createView(),
            'sourceTypeLabel' => $source->getTypeLabel(),
        ));
    }

    /**
     *
     * @ManagerRoute("/application/{slug}/instance/{instanceId}")
     * @param Request $request
     * @param string $slug
     * @param string $instanceId
     * @return Response
     */
    public function instanceAction(Request $request, $slug, $instanceId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var SourceInstance|null $instance */
        $instance = $em->getRepository("MapbenderCoreBundle:SourceInstance")->find($instanceId);

        if (null === $instance) {
            throw $this->createNotFoundException('Instance does not exist');
        }

        $this->denyAccessUnlessGranted('EDIT', new ObjectIdentity('class', 'Mapbender\CoreBundle\Entity\Source'));
        $factory = $this->getTypeDirectory()->getInstanceFactory($instance->getSource());
        $form = $this->createForm($factory->getFormType($instance), $instance);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($instance);
            $layerSet = $instance->getLayerset();
            if ($layerSet) {
                $application = $layerSet->getApplication();
                if ($application) {
                    $application->setUpdated(new \DateTime('now'));
                    $em->persist($application);
                }
            }
            $em->flush();

            $this->addFlash('success', 'Your instance has been updated.');
            return $this->redirectToRoute('mapbender_manager_application_edit', array(
                "slug" => $slug,
            ));
        }

        return $this->render($factory->getFormTemplate($instance), array(
            "form" => $form->createView(),
            "slug" => $slug,
            "instance" => $instance,
        ));
    }

    /**
     *
     * @ManagerRoute("/application/{slug}/instance/{layersetId}/weight/{instanceId}")
     * @param Request $request
     * @param string $slug
     * @param string $layersetId
     * @param string $instanceId
     * @return Response
     */
    public function instanceWeightAction(Request $request, $slug, $layersetId, $instanceId)
    {

        $newWeight = $request->get("number");
        $targetLayersetId = $request->get("new_layersetId");
        $em = $this->getEntityManager();
        /** @var EntityRepository $instanceRepository */
        $instanceRepository = $this->getDoctrine()->getRepository('MapbenderCoreBundle:SourceInstance');

        /** @var SourceInstance $instance */
        $instance = $instanceRepository->find($instanceId);

        if (!$instance) {
            throw $this->createNotFoundException('The source instance id:"' . $instanceId . '" does not exist.');
        }
        if (intval($newWeight) === $instance->getWeight() && $layersetId === $targetLayersetId) {
            return new JsonResponse(array(
                'error' => '',      // why?
                'result' => 'ok',   // why?
            ));
        }

        $layerset = $this->requireLayerset($layersetId);
        if ($layersetId === $targetLayersetId) {
            WeightSortedCollectionUtil::updateSingleWeight($layerset->getInstances(), $instance, $newWeight);
        } else {
            $targetLayerset = $this->requireLayerset($targetLayersetId);
            $targetCollection = $targetLayerset->getInstances();
            WeightSortedCollectionUtil::moveBetweenCollections($targetCollection, $layerset->getInstances(), $instance, $newWeight);
            $instance->setLayerset($targetLayerset);
            $em->persist($targetLayerset);
        }
        $em->persist($layerset);
        $em->flush();

        return new JsonResponse(array(
            'error' => '',      // why?
            'result' => 'ok',   // why?
        ));
    }

    /**
     *
     * @ManagerRoute("/application/{slug}/instance/{layersetId}/enabled/{instanceId}", methods={"POST"})
     * @param Request $request
     * @param string $slug
     * @param string $layersetId
     * @param string $instanceId
     * @return Response
     */
    public function instanceEnabledAction(Request $request, $slug, $layersetId, $instanceId)
    {
        $em = $this->getEntityManager();
        /** @var SourceInstance|null $sourceInstance */
        $sourceInstance = $em->getRepository("MapbenderCoreBundle:SourceInstance")->find($instanceId);
        if (!$sourceInstance) {
            throw $this->createNotFoundException();
        }
        $application = $sourceInstance->getLayerset()->getApplication();
        $wasEnabled = $sourceInstance->getEnabled();
        $newEnabled = $request->get('enabled') === 'true';
        $sourceInstance->setEnabled($newEnabled);
        $application->setUpdated(new \DateTime('now'));
        $em->persist($application);
        $em->persist($sourceInstance);
        $em->flush();
        return new JsonResponse(array(
            'success' => array(         // why?
                "id" => $sourceInstance->getId(), // why?
                "type" => "instance",   // why?
                "enabled" => array(
                    'before' => $wasEnabled,
                    'after' => $newEnabled,
                ),
            ),
        ));
    }

    /**
     * @return TypeDirectoryService
     */
    protected function getTypeDirectory()
    {
        /** @var TypeDirectoryService $service */
        $service = $this->get('mapbender.source.typedirectory.service');
        return $service;
    }

    protected function setAliasForDuplicate(Source $source)
    {
        $wmsWithSameTitle = $this->getDoctrine()
            ->getManager()
            ->getRepository("MapbenderCoreBundle:Source")
            ->findBy(array('title' => $source->getTitle()));

        if (count($wmsWithSameTitle) > 0) {
            $source->setAlias(count($wmsWithSameTitle));
        }
    }

    /**
     * @param object $entity
     */
    protected function initializeAccessControl($entity)
    {
        /** @var MutableAclProviderInterface $aclProvider */
        $aclProvider    = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($entity);
        $acl            = $aclProvider->createAcl($objectIdentity);

        $securityIdentity = UserSecurityIdentity::fromAccount($this->getUser());

        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $aclProvider->updateAcl($acl);
    }
}

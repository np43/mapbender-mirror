<?php

namespace Mapbender\CoreBundle;

use Doctrine\ORM\EntityRepository;
use Mapbender\CoreBundle\Component\ApplicationYAMLMapper;
use Mapbender\CoreBundle\Component\ElementInventoryService;
use Mapbender\CoreBundle\Component\MapbenderBundle;
use Mapbender\CoreBundle\Component\Source\TypeDirectoryService;
use Mapbender\CoreBundle\Component\UploadsManager;
use Mapbender\CoreBundle\Component\YamlApplicationImporter;
use Mapbender\CoreBundle\Entity\Application;
use Mapbender\CoreBundle\Entity\Layerset;
use Mapbender\CoreBundle\Utils\EntityUtil;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Mapbender - The central Mapbender3 service(core). Provides metadata about
 * available elements and templates.
 *
 * @author Christian Wygoda
 * @author Andriy Oblivantsev
 */
class Mapbender
{
    /** @var \Doctrine\ORM\EntityManager|\Doctrine\Common\Persistence\ObjectManager */
    protected $manager;

    /** @var ContainerInterface */
    private $container;

    /** @var string[] */
    private $templates = array();

    /**
     * Mapbender constructor.
     *
     * Iterate over all bundles and if is an MapbenderBundle, get list
     * of elements, layers and templates.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $bundles          = $container->get('kernel')->getBundles();
        $registry         = $container->get('doctrine');
        $this->manager    = $registry->getManager();
        $this->container  = $container;

        /** @var MapbenderBundle $bundle */
        foreach ($bundles as $bundle) {
            if (!is_subclass_of($bundle, 'Mapbender\CoreBundle\Component\MapbenderBundle')) {
                continue;
            }
            $this->templates          = array_merge($this->templates, $bundle->getTemplates());
        }
    }

    /**
     * Get list of all declared element classes.
     *
     * Element classes need to be declared in each bundle's main class getElement
     * method.
     *
     * @return string[]
     */
    public function getElements()
    {
        /** @var ElementInventoryService $inventoryService */
        $inventoryService = $this->container->get('mapbender.element_inventory.service');
        return $inventoryService->getActiveInventory();
    }

    /**
     * Get list of names of all declared template classes.
     *
     * Template classes need to be declared in each bundle's main class
     * getTemplates method.
     *
     * @return string[]
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * Get application entities
     *
     * @return Application[]
     */
    public function getApplicationEntities()
    {
        return array_merge(
            $this->getYamlApplicationEntities(true),
            $this->getDatabaseApplicationEntities()
        );
    }

    /**
     * Get application entity for given slug
     *
     * @param string $slug
     * @return Application
     */
    public function getApplicationEntity($slug)
    {
        $registry   = $this->container->get('doctrine');
        $repository = $registry->getRepository('MapbenderCoreBundle:Application');

        if ($repository instanceof EntityRepository) {
            /** @var EntityRepository $repository */
            $entity = $repository->findOneBy(array(
                'slug' => $slug,
            ));
        } else {
            $entity = null;
        }
        /** @var Application|null $entity */
        if ($entity) {
            $entity->setSource(Application::SOURCE_DB);
        } else {
            $yamlMapper = new ApplicationYAMLMapper($this->container);
            $entity     = $yamlMapper->getApplication($slug);
        }

        return $entity;
    }

    /**
     * Get public YAML application entities
     *
     * @param bool $onlyPublic Only public applications?
     * @return Application[]
     */
    public function getYamlApplicationEntities($onlyPublic = true)
    {
        $applications = array();
        $yamlMapper   = new ApplicationYAMLMapper($this->container);
        foreach ($yamlMapper->getApplications() as $application) {
            if ($onlyPublic && !$application->isPublished()) {
                continue;
            }

            $applications[ $application->getSlug() ] = $application;
        }
        return $applications;
    }

    /**
     * Get data base entities
     *
     * @return Application[]
     */
    public function getDatabaseApplicationEntities()
    {
        $repository = $this->manager->getRepository('MapbenderCoreBundle:Application');
        /** @var Application[] $applications */
        $applications = $repository->findBy(array(), array(
            'title' => 'ASC',
        ));
        foreach ($applications as $application) {
            $application->setSource(Application::SOURCE_DB);
        }
        return $applications;
    }

    /**
     * Import YAML application
     *
     * @param  string     $slug     Source application slug
     */
    public function importYamlApplication($slug)
    {
        $application = $this->getApplicationEntity($slug);
        $newSlug = EntityUtil::getUniqueValue($this->manager, get_class($application), 'slug', $application->getSlug() . '_yml', '');
        $newTitle = EntityUtil::getUniqueValue($this->manager, get_class($application), 'title', $application->getTitle(), ' ');
        $elements             = array();
        /** @var Layerset[] $layerSetMap */
        $layerSetMap = array();
        $translator           = $this->container->get("translator");

        // @todo: move all the following code into the YamlApplicationImporter service

        $application->setSlug($newSlug);
        $application->setTitle($newTitle);
        $application->setSource(Application::SOURCE_DB);
        $application->setPublished(true);
        $application->setUpdated(new \DateTime('now'));

        /** @var UploadsManager $ulm */
        $ulm = $this->container->get('mapbender.uploads_manager.service');
        $ulm->copySubdirectory($slug, $newSlug);

        $this->manager->beginTransaction();

        /**
         * Save application
         */
        $this->manager->persist($application);

        /**
         * Save region properties
         */
        foreach ($application->getRegionProperties() as $prop) {
            $prop->setApplication($application);
            $this->manager->persist($prop);
        }
        /**
         * Save elements
         */
        foreach ($application->getElements() as $elm) {
            $elements[ $elm->getId() ] = $elm;
            $title                     = $translator->trans($elm->getTitle());
            $elm->setTitle($title);
            $this->manager->persist($elm);
        }

        /**
         * Save layer sets
         */
        /** @var TypeDirectoryService $instanceFactory */
        $instanceFactory = $this->container->get('mapbender.source.typedirectory.service');
        $siblingSources = array();
        foreach ($application->getLayersets() as $set) {
            $layerSetMap[$set->getId()] = $set;
            foreach ($set->getInstances() as $inst) {
                if (!$instanceFactory->matchInstanceToPersistedSource($inst, $siblingSources)) {
                    $this->manager->persist($inst->getSource());
                }
                $siblingSources[] = $inst->getSource();
                $this->manager->persist($inst);
            }
            $this->manager->persist($set);
        }

        // Flush to generate final layer ids
        $this->manager->flush();

        /**
         * Post update element configurations
         */
        foreach ($elements as $element) {
            $config = $element->getConfiguration();
            if (isset($config['target'])) {
                $elm              = $elements[ $config['target'] ];
                $config['target'] = $elm->getId();
            }
            if (isset($config['layersets'])) {
                $layerSets = array();
                foreach ($config['layersets'] as $layerSetId) {
                    $layerSet = $layerSetMap[$layerSetId];
                    $layerSets[] = $layerSet->getId();
                }
                $config['layersets'] = $layerSets;

            }
            if (isset($config['layerset'])) {
                $layerSet = $layerSetMap[$config['layerset']];
                $config['layerset'] = $layerSet->getId();
            }

            if (is_a($element->getClass(), 'Mapbender\CoreBundle\Element\BaseSourceSwitcher', true)) {
                if ($config['instancesets']) {
                    foreach ($config['instancesets'] as $instanceSetId => $instanceSet) {
                        $instances = array();
                        foreach ($instanceSet["instances"] as $instanceNamedId) {
                            foreach ($application->getLayersets() as $appInstanceSet) {
                                foreach ($appInstanceSet->getInstances() as $appInstance) {
                                    $instances = array();
                                    // hack: Title becomes original UID by import
                                    if ($appInstance->getSource()->getTitle() == $instanceNamedId) {
                                        $instances[] = $appInstance->getId();
                                        break;
                                    }
                                }

                            }
                        }
                        $config['instancesets'][$instanceSetId]['instances'] = $instances;
                    }
                }
            }

            $element->setConfiguration($config);
            $this->manager->persist($element);
        }


        /** @var YamlApplicationImporter $importerService */
        $importerService = $this->container->get('mapbender.yaml_application_importer.service');
        $importerService->addViewPermissions($application);

        $this->manager->flush();
        $this->manager->commit();
    }
}

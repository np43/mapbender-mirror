<?php

namespace Mapbender\WmsBundle\Element;

use Mapbender\CoreBundle\Component\Element;
use Mapbender\CoreBundle\Component\Source\TypeDirectoryService;
use Mapbender\WmsBundle\Component\Wms\Importer;
use Mapbender\WmsBundle\Component\WmsSourceEntityHandler;
use Mapbender\WmsBundle\Entity\WmsOrigin;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class WmsLoader
 * @package Mapbender\WmsBundle\Element
 */
class WmsLoader extends Element
{
    /**
     * @return string|string[]
     */
    public static function getClassTitle()
    {
        return "mb.wms.wmsloader.class.title";
    }

    /**
     * @return string
     */
    public static function getClassDescription()
    {
        return "mb.wms.wmsloader.class.description";
    }

    /**
     * @return array
     */
    public static function getClassTags()
    {
        return array("mb.wms.wmsloader.wms", "mb.wms.wmsloader.loader");
    }

    /**
     * @return array
     */
    public static function getDefaultConfiguration()
    {
        return array(
            "tooltip" => "",
            "target" => null,
            "autoOpen" => false,
            "defaultFormat" => "image/png",
            "defaultInfoFormat" => "text/html",
            "splitLayers" => false,
            "useDeclarative" => false
        );
    }

    /**
     * @return string
     */
    public function getWidgetName()
    {
        return 'mapbender.mbWmsloader';
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        $configuration = parent::getConfiguration();
        if ($this->container->get('request_stack')->getCurrentRequest()->get('wms_url')) {
            $wms_url = $this->container->get('request_stack')->getCurrentRequest()->get('wms_url');
            $all = $this->container->get('request_stack')->getCurrentRequest()->query->all();
            foreach ($all as $key => $value) {
                if (strtolower($key) === "version" && stripos($wms_url, "version") === false) {
                    $wms_url .= "&version=" . $value;
                } elseif (strtolower($key) === "request" && stripos($wms_url, "request") === false) {
                    $wms_url .= "&request=" . $value;
                } elseif (strtolower($key) === "service" && stripos($wms_url, "service") === false) {
                    $wms_url .= "&service=" . $value;
                }
            }
            $configuration['wms_url'] = urldecode($wms_url);
        }
        if ($this->container->get('request_stack')->getCurrentRequest()->get('wms_id')) {
            $wmsId = $this->container->get('request_stack')->getCurrentRequest()->get('wms_id');
            $configuration['wms_id'] = $wmsId;
        }
        return $configuration;
    }

    /**
     * @return array|\string[][]
     */
    public function getAssets()
    {
        $assetRefs = array(
            'js' => array(
                '@MapbenderWmsBundle/Resources/public/mapbender.element.wmsloader.js',
            ),
            'css' => array(
                '@MapbenderWmsBundle/Resources/public/sass/element/wmsloader.scss',
            ),
            'trans' => array(
                'MapbenderWmsBundle:Element:wmsloader.json.twig',
            ),
        );
        if (!empty($config['useDeclarative'])) {
            $assetRefs['js'][] = '@MapbenderCoreBundle/Resources/public/mapbender.distpatcher.js';
        }
        return $assetRefs;
    }

    /**
     * @return string
     */
    public static function getType()
    {
        return 'Mapbender\WmsBundle\Element\Type\WmsLoaderAdminType';
    }

    /**
     * @return string
     */
    public static function getFormTemplate()
    {
        return 'MapbenderWmsBundle:ElementAdmin:wmsloader.html.twig';
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->container->get('templating')
            ->render('MapbenderWmsBundle:Element:wmsloader.html.twig', array(
                'id' => $this->getId(),
                "title" => $this->getTitle(),
                'example_url' => $this->container->getParameter('wmsloader.example_url'),
                'configuration' => $this->getConfiguration()));
    }

    /**
     * @param string $action
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Mapbender\CoreBundle\Component\Exception\XmlParseException
     */
    public function httpAction($action)
    {
        switch ($action) {
            case 'loadWms':
                return $this->loadWms();
            default:
                throw new NotFoundHttpException('No such action');
        }
    }

    /**
     * @return JsonResponse
     * @throws \Mapbender\CoreBundle\Component\Exception\XmlParseException
     */
    protected function loadWms()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $wmsSource = $this->getWmsSource($request);

        $wmsSourceEntityHandler = new WmsSourceEntityHandler($this->container, $wmsSource);
        $wmsInstance = $wmsSourceEntityHandler->createInstance();
        /** @var TypeDirectoryService $directory */
        $directory = $this->container->get('mapbender.source.typedirectory.service');
        $layerConfiguration = $directory->getSourceService($wmsInstance)->getConfiguration($wmsInstance);
        $elementConfig = $this->getConfiguration();
        if ($elementConfig['splitLayers']) {
            $layerConfigurations = $this->splitLayers($layerConfiguration);
        } else {
            $layerConfigurations = [$layerConfiguration];
        }

        return new JsonResponse($layerConfigurations);
    }

    /**
     * @param $request
     * @return \Mapbender\WmsBundle\Entity\WmsSource
     * @throws \Mapbender\CoreBundle\Component\Exception\XmlParseException
     */
    protected function getWmsSource(Request $request)
    {
        $requestUrl = $request->get("url");
        $requestUserName = $request->get("username");
        $requestPassword = $request->get("password");
        $onlyValid = false;

        $wmsOrigin = new WmsOrigin($requestUrl, $requestUserName, $requestPassword);
        /** @var Importer $importer */
        $importer = $this->container->get('mapbender.importer.source.wms.service');
        $importerResponse = $importer->evaluateServer($wmsOrigin, $onlyValid);

        return $importerResponse->getWmsSourceEntity();
    }

    /**
     * @param $layerConfiguration
     * @return array
     */
    protected function splitLayers($layerConfiguration)
    {
        $children = $layerConfiguration['configuration']['children'][0]['children'];
        $layerConfigurations = array();
        foreach ($children as $child) {
            $layerConfiguration['configuration']['children'][0]['children'] = [$child];
            $layerConfiguration['configuration']['children'][0]['options']['title'] = $child['options']['title']
                . ' ('
                . $layerConfiguration['configuration']['title']
                . ')'
            ;
            $layerConfigurations[] = $layerConfiguration;
        }
        return $layerConfigurations;
    }
}

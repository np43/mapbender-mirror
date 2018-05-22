<?php
/**
 *
 * @author David Patzke <david.patzke@wheregroup.com>
 */

namespace Mapbender\CoreBundle\Routing;

use Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RouteCollection;

class BundleLoader
{

    protected $container;
    protected $path                    = "/Controller/";
    protected $loader;

    /**
     * BundleLoader constructor.
     *
     * @param $container
     */
    public function __construct($container)
    {

        $this->container = $container;
        $rootdir = $this->container->get('kernel')->getRootDir();
        $this->loader = new PhpFileLoader(new FileLocator());

    }

    /**
     * @param      $resource
     * @param null $type
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        $routes  = new RouteCollection();
        $bundles = $this->container->getParameter('kernel.bundles');
        foreach ($bundles as $bundleName => $bundlesPath) {

            if (preg_match('/Mapbender/', $bundleName, $matches)) {

                $routingConfigPath = "@" . $bundleName.  $this->path;

                try {
                    $routes->addCollection(
                        $this->loader->import($routingConfigPath)
                    );
                } catch (FileLoaderImportCircularReferenceException $e) {
                    throw $e;
                } catch (FileLoaderLoadException $e) {
                    throw $e;
                }
            }
        }

        return $routes;
    }


}
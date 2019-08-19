<?php

namespace Mapbender\ManagerBundle\Template;

use Mapbender\CoreBundle\Component\Template;

/**
 * Application manager template
 *
 * @copyright 03.02.2015 by WhereGroup GmbH & Co. KG
 */
class ManagerTemplate extends Template
{
    protected static $css = array(
        '@MapbenderManagerBundle/Resources/public/sass/manager/applications.scss',
        '/components/jquery-ui/themes/base/jquery-ui.css',
        '/../vendor/twbs/bootstrap/dist/css/bootstrap.css',
        "/components/datatables/media/css/dataTables.bootstrap4.css",
        
    );

    protected static $js = array(
        '/components/jquerydialogextendjs/jquerydialogextendjs-built.js',
        '@MapbenderCoreBundle/Resources/public/mapbender.trans.js',
        '@MapbenderCoreBundle/Resources/public/vendor/popper.js',
        '/../vendor/twbs/bootstrap/dist/js/bootstrap.js',
        "/components/datatables/media/js/dataTables.bootstrap4.js",
        
        '/components/underscore/underscore-min.js'
    );

    protected static $translations = array(
        '@MapbenderManagerBundle/Resources/views/translations.json.twig'
    );

    /**
     * @inheritdoc
     */
    public function render($format = 'html', $html = true, $css = true, $js = true)
    {
        return "";
    }
}

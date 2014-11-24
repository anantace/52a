<?php
require 'bootstrap.php';



/**
 * ZweiundfuenfzigaPlugin.class.php
 *
 * ...
 *
 * @author  asudau@uos.de
 * @version 0.1a
 */

class ZweiundfuenfzigaPlugin extends AbstractStudIPAdministrationPlugin {

    public function __construct() {
        parent::__construct();

        
	$navigation = new PluginNavigation();
        $navigation->setDisplayname('52a Statistiken');
        $this->setNavigation($navigation);
        $top_navigation = new PluginNavigation();
        $top_navigation->setDisplayname('52a Statistiken');
        $this->setTopnavigation($top_navigation);


/**
	 $navigation = new Navigation(_('52a Statistiken'));
        $navigation->setURL(PluginEngine::GetURL($this, array(), '/LicenseCount'));
        $navigation->setImage(Assets::image_path('blank.gif'));
        Navigation::addItem('/zweiundfuenfzigaplugin', $navigation);

	
	 $url = PluginEngine::getURL($this, array(), '/LicenseCount');
        $index  = new Navigation(_("Übersicht"),$url );
        Navigation::addItem('/zweiundfuenfzigaplugin/uebersicht', $index);


	 $url = PluginEngine::getURL($this, array(), 'LicenseCount/');
        $license_count  = new Navigation(_("Aktuelle Zählung der Lizenzen"),$url );
        Navigation::addItem('/zweiundfuenfzigaplugin/uebersicht/LicenseCount', $license_count);
**/
	/**
	 $url = PluginEngine::getURL($this, array(), '/pie');
        $pie  = new Navigation(_("Versuch Pie"),$url );
        Navigation::addItem('/zweiundfuenfzigaplugin/uebersicht/pie', $pie);
	**/

    }

    public function initialize () {

    
        PageLayout::addStylesheet($this->getPluginURL().'/assets/style.css');
        PageLayout::addScript($this->getPluginURL().'/assets/application.js');
    }

    public function perform($unconsumed_path) {
        $this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'show'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }

    private function setupAutoload() {
        if (class_exists("StudipAutoloader")) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function ($class) {
                include_once __DIR__ . $class . '.php';
            });
        }
    }
}

<?php
namespace Craft;

class ArchFotoliaPlugin extends BasePlugin
{
    function getName()
    {
         return Craft::t('Fotolia Images');
    }

    function getVersion()
    {
        return '0.1';
    }

    function getDeveloper()
    {
        return 'August';
    }

    function getDeveloperUrl()
    {
        return 'http://globalbizhost.com';
    }
    
    public function hasCpSection()
    {
    	return true;
    }
    
    protected function defineSettings()
    {
    	return array(
    			'apiKey' => array(AttributeType::String),
    			'price' => array(AttributeType::String),
    			'description' => array(AttributeType::Mixed),
    			'productInfo' => array(AttributeType::Mixed),
    	);
    }

    public function getSettingsHtml()
    {
    	return craft()->templates->render('archfotolia/settings', array(
    			'settings' => $this->getSettings()
    	));
    }
    
    public function registerCpRoutes()
    {
    	return array(
    			'archfotolia\/images\/search' => 'archfotolia/images/_search',
    	);
    }
}
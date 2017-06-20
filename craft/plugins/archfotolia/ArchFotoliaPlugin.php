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
    
    public function registerCpRoutes()
    {
    	return array(
    			'archfotolia\/images\/search' => 'archfotolia/images/_search',
    	);
    }
}
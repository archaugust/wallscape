<?php
namespace Craft;
/**
 * Images Controller
 *
 * Defines actions which can be posted to by forms in our templates.
 */
class ArchFotolia_ImagesController extends BaseController
{
    /**
     * Search Images
     *
     * Search Fotolia for images through their API, based on POST data
     */
    public function actionSearchResults()
    {
        $this->requirePostRequest();
        if (!$keywords = craft()->request->getPost('keywords')) {
            $keywords = "";
        }
        
        /**
         * Get search results from Fotolia
         */
        $api_key = $configSettingValue = craft()->config->get('api_key', 'archfotolia');
        $json = file_get_contents('http://'. $api_key .'@api.fotolia.com/Rest/1/search/getSearchResults?search_parameters%5Bwords%5D='. $keywords .'&search_parameters%5Blanguage_id%5D=2');
        $results = json_decode($json);
        
        $results_count = $results->nb_results;
        $page = 1;
        
        $this->renderTemplate('archfotolia/images/search_results', array(
        		"count" => $results_count,
        		"items" => $results
        ));
    }
    
    /** 
     * Add images 
     * 
     * Add images as product type 'Fotolia'
     */
    public function actionAddImages() {
    	$this->requirePostRequest();
    	if ($images = craft()->request->getPost('images')) {
    		$category = craft()->request->getPost('category');
    		
   			foreach ($images as $image) {
   				$product = new Commerce_ProductModel;
   				$product->typeId = 3;
   				
   				/** 
   				 * test product details
   				 * 
   				 * custom info to be pulled from Fotolia
   				 */
	    		$product->setContent(array(
	    				'title' => 'title',
	    				'collectionsCategory' => $category,
	    				'fotoliaId' => $image,
	    				'image' => 'http://t1.ftcdn.net/jpg/00/85/49/96/400_F_85499695_QX0oDTvKncxkgczhIdIl1tCKkCyruQfB.jpg'
	    		));
	    		
    			craft()->commerce_products->saveProduct($product);
   			}
	    		
	    		
   			craft()->userSession->setNotice(Craft::t('Added '. count($images) .' image(s) to '. $category .' category.'));
    	}
    	
    }
}
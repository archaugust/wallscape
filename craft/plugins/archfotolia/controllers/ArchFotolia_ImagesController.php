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
        	craft()->request->redirect('archfotolia');
        }
        
        /**
         * Get search results from Fotolia
         */
        $pluginSettings = craft()->plugins->getPlugin('archfotolia')->getSettings();
        $apiKey = $pluginSettings->apiKey;
        
        $json = file_get_contents('http://'. $apiKey .'@api.fotolia.com/Rest/1/search/getSearchResults?search_parameters%5Bwords%5D='. $keywords .'&search_parameters%5Blimit%5D=20');
        $results = json_decode($json);
        
        $results_count = $results->nb_results;
        
        $this->renderTemplate('archfotolia/images/search_results', array(
        		"keywords" => $keywords,
        		"count" => $results_count,
        		"items" => $results
        ));
    }
    
    public function actionSearchResultsAjax()
    {
    	$this->requireAjaxRequest();
    	
    	$keywords = craft()->request->getPost('keywords'); 
    	$offset = craft()->request->getPost('offset');
    		
    	/**
    	 * Get search results from Fotolia
    	 */
    	$pluginSettings = craft()->plugins->getPlugin('archfotolia')->getSettings();
    	$apiKey = $pluginSettings->apiKey;
    	
    	$json = file_get_contents('http://'. $apiKey .'@api.fotolia.com/Rest/1/search/getSearchResults?search_parameters%5Bwords%5D='. $keywords .'&search_parameters%5Blimit%5D=20&search_parameters%5Boffset%5D='. $offset);
    	$results = json_decode($json);
    	
    	$this->returnJson(array('success' => true, "items" => $results));
    }
    
    /** 
     * Add images 
     * 
     * Add images as product type 'Fotolia'
     */
    public function actionAddImages() {
    	$this->requirePostRequest();
    	if ($images = craft()->request->getPost('images')) {
    		
    		// get Price
    		$pluginSettings = craft()->plugins->getPlugin('archfotolia')->getSettings();
    		$price = $pluginSettings->price;
    		
    		$item = craft()->request->getPost('category');
    		$item = explode('||', $item);
    		$category = array();
    		$category['id'] = $item[0];
    		$category['name'] = $item[1];
    		
   			foreach ($images as $image) {
   				$item = explode('||', $image);
   				$image = array();
   				$image['id'] = $item[0];
   				$image['title'] = $item[1];
   				$image['url'] = $item[2];

   				$product = new Commerce_ProductModel;
   				$product->typeId = 3;
   				$product->enabled = true;
   				$product->promotable = true;
   				$product->freeShipping = 0;
   				$product->postDate = new DateTime();
   				$product->getContent()->title = $image['title'];
   				$product->slug = StringHelper::toKebabCase($image['title']);
   				
	    		$product->setContent(array(
	    				'collectionsCategory' => $category['id'],
   						'fotoliaId' => $image['id'],
   						'fotoliaImageUrl' => $image['url'],
   						'fotoliaProductCategoryTitle' => $category['name']
	    		));

	    		$variant = new Commerce_VariantModel();
	    		$variant->setProduct($product);
	    		$variant->sortOrder = 1;
	    		$variant->getContent()->title = $image['title'];
	    		$variant->sku = $image['id'];
	    		$variant->price = $price;
	    		$variant->unlimitedStock = true;
	    		
	    		$product->setVariants(array($variant));
	    		
    			craft()->commerce_products->saveProduct($product);
   			}
	    		
	    		
   			craft()->userSession->setNotice(Craft::t('Added '. count($images) .' image(s) to '. $category['name'] .' category.'));
    	}
    	
    }
    
    /**
     * Delete image
     *
     * Delete an existing image
     */
    public function actionDeleteImage()
    {
    	$this->requirePostRequest();
    	$this->requireAjaxRequest();
    	$id = craft()->request->getRequiredPost('id');
    	craft()->archFotolia->deleteImageById($id);
    	$this->returnJson(array('success' => true));
    }
}
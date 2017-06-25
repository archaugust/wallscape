<?php
namespace Craft;

class ArchFotoliaService extends BaseApplicationComponent
{
    public function saveImage($filename, $url)
    {
    	$iAssetSourceId = 1;
    	$iAssetFolderId = (int)craft()->assets->getRootFolderBySourceId($iAssetSourceId)->id;
    	$sTempFilePath = AssetsHelper::getTempFilePath();
    	$sFileContents = file_get_contents($url);
    	file_put_contents($sTempFilePath, $sFileContents);
    	
    	// Get craft to copy the temp file into the assets folder
    	$oAssetOperationResponse = craft()->assets->insertFileByLocalPath(
    			$sTempFilePath,
    			$filename .'.'. substr($url, -3),
    			$iAssetFolderId,
    			AssetConflictResolution::Replace
    			);
    	
    	return (int)$oAssetOperationResponse->responseData["fileId"];
    }

    public function deleteImageById($id)
    {
    	
    }
}
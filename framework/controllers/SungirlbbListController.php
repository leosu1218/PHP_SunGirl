<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/SungirlbbListCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/SungirlbbPhotoCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/UploadHelper/HomePageUploadHelper.php' );




/**
 * Class OrderController
 *
 * PHP version 5.3
 *
 * @category Controller
 * @package Controller
 * @author Rex Chen <rexchen@synctech-infinity.com>,Jai Chien <jaichien@syncte-infinity.com>
 * @copyright 2015 synctech.com
 */
class SungirlbbListController extends RestController {

    /**
     *  GET:   /homepage/<position:\w+>/image
     * Get home page images with position.
     * @param $position
     * @return array
     * @throws AuthorizationException
     * @throws Exception
     */
    public function getSungirlList($category , $pageNo, $pageSize ){
        $collection = new SungirlbbListCollection;
        $photoCollection = new SungirlbbPhotoCollection();
        $records = $collection->getRecords( array("category"=> $category ) ,$pageNo,$pageSize);
        foreach($records['records'] as $key => $record){
            $record['id']
            $photorecords = $collection->getRecords( array(""=>  $record['id'] ));

        }
        $photoCollection = new SungirlbbPhotoCollection();

        return $records;
    }

    /**
     * POST: 	/website/<position:\w+>/upload
     * @return array
     */
    public function upLoadHomePage($position){
        PlatformUser::instanceBySession();
        $upload = $this->createUpload($position);
        $ImageCollection =  $this->createCollection($position);
        $result = $upload->receive();

        $upLoadData = array();
        foreach($result  as  $item){
            $upLoadData['filename'] = $item['fileName'] ;
            $upLoadData['property'] = 0 ;
        }

        $count = $ImageCollection->create($upLoadData);
        if($count != 1) {
            throw new DbOperationException("insert returned record to DB fail.");
        }
        return  array();
    }

    /**
     * DELETE: /website/<position:\w+>/<id:\d+>
     * @return array
     * @throws AuthorizationException
     * @throws UploadException
     */
    public function removeHomePage($position,$id){
        PlatformUser::instanceBySession();
        $ImageCollection = $this->createCollection($position);
        $upload = $this->createUpload($position);

        $homePageModel = $ImageCollection -> getById($id) ;
        $homePageData = $homePageModel ->toRecord() ;

        $count = $homePageModel->destroy();
        if($count != 1) {
            throw new DbOperationException("Delete returned record to DB fail.");
        }
        $uploadData =  $upload ->removeByName($homePageData['filename']);

        return  array($uploadData);
    }

    private function createUpload( $type ){
        if( $type=="banner" ){
            return new HomePageUploadHelper();
        }
        else{
            throw new Exception("Unsuport this $type type to get collection.", 1);
        }
    }


}




?>

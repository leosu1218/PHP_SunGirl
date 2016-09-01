<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/CarouselImageCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/UploadHelper/HomePageUploadHelper.php' );
require_once( FRAMEWORK_PATH . 'collections/PlatformUserCollection.php' );



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
class CarouselImageController extends RestController {

    /**
     * GET: 	/website/<position:\w+>/image/<pageNo:\d+>/<pageSize:\d+>
     * @param $position
     * @param $pageNo
     * @param $pageSize
     * @return mixed
     * @throws Exception
     */
    public function getByBanner( $position, $pageNo, $pageSize ){
        $collection = $this->createCollection( $position );
        $records = $collection->getRecords( array("property"=> 0 ));

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
            $upLoadData['high'] = $item['high'] ;
            $upLoadData['width'] = $item['width'] ;

        }

        $count = $ImageCollection->create($upLoadData);
        if($count != 1) {
            throw new DbOperationException("insert returned record to DB fail.");
        }
        return $result;
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

    /**
     * @param $type
     * @return AdvertisementImageCollection or HomePageImageCollection
     * @throws Exception
     */
    private function createCollection( $type ){
        if( $type=="banner" ){
            return new CarouselImageCollection();
        }
        else{
            throw new Exception("Unsuport this $type type to get collection.", 1);
        }
    }

    private function createUpload( $type ){
        if( $type=="banner" ){
            return new HomePageUploadHelper();
        }
        else{
            throw new Exception("Unsuport this $type type to get collection.", 1);
        }
    }

    /**
     * PUT: /website/<position:\w+>/modify/<id:\d+>
     * @return array
     */
    public function updateUrl( $position, $id ) {
        PlatformUser::instanceBySession();
        $collection = $this->createCollection($position);
        $attributes = array(
            'image_url' => $this->params("bannerUrl")
        );
        $count = $collection->getById($id)->update($attributes);
        if($count != 1) {
            throw new DbOperationException("update imglink record to DB fail.");
        }
        return $attributes;
    }

}




?>

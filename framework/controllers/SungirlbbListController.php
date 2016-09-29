<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/SungirlbbListCollection.php' );
require_once( FRAMEWORK_PATH . 'collections/SungirlbbPhotoCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/UploadHelper/SungirlUploadHelper.php' );
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
class SungirlbbListController extends RestController {


    /**
     * GET: 	/sungirl/<category:\w+>/list/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * @param $category
     * @param $pageNo
     * @param $pageSize
     * @return array
     * @throws AuthorizationException
     */
    public function getSungirlList($category , $pageNo, $pageSize ){
        $collection = new SungirlbbListCollection;
        $records = $collection->getRecords( array("category"=> $category ) ,$pageNo,$pageSize);
        return $records;
    }

    /**
     * GET: 	/sungirl/<category:\w+>/<id:\d+>
     * @param $category
     * @param $id
     * @return array
     * @throws AuthorizationException
     */
    public function getSungirlById($category,$id){
        $collection = new SungirlbbListCollection;
        $record = $collection->getRecordById($id);
        if($category == 'photo'){
            $photoCollection = new SungirlbbPhotoCollection();
            $photorecords = $photoCollection->getRecords( array("sungirlbb_id"=> $id ));
            $record['photo'] = $photorecords['records'];
        }
        return $record;
    }

    /**
     * GET: 	/sungirl/<category:\w+>/<id:\d+>/client
     * @param $category
     * @param $id
     * @return array
     * @throws AuthorizationException
     */
    public function getSungirlClientById($category,$id){
        $collection = new SungirlbbListCollection;
        $photoCollection = new SungirlbbPhotoCollection();
        $attributes = array();
        if($category == 'all'){
            $attributes['home_state'] = 0;
        }else{
            $attributes['category'] = $category;
        }
        $attributes['id'] = $id;
        $records = $collection->getRecords($attributes, 1, 1 , array() );
        foreach($records['records'] as $key => $record){
            $photorecords = $photoCollection->getRecords( array("sungirlbb_id"=>  $record['id'] ));
            $records['records'][$key]['photo'] = $photorecords['records'];
        }

        return $records;
    }

    /**
     * @param $category
     * @param $pageNo
     * @param $pageSize
     * @return array
     * @throws AuthorizationException
     */
    public function getSungirlClient($category , $pageNo, $pageSize ){
        $collection = new SungirlbbListCollection;
        $photoCollection = new SungirlbbPhotoCollection();
        $attributes = array();
        if($category == 'all'){
            $attributes['home_state'] = 0;
        }else{
            $attributes['category'] = $category;
        }

        $records = $collection->getRecords($attributes, $pageNo, $pageSize , array() , "ready_time DESC , id DESC");
        foreach($records['records'] as $key => $record){
            $photorecords = $photoCollection->getRecords( array("sungirlbb_id"=>  $record['id'] ));
            $records['records'][$key]['photo'] = $photorecords['records'];
        }

        return $records;
    }

    /**
     * GET: 	/sungirl/<category:\w+>/client/clickSum/<pageNo:\d+>/<pageSize:\d+>
     * @param $category
     * @param $pageNo
     * @param $pageSize
     * @return array
     * @throws AuthorizationException
     */
    public function getSungirlClientBySum($category , $pageNo, $pageSize ){
        $collection = new SungirlbbListCollection;
        $photoCollection = new SungirlbbPhotoCollection();
        $attributes = array();
        if($category == 'all'){
            $attributes['home_state'] = 0;
        }else{
            $attributes['category'] = $category;
        }

        $records = $collection->getRecords($attributes, $pageNo, $pageSize , array() , "click_sum DESC");
        foreach($records['records'] as $key => $record){
            $photorecords = $photoCollection->getRecords( array("sungirlbb_id"=>  $record['id'] ));
            $records['records'][$key]['photo'] = $photorecords['records'];
        }

        return $records;
    }

    /**
     * POST: 	/sungirl/photo/upload
     * @param $position
     * @return array
     * @throws Exception
     * @throws UploadException
     */
    public function upLoadPhoto(){
        PlatformUser::instanceBySession();
        $upload = new SungirlUploadHelper ;
        $result = $upload->receive();

        return $result;
    }

    /**
     * DELETE: /sungirl/photo/delete/<filename:\w+>/<type:\w+>
     * @param $filename
     * @param $type
     * @return array
     */
    public function removePhoto($filename,$type){
        PlatformUser::instanceBySession();
        $upload = new SungirlUploadHelper;
        $uploadData =  $upload ->removeByName($filename . "." . $type);

        return  array($uploadData);
    }


    /**
     * POST: 	/sungirl/<category:\w+>/create
     * @param $category
     * @return array
     * @throws AuthorizationException
     * @throws InvalidAccessParamsException
     */
    public function create( $category ) {
        $result = array();
        $user = PlatformUser::instanceBySession();
        $collection = new SungirlbbListCollection;
        $attribute = $this->receiver;
        unset($attribute['photo']);
        $attribute['create_time'] = date("Y-m-d H:i:s");
        $attribute['category'] = $category;
        $result["effectRow"] = $collection->create( $attribute );
        $result["id"] = $collection->lastCreated()->id;

        if( $category == "photo" ){
            $photos = $this->params("photo");
            $photoCollection = new SungirlbbPhotoCollection;

            foreach($photos as $key => $photo){
                $result["effectRow"] = $photoCollection->create( array('sungirlbb_id' => $result["id"] , 'photo_name' => $photo['fileName'] ,'height' => $photo['height'] ,'width' => $photo['width'] ) );
            }
        }

        return $result;
    }

    /**
     * POST: 	/sungirl/photo/upload
     * @param $category
     * @param $id
     * @return array
     * @throws AuthorizationException
     * @throws DbOperationException
     * @throws InvalidAccessParamsException
     */
    public function update( $category ,$id) {
        $result = array();
        PlatformUser::instanceBySession();
        $collection = new SungirlbbListCollection;
        $model = $collection->getById($id);
        $attribute = $this->receiver;
        unset($attribute['photo']);
        $model->update($attribute);
        if( $category == "photo" ){
            $photos = $this->params("photo");
            $photoCollection = new SungirlbbPhotoCollection;

            $photoCout = $photoCollection->multipleDestroyByCondition(array('sungirlbb_id' => $id));
            if($photoCout == 0) {
                throw new DbOperationException("Delete sungirlbb_photo record to DB fail.");
            }
            foreach($photos as $key => $photo){
                $result["effectRow"] = $photoCollection->create( array('sungirlbb_id' => $id , 'photo_name' => $photo['fileName'] ,'height' => $photo['height'] ,'width' => $photo['width'] ) );
            }
        }

        return $result;
    }

    /**
     * DELETE: /sungirl/delete/<id:\d+>
     * @param $id
     * @return array
     * @throws AuthorizationException
     * @throws DbOperationException
     */
    public function removeSungirl($category,$id){
        PlatformUser::instanceBySession();
        $collection = new SungirlbbListCollection;
        $photoCollection = new SungirlbbPhotoCollection();
        $count = $collection->getById($id)->destroy();
        if($count != 1) {
            throw new DbOperationException("Delete sungirlbb_list record to DB fail.");
        }
        if( $category == "photo" ) {
            $photoCout = $photoCollection->multipleDestroyByCondition(array('sungirlbb_id' => $id));
        }

        return  array($count);
    }


}




?>

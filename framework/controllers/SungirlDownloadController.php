<?php
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
require_once( FRAMEWORK_PATH . 'collections/SungirlDownloadCollection.php' );
require_once( FRAMEWORK_PATH . 'extends/UploadHelper/SungirlDownloadHelper.php' );
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
class SungirlDownloadController extends RestController {


    /**
     * GET: 	/sungirlDownload//list/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>
     * @param $pageNo
     * @param $pageSize
     * @return array
     * @throws AuthorizationException
     */
    public function getSungirlDownload($pageNo, $pageSize ){
        PlatformUser::instanceBySession();
        $collection = new SungirlDownloadCollection;
        $records = $collection->getRecords(array(),$pageNo,$pageSize);
        return $records;
    }

    /**
     * GET: 	/sungirl/download/getByid/<id:\d+>
     * @param $category
     * @param $id
     * @return array
     * @throws AuthorizationException
     */

    public function getSungirlDownloadById($id){
        PlatformUser::instanceBySession();
        $collection = new SungirlDownloadCollection;
        $record = $collection->getRecordById($id);
        return $record;
    }

    /**
     * GET: 	/sungirl/client/download/<pageNo:\d+>/<pageSize:\d+>
     * @param $category
     * @param $pageNo
     * @param $pageSize
     * @return array
     * @throws AuthorizationException
     */
    public function getDownloadClient( $pageNo, $pageSize ){
        $collection = new SungirlDownloadCollection;
        $search = array('ready_time' => date("Y-m-d"));
        $records = $collection->searchRecords($pageNo, $pageSize, $search , 'ready_time DESC');
        return $records;
    }

    /**
     * GET: 	/sungirl/client/download/clickSum/<pageNo:\d+>/<pageSize:\d+>
     * @param $pageNo
     * @param $pageSize
     * @return array
     */
    public function getDownloadClientBysum( $pageNo, $pageSize ){
        $collection = new SungirlDownloadCollection;
        $search = array('ready_time' => date("Y-m-d"));
        $records = $collection->searchRecords($pageNo, $pageSize, $search , 'click_sum DESC');
        return $records;
    }

    /**
     * @param $id
     * @return array
     */
    public function getDownloadClientByid($id){
        $collection = new SungirlDownloadCollection;
        $search = array('ready_time' => date("Y-m-d"), 'id' => $id);
        $records = $collection->searchRecords(1, 1, $search, 'ready_time DESC');
        return $records;
    }

    /**
     * POST: 	/sungirl/downnload/upload
     * @param $position
     * @return array
     * @throws Exception
     * @throws UploadException
     */
    public function upLoadDownload(){
        PlatformUser::instanceBySession();
        $upload = new SungirlDownloadHelper ;
        $result = $upload->receive();

        return $result;
    }

    /**
     * DELETE: /sungirl/download/deleteImg/<filename:\w+>/<type:\w+>
     * @param $filename
     * @param $type
     * @return array
     */
    public function removeDownloadImg($filename,$type){
        PlatformUser::instanceBySession();
        $upload = new SungirlDownloadHelper;
        $uploadData =  $upload ->removeByName($filename . "." . $type);

        return  array($uploadData);
    }


    /**
     * POST: 	/sungirl/create/download
     * @param $category
     * @return array
     * @throws AuthorizationException
     * @throws InvalidAccessParamsException
     */
    public function create( ) {
        $result = array();
        $user = PlatformUser::instanceBySession();
        $collection = new SungirlDownloadCollection;
        $attribute = $this->receiver;
        $attribute['create_time'] = date("Y-m-d H:i:s");
        $result["effectRow"] = $collection->create( $attribute );

        return $result;
    }

    /**
     * PUT: 	/sungirl/update/download/<id:\d+>
     * @param $category
     * @param $id
     * @return array
     * @throws AuthorizationException
     * @throws DbOperationException
     * @throws InvalidAccessParamsException
     */
    public function update($id) {
        $result = array();
        PlatformUser::instanceBySession();
        $collection = new SungirlDownloadCollection;
        $model = $collection->getById($id);
        $attribute = $this->receiver;
        $model->update($attribute);

        return $result;
    }

    /**
     * DELETE: /sungirl/delete/download/<id:\d+>
     * @param $id
     * @return array
     * @throws AuthorizationException
     * @throws DbOperationException
     */
    public function removeDownload($id){
        PlatformUser::instanceBySession();
        $collection = new SungirlDownloadCollection;
        $count = $collection->getById($id)->destroy();
        if($count != 1) {
            throw new DbOperationException("Delete sungirlbb_list record to DB fail.");
        }

        return  array($count);
    }


}




?>

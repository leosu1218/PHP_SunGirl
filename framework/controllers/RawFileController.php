<?php
/**
 *  RawFileController code.
 *
 *  PHP version 5.3
 *
 *  @category NeteXss
 *  @package Controller
 *  @author Rex Chen <rexchen@synctech-infinity.com>
 *  @copyright 2015 synctech.com
 */
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
include_once( FRAMEWORK_PATH . 'collections/RawFileCollection.php' );
include_once( FRAMEWORK_PATH . 'collections/IMediaEventRawCollection.php' );
include_once( FRAMEWORK_PATH . 'models/PlatformUser.php' );
require_once( FRAMEWORK_PATH . 'extends/UploadHelper/IMediaEventUploadHelper.php' );

class RawFileController extends RestController {

    /**
     * DELETE:   /rawfile/imediaevent/<id:\d+>
     * remove raw_file by id.
     * @param $id
     */
    public function remove($id) {
        $actor      = PlatformUser::instanceBySession();
        $rawFile    = new RawFileCollection();

        $rawFile->setActor($actor);
        $model = $rawFile->getById($id);
        $isSuccess = $model->destroy();
        return array("isSuccess"=>$isSuccess);
    }

    /**
     * DELETE:   /rawfile/imediaevent/self-all
     * Remove all raw file by session user id
     */
    public function removeSelfAll() {
        $actor      = PlatformUser::instanceBySession();
        $rawFile    = new RawFileCollection();
        $count = $rawFile->destroyByUserId($actor->getId());
        return array("isSuccess" => $count);
    }

    /**
     * GET: 	/rawfile/imediaevent/list/<pageNo:\d+>/<pageSize:\d+>
     * Get self's raw file list.
     * @param $pageNo
     * @param $pageSize
     */
    public function getList($pageNo, $pageSize) {
        $actor      = PlatformUser::instanceBySession();
        $rawFile    = new RawFileCollection();
        $attributes = array(
            "user_id" => $actor->getId()
        );

        $rawFile->setActor($actor);
        return $rawFile->getRecords($attributes, $pageNo, $pageSize);
    }

    /**
     * @throws DbOperationException
     * @throws Exception
     */
    public function receiveFile() {
        $actor      = PlatformUser::instanceBySession();
        $rawFile    = new RawFileCollection();
        $dao        = $rawFile->dao;
        $response   = array();

        if($dao->transaction()) {
            try {

                $rawFile->setActor($actor);
                $upload     = new IMediaEventUploadHelper($rawFile);
                $appRawFile = new IMediaEventRawCollection($dao);
                $result     = $upload->receive();

                foreach($result as $key => $item) {
                    $rowCount = $appRawFile->createByCSV($item["currentPath"], $item["rawId"]);
                    $response[$key] = array(
                        "id" => $item["rawId"],
                        "name" => $item["originName"],
                        "rowCount" => $rowCount
                    );
                }

                $dao->commit();
                return $response;
            }
            catch(Exception $e) {
                $dao->rollback();
                throw $e;
            }
        }
        else {
            throw new DbOperationException("Can not start transaction.");
        }
    }
}




?>
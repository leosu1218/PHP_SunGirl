<?php
/**
 *  IMediaEventRawController code.
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


class IMediaEventRawController extends RestController {

    /**
     * GET: 	/imediaevent/self-all/box/list/<pageNo:\d+>/<pageSize:\d+>
     * Get self's box list.
     * @param $pageNo
     * @param $pageSize
     */
    public function getBoxListSelf($pageNo, $pageSize) {
        $actor      = PlatformUser::instanceBySession();
        $collection    = new IMediaEventRawCollection();
        return $collection
                ->setActor($actor)
                ->getBoxListByUserId($actor->getId(), $pageNo, $pageSize);
    }

    /**
     * GET: 	/imediaevent/self-all/box/<boxId:\w+>/cam/list/<pageNo:\d+>/<pageSize:\d+>
     * Get self's cam list by box id.
     * @param $pageNo
     * @param $pageSize
     * @return mixed
     */
    public function getCamListSelfByBoxId($boxId, $pageNo, $pageSize) {
        $actor      = PlatformUser::instanceBySession();
        $collection    = new IMediaEventRawCollection();
        $boxId = urldecode($boxId);
        return $collection
            ->setActor($actor)
            ->getCamListByBoxId($actor->getId(), $boxId, $pageNo, $pageSize);
    }
}




?>
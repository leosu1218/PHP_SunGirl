<?php
/**
 *  IMediaEventTranslateRuleController code.
 *
 *  PHP version 5.3
 *
 *  @category NeteXss
 *  @package Controller
 *  @author Rex Chen <rexchen@synctech-infinity.com>
 *  @copyright 2015 synctech.com
 */
require_once( FRAMEWORK_PATH . 'system/controllers/RestController.php' );
include_once( FRAMEWORK_PATH . 'collections/IMediaEventTranslateRuleCollection.php' );
include_once( FRAMEWORK_PATH . 'models/PlatformUser.php' );


class IMediaEventTranslateRuleController extends RestController {

    protected $collection = null;

    /**
     * Init controller.
     */
    public function __construct() {
        parent::__construct();
        $this->collection = new IMediaEventTranslateRuleCollection();
    }

    /**
     * Create a condition array with default's attributes
     * @param array $attributes Extend attributes.
     * @return array
     */
    private function createCondition($attributes=array()) {
        $actor = PlatformUser::instanceBySession();
        return array_merge(array("user_id" => $actor->getId()), $attributes);
    }

    /**
     * Return rule not found http response/
     */
    private function returnRuleNotFound() {
        $this->responser->send(array("m" => "Rule not found."), $this->responser->NotFound());
    }

    /**
     * GET: 	/imediaevent/translate-rule/list/<pageNo:\d+>/<pageSize:\d+>
     * Get a translate rule list for iMedia events records.
     * @param $pageNo List's page number.
     * @param $pageSize List's page size.
     * @throws AuthorizationException
     * @return array Rules list.
     */
    public function getList($pageNo, $pageSize) {
        $attributes = $this->createCondition();
        return $this->collection->getRecords($attributes, $pageNo, $pageSize);
    }

    /**
     * GET: 	/imediaevent/translate-rule/<id:\d+>
     * Get a translate rule record by rule's record id.
     * @param $id Rule's record id.
     * @return array Rule's record.
     * @throws AuthorizationException
     */
    public function getById($id) {
        $attributes = $this->createCondition(array("id" => $id));
        $record = $this->collection->getRecord($attributes);
        if(empty($record)) {
           $this->returnRuleNotFound();
        }
        return $record;
    }

    /**
     * DELETE: 	/imediaevent/translate-rule/<id:\d+>
     * Delete a translate rule record by rule's record id.
     * @param $id Rule's record id.
     * @return array Result.
     * @throws AuthorizationException
     */
    public function deleteById($id) {
        $attributes = $this->createCondition(array("id" => $id));
        $model = $this->collection->get($attributes);
        if(!is_null($model->getId())) {
            if(!$model->destroy()) {
                throw new DbOperationException("IMedia translate rule id delete fail on db operation.");
            }
        }
        else {
            $this->returnRuleNotFound();
        }

        return array();
    }

    /**
     * PUT: 	/imediaevent/translate-rule/<id:\d+>
     * Update a translate rule record by rule's record id.
     * @param $id Rule's record id.
     * @return array New rule's record.
     * @throws AuthorizationException
     */
    public function updateById($id) {
        $newAttributes = array(
            "name" =>  $this->params("name"),
            "type" =>  $this->params("type")
        );

        $attributes = $this->createCondition(array("id" => $id));
        $model = $this->collection->get($attributes);
        if(!is_null($model->getId())) {
            if(!$model->update($newAttributes)) {
                throw new DbOperationException("IMedia translate rule id update fail on db operation.");
            }
        }
        else {
            $this->returnRuleNotFound();
        }

        return $model->toRecord();
    }

    /**
     * POST: 	/imediaevent/translate-rule
     * Create a translate rule record.
     * @return array New rule's record.
     * @throws AuthorizationException
     */
    public function create() {
        $attributes = $this->createCondition(array(
            "name" =>  $this->params("name"),
            "type" =>  $this->params("type")
        ));
        $effectRows = $this->collection->create($attributes);
        if($effectRows > 0) {
            return $this->collection->lastCreated()->toRecord();
        }
        else {
            throw new DbOperationException("IMedia translate rule id create fail on db operation.");
        }
    }
}

?>
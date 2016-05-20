<?php
require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/IMediaEventTranslateRule.php' );

/**
 *	class IMediaEventTranslateRuleCollection
 *
 *	PHP version 5.3
 *
 *	@category Collection
 *	@package Permission
 *	@author Rex chen <rexchen@synctech.ebiz.tw>
 *	@copyright 2015 synctech.com
 */
class IMediaEventTranslateRuleCollection extends PermissionDbCollection {

    /* DbCollection abstract methods. */

    /**
     *	Get the entity table name.
     *
     *	@return string
     */
    public function getTable() {
        return "imedia_event_translate_rule";
    }

    public function getModelName() {
        return "IMediaEventTranslateRule";
    }

    /**
     *	Check attributes is valid.
     *
     *	@param $attributes 	array Attributes want to checked.
     *	@return bool 		If valid return true.
     */
    public function validAttributes($attributes) {
        if(array_key_exists("id", $attributes)) {
            throw new Exception("Can't write the attribute 'id'.");
        }

        return true;
    }

    /**
     *	Get Primary key attribute name
     *
     *	@return string
     */
    public function getPrimaryAttribute() {
        return "id";
    }
}



?>

<?php
/**
 *	IMediaTranslateRule code.
 *
 *	PHP version 5.3
 *
 *	@category Model
 *	@package User
 *	@author Rex chen <rexchen@synctech.ebiz.tw>
 *	@copyright 2015 synctech.com
 */

require_once( FRAMEWORK_PATH . 'system/models/PermissionDbModel.php' );

/**
 *	IMediaTranslateRule code.
 *
 *	PHP version 5.3
 *
 *	@category Model
 *	@package User
 *	@author Rex chen <rexchen@synctech.ebiz.tw>
 *	@copyright 2015 synctech.com
 */

class IMediaEventTranslateRule extends PermissionDbModel {

    /* 	Method of interface User. */
    /**
     *	Get the entity table name.
     *
     *	@return string
     */
    public function getTable() {
        return "imedia_event_translate_rule";
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
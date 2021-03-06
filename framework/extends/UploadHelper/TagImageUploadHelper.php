<?php
include_once(dirname(__FILE__) . '/UploadHelper.php');

/**
 * Class IMediaEventUploadHelper
 */
class TagImageUploadHelper extends UploadHelper {
    const FILE_PATH  = "website/tag/";

    /*      UploadHelper abstract methods.    */
    /**
     * Defined accepted MIME type for upload.
     * @return array array( <type1>, <type2>, <type3> ... )
     */
    public function acceptMIMEType() {
        return array(
            self::IMG_PNG_MIME,
            self::IMG_JPG_MIME,
            self::IMG_JPEG_MIME,
            self::IMG_GIF_MIME
        );
    }

    /**
     * Get save path.
     * @param $fileHandler $_FILES item.
     * @return string e.g. /tmp/files/
     */
    public function savePath($fileHandler=array(),$extension="*") {
        return UPLOAD . self::FILE_PATH;
    }

    /**
     * Handle of each other file name generation for saved.
     * @param $fileHandler $_FILES item.
     * @return string e.g. test.jpg
     */
    public function saveName($fileHandler,$extension="*") {
        return $fileHandler['name'];
    }

    public function removeByName($fileName){
        $removefile = UPLOAD . self::FILE_PATH . $fileName ;
        return unlink($removefile) ;
    }
}

?>
<?php
include_once(dirname(__FILE__) . '/UploadHelper.php');
include_once(FRAMEWORK_PATH . 'collections/RawFileCollection.php');

/**
 * Class IMediaEventUploadHelper
 */
class IMediaEventUploadHelper extends UploadHelper {

    private $salt = "IsnAl56Xs";
    private $hash = "";

    protected $collection = null;

    /**
     * @param RawFileCollection $collection
     */
    public function __construct(RawFileCollection $collection) {
        if($collection->getActor()) {
            $this->collection = $collection;
        }
        else {
            throw new UploadException("RawCollection should be set actor.");
        }
    }

    /**
     * Get the upload file's hash code.
     * @param $fileHandler
     * @return string
     */
    private function getHash($fileHandler) {
        if($this->hash == "") {
            $code = hash_file('sha1', $fileHandler["tmp_name"]);
            $this->hash = md5($code . $this->salt);
        }
        return $this->hash;
    }

    /**
     * Verify file.
     * @param $hash string File's hash code.
     * @throws AuthorizationException
     * @throws UploadException
     */
    private function verifyFile($hash) {
        $attributes = array("hash" => $hash);
        if($this->collection->count($attributes) > 0) {
            throw new UploadException("Already exists the file.");
        }
    }

    /**
     * Return upload user id(by session).
     * @return int
     */
    private function getUserId() {
        return $this->collection->getActor()->getId();
    }

    /**
     * Save file record to db.
     * @param $fileHandler
     */
    private function saveToDatabase(&$fileHandler) {
        $hash = $this->getHash($fileHandler);
        $this->verifyFile($hash);

        $rowCount = $this->collection->create(array(
            "name"              => $fileHandler["name"],
            "hash"              => $hash,
            "create_datetime"   => date("Y-m-d H:i:s"),
            "user_id"           => $this->getUserId()
        ));

        if($rowCount == 0) {
            throw new UploadException("Save upload file info to Db fail.");
        }
        else {
            $fileHandler["raw_id"] = $this->collection->lastCreated()->getId();
        }
    }

    /*      UploadHelper abstract methods.    */
    /**
     * Defined accepted MIME type for upload.
     * @return array array( <type1>, <type2>, <type3> ... )
     */
    public function acceptMIMEType() {
        return array(
            self::OCTET_STREAM_MIME,
            self::MS_EXCEL_MIME,
            self::TEXT_PLAIN_MIME,
            self::TEXT_CSV_MIME
        );
    }

    /**
     * Get save path.
     * @param $fileHandler $_FILES item.
     * @return string e.g. /tmp/files/
     */
    public function savePath($fileHandler=array()) {
        return RAW_FILES_PATH . "imedia_event/";
    }

    /**
     * Handle of each other file name generation for saved.
     * @param $fileHandler $_FILES item.
     * @return string e.g. test.jpg
     */
    public function saveName($fileHandler) {
        return $this->getHash($fileHandler) . ".csv";
    }

    /**
     * On received upload file hook.
     * @param $fileHandler $_FILES item.
     */
    public function onReceived(&$fileHandler) {
        $this->saveToDatabase($fileHandler);
    }

    /**
     * On saved upload file hook.
     * @param $fileHandler $_FILES item.
     */
    public function onSaved(&$fileHandler, &$result) {
        $result["savePath"]     = $this->savePath($fileHandler);
        $result["rawId"]        = $fileHandler["raw_id"];
        $result["currentPath"]  = $result["savePath"] . $result["fileName"];
    }
}

?>
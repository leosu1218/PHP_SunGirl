<?php
require_once(dirname(__FILE__) . "/UploadException.php");


/**
 * Class UploadHelper
 */
abstract class UploadHelper {

    const PDF_MIME              = 'application/pdf';
    const OCTET_STREAM_MIME     = 'application/octet-stream';
    const ZIP_MIME              = 'application/zip';

    const MS_WORD_MIME          = 'application/msword';
    const MS_EXCEL_MIME         = 'application/vnd.ms-excel';
    const MS_POWERPOINT_MIME    = 'application/vnd.ms-powerpoint';

    const IMG_GIT_MIME          = 'image/gif';
    const IMG_PNG_MIME          = 'image/png';
    const IMG_JPG_MIME          = 'image/jpg';

    const AUDIO_MPEG_MIME       = 'audio/mpeg';
    const AUDIO_WAV_MIME        = 'audio/x-wav';

    const VIDEO_MPEG_MIME       = 'video/mpeg';
    const VIDEO_QUICKTIME_MIME  = 'video/quicktime';
    const VIDEO_AVI_MIME        = 'video/x-msvideo';

    const TEXT_PLAIN_MIME       = 'text/plain';
    const TEXT_HTML_MIME        = 'text/html';
    const TEXT_CSV_MIME         = 'text/csv';

    const ALL_MIME              = '*/*';

    /**
     * Defined accepted MIME type for upload.
     * @return array array( <type1>, <type2>, <type3> ... )
     */
    abstract public function acceptMIMEType();

    /**
     * Get save path.
     * @param $fileHandler $_FILES item.
     * @return string e.g. /tmp/files/
     */
    abstract public function savePath($fileHandler);

    /**
     * Handle of each other file name generation for saved.
     * @param $fileHandler $_FILES item.
     * @return string e.g. test.jpg
     */
    abstract public function saveName($fileHandler);

    /**
     * On received upload file hook.
     * @param $fileHandler $_FILES item.
     */
    public function onReceived(&$fileHandler) {
        // Default do nothing
    }

    /**
     * On saved upload file hook.
     * @param $fileHandler $_FILES item.
     */
    public function onSaved(&$fileHandler, &$result) {
        // Default do nothing
    }

    /**
     * Check file's MIME tpye is accept.
     * @param $fileHandler $_FILES item.
     * @return bool
     */
    public function isAcceptMIME($fileHandler) {
        $acceptList = $this->acceptMIMEType();

        if(!is_array($acceptList)) {
            throw new UploadException("Invalid accept MIME type list from method [acceptMIMEType()]");
        }

        foreach($acceptList as $index => $acceptList) {
            if(($fileHandler["type"] == $acceptList) ||
                ($acceptList == self::ALL_MIME)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Receiving upload files
     * @return array
     */
    public function receive(){

        if(empty($_FILES)) {
            throw new UploadException("Can not received file data.");
        }

        $result = array();
        foreach ($_FILES as $key => $file) {

            $this->onReceived($file);

            if(!$this->isAcceptMIME($file)) {
                throw new UploadException("Not accept the MIME type [" . $file["type"] . "]");
            }

            $name           = $this->saveName($file);
            $path           = $this->savePath($file);
            $currentPath    = $path . $name;
            $tempPath       = $file["tmp_name"];
            $saveResult     = move_uploaded_file($tempPath, $currentPath);

            if(!$saveResult) {
                throw new UploadException("Can not save the file [$tempPath] to [$currentPath]");
            }

            $result[ $key ] = array(
                "originName" => $file['name'],
                "fileName" => $name,
                "type" => $file['type'],
                "size" => $file["size"],
                "error" => $file["error"],
            );

            $this->onSaved($file, $result[$key]);
        }

        return $result;
    }
}


?>
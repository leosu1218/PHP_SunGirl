<?php
require_once( FRAMEWORK_PATH . 'system/collections/PermissionDbCollection.php' );
require_once( FRAMEWORK_PATH . 'models/IMediaEventRaw.php' );
require_once( FRAMEWORK_PATH . 'collections/IMediaEventRaw/SearchUserIdWithBoxId.php' );
require_once( FRAMEWORK_PATH . 'collections/IMediaEventRaw/SearchBoxIdWithCamId.php' );

/**
 *	class IMediaEventRawCollection
 *
 *	PHP version 5.3
 *
 *	@category Collection
 *	@package Permission
 *	@author Rex chen <rexchen@synctech.ebiz.tw>
 *	@copyright 2015 synctech.com
 */
class IMediaEventRawCollection extends PermissionDbCollection {


    /**
     * Append search condition's statement for search records sql.
     *
     * @param DbHero $dao  The data access object want to set statements.
     * @param $params array SQL's params (reference PDO)
     * @param $conditions array  SQL's condition statements.
     * @param $select array SQL's select fields.
     * @param $search array Search value and params.
     */
    public function appendStatements(DbHero &$dao, &$params, &$conditions, &$select, &$search, &$sqlStatements) {
        foreach ($sqlStatements as $key => $statement) {
            $statement->append($dao, $params, $conditions, $select, $search);
        }
    }

    /**
     * @param $pageNo
     * @param $pageSize
     * @param array $search
     * @param $joinStatement
     * @param $searchConditions
     * @return array
     * @throws Exception
     * @throws InvalidAccessParamsException
     */
    public function executeGetRecords( $pageNo, $pageSize, $search=array(), &$joinStatement, &$searchConditions ) {
        $result     = $this->getDefaultRecords($pageNo, $pageSize);
        $table      = $this->getTable();
        $conditions = array('and','1=1');
        $params     = array();
        $select     = array();

        $this->dao->fresh();
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $joinStatement);
        $this->appendStatements($this->dao, $params, $conditions, $select, $search, $searchConditions);

        $this->dao->from("$table ier");
        $this->dao->where($conditions,$params);
        $this->dao->select($select);

        $result['recordCount'] = intval($this->dao->queryCount());
        $result["totalPage"] = intval(ceil($result['recordCount'] / $pageSize));

        $this->dao->paging($pageNo, $pageSize);
        $result["records"] = $this->dao->queryAll();

        return $result;
    }

    /**
     * Get box id list by user's id.
     * @param $id int User id
     * @param $pageNo int Page number
     * @param $pageSize int Page Size
     * @return array
     */
    public function getBoxListByUserId($id, $pageNo, $pageSize) {
        $joinStatements = array();
        $searchStatements = array(new SearchUserIdWithBoxId());
        $search = array("userId" => $id);
        return $this->executeGetRecords($pageNo, $pageSize, $search, $joinStatements, $searchStatements);
    }

    /**
     * Get cam id list by box id under the user id.
     * @param $id int User id
     * @param $boxId int Box id
     * @param $pageNo int Page number
     * @param $pageSize int Page Size
     * @return array
     */
    public function getCamListByBoxId($id, $boxId, $pageNo, $pageSize) {
        $joinStatements = array();
        $searchStatements = array(new SearchBoxIdWithCamId());
        $search = array("userId" => $id, "boxId" => $boxId);
        return $this->executeGetRecords($pageNo, $pageSize, $search, $joinStatements, $searchStatements);
    }

    /**
     * Get datetime string from CSV field e.g. Y2015082015275412 to 2015-08-20 15:27:54
     * @param $field
     * @return string
     */
    public function getDateTimeFromCSVField($field) {
        $pattern = '/Y(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
        preg_match($pattern, $field, $matches, PREG_OFFSET_CAPTURE);

        if(count($matches) == 8) {
            $year = $matches[1][0];
            $month = $matches[2][0];
            $day = $matches[3][0];
            $hour = $matches[4][0];
            $minute = $matches[5][0];
            $second = $matches[6][0];
            $minSecond = $matches[7][0];
            return "$year-$month-$day $hour:$minute:$second";
        }
        else {
            throw new DataAccessResultException("Invalid datetime format csv field.");
        }
    }

    /**
     * Convert csv file to array.
     * @param string $currentPath
     * @param string $delimiter
     * @throws DataAccessResultException
     * @throws OperationConflictException
     * @return array
     */
    function csvToArray($currentPath='', $rawFileId=0, $delimiter=',') {
        $data = array();
        if(!file_exists($currentPath) || !is_readable($currentPath)) {
            throw new DataAccessResultException("Can't read CSV file [$currentPath]");
        }

        $handle = @fopen($currentPath, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $buffer = str_replace(" ", "", trim($buffer));
                $buffer = mb_convert_encoding($buffer, "UTF-8", "cp950,gb2312,gbk,UTF-8");
                $rowArray = explode($delimiter,  $buffer);
                if(count($rowArray) != 13) {
                    throw new DataAccessResultException("Invalid CSV file format. Fields count should be 13.");
                }

                $rowArray[3] = $this->getDateTimeFromCSVField($rowArray[3]);
                $rowArray[4] = $this->getDateTimeFromCSVField($rowArray[4]);
                $rowArray[] = $rawFileId;
                $data[] = $rowArray;
            }
            if (!feof($handle)) {
                throw new DataAccessResultException("Unexpected file read fail.");
            }

            fclose($handle);
        }
        return $data;
    }

    /**
     * Create records by csv file.
     * @param string $currentPath
     * @param int $rawFileId
     * @return bool
     * @throws AuthorizationException
     */
    public function createByCSV($currentPath='', $rawFileId=0) {
        $records = $this->csvToArray($currentPath, $rawFileId);
        $attributes = array(
            "raw_serial",
            "box_id",
            "cam_id",
            "start_datetime",
            "end_datetime",
            "watching_timer",
            "gender",
            "years_old_type",
            "ots_id",
            "free_col1",
            "free_col2",
            "free_col3",
            "face_distance",
            "raw_file_id",
        );

        $rowCount = $this->multipleCreate($attributes, $records);
        if(count($records) != $rowCount) {
            throw new DataAccessResultException("Create records error.");
        }

        return $rowCount;
    }

    /* DbCollection abstract methods. */

    /**
     *	Get the entity table name.
     *
     *	@return string
     */
    public function getTable() {
        return "imedia_event_raw";
    }

    public function getModelName() {
        return "IMediaEventRaw";
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

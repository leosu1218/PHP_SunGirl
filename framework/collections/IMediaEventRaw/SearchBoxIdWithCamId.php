<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class SearchIds
 * Append activities id list.
 */
class SearchBoxIdWithCamId extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        if(array_key_exists('userId', $search) && array_key_exists('boxId', $search)) {
            $dao->leftJoin('raw_file rf ', 'ier.raw_file_id=rf.id');
            array_push($select, 'ier.cam_id camId');

            array_push($conditions, 'rf.user_id=:userId');
            $params[':userId'] = $search['userId'];

            array_push($conditions, 'ier.box_id=:boxId');
            $params[':boxId'] = $search['boxId'];

            $dao->group('ier.cam_id');
        }
        else {
            throw new DbOperationException("Missing required search condition [userId] or [boxId].");
        }
    }
}
?>
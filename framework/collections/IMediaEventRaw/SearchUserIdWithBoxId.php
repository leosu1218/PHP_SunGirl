<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );

/**
 * Class SearchIds
 * Append activities id list.
 */
class SearchUserIdWithBoxId extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        if(array_key_exists('userId', $search)) {
            $dao->leftJoin('raw_file rf ', 'ier.raw_file_id=rf.id');
            array_push($select, 'ier.box_id boxId');
            array_push($conditions, 'rf.user_id=:userId');
            $params[':userId'] = $search['userId'];
            $dao->group('ier.box_id');
        }
        else {
            throw new DbOperationException("Missing required search condition [userId].");
        }
    }
}
?>
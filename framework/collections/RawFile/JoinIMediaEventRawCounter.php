<?php
require_once( dirname(__FILE__) . "/../JoinStatement.php" );
require_once( FRAMEWORK_PATH . 'extends/DatetimeHelper.php' );

/**
 * Class SearchIds
 * Append activities id list.
 */
class JoinIMediaEventRawCounter extends JoinStatement {
    /**
     * Append JOIN SQL statement of Master info to DAO.
     *
     * @param DbHero $dao Db access object.
     * @param $params array Condition params.
     * @param $conditions array Condition statement.
     * @param $select array Select statement.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        if(array_key_exists('startDate', $search) && array_key_exists('endDate', $search)) {

            $startDate  = (new DateTime( $search['startDate'] ))->format('Y-m-d H:i:s');
            $endDate = DatetimeHelper::afterDate($search["endDate"], 1);
            $endDate = (new DateTime( $endDate ))->format('Y-m-d H:i:s');

            $subQuery = '(SELECT * FROM imedia_event_raw WHERE ';

            if(array_key_exists('boxId', $search)) {
                $subQuery .= "box_id=:boxId AND ";
                $params[':boxId'] = $search['boxId'];
            }

            if(array_key_exists('camId', $search)) {
                $subQuery .= "cam_id=:camId AND ";
                $params[':camId'] = $search['camId'];
            }

            $subQuery .= '1=1 GROUP BY ots_id ORDER BY start_datetime ASC) ier';

            $subTable = "(SELECT
                        COUNT(ier.years_old_type) count,
                        ier.years_old_type years_old_type,
                        ier.raw_file_id raw_file_id,
                        ier.gender gender
                    FROM
                        $subQuery
                    WHERE
                        (start_datetime BETWEEN '$startDate' AND '$endDate')
                        AND (ier.gender=1 OR ier.gender=2)
                    GROUP BY
                        ier.years_old_type, ier.gender, ier.raw_file_id
                    ) ier";

            $dao->rightJoin($subTable, 'ier.raw_file_id=rf.id');
            $dao->group('label');

            array_push($select, 'SUM(ier.count) value');
            array_push($select, 'CONCAT(\'y:\', ier.years_old_type, \', g:\', ier.gender) label');

        }
        else {
            throw new DbOperationException("Missing required search condition [startDate].");
        }
    }
}
?>
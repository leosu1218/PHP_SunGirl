<?php
require_once( dirname(__FILE__) . "/../ConditionStatement.php" );

/**
 * Class SearchGeneralActivityIds
 */
class SearchStandardCasePageClassification  extends ConditionStatement {
    /**
     * Append search condition statement to dao.
     *
     * @param DbHero $dao  The data access object want to set statements.
     * @param $params array SQL's params (reference PDO)
     * @param $conditions array  SQL's condition statements.
     * @param $select array SQL's select fields.
     * @param $search array Search value and params.
     */
    public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search) {
        if(array_key_exists('classification', $search)) {
            if($search['classification'] != 'all'){
                array_push($conditions, 's.classification=:classification');
                $params[':classification'] = $search['classification'];
            }
        }
    }
}
?>
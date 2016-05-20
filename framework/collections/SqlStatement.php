<?php

/**
 * Abstract class SearchCondition
 */
abstract class SqlStatement {
    /**
     * Append search condition statement to dao.
     *
     * @param DbHero $dao  The data access object want to set statements.
     * @param $params array SQL's params (reference PDO)
     * @param $conditions array  SQL's condition statements.
     * @param $select array SQL's select fields.
     * @param $search array Search value and params.
     */
    // abstract public function append(DbHero &$dao, &$params, &$conditions, &$select, &$search);
}



?>
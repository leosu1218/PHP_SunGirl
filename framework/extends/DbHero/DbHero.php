<?php 

/**
*	A base on PDO module tool that for simple db query function.
*	the module help developer coding flexibility, speed with db query. 
*
*	@author Rex Chen March 30 2014
*	@version 1.0
*/
 
class DbHero
{

	/**
	*	PDO connection.
	*
	*/
	private $_conn;

    private $transactionCounter = 0;

	/**
	*	db sentence.
	*
	*/
	private $_stmt;

	/**
	*	select sentence
	*
	*/
	private $_select;

	/**
	*	from sentence
	*
	*/
	private $_from;

	/**
	*	where sentence
	*
	*/
	private $_where;

	/**
	*	like sentence
	*
	*/
	private $_like;	

	/**
	*	join sentence
	*
	*/
	private $_join;

	/**
	*	left join sentence
	*
	*/
	private $_leftJoin;

	/**
	*	right sentence
	*
	*/
	private $_rightJoin;

	/**
	*	limit sentence
	*
	*/
	private $_limit;

	/**
	*	offset sentence
	*
	*/
	private $_offset;

	/**
	*	order sentence
	*
	*/
	private $_order;

	/**
	*	group sentence
	*
	*/
	private $_group;

	/**
	*	having sentence
	*
	*/
	private $_having;


	private $lastInsertId = -1;

	/**
	*	initial by database name.
	*
	*
	*/
	public function __construct( $select ) {
		try {
			$config = $this->_config();
            if(is_string($select)) {
                if(!is_array($config)) {
                    throw new Exception("Invalid config.", 1);
                }

                if(!isset($config[$select])) {
                    throw new Exception("[$select] configuration not found!", 1);
                }

                if(is_array($config[$select])) {
                    $this->_conn = new PDO( $config[ $select ][0], $config[ $select ][1], $config[ $select ][2] );
                }
                else if($config[$select]  instanceof PDO) {
                    $this->_conn = $config[$select];
                }
                else {
                    throw new Exception("Invalid instance type of [$select] configuration.", 1);
                }
            }
            else if($select instanceof PDO) {
                $this->_conn = $select;
            }
            else {
                throw new Exception("Invalid input of DbHero");
            }

			$this->_conn->query( "SET NAMES 'UTF8'" );

			// initial sentence.
			$this->_select 		= array( '*', array() );
			$this->_from 		= array( null, array() );
			$this->_where 		= array( null, array() );
			$this->_like        = null;
			
			// table, conditions, params
			$this->_join 		= array( array(), array(), array() );
			$this->_leftJoin 	= array( array(), array(), array() );
			$this->_rightJoin 	= array( array(), array(), array() );

			$this->_limit 		= null;
			$this->_offset 		= null;
			$this->_order		= null;
			$this->_group		= null;
			$this->_having		= null;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->getMessage(), 1);	  
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage(), 1);
		}
	}

	/**
	*	config connection datas.
	*
	*
	*/
	protected function _config()
	{
		return array(
			
		);
	}

	/**
	*	get sql by sql builder.
	*
	*	@return string sql sentence.
	*/
	public function sql()
	{
		return $this->_sqlQueryBuilder();
	}

    /**
     * Get error information.
     * @return array
     */
    public function errorInfo() {
        return $this->_conn->errorInfo();
    }

    /**
     * Begin transaction mode.
     */
    public function transaction() {
        if(!$this->transactionCounter++) {
            return $this->_conn->beginTransaction();
        }
        return $this->transactionCounter >= 0;
    }

    /**
     * @return bool
     */
    function commit() {
        if(!--$this->transactionCounter) {
            return $this->_conn->commit();
        }
        return $this->transactionCounter >= 0;
    }

    /**
     * @return bool
     */
    function rollback() {
        if($this->transactionCounter >= 0)  {
            $this->transactionCounter = 0;
            return $this->_conn->rollback();
        }
        $this->transactionCounter = 0;
        return false;
    }

    /**
	*	building query sql command.
	*
	*	@return string sql sentence.
	*/
	private function _sqlQueryBuilder( $select = null, $from = null, $where = null, $limit = null, $offset = null, $join = null, $leftJoin = null, $rightJoin = null, $order = null, $group = null, $like = null, $having = null )
	{
		// initial part of command sentence.
		// set instance data when undefine other command.
		if( is_null($select) )
			$select = $this->_select;

		if( is_null($from) )
			$from = $this->_from;

		if( is_null($where) )
			$where = $this->_where;

		if( is_null($like) )
			$like = $this->_like;

		if( is_null($limit) )
			$limit = $this->_limit;

		if( is_null($offset) )
			$offset = $this->_offset;

		if( is_null($join) )
			$join = $this->_join;

		if( is_null($leftJoin) )
			$leftJoin = $this->_leftJoin;

		if( is_null($rightJoin) )
			$rightJoin = $this->_rightJoin;

		if( is_null($order) )
			$order = $this->_order;
		if( is_null($group) )
			$group = $this->_group;
		if( is_null($having) )
			$having = $this->_having;

		// extend select sentence.
		$sql = 	'SELECT ' . $select[0] ;

		// extend from sentence.
		if( !is_null($from[0]) )
			$sql .= ' FROM ' . $from[0];
		else
			throw new Exception("Missing from sql parameters to query.", 1);

		// extend join sentence, join[0] = table, join[1] = condition.
		if( ( count( $join[0] ) > 0 ) && ( count( $join[0] ) ==  count( $join[1] ) ) )
		{
			foreach( $join[0] as $k => $v )
				$sql .= ' JOIN ' . $v . ' ON ' . $join[1][$k];
		}

		// extend left join sentence, leftJoin[0] = table, leftJoin[1] = condition.
		if( ( count( $leftJoin[0] ) > 0 ) && ( count( $leftJoin[0] ) ==  count( $leftJoin[1] ) ) )
		{
			foreach( $leftJoin[0] as $k => $v )
				$sql .= ' LEFT JOIN ' . $v . ' ON ' . $leftJoin[1][$k];
		}

		// extend right join sentence, rightJoin[0] = table, rightJoin[1] = condition.
		if( ( count( $rightJoin[0] ) > 0 ) && ( count( $rightJoin[0] ) ==  count( $rightJoin[1] ) ) )
		{
			foreach( $rightJoin[0] as $k => $v )
				$sql .= ' RIGHT JOIN ' . $v . ' ON ' . $rightJoin[1][$k];
		}

		// extend where sentence.
		if( !is_null($where[0]) )
			$sql .=	' WHERE ' . $where[0] ; 

		// extend where sentence.
		if( is_string($like) )
			$sql .=	$like ; 		

		// extend where sentence.
		if( is_string($group) )
			$sql .=	' GROUP BY ' . $group ; 

		// extend having sentence.
		if( is_string($having) )
			$sql .=	' HAVING ' . $having ; 

		// extend where sentence.
		if( is_string($order) )
			$sql .=	' ORDER BY ' . $order ; 

		// extend limit sentence.
		if( is_int($limit) || is_numeric($limit) )
		{
			$sql .=	' LIMIT ';

			if( is_int($offset) || is_numeric($offset) )
				$sql .= $offset . ',';

			$sql .=	$limit;
		}

		return $sql;
	}

	/**
	*	add select condition.
	*
	*	@param mixed $condition
	*	@param array $params sql bind value params.
	*/
	public function select( $condition , $params = array() )
	{
		// string setting mode.
		if( is_string( $condition ) )
		{
			$this->_select[0] = $condition;
		}
		// array setting mode.
		else if( is_array( $condition ) )
		{
			// combine select fields.
			$select = '';
			foreach( $condition as $name => $value )
				$select .= $value . ",";

			$this->_select[0] = substr( $select, 0, -1 );	
		}
		else
		{
			throw new Exception("Unsupport the select condition", 1);			
		}		

		$this->_select[1] = $params;	
	}

	/**
	*	add from condition.
	*
	*	@param mixed $condition
	*	@param array $params sql bind value params.
	*/
	// public function from( $condition, $params = array() )
	public function from( $condition )
	{
		// string setting mode.
		if( is_string( $condition ) )
		{
			$this->_from[0] = $condition;
		}
		// array setting mode.
		else if( is_array( $condition ) )
		{
			// combine select fields.
			$from = '';
			foreach( $condition as $name => $value )
				$from .= $value . ",";

			$this->_from[0] = substr( $from, 0, -1 );	
		}
		else
		{
			throw new Exception("Unsupport the from condition", 1);			
		}

		// $this->_from[1] = $params;	
	}

	/**
	*	Append extends where statements
	*
	*	@param mixed $condition
	*	@param array $params sql bind value params.
	*/
	public function appendWhere($condition = array(), $params = array() ) {

		// array setting mode.
		if( is_array( $condition ) )
		{
			// combine select fields.
			// operator 'and', 'or' etc..
			$op		= ' ' . array_shift( $condition ) . ' ';
			$where 	= '';

			foreach( $condition as $name => $value )			
				$where .= $op . $value;				

			// substr operator of end of sentence.
			$this->_where[0] .= $where;	
		}
		else
		{
			throw new Exception("Unsupport the where condition", 1);			
		}

		$this->_where[1] = array_merge($this->_where[1], $params);	
	}

	/**
	*	add where condition.
	*
	*	@param mixed $condition
	*	@param array $params sql bind value params.
	*/
	public function where( $condition , $params = array() )
	{
		// string setting mode.
		if( is_string( $condition ) )
		{
			$this->_where[0] = $condition;
		}
		// array setting mode.
		else if( is_array( $condition ) )
		{
			// combine select fields.
			// operator 'and', 'or' etc..
			$op		= ' ' . array_shift( $condition ) . ' ';
			$where 	= '';

			foreach( $condition as $name => $value )			
				$where .= $value . $op;				

			// substr operator of end of sentence.
			$this->_where[0] = substr( $where, 0, (strlen($op) * -1) );	
		}
		else
		{
			throw new Exception("Unsupport the where condition", 1);			
		}

		$this->_where[1] = $params;	
	}

	/**
	*	add having condition.
	*
	*	@param mixed $condition
	*	@param array $params sql bind value params.
	*/
	public function having( $condition , $params = array() )
	{
		// TODO completed
		$this->_having = $condition;	
	}

	public function like( $sentence )
	{
		if( is_string($sentence))
		{
			$this->_like = $sentence;
		}
		else
		{
			throw new Exception("Unsupport the like condition", 1);
		}	
	}

	/**
	*	add limit condition.
	*
	*	@param int $limit number of query limit
	*	@param int $offset number of query offset
	*/
	public function limit( $limit, $offset = null )
	{
		if( is_int($limit) || is_numeric($limit) )
			$this->_limit = $limit;
		else
			throw new Exception("limit only allow integer or numeric string.", 1);
			
		if( is_int($offset) || is_numeric($offset) )
			$this->_offset = $offset;
	}

	/**
	*	add limit condition.
	*
	*	@param int $offset number of query offset
	*/
	public function offset( $offset )
	{
		if( is_int($offset) || is_numeric($offset) )
			$this->_offset = $offset;
		else
			throw new Exception("offset only allow integer or numeric string.", 1);
	}

	/** 
	*	add join condition.
	*
	*	@param mixed $table
	*	@param mixed $condition
	*	@param array $params sql bind value params.
	*/
	public function  join( $table, $condition , $params = array() )
	{
		// store table info.
		if( is_string( $table ) )
			$this->_join[0][] = $table;
		// else if( is_array( $table ) )			
		// 	$this->_join[0] = array_merge( $this->_join[0], $table );
		else
			throw new Exception("Unsupport the join table", 1);			


		// store table info.
		if( is_string( $condition ) )
			$this->_join[1][] = $condition;
		// else if( is_array( $condition ) )			
		// 	$this->_join[1] = array_merge( $this->_join[1], $condition );
		else
			throw new Exception("Unsupport the join condition", 1);			
		

		$this->_join[2] = $params;	
	}

	/** 
	*	add left join condition.
	*
	*	@param mixed $table
	*	@param mixed $condition
	*	@param array $params sql bind value params.
	*/
	public function leftJoin( $table, $condition , $params = array() )
	{
		// store table info.
		if( is_string( $table ) )
			$this->_leftJoin[0][] = $table;
		// else if( is_array( $table ) )			
		// 	$this->_leftJoin[0] = array_merge( $this->_leftJoin[0], $table );
		else
			throw new Exception("Unsupport the left join table", 1);			


		// store table info.
		if( is_string( $condition ) )
			$this->_leftJoin[1][] = $condition;
		// else if( is_array( $condition ) )			
		// 	$this->_leftJoin[1] = array_merge( $this->_leftJoin[1], $condition );
		else
			throw new Exception("Unsupport the left join condition", 1);			
		

		$this->_leftJoin[2] = $params;	
	}

	/** 
	*	add right join condition.
	*
	*	@param mixed $table
	*	@param mixed $condition
	*	@param array $params sql bind value params.
	*/
	public function rightJoin( $table, $condition , $params = array() )
	{
		// store table info.
		if( is_string( $table ) )
			$this->_rightJoin[0][] = $table;
		// else if( is_array( $table ) )			
		// 	$this->_rightJoin[0] = array_merge( $this->_rightJoin[0], $table );
		else
			throw new Exception("Unsupport the right join table", 1);			


		// store table info.
		if( is_string( $condition ) )
			$this->_rightJoin[1][] = $condition;
		// else if( is_array( $condition ) )			
		// 	$this->_rightJoin[1] = array_merge( $this->_rightJoin[1], $condition );
		else
			throw new Exception("Unsupport the right join condition", 1);			
		

		$this->_rightJoin[2] = $params;	
	}

	/**
	*	add order condition.
	*
	*	@param string $condition the order condition.
	*/
	public function order( $condition )
	{
		if( is_string( $condition ) )
			$this->_order = $condition;
		else
			throw new Exception("Unsupport the order condition input type.", 1);
			
	}

	/**
	*	add order condition.
	*
	*	@param string $condition the order condition.
	*/
	public function group( $condition )
	{
		if( is_string( $condition ) )
			$this->_group = $condition;
		else
			throw new Exception("Unsupport the group condition input type.", 1);
			
	}

	/**
	*	paging rows by limit sentence.
	*
	*/
	public function paging( $pageno, $pagesize )
	{
		if( (is_int($pageno) || is_numeric($pageno)) && (is_int($pagesize) || is_numeric($pagesize)) )
		{
			$this->_offset = ( $pageno - 1 ) * $pagesize;
			$this->_limit = $pagesize;
		}
		else
			throw new Exception("pagesize and pageno only allow integer or numeric string.", 1);		
	}

	/**
	*	prepare sql.
	*
	*	@param string $sql sql sentence.
	*/
	public function prepare( $sql )
	{
		$this->_stmt = $this->_conn->prepare( $sql );		
	}

	/**
	*	execute sql.
	*
	*	@param array $params sql bind value params.
	*/
	public function executeQuery( $params = array() )
	{
		// prepare sql sentence.
		$sql = $this->_sqlQueryBuilder();
		$this->prepare( $sql );

		// bind all sentence value.
		if( is_array($params) )
		{
			$params = array_merge( $params, $this->_select[1] );
			$params = array_merge( $params, $this->_where[1] );
			$params = array_merge( $params, $this->_join[2] );
			$params = array_merge( $params, $this->_leftJoin[2] );
			$params = array_merge( $params, $this->_rightJoin[2] );
		}

		$this->bindValue( $params );
		
		// execute the sentence.
		$this->_stmt->execute();
	}

	/**
	*	bind value into the sql.
	*
	*	@param array $params sql bind value params.
	*/
	public function bindValue( $params = array(), $stmt = null ) 
	{
		// initial senetence object.
		if( is_null( $stmt ) )
			$stmt = $this->_stmt;

		if( is_array($params) )
		{
			foreach( $params as $key => $value )
			{				
				$stmt->bindValue( $key, $value );
			}
		}
		else
			throw new Exception("Invalid array object.", 1);		
	}

	/**
	*	query all rows
	*
	*	@param array $params sql bind value params.
	*	@return array
	*/
	public function queryAll( $params = array() )
	{
		$this->executeQuery( $params );

		$record = array();
		while( $row = $this->_stmt->fetch( PDO::FETCH_ASSOC ) )
		{
			$record[] = $row;
		}

		return $record;
	}

	/**
	*	query a row.
	*
	*	@param array $params sql bind value params.
	*	@return array
	*/
	public function query( $params = array() )
	{
		$this->executeQuery( $params );

		$record = array();
		if( $row = $this->_stmt->fetch( PDO::FETCH_ASSOC ) )
		{
			$record = $row;
		}

		return $record;
	}

	/**
	*	query all rows counter.
	*
	*/
	public function queryCount( $params = array() )
	{
		
		// prepare sql sentence with select counter.
		$sql = $this->_sqlQueryBuilder( 
			// select
			// array( 'count(*) counter' ),	
			null,
			// from
			null,
			// where
			null,
			// limit
			'null',
			// offset
			'null',
			// join
			null,
			// leftJoin
			null,
			// rightJoin
			null,
			// order
			null
			// group
			// false,
			//like
			// null 
		);		

		$stmt = $this->_conn->prepare( $sql );

		// merge all sentence value.
		if( is_array($params) )
		{
			$params = array_merge( $params, $this->_where[1] );	
			$params = array_merge( $params, $this->_join[2] );
			$params = array_merge( $params, $this->_leftJoin[2] );
			$params = array_merge( $params, $this->_rightJoin[2] );		
		}

		// bind value into the sentence.
		$this->bindValue( $params, $stmt );
		
		// execute the sentence.
		$stmt->execute();
		$counter = $stmt->rowCount();		
		return $counter;		
	}

	/**
	 * 刪除資料庫中的資料
	 * 
	 * @param  [type] $table
	 * @param  [type] $where
	 * @param  array  $params
	 * @return [type]
	 */
	public function delete( $table, $where, $params = array() ) {

		$sql = '';

		// extend table sentence.
		if( is_string( $table ) )
		{
			$sql .= "DELETE FROM `$table` "; 
		}
		else
			throw new Exception("Unsupport the table input data type.", 1);

		// extend where sentence.
		$sql .= ' WHERE ';
		if( is_array( $where ) )
		{
			// combine select fields.
			// operator 'and', 'or' etc..
			$op		= ' ' . array_shift( $where ) . ' ';

			foreach( $where as $name => $value )			
				$sql .= "$value" . $op;				

			// substr operator of end of sentence.
			$sql = substr( $sql, 0, (strlen($op) * -1) );	
		}
		else if( is_string( $where ) )
		{
			$sql .= $where;
		}
		else
			throw new Exception("Unsupport the where input data type.", 1);

		// prepare sentence.
		$stmt = $this->_conn->prepare( $sql );
		$stmt->execute( $params );

		return $stmt->rowCount();
	}

	/**
	 * For test delete sql command.
	 * @param  string 	$table  	The table want to delet record.
	 * @param  mixed 	$where  	String or array, where condition for delete.
	 * @param  array  	$params 	Conditions parameters.
	 * @return string         		The sql will be execute.
	 */
	public function getDelete( $table, $where, $params = array() ) {

		$sql = '';

		// extend table sentence.
		if( is_string( $table ) )
		{
			$sql .= "DELETE FROM `$table` "; 
		}
		else
			throw new Exception("Unsupport the table input data type.", 1);

		// extend where sentence.
		$sql .= ' WHERE ';
		if( is_array( $where ) )
		{
			// combine select fields.
			// operator 'and', 'or' etc..
			$op		= ' ' . array_shift( $where ) . ' ';

			foreach( $where as $name => $value )			
				$sql .= "$value" . $op;				

			// substr operator of end of sentence.
			$sql = substr( $sql, 0, (strlen($op) * -1) );	
		}
		else if( is_string( $where ) )
		{
			$sql .= $where;
		}
		else
			throw new Exception("Unsupport the where input data type.", 1);

		// prepare sentence.
		return $sql;
	}

	/**
	*	update data into database
	*
	*
	*/
	public function update( $table, $setValues, $where, $params = array() )
	{
		$sql = '';

		// extend table sentence.
		if( is_string( $table ) )
		{
			$sql .= "UPDATE `$table` SET "; 
		}
		else
			throw new Exception("Unsupport the table input data type.", 1);

		// extend set fields/values sentence.
		if( is_array( $setValues ) )
		{
			foreach( $setValues as $name => $value )			
				$sql .= "`$name` = '$value',";

			// substr ',' char of end of sentence.
			$sql = substr( $sql, 0, -1 );	
		}
		else
			throw new Exception("Unsupport the set values input data type.", 1);

		// extend where sentence.
		$sql .= ' WHERE ';
		if( is_array( $where ) )
		{
			// combine select fields.
			// operator 'and', 'or' etc..
			$op		= ' ' . array_shift( $where ) . ' ';

			foreach( $where as $name => $value )			
				$sql .= "$value" . $op;				

			// substr operator of end of sentence.
			$sql = substr( $sql, 0, (strlen($op) * -1) );	
		}
		else if( is_string( $where ) )
		{
			$sql .= $where;
		}
		else
			throw new Exception("Unsupport the where input data type.", 1);

		// prepare sentence.
		$stmt = $this->_conn->prepare( $sql );
		$stmt->execute( $params );

		return $stmt->rowCount();	
	}

	public function getUpdate( $table, $setValues, $where, $params = array() )
	{
		$sql = '';

		// extend table sentence.
		if( is_string( $table ) )
		{
			$sql .= "UPDATE `$table` SET "; 
		}
		else
			throw new Exception("Unsupport the table input data type.", 1);

		// extend set fields/values sentence.
		if( is_array( $setValues ) )
		{
			foreach( $setValues as $name => $value )			
				$sql .= "`$name` = '$value',";

			// substr ',' char of end of sentence.
			$sql = substr( $sql, 0, -1 );	
		}
		else
			throw new Exception("Unsupport the set values input data type.", 1);

		// extend where sentence.
		$sql .= ' WHERE ';
		if( is_array( $where ) )
		{
			// combine select fields.
			// operator 'and', 'or' etc..
			$op		= ' ' . array_shift( $where ) . ' ';

			foreach( $where as $name => $value )			
				$sql .= "$value" . $op;				

			// substr operator of end of sentence.
			$sql = substr( $sql, 0, (strlen($op) * -1) );	
		}
		else if( is_string( $where ) )
		{
			$sql .= $where;
		}
		else
			throw new Exception("Unsupport the where input data type.", 1);

		return $sql;
	}


	/**
	*	執行一段sql語法
	*/
	public function runExecute( $sql, $params = array() ) {

		// prepare sentence.
		$stmt = $this->_conn->prepare( $sql );
		$stmt->execute( $params );

		return $stmt->rowCount();
	}

	/**
	*	執行一段sql查詢語法
	*/
	public function runQuery( $sql, $params = array() ) {

		$stmt = $this->_conn->prepare( $sql );

		// bind value into the sentence.
		$this->bindValue( $params, $stmt );
		
		// execute the sentence.
		$stmt->execute();

		$record = 0;

		while( $row = $this->_stmt->fetch( PDO::FETCH_ASSOC ) )
		{
			$record[] = $row;
		}

		return $record;
	}

	/**
	*	insert multiple data into database
	*
	*	@param $columns array Array('field1','field2','field3','field4')
	*	@param $values array Array(
	*							Array('value1-1','value1-2','value1-3','value1-4'),
	*							Array('value2-1','value2-2','value2-3','value2-4'),
	*							Array('value3-1','value3-2','value3-3','value3-4'),
	*						)
	*/
	public function multipleInsert( $table, $columns, $values ) {
		
		$sql = '';	
		$params = array();
		$fieldsCount = count($columns);

		// extend table sentence.
		if( is_string( $table ) )
		{
			$sql .= "INSERT INTO `$table`("; 
			
			$valueArgs = " (";
		}
		else
			throw new Exception("Unsupport the table input data type.", 1);

		// extend set fields/values sentence.
		if( is_array( $columns ) )
		{
			foreach( $columns as $index => $name )
			{				
				$sql .= "`$name`,";
				$valueArgs .= "?,";				
			}			
			
			// substr ',' char of end of sentence.
			$sql = substr( $sql, 0, -1 );
			$valueArgs = substr( $valueArgs, 0, -1 );			
		}
		else {			
			throw new Exception("Unsupport the columns input data type.", 1);
		}
		
		$sql = $sql . ')';
		$valueArgs = $valueArgs . ')';

		// extend values collection.
		// extend set fields/values sentence.
		if( is_array( $values ) )
		{
			$sql .= ' VALUES';
			$params = array();
			foreach( $values as $index => $value )
			{							
				$isValidValueCount = (count($value) == $fieldsCount);
				if($isValidValueCount) {
					$sql .= $valueArgs . ",";
					foreach ($value as $key => $arg) {
						array_push($params, $arg);	
					}
				}
				else {
					throw new Exception("Invalid value args count.", 1);					
				}				
			}			
			
			// substr ',' char of end of sentence.
			$sql = substr( $sql, 0, -1 );			
		}
		else {			
			throw new Exception("Unsupport the values input data type.", 1);
		}
		
		$sql .= '';
			
		$stmt = $this->_conn->prepare( $sql );
		$stmt->execute( $params );
		return $stmt->rowCount();
	}

	/**
	*	insert data into database
	*
	*
	*/
	public function insert( $table, $columns )
	{
		$sql = '';
		$values = '';
		$params = array();

		// extend table sentence.
		if( is_string( $table ) )
		{
			$sql .= "INSERT INTO `$table`("; 
			$values .= " VALUES( ";
		}
		else
			throw new Exception("Unsupport the table input data type.", 1);

		// extend set fields/values sentence.
		if( is_array( $columns ) )
		{
			foreach( $columns as $name => $value )
			{
				$sql .= "`$name`,";
				$values .= ":$name,";
				$params[ ":$name" ] = $value;
			}			
			
			// substr ',' char of end of sentence.
			$sql = substr( $sql, 0, -1 );
			$values = substr( $values, 0, -1 );
		}
		else
			throw new Exception("Unsupport the columns input data type.", 1);

		$sql = $sql . ')' . $values . ')';
		
		
		// prepare sentence.
		$stmt = $this->_conn->prepare( $sql );
		$stmt->execute( $params );

		$this->lastInsertId = $this->_conn->lastInsertId();

		return $stmt->rowCount();	
	}

	/**
	*	Get mutiple insert statments.
	*
	*	@return string Statment of insert.
	*/
	public function getMultipleInsert($table, $columns, $values) {
		$sql = '';	
		$params = array();
		$fieldsCount = count($columns);

		// extend table sentence.
		if( is_string( $table ) )
		{
			$sql .= "INSERT INTO `$table`("; 
			
			$valueArgs = " (";
		}
		else
			throw new Exception("Unsupport the table input data type.", 1);

		// extend set fields/values sentence.
		if( is_array( $columns ) )
		{
			foreach( $columns as $index => $name )
			{				
				$sql .= "`$name`,";
				$valueArgs .= "?,";				
			}			
			
			// substr ',' char of end of sentence.
			$sql = substr( $sql, 0, -1 );
			$valueArgs = substr( $valueArgs, 0, -1 );			
		}
		else {			
			throw new Exception("Unsupport the columns input data type.", 1);
		}
		
		$sql = $sql . ')';
		$valueArgs = $valueArgs . ')';

		// extend values collection.
		// extend set fields/values sentence.
		if( is_array( $values ) )
		{
			$sql .= ' VALUES';
			$params = array();
			foreach( $values as $index => $value )
			{							
				$isValidValueCount = (count($value) == $fieldsCount);
				if($isValidValueCount) {
					$sql .= $valueArgs . ",";
					foreach ($value as $key => $arg) {
						array_push($params, $arg);	
					}
				}
				else {
					throw new Exception("Invalid value args count.", 1);					
				}				
			}			
			
			// substr ',' char of end of sentence.
			$sql = substr( $sql, 0, -1 );			
		}
		else {			
			throw new Exception("Unsupport the values input data type.", 1);
		}
		
		$sql .= '';
				
		return $sql;
	}

	public function getSql()
	{
		return $this->_sqlQueryBuilder();
	}
	
	public function getInsert( $table, $columns )
	{
		$sql = '';
		$values = '';
		$params = array();

		// extend table sentence.
		if( is_string( $table ) )
		{
			$sql .= "INSERT INTO `$table`("; 
			$values .= " VALUES( ";
		}
		else
			throw new Exception("Unsupport the table input data type.", 1);

		// extend set fields/values sentence.
		if( is_array( $columns ) )
		{
			foreach( $columns as $name => $value )
			{
				$sql .= "`$name`,";
				$values .= ":$name,";
				$params[ ":$name" ] = $value;
			}			
			
			// substr ',' char of end of sentence.
			$sql = substr( $sql, 0, -1 );
			$values = substr( $values, 0, -1 );
		}
		else
			throw new Exception("Unsupport the columns input data type.", 1);

		$sql = $sql . ')' . $values . ')';
		
		return $sql;
	}	

	/**
	*	取得最後1次insert指令完成時所存取的id
	*
	*	@return int 最後insert的row id
	*/
	public function lastInsertId() {

		if( $this->lastInsertId > 0 ) {
			return $this->lastInsertId;
		} 
		else {
			throw new Exception("Not set last insert id.", 1);
		}
	}

	/**
	*	重置所有sql動作的指令暫存
	*
	*/
	public function fresh() {
		
		// initial sentence.
		$this->_select 		= array( '*', array() );
		$this->_from 		= array( null, array() );
		$this->_where 		= array( null, array() );
			
		// table, conditions, params
		$this->_join 		= array( array(), array(), array() );
		$this->_leftJoin 	= array( array(), array(), array() );
		$this->_rightJoin 	= array( array(), array(), array() );

		$this->_limit 		= null;
		$this->_offset 		= null;
		$this->_order		= null;
		$this->_group		= null;
	}

}





?>
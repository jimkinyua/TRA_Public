<?php
	
	class Sql_server
	{
		private $serverName;
		private $connectionInfo;
		private $conn;
		private $params;
		private $last_query;
		private $prep_sql;
		private $stmt;
		private $row_count;
		private $affected_rows;
		private $rs;
		private $errors;
		
		public function __construct()
		{
			$this->row_count = 0;
			$this->affected_rows = 0;
			$this->last_query= NULL;
			$this->prep_sql = NULL;
			
			$this->serverName = "VUGREVENUE"; //serverName\instanceName
			$this->connectionInfo = array( "Database"=>"COUNTYREVENUE", "UID"=>"ugrev", "PWD"=>"ugrev");
			//$this->connectionInfo = array( "Database"=>"COUNTYREVENUE", "UID"=>"sa", "PWD"=>"123456");
			$this->conn = sqlsrv_connect( $this->serverName, $this->connectionInfo);
			 
			if($this->conn) {
				 //echo "Connection established.<br />";
			}else{
				 echo "Connection could not be established.<br />";
				 die( print_r( sqlsrv_errors(), true));
			}
		}
		
		public function __destruct()
		{
			sqlsrv_close($this->conn);
		}
		
		public function resultset_has_rows()
		{
			return ($this->row_count > 0);
		}
		
		public function get_row_count()
		{
			return $this->row_count;
		}
		
		public function get_affected_rows()
		{
			return $this->affected_rows;
		}
		
		public function get_last_query()
		{
			return $this->last_query;
		}
		
		// returns the next item in the result set
		public function next()
		{
			if($this->rs != NULL && $this->get_row_count() > 0)
			{
				// return the next row
			}
			
			// no rows in the resultset
			return NULL;
		}
		
		public function get_field(int $field)
		{
			return sqlsrv_get_field( $this->stmt, $field);
		}
		
		public function commit() {
			sqlsrv_commit($this->conn);
		}
		
		public function rollback() {
			sqlsrv_rollback($this->conn);
		}
		
		public function begin_transaction() {
			return sqlsrv_begin_transaction($this->conn);
		}
		
		public function run_query($sql, $params=NULL)
		{
			$this->last_query = $this->prep_sql = $sql;
			//$this->stmt = sqlsrv_query( $this->conn, $sql, $params);
			if($params == NULL) {
				$this->stmt = sqlsrv_query( $this->conn, $sql);
			} else {
				$this->stmt = sqlsrv_query( $this->conn, $sql, $params);
			}
			//echo var_dump($this->stmt);
			if($this->stmt != false) {
				$this->affected_rows = sqlsrv_rows_affected($this->stmt);
				$this->row_count = sqlsrv_num_rows($this->stmt);
			} else {
				$this->affected_rows = 0;
				$this->row_count = 0;
			}
			
			return $this->stmt;
		}
		
		public function run_prepared_query($sql)
		{
			$this->prep_sql = $sql;
			
			if($this->prep_sql != NULL && strlen(trim($this->prep_sql)) > 0)
			{
				$query_param_count = substr_count($this->prep_sql);
				
				if($query_param_count > 0)
				{
					if($this->get_param_count() == $query_param_count)
					{
						// execute prepared statements
						$$this->stmt = sqlsrv_prepare($this->conn, $this->prep_sql, $this->params);
						$this->last_query = $this->prep_sql;
						
						if(sqlsrv_execute($stmt) === false)
						{
							$this->errors = sqlsrv_errors();
							exit("Error occured");
						}
						else
						{
							$this->affected_rows = sqlsrv_rows_affected();
							$this->row_count = sqlsrv_num_rows();
						}
					}
					else
					{
						// number in the negative means that query param are less than provided params
						// positive int means provided params are less than required by query param count
						echo "missing parameters ".($query_param_count - $this->get_param_count());
					}
				}
			}
		}
		
		public function fetch_assoc()
		{
			return sqlsrv_fetch_array($this->stmt, SQLSRV_FETCH_ASSOC);
		}
		
		public function fetch_assoc_wr($resource) {
			return sqlsrv_fetch_array($resource, SQLSRV_FETCH_ASSOC);
		}
		
		public function set_param(int $index, $param)
		{
			$this->params[$index] = $param;
		}
		
		private function get_param_count()
		{
			return count($this->params);
		}
		
		private function get_error()
		{
			return $this->errors;
		}
		
		public function get_stmt()
		{
			return $this->stmt;
		}
		
	};
	
	$db = new Sql_server();
	
?>
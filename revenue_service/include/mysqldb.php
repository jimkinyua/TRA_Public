<?php
	
	class MySQLDatabase
	{
		private $connection;
		private $sql;
		private $stmt;
		
		public function __construct()
		{
			$this->open_connection();
		}
				
		public function open_connection()
		{
			$this->connection = new mysqli("localhost", "root", "", "database");
			
			if(mysqli_connect_errno())
			{
				//die("Unable to connect to database - ".mysqli_connect_error()." (".mysqli_connect_errno().")");
				die("Unable to connect to database - ".$this->get_error()." (".$this->get_error_no().")");
			}
		}
		
		public function close_connection()
		{
			mysqli_close($this->connection);
		}
		
		public function run_query($sql)
		{
			return mysqli_query($this->connection, $sql);
		}
		
		public function get_affected_rows()
		{
		}
		
		public function prepare($sql)
		{
			$this->sql = $sql;
			$stmt = $this->connection->prepare($sql);
		}
		
		public function fetch_all($rs)
		{
			return mysqli_fetch_all($rs, MYSQLI_ASSOC);
		}
		
		public function fetch_assoc($rs)
		{
			return mysqli_fetch_assoc($rs);
		}
		
		public function get_last_query()
		{
			return $this->sql;
		}
		
		public function get_error()
		{
			return mysqli_error($this->connection);
		}
		
		public function get_error_no()
		{
			return mysqli_errno($this->connection);
		}
		
		public function get_connection()
		{
			return $this->connection;
		}
	};
	
	//$db = new MySQLDatabase();
	
?>
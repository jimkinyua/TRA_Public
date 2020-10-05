<?php
	
	class Session
	{
		
		public function __construct()
		{
			session_start();
		}
		
		public function put($key=NULL, $value)
		{
			if($key != NULL)
			{
				$_SESSION[$key] = $value;
			}
		}
		
		public function get($key)
		{
			return $_SESSION[$key];
		}
		
		public function clear()
		{
			session_unset();
			session_destroy();
		}
		
	};
	
	$session = new Session();
	
?>
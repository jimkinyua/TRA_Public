
<?php
	
	class User
	{
		
		private $user_dtls;
		private $message;
		
		public function __construct()
		{
			/*$this->user_dtls = array();
			
			$this->user_dtls['userid']          = "";
			$this->user_dtls['userlevel']       = "";
			$this->user_dtls['defaultpassword'] = "";
			$this->user_dtls['heartbeat']       = "";
			$this->user_dtls['paybill']         = "";
			$this->user_dtls['currency']        = "";
			$this->user_dtls['username']        = "";
			$this->user_dtls['name']            = "";
			$this->user_dtls['token']           = "";
			$this->user_dtls['marketid']        = "";
			$this->user_dtls['market']          = "";
			$this->user_dtls['status']          = "";
			$this->user_dtls['device']          = "";*/
		}
		
		public function login($username = NULL, $password = NULL, $device = NULL)
		{
			//if($username != NULL && $password != NULL)
			if($username != NULL || $password != NULL)
			{
				$username = trim($username);
				$password = trim($password);
				
				//if(!empty($username) && !empty($password))
				if(!empty($username) || !empty($password))
				{
					global $db;
					
					/*$sql = "select TOP 1 u.userid, u.userfullnames, u.username, u.active, u.userstatusid, ";
					$sql .= "(select marketname from markets where marketid=um.marketid) as market, ud.deviceserialno from users u ";
					$sql .= "inner join usermarkets um on um.userid=u.userid inner join userdevices ud on ud.deviceuserid=u.userid where u.username=? ";
					$sql .= "and ud.devicepinno=?";*/
					$sql = "select TOP 1 u.agentid as user_d, concat(concat(u.lastname, ' '), u.firstname) as userfullnames, u.username, u.active, ";
					$sql .= "u.userstatusid, (select marketname from markets where marketid=ud.marketid) as market, ";
					$sql .= "(select top 1 value from systemparams where paramname='Void Limt') as voidLimit, ud.deviceserialno from agents u ";
					$sql .= "inner join userdevices ud on ud.deviceuserid=u.agentid where u.username=? ";
					$sql .= "and ud.devicepinno=? and ud.deviceserialno=? and ud.deviceuserstatusid=1";
					
					$db->run_query($sql, array($username, $password, $device));
					
					if($db->get_stmt() == false) {
						die( print_r( sqlsrv_errors(), true) );
						$this->message = "Wrong username or password";
					}
					
					while(($dtls = $db->fetch_assoc()) != NULL) {
						$this->message = "Login successful";
						
						if(isset($dtls['userid']) && !empty($dtls['userid'])) {
							$this->user_dtls['userid']          = $dtls['userid'];
						} else {
							$this->user_dtls['userid']          = "";
						}
						$this->user_dtls['heartbeat']       = 60;
						$this->user_dtls['paybill']         = "";
						$this->user_dtls['currency']        = "KES";
						$this->user_dtls['username']        = $dtls['username'];
						$this->user_dtls['name']            = $dtls['userfullnames'];
						$this->user_dtls['marketid']        = "1";
						$this->user_dtls['market']          = $dtls['market'];
						$this->user_dtls['status']          = $dtls['active'];
						$this->user_dtls['voidlimit']		= $dtls['voidLimit'];
						//$this->user_dtls['county']          = $dtls['county'];
						//$this->user_dtls['device']          = "";
						$this->user_dtls['datetime']        = date("Y-m-d H:i:s");
					}
					
					if(!isset($this->user_dtls) || count($this->user_dtls) <= 0) $this->message = "Wrong username or password";
				}
				else
				{
					$this->message = "Please provide both username and password";
				}
			}
			else
			{
				$this->message = "Please provide both username and password";
			}
			
			return isset($this->user_dtls) ? true : false;
		}
		
		public function get_login_details()
		{
			return $this->user_dtls;
		}
		
		public function get($key) {
			return isset($key) ? $this->user_dtls[$key] : NULL;
		}
		
		public function update_token($token, $username) {
			$sql = "update users set remembertoken=? where username=?";
		}
		
		public function get_user_details($username = NULL)
		{
			if($username != NULL && strlen(($username = trim($username))) > 0)
			{
				global $db;
				/*$sql = "select TOP 1 u.userid, u.userfullnames, u.username, u.active, u.userstatusid, ";
				$sql .= "(select marketname from markets where marketid=um.marketid) as market, ud.deviceserialno from users u ";
				$sql .= "inner join usermarkets um on um.userid=u.userid inner join userdevices ud on ud.deviceuserid=u.userid where u.username=? ";
				$sql .= "order by ud.createddate desc";*/
				
				/*$sql = "select TOP 1 u.userid, u.userfullnames, u.username, u.active, u.userstatusid, ";
				$sql .= "(select marketname from markets where marketid=ud.marketid) as market, ud.deviceserialno from users u ";
				$sql .= " inner join userdevices ud on ud.deviceuserid=u.userid ";
				$sql .= "where u.username=? and ud.deviceuserstatusid=1 order by ud.createddate desc";*/
				$sql = "select TOP 1 u.agentid as userid, concat(concat(u.lastname, ' '), u.firstname) as userfullnames, u.username, u.active, u.userstatusid, ";
				$sql .= "(select marketname from markets where marketid=ud.marketid) as market, ud.deviceserialno from agents u ";
				$sql .= "inner join userdevices ud on ud.deviceuserid=u.agentid where u.username=? and ud.deviceuserstatusid=1";
				
				$db->run_query($sql, array($username));
				
				if($db->get_stmt() == false) {
					$this->message = "User not found";
					die( print_r( sqlsrv_errors(), true) );
				}
				
				while($dtls = $db->fetch_assoc()) {
					$this->user_dtls['userid']          = $dtls['userid'];
					//$this->user_dtls['userlevel']       = $dtls['userlevel'];
					$this->user_dtls['defaultpassword'] = "1234";
					$this->user_dtls['heartbeat']       = 60;
					$this->user_dtls['paybill']         = "";
					$this->user_dtls['currency']        = "KES";
					$this->user_dtls['username']        = $dtls['username'];
					$this->user_dtls['name']            = $dtls['userfullnames'];
					$this->user_dtls['marketid']        = "1";
					$this->user_dtls['market']          = $dtls['market'];
					$this->user_dtls['status']          = $dtls['active'];
					$this->user_dtls['device']          = $dtls['deviceserialno'];
					//$this->user_dtls['county']          = $dtls['county'];
					$this->user_dtls['datetime']        = date("Y-m-d H:i:s");
				}
			}
			
			return isset($this->user_dtls) ? true : NULL;
		}
		
		public function can_do_txn()
		{
			global $db;
			if($this->user_dtls['can_transact'] && $this->user_dtls['can_transact'])
				return true;
			
			return false;
		}
		
		public function can_verify()
		{
			return (isset($this->user_dtls['can_verify']) && $this->user_dtls['can_verify'])
												? $this->user_dtls['can_verify'] 
												: false;
		}
		
		public function user_exists($username)
		{
			$sql = "select username from users where username=?";
			
			return false;
		}
		
		public function change_password($username, $old_password, $new_password, $serial)
		{
			global $db;
			$sql  = "update userdevices set devicepinno=? where deviceserialno=? and deviceuserstatusid=1 and ";
			$sql .= "deviceuserid=(select top 1 agentid from agents where username=? order by agentid desc) and devicepinno=?;";
			
			if($db->run_query($sql, array($new_password, $serial, $username, $old_password)) && $db->get_affected_rows() > 0) {
				return true;
			}
			
			return false;
		}
		
		public function save_token($token, $username)
		{
			$sql = "update users set api_token=? where username=?";
			return false;
		}
		
		public function get_token($username = NULL)
		{
			global $db;
			$sql = "select api_token from users where username=? and confirmed=1 limit 1";
			
			$stmt = $db->get_connection()->prepare($sql);
			$stmt->bind_param('s', $username);
			$stmt->execute();
			
			if($stmt->num_rows() > 0)
			{
				$rs = array();
				$stmt->store_result();
				$stmt->bind_result($token);
				
				while($stmt->fetch())
				{
					$o['api_token'] = $token;
					$rs = $o;
				}
				
				return $token;
			}
			
			return true;
		}
		
		public function get_message() {
			return $this->message;
		}
		
		public function clear() {
			unset($this->user_dtls);
		}
		
	};
	
	$user = new User();
	
?>
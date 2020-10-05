<?php include_once("sms.php"); ?>

<?php
	
	class Mpesa
	{
		private $user;
		private $pass;
		private $id; // ipn id
		private $orig; // should be MPESA
		private $dest;
		private $text; // message
		private $mpesa_code;
		private $mpesa_acc;
		private $mpesa_amt;
		private $mpesa_sender;
		private $mpesa_trx_time;
		private $mpesa_trx_date;
		private $tstamp;
		private $mpesa_msisdn;
		private $txn_status = "OK";
		
		private $log;
		
		public function msg_is_valid()
		{
			if((isset($this->user) && strlen(trim($this->user)) > 0) && 
				(isset($this->pass) && strlen(trim($this->pass)) > 0))
			{
				if(strcmp($this->user, "corecs") == 0 && strcmp($this->pass, "i7gQbvoKz6w765") == 0)
				{
					return true;
				}
			}
			
			return false;
		}
		
		public function process_mpesa_msg($mpesa_msg = NULL)
		{
			if(isset($mpesa_msg) && $mpesa_msg != NULL && strlen(trim($mpesa_msg)) > 0)
			{
				// process mpesa message
				echo $mpesa_msg;
				
				if($this->msg_is_valid())
				{
					if($this->save_payment())
					{
						$composed_msg = "";
						
						if($sms->send($composed_msg, $this->mpesa_sender) == 0)
						{
							echo "SMS alert successful";
						}
						else
						{
							echo "SMS alert not successful";
						}
						
						echo "OK|Success";
					}
					else
					{
						echo "OK|Fail";
					}
				}
				else
				{
					echo "Invalid mpesa credentials";
				}
			}
		}
		
		private function save_payment()
		{
			
			global $db;
			$sql = "INSERT INTO messages(ipn_id, origin, destination, message, mpesa_code, "
				+ "mpesa_account, amount, sender, mpesa_txn_time, mpesa_txn_date, timestamp, msisdn, txn_status, "
				+ "txn_reverse, reverse_dt, created_dt, created_by, updated_dt, updated_by) VALUES "
				+ "(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			
			$stmt = $db->get_connection()->prepare($sql);
			$stmt->bind_param('sssssssssssssssssss'
			, $this->id // ipn id
			, $this->orig // should be MPESA
			, $this->dest
			, $this->text // message
			, $this->mpesa_code
			, $this->mpesa_acc
			, $this->mpesa_amt
			, $this->mpesa_sender
			, $this->mpesa_trx_time
			, $this->mpesa_trx_date
			, $this->tstamp
			, $this->mpesa_msisdn
			, $this->txn_status // txn status
			, "0" // txn reverse
			, ""// reverse date
			, ""//yyyy-MM-dd HH:mm:ss
			, "1"
			, ""// yyyy-MM-dd HH:mm:ss
			, "1");
			
			if($stmt->execute() > 0)
			{
				return true;
			}
			
			return false;
		}
		
		public function settle_pmt($mpesa_code = NULL)
		{
			if(isset($mpesa_code) && !empty($mpesa_code))
			{
				global $db;
				$sql = "update {$table} set paid=? where mpesa_code=?;";
				
				$stmt = $db->get_connection()->prepare($sql);
				$stmt->bind_param('ss', "1", $mpesa_code);
				
				if($stmt->execute() > 0)
				{
					return true;
				}
			}
			
			return false;
		}
		
		public function reverse_payment($mpesa_code = NULL)
		{
			if(isset($mpesa_code) && !empty($mpesa_code))
			{
				global $db;
				$sql = "update {$table} set reverse=? where mpesa_code=?;";
				
				$stmt = $db->get_connection()->prepare($sql);
				$stmt->bind_param('ss', "1", $mpesa_code);
				
				if($stmt->execute() > 0)
				{
					return true;
				}
			}
		}
		
		public function get_mpesa_dtls($mpesa_code)
		{
			if(isset($mpesa_code) && $mpesa_code != NULL && strlen(trim($mpesa_code)) > 0)
			{
				global $db;
				$sql = "select origin, mpesa_code, mpesa_account, amount, sender, mpesa_txn_time, mpesa_txn_date, timestamp,";
				$sql .= "txn_status, txn_reverse, reverse_dt from mpesa_txns where mpesa_code=? limit 1 ";
				
				$stmt = $db->get_connection()->prepare($sql);
				$stmt->bind_param('s', $mpesa_code);
				$stmt->execute();
				
				if($smt->num_rows() > 0)
				{
					$stmt->store_result();
					$stmt->bind_result($origin, $mpesa_code, $mpesa_account, $amount, $sender, 
							$mpesa_txn_time, $mpesa_txn_date, $timestamp, $txn_status, $txn_reverse, $reverse_dt);
					
					while($stmt->fetch())
					{
						$o['origin'] = $origin;
						$o['mpesa_code'] = $mpesa_code;
						$o['mpesa_account'] = $mpesa_account;
						$o['amount'] = $amount;
						$o['sender'] = $sender;
						$o['mpesa_txn_time'] = $mpesa_txn_time;
						$o['mpesa_txn_date'] = $mpesa_txn_date;
						$o['timestamp'] = $timestamp;
						$o['txn_status'] = $txn_status;
						$o['txn_reverse'] = $txn_reverse;
						$o['reverse_dt'] = $reverse_dt;
					}
					
					return json_encode($o);
				}
			}
			
			return NULL;
		}
		
	};
	
	$mpesa = new Mpesa();
	
?>
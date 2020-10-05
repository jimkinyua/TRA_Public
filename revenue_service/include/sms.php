<?php include_once("mysqldb.php"); ?>

<?php
	
	// sms with infobip
	class Sms
	{
		
		private $sms;
		private $error;
		private $sms_endpoint = "http://api.infobip.com/api/v3/sendsms/json";
		
		public function __construct()
		{
			$this->sms = array();
			
			$this->sms['authentication']['username'] = "Kiplagat";
			$this->sms['authentication']['password'] = "kiplagat";
			$obj['authentication']['username'] = "rop";
			$obj['authentication']['password'] = "kip";
			/*$obj['messages'] = array();
			$msg['sender'] = "Sender";
			$msg['text'] = "Hello";
			$msg['recepients'] = array();
			$rcp = array();
			$rcp['gsm'] = "385951111111";
			array_push($msg['recepients'], $rcp);
			$rcp['gsm'] = "385952222222";
			array_push($msg['recepients'], $rcp);
			$rcp['gsm'] = "385953333333";
			array_push($msg['recepients'], $rcp);
			array_push($obj['messages'], $msg);*/
		}
		
		public function is_valid($message)
		{
			return false;
		}
		
		public function generate($message)
		{
			// generate appropriate message headers and save as sms
			$sms = array();
			$sms['message']['text'] = $message;
			$sms['message']['recipients'] = "";
			
			// save message if it is valid
			if($this->save($sms))
			{
				return true;
			}
			
			return false;
		}
		
		public function save($sms)
		{
			return false;
		}
		
		public function send($message, $phone_no)
		{
			if($this->is_valid($message))
			{
				if($this->generate($message))
				{
					// call geteway api
					return 0;
				}
			}
			
			return -1;
		}
		
		public function get_error()
		{
			return $this->error;
		}
		
	};
	
	$sms = new Sms();
	
?>
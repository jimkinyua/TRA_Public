<?php require_once("include/util.php"); ?>
<?php require_once("include/sqlserverdb.php"); ?>
<?php include_once("include/user.php"); ?>
<?php include_once("include/device.php"); ?>
<?php include_once("include/service.php"); ?>
<?php include_once("include/transaction.php"); ?>
<?php include_once("constants/http_response.php"); ?>
<?php require_once("include/logger/logger.php"); ?>

<?php
	// takes care of xss
	//header('Content-type: application/json'); 
	//header("x-content-type-options: nosniff");
	//print $_GET['json'];
	error_reporting(E_ALL);
	ini_set('display_errors', True);
?>

<?php
	
	$log = new Katzgrau\KLogger\Logger(LOG_PATH, Psr\Log\LogLevel::INFO);
	
	$response;
	
	if(isset($_GET['q'])) {
		$request = $rq = json_decode($_GET['q'], true);
		
		$log->info("request ##### ".$_GET['q']);
		
		
		if(parse_request($request)) {
			if(!isset($request['token'])) $request['token'] = NULL;
			if(!isset($request['obj'])) $request['obj'] = NULL;
			
			if(strlen($request['command']) > 0) {
				// load user info
				if(!$user->get_user_details($request['username'])) exit(set_response(HTTP_FORBIDDEN, $request['command'], "user not found", NULL, false));
				
				// load device info
				if(!$device->load_device_info($request['serial'])) exit(set_response(HTTP_FORBIDDEN, $request['command'], "device not found", NULL, false));
				
				//check if user is mapped to specified device
				if(strcmp($user->get('device'), $request['serial']) != 0) exit(set_response(HTTP_FORBIDDEN, $request['command'], "user not assigned to this device", NULL, false));
				
				if($device->device_is_locked()) exit(set_response(HTTP_FORBIDDEN, $request['command'], "This device is locked. Please contact Admin"));
				
				if($device->is_pos()) {
					process_pos_request($request);
				} else if($device->is_android()) {
					process_android_request($request);
				} else {
					exit(set_response(HTTP_FORBIDDEN, $request['command'], "Unrecognized device", NULL, false));
				}
			}
		} else {
			exit(set_response(HTTP_FORBIDDEN, isset($request['command']) ? $request['command'] : " ", "Invalid request"));
		}
	}
	
	function set_response($code=NULL, $command=NULL, $message=NULL, $obj=NULL, $show_token = true)
	{
		global $request;
		global $user;
		global $response;
		global $log;
		
		if($code == NULL || empty($code)) return "Response is missing code";
		if($command == NULL) return "Response is missing command";
		if($message == NULL) return "Response is missing description message";
		
		$token = generate_token($request['username'], $request['serial']);
		
		$user->update_token($token, $request['username']);
		
		$response['code']     = $code;
		$response['command']  = $command;
		$response['response']['message'] = $message;
		if($show_token) $response['token'] =  $token;
		if(isset($obj)) $response['response']['obj'] = $obj;
		//die(print_r($response));
		$log->info("response ############# ".json_encode($response));
		
		return json_encode($response);
	}
	
	function parse_request($rq = NULL)
	{
		if($rq != NULL)
		{
			if(json_last_error() == JSON_ERROR_NONE)
			{
				$has_error = false;
				
				if(!isset($rq['command']) || empty($rq['command']))
				{
					echo "Command not provided<br/>";
					if($has_error == false) $has_error = true;
				}
				
				if(!isset($rq['action']) || empty($rq['action']))
				{
					echo "Request action not provided<br/>";
					if($has_error == false) $has_error = true;
				}
				
				if(!isset($rq['serial']) || empty($rq['serial']))
				{
					echo "Device serial not provided<br/>";
					if($has_error == false) $has_error = true;
				}
				
				if(!isset($rq['username']) || empty($rq['username']))
				{
					echo "Username not provided<br/>";
					if($has_error == false) $has_error = true;
				}
				
				return !$has_error;
			}
			else
			{
				echo json_last_error()." ### ".json_last_error_msg()."<br />";
				echo "invalid data format";
			}
		}
		else
		{
			//echo "Empty request";
		}
		
		return false;
	}
	
	function process_pos_request($req) {
		global $user;
		global $log;
		
		$log->info("processing pos request");
		
		switch($req['command']) {
			case 'LOGIN':
				$username = $req['username'];
				$password = isset($req['obj']['password']) ? $req['obj']['password'] : "";
				$device = $req['serial'];
				
				$user->clear();
				
				if($user->login($username, $password, $device)) {
					exit(set_response(HTTP_OK, $req['command'], $user->get_message(), $user->get_login_details()));
				} else {
					exit(set_response(HTTP_FORBIDDEN, $req['command'], $user->get_message(), NULL, false));
				}
			case 'SERVICE':
				global $service;
				
				$services = $service->get_offline_services($req['obj']['page']);
				//die(print_r($services));
				if($services != NULL)
				{
					exit(set_response(HTTP_CREATED, $req['command'], $service->get_message(), $services));
				}
				else
				{
					exit(set_response(HTTP_NOT_FOUND, $req['command'], $service->get_message()));
				}
			case 'RECEIPT':
				global $transaction;
				global $user;
				global $device;
				
				$txn = $req['obj'];
				
				if(count($txn) > 0) {
					for($i = 0; $i < count($txn); $i++) {
						$txn[$i]['deviceid'] = $device->get('deviceid');
						$txn[$i]['amount'] = $txn[$i]['total'];
						$txn[$i]['agent'] = $req['username'];
						$txn[$i]['userid'] = $user->get('userid');
						$txn[$i]['created_by'] = $user->get('userid');
						//print_r($txn);
						unset($txn[$i]['total']);
						
						if(!$transaction->txn_is_complete($txn[$i]))exit(set_response(HTTP_FORBIDDEN, $req['command'], $transaction->get_message()));
					}
					
					$transaction->transact($txn);
					exit(set_response($transaction->get_response_code(), $req['command'], $transaction->get_message()));
				} else {
					exit(set_response(HTTP_CREATED, $req['command'], "MIssing transaction(s)"));
				}
			case 'VOID':
				global $transaction;
				$txn_ref = isset($req['obj']['tref']) ? $req['obj']['tref'] : "";
				
				if(isset($txn_ref) && $transaction->void_txn($txn_ref)) {
					exit(set_response(HTTP_CREATED, $req['command'], $transaction->get_message()));
				} else {
					exit(set_response(HTTP_FORBIDDEN, $req['command'], $transaction->get_message()));
				}
			case 'EOD':
				global $transaction;
				
				$eod = $transaction->calc_eod($req['username']);
				
				if($eod != NULL) {
					exit(set_response(HTTP_CREATED, $req['command'], $transaction->get_message(), $eod));
				} else {
					exit(set_response(HTTP_NOT_FOUND, $req['command'], $transaction->get_message()));
				}
			case 'PASSWORD':
				if($user->change_password($req['username'], $req['obj']['old_password'], $req['obj']['new_password'], $req['serial'])) {
					exit(set_response(HTTP_CREATED, $req['command'], "Password update successful", $eod));
				} else {
					exit(set_response(HTTP_FORBIDDEN, $req['command'], "Password change failed", $eod));
				}
			default:
				exit(set_response("001", $req['command'], "Empty or invalid command"));
		}
	}
	
	function process_android_request($req) {
		global $user;
		global $log;
		
		$log->info("processing android request");
		
		switch($req['command']) {
			case 'LOGIN':
				$username = $req['username'];
				$password = isset($req['obj']['password']) ? $req['obj']['password'] : "";
				$device = $req['serial'];
				
				$user->clear();
				
				if($user->login($username, $password, $device)) {
					exit(set_response(HTTP_OK, $req['command'], $user->get_message(), $user->get_login_details()));
				} else {
					exit(set_response(HTTP_FORBIDDEN, $req['command'], $user->get_message(), NULL, false));
				}
			case 'PARKING':
				global $transaction;
				$reg_no = isset($req['obj']['reg_no']) ? $req['obj']['reg_no'] : "";
				
				$rcpt = $transaction->get_pmt_parking($reg_no);
				
				if($rcpt != NULL) {
					exit(set_response(HTTP_OK, $req['command'], "Transaction ".$rcpt['posreceiptno']." found", $rcpt));
				} else {
					$msg = $transaction->get_message();
					exit(set_response(HTTP_NOT_FOUND, $req['command'], $msg == NULL ? "Txn not found" : $msg));
				}
				
			case 'RECEIPT':
				global $transaction;
				$billno = isset($req['obj']['rcpt']) ? $req['obj']['rcpt'] : "";
				$rcpt = $transaction->get_pmt($billno);
				
				if($rcpt != NULL) {
					exit(set_response(HTTP_OK, $req['command'], "Transaction ".$rcpt['posreceiptno']." found", $rcpt));
				} else {
					$msg = $transaction->get_message();
					exit(set_response(HTTP_NOT_FOUND, $req['command'], $msg == NULL ? "Txn not found" : $msg));
				}
			case 'SBP':
				global $transaction;
				
				$sbp = $transaction->get_sbp_details($req['obj']['sbp']);
				
				if($sbp != null) {
					exit(set_response(HTTP_OK, $req['command'], "Permit ".$sbp['permitNo']." found", $sbp));
				} else {
					$msg = $transaction->get_message();
					exit(set_response(HTTP_NOT_FOUND, $req['command'], "Permit not found"));
				}
				
				break;
			case 'PASSWORD':
				if($user->change_password($req['username'], $req['obj']['old_password'], $req['obj']['new_password'], $req['serial'])) {
					exit(set_response(HTTP_CREATED, $req['command'], "Password update successful", $eod));
				} else {
					exit(set_response(HTTP_FORBIDDEN, $req['command'], "Password change failed", $eod));
				}
			default:
				exit(set_response(HTTP_FORBIDDEN, $req['command'], $msg == NULL ? "Empty or invalid command" : $msg));
		}
		
		echo "processing android request";
	}

?>
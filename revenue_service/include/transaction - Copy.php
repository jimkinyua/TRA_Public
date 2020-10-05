<?php require_once("logger/logger.php"); ?>

<?php
	
	// Transaction response codes
	define("PAYMENT_PENDING", 100);
	define("PAYMENT_INCOMPLETE", 200);
	define("NO_ERROR", -1);
	define("TXN_STATUS_OK", 1);
	define("TXN_STATUS_VOID", 2);
	
	class Transaction
	{
		
		private $response_code = -1;
		private $txn;
		private $message;
		private $invoice_hdr_id= 2;
		private $log;
		
		public function __construct()
		{
			$this->txn = array();
			
			$this->txn['billno'] = "";
			$this->txn['transactiontype'] = "";
			$this->txn['transactiondate'] = "";
			$this->txn['serviceid'] = "";
			$this->txn['paymentmethod'] = "";
			$this->txn['total'] = "";
			$this->txn['market'] = "";
			
			//$this->log = new ('C:\\inetpub\\logs\\service logs\\service.log');
			//$this->log = new Katzgrau\KLogger\Logger('C:\\inetpub\\logs\\service logs\\service_logs', Psr\Log\LogLevel::INFO);
			$this->log = new Katzgrau\KLogger\Logger(LOG_PATH, Psr\Log\LogLevel::INFO);
		}
		
		public function get_response_msg($response_code)
		{
			switch($response_code)
			{
				case PAYMENT_INCOMPLETE:
					return "Incomplete payment";
				case PAYMENT_PENDING:
					return "Payment pending";
				case NO_ERROR:
					return "No error found";
				default:
					return "Unknown response code";
			}
		}
		
		public function txn_is_valid($txn = NULL)
		{
			if($txn != NULL)
			{
				if(isset($txn['ref']) && !empty($txn['ref']))
				{
					$t = $this->get_txn_dtls($txn['ref']);
					
					if($t != NULL)
					{
						// transaction details found
						if(isset($t['valid']) && $t['valid'] != 1)
						{
							$this->response_code = NO_ERROR;
							return false;
						}
					}
				}
			}
			
			return "Transactino validation failed";
		}
		
		public function txn_is_complete($txn)
		{
			$has_error = false;
			$error = "";
			$counter = 0;
			
			if(!isset($txn['billno']) || empty($txn['billno']))
			{
				$error = ++$counter.". Bill number\n";
				$has_error = true;
			}
			
			if(!isset($txn['transactiondate']) || empty($txn['transactiondate']))
			{
				$error = ++$counter.". Transactioin date\n";
				$has_error = true;
			}
			
			if(!isset($txn['serviceid']) || empty($txn['serviceid']))
			{
				$error = ++$counter.". Service id\n";
				$has_error = true;
			}
			
			/*if(!isset($txn['userid']) || empty($txn['userid']))
			{
				$error = ++$counter.". userid\n";
				$has_error = true;
			}*/
			
			if(!isset($txn['market']) || empty($txn['market']))
			{
				$error = ++$counter.". Market\n";
				$has_error = true;
			}
			
			if($has_error)
			{
				$this->message = "Please provide the following details\n".$error;
				return false;
			}
			
			return true;
		}
		
		public function void_txn($txn_ref)
		{
			global $db;
			$sql = "update invoiceheader set status=? where invoiceheaderid=";
			$sql .= "(select top 1 invoiceheaderid from invoicelines where posreceiptid=?)";
			
			$db->run_query($sql, array(
							TXN_STATUS_VOID,
							$txn_ref
							));
				
			if($db->get_stmt() == false) {
				die( print_r( sqlsrv_errors(), true) );
				$this->message = "Error voiding transaction";
				
				return false;
			}
			
			if($db->get_affected_rows() > 0) {
				$this->message = "Transaction voided successfully";
				return true;
			}
			
			$this->message = "Transaction voiding failed";
			
			return false;
		}
		
		private function day_invoice_created($customer) {
			global $db;
			//$sql = "select top 1 invoiceheaderid from invoiceheader where customer_id=? order by invoiceheaderid desc";
			$sql = "select top 1 invoiceheaderid, CASE WHEN cast((select top 1 createddate from invoiceheader ";
			$sql .= "where customerid=(select agentid from agents where username=?) order by invoiceheaderid desc) as date) = cast(getdate() as date) ";
			$sql .= "THEN 'Y' ELSE 'N' END as datediff from invoiceheader";
			
			if($db->run_query($sql, array($customer))) {
				$row = $db->fetch_assoc();
				$this->invoice_hdr_id = $row['invoiceheaderid'];
				if($row['datediff'] == 'Y') return true;
			} else {
				echo __LINE__;
				die(print_r(sqlsrv_errors(), true));
			}
			
			return false;
		}
		
		private function create_invoice($customer_id) {
			global $db;
			$sql = "insert into invoiceheader (invoicedate, customerid, createddate, createdby, paid) ";
			$sql .= "values (GETDATE(),(select agentid from agents where username=?),GETDATE(),(select agentid from agents where username=?),0)";
			//echo "customer - ".$customer_id.";";
			if($db->run_query($sql, array($customer_id, $customer_id)) && $db->get_affected_rows() > 0) {
				return true;
			} else {
				echo __LINE__;
				$this->log->info("error at line >>> ". __LINE__);
				$this->log->info("user id >>> ". $customer_id);
				$this->log->info(json_encode(sqlsrv_errors()));
				die(print_r(sqlsrv_errors(), true));
			}
			
			return false;
		}
		
		public function settle_mpesa_pmt($mpesa_code) {
			global $db;
			$sql = "select top 1 mpesa_amt, status from mpesa where mpesa_code=?";
			
			if($db->run_query($sql, array($mpesa_code)) && $db->row_count() > 0) {
				$row = $db->fetch_assoc();
				
				if($row['status'] == MPESA_NOT_PAID) {
					// transaction is not paid
					// complete transaction
					$sql = "update mpesa set status=? where mpesa_code=?";
					
					if($db->run_query($sql, array(MPESA_PAID, $mpesa_code))) {
						return true;
					}
				} else if($row['status'] == MPESA_PAID) {
					$this->message = "Mpesa reference has already been used";
				} else if($row['status'] == MPESA_REVERSED) {
					$this->message = "Mpesa payment was reversed";
				} else {
					$this->message = "Mpesa code with undefined status";
				}
			} else {
				$this->message = "Invalid mpesa code";
			}
			
			return false;
		}
		
		public function get_pmt($billno) {
			global $db;
			$txn_info = NULL;
			/*$sql  = "select top 1 ci.posreceiptno, ci.amount, (select marketname from markets where marketid=um.marketid) as market, ";
			$sql .= "(select submissiondate from serviceheader where serviceheaderid=il.serviceheaderid) as transactiondate, ";
			$sql .= "(select servicename from services where serviceid=(select serviceid from serviceheader ";
			$sql .= "where serviceheaderid=il.serviceheaderid)) as service, (select concat(concat(lastname, ' '), firstname) ";
			$sql .= "from agents where agentid=il.createdby) as agent, ";
			$sql .= "(select serviceheaderid from serviceheader where serviceheaderid=il.serviceheaderid) as serviceheaderid ";
			$sql .= "from consolidateinvoice ci inner join invoicelines il on il.invoicelineid=ci.invoicelineid ";
			$sql .= "inner join usermarkets um on um.userid=il.createdby where ci.posreceiptno=?";*/
			
			$sql  = "select top 1 il.posreceiptid as posreceiptno, il.amount, (select marketname from markets where marketid=il.marketid) as market, ";
			$sql .= "(select submissiondate from serviceheader where serviceheaderid=il.serviceheaderid) as transactiondate, ";
			$sql .= "(select servicename from services where serviceid=(select serviceid from serviceheader ";
			$sql .= "where serviceheaderid=il.serviceheaderid)) as service, (select concat(concat(lastname, ' '), firstname) ";
			$sql .= "from agents where agentid=il.createdby) as agent, il.serviceheaderid from invoicelines il where il.posreceiptid=?";
			
			//if($db->run_query($sql, array($billno)) && $db->row_count() > 0) {
			if($db->run_query($sql, array($billno))) {
				while($txn = $db->fetch_assoc()) {
					$this->log->info("received txn info => ".json_encode($txn));
					
					if(isset($txn) && isset($txn['serviceheaderid'])) {
						$sql = "select fc.formcolumnname as param, fd.value from formdata fd ";
						$sql .= "inner join formcolumns fc on fc.formcolumnid=fd.formcolumnid where fd.serviceheaderid=?";
						
						//if($db->run_query($sql, array($txn['serviceheaderid'])) && $db->row_count() > 0) {
						if($db->run_query($sql, array($txn['serviceheaderid']))) {
							$params = array();
							
							while($p = $db->fetch_assoc()) {
								array_push($params, $p);
							}
							
							array_push($txn, $params);
						}
					}
					unset($txn['serviceheaderid']);
					unset($txn['0']);
					if(isset($txn) && count($txn) > 0) $txn_info = $txn;
				}
				
				return $txn_info;
			} else {
				$message = "Transaction not found";
				
				return NULL;
			}
		}
		
		public function get_pmt_parking($reg_no) {
			global $db;
			$txn_info = NULL;
			$sql  = "select top 1 il.posreceiptid as posreceiptno, il.amount, (select marketname from markets where marketid=il.marketid) as market, ";
			$sql .= "(select submissiondate from serviceheader where serviceheaderid=il.serviceheaderid) as transactiondate, ";
			$sql .= "(select servicename from services where serviceid=(select serviceid from serviceheader ";
			$sql .= "where serviceheaderid=il.serviceheaderid)) as service, (select concat(concat(lastname, ' '), firstname) ";
			$sql .= "from agents where agentid=il.createdby) as agent, il.serviceheaderid ";
			$sql .= "from invoicelines il join formdata fd on fd.serviceheaderid=il.serviceheaderid where fd.value=? ";
			$sql .= "order by il.invoicelineid desc ";
			
			if($db->run_query($sql, array($reg_no))) {
				while($txn = $db->fetch_assoc()) {
					$this->log->info("received txn info => ".json_encode($txn));
					
					if(isset($txn) && isset($txn['serviceheaderid'])) {
						$sql = "select fc.formcolumnname as param, fd.value from formdata fd ";
						$sql .= "inner join formcolumns fc on fc.formcolumnid=fd.formcolumnid where fd.serviceheaderid=?";
						
						if($db->run_query($sql, array($txn['serviceheaderid']))) {
							$params = array();
							
							while($p = $db->fetch_assoc()) {
								array_push($params, $p);
							}
							
							array_push($txn, $params);
						}
					}
					unset($txn['serviceheaderid']);
					unset($txn['0']);
					if(isset($txn) && count($txn) > 0) $txn_info = $txn;
				}
				
				return $txn_info;
			} else {
				$message = "Transaction not found";
				
				return NULL;
			}
		}
		
		public function transact_mpesa($mpesa_payments) {
			//echo "OK";
				$mpesa_application_query = "insert into serviceheader (customerid, serviceid, formid, submissiondate, createddate, createdby, servicestatusid, ";
				$mpesa_application_query .= "approved) values (?,?,(select top 1 sg.formid from servicegroup sg ";
				$mpesa_application_query .= "inner join services s on s.servicegroupid=sg.servicegroupid where s.serviceid=?),?, GETDATE(),?,?,0);";
				
				if($db->run_query($mpesa_application_query)) {
					$this->create_invoice($txn['agent']);
					
					if(!$this->settle_mpesa_pmt($txn['mpesacode'])) {
						return false;
					}
					
					$sql = "insert into invoiceheader () values ()";
					
					$sql = "insert into invoicelines (invoiceheaderid, serviceheaderid, amount, ";
					$sql .= "createdate, createdby) values ((select invoiceheaderid from invoiceheader where invoiceheaderid=?),?,?,?)";
					
					if($db-run_query($sql, array()) && $db->get_affected_rows() >  0) {
						$this->message = "Transaction is successful";
						
						if($invoice_line > 0) {
							$sql = "insert into consolidateinvoice (invoicelineid, posreceiptno, amount, createdby, createddate) values (?,?,?,1,GETDATE())";
							
							if($db->run_query($sql, array(1, $txn['billno'], $txn['amount'])) && $db->get_affected_rows() > 0) {
								$this->message = "Transaction successful";
								
								if(isset($txn['params']) && count($txn['params']) > 0) {
									foreach($txn['params'] as $key=>$value) {
										$sql = "insert into formdata (formcolumnid, serviceheaderid, value, creatddate, createdby) ";
										$sql .= "values ((select formcolumnid from formcolumns where formcolumnname=?),";
										$sql .= "(select top 1 serviceheaderid from serviceheader where customerid=?), ";
										$sql .= "(select top 1 userid from users where username=?),GETDATE(),";
										$sql .= "(select top 1 userid from users where username=?))";
										
										if(!$db->run_query($sql, array($key, $txn['agent']), $value, $txn['agent']) && $db->get_affected_rows() == count($txn['params'])) {
											// rollback transaction
											$db->rollback();
											die(print_r(sqlsrv_errors(), true));
											return false;
										}
									}
								}
								
								// commit transaction
								$db->commit();
								return true;
							} else {
								// rollback transaction
								$db->rollback();
								die(print_r(sqlsrv_errors(), true));
							}
						}
						
						if(count($cash_payments) <= 0) return true;
					} else {
						$message = "Transaction failed";
						$db->rollback();
					}
				}
		}
		
		public function transact($txn) {
			global $db;
			$service_hdr = 0;
			$invoice_header = 0;
			$invoice_line = 0;
			$mpesa_payments = array();
			$cash_payments = array();
			
			$this->response_code = HTTP_BAD_REQUEST;
			
			foreach($txn as $t) {
				//print_r($t);
				if(strcmp(strtoupper(trim($t['paymentmethod'])), "MPESA") == 0) {
					array_push($mpesa_payments, $t);
				} else if(strcmp(strtoupper(trim($t['paymentmethod'])), "CASH") == 0) {
					array_push($cash_payments, $t);
				}
			}
			
			if(count($mpesa_payments) > 0) {
				if(!$this->transact_mpesa($mpesa_payments)) {
					return false;
				}
			}
			
			if(count($cash_payments) > 0) {
				$db->begin_transaction();
				
				foreach($cash_payments as $pmt) {
					$cash_invoice_query = "insert into serviceheader (customerid, serviceid, formid, submissiondate, createddate, createdby, ";
					$cash_invoice_query .= "servicestatusid, approved) values ((select top 1 ud.customerid from userdevices ud where ";
					$cash_invoice_query .= "ud.deviceuserid=? and deviceuserstatusid=1 order by ud.userdeviceid desc),(select top 1 serviceid from servicetrees ";
					$cash_invoice_query .= "where servicetreeid=?), (select top 1 sc.formid from services s inner join servicecategory sc ";
					$cash_invoice_query .= "on sc.servicecategoryid=s.servicecategoryid where s.serviceid=(select top 1 serviceid from servicetrees ";
					$cash_invoice_query .= "where servicetreeid=?)),?, GETDATE(),?,'1',5)";
					//print_r($pmt);
					$this->log->info("service insert values - ".json_encode($pmt));
					if(!$db->run_query($cash_invoice_query, 
							array($pmt['userid'], $pmt['serviceid'], $pmt['serviceid'], $pmt['transactiondate'], $pmt['userid']))) {
						$db->rollback();
						$this->message = "Transaction failed";
						
						$error = sqlsrv_errors();
						$this->log->info("error inserting serviceheader info - ".print_r(sqlsrv_errors(), true));
						
						return false;
					} else {
						//echo __LINE__;
						//die(print_r(sqlsrv_errors(), true));
					}
					
					if(!$this->day_invoice_created($pmt['agent'])) {
						$this->create_invoice($pmt['agent']);
					}
					
					$sql = "select top 1 serviceheaderid, serviceid, formid from serviceheader where customerid=";
					$sql .= "(select top 1 ud.customerid from userdevices ud where ud.deviceuserid=? order by ud.userdeviceid desc) ";
					$sql .= "order by serviceheaderid desc";
					
					if($db->run_query($sql, array($pmt['userid']))) {
						while($row = $db->fetch_assoc()) {
							$service_hdr = (int) $row['serviceheaderid'];
							$service_id = (int) $row['serviceid'];
						}
						
						if($service_hdr <= 0) {
							$db->rollback();
							//$this->message = "Transaction failed";
							$this->message = "Service header not found";
							$this->log->info($this->message." - ".$service_hdr);
							// echo __LINE__;
							return false;
						}
					}
					
					$sql = "insert into invoiceheader (invoicedate, customerid, createddate, createdby, paid) values (GETDATE(), ";
					$sql .= "(select top 1 ud.customerid from userdevices ud where ud.deviceuserid=? order by ud.userdeviceid desc), GETDATE(),?,0);";
					
					if(!$db->run_query($sql, array($pmt['userid'], $pmt['userid']))) {
						$db->rollback();
						$this->message = "Transaction failed";
						$this->log->info("invoice header id insert");
						
						$this->log->info("error at line >>> ". __LINE__);
						$this->log->info("user id >>> ". $pmt['userid']);
						$this->log->info(json_encode(sqlsrv_errors()));
						die(print_r(sqlsrv_errors(), true));
						
						return false;
					}
					
					$sql = "select top 1 invoiceheaderid from invoiceheader where customerid=? order by invoiceheaderid desc";
					
					//if($db->run_query($sql, array($pmt['userid'])) && $db->affected_rows() > 0) {
					if($db->run_query($sql, array($pmt['userid']))) {
						while($row = $db->fetch_assoc()) {
							$invoice_header = (int) $row['invoiceheaderid'];
						}
						
						if($invoice_header < 1) {
							// invalid invoice header
							//$this->message = "Transaction failed";
							$this->message = "invoice header not found";
							$db->rollback();
							return false;
						}
					} else {
						$db->rollback();
						//$this->message = "Transaction failed";
						$this->message = "invoiceheader selection failed";
						echo __LINE__;
						die(print_r(sqlsrv_errors(), true));
						return false;
					}
					
					/*$sql = "insert into invoicelines (invoiceheaderid, serviceid, serviceheaderid, amount, ";
					$sql .= "createdate, createdby) values (?,?,?,?,GETDATE(),(select agentid from agents where username=?))";*/
					$sql = "insert into invoicelines (invoiceheaderid, serviceid, serviceheaderid, posreceiptid, marketid, amount, ";
					$sql .= "createdate, createdby) values (?,?,?,?,(select top 1 marketid from markets where marketname=?),?,GETDATE(),(select agentid from agents where username=?))";
					
					if($db->run_query($sql, array($invoice_header, $service_id, $service_hdr, $pmt['billno'], $pmt['market'], $pmt['amount'], $pmt['agent'])) && $db->get_affected_rows() >  0) {
						$sql = "select top 1 invoicelineid from invoicelines where createdby=? order by invoicelineid desc";
						
						if($db->run_query($sql, array($pmt['userid']))) {
							$row = $db->fetch_assoc();
							
							if(is_numeric($row['invoicelineid'])) $invoice_line = $row['invoicelineid'];
						}
							
						
						if($invoice_line > 0) {
							//$sql = "insert into consolidateinvoice (invoicelineid, posreceiptno, amount, createdby, createddate) values (?,?,?,1,GETDATE())";
							
							//if($db->run_query($sql, array($invoice_line, $pmt['billno'], $pmt['amount'])) && $db->get_affected_rows() > 0) {
								if(isset($pmt['params']) && count($pmt['params']) > 0) {
									foreach($pmt['params'] as $key=>$value) {
										$sql = "insert into formdata (formcolumnid, serviceheaderid, value, createddate, createdby) ";
										$sql .= "values ((select formcolumnid from formcolumns where formcolumnname=?),";
										$sql .= "(select top 1 serviceheaderid from serviceheader where customerid=(select ca.customerid from customeragents ca ";
										$sql .= "join agents a on ca.agentid=a.agentid where a.username=?) order by serviceheaderid desc), ?,GETDATE(),";
										$sql .= "(select top 1 agentid from agents where username=?))";
										
										//echo ("query => ".$sql."<br />");
										//echo("formdata key => ".$key.", value => ".$value.", other => ".json_encode($pmt['params']).", agent => ".$pmt['agent']."<br />");
										
										if($db->run_query($sql, array($key, $pmt['agent'], $value, $pmt['agent'])) && $db->get_affected_rows() <= 0)
										{
											// rollback transaction
											$this->log->info("rolling back transaction");
											$db->rollback();
											$this->message = "Transaction failed";
											//echo __LINE__;
											//die(print_r(sqlsrv_errors(), true));
											return false;
										}
									}
								}
								
								// commit transaction
								$db->commit();
								$this->response_code = HTTP_OK;
								$this->message = "Transaction successful";
								return true;
							/*} else {
								//$this->message = "Transaction failed";
								$this->message = "consolidateinvoice";
								// rollback transaction
								$db->rollback();
								echo __LINE__;
								die(print_r(sqlsrv_errors(), true));
							}*/
						} else {
							$db->rollback();
							//$this->message = "Transaction failed";
							$this->message = "Invoice not found";
							echo __LINE__;
							die(print_r(sqlsrv_errors(), true));
							return false;
						}
					} else {
						$this->message = "Invoice lines";
						echo __LINE__;
						die(print_r(sqlsrv_errors(), true));
						return false;
					}
				}
				
			}
			
			return false;
		}
		
		public function calc_eod($username = NULL) {
			if($username != NULL && is_string($username)) {
				global $db;
				$sql = "select ih.invoiceheaderid as invoice, CONVERT(NVARCHAR, ih.invoicedate, 120) as invoicedate,(select sum(amount) from invoicelines ";
				$sql .= "where invoiceheaderid=ih.invoiceheaderid) as total ";
				$sql .= "from invoiceheader ih where createdby = (select top 1 userid from agents where username=?) and paid=0";
				
				if($db->run_query($sql, array($username))) {
					$txns = array();
					
					while($row = $db->fetch_assoc()) {
						array_push($txns, $row);
					}
					
					$this->message = "End of day results";
					$this->log->info($this->message);
					
					$this->log->info("end of day txns #### ".json_encode($txns));
					
					return count($txns) > 0 ? $txns : NULL;
				} else {
					$error = sqlsrv_errors();
					
					if($error != NULL) {
						$this->log->info("sql state - ".$error['SQLSTATE']);
						$this->log->info("code - ".$error['code']);
						$this->log->info("message - ".$error['message']);
					}
				}
			}
			
			$this->message = "Customer not found";
			
			return NULL;
		}
		
		public function get_sbp_details($ref_no) {
			global $db;
			$sbp = NULL;
			
			$sql  = "select top 1 p.PermitNo,p.IssueDate,p.ExpiryDate,c.CustomerName,p.ServiceHeaderID ";
			$sql .= "from ServiceHeader sh join Permits p on p.ServiceHeaderID=sh.ServiceHeaderID join Customer c on sh.CustomerID=c.CustomerID where p.PermitNo=?";
			
			if($db->run_query($sql, array($ref_no))) {
				$this->log->info("Permit found");
				while($sbp = $db->fetch_assoc()) {
					$this->log->info("received sbp info => ".json_encode($sbp));
					
					if(!isset($sbp) || count($sbp) <= 0) $sbp = NULL;
					unset($sbp['ServiceHeaderID']);
					break;
				}
			} else {
				$message = "Permit not found";
			}
			
			return $sbp;
		}
		
		public function get_message() {
			return $this->message;
		}
		
		public function get_response_code()
		{
			return $this->response_code;
		}
		
	};
	
	$transaction = new Transaction();
	
?>
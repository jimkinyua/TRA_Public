<?php include_once("util.php"); ?>

<?php
	
	define('PAGE_SIZE', 50);
	
	class Service
	{
		
		private $service;
		private $message;
		
		
		public function __construct()
		{
			$this->service = array();
			
			$this->service['id'] = "";
			$this->service['code'] = "";
			$this->service['name'] = "";
			$this->service['parent'] = "-1";
			$this->service['child'] = "-1";
			$this->service['param'] = "-1";
		}
		
		public function __destruct()
		{
			$this->service = NULL;
		}
		
		public function get_services($market = 0, $parent_id)
		{
			global $db;
			
			$sql = "select st.servicetreeid as serviceid, s.servicename, st.parentid as parent, st.isservice from servicetrees st ";
			$sql .= "inner join services s on s.serviceid=st.serviceid where st.parentid=?";
			
			//$db->run_query($sql, array($market, $parent_id));
			$db->run_query($sql, array($parent_id));
					
			if($db->get_stmt() == false) {
				$this->message = "Service not found";
				die( print_r( sqlsrv_errors(), true) );
			}
			
			$services = array();
			
			while($row = $db->fetch_assoc()) {
				$row['code'] = "-1";
				$row['parent'] = $row['parent'] == 0 ? "0" : "1";
				$row['child'] = $row['isservice'] == 0 ? "0" : "1";
				$row['params'] = "-1";
				unset($row['isservice']);
				array_push($services, $row);
			}
			//return "{\"id\":\"3\", \"code\":\"\", \"name\":\"\", \"parent\":\"2\", \"child\":\"0\", \"params\":\"INT-param1, DBL-param2, STR-param3\", \"charge\":\"100\"}";
			return count($services) > 0 ? $services : NULL;
		}
		
		//public function get_offline_services($offset, $marketid) {
		public function get_offline_services($offset) {
			//[{"id":"1", "code":"", "name":"", "parent":"-1", "child":"1", "params":"-1", "charge":"-1"}]
			
			global $db;
			$sql = "select servicetreeid as serviceid, description as servicename, parentid as parent, isservice,  ";
			$sql .= "(select top 1 amount from servicecharges sc where serviceid=st.serviceid order by chargeid desc) as charge ";
			$sql .= "from servicetrees st order by st.servicetreeid asc offset ? rows fetch next ? rows only";
			/*$sql = "select servicetreeid as serviceid, description as servicename, parentid as parent, isservice,  ";
			$sql .= "(select top 1 amount from servicecharges sc where serviceid=st.serviceid order by chargeid desc) as charge ";
			$sql .= "from servicetrees st marketservices ms where ms.marketid=? order by st.servicetreeid asc offset ? rows fetch next ? rows only";*/
			
			/*if(!isset($market)) {
				$this->message="Market is missing";
				return NULL;
			}*/
			
			if(!isset($offset)) {
				$this->message = "Please provide a page";
				return NULL;
			}
			
			if(!is_string($offset) || !is_str_contain($offset, '-')) {
				$this->message = "Invalid page format";
				return NULL;
			}
			
			$page = explode("-", $offset);
			
			if($page == FALSE && count($page) != 2) {
				$this->message = "Invalid page";
				return NULL;
			}
			
			$page[0] = (int) $page[0];
			$page[1] = (int) $page[1];
			
			$db->run_query($sql, array($page[0], $page[1] > 0 ? $page[1] : 50));
			//$db->run_query($sql, array($page[0], PAGE_SIZE));
			
			if($db->get_stmt() == false) {
				$this->message = "Service(s) not found";
				die( print_r( sqlsrv_errors(), true) );
				//return NULL;
			}
			
			$services = array();
			$stmt = $db->get_stmt();
			
			while(($row = $db->fetch_assoc_wr($stmt)) != NULL) {
				$row['code'] = "-1";
				$row['child'] = $row['isservice'] == 0 ? "0" : "1";
				//print_r($row);
				if(strcmp($row['isservice'], "1") == 0)  {
					$params = $this->get_service_params($row['serviceid']);
					
					//if(isset($params) && is_array($params) && count($params) > 0) {
					if($params != NULL && strlen(trim($params)) > 0) {
						$row['params'] = $params;
					} else {
						$row['params'] = "-1";
					}
				} else {
					$row['params'] = "-1";
				}
				
				$row['charge'] = $row['charge'] == NULL ? "-1" : $row['charge'];
				//die(print_r($row));
				unset($row['isservice']);
				array_push($services, $row);
			}
			
			//if(count($services) > 0) $this->message = "Services found";
			if(count($services) > 0) {
				$this->message = "Service(s) found";
				return $services;
			} else {
				$this->message = "Service(s) not found";
				return NULL;
			}
			//die(print_r($services));
			//die(count($services));
			//return count($services) > 0 ? $services : NULL;
		}
		
		public function get_service_params($service_id) {
			global $db;
			/*$sql = "select fc.formcolumnname, cd.columndatatypename from formcolumns fc ";
			$sql .= "inner join formdata fm on fc.formcolumnid=fm.formcolumnid ";
			$sql .= "inner join columndatatype cd on cd.columndatatypeid=fc.columndatatypeid where fm.serviceheaderid=?";*/
			$sql = "select fc.formcolumnname, cd.columndatatypename from formcolumns fc ";
			$sql .= "inner join columndatatype cd on fc.columndatatypeid=cd.columndatatypeid ";
			$sql .= "where fc.formid=(select top 1 sc.formid from servicetrees st inner join services s on s.serviceid=st.serviceid ";
			$sql .= "inner join servicecategory sc on sc.servicecategoryid=s.servicecategoryid where st.servicetreeid=?)";
			
			$db->run_query($sql, array($service_id));
					
			if($db->get_stmt() == false) {
				$this->message = "Service details not found";
				die( print_r( sqlsrv_errors(), true) );
			}
			
			$params = "";
			
			while($row = $db->fetch_assoc()) {
				$params .= starts_with(strtoupper($row['columndatatypename']), "TEXT") ? "STR" : "NUM";
				$params .= "-";
				$params .= $row['formcolumnname'].",";
			}
			
			$params = trim(substr($params, 0, -1));
			//echo "params ".$params;
			
			return strlen($params) > 0 ? $params : NULL;
		}
		
		public function get_service_dtls($serviceid)
		{
			global $db;
			$sql = "select st.servicetreeid as serviceid, s.servicename, st.parentid as parent, st.isservice from servicetrees st ";
			$sql .= "inner join services s on s.serviceid=st.serviceid inner join servicecharges sc on sc.serviceid=s.serviceid ";
			$sql .= "where st.servicetreeid=?";
			
			$db->run_query($sql, array($serviceid));
					
			if($db->get_stmt() == false) {
				$this->message = "Service details not found";
				die( print_r( sqlsrv_errors(), true) );
			}
			
			$service_dtls = array();
			
			while($row = $db->fetch_assoc()) {
				$row['code'] = "-1";
				$row['parent'] = $row['parent'] == 0 ? "0" : "1";
				$row['child'] = $row['isservice'] == 0 ? "0" : "1";
				$row['params'] = "-1";
				unset($row['isservice']);
				array_push($service_dtls, $row);
			}
			
			return count($service_dtls) > 0 ? $service_dtls : NULL;
			//return "{\"id\":\"3\", \"code\":\"\", \"name\":\"\", \"parent\":\"2\", \"child\":\"0\", \"params\":\"INT-param1, DBL-param2, STR-param3\", \"charge\":\"100\"}";
		}
		
		public function get_message() {
			return $this->message;
		}
		
	};
	
	$service = new Service();
	
?>
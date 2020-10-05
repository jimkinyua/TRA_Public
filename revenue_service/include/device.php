<?php
	
	class Device
	{
		private $device;
		
		public function __construct()
		{
			//$this->device = array();
		}
		
		public function __destruct()
		{
			unset($this->device);
		}
		
		public function block_device($device_serial)
		{
			return false;
		}
		
		public function load_device_info($serial)
		{
			if($serial != NULL && !empty($serial))
			{
				global $db;
				$sql = "select top 1 d.deviceid, (select devicetypename from devicetype dt where devicetypeid=d.devicetypeid) as devicetype, ";
				$sql .= "d.description, d.deviceserialno, d.status, d.macaddress from devices d where d.deviceserialno=? order by createddate desc";
				
				$db->run_query($sql, array($serial));
				
				while($row = $db->fetch_assoc()) {
					$this->device['deviceid'] = $row['deviceid'];
					$this->device['devicetype'] = $row['devicetype'];
					$this->device['description'] = $row['description'];
					$this->device['serial'] = $row['deviceserialno'];
					$this->device['macaddress'] = $row['macaddress'];
					$this->device['serial'] = trim($row['deviceserialno']);
					$this->device['lockstatus'] = $row['status'] == 1 ? false : true;
				}
				
			}
			
			return isset($this->device)? true : NULL;
		}
		
		public function is_alowed_to_transact()
		{
		}
		
		public function is_pos()
		{
			return strcmp($this->get('devicetype'), "POS") == 0;
		}
		
		public function is_android()
		{
			return (strcmp(trim($this->get('devicetype')),"PHONE") == 0);
		}
		
		public function get($key) {
			return isset($this->device[$key]) ? $this->device[$key] : NULL;
		}
		
		public function device_is_locked()
		{
			return $this->get('lockstatus');
		}
		
	}
	
	$device = new Device();
	
?>
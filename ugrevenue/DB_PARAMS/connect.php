<?php	
//require_once('config.php');

$myServer = "VUGREVENUE";
$myUser = "ugrev";
$myPass = 'ugrev';
$myDB = "COUNTYREVENUE";

$connectionInfo = array("UID" => $myUser, "PWD" => $myPass, "Database"=> $myDB, "ReturnDatesAsStrings" => true,"CharacterSet" => "UTF-8");
//$db = mssql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer");
$db = sqlsrv_connect( $myServer, $connectionInfo);
if ($db)
{
	//echo "Database Connection successful";
} else
{
	//echo "Database Connection Failed";
	//echo "Parameters are $myUser $myPass $myDB";
}

?>
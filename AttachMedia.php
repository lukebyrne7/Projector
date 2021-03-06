<?php require_once('Connections/projector.php'); 

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

?>
<?php
//print "We are going to attach the media " . $_GET['Id'] . " to the step: " . $_GET['StepId'] . " with the Project ID: " . $_GET['ProjectId'];

$type = "image";	// default to attaching an image
$typeId = 0;			// 0 is the value for Image Media types
if (isset($_GET['type'])) {
	$type = $_GET['type'];
	switch($type) {
		case "video" : $typeId = 1;
		break;
		default : $typeId = 0;
		break;
	}
}

mysql_select_db($database_projector, $projector);

$sqlCommand = sprintf("INSERT INTO MediaAttach SET MediaId = %s, ProjectId = %s, StepId = %s, Type = %s",
										 GetSQLValueString($_GET['MediaId'], "int"),
										 GetSQLValueString($_GET['ProjectId'], "int"),
										 GetSQLValueString($_GET['StepId'], "int"),
										 GetSQLValueString($typeId, "int"));
										 
$Result1 = mysql_query($sqlCommand, $projector) or die(mysql_error());

$updateGoTo = "EditStep.php";
$updateGoTo .= "?Id=" . $_GET['StepId'];

header(sprintf("Location: %s", $updateGoTo));
?>

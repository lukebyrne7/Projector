<?php require_once('Connections/projector.php'); ?>
<?php
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

$slideId = "-1";
if (isset($_GET['Id'])) {
  $slideId = $_GET['Id'];
}
$ProjectName = "";

if (isset($_SESSION['ProjectName']))
	$ProjectName = $_SESSION['ProjectName'];

mysql_select_db($database_projector, $projector);
$query_steps = "SELECT * FROM Slides WHERE Id = " . $slideId;
$steps = mysql_query($query_steps, $projector) or die(mysql_error());
$row_steps = mysql_fetch_assoc($steps);
$projectId = $row_steps['ProjectId'];
$totalRows_steps = mysql_num_rows($steps);

session_start();
$_SESSION['ProjectId'] = $projectId;
$_SESSION['SlideId'] = $row_steps['Id'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// Default to performing an upate unless we posted a action on the url then use that
$action = "Update";
$actionTitle = "Edit";
if (isset($_GET["action"])) {
	$action = $_GET["action"];
	$actionTitle = $_GET["action"];
}

// if we are adding a slide set the Duration, Image Time, and Fade Duration fields to good default values
if ($action == "Add") {
	$durationValue = 30;
	$imageTimeValue = 10;
	$fadeDurationValue = 100;
} else
{
	$durationValue = $row_steps['Duration'];
	$imageTimeValue = $row_steps['ImageTime'];
	$fadeDurationValue = $row_steps['FadeDuration'];
}

if (isset($_GET["ProjectId"])) {
	$projectId = $_GET["ProjectId"];
}


if (isset($_POST["MM_action"])) {
	
	if ($_POST["MM_action"] == "Add") {
			$sqlCommand = sprintf("INSERT INTO Slides SET ProjectId = %s, SortOrder = %s, Duration = %s, ImageTime = %s, FadeDuration = %s, Title = %s, TemplateName = %s, Text = %s",
                       GetSQLValueString($_POST['ProjectId'], "int"),
                       GetSQLValueString($_POST['SortOrder'], "int"),
											 GetSQLValueString($_POST['Duration'], "int"),
                       GetSQLValueString($_POST['ImageTime'], "int"),
											 GetSQLValueString($_POST['FadeDuration'], "int"),
                       GetSQLValueString($_POST['Title'], "text"),
                       GetSQLValueString($_POST['TemplateName'], "text"),
											 GetSQLValueString($_POST['Text'], "text"));
//		print "sqlCommand: " . $sqlCommand;									 
/* To Do get the id of the record we just added											 
		$sqlComamand .= ";SELECT last_insert_id( );"; 									 
*/
	} else
  	$sqlCommand = sprintf("UPDATE Slides SET ProjectId=%s, SortOrder=%s, Duration = %s, ImageTime = %s, FadeDuration = %s, Title = %s, TemplateName = %s, `Text`=%s WHERE Id=%s",
                       GetSQLValueString($_POST['ProjectId'], "int"),
                       GetSQLValueString($_POST['SortOrder'], "int"),
											 GetSQLValueString($_POST['Duration'], "int"),
                       GetSQLValueString($_POST['ImageTime'], "text"),
                       GetSQLValueString($_POST['FadeDuration'], "text"),
                       GetSQLValueString($_POST['Title'], "text"),
                       GetSQLValueString($_POST['TemplateName'], "text"),
											 GetSQLValueString($_POST['Text'], "text"),
                       GetSQLValueString($_POST['Id'], "int"));

//	print "sqlCommand: " . $sqlCommand;
  mysql_select_db($database_projector, $projector);
  $Result1 = mysql_query($sqlCommand, $projector) or die(mysql_error());

  $updateGoTo = "ViewSlides.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
	$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
	$updateGoTo .= "ProjectId=" . $projectId; 
  header(sprintf("Location: %s", $updateGoTo));
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Edit Slide</title>
<script type="text/javascript" src="jquery-ui-1.8.21/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.21/js/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="js/utility.js"></script>
<script type="text/javascript">

function attachMedia(projectId)
{
	$.ajax({
  	url: "MediaData.php?ProjectId=" + projectId + "&Size=450x250&attachTo=slide",
  	cache: false
	}).done(function( html ) {
		$("#Dialog").removeClass("hideMe");
  	$("#Dialog").append(html);
		$( "#Dialog" ).dialog({
			height: 600,
			width: 500,
			modal: true
		});
	});
}

</script>
<link href="_css/main.css" rel="stylesheet" type="text/css" />
<style type="text/css">
/* BeginOAWidget_Instance_2921536: #OAWidget */

.blueLayer {
	height: 300px;
	width: 300px;
}

.layer {
	font-family: Helvetica Neue, Helvetica, nimbus-sans, Arial, "Lucida Grande", sans-serif;
	background-color: #eee;
	margin-right: auto;
	margin-left: auto;
	padding: 15px;
	border-color : #666;
	border-style: solid;
	border-width: 3px;
	opacity : 1;
	-moz-border-radius : 10px;
	-webkit-border-radius : 10px;
	border-radius : 10px;
	width: 800px;
	-moz-box-shadow: 3px 3px 5px 6px #ccc;
	-webkit-box-shadow: 5px 5px 5px 6px #ccc;
	box-shadow: 3px 3px 5px 6px #ccc;
}

body {
	font-family: Helvetica Neue, Helvetica, nimbus-sans, Arial, "Lucida Grande", sans-serif;
}

label {
	float: left;
	text-align: right;
	margin-top: 5px;
	margin-bottom: 5px;
	margin-right: 15px;
	padding-top: 5px;
	font-size: 1.2em;
	width : 120px;
	color:#555;
}

.descriptionText {	
	margin-top: 5px;
	margin-bottom: 5px;
	font-size: 1em;
	margin-top: 5px;
	margin-bottom: 5px;
}

.wideLabel {
		width: 305px;
}

input
{
	font-size: 1.2em; 
	padding: 5px; 
	border: 1px solid #b9bdc1;  
	color: #444;	
	margin-top:5px;
	margin-bottom:5px;
}
	
input:focus{
	background-color:LightYellow;
	color : #222;	
}
	
textarea {
	font-size: 1em;
	padding: 5px;
	height: 110px;
	color: #444;
	border: 1px solid #b9bdc1;
	margin-top: 5px;
	margin-bottom: 5px;
	width: 550px;
}

select {
	font-size:1.2em;
	margin-top : 5px;
	margin-bottom: 5px;
	border: 1px solid #b9bdc1;  
	color: #444;		
}

legend {
	font-size: 1.5em;
	text-align:center;
	color : #222;
}
.hint{
	display:none;
}
	
.field:hover .hint {  
	position: absolute;
	display: block;  
	margin: -30px 0 0 455px;
	color: #FFFFFF;
	padding: 7px 10px;
	background: rgba(0, 0, 0, 0.6);
	
	-moz-border-radius: 7px;
	-webkit-border-radius: 7px;
	border-radius: 7px;	
	}

.clearFloat {
	clear:both;
}

.verticalAlign {
	float : left;
}

.lineUp {
}

.imageDiv {
	margin-left: 10px;
	float: left;
}


		
/* EndOAWidget_Instance_2921536 */
.blueButton {
	background-color: #3AADEF;
	color:#FFF;
	padding-left:30px;
	padding-right:30px;
}

/* small button for detach under images*/
a.smallRedButton {
	color : #FFF;
	background-color: #C03;
	padding-left:5px;
	padding-right:5px;
}


.hideMe {
	visibility:hidden;
}

.captionDiv {
	white-space: nowrap;
	max-width: 200px;
	overflow: hidden;
	text-overflow: ellipsis;
	font-size: 14px;
}
</style>
<link href="jquery-ui-1.8.21/css/smoothness/jquery-ui-1.8.22.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<!--<link href="css/formStyle.css" rel="stylesheet" type="text/css" />-->
<script type="text/javascript">

function updateThumbnailImage(object)
{
	var thumbnailURL = object.value;
	console.log('thumbnailURL: ' + thumbnailURL);	
	document.getElementById('thumbnailImage').src = thumbnailURL;
}

tinyMCE.init({
				
        mode : "textareas",
				theme : "advanced",
					// Theme options
				theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,charmap",
				theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
			/*	theme_advanced_statusbar_location : "bottom",*/
				theme_advanced_resizing : true,
});

</script>
</head>

<body>
<?php include("HeaderNav.php") ?>
<div class="subNav"><a href="ViewProjects.php">View Projects </a>| <a href="ViewSteps.php?ProjectId=<?php echo $projectId; ?>">View Steps</a> | <a href="EditStep.php?action=Add"><img src="_images/icons/Plus16x16.gif" height="16" width="16" /> Add Step</a> | <a href="ViewMedia.php">View Media</a> | <a href="EditMedia.php?action=Add"><img src="_images/icons/Plus16x16.gif" height="16" width="16" /> Add Media</a></div></div>
<div class="layer">
	<div class="subSubNav"><a href="ViewSlides.php?ProjectId=<?php echo $projectId; ?>">View Slides</a> | <a href="EditSlide.php?action=Add&ProjectId=<?php echo $projectId; ?>"><img src="_images/icons/Plus16x16.gif" height="16" width="16" />Add Slide</a></div>
	<form action="<?php echo $editFormAction; ?>" id="updateForm" name="updateForm" method="POST">
  <fieldset>	
    <legend><?php echo $actionTitle; ?> Slide</legend>
    <label for="Id">Id:</label>
    <input name="Id" type="text" id="Id" value="<?php echo $row_steps['Id']; ?>" size="5" readonly="readonly" />
    <div class="clearFloat"></div>
    <label for="ProjectId">ProjectId:</label>
    <input name="ProjectId" type="text" id="ProjectId" placeholder="Project Name" value="<?php echo $projectId; ?>" size="5" /> <?php echo $ProjectName; ?>
    <div class="clearFloat"></div>
    <label for="Title">Title:</label>
    <input name="Title" type="text" class="wideLabel" id="Title" value="<?php echo $row_steps['Title']; ?>" />
    <div class="clearFloat"></div>
    <label for="grade">Order:</label>
    <input name="SortOrder" type="text" id="grade" value="<?php echo $row_steps['SortOrder']; ?>" size="5" />
    <div class="clearFloat"></div>
    <label for="grade">Duration:</label>
    <input name="Duration" type="text" id="grade" value="<?php echo $durationValue; ?>" size="5" /> (seconds)
    <div class="clearFloat"></div>
    <label for="grade">Image Time:</label>
    <input name="ImageTime" type="text" id="grade" value="<?php echo $imageTimeValue; ?>" size="5" /> (seconds)
    <div class="clearFloat"></div>
    <label for="grade">FadeDuration:</label>
    <input name="FadeDuration" type="text" id="grade" value="<?php echo $fadeDurationValue; ?>" size="5" /> (milliseconds)
    <div class="clearFloat"></div>
    <label for="Text">Text:</label>
    <textarea name="Text" id="Text"><?php echo $row_steps['Text']; ?></textarea>
    <div class="clearFloat"></div>
     <div class="lineUp">
     	<label for="TemplateName">Template:</label>
      <select name="TemplateName" id="TemplateName" value="<?php echo $row_steps['TemplateName']; ?>">
      	<option value="3xLandscape" <?php if ($row_steps['TemplateName'] == "3xLandscape") echo ' selected="selected" '; ?>>3xLandscape</option>
        <option value="2xLandscape" <?php if ($row_steps['TemplateName'] == "2xLandscape") echo ' selected="selected" '; ?>>2xLandscape</option>
        <option value="1Portrait1Landscape" <?php if ($row_steps['TemplateName'] == "1Portrait1Landscape") echo ' selected="selected" '; ?>>1Portrait1Landscape</option>
      </select>
    </div>
    <div class="clearFloat"></div>
    <?php if ($action == "Update"): ?>
    <div>
    	<label for="thumbnail">Media:</label>
      <div class="imageDiv">
      	<?php if ($action == "Update") include("AttachedSlideQuery.php"); ?>
    	</div>
    </div>
    <div class="clearFloat"></div>
    <div style="text-align:center">    	
    	<input class="blueButton" type="button" name="button" id="button" value="Attach Media" onclick="attachMedia(<?php echo $projectId; ?>)" />
      
    </div>
    <?php endif; ?>
  </fieldset>
  <div style="text-align:center">
    <input class="blueButton" type="submit" name="button" id="button" value="<?php echo $action; ?>" />
    <input class="whiteButton" type="button" name="button" id="button" value="Cancel" onclick="goToURL('ViewSlides.php?ProjectId=<?php echo $projectId; ?>')" />
  </div>
  <input type="hidden" name="MM_action" value="<?php echo $action; ?>" />
	</form>
</div>
<div id="footer">
&copy;2012 Pearson Foundation
</div>
<!-- this is used for displaying a dialog with the media to be attached -->
<div id="Dialog" class="hideMe">
Select the media to be attached:
</div>
</body>
</html>
<?php
mysql_free_result($steps);
?>

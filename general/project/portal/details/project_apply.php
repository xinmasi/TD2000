<?php
include_once("inc/auth.inc.php");
$proj_id = isset($PROJ_ID)?$PROJ_ID:0;

$query = "update proj_project set proj_status = '1' where PROJ_ID = '$proj_id' and PROJ_OWNER = '".$_SESSION['LOGIN_USER_ID']."' and PROJ_MANAGER != '' and PROJ_MANAGER != 'choose'";
exequery(TD::conn(),$query);

header("location:../details/proj_progression.php?VALUE=2&PROJ_ID=$proj_id ");
?>
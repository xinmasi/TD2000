<?php 
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
$m_start = $_POST['m_start'];
$m_end   = $_POST['m_end'];
$mr_room = $_POST['MR_ROOM'];
$m_id    = $_POST['m_id'];

$sql = "select M_ROOM from meeting where M_ID = '".$m_id."'";
$cursor = exequery(TD::conn(),$sql);
$ROW=mysql_fetch_array($cursor);
$M_ROOM_OLD = $ROW['M_ROOM'];
if($M_ROOM_OLD != $mr_room)
{
	$sql = "select count(*) as ifint from meeting where (('".$m_start."'<  M_START and '".$m_end."'> M_START) or ('".$m_start."'>= M_START and '".$m_end."'<= M_END ) or ( '".$m_start."' < M_END and '".$m_end."'> M_END )) and M_ROOM = '".$mr_room."'";
	$cursor = exequery(TD::conn(),$sql);
	$ROW=mysql_fetch_array($cursor);
	if($ROW['ifint'] > 0)
	{
		echo 1;
		exit;
	}
}
echo 0;
exit;
?>
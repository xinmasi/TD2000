<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("考勤统计列表");
$DATE1=$_GET['DATE1'];
$DATE2=$_GET['DATE2'];
$DUTY_TYPE1=$_GET['DUTY_TYPE'];
$DEPARTMENT1=$_GET['DEPARTMENT1'];
//include_once("menu_top.php");
?>

<iframe name="menu_top" id="menu_top" scrolling="auto" src="menu_top.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&DUTY_TYPE1=<?=$DUTY_TYPE?>&DEPARTMENT1=<?=$DEPARTMENT1?>" noresize frameborder="0" style="width:100%;height:100%;"></iframe>
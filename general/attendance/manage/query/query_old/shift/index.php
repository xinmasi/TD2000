<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("考勤统计列表");
$DATE1=$_GET['DATE1'];
$DATE2=$_GET['DATE2'];
$DUTY_TYPE1=$_GET['DUTY_TYPE'];
$DEPARTMENT1=$_GET['DEPARTMENT1'];
//include_once("menu_top_shift.php");
?>
<div>
    <div>
    <iframe name="menu_top" id="menu_top" scrolling="auto" src="menu_top_shift.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&DUTY_TYPE1=<?=$DUTY_TYPE?>&DEPARTMENT1=<?=$DEPARTMENT1?>" noresize frameborder="0" style="height:100%;width:100%"></iframe>
    </div>
</div>
<!--
<frameset rows="30,*"  cols="*" frameborder="NO" border="0" framespacing="0" id="frame1">
    <frame name="menu_top" id="menu_top" scrolling="no" noresize src="menu_top_shift.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&DUTY_TYPE1=<?=$DUTY_TYPE?>&DEPARTMENT1=<?=$DEPARTMENT1?>" frameborder="0">
    <frame name="menu_main1" id="menu_main1" scrolling="auto" noresize src="index1.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&DUTY_TYPE1=<?=$DUTY_TYPE?>&DEPARTMENT1=<?=$DEPARTMENT1?>" frameborder="0">
</frameset>
-->
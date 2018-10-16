<?
include_once("inc/auth.inc.php");

include_once("inc/utility_all.php");
$MENU_TOP=array(
   array("text" => _("考勤审批"), "href" => "confirm/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("人员考勤记录"), "href" => "user_manage/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("考勤统计"), "href" => "query/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif")
);
$CUR_DATE=date("Y-m-d",time());
$MENU_RIGHT='<div style="float:left;padding-top:6px;"><b>'._("今日").' '.$CUR_DATE.' '.get_week($CUR_DATE).'</b></div>';
//$MENU_RIGHT.=' <div style="float:left;padding-top:6px;"><a href="import.php?GROUP_ID='.$GROUP_ID.'"> 导入上下班数据</a></div>

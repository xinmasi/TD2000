<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("考核任务管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/score.gif"),
   array("text" => _("新建考核任务"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/score.gif"),
   array("text" => _("考核数据查询"), "href" => "scoredate/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/score.gif")
);

include_once("inc/menu_top.php");
?>

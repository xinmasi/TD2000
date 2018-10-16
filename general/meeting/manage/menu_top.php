<?
include_once("inc/auth.inc.php");

if($CYCLE==1)
{
	$MENU_TOP=array(
	   array("text" => _("待批周期性会议"), "href" => "manage1.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("待批会议"), "href" => "manage.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("已准会议"), "href" => "manage.php?M_STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("进行中会议"), "href" => "manage.php?M_STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("未批准会议"), "href" => "manage.php?M_STATUS=3", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("已结束会议"), "href" => "manage.php?M_STATUS=4", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("会议纪要审批"), "href" => "summary.php?M_STATUS=5", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif")
	);
}
else
{
	$MENU_TOP=array(
	   array("text" => _("待批会议"), "href" => "manage.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("待批周期性会议"), "href" => "manage1.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("已准会议"), "href" => "manage.php?M_STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("进行中会议"), "href" => "manage.php?M_STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("未批准会议"), "href" => "manage.php?M_STATUS=3", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("已结束会议"), "href" => "manage.php?M_STATUS=4", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("会议纪要审批"), "href" => "summary.php?M_STATUS=5", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif")
	);
}

include_once("inc/menu_top.php");
?>

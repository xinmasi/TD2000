<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("�ճ�����"), "href" => "new.php?USER_ID=$USER_ID&CAL_TIME=$CAL_TIME&TIME_DIFF=$TIME_DIFF", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/email_close.gif"),
   array("text" => _("����������"), "href" => "new_affair.php?USER_ID=$USER_ID&CAL_TIME=$CAL_TIME&TIME_DIFF=$TIME_DIFF", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/email_open.gif"),
   array("text" => _("����"), "href" => "task_edit.php?USER_ID=$USER_ID&CAL_TIME=$CAL_TIME&TIME_DIFF=$TIME_DIFF", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/edit.gif")
);

$dataType = '';
include_once("inc/menu_top.php");
?>
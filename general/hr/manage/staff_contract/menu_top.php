<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("合同管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("新建合同信息"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("合同信息查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("批量导入导出"), "href" => "in_out_index.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/inout.gif"),
   array("text" => _("试用到期查询"), "href" => "due_query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("合同到期查询"), "href" => "due_query1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("未签劳动合同"), "href" => "no_contract.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>

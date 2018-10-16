<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
         array("text" => _("进行中项目"), "href" => "left.php?PROJ_STATUS=2", "target" => "file_tree", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/file_folder.gif"),
         array("text" => _("历史项目查询"), "href" => "left.php?PROJ_STATUS=3", "target" => "file_tree", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/file_folder.gif")
      );
      
include_once("inc/menu_top.php");
?>      
      

<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("自定义字段");

$SCRIPT='
function delete_code(CODE_ID,CODE_NO,CODE_NAME)
{
 var msg = sprintf("'._("确认要删除该自定义字段 '%s' 模块吗？该模块下的所有自定义字段和数据都会被删除。").'", CODE_NAME);
 if(window.confirm(msg))
 {
  URL="delete.php?CODE_ID=" + CODE_ID;
  parent.code_edit.location=URL;
 }
}';


$MENU_HEAD=array("text" => _("自定义字段管理"), "img" => MYOA_STATIC_SERVER."/static/images/menu/system.gif", "class" => "");
$module='<table class="TableBlock" width="100%" align="center">';

 $query = "SELECT * from sys_code where PARENT_NO='PROJ_TYPE' order by CODE_ORDER";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
   $CODE_ID=$ROW["CODE_ID"];
   $CODE_NO=$ROW["CODE_NO"];
   $CODE_NAME=$ROW["CODE_NAME"];
   $CODE_EXT=unserialize($ROW["CODE_EXT"]);
	 if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
		  $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
   
   $CODE_FLAG=$ROW["CODE_FLAG"];
   $CODE_ORDER=$ROW["CODE_ORDER"];

  $module.='  <tr class="TableData" title="'.$CODE_NAME.'">
    <td align="center"><b><a href="define/?CODE_ID='.$CODE_NO.'&CODE_NAME='.$CODE_NAME.'" target="code_edit">'.$CODE_NAME.'</a></b></td>
    <td align="center">
	  <a href="define/?CODE_ID='.$CODE_NO.'&CODE_NAME='.$CODE_NAME.'" target="code_edit">'._("设置").'</a>
    </td>
  </tr>';
}//while
$module.='</table>';

$MENU_LEFT=array();
$target="code_edit";

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("项目类型自定义"), "href" => "", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => $module, "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("全局字段管理"), "href" => "define/?CODE_ID=G&CODE_NAME="._("全局字段列表"), "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");


include_once("inc/menu_left.php");
?>
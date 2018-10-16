<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$SCRIPT='
function delete_code(TYPE_ID,ITEM_NAME)
{
 var msg = sprintf("'._("确认要删除积分项主分类 '%s' 吗？下级积分项也将被删除").'", ITEM_NAME);
 if(window.confirm(msg))
 {
  URL="delete.php?TYPE_ID=" + TYPE_ID;
  location=URL;
 }
}';

$MENU_HEAD=array("text" => _("积分项主分类设置"), "img" => MYOA_STATIC_SERVER."/static/images/menu/system.gif", "class" => "");
$module_head='<table class="TableBlock" width="100%">';
$module_line_oa=$module_line_hr=$module_line_man="";
$module_oa=$module_hr=$module_man="";
$query = "SELECT * from hr_integral_item_type order by TYPE_ORDER";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $TYPE_ID=$ROW["TYPE_ID"];
  $TYPE_NO=$ROW["TYPE_NO"];
  $TYPE_NAME=$ROW["TYPE_NAME"];
  $TYPE_FROM=$ROW["TYPE_FROM"];
  if($TYPE_NAME == "岗位"){
      $TYPE_NAME = "应聘岗位";
  }
  $module_line="";
  $module_line.='<tr class="TableData" title="'.$TYPE_NAME.'">
   <td width="50%">
      <b>'.$TYPE_NAME.'</b>
   </td>
   <td nowrap>
     <a href="edit.php?TYPE_ID='.$TYPE_ID.'" target="code_edit"> 编辑</a>&nbsp;
     <a href="func?TYPE_ID='.$TYPE_ID.'" target="code_edit"> 下一级</a>&nbsp;';

  if($TYPE_FROM==3)
     $module_line.='<a href="javascript:delete_code('.$TYPE_ID.',\''.$TYPE_NAME.'\');"> '._("删除").'</a>';
   $module_line.='</td></tr>';
   if($TYPE_FROM==1)
   		$module_line_oa.=$module_line;
   	else if($TYPE_FROM==2)
   		$module_line_hr.=$module_line;
   	else if($TYPE_FROM==3)
   		$module_line_man.=$module_line;
}//while
$module_end='</table>';
if($module_line_oa!="")
	$module_oa=$module_head.$module_line_oa.$module_end;
if($module_line_hr!="")
	$module_hr=$module_head.$module_line_hr.$module_end;
if($module_line_man!="")
	$module_man=$module_head.$module_line_man.$module_end;
$MENU_LEFT=array();
$target="code_edit";

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("增加自定义积分项主分类"), "href" => "new.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("自定义积分项"), "href" => "list.php?FROM=3", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => $module_man, "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("OA使用积分项"), "href" => "list.php?FROM=1", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => $module_oa, "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("人事档案积分项"), "href" => "list.php?FROM=2", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => $module_hr, "module_style" => "");

include_once("inc/menu_left.php");
?>

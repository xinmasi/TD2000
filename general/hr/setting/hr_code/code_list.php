<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$SCRIPT='
function delete_code(CODE_ID,CODE_NO,CODE_NAME)
{
 var msg = sprintf("'._("ȷ��Ҫɾ������������ '%s' ���¼�����Ҳ����ɾ��").'", CODE_NAME);
 if(window.confirm(msg))
 {
  URL="delete.php?CODE_ID=" + CODE_ID +"&CODE_NO=" +CODE_NO;  
  location=URL;
 }
}';

$MENU_HEAD=array("text" => _("HRMS��������������"), "img" => MYOA_STATIC_SERVER."/static/images/menu/system.gif", "class" => "");
$module='<table class="TableBlock" width="100%">';

$query = "SELECT * from HR_CODE where PARENT_NO='' and CODE_NO!='HR_STAFF_CONTRACT2' order by CODE_ORDER";
$cursor= exequery(TD::conn(),$query, $connstatus);
while($ROW=mysql_fetch_array($cursor))
{
  $CODE_ID=$ROW["CODE_ID"];
  $CODE_NO=$ROW["CODE_NO"];
  $CODE_NAME=$ROW["CODE_NAME"];
  $CODE_FLAG=$ROW["CODE_FLAG"];

  $module.='<tr class="TableData" title="'.$CODE_NAME.'">
   <td>
      <b>'.$CODE_NAME.'</b>
   </td>
   <td nowrap>
     <a href="edit.php?CODE_ID='.$CODE_ID.'" target="code_edit"> �༭</a>&nbsp;
     <a href="func/?CODE_ID='.$CODE_ID.'" target="code_edit"> ��һ��</a>&nbsp;';

  if($CODE_FLAG!=0)
  	$module.='<a href="javascript:delete_code(\''.$CODE_ID.'\',\''.$CODE_NO.'\',\''.$CODE_NAME.'\');">'. _("ɾ��").'</a>';

   $module.='</td></tr>';

}//while
$module.='</table>';

$MENU_LEFT=array();
$target="code_edit";

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("���Ӵ���������"), "href" => "new.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => $module, "module_style" => "");
include_once("inc/menu_left.php");
?>

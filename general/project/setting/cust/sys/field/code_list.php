<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�Զ����ֶ�");

$SCRIPT='
function delete_code(CODE_ID,CODE_NO,CODE_NAME)
{
 var msg = sprintf("'._("ȷ��Ҫɾ�����Զ����ֶ� '%s' ģ���𣿸�ģ���µ������Զ����ֶκ����ݶ��ᱻɾ����").'", CODE_NAME);
 if(window.confirm(msg))
 {
  URL="delete.php?CODE_ID=" + CODE_ID;
  parent.code_edit.location=URL;
 }
}';


$MENU_HEAD=array("text" => _("�Զ����ֶι���"), "img" => MYOA_STATIC_SERVER."/static/images/menu/system.gif", "class" => "");
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
	  <a href="define/?CODE_ID='.$CODE_NO.'&CODE_NAME='.$CODE_NAME.'" target="code_edit">'._("����").'</a>
    </td>
  </tr>';
}//while
$module.='</table>';

$MENU_LEFT=array();
$target="code_edit";

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("��Ŀ�����Զ���"), "href" => "", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => $module, "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("ȫ���ֶι���"), "href" => "define/?CODE_ID=G&CODE_NAME="._("ȫ���ֶ��б�"), "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");


include_once("inc/menu_left.php");
?>
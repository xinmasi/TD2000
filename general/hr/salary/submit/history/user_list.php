<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$MENU_LEFT=array();

$target="hrms";
$user_list=array(
"PARA_URL1" => "",
"PARA_URL2" => "/general/hr/salary/submit/history/sal_data.php",
"PARA_TARGET" => $target,
"PARA_ID" => "FLOW_ID",
"PARA_VALUE"=> $FLOW_ID,
"PRIV_NO_FLAG" => "1",
"MANAGE_FLAG" => "1",
"xname" => "salary_submit_history",
"showButton" => "0",
"include_file" => "inc/user_list/index.php");

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("在职人员"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_list, "module_style" => "");

$query = "SELECT POST_PRIV from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $POST_PRIV=$ROW["POST_PRIV"];

if($POST_PRIV=="1")
{
   $query = "SELECT PRIV_NO from USER_PRIV where USER_PRIV='".$_SESSION["LOGIN_USER_PRIV"]."'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $PRIV_NO=$ROW["PRIV_NO"];
   
   $user_out='<table class="TableBlock" width="100%" align="center">';

   if($_SESSION["LOGIN_USER_PRIV"]!="1")
      $query = "SELECT USER_ID,USER_NAME,PRIV_NAME from USER,USER_PRIV where DEPT_ID=0 and USER.USER_PRIV=USER_PRIV.USER_PRIV and USER_PRIV.PRIV_NO>'$PRIV_NO' and USER_PRIV.USER_PRIV!=1 order by PRIV_NO,USER_NO,USER_NAME";
   else
      $query = "SELECT USER_ID,USER_NAME,PRIV_NAME from USER,USER_PRIV where DEPT_ID=0 and USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";

   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_COUNT++;
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
      $USER_PRIV=$ROW["PRIV_NAME"];

      $user_out.='
   <tr class="TableData" align="center">
     <td nowrap width="80">'.$USER_PRIV.'</td>
     <td nowrap><a href="sal_data.php?USER_ID='.$USER_ID.'&FLOW_ID='.$FLOW_ID.'" target='.$target.'>'.$USER_NAME.'</a></td>
   </tr>';
   }
   $user_out.='</table>';
   $module_style="display:none;";
   $MENU_LEFT[count($MENU_LEFT)] = array("text" => _("离职人员/外部人员"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_out, "module_style" => $module_style);

}

include_once("inc/menu_left.php");
?>
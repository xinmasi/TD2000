<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");



$target="wage_info";
$user_list=array(
"PARA_URL1" => "wage_list.php",
"PARA_URL2" => "wage_info.php",
"PARA_TARGET" => $target,
"PRIV_NO_FLAG" => "1",
"MANAGE_FLAG" => "1",
"MODULE_ID" => "",
"xname" => "hrms_manage",
"showButton" => "0",
"include_file" => "inc/user_list/index.php");

$MENU_LEFT[] = array("text" => _("在职人员"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_list, "module_style" => "");

$MENU_TOP=array();

$PRIV_NO_FLAG="2";
$MANAGE_FLAG="1";
$MODULE_ID=9;
include_once("inc/my_priv.php");
   $module_style="display:none;";

if($DEPT_PRIV=="1")
{
   $user_out='<table class="TableBlock" width="100%" align="center">';
   $query = "SELECT USER.USER_ID,USER_NAME,PRIV_NAME from USER_PRIV,USER  where USER.DEPT_ID=0 and USER.USER_PRIV=USER_PRIV.USER_PRIV";
   if($_SESSION["LOGIN_USER_PRIV"]!="1")
   {
      if($ROLE_PRIV=="0")
         $query .= " and USER_PRIV.PRIV_NO>$MY_PRIV_NO";
      else if($ROLE_PRIV=="1")
         $query .= " and USER_PRIV.PRIV_NO>=$MY_PRIV_NO";
      else if($ROLE_PRIV=="3")
      {
      	$PRIV_ID_STR=td_trim($PRIV_ID_STR);
      	if($PRIV_ID_STR!="")
          $query .= " and USER.USER_PRIV in ($PRIV_ID_STR)";
      }
      if($PRIV_NO_FLAG=="3")
         $query .= " and USER_PRIV.USER_PRIV!=1";
      if($DEPT_PRIV=="3")
      {
      	$USER_ID_STR=td_trim($USER_ID_STR);
      	if($USER_ID_STR!="")
          $query .= " and USER.USER_ID in ($USER_ID_STR)";
      }
   }
   $query.= " order by PRIV_NO,USER_NO,USER_NAME";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_COUNT++;
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
      $PRIV_NAME=$ROW["PRIV_NAME"];

      $target="wage_info";
      $user_out.='
   <tr class="TableData" align="center">
     <td nowrap width="80">'.$PRIV_NAME.'</td>
     <td nowrap><a href="wage_info.php?USER_ID='.$USER_ID.'&DEPT_ID=0" target='.$target.'>'.$USER_NAME.'</a></td>
   </tr>';
   }
   $user_out.='</table>';


   $MENU_LEFT[count($MENU_LEFT)] = array("text" => _("离职人员/外部人员"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_out, "module_style" => $module_style);
}
include_once("inc/menu_left.php");
?>

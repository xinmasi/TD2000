<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
//选择离职用户

$MENU_TOP=array();

$PRIV_NO_FLAG="2";
$MANAGE_FLAG="1";
$MODULE_ID=10;
include_once("inc/my_priv.php");
$target="hrms";
$MENU_LEFT_CONFIGS['framename'] = $target;
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("查询/导出离职人员人事档案"), "href" => "query.php?is_leave=1", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");

if($DEPT_PRIV=="1")
{
    $user_list=array(
        "PARA_URL1" => "",
        "PARA_URL2" => "/general/hr/manage/query/show_staff_info.php",
        "PARA_TARGET" => $target,
        "PRIV_NO_FLAG" => "1",
        "MANAGE_FLAG" => "1",
        "MODULE_ID" => "10",
        "xname" => "hrms_manage",
        "showButton" => "0",
        "xtree" => "/general/hr/manage/staff_info/tree.php",
        "include_file" => "inc/user_list/index.php");

    $MENU_LEFT[count($MENU_LEFT)] = array("text" => _("已记录所属部门"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_list, "module_style" => $module_style);

    $query = "SELECT * from USER_PRIV where USER_PRIV='".$_SESSION["LOGIN_USER_PRIV"]."'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $PRIV_NO=$ROW["PRIV_NO"];

    $user_out='<table class="TableBlock" width="100%" align="center">';

    $query = "SELECT USER.USER_ID,USER_NAME,PRIV_NAME from USER_PRIV,USER left join HR_STAFF_INFO on USER.USER_ID=HR_STAFF_INFO.USER_ID where USER.DEPT_ID=0 and (HR_STAFF_INFO.DEPT_ID='0' or HR_STAFF_INFO.DEPT_ID is NULL) and USER.USER_PRIV=USER_PRIV.USER_PRIV";
    if($_SESSION["LOGIN_USER_PRIV"]!="1")
    {
        if($ROLE_PRIV=="0")
            $query .= " and USER_PRIV.PRIV_NO>$PRIV_NO";
        else if($ROLE_PRIV=="1")
            $query .= " and USER_PRIV.PRIV_NO>=$PRIV_NO";
        else if($ROLE_PRIV=="3")
            $query .= " and find_in_set(USER.USER_PRIV,'$PRIV_ID_STR')";
        if($PRIV_NO_FLAG=="3")
            $query .= " and USER_PRIV.USER_PRIV!=1";
        if($DEPT_PRIV=="3")
            $query .= " and find_in_set(USER.USER_ID,'$USER_ID_STR')";
    }
    $query.= " order by PRIV_NO,USER_NO,USER_NAME";//echo $query;exit;
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $USER_COUNT++;
        $USER_ID=$ROW["USER_ID"];
        $USER_NAME=$ROW["USER_NAME"];
        $PRIV_NAME=$ROW["PRIV_NAME"];

        $user_out.='
   <tr class="TableData" align="center">
     <td nowrap width="80">'.$PRIV_NAME.'</td>
     <td nowrap><a href="/general/hr/manage/query/show_staff_info.php?USER_ID='.$USER_ID.'&DEPT_ID=0" target='.$target.'>'.$USER_NAME.'</a></td>
   </tr>';
    }
    $user_out.='</table>';
    $module_style="display:none;";

    $MENU_LEFT[count($MENU_LEFT)] = array("text" => _("未记录所属部门"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_out, "module_style" => $module_style);
}else
{
    $user_list=array(
        "PARA_URL1" => "",
        "PARA_URL2" => "/general/hr/manage/query/show_staff_info.php",
        "PARA_TARGET" => $target,
        "PRIV_NO_FLAG" => "1",
        "MANAGE_FLAG" => "1",
        "MODULE_ID" => "10",
        "xname" => "hrms_manage",
        "showButton" => "0",
        "xtree" => "/general/hr/manage/staff_info/tree.php",
        "include_file" => "inc/hr_user_list/index.php");

    $MENU_LEFT[count($MENU_LEFT)] = array("text" => _("已记录所属部门"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_list, "module_style" => $module_style);
}
include_once("inc/menu_left.php");
?>

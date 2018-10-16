<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");

$is_manager = 0;
//获取人事专员和人力资源管理员
$sql = "SELECT DEPT_HR_MANAGER,DEPT_HR_SPECIALIST FROM hr_manager WHERE find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_MANAGER) or find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_SPECIALIST)";
$cursor1 = exequery(TD::conn(),$sql);
if($arr=mysql_fetch_array($cursor1))
{
    $is_manager = 1;
    /*
	$DEPT_HR_MANAGER    .= $arr['DEPT_HR_MANAGER'].",";
	$DEPT_HR_SPECIALIST .= $arr['DEPT_HR_SPECIALIST'].",";
	*/
}

/*
$TO_ID_STR = array_unique(explode(',',$DEPT_HR_MANAGER .$DEPT_HR_SPECIALIST));
$TO_ID_STR = array_filter($TO_ID_STR);
$TO_ID_STR = implode(",",$TO_ID_STR);
*/

$MENU_HEAD = array();
$MENU_LEFT=array();
$target="staff_info";
$user_list=array(
"PARA_URL1" => "user_list.php",
"PARA_URL2" => "staff_info.php",
"PARA_TARGET" => $target,
"PRIV_NO_FLAG" => "1",
"MANAGE_FLAG" => "1",
"MODULE_ID" => "9",
"xname" => "hrms_manage",
"showButton" => "0",
"include_file" => "inc/hr_user_list/index.php");

$MENU_LEFT[] = array("text" => _("在职人员档案"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_list, "module_style" => "");

if($_SESSION['LOGIN_USER_PRIV']==1 || $is_manager==1)
{
    $MENU_LEFT[] = array("text" => _("导入人事档案"), "href" => "import.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");
}
$MENU_LEFT[] = array("text" => _("退休人员查询"), "href" => "remind.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");
$MENU_LEFT[] = array("text" => _("批量更新档案"), "href" => "batch_update.php", "onclick" => "", "target" => $target, "title" => _("批量设置用户的人事档案"), "img" => "", "module" => "", "module_style" => "");
$MENU_LEFT[] = array("text" => _("人力资源信息中心"), "href" => "hrms_info.php", "onclick" => "", "target" => $target, "title" => _("人力资源信息中心"), "img" => "", "module" => "", "module_style" => "");
$MENU_LEFT[] = array("text" => _("岗位职责"), "href" => "job_responsibilities.php", "onclick" => "", "target" => $target, "title" => _("岗位职责"), "img" => "", "module" => "", "module_style" => "");

$sql = "select id from crscell.crs_report where id=771 and repno='BI53'";
$cursor = exequery(TD::conn(), $sql);
if($row = mysql_fetch_array($cursor))
{
    $MENU_LEFT[] = array("text" => _("五险一金办理登记"), "href" => "/general/reportshop/workshop/report/list_report.php?isreport=y&repid=776&inline", "onclick" => "", "target" => $target, "title" => _("五险一金办理登记"), "img" => "", "module" => "", "module_style" => "");
    $MENU_LEFT[] = array("text" => _("本月五险一金待办理事项"), "href" => "/general/reportshop/workshop/report/phpcell/index.php?repid=777&openmode=write&isquery=y&inline", "onclick" => "", "target" => $target, "title" => _("本月五险一金待办理事项"), "img" => "", "module" => "", "module_style" => "");
}
include_once("inc/menu_left.php");
?>
<body>
    <div id="center">
        <iframe name="staff_info" scrolling="YES" noresize src="blank.php?DEPT_ID=<?=$DEPT_ID?>&DEPT_NAME=<?=urlencode($DEPT_NAME1)?>></iframe>
</div>
</body>
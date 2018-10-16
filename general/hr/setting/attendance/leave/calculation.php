<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms2.php" );
include_once("inc/utility_sms1.php" );
include_once("inc/utility_org.php");
include_once("general/system/log/annual_leave_log.php");
$HTML_PAGE_TITLE = _("自动计算人事档案年假");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$PARA_ARRAY=get_sys_para("LEAVE_BY_SENIORITY,ENTRY_RESET_LEAVE");
$leave_by_seniority = $PARA_ARRAY["LEAVE_BY_SENIORITY"];//是否开启按工龄计算年假
$entry_reset_leave = $PARA_ARRAY["ENTRY_RESET_LEAVE"];//是否开启按入职日期计算年假

$sql = "select STAFF_ID,USER_ID,STAFF_NAME,DEPT_ID,JOB_AGE from hr_staff_info where DATES_EMPLOYED!='0000-00-00'";
$cursor = exequery(TD::conn(),$sql);
while($ROW=mysql_fetch_array($cursor))
{
    $staff_id = $ROW['STAFF_ID'];
    $user_id = $ROW['USER_ID'];
    $staff_name = $ROW['STAFF_NAME'];
    $dept_id = $ROW['DEPT_ID'];
    $job_age = $ROW['JOB_AGE'];
    if($job_age!='')
    {
        //查询出相应工龄的年假
        $query = "select working_years,leave_day from attend_leave_param ORDER BY working_years";
        $result = exequery(TD::conn(),$query);
        while($rows=mysql_fetch_array($result))
        {
            $working_years = $rows['working_years'];
            $leave_day = $rows["leave_day"];
            if($job_age>=$working_years)
            {
                $LEAVE_TYPE = $leave_day; 
            }
        }
        $query2 = "update hr_staff_info set LEAVE_TYPE='$LEAVE_TYPE' where STAFF_ID='$staff_id'";
        exequery(TD::conn(),$query2);
    }
    /*else
    {   
        $uid_str = GetUidByUserID($user_id);
        $uid = substr($uid_str,0,strlen($uid_str)-1);
        $REMIND_URL="hr/manage/staff_info/staff_info.php?UID=".$uid."&USER_ID=".$user_id."&DEPT_ID=".$dept_id."&FROM_DEPT_ID=".$dept_id;
        $SMS_CONTENT=$staff_name._("没有添加入职时间，无法自动计算年休假，请及时给此用户添加入职时间！");
        send_sms('',$_SESSION['LOGIN_USER_ID'],'admin','9_1',$SMS_CONTENT,$REMIND_URL);
    }*/    
}
addAnnualLeaveLog('','',2);
Message(_("成功"), _("人事档案年假信息已重置成功"));
?>
<center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';"></center>
<?
exit;


?>
</body>
</html>

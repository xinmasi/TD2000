<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms2.php" );
include_once("inc/utility_sms1.php" );
include_once("inc/utility_org.php");
include_once("general/system/log/annual_leave_log.php");
$HTML_PAGE_TITLE = _("�Զ��������µ������");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$PARA_ARRAY=get_sys_para("LEAVE_BY_SENIORITY,ENTRY_RESET_LEAVE");
$leave_by_seniority = $PARA_ARRAY["LEAVE_BY_SENIORITY"];//�Ƿ���������������
$entry_reset_leave = $PARA_ARRAY["ENTRY_RESET_LEAVE"];//�Ƿ�������ְ���ڼ������

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
        //��ѯ����Ӧ��������
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
        $SMS_CONTENT=$staff_name._("û�������ְʱ�䣬�޷��Զ��������ݼ٣��뼰ʱ�����û������ְʱ�䣡");
        send_sms('',$_SESSION['LOGIN_USER_ID'],'admin','9_1',$SMS_CONTENT,$REMIND_URL);
    }*/    
}
addAnnualLeaveLog('','',2);
Message(_("�ɹ�"), _("���µ��������Ϣ�����óɹ�"));
?>
<center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php';"></center>
<?
exit;


?>
</body>
</html>

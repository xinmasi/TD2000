<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

include_once("inc/header.inc.php");
?>
<body class="bodycolor">

<?
if($seniority!='' && $leave!='')
{
    $sql = "select * from attend_leave_param where working_years='$seniority' and id!='$LEAVE_ID'";
    $cursor = exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($cursor))
    {
        $year = $ROW['leave_day'];
        if($leave == $year)
        {
            Message(_("����"),_("�������Ϣ�Ѿ����ڣ������ظ��༭��"));
            Button_Back();
            exit;
        }
        else
        {
            Message(_("����"),_("�˹�����Ѿ����ڣ����������ȥ��Ӧ��Ŀ�༭��"));
            Button_Back();
            exit;
        }
    }
    else
    {
        //������ú�������֤
        $sql    = "select * from attend_leave_param where 1=1";
        $cursor = exequery(TD::conn(),$sql);
        while($ROW=mysql_fetch_array($cursor)){
            $working_years  = $ROW["working_years"];
            $leave_day      = $ROW["leave_day"];
            if(($seniority > $working_years && $leave < $leave_day) || ($seniority < $working_years && $leave > $leave_day)){
                Message(_("����"),_("������ò����������±༭��"));
                Button_Back();
                exit;
            }
        }
        $query="update attend_leave_param set working_years='$seniority',leave_day='$leave' where id='$LEAVE_ID'";
        exequery(TD::conn(),$query);

        header("location: index.php?connstatus=1");
    }
}
else
{
    Message(_("����"),_("����������������Ϊ�գ�"));
    Button_Back();
    exit;
}
?>
</body>
</html>

<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("增加年假信息");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
if($seniority!='' && $leave!='')
{
    $sql = "select * from attend_leave_param where working_years='$seniority'";
    $cursor = exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($cursor))
    {
        $year = $ROW['leave_day'];
        if($leave == $year)
        {
            Message(_("错误"),_("此年假信息已经添加，不能重复添加！"));
            Button_Back();
            exit;
        }
        else
        {
            Message(_("错误"),_("此工龄段已经添加，如需更改请直接编辑！"));
            Button_Back();
            exit;
        }
    }
    else
    {
        //年假设置合理性验证
        $sql    = "select * from attend_leave_param where 1=1";
        $cursor = exequery(TD::conn(),$sql);
        while($ROW=mysql_fetch_array($cursor)){
            $working_years  = $ROW["working_years"];
            $leave_day      = $ROW["leave_day"];
            if(($seniority > $working_years && $leave < $leave_day) || ($seniority < $working_years && $leave > $leave_day)){
                Message(_("错误"),_("年假设置不合理，请重新编辑！"));
                Button_Back();
                exit;
            }
        }
        $query="insert into attend_leave_param(working_years,leave_day) values ('$seniority','$leave')";
        exequery(TD::conn(),$query);

        header("location: index.php?connstatus=1");
    }
}
else
{
    Message(_("错误"),_("工龄和年假天数不能为空！"));
    Button_Back();
    exit;
}
?>
</body>
</html>

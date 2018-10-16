<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("合同到期查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
    if(document.form1.QUERY_DATE1.value!="" && document.form1.QUERY_DATE2.value=="")
    {
        alert("<?=_("请输入结束时间")?>");
        return (false);
    }
    if(document.form1.QUERY_DATE1.value=="" && document.form1.QUERY_DATE2.value!="")
    {
        alert("<?=_("请输入开始时间")?>");
        return (false);
    }
    document.form1.submit();
}
</script>

<body class="bodycolor" topmargin="5">
<form method="post" name="form1" action="#">
<table border="0" width="100%" cellpadding="3" cellspacing="1" align="center" bgcolor="#000000">
    <tr>
        <td class="TableHeader">
            &nbsp;<?=_("合同到期查询")?>&nbsp;&nbsp;
            <?=_("合同是否续签")?>
            <select name="IS_RENEW" style="background: white;">
                <option value="0" <?if($IS_RENEW==0) echo selected?>><?=_("否")?>&nbsp;&nbsp;</option>
                <option value="1" <?if($IS_RENEW==1) echo selected?>><?=_("是")?>&nbsp;&nbsp;</option>
            </select>
            <?=_("合同类型")?>
            <select name="CONTRACT_TYPE" style="background: white;" title="<?=_("合同类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
                <option value=""><?=_("合同类型")?>&nbsp;&nbsp;</option>
                <?=hrms_code_list("HR_STAFF_CONTRACT1",$CONTRACT_TYPE)?>
            </select>
            <?=_("从")?>
            <input type="text" id="start_time" name="QUERY_DATE1" size="10" maxlength="10" class="BigInput" value="<?=$QUERY_DATE1?>" onClick="WdatePicker()"/>
            <?=_("至")?>
            <input type="text" name="QUERY_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$QUERY_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
            <input type="hidden" name="inform" value="1"/>
            <input type="button" value="<?=_("确定")?>" class="SmallButton" onClick="CheckForm();">&nbsp;&nbsp;
        </td>
    </tr>
</table>
</form>
<br>
<?
$WHERE_STR = hr_priv("STAFF_NAME");
if($inform=="1")
{
    if($IS_RENEW==0)
    {
        if($IS_RENEW!='')
        {
            $WHERE_STR.= " and IS_RENEW = '$IS_RENEW'";
        }
        if($QUERY_DATE2 < $QUERY_DATE1)
        {
            Message("",_("开始日期不能大于结束日期！"));
            exit();
        }
        if($QUERY_DATE1!='')
        {
            $WHERE_STR.= " and CONTRACT_END_TIME >= '$QUERY_DATE1'";
        }
        if($QUERY_DATE2!='')
        {
            $WHERE_STR.= " and CONTRACT_END_TIME <= '$QUERY_DATE2'";
        }
        if($CONTRACT_TYPE!='')
        {
            $WHERE_STR.= " and CONTRACT_TYPE = '$CONTRACT_TYPE'";
        }
    }
    else
    {
        if($IS_RENEW!='')
        {
            $WHERE_STR.= " and IS_RENEW = '$IS_RENEW'";
        }
        if($CONTRACT_TYPE!='')
        {
            $WHERE_STR.= " and CONTRACT_TYPE = '$CONTRACT_TYPE'";
        }
    }
}
else
{
    $CUR_MONTH = Date("Y-m");
    $TODAY = Date("Y-m-d");
    $WHERE_STR.= " and (CONTRACT_END_TIME like '".$CUR_MONTH."%' or CONTRACT_END_TIME <= '$TODAY' and CONTRACT_END_TIME !='0000-00-00') and IS_RENEW !='1'";
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"><?=_("查询结果")?></span>&nbsp;
        </td>
    </tr>
</table>
<?
$TOTAL_ITEMS = "";
$query = "SELECT * from HR_STAFF_CONTRACT where ".$WHERE_STR." and STATUS = '2' order by ADD_TIME desc";
$cursor= exequery(TD::conn(), $query);
while($ROW=mysql_fetch_array($cursor))
{
    if($inform=="1" && $IS_RENEW==1)
    {
        $RENEW_END_TIME = "";
        if($ROW["RENEW_TIME"]!="0000-00-00" && $ROW["RENEW_TIME"]!="")
        {
            $HR_RENEW_TIME_ARRAY = explode('|',$ROW["RENEW_TIME"]);
            $RENEW_END_TIME = $HR_RENEW_TIME_ARRAY[count($HR_RENEW_TIME_ARRAY)-2];
            if($QUERY_DATE1 !="" && $RENEW_END_TIME >= $QUERY_DATE1 && $RENEW_END_TIME <= $QUERY_DATE2)
            {
                $CONTRACT_ID                = $ROW["CONTRACT_ID"];
                $CREATE_USER_ID             = $ROW["CREATE_USER_ID"];
                $CREATE_DEPT_ID             = $ROW["CREATE_DEPT_ID"];
                $STAFF_NAME                 = $ROW["STAFF_NAME"];
                $STAFF_CONTRACT_NO          = $ROW["STAFF_CONTRACT_NO"];
                $CONTRACT_TYPE              = $ROW["CONTRACT_TYPE"];
                $MAKE_CONTRACT              = $ROW["MAKE_CONTRACT"];
                $STATUS                     = $ROW["STATUS"];
                $ADD_TIME                   = $ROW["ADD_TIME"];
                $CONTRACT_ENTERPRIES        = $ROW["CONTRACT_ENTERPRIES"];
                $PROBATION_END_DATE         = $ROW["PROBATION_END_DATE"];
                $TRAIL_OVER_TIME            = $ROW["TRAIL_OVER_TIME"];
                $TRAIL_EFFECTIVE_TIME       = $ROW["TRAIL_EFFECTIVE_TIME"];
                $CONTRACT_END_TIME          = $ROW["CONTRACT_END_TIME"];
                $PROBATION_EFFECTIVE_DATE   = $ROW["PROBATION_EFFECTIVE_DATE"];                  
            }
            else if($QUERY_DATE1 =="")
            {
                $CONTRACT_ID                = $ROW["CONTRACT_ID"];
                $CREATE_USER_ID             = $ROW["CREATE_USER_ID"];
                $CREATE_DEPT_ID             = $ROW["CREATE_DEPT_ID"];
                $STAFF_NAME                 = $ROW["STAFF_NAME"];
                $STAFF_CONTRACT_NO          = $ROW["STAFF_CONTRACT_NO"];
                $CONTRACT_TYPE              = $ROW["CONTRACT_TYPE"];
                $MAKE_CONTRACT              = $ROW["MAKE_CONTRACT"];
                $STATUS                     = $ROW["STATUS"];
                $ADD_TIME                   = $ROW["ADD_TIME"];
                $CONTRACT_ENTERPRIES        = $ROW["CONTRACT_ENTERPRIES"];
                $PROBATION_END_DATE         = $ROW["PROBATION_END_DATE"];
                $TRAIL_OVER_TIME            = $ROW["TRAIL_OVER_TIME"];
                $TRAIL_EFFECTIVE_TIME       = $ROW["TRAIL_EFFECTIVE_TIME"];
                $CONTRACT_END_TIME          = $ROW["CONTRACT_END_TIME"];
                $PROBATION_EFFECTIVE_DATE   = $ROW["PROBATION_EFFECTIVE_DATE"];
            }
            else
            {
                continue;
            }
        }
        else
        {
            continue;
        }
    }
    else
    {
        $CONTRACT_ID                        = $ROW["CONTRACT_ID"];
        $CREATE_USER_ID                     = $ROW["CREATE_USER_ID"];
        $CREATE_DEPT_ID                     = $ROW["CREATE_DEPT_ID"];
        $STAFF_NAME                         = $ROW["STAFF_NAME"];
        $STAFF_CONTRACT_NO                  = $ROW["STAFF_CONTRACT_NO"];
        $CONTRACT_TYPE                      = $ROW["CONTRACT_TYPE"];
        $MAKE_CONTRACT                      = $ROW["MAKE_CONTRACT"];
        $STATUS                             = $ROW["STATUS"];
        $ADD_TIME                           = $ROW["ADD_TIME"];
        $CONTRACT_ENTERPRIES                = $ROW["CONTRACT_ENTERPRIES"];
        $PROBATION_END_DATE                 = $ROW["PROBATION_END_DATE"];
        $TRAIL_OVER_TIME                    = $ROW["TRAIL_OVER_TIME"];
        $TRAIL_EFFECTIVE_TIME               = $ROW["TRAIL_EFFECTIVE_TIME"];
        $CONTRACT_END_TIME                  = $ROW["CONTRACT_END_TIME"];
        $PROBATION_EFFECTIVE_DATE           = $ROW["PROBATION_EFFECTIVE_DATE"];
    }
    
    if($TRAIL_OVER_TIME == "0000-00-00")
    {
        $TRAIL_OVER_TIME = "";
    }
    if($CONTRACT_END_TIME == "0000-00-00")
    {
        $CONTRACT_END_TIME = "";
    }
    if($MAKE_CONTRACT == "0000-00-00")
    {
        $MAKE_CONTRACT = "";
    }
    if($PROBATION_END_DATE == "0000-00-00")
    {
        $PROBATION_END_DATE = "";
    }
    if($PROBATION_EFFECTIVE_DATE == "0000-00-00")
    {
        $PROBATION_EFFECTIVE_DATE = "";
    }
   
    $STAFF_NAME1 = substr(GetUserNameById($STAFF_NAME),0,-1);
    $CONTRACT_DEPT = "";   
    $query2 = "SELECT department.DEPT_NAME from department,user where user.USER_ID='$STAFF_NAME' and department.DEPT_ID=user.DEPT_ID";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $CONTRACT_DEPT = $ROW2['DEPT_NAME'];
    }
    $query3 = "SELECT CODE_NAME from hr_code where CODE_NO='$CONTRACT_ENTERPRIES' && PARENT_NO='HR_ENTERPRISE'";
    $cursor3= exequery(TD::conn(),$query3);
    if($ROW3=mysql_fetch_array($cursor3))
    {
        $CONTRACT_ENTERPRIES = $ROW3['CODE_NAME'];
    }
    
    if($STAFF_NAME1!="")
    {
        $query1 = "SELECT STAFF_NAME,WORK_JOB from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
        {
            $CONTRACT_JOB="";
            $query4 = "SELECT DEPT_ID from user where USER_ID='$STAFF_NAME'";            
            $cursor4= exequery(TD::conn(),$query4);
            $ROW4=mysql_fetch_array($cursor4);
            if($ROW4["DEPT_ID"]==0)
            {
                $STAFF_NAME1 = $ROW1["STAFF_NAME"];
                $HR_WORK_JOB = $ROW1['WORK_JOB'];
                
                $STAFF_NAME1=$STAFF_NAME1."(<font color='red'>"._("用户已离职")."</font>)";
                $query5 = "SELECT CODE_NAME from hr_code where PARENT_NO='POOL_POSITION' and CODE_NO='$HR_WORK_JOB'";
                $cursor5= exequery(TD::conn(),$query5);
                $ROW5=mysql_fetch_array($cursor5);
                $CONTRACT_JOB=$ROW5["CODE_NAME"];
            }
            else
            {
                $STAFF_NAME1 = $ROW1["STAFF_NAME"];
                $HR_WORK_JOB = $ROW1['WORK_JOB'];
                
                $query5 = "SELECT CODE_NAME from hr_code where PARENT_NO='POOL_POSITION' and CODE_NO='$HR_WORK_JOB'";
                $cursor5= exequery(TD::conn(),$query5);
                $ROW5=mysql_fetch_array($cursor5);
                $CONTRACT_JOB = $ROW5["CODE_NAME"];
            }
        }
    }
    else
    {
        $query7 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor7= exequery(TD::conn(),$query7);
        if($ROW7=mysql_fetch_array($cursor7))
        {
            $STAFF_NAME1 = $ROW7["STAFF_NAME"]."(<font color='red'>"._("用户已删除")."</font>)";
        }
    }
    $CONTRACT_TYPE=get_hrms_code_name($CONTRACT_TYPE,"HR_STAFF_CONTRACT1");
    $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_CONTRACT2");
    
    $TOTAL_ITEMS++;
    
    //显示续签 or 合同
    $CONTRACT_END_TIME = ($IS_RENEW == 1) ? $RENEW_END_TIME : $CONTRACT_END_TIME;
    $SHOW_NAME = ($IS_RENEW == 1) ? _("续签到期日期") : _("合同到期日期");
    
    if($TOTAL_ITEMS == 1)
    {
?>
    <table class="TableList" width="100%">
        <tr class="TableHeader">
            <td nowrap align="center"><?=_("姓名")?></td>
            <td nowrap align="center"><?=_("部门")?></td>
            <td nowrap align="center"><?=_("岗位")?></td>
            <td nowrap align="center"><?=_("合同类型")?></td>
            <td nowrap align="center"><?=_("签署公司")?></td>
            <td nowrap align="center"><?=_("合同生效日期")?></td>      
            <td nowrap align="center"><?=_("试用到期日期")?></td>
            <td nowrap align="center"><?=$SHOW_NAME?></td>
            <td nowrap align="center"><?=_("操作")?></td>
        </tr>
<?
    }
?>
    <tr class="TableData">
        <td nowrap align="center"><?=$STAFF_NAME1?></td>
        <td nowrap align="center"><?=$CONTRACT_DEPT?></td>
        <td nowrap align="center"><?=$CONTRACT_JOB?></td>
        <td nowrap align="center"><?=$CONTRACT_TYPE?></td>
        <td nowrap align="center"><?=$CONTRACT_ENTERPRIES?></td>
        <td nowrap align="center"><?=$PROBATION_EFFECTIVE_DATE?></td>      
        <td nowrap align="center"><?=$TRAIL_OVER_TIME?></td>
        <td nowrap align="center"><?=$CONTRACT_END_TIME?></td>
        <td nowrap align="center">
            <a href="javascript:;" onClick="window.open('contract_detail.php?CONTRACT_ID=<?=$CONTRACT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
        </td>
    </tr>
<?
}

if($TOTAL_ITEMS<=0)
{
    if($inform=="1")
    {
        Message("",_("无符合条件的合同到期记录"));
    }
    else
    {
        Message("",_("本月无合同到期记录"));
    }
}
?>
</table>
</body>
</html>

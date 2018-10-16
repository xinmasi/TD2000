<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("合同管理");
include_once("inc/header.inc.php");
?>

<script>
function delete_contract(CONTRACT_ID)
{
    msg='<?=_("确认要删除该项合同信息吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?CONTRACT_ID=" + CONTRACT_ID+"&PAGE_START=<?=$PAGE_START?>";
        window.location=URL;
    }
}

function check_all()
{
    for(i=0;i<document.getElementsByName("email_select").length;i++)
    {
        if(document.getElementsByName("allbox").item(0).checked)
            document.getElementsByName("email_select").item(i).checked=true;
        else
            document.getElementsByName("email_select").item(i).checked=false;
    }
    
    if(i==0)
    {
        if(document.getElementsByName("allbox").item(0).checked)
            document.getElementsByName("email_select").checked=true;
        else
            document.getElementsByName("email_select").checked=false;
    }
}

function check_one(el)
{
    if(!el.checked)
        document.getElementsByName("allbox").item(0).checked=false;
}

function get_checked()
{
    checked_str="";
    for(i=0;i<document.getElementsByName("email_select").length;i++)
    {
    
        el=document.getElementsByName("email_select").item(i);
        if(el.checked)
        {
            val=el.value;
            checked_str+=val + ",";
        }
    }
    
    if(i==0)
    {
        el=document.getElementsByName("email_select");
        if(el.checked)
        {
            val=el.value;
            checked_str+=val + ",";
        }
    }
    return checked_str;
}

function delete_mail()
{
    delete_str=get_checked();
    if(delete_str=="")
    {
        alert("<?=_("要删除合同信息，请至少选择其中一条。")?>");
        return;
    }
    
    msg='<?=_("确认要删除该项合同信息吗？")?>';
    if(window.confirm(msg))
    {
        url="delete.php?CONTRACT_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
        location=url;
    }
}

function change_type(type)
{
    window.location="index1.php?start=<?=$start?>";
}
function sync()
{
    url="sync.php?start=<?=$start?>";
    location=url;
}
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
    $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

//OA管理员 档案管理员 新建人 
$WHERE_STR = hr_priv("STAFF_NAME");

if(!isset($TOTAL_ITEMS))
{
    $query = "SELECT count(*) from HR_STAFF_CONTRACT where ".$WHERE_STR;
    $cursor= exequery(TD::conn(),$query, $connstatus);
    if($ROW=mysql_fetch_array($cursor))
        $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("合同管理")?></span>&nbsp;
        </td>
<?
if($TOTAL_ITEMS>0)
{
?>    
        <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
<?
}
?>
    </tr>
</table>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("选择")?></td>
        <td nowrap align="center"><?=_("姓名")?></td>
        <td nowrap align="center"><?=_("部门")?></td>
        <td nowrap align="center"><?=_("岗位")?></td>
        <td nowrap align="center"><?=_("合同类型")?></td>
        <td nowrap align="center"><?=_("签署公司")?></td>
        <td nowrap align="center"><?=_("合同生效日期")?></td>      
        <td nowrap align="center"><?=_("试用到期日期")?></td>
        <td nowrap align="center"><?=_("合同到期日期")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
}

$query = "SELECT * from  HR_STAFF_CONTRACT where ".$WHERE_STR." order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$CONTRACT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $CONTRACT_COUNT++;
    
    $CONTRACT_ID                = $ROW["CONTRACT_ID"];
    $CREATE_USER_ID             = $ROW["CREATE_USER_ID"];
    $CREATE_DEPT_ID             = $ROW["CREATE_DEPT_ID"];
    $STAFF_NAME                 = $ROW["STAFF_NAME"];
    $STAFF_CONTRACT_NO          = $ROW["STAFF_CONTRACT_NO"];
    $CONTRACT_TYPE              = $ROW["CONTRACT_TYPE"];
    $MAKE_CONTRACT              = $ROW["MAKE_CONTRACT"];
    $STATUS                     = $ROW["STATUS"];
    $IS_TRIAL                   = $ROW["IS_TRIAL"];
    $ADD_TIME                   = $ROW["ADD_TIME"];
    $CONTRACT_ENTERPRIES        = $ROW["CONTRACT_ENTERPRIES"];
    $PROBATION_END_DATE         = $ROW["PROBATION_END_DATE"];
    $TRAIL_OVER_TIME            = $ROW["TRAIL_OVER_TIME"];
    $TRAIL_EFFECTIVE_TIME       = $ROW["TRAIL_EFFECTIVE_TIME"];
    $CONTRACT_END_TIME          = $ROW["CONTRACT_END_TIME"];
    $PROBATION_EFFECTIVE_DATE   = $ROW["PROBATION_EFFECTIVE_DATE"];
    $IS_RENEW                   = $ROW["IS_RENEW"];
    $RENEW_TIME                 = $ROW["RENEW_TIME"];


   
    if ($TRAIL_OVER_TIME == "0000-00-00")
        $TRAIL_OVER_TIME = "";
    if ($CONTRACT_END_TIME == "0000-00-00")
        $CONTRACT_END_TIME = "";
    if ($MAKE_CONTRACT == "0000-00-00")
        $MAKE_CONTRACT = "";
    if ($PROBATION_END_DATE == "0000-00-00")
        $PROBATION_END_DATE = "";
    if ($PROBATION_EFFECTIVE_DATE == "0000-00-00")
        $PROBATION_EFFECTIVE_DATE = "";
    $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
    $sql2 = "update HR_STAFF_INFO set STAFF_NAME='$STAFF_NAME1' where USER_ID='$STAFF_NAME'";
    exequery(TD::conn(),$sql2);
    $CONTRACT_DEPT="";
    $query2 = "SELECT department.DEPT_NAME from department,user where user.USER_ID='$STAFF_NAME' and department.DEPT_ID=user.DEPT_ID";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $CONTRACT_DEPT=$ROW2['DEPT_NAME'];
    }
    $query3 = "SELECT CODE_NAME from hr_code where CODE_NO='$CONTRACT_ENTERPRIES' && PARENT_NO='HR_ENTERPRISE'";
    $cursor3= exequery(TD::conn(),$query3);
    if($ROW3=mysql_fetch_array($cursor3))
    {
        $CONTRACT_ENTERPRIES=$ROW3['CODE_NAME'];
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
                $STAFF_NAME1=$ROW1["STAFF_NAME"];
                $HR_WORK_JOB=$ROW1['WORK_JOB'];
                $STAFF_NAME1=$STAFF_NAME1."(<font color='red'>"._("用户已离职")."</font>)";
                $query5 = "SELECT CODE_NAME from hr_code where PARENT_NO='POOL_POSITION' and CODE_NO='$HR_WORK_JOB'";
                $cursor5= exequery(TD::conn(),$query5);
                $ROW5=mysql_fetch_array($cursor5);
                $CONTRACT_JOB=$ROW5["CODE_NAME"];
            }
            else
            {
                $STAFF_NAME1=$ROW1["STAFF_NAME"];
                $HR_WORK_JOB=$ROW1['WORK_JOB'];
                $query5 = "SELECT CODE_NAME from hr_code where PARENT_NO='POOL_POSITION' and CODE_NO='$HR_WORK_JOB'";
                $cursor5= exequery(TD::conn(),$query5);
                $ROW5=mysql_fetch_array($cursor5);
                $CONTRACT_JOB=$ROW5["CODE_NAME"];
            }
        }
    }
    else
    {
       // file_put_contents('./3.txt','33333333333');
        //$query7 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        //$cursor7= exequery(TD::conn(),$query7);
        //if($ROW7=mysql_fetch_array($cursor7))
        //{
            $STAFF_NAME1="(<font color='red'>"._("用户已删除")."</font>)";
        //}
    }
    $CONTRACT_TYPE=get_hrms_code_name($CONTRACT_TYPE,"HR_STAFF_CONTRACT1");
    $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_CONTRACT2");

    //获取续签日期
    if($IS_RENEW==1 && ($RENEW_TIME!="" && $RENEW_TIME!="0000-00-00"))
    {
        $RENEW_TIME_ARR = explode("|",$RENEW_TIME);
        $RENEW_TIME_ARR = array_filter($RENEW_TIME_ARR);
        $CONTRACT_END_TIME = end($RENEW_TIME_ARR);
    }


?>
    <tr class="TableData">
        <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$CONTRACT_ID?>" onClick="check_one(self);">
        <td nowrap align="center"><?=$STAFF_NAME1?></td>
        <td nowrap align="center"><?=$CONTRACT_DEPT?></td>
        <td nowrap align="center"><?=$CONTRACT_JOB?></td>
        <td nowrap align="center"><?=$CONTRACT_TYPE?></td>
        <td nowrap align="center"><?=$CONTRACT_ENTERPRIES?></td>
        <td nowrap align="center"><?=$PROBATION_EFFECTIVE_DATE?></td>      
        <td nowrap align="center"><?=$IS_TRIAL==1?$TRAIL_OVER_TIME:""?></td>
        <td nowrap align="center"><?=$CONTRACT_END_TIME?></td>
        <td nowrap align="center">
            <a href="javascript:;" onClick="window.open('contract_detail.php?CONTRACT_ID=<?=$CONTRACT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            <a href="modify.php?CONTRACT_ID=<?=$CONTRACT_ID?>&xianshiinfo=fou"> <?=_("修改")?></a>&nbsp;
            <a href="javascript:delete_contract(<?=$CONTRACT_ID?>);"> <?=_("删除")?></a>&nbsp;
        </td>
    </tr>
<?
}

if($TOTAL_ITEMS>0)
{
?>
    <tr class="TableControl">
        <td colspan="19">
            <input type="checkbox" style="margin-left: 6px;" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
            <a href="javascript:delete_mail();" title="<?=_("删除所选合同信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; <input type="button"  onClick="sync();" title="<?=_("同步用户表用户名")?>" value="<?=_("同步数据")?>">
        </td>
    </tr>
<?
}else{
    Message("",_("无合同信息记录"));    
}
?>
</table>
</body>

</html>

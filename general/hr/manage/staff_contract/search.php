<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/utility_cache.php");

$PER_PAGE = 10;
if(!isset($start) || $start=="")
    $start=0;
$HTML_PAGE_TITLE = _("合同信息查询");
include_once("inc/header.inc.php");
?>
<script Language=JavaScript>
    function delete_contract(CONTRACT_ID)
    {
        msg='<?=_("确认要删除该项合同信息吗？")?>';
        if(window.confirm(msg))
        {
            URL="delete.php?CONTRACT_ID=" + CONTRACT_ID+"&STAFF_NAME=<?=$STAFF_NAME?>&STATUS=<?=$STATUS?>&CONTRACT_TYPE=<?=$CONTRACT_TYPE?>&STAFF_CONTRACT_NO=<?=$STAFF_CONTRACT_NO?>&MAKE_CONTRACT1=<?=$MAKE_CONTRACT1?>&MAKE_CONTRACT2=<?=$MAKE_CONTRACT2?>&TRAIL_OVER_TIME1=<?=$TRAIL_OVER_TIME1?>&TRAIL_OVER_TIME2=<?=$TRAIL_OVER_TIME2?>&CONTRACT_END_TIME1=<?=$CONTRACT_END_TIME1?>&CONTRACT_END_TIME2=<?=$CONTRACT_END_TIME2?>&start=<?=$start?>";
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
            {  val=el.value;
                checked_str+=val + ",";
            }
        }

        if(i==0)
        {
            el=document.getElementsByName("email_select");
            if(el.checked)
            {  val=el.value;
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
            url="delete.php?CONTRACT_ID="+ delete_str +"&start=<?=$start?>";
            location=url;
        }
    }

    function change_type(type)
    {
        window.location="index1.php?start=<?=$start?>";
    }
</script>

<body class="bodycolor" topmargin="5">

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------

if($MAKE_CONTRACT1!="")
{
    $TIME_OK=is_date($MAKE_CONTRACT1);

    if(!$TIME_OK)
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $MAKE_CONTRACT1=$MAKE_CONTRACT1." 00:00:00";
}

if($MAKE_CONTRACT2!="")
{
    $TIME_OK=is_date($MAKE_CONTRACT2);

    if(!$TIME_OK)
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $MAKE_CONTRACT2=$MAKE_CONTRACT2." 23:59:59";
}
if($TRAIL_OVER_TIME1!="")
{
    $TIME_OK=is_date($TRAIL_OVER_TIME1);

    if(!$TIME_OK)
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $TRAIL_OVER_TIME1=$TRAIL_OVER_TIME1." 00:00:00";
}

if($TRAIL_OVER_TIME2!="")
{
    $TIME_OK=is_date($TRAIL_OVER_TIME2);

    if(!$TIME_OK)
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $TRAIL_OVER_TIME2=$TRAIL_OVER_TIME2." 23:59:59";
}
if($CONTRACT_END_TIME1!="")
{
    $TIME_OK=is_date($CONTRACT_END_TIME1);

    if(!$TIME_OK)
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $CONTRACT_END_TIME1=$CONTRACT_END_TIME1." 00:00:00";
}

if($CONTRACT_END_TIME2!="")
{
    $TIME_OK=is_date($CONTRACT_END_TIME2);

    if(!$TIME_OK)
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $CONTRACT_END_TIME2=$CONTRACT_END_TIME2." 23:59:59";
}

if($CONTRACT_REMOVE_TIME1!="")
{
    $TIME_OK=is_date($CONTRACT_REMOVE_TIME1);

    if(!$TIME_OK)
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $CONTRACT_REMOVE_TIME1=$CONTRACT_REMOVE_TIME1." 00:00:00";
}

if($CONTRACT_REMOVE_TIME2!="")
{
    $TIME_OK=is_date($CONTRACT_REMOVE_TIME2);

    if(!$TIME_OK)
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $CONTRACT_REMOVE_TIME2=$CONTRACT_REMOVE_TIME2." 23:59:59";
}
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR = "";
$THE_FOUR_VAR  = "";
if($HR_TABLE==1)
{
    $END_DATE=date("Y-m-d",time()+(60*60*24*7));
    $CONDITION_STR.=" and TRAIL_OVER_TIME<='$END_DATE' and TRAIL_OVER_TIME>='$CUR_DATE' or CONTRACT_END_TIME<='$END_DATE' and CONTRACT_END_TIME>='$CUR_DATE'";
}
else
{
    if($STAFF_NAME!="")
        $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
    if($STAFF_NAME=="" && $STAFF_NAME1!="")
    {
        $query = "SELECT USER_ID FROM USER WHERE USER_NAME='$STAFF_NAME1'";
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
            $STAFF_ID_STR.=$ROW[0].",";
        $CONDITION_STR.=" and find_in_set(STAFF_NAME,'$STAFF_ID_STR')";
    }

    if($CONTRACT_TYPE!="")
        $CONDITION_STR.=" and CONTRACT_TYPE='$CONTRACT_TYPE'";
    if($STAFF_CONTRACT_NO!="")
        $CONDITION_STR.=" and STAFF_CONTRACT_NO like '%".$STAFF_CONTRACT_NO."%'";
    if($MAKE_CONTRACT1!="")
        $CONDITION_STR.=" and MAKE_CONTRACT>='$MAKE_CONTRACT1'";
    if($MAKE_CONTRACT2!="")
        $CONDITION_STR.=" and MAKE_CONTRACT<='$MAKE_CONTRACT2'";
    if($TRAIL_OVER_TIME1!="")
        $CONDITION_STR.=" and TRAIL_OVER_TIME>='$TRAIL_OVER_TIME1'";
    if($TRAIL_OVER_TIME2!="")
        $CONDITION_STR.=" and TRAIL_OVER_TIME<='$TRAIL_OVER_TIME2'";
    if($CONTRACT_END_TIME1!="")
        $CONDITION_STR.=" and CONTRACT_END_TIME>='$CONTRACT_END_TIME1'";
    if($CONTRACT_END_TIME2!="")
        $CONDITION_STR.=" and CONTRACT_END_TIME<='$CONTRACT_END_TIME2'";
    if($CONTRACT_REMOVE_TIME1!="")
        $CONDITION_STR.=" and CONTRACT_REMOVE_TIME>='$CONTRACT_REMOVE_TIME1'";
    if($CONTRACT_REMOVE_TIME2!="")
        $CONDITION_STR.=" and CONTRACT_REMOVE_TIME<='$CONTRACT_REMOVE_TIME2'";

    if($CONTRACT_ENTERPRIES!="")
        $CONDITION_STR.=" and CONTRACT_ENTERPRIES='$CONTRACT_ENTERPRIES'";}

if($CONTRACT_SPECIALIZATION !="")
    $CONDITION_STR.=" and CONTRACT_SPECIALIZATION='$CONTRACT_SPECIALIZATION'";

$CONTRACT_COUNT2=0;
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_CONTRACT where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$CONTRACT_COUNT2=mysql_num_rows($cursor);

$THE_FOUR_VAR .= "STAFF_NAME=$STAFF_NAME&STATUS=$STATUS&CONTRACT_TYPE=$CONTRACT_TYPE&STAFF_CONTRACT_NO=$STAFF_CONTRACT_NO&MAKE_CONTRACT1=$MAKE_CONTRACT1&MAKE_CONTRACT2=$MAKE_CONTRACT2&TRAIL_OVER_TIME1=$TRAIL_OVER_TIME1&TRAIL_OVER_TIME2=$TRAIL_OVER_TIME2&CONTRACT_END_TIME1=$CONTRACT_END_TIME1&CONTRACT_END_TIME2=$CONTRACT_END_TIME2&CONTRACT_ENTERPRIES=$CONTRACT_ENTERPRIES&"."start";

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("合同信息查询结果")?></span><br>
        </td>
        <td align="right" valign="bottom" class="small1"><?=page_bar($start,$CONTRACT_COUNT2,$PER_PAGE,$THE_FOUR_VAR)?></td>
    </tr>
</table>

<?
$query.=" limit $start,$PER_PAGE";
$cursor=exequery(TD::conn(),$query);
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
        $query7 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor7= exequery(TD::conn(),$query7);
        if($ROW7=mysql_fetch_array($cursor7))
        {
            $STAFF_NAME1=$ROW7["STAFF_NAME"]."(<font color='red'>"._("用户已删除")."</font>)";
        }
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

if($CONTRACT_COUNT==1)
{
?>
<table class="TableList" width="100%">
    <thead class="TableHeader">
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
    </thead>
    <?
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
        <td nowrap align="center"><?=$TRAIL_OVER_TIME?></td>
        <td nowrap align="center"><?=$CONTRACT_END_TIME?></td>
        <td align="center">
            <a href="javascript:;" onClick="window.open('contract_detail.php?CONTRACT_ID=<?=$CONTRACT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            <a href="modify.php?CONTRACT_ID=<?=$CONTRACT_ID?>"> <?=_("修改")?></a>
            <a href="javascript:delete_contract(<?=$CONTRACT_ID?>);"> <?=_("删除")?></a>
        </td>
        </td>
    </tr>
    <?
    }

    if($CONTRACT_COUNT==0)
    {
        Message("",_("无符合条件的合同信息！"));
        Button_Back();
        exit;
    }
    else
    {
    ?>
    <tr class="TableControl">
        <td colspan="19">
            <input type="checkbox" name="allbox" style="margin-left: 6px;" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
            <a href="javascript:delete_mail();" title="<?=_("删除所选合同信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp;
        </td>
    </tr>
</table>
<br>
    <div align="center">
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php';">
    </div>
<?
}
?>
</body>

</html>

<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("员工离职信息查询");
include_once("inc/header.inc.php");

$PAGE_SIZE =10;
$CUR_DATE=date("Y-m-d",time());
if(!isset($start) || $start=="")
    $start=0;
?>


<script Language=JavaScript>
function delete_leave(LEAVE_ID)
{
    msg='<?=_("确认要删除该项员工离职信息吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?LEAVE_ID=" + LEAVE_ID+"&PAGE_START=<?=$PAGE_START?>";
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
        alert("<?=_("要删除员工离职信息，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要删除该项员工离职信息吗？")?>';
    if(window.confirm(msg))
    {
        url="delete.php?LEAVE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
        location=url;
    }
}

function change_type(type)
{
    window.location="index1.php?start=<?=$start?>";
}
</script>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------

if($QUIT_TIME_PLAN1!="")
{
    $TIME_OK=is_date($QUIT_TIME_PLAN1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    //$QUIT_TIME_PLAN1=$QUIT_TIME_PLAN1." 00:00:00";
}

if($QUIT_TIME_FACT1!="")
{
    $TIME_OK=is_date($QUIT_TIME_FACT1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    //$QUIT_TIME_FACT1=$QUIT_TIME_FACT1." 23:59:59";
}

if($QUIT_TIME_PLAN2!="")
{
    $TIME_OK=is_date($QUIT_TIME_PLAN2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    //$QUIT_TIME_PLAN2=$QUIT_TIME_PLAN2." 00:00:00";
}

if($QUIT_TIME_FACT2!="")
{
    $TIME_OK=is_date($QUIT_TIME_FACT2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    //$QUIT_TIME_FACT2=$QUIT_TIME_FACT2." 23:59:59";
}
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
    $YEAR=date("Y",time());
    $START_DATE=$YEAR."-01-01";
    $END_DATE=$YEAR."-12-31";
    $CONDITION_STR.=" and QUIT_TIME_FACT >= '$START_DATE' and QUIT_TIME_FACT <= '$END_DATE'";
}
else
{
    if($QUIT_REASON!="")
        $CONDITION_STR.=" and QUIT_REASON like '%".$QUIT_REASON."%'";
    if($MATERIALS_CONDITION!="")
        $CONDITION_STR.=" and MATERIALS_CONDITION like '%".$MATERIALS_CONDITION."%'";
    if($LEAVE_PERSON!="")
        $CONDITION_STR.=" and LEAVE_PERSON='$LEAVE_PERSON'";
    if($QUIT_TYPE!="")
        $CONDITION_STR.=" and QUIT_TYPE='$QUIT_TYPE'";
    if($LEAVE_DEPT!="")
        $CONDITION_STR.=" and LEAVE_DEPT='$LEAVE_DEPT'";
    if($QUIT_TIME_PLAN1!="")
        $CONDITION_STR.=" and QUIT_TIME_PLAN>='$QUIT_TIME_PLAN1'";
    if($QUIT_TIME_PLAN2!="")
        $CONDITION_STR.=" and QUIT_TIME_PLAN<='$QUIT_TIME_PLAN2'";
    if($QUIT_TIME_FACT1!="")
        $CONDITION_STR.=" and QUIT_TIME_FACT>='$QUIT_TIME_FACT1'";
    if($QUIT_TIME_FACT2!="")
        $CONDITION_STR.=" and QUIT_TIME_FACT<='$QUIT_TIME_FACT2'";
}
$CONDITION_STR = hr_priv("CREATE_USER_ID").$CONDITION_STR;
$query = "SELECT count(*) from HR_STAFF_LEAVE where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$ROW=mysql_fetch_array($cursor);
$COUNT=$ROW[0];
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("员工离职信息查询结果")?></span><br>
        </td>
        <?
        $QSTRING="";
        foreach ($_POST as $key=> $value)
            $QSTRING.=$key."=".$value."&";

        $THE_FOUR_VAR = $QSTRING."start";

        if($_SERVER['QUERY_STRING'] == "")
        {
            $_SERVER['QUERY_STRING'] = $QSTRING;
        }
        ?>
        <td align="right" valign="bottom" class="small1"><?=page_bar($start,$COUNT,$PAGE_SIZE,$THE_FOUR_VAR,null,false,1)?></td>
    </tr>
    </tr>
</table>
<?

$query = "SELECT * from HR_STAFF_LEAVE where ".$CONDITION_STR." limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
$LEAVE_COUNT++;
$LEAVE_ID=$ROW["LEAVE_ID"];
$CREATE_USER_ID=$ROW["CREATE_USER_ID"];
$CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
$QUIT_TIME_PLAN=$ROW["QUIT_TIME_PLAN"];
$QUIT_TYPE=$ROW["QUIT_TYPE"];
$LAST_SALARY_TIME=$ROW["LAST_SALARY_TIME"];
$LEAVE_PERSON=$ROW["LEAVE_PERSON"];
$ADD_TIME=$ROW["ADD_TIME"];
$QUIT_TIME_FACT=$ROW["QUIT_TIME_FACT"];
$POSITION=$ROW["POSITION"];
$IS_REINSTATEMENT=$ROW["IS_REINSTATEMENT"];
$LEAVE_DEPT =$ROW["LEAVE_DEPT"];
$SALARY =$ROW["SALARY"];

$LEAVE_DEPT_NAME=substr(GetDeptNameById($LEAVE_DEPT),0,-1);
$QUIT_TYPE=get_hrms_code_name($QUIT_TYPE,"HR_STAFF_LEAVE");
$LEAVE_PERSON_NAME=substr(GetUserNameById($LEAVE_PERSON),0,-1);
if($LEAVE_PERSON_NAME=="")
{
    $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
        $LEAVE_PERSON=$ROW1["STAFF_NAME"];
    $LEAVE_PERSON_NAME=$LEAVE_PERSON."("._("<font color=red>用户已删除</font>").")";
}
if($POSITION=="")
{
    $query1 = "SELECT JOB_POSITION from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
        $POSITION=$ROW1["JOB_POSITION"];
}
//add by zxb 2014-4-17 角色信息
$query1 = "SELECT USER_PRIV_NAME from USER,USER_PRIV where USER.USER_ID='$LEAVE_PERSON' AND USER.USER_PRIV=USER_PRIV.USER_PRIV";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
    $USER_PRIV_NAME=$ROW1["USER_PRIV_NAME"];
/*不清楚什么意思20131013
$query1 = "SELECT JOB_POSITION from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
   $JOB_POSITION=$ROW1["JOB_POSITION"];
*/
if($LEAVE_COUNT==1)
{
?>
<table class="TableList" width="100%">
    <thead class="TableHeader">
    <td nowrap align="center"><?=_("选择")?></td>
    <td nowrap align="center"><?=_("离职人员")?></td>
    <td nowrap align="center"><?=_("角色")?></td>
    <td nowrap align="center"><?=_("离职部门")?></td>
    <td nowrap align="center"><?=_("担任职务")?></td>
    <td nowrap align="center"><?=_("离职类型")?></td>
    <td nowrap align="center"><?=_("拟离职日期")?></td>
    <td nowrap align="center"><?=_("实际离职日期")?></td>
    <td nowrap align="center"><?=_("工资截止日期")?></td>
    <td nowrap align="center"><?=_("离职当月薪资")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    <?
    }
    ?>
    <tr class="TableData">
        <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$LEAVE_ID?>" onClick="check_one(self);">
        <td nowrap align="center"><?=$LEAVE_PERSON_NAME?></td>
        <td nowrap align="center"><?=$USER_PRIV_NAME?></td>
        <td nowrap align="center"><?=$LEAVE_DEPT_NAME?></td>
        <td nowrap align="center"><?=$POSITION?></td>
        <td nowrap align="center"><?=$QUIT_TYPE?></td>
        <td nowrap align="center"><?=$QUIT_TIME_PLAN=="0000-00-00"?"":$QUIT_TIME_PLAN;?></td>
        <td nowrap align="center"><?=$QUIT_TIME_FACT=="0000-00-00"?"":$QUIT_TIME_FACT;?></td>
        <td nowrap align="center"><?=$LAST_SALARY_TIME=="0000-00-00"?"":$LAST_SALARY_TIME;?></td>
        <td nowrap align="center"><?=$SALARY;?></td>
        <td align="center">
            <a href="javascript:;" onClick="window.open('leave_detail.php?LEAVE_ID=<?=$LEAVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
            <?
            if($IS_REINSTATEMENT == 0)
            {
                ?>
                <a href="../staff_reinstatement/new.php?USER_ID=<?=$LEAVE_PERSON?>"> <?=_("复职")?></a>&nbsp;
                <?
            }
            ?>
            <a href="modify.php?LEAVE_ID=<?=$LEAVE_ID?>"> <?=_("修改")?></a>&nbsp;
            <a href="javascript:delete_leave(<?=$LEAVE_ID?>);"> <?=_("删除")?></a>&nbsp;
        </td>
        </td>
    </tr>
    <?
    }
    if($LEAVE_COUNT==0)
    {
        Message("",_("无符合条件的员工离职信息！"));
        Button_Back();
        exit;
    }
    else
    {
    ?>
    <tr class="TableControl">
        <td colspan="19">
            <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
            <a href="javascript:delete_mail();" title="<?=_("删除所选员工离职信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp;
        </td>
    </tr>
</table>
    <div align="center" style="margin-top:5px;">
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php'">
    </div>
<?
}
?>
</body>

</html>

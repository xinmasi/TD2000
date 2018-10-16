<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

if(!$PAGE_SIZE)
    $PAGE_SIZE = get_page_size("NEWS", 10);
if(!isset($start) || $start=="")
    $start=0;

if(!isset($TOTAL_ITEMS))
{
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
        $query = "SELECT count(*) from HR_RECRUIT_FILTER";
    else
        $query = "SELECT count(*) from HR_RECRUIT_FILTER where TRANSACTOR_STEP='".$_SESSION["LOGIN_USER_ID"]."' or NEXT_TRANSA_STEP='".$_SESSION["LOGIN_USER_ID"]."' or NEXT_TRANSA_STEP1='".$_SESSION["LOGIN_USER_ID"]."' or NEXT_TRANSA_STEP2='".$_SESSION["LOGIN_USER_ID"]."' or NEXT_TRANSA_STEP3='".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor= exequery(TD::conn(),$query, $connstatus);
    $TOTAL_ITEMS=0;
    if($ROW=mysql_fetch_array($cursor))
        $TOTAL_ITEMS=$ROW[0];
}

//修改事务提醒状态--yc
update_sms_status('65',0);


$HTML_PAGE_TITLE = _("招聘筛选");
include_once("inc/header.inc.php");
?>


<script>
function open_news(FILTER_ID,FORMAT)
{
    URL="../show/read_news.php?FILTER_ID="+FILTER_ID;
    myleft=(screen.availWidth-780)/2;
    mytop=100
    mywidth=780;
    myheight=500;
    if(FORMAT=="1")
    {
        myleft=0;
        mytop=0
        mywidth=screen.availWidth-10;
        myheight=screen.availHeight-40;
    }
    window.open(URL,"read_news","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function delete_filter(FILTER_ID,start,EXPERT_ID)
{
    msg='<?=_("确认要删除该记录吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?FILTER_ID=" + FILTER_ID + "&start=" + start + "&EXPERT_ID="+EXPERT_ID;
        window.location=URL;
    }
}

function order_by(field,asc_desc)
{
    window.location="index1.php?start=<?=$start?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function check_one(el)
{
    if(!el.checked)
        document.getElementsByName("allbox").item(0).checked=false;
}

function check_all()
{
    for (i=0;i<document.getElementsByName("email_select").length;i++)
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
function delete_mail()
{
    delete_str="";
    for(i=0;i<document.getElementsByName("email_select").length;i++)
    {

        el=document.getElementsByName("email_select").item(i);
        if(el.checked)
        {  val=el.value;
            delete_str+=val + ",";
        }
    }

    if(i==0)
    {
        el=document.getElementsByName("email_select");
        if(el.checked)
        {  val=el.value;
            delete_str+=val + ",";
        }
    }

    if(delete_str=="")
    {
        alert("<?=_("要删除人事档案，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要删除所选培训记录吗？")?>';
    if(window.confirm(msg))
    {
        url="delete.php?FILTER_ID="+ delete_str +"&start=<?=$start?>" + "&EXPERT_ID=<?=$EXPERT_ID?>";
        location=url;
    }
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/news.gif" align="absmiddle"><span class="big3"> <?=_("招聘筛选")?></span>&nbsp;</td>
        <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
    </tr>
</table>
<?


if($ASC_DESC=="")
    $ASC_DESC="1";
if($FIELD=="")
    $FIELD="NEW_TIME";

if($_SESSION["LOGIN_USER_PRIV"]=="1")
    $query = "SELECT * from HR_RECRUIT_FILTER ";
else
    $query = "SELECT * from HR_RECRUIT_FILTER where TRANSACTOR_STEP='".$_SESSION["LOGIN_USER_ID"]."' or NEXT_TRANSA_STEP='".$_SESSION["LOGIN_USER_ID"]."' or NEXT_TRANSA_STEP1='".$_SESSION["LOGIN_USER_ID"]."' or NEXT_TRANSA_STEP2='".$_SESSION["LOGIN_USER_ID"]."' or NEXT_TRANSA_STEP3='".$_SESSION["LOGIN_USER_ID"]."'";

$query .= " order by $FIELD";
if($ASC_DESC=="1")
    $query .= " desc";
else
    $query .= " asc";
$query .= " limit $start,$PAGE_SIZE";
if($ASC_DESC=="0")
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";

$cursor= exequery(TD::conn(),$query, $connstatus);
$COUNT = mysql_num_rows($cursor);

if($COUNT <= 0)
{
    Message("", _("无招聘筛选信息"));
    exit;
}

?>

<table class="TableList" width="100%">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("选择")?></td>
        <td nowrap align="center"><?=_("应聘者姓名")?></td>
        <td nowrap align="center"><?=_("应聘岗位")?></td>
        <td nowrap align="center"><?=_("所学专业")?></td>
        <td nowrap align="center"><?=_("联系电话")?></td>
        <td nowrap align="center"><?=_("发起人")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>

    <?
    $FILTER_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $FILTER_COUNT++;

        $FILTER_ID=$ROW["FILTER_ID"];
        $USER_ID=$ROW["USER_ID"];
        $DEPT_ID=$ROW["DEPT_ID"];
        $EXPERT_ID=$ROW["EXPERT_ID"];
        $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];
        $PLAN_NO=$ROW["PLAN_NO"];
        $POSITION=$ROW["POSITION"];
        $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
        $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
        $ONE_DATE_TIME=$ROW["ONE_DATE_TIME"];
        $NEXT_TRANSA_STEP=$ROW["NEXT_TRANSA_STEP"];
        $NEXT_TRANSA_STEP1=$ROW["NEXT_TRANSA_STEP1"];
        $NEXT_TRANSA_STEP2=$ROW["NEXT_TRANSA_STEP2"];
        $NEXT_TRANSA_STEP3=$ROW["NEXT_TRANSA_STEP3"];
        $TRANSACTOR_STEP=$ROW["TRANSACTOR_STEP"];
        $NEW_TIME=$ROW["NEW_TIME"];
        $STEP_FLAG=$ROW["STEP_FLAG"];
        $END_FLAG=$ROW["END_FLAG"];

        $NEXT_TRANSA_STEP = substr(GetUserNameById($NEXT_TRANSA_STEP),0,-1);
        $TRANSACTOR_STEP = substr(GetUserNameById($TRANSACTOR_STEP),0,-1);
        if($FILTER_COUNT%2==1)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";
// VAR_DUMP($NEXT_TRANSA_STEP);
// VAR_DUMP($_SESSION["LOGIN_USER_NAME"]);
        ?>
        <tr class="<?=$TableLine?>">
            <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$FILTER_ID?>" onClick="check_one(self);">
            <td nowrap align="center" >
                <a href="javascript:;" onClick="window.open('filter_detail.php?FILTER_ID=<?=$FILTER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=$EMPLOYEE_NAME?><?if($END_FLAG==2) {echo "<span nowrap style='color:red' style='display:inline;'>("._("已通过").")</span>";} else if($END_FLAG==1) {echo "<span nowrap style='color:red' style='display:inline;'>("._("未通过").")</span>";} else echo "<div nowrap style='color:red' style='display:inline;'>("._("待筛选").")</div>"; ?></a>&nbsp;
            </td>
            <td nowrap align="center"><?=$POSITION?></td>
            <td nowrap align="center"><?=$EMPLOYEE_MAJOR?></td>
            <td nowrap align="center"><?=$EMPLOYEE_PHONE?></td>
            <td nowrap align="center"><?=$TRANSACTOR_STEP?></td>
            <td align="center" width="200">
                <a href="javascript:;" onClick="window.open('filter_detail.php?FILTER_ID=<?=$FILTER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
                <? if($END_FLAG!=2&&$END_FLAG!=1)
                {

                    if($STEP_FLAG==4  && $NEXT_TRANSA_STEP3==$_SESSION["LOGIN_USER_ID"])
                    {

                        echo _("<a href='deal_with.php?FILTER_ID=$FILTER_ID&start==$start'> 办理</a>&nbsp;");
                    }
                    if($STEP_FLAG==3  && $NEXT_TRANSA_STEP2==$_SESSION["LOGIN_USER_ID"])
                    {
                        echo _("<a href='deal_with.php?FILTER_ID=$FILTER_ID&start==$start'> 办理</a>&nbsp;");
                    }
                    if($STEP_FLAG==2  && $NEXT_TRANSA_STEP1==$_SESSION["LOGIN_USER_ID"])
                    {
                        echo _("<a href='deal_with.php?FILTER_ID=$FILTER_ID&start==$start'> 办理</a>&nbsp;");
                    }
                    if($STEP_FLAG==1  && $NEXT_TRANSA_STEP==$_SESSION["LOGIN_USER_NAME"])
                    {
                        echo _("<a href='deal_with.php?FILTER_ID=$FILTER_ID&start==$start'> 办理</a>&nbsp;");
                    }
                }

                ?>
                <a href="modify.php?FILTER_ID=<?=$FILTER_ID?>&start=<?=$start?>"> <?=_("修改")?></a>&nbsp;
                <a href="#" onclick="delete_filter('<?=$FILTER_ID?>','<?=$start?>','<?=$EXPERT_ID?>')"> <?=_("删除")?></a>
            </td>
        </tr>
        <?
    }
    ?>

    <tr class="TableControl">
        <td colspan="19">
            <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label> &nbsp;
            <a href="javascript:delete_mail();" title="<?=_("删除所选培训记录")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp;
        </td>
    </tr>

</table>
</body>

</html>

<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");

//2013-4-11 主服务查询
if($IS_MAIN==1)
{
    $QUERY_MASTER = true;
}
else
{
    $QUERY_MASTER = "";
}
   
$HTML_PAGE_TITLE = _("联系人查询");
include_once("inc/header.inc.php");

//============================ 显示 =======================================
$query = "SELECT * FROM ADDRESS_GROUP WHERE USER_ID='' or USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ORDER BY GROUP_NAME asc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $PRIV_DEPT      = $ROW["PRIV_DEPT"];
    $PRIV_ROLE      = $ROW["PRIV_ROLE"];
    $PRIV_USER      = $ROW["PRIV_USER"];
    $USER_ID        = $ROW["USER_ID"];
    if($PRIV_DEPT!="ALL_DEPT" && $USER_ID=="")
    {
        if(!find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) && !find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) && !find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]) and !check_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV_OTHER"],true)!="" and !check_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID_OTHER"],true)!="")
        {
            continue;
        }
    }
    
    $GROUP_ID1     = $ROW["GROUP_ID"];
    
    $GROUP_ID_STR .= $GROUP_ID1.",";
}//while

$GROUP_ID_STR = $GROUP_ID_STR."0,";

$query = "SELECT * FROM ADDRESS WHERE (USER_ID='' or USER_ID='".$_SESSION["LOGIN_USER_ID"]."') ";

$s_where_str  = "";
$s_where_str .= "`GROUP_ID=".$GROUP_ID."`";
$where_repeat = " USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ";

if($PSN_NAME!="")
{
    $query        .= " and PSN_NAME like '%$PSN_NAME%'";
    $s_where_str  .= "`PSN_NAME=".$PSN_NAME."`";
	$where_repeat .= " and PSN_NAME like '%$PSN_NAME%'";
}
if($GROUP_ID!="" && $GROUP_ID!="ALL_DEPT")
{
    $query        .= " and GROUP_ID='$GROUP_ID'";
	$where_repeat .= " and GROUP_ID='$GROUP_ID'";
}
if($SEX!="" && $SEX!="ALL")
{
    $query        .= " and SEX='$SEX'";
	$where_repeat .= " and SEX='$SEX'";
}
if($MOBIL_NO!="")
{
    $query        .= " and MOBIL_NO like '%$MOBIL_NO%'";
    $s_where_str  .= "`MOBIL_NO=".$MOBIL_NO."`";
	$where_repeat .= " and MOBIL_NO like '%$MOBIL_NO%'";
}
if($DEPT_NAME!="")
{
    $query        .= " and DEPT_NAME like '%$DEPT_NAME%'";
    $s_where_str  .= "`DEPT_NAME=".$DEPT_NAME."`";
	$where_repeat .= " and DEPT_NAME like '%$DEPT_NAME%'";
}
if($TEL_NO_DEPT!="")
{
    $query        .= " and TEL_NO_DEPT like '%$TEL_NO_DEPT%'";
    $s_where_str  .= "`TEL_NO_DEPT=".$TEL_NO_DEPT."`";
	$where_repeat .= " and TEL_NO_DEPT like '%$TEL_NO_DEPT%'";
}
if($TEL_NO_HOME!="")
{
    $query        .= " and TEL_NO_HOME like '%$TEL_NO_HOME%'";
    $s_where_str  .= "`TEL_NO_HOME=".$TEL_NO_HOME."`";
	$where_repeat .= " and TEL_NO_HOME like '%$TEL_NO_HOME%'";
}
if($ADD_DEPT!="")
{
    $query        .= " and ADD_DEPT like '%$ADD_DEPT%'";
    $s_where_str  .= "`ADD_DEPT=".$ADD_DEPT."`";
	$where_repeat .= " and ADD_DEPT like '%$ADD_DEPT%'";
}
if($ADD_HOME!="")
{
    $query        .= " and ADD_HOME like '%$ADD_HOME%'";
    $s_where_str  .= "`ADD_HOME=".$ADD_HOME."`";
	$where_repeat .= " and ADD_HOME like '%$ADD_HOME%'";
}
if($NOTES!="")
{
    $query        .= " and NOTES like '%$NOTES%'";
    $s_where_str  .= "`NOTES=".$NOTES."`";
	$where_repeat .= " and NOTES like '%$NOTES%'";
}

if($ALL_NO!="")
{
    $query        .= " and (MOBIL_NO like '%$ALL_NO%' or TEL_NO_HOME like '%$ALL_NO%' or TEL_NO_DEPT like '%$ALL_NO%' or OICQ_NO like '%$ALL_NO%' or EMAIL like '%$ALL_NO%')";
	$where_repeat .= " and (MOBIL_NO like '%$ALL_NO%' or TEL_NO_HOME like '%$ALL_NO%' or TEL_NO_DEPT like '%$ALL_NO%' or OICQ_NO like '%$ALL_NO%' or EMAIL like '%$ALL_NO%')";
    $s_where_str  .= "`ALL_NO=".$ALL_NO."` ";
}

$where_repeat = str_replace("'","@",$where_repeat);
$query       .= " ORDER BY GROUP_ID,PSN_NAME asc";
$cursor       = exequery(TD::conn(),$query);
$ADD_COUNT    = 0;
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function add_detail(ADD_ID)
{
    URL="add_detail.php?ADD_ID="+ADD_ID+"&where_str=<?=$s_where_str?>";
    myleft=(screen.availWidth-750)/2;
    window.open(URL,"detail","height=620,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=50,left="+myleft+",resizable=yes");
}

function delete_add(ADD_ID)
{
    msg='<?=_("确认要删除该联系人吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?ADD_ID=" + ADD_ID+"&where_str=<?=$s_where_str?>";
        window.location=URL;
    }
}

function check_all()
{
    for (i=0;i<document.getElementsByName('add_select').length;i++)
    {
        if(document.getElementsByName("allbox")[0].checked)
        {
            document.getElementsByName('add_select').item(i).checked=true;
        }
        else
        {
            document.getElementsByName('add_select').item(i).checked=false;
        }
    }
    
    if(i==0)
    {
        if(document.getElementsByName("allbox")[0].checked)
        {
            document.getElementsByName('add_select').checked=true;
        }
        else
        {
            document.getElementsByName('add_select').checked=false;
        }
    }
}

function check_one(el)
{
    if(!el.checked)
    {
        document.getElementsByName("allbox")[0].checked=false;
    }
}

function get_checked()
{
    checked_str="";
    for(i=0;i<document.getElementsByName('add_select').length;i++)
    {
        el=document.getElementsByName('add_select').item(i);
        if(el.checked)
        {
            val=el.value;
            checked_str+=val + ",";
        }
    }
    
    if(i==0)
    {
        el=document.getElementsByName('add_select');
        if(el.checked)
        {
            val=el.value;
            checked_str+=val + ",";
        }
    }
    return checked_str;
}

function del_add_str()
{
    delete_str=get_checked();
    if(delete_str=="")
    {
        alert("<?=_("请至少选择一个联系人")?>");
        return;
    }
    
    msg='<?=_("确认要删除所选联系人吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?DELETE_STR="+delete_str+"&where_str=<?=$s_where_str?>";
        window.location=URL;
    }
}

function group_send(send_type)
{
    send_str=get_checked();
    
    if(send_str=="")
    {
        alert("<?=_("请至少选择一个联系人")?>");
        return;
    }
    
    URL="group_send.php?send_str="+send_str+"&send_type="+send_type;
    
    myleft=(screen.availWidth-750)/2;
    window.open(URL,"group_send","height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=50,left="+myleft+",resizable=yes");
}

function del_repeat()
{
	msg='<?=_("确认要删除查询结果中重复的联系人吗？")?>';
	if(window.confirm(msg))
    {
        URL="delete.php?type=repeat&where_str=<?=$s_where_str?>&where_repeat=<?=$where_repeat?>";
        window.location=URL;
    }
	
}
</script>
<body class="bodycolor">

<br>
<table border="0" style="width:85%;" cellspacing="0" cellpadding="3" class="small" align="center">
    <tr>
        <td class="Big"><span class="big3"> <?=_("联系人查询结果")?></span>&nbsp;&nbsp;<button type="button" class="btn" onClick="location='search.php';"><?=_("返回")?></button>&nbsp;&nbsp;<button type="button" class="btn" title="删除查询结果中重复的联系人信息" onClick="del_repeat();"><?=_("删除重复联系人")?></button>
        </td>
    </tr>
</table>
<br>
<?
while($ROW=mysql_fetch_array($cursor))
{
    $GROUP_ID=$ROW["GROUP_ID"];
    if(!find_id($GROUP_ID_STR,$GROUP_ID))
    {
        continue;
    }
    
    $ADD_COUNT++;
    
    $ADD_ID         = $ROW["ADD_ID"];
    $PSN_NAME       = $ROW["PSN_NAME"];
    $SEX            = $ROW["SEX"];
    $DEPT_NAME      = $ROW["DEPT_NAME"];
    $TEL_NO_DEPT    = $ROW["TEL_NO_DEPT"];
    $MOBIL_NO       = $ROW["MOBIL_NO"];
    $EMAIL          = $ROW["EMAIL"];
    $USER_ID        = $ROW["USER_ID"];
    
    $query1  = "SELECT * FROM ADDRESS_GROUP WHERE GROUP_ID='$GROUP_ID'";
    $cursor1 = exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
    {
        $GROUP_NAME = $ROW1["GROUP_NAME"];
    }
    if($GROUP_ID==0)
    {
        $GROUP_NAME = _("默认");
    }
    if($USER_ID=="")
    {
        $GROUP_NAME.=_("(公共)");
    }
    
    //该组是否有维护权限
    $PRIV_FLAG = 0;
    if($_SESSION["LOGIN_USER_PRIV"]!=1 && $USER_ID!=$_SESSION["LOGIN_USER_ID"])
    {
        $query1 = "SELECT GROUP_ID,GROUP_NAME FROM ADDRESS_GROUP WHERE GROUP_ID='$GROUP_ID' and  (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER))";
    }
    else
    {
        $query1 = "SELECT GROUP_ID,GROUP_NAME FROM ADDRESS_GROUP WHERE GROUP_ID='$GROUP_ID'";
    }
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
    {
        $PRIV_FLAG = 1;
    }
    
    if($_SESSION["LOGIN_USER_PRIV"]==1 && $GROUP_ID==0)
    {
        $PRIV_FLAG = 1;
    }
    
    if($MOBIL_NO!="")
    {
        $MOBIL_NO_STR.=$MOBIL_NO.",";
    }
    
    if($SEX=="0")
    {
        $SEX=_("男");
    }
    else if($SEX=="1")
    {
        $SEX=_("女");
    }
    else
    {
        $SEX="";
    }

    if($ADD_COUNT==1)
    {
?>

<table class="table table-bordered table-hover" style="width:85%;" align="center">
    <thead style="background-color:#EBEBEB;">
        <th nowrap style="text-align: center;width:3%;"><?=_("选择")?></th>
        <th nowrap style="text-align: center;width:8%;"><?=_("组名")?></th>
        <th nowrap style="text-align: center;width:9%;"><?=_("姓名")?></th>
        <th nowrap style="text-align: center;width:5%;"><?=_("性别")?></th>
        <th nowrap style="text-align: center;width:20%;"><?=_("单位名称")?></th>
        <th nowrap style="text-align: center;width:10%;"><?=_("工作电话")?></th>
        <th nowrap style="text-align: center;width:10%;"><?=_("手机")?> <a href="javascript:form1.submit();"><?=_("群发")?></a></th>
        <th nowrap style="text-align: center;width:10%;"><?=_("电子邮件")?></th>
        <th nowrap style="text-align: center;width:10%;"><?=_("操作")?></th>
    </thead>

<?
    }
?>
    <tr class="TableData">
<?
if($PRIV_FLAG==1)
{
?>
        <td style="text-align: center;"><input type="checkbox" name="add_select" value="<?=$ADD_ID?>" onClick="check_one(self);"></td>
<?
}
else
{
?>
        <td style="text-align: center;">-</td>
<?
}
?>
        <td style="text-align: center;"><?=$GROUP_NAME?></td>
        <td style="text-align: center;"><a href="javascript:add_detail('<?=$ADD_ID?>');"><?=$PSN_NAME?></a></td>
        <td style="text-align: center;"><?=$SEX?></td>
        <td style="text-align: center;"><?=$DEPT_NAME?></td>
        <td style="text-align: center;"><?=$TEL_NO_DEPT?></td>
        <td style="text-align: center;"><a href="/general/mobile_sms/new/?TO_ID1=<?=$MOBIL_NO?>," target="_blank"><?=$MOBIL_NO?></a></td>
        <td style="text-align: center;"><a href="/general/email/new/?TO_WEBMAIL=<?=$EMAIL?>" target="_blank"><?=$EMAIL?></a></td>
        <td style="text-align: center;" width="100">
            <a href="javascript:add_detail('<?=$ADD_ID?>');"> <?=_("详情")?></a>
<?
if($PRIV_FLAG==1)
{
?>          
            <a href="edit.php?add_id=<?=$ADD_ID?>&where_str=<?=$s_where_str?>"> <?=_("编辑")?></a>
            <a href="javascript:delete_add(<?=$ADD_ID?>);"> <?=_("删除")?></a>          
<?
}
?>
        </td>
    </tr>

<?
}

if($ADD_COUNT==0)
{
    Message("",_("无符合条件的联系人"));
?>
<center>
    <button type="button" class="btn" onClick="location='search.php';"><?=_("返回")?></button>
</center>
<?
    exit;
}
else
{
?>
    <tr class="TableControl" style="background:#fff">
        <td colspan="9" class="form-inline">
            &nbsp;<label class="checkbox" for="allbox_for"><input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">全选</label>&nbsp;
            &nbsp;<button type="button" class="btn" onClick="del_add_str();" title="删除所选联系人">删除</button>
            &nbsp;<button type="button" class="btn" onClick="group_send('tel');" title="群发所选联系人短信">群发短信</button>
            &nbsp;<button type="button" class="btn" onClick="group_send('email');" title="群发所选联系人邮件">群发邮件</button>
        </td>
    </tr>
</table>
<?
}
?>

<form name="form1" method="post" action="/general/mobile_sms/new/index.php">
    <input type="hidden" name="TO_ID1" value="<?=$MOBIL_NO_STR?>">
</form>

<center>
    <button type="button" class="btn" onClick="location='search.php';"><?=_("返回")?></button>
</center>
<br>
</body>
</html>

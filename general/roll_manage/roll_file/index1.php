<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

if(!isset($TYPE))
    $TYPE="0";
$ITEMS_IN_PAGE=10;
if(!isset($start) || $start=="")
    $start=0;

//修改事务提醒状态--yc
update_sms_status('37',0);

$HTML_PAGE_TITLE = _("文件管理");
include_once("inc/header.inc.php");
?>
<script>
function open_file(FILE_ID)
{
    URL="../read_file.php?FILE_ID="+FILE_ID;
    myleft=(screen.availWidth-500)/2;
    mytop=150
    mywidth=550;
    myheight=300;
    window.open(URL,"read_file","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function delete_file(FILE_ID,CUR_PAGE)
{
    msg='<?=_("确认要销毁该项文件吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?FILE_ID=" + FILE_ID + "&CUR_PAGE=" + CUR_PAGE;
        window.location=URL;
    }
}


function delete_all()
{
    delete_str="";
    for(i=0;i<document.getElementsByName("file_select").length;i++)
    {

        el=document.getElementsByName("file_select").item(i);
        if(el.checked)
        {  val=el.value;
            delete_str+=val + ",";
        }
    }

    if(i==0)
    {
        el=document.getElementsByName("file_select");
        if(el.checked)
        {  val=el.value;
            delete_str+=val + ",";
        }
    }

    if(delete_str=="")
    {
        alert("<?=_("要销毁文件，请至少选择其中一个。")?>");
        document.form1.reset();
        return;
    }


    msg='<?=_("确认要销毁已选中的文件吗？")?>';
    if(window.confirm(msg))
    {
        url="./delete_all.php?DELETE_STR="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
        window.location=url;
    }
}

function order_by(field,asc_desc)
{
    window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function file_troop(type)
{
    document.form1.action="troop.php?CUR_PAGE=<?=$CUR_PAGE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
    document.form1.target='_self';
    document.form1.submit();
}

function check_all()
{

    for (i=0;i<document.getElementsByName("file_select").length;i++)
    {
        if(document.getElementsByName("allbox")[0].checked)
            document.getElementsByName("file_select").item(i).checked=true;
        else
            document.getElementsByName("file_select").item(i).checked=false;
    }

    if(i==0)
    {
        if(document.getElementsByName("allbox")[0].checked)
            document.getElementsByName("file_select").checked=true;
        else
            document.getElementsByName("file_select").checked=false;
    }
}

function change_roll()
{
    delete_str="";
    for(i=0;i<document.getElementsByName("file_select").length;i++)
    {

        el=document.getElementsByName("file_select").item(i);
        if(el.checked)
        {  val=el.value;
            delete_str+=val + ",";
        }
    }

    if(i==0)
    {
        el=document.getElementsByName("file_select");
        if(el.checked)
        {  val=el.value;
            delete_str+=val + ",";
        }
    }
    var myselect=document.getElementById("SROLL_ID");
    var index=myselect.selectedIndex ;
    if(delete_str=="")
    {
        alert("<?=_("要组卷文件，请至少选择其中一个。")?>");
        document.form1.reset();
        return;
    }

    roll_id=myselect.options[index].value;
    url="./change_roll.php?DELETE_STR="+ delete_str +"&ROLL_ID="+roll_id+"&PAGE_START=<?=$PAGE_START?>";
    location=url;
}
function check_one(el)
{
    if(!el.checked)
        document.getElementsByName("allbox")[0].checked=false;
}
</script>

<body class="bodycolor">
<?
if($_SESSION["LOGIN_USER_PRIV"]=="1")
    $query = "SELECT count(*) from RMS_FILE where DEL_USER=''";
else
    $query = "SELECT count(*) from RMS_FILE,RMS_ROLL,RMS_ROLL_ROOM where RMS_FILE.ROLL_ID=RMS_ROLL.ROLL_ID and RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID and (RMS_FILE.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER = '".$_SESSION["LOGIN_USER_ID"]."') and RMS_FILE.DEL_USER=''";

if($TYPE!="0")
    $query .= " and CATALOG_NO='$TYPE'";

$cursor= exequery(TD::conn(),$query, $connstatus);
$RMS_FILE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
    $RMS_FILE_COUNT=$ROW[0];

if($RMS_FILE_COUNT==0)
{
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("文件管理")?></span>&nbsp;
            </td>
        </tr>
    </table>
    <br>

    <?
    Message("",_("无文件"));
    ?><br>    
    <tr class="TableControl">
        <td colspan="9">

            <b><?=_("文件操作：")?></b>
            <input type="button"  value="<?=_("文件导入")?>" class="SmallButton" onClick="window.location='import_prepare.php'" title="<?=_("文件导入")?>">
        </td>
    </tr>    
    <?
    exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("文件管理")?></span>&nbsp;
        </td>
        <td align="right" valign="bottom" class="small1"><?=sprintf(_("共%s条"), '<span class="big4">&nbsp;'.$RMS_FILE_COUNT.'</span>')?>&nbsp;
        </td>
        <td align="right" valign="bottom" class="small1">
            <?=page_bar($start,$RMS_FILE_COUNT,$ITEMS_IN_PAGE)?>
        </td>
    </tr>
</table>
<?
if($ASC_DESC=="")
    $ASC_DESC="1";
if($FIELD=="")
    $FIELD="FILE_CODE";
//============================ 显示已发布文件 =======================================
if($_SESSION["LOGIN_USER_PRIV"]=="1") {
    $query = "SELECT * from RMS_FILE where DEL_USER=''";
}
else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id != "") {
        $query = "SELECT * from RMS_FILE,RMS_ROLL,RMS_ROLL_ROOM where RMS_FILE.ROLL_ID=RMS_ROLL.ROLL_ID and RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID and (find_in_set(RMS_FILE.ADD_USER,'".$user_id."') or find_in_set(RMS_ROLL.MANAGER,'".$user_id."') or find_in_set(RMS_ROLL_ROOM.MANAGE_USER,'".$user_id."') and RMS_FILE.DEL_USER='')";
    }else{
        $query = "SELECT * from RMS_FILE,RMS_ROLL,RMS_ROLL_ROOM where RMS_FILE.ROLL_ID=RMS_ROLL.ROLL_ID and RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID and (RMS_FILE.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER = '".$_SESSION["LOGIN_USER_ID"]."' and RMS_FILE.DEL_USER='')";
    }
}
else
{
    $query = "SELECT * from RMS_FILE,RMS_ROLL,RMS_ROLL_ROOM where RMS_FILE.ROLL_ID=RMS_ROLL.ROLL_ID and RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID and (RMS_FILE.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER = '".$_SESSION["LOGIN_USER_ID"]."' and RMS_FILE.DEL_USER='')";
}
$query .= " order by $FIELD";
if($ASC_DESC=="1")
    $query .= " desc";
else
    $query .= " asc";
$query .= " limit $start,$ITEMS_IN_PAGE";
if($ASC_DESC=="0")
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";

?>
<table class="TableList" width="100%">
    <form action="?"  method="post" name="form1">
        <tr class="TableHeader">
            <td nowrap align="center"><input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"></td>
            <td nowrap align="center" onClick="order_by('FILE_CODE','<?if($FIELD=="FILE_CODE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("文件号")?></u><?if($FIELD=="FILE_CODE") echo $ORDER_IMG;?></td>
            <td nowrap align="center" onClick="order_by('FILE_TITLE','<?if($FIELD=="FILE_TITLE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("文件标题")?></u><?if($FIELD=="FILE_TITLE") echo $ORDER_IMG;?></td>
            <td nowrap align="center"><?=_("密级")?></td>
            <td nowrap align="center" onClick="order_by('SEND_UNIT','<?if($FIELD=="SEND_UNIT"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发文单位")?></u><?if($FIELD=="SEND_UNIT"||$FIELD=="") echo $ORDER_IMG;?></td>
            <td nowrap align="center" onClick="order_by('SEND_DATE','<?if($FIELD=="SEND_DATE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发文时间")?></u><?if($FIELD=="SEND_DATE") echo $ORDER_IMG;?></td>
            <td nowrap align="center"><?=_("所属案卷")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
        </tr>

    <?
    $cursor= exequery(TD::conn(),$query, $connstatus);
    $RMS_FILE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $RMS_FILE_COUNT++;

        $FILE_ID=$ROW["FILE_ID"];
        $FILE_CODE=$ROW["FILE_CODE"];
        $FILE_TITLE=$ROW["FILE_TITLE"];
        $SECRET=$ROW["SECRET"];
        $SEND_UNIT=$ROW["SEND_UNIT"];
        $SEND_DATE=$ROW["SEND_DATE"];
        $URGENCY=$ROW["URGENCY"];
        $ROLL_ID=$ROW["ROLL_ID"];

        $query1 = "SELECT * from RMS_ROLL where ROLL_ID='$ROLL_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $ROLL_NAME=$ROW["ROLL_NAME"];
            $STATUS=$ROW["STATUS"];
        }
        else
        {
            $ROLL_NAME="";
        }

        if($SEND_DATE=='0000-00-00')
            $SEND_DATE='';

        $FILE_TITLE=td_htmlspecialchars($FILE_TITLE);

        $SECRET=get_code_name($SECRET,"RMS_SECRET");
        $URGENCY=get_code_name($URGENCY,"RMS_URGENCY");

        if($RMS_FILE_COUNT%2==1)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";
        ?>
        <tr class="<?=$TableLine?>">
            <td nowrap align="center">
                <?
                if($STATUS!=1)
                {
                ?>
                <input type="checkbox" name="file_select" value="<?=$FILE_ID?>" onClick="check_one(self);"></td>
            <?
            }
            else
                echo "&nbsp;";
            ?>
            <td align="center">
                <a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_CODE?></a>
            </td>
            <td nowrap align="center"><a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_TITLE?></a></td>
            <td nowrap align="center"><?=$SECRET?></td>
            <td nowrap align="center"><?=$SEND_UNIT?></td>
            <td nowrap align="center"><?=$SEND_DATE?></td>
            <td nowrap align="center"><?=$ROLL_NAME?></td>
            <td nowrap align="center">
                <?
                if($STATUS!=1)
                {
                    ?>

                    <a href="modify.php?FILE_ID=<?=$FILE_ID?>&start=<?=$start?>"> <?=_("修改")?></a>
                    <a href="javascript:delete_file('<?=$FILE_ID?>','<?=$CUR_PAGE?>');"> <?=_("销毁")?></a>
                    <?
                }
                else
                    echo _("案卷已封");
                ?>
            </td>
        </tr>
        <?
    }
    ?>

        <tr class="TableControl">
            <td colspan="9">

                <b><?=_("文件操作：")?></b>
                <?=_("组卷至：")?><select name="ROLL_ID" id="SROLL_ID" onChange="change_roll();" class="SmallSelect">
                    <option value=""><?=_("请选择案卷")?></option>
                    <?
                    if($_SESSION["LOGIN_USER_PRIV"]=="1")
                    {
                        $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where RMS_ROLL.STATUS=0";
                    }
                    else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1')
                    {
                        $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
                        if($dept_id)
                        {
                            $dept_str = $dept_id;
                        }
                        else
                        {
                            $dept_str = $_SESSION["LOGIN_DEPT_ID"];
                        }
                        $UID = rtrim(GetUidByOther('','',$dept_str),",");
                        $user_id = rtrim(GetUserIDByUid($UID),",");
                        if($user_id != "") {
                            $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (find_in_set(RMS_ROLL.ADD_USER,'".$user_id."') or find_in_set(RMS_ROLL.MANAGER,'".$user_id."') or find_in_set(RMS_ROLL_ROOM.MANAGE_USER,'".$user_id."') AND RMS_ROLL.STATUS=0";
                        }else{
                            $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."') AND RMS_ROLL.STATUS=0";
                        }
                    }
                    else
                    {
                        $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."') AND RMS_ROLL.STATUS=0";
                    }
                    $cursor= exequery(TD::conn(),$query);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                        $ROLL_ID=$ROW["ROLL_ID"];
                        $ROLL_CODE=$ROW["ROLL_CODE"];
                        $ROLL_NAME=$ROW["ROLL_NAME"];
                        ?>
                        <option value=<?=$ROLL_ID?>><?=$ROLL_CODE?> - <?=$ROLL_NAME?></option>
                        <?
                    }
                    ?>
                </select>

                <input type="button"  value="<?=_("批量销毁")?>" class="SmallButton" onClick="delete_all()" title="<?=_("销毁已选中文件")?>">
                <input type="button"  value="<?=_("文件导入")?>" class="SmallButton" onClick="window.location='import_prepare.php'" title="<?=_("文件导入")?>">
            </td>
        </tr>
    </form>
</table>

</body>
</html>

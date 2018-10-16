<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("查看文件");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
jQuery(function(){
    jQuery("#allbox_for").click(function(){
        var checkVal = jQuery("#allbox_for").prop("checked");
        if(checkVal == 'checked' || checkVal == true)
        {
            jQuery("[name='file_select']").prop("checked",'true');
        }
        else
        {
            jQuery("[name='file_select']").removeAttr("checked");
        }
    })
    
    jQuery("[name='file_select']").click(function(){
        jQuery("#allbox_for").removeAttr("checked");
    })
});

function get_checked()
{
    checked_str="";
    
    jQuery("input[name='file_select']:checkbox").each(function(){ 
        if(jQuery(this).prop("checked")){
            checked_str += jQuery(this).val()+","
        }
    })
    
    return checked_str;
}

function open_file(FILE_ID,ISAUDIT)
{
    if (ISAUDIT==0)
    {
        URL="./read_file.php?FILE_ID="+FILE_ID;
    }
    else
    {
        URL="./read_file0.php?FILE_ID="+FILE_ID;
    }
    
    myleft=(screen.availWidth-500)/2;
    mytop=150
    mywidth=550;
    myheight=300;
    window.open(URL,"read_file","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function lend_file(FILE_ID,CUR_PAGE)
{
    msg='<?=_("确认要借阅该项文件吗？")?>';
    if(window.confirm(msg))
    {
        URL="./lend.php?ROLL_ID=<?=$ROLL_ID?>&FILE_ID=" + FILE_ID + "&CUR_PAGE=" + CUR_PAGE;
        window.location=URL;
    }
}

function lend_all()
{
    check_str="";
    check_str=get_checked();
    
    if(check_str=="")
    {
        alert("<?=_("要借阅文件，请至少选择其中一个。")?>");
        document.form1.reset();
        return;
    }
    
    msg='<?=_("确认要借阅已选中的文件吗？")?>';
    if(window.confirm(msg))
    {
        url="./lend_all.php?DELETE_STR="+ check_str +"&PAGE_START=<?=$PAGE_START?>&ROLL_ID=<?=$ROLL_ID?>";
        window.location=url;
    }
}

function order_by(field,asc_desc)
{
    window.location="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function change_roll()
{
    check_str="";
    check_str=get_checked();
    
    if(check_str=="")
    {
        alert("<?=_("要移卷文件，请至少选择其中一个。")?>");
        document.form1.reset();
        return;
    }
    
    roll_id=document.getElementsByName("ROLL_ID").value;
    url="../../roll_file/change_roll.php?OP=1&DELETE_STR="+ check_str +"&ROLL_ID="+roll_id+"&PAGE_START=<?=$PAGE_START?>&ROLL_ID0=<?=$ROLL_ID?>";
    location=url;
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("文件查询")?></span><br>
        </td>
    </tr>
</table>

<form action="roll_file.php"  method="post" name="form2">
    <table class="TableBlock" width="85%" align="center">
        <tr>
            <td nowrap class="TableData">
                <?=_("文件标题：")?>
                <input type="text" name="FILE_TITLE" value="<?=$FILE_TITLE?>">&nbsp;&nbsp;&nbsp;
                <?=_("发文单位：")?>
                <input type="text" name="SEND_UNIT" value="<?=$SEND_UNIT?>">&nbsp;&nbsp;&nbsp;
                <?=_("发文时间：")?>
                <input type="text" name="SEND_DATE1" size="10" maxlength="10" value="<?=$SEND_DATE1?>" onClick="WdatePicker()">&nbsp;
                <?=_("至")?>&nbsp;
                <input type="text" name="SEND_DATE2" size="10" maxlength="10" value="<?=$SEND_DATE2?>" onClick="WdatePicker()">&nbsp;&nbsp;
                <input type="hidden" name="ROLL_ID" value="<?=$ROLL_ID?>">
                <input type="hidden" name="CUR_PAGE" value="<?=$CUR_PAGE?>">
                <input type="submit" value="<?=_("查询")?>" class="BigButton">
            </td>
        </tr>
    </table>
</form>
<br>
<?
$s_where_str = '';
if($FILE_TITLE != '')
{
    $s_where_str .= " and FILE_TITLE like '%$FILE_TITLE%' ";
}
if($SEND_UNIT != '')
{
    $s_where_str .= " and SEND_UNIT like '%$SEND_UNIT%' ";
}
if($SEND_DATE1 != '')
{
    $s_where_str .= " and SEND_DATE>='$SEND_DATE1' ";
}
if($SEND_DATE2 != '')
{
    $s_where_str .= " and SEND_DATE<='$SEND_DATE2' ";
}

$query = "SELECT count(*) from RMS_FILE where ROLL_ID='$ROLL_ID' and DEL_USER=''".$s_where_str;
$cursor= exequery(TD::conn(),$query);
$RMS_FILE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
{
    $RMS_FILE_COUNT=$ROW[0];
}

if($RMS_FILE_COUNT==0)
{
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("查看文件")?></span>&nbsp;
            </td>
        </tr>
    </table>
    <br>
<?
    Message("",_("无文件"));
    exit;
}
 
$PER_PAGE=15;
$PAGES=10;
$PAGE_COUNT=ceil($RMS_FILE_COUNT/$PER_PAGE);

if($CUR_PAGE<=0 || $CUR_PAGE=="")
{
    $CUR_PAGE=1;
}
if($CUR_PAGE>$PAGE_COUNT)
{
    $CUR_PAGE=$PAGE_COUNT;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("查看文件")?></span>&nbsp;
        </td>
        <td align="right" valign="bottom" class="small1"><?=sprintf(_("共 %s 条"), '<span class="big4">'.$RMS_FILE_COUNT.'</span>')?>
        </td>
        <td align="right" valign="bottom" class="small1">
            <a class="A1" href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=1&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("首页")?></a>&nbsp;
            <a class="A1" href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$PAGE_COUNT?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("末页")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
{
    $J=$PAGES;
}
else
{
    $J=$CUR_PAGE%$PAGES;
}

if($CUR_PAGE> $PAGES)
{
?>
            <a class="A1" href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("上%s页"), $PAGES)?></a>&nbsp;&nbsp;
<?
}

for($I=$CUR_PAGE-$J+1;$I<=$CUR_PAGE-$J+$PAGES;$I++)
{
    if($I>$PAGE_COUNT)
    {
        break;
    }
    if($I==$CUR_PAGE)
    {
?>
        [<?=$I?>]&nbsp;
<?
    }
    else
    {
?>
        [<a class="A1" href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$I?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=$I?></a>]&nbsp;
<?
    }
}
?>
    &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
?>
            <a class="A1" href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$I?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("下%s页"), $PAGES)?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
            <a class="A1" href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE-1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("上一页")?></a>&nbsp;
<?
}
else
{
?>
            <?=_("上一页")?>&nbsp;
<?
}

if($CUR_PAGE+1<= $PAGE_COUNT)
{
?>
            <a class="A1" href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("下一页")?></a>&nbsp;
<?
}
else
{
?>
            <?=_("下一页")?>&nbsp;
<?
}
?>
            &nbsp;
        </td>
    </tr>
</table>
<?
if($ASC_DESC=="")
{
    $ASC_DESC="1";
}
if($FIELD=="")
{
    $FIELD="FILE_CODE";
}
 //============================ 显示已发布文件 =======================================
$query = "SELECT * from RMS_FILE where ROLL_ID='$ROLL_ID' and DEL_USER=''".$s_where_str;
$query .= " order by $FIELD";
if($ASC_DESC=="1")
{
    $query .= " desc";
}
else
{
    $query .= " asc";
}

if($ASC_DESC=="0")
{
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
}
else
{
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
}
?>
<br>
<table class="TableList" width="100%">
<form action="?"  method="post" name="form1">
    <tr class="TableHeader">
        <td nowrap align="center"><input type="checkbox" name="allbox" id="allbox_for"></td>
        <td nowrap align="center" onclick="order_by('FILE_CODE','<?if($FIELD=="FILE_CODE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("文件号")?></u><?if($FIELD=="FILE_CODE") echo $ORDER_IMG;?></td>
        <td nowrap align="center" onclick="order_by('FILE_TITLE','<?if($FIELD=="FILE_TITLE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("文件标题")?></u><?if($FIELD=="FILE_TITLE") echo $ORDER_IMG;?></td>
        <td nowrap align="center"><?=_("密级")?></td>
        <td nowrap align="center" onclick="order_by('SEND_UNIT','<?if($FIELD=="SEND_UNIT"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发文单位")?></u><?if($FIELD=="SEND_UNIT"||$FIELD=="") echo $ORDER_IMG;?></td>
        <td nowrap align="center" onclick="order_by('SEND_DATE','<?if($FIELD=="SEND_DATE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发文时间")?></u><?if($FIELD=="SEND_DATE") echo $ORDER_IMG;?></td>
        <td nowrap align="center"><?=_("紧急等级")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>
    
    <?
    $cursor= exequery(TD::conn(),$query);
    $RMS_FILE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $RMS_FILE_COUNT++;
        
        if($RMS_FILE_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
        {
            continue;
        }
        if($RMS_FILE_COUNT>$CUR_PAGE*$PER_PAGE)
        {
            break;
        }
        
        $FILE_ID=$ROW["FILE_ID"];
        $FILE_CODE=$ROW["FILE_CODE"];
        $FILE_TITLE=$ROW["FILE_TITLE"];
        $SECRET=$ROW["SECRET"];
        $SEND_UNIT=$ROW["SEND_UNIT"];
        $SEND_DATE=$ROW["SEND_DATE"];
        $URGENCY=$ROW["URGENCY"];
        $ISAUDIT=$ROW["ISAUDIT"];
        
        $FILE_TITLE=td_htmlspecialchars($FILE_TITLE);
        if($SEND_DATE=='0000-00-00') $SEND_DATE='';
        
        $SECRET=get_code_name($SECRET,"RMS_SECRET");
        $URGENCY=get_code_name($URGENCY,"RMS_URGENCY");
        
        if($RMS_FILE_COUNT%2==1)
        {
            $TableLine="TableLine1";
        }
        else
        {
            $TableLine="TableLine2";
        }
        ?>
        <tr class="<?=$TableLine?>">
            <td nowrap align="center"><input type="checkbox" name="file_select" value="<?=$FILE_ID?>"></td>
            <td align="center">
                <a href="javascript:open_file('<?=$FILE_ID?>','<?=$ISAUDIT?>');"><?=$FILE_CODE?></a>
            </td>
            <td nowrap align="center"><?=$FILE_TITLE?></td>
            <td nowrap align="center"><?=$SECRET?></td>
            <td nowrap align="center"><?=$SEND_UNIT?></td>
            <td nowrap align="center"><?=$SEND_DATE?></td>
            <td nowrap align="center"><?=$URGENCY?></td>
            <td nowrap align="center">
        <?
        if($ISAUDIT==1)
        {
        ?>
                <a href="javascript:lend_file('<?=$FILE_ID?>','<?=$CUR_PAGE?>');"> <?=_("借阅")?></a>
        <?
        }
        else
        {
        ?>
                <?=_("不需借阅")?>
        <?
        }
        ?>
            </td>
        </tr>
        <?
    }
    ?>
    
    <tr class="TableControl">
        <td colspan="9" align="center">
            <input type="button"  value="<?=_("批量借阅")?>" class="SmallButton" onClick="lend_all()" title="<?=_("借阅已选中文件")?>">
        </td>
    </tr>
</form>
</table>
</body>

</html>

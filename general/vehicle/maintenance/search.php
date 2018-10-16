<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("车辆维护记录管理");
include_once("inc/header.inc.php");
$para = '';
foreach($_GET as $k => $v)
{
    if($v!=''&&$k!='FIELD' && $k!='ASC_DESC')
    {
        $para.=$k."=".$v.",";
    }
}
$paras = rtrim($para,',');
$parameter = str_replace(',','&',$paras);
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
    jQuery(function(){
        jQuery("#allbox_for").click(function(){
            if(jQuery("#allbox_for").is(":checked"))
            {
                jQuery("[name='source_select']").attr("checked",'true');
            }
            else
            {
                jQuery("[name='source_select']").removeAttr("checked");
            }
        })

        jQuery("[name='source_select']").click(function(){
            jQuery("#allbox_for").removeAttr("checked");
        })
    });
    function delete_vehicle(VM_ID,PARA)
    {
        msg='<?=_("确认要删除该维护信息吗？")?>';
        if(window.confirm(msg))
        {
            URL='delete.php?VM_ID='+VM_ID+'&'+PARA;
            window.location=URL;
        }
    }
    function get_checked()
    {
        checked_str="";

        jQuery("input[name='source_select']:checkbox").each(function(){
            if(jQuery(this).attr("checked")){
                checked_str += jQuery(this).val()+","
            }
        })

        return checked_str;
    }
    function delete_item(PARA)
    {
        delete_str=get_checked();
        if(delete_str=="")
        {
            alert("<?=_("要删除维护信息，请至少选择其中一条。")?>");
            return;
        }
        msg='<?=_("确认要删除所选维护信息吗？")?>';
        if(window.confirm(msg))
        {
            url="delete.php?VM_ID="+ delete_str + '&delete_all=1' +'&'+PARA;
            location=url;
        }
    }
    function order_by(field,asc_desc,PARA)
    {
        window.location="search.php?FIELD="+field+"&ASC_DESC="+asc_desc+"&"+PARA;
    }

</script>


<body class="bodycolor">
<?
if($BEGIN_DATE!="")
{
    $TIME_OK=is_date($BEGIN_DATE);

    if(!$TIME_OK)
    { Message(_("错误"),_("维护日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($END_DATE!="")
{
    $TIME_OK=is_date($END_DATE);

    if(!$TIME_OK)
    { Message(_("错误"),_("维护日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($VM_FEE_MIN!=""&&!is_numeric($VM_FEE_MIN) || $VM_FEE_MAX!=""&&!is_numeric($VM_FEE_MAX))
{
    Message(_("错误"),_("维护费用应为数字！"));
    Button_Back();
    exit;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("维护记录管理")?></span>
        </td>
    </tr>
</table>

<?
$WHERE_STR="";
if($V_ID!="")
    $WHERE_STR.=" and V_ID='$V_ID'";
if($BEGIN_DATE!="")
    $WHERE_STR.=" and VM_REQUEST_DATE>='$BEGIN_DATE'";
if($END_DATE!="")
    $WHERE_STR.=" and VM_REQUEST_DATE<='$END_DATE'";
if($VM_TYPE!="")
    $WHERE_STR.=" and VM_TYPE='$VM_TYPE'";
if($VM_REASON!="")
    $WHERE_STR.=" and VM_REASON like '%$VM_REASON%'";
if($VM_PERSON!="")
    $WHERE_STR.=" and VM_PERSON like '%$VM_PERSON%'";
if($VM_FEE_MIN!="")
    $WHERE_STR.=" and VM_FEE>='$VM_FEE_MIN'";
if($VM_FEE_MAX!="")
    $WHERE_STR.=" and VM_FEE<='$VM_FEE_MAX'";
if($VM_REMARK!="")
    $WHERE_STR.=" and VM_REMARK like '%$VM_REMARK%'";

if($ASC_DESC=="")
    $ASC_DESC="1";
if($FIELD=="")
    $FIELD="VM_REQUEST_DATE";

$query = "SELECT * from VEHICLE_MAINTENANCE where 1=1".$WHERE_STR;
$query .= " order by $FIELD";
if($ASC_DESC=="1")
    $query .= " desc";
else
    $query .= " asc";

if($ASC_DESC=="0")
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";

$cursor= exequery(TD::conn(),$query);

if($OPERATION == "export")
{
    $EXCEL_OUT=array(_("车牌号"),_("维护类型"),_("维护原因"),_("维护日期"),_("经办人"),_("维护费用"),_("备注"));

    ob_end_clean();
    require_once ('inc/ExcelWriter.php');

    $objExcel = new ExcelWriter();
    $objExcel->setFileName(_("车辆维护记录"));
    $objExcel->addHead($EXCEL_OUT);

    while($ROW=mysql_fetch_array($cursor))
    {
        $VM_ID=$ROW["VM_ID"];
        $V_ID=$ROW["V_ID"];
        $VM_REQUEST_DATE=$ROW["VM_REQUEST_DATE"];
        $VM_TYPE=$ROW["VM_TYPE"];
        $VM_REASON=$ROW["VM_REASON"];
        $VM_FEE=$ROW["VM_FEE"];
        $VM_PERSON=$ROW["VM_PERSON"];
        $VM_REMARK =$ROW["VM_REMARK"];

        if($VM_REQUEST_DATE=="0000-00-00")
            $VM_REQUEST_DATE="";

        $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
        $cursor1= exequery(TD::conn(),$query);
        if($ROW1=mysql_fetch_array($cursor1))
            $V_NUM=$ROW1["V_NUM"];

        $query2="select CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='VEHICLE_REPAIR_TYPE' and CODE_NO ='$VM_TYPE'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
        {
            $VM_TYPE_DESC=$ROW2["CODE_NAME"];
            $CODE_EXT=unserialize($ROW["CODE_EXT"]);
            if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
                $VM_TYPE_DESC = $CODE_EXT[MYOA_LANG_COOKIE];
        }

        $EXCEL_OUT=$V_NUM.",".$VM_TYPE_DESC.",".str_replace(",",_("，"),preg_replace("/\s+/", " ", $VM_REASON)).",".$VM_REQUEST_DATE.",".$VM_PERSON.",".$VM_FEE.",".preg_replace("/\s+/", " ", $VM_REMARK);
        $objExcel->addRow($EXCEL_OUT);
    }

    $objExcel->Save();
    exit;
}

$VM_COUNT=0;
$VM_FEE_SUM=0;
while($ROW=mysql_fetch_array($cursor))
{
    $VM_COUNT++;
    $VM_ID=$ROW["VM_ID"];
    $V_ID=$ROW["V_ID"];
    $VM_REQUEST_DATE=$ROW["VM_REQUEST_DATE"];
    $VM_TYPE=$ROW["VM_TYPE"];
    $VM_REASON=$ROW["VM_REASON"];
    $VM_FEE=$ROW["VM_FEE"];
    $VM_PERSON=$ROW["VM_PERSON"];
    $VM_REMARK =$ROW["VM_REMARK"];

    $VM_FEE_SUM+=$VM_FEE;

    if($VM_REQUEST_DATE=="0000-00-00")
    $VM_REQUEST_DATE="";

    $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW1=mysql_fetch_array($cursor1))
    $V_NUM=$ROW1["V_NUM"];

    $query2="select CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='VEHICLE_REPAIR_TYPE' and CODE_NO ='$VM_TYPE'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
    $VM_TYPE_DESC=$ROW2["CODE_NAME"];
    $CODE_EXT=unserialize($ROW["CODE_EXT"]);
    if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
    $VM_TYPE_DESC = $CODE_EXT[MYOA_LANG_COOKIE];
    }

    if($VM_COUNT==1)
    {
    ?>
    <table class="TableList" width="95%" align="center">
        <tr class="TableHeader">
            <td nowrap align="center"><?=_("选择")?></td>
            <td nowrap align="center"><?=_("车牌号")?></td>
            <td nowrap align="center"><?=_("维护类型")?></td>
            <td nowrap align="center"><?=_("维护原因")?></td>
            <td nowrap align="center" onclick="order_by('VM_REQUEST_DATE','<?if($FIELD=="VM_REQUEST_DATE"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>','<?=$parameter?>');" style="cursor:hand;" width="30%"><u><?=_("维护日期")?></u><?if($FIELD=="VM_REQUEST_DATE"||$FIELD=="") echo $ORDER_IMG;?></td>
            <td nowrap align="center"><?=_("经办人")?></td>
            <td nowrap align="center"><?=_("维护费用")?></td>
            <td nowrap align="center"><?=_("备注")?></td>
            <td nowrap align="center"><?=_("操作")?></td>
        </tr>
        <?
        }
        ?>
        <tr class="TableData">
            <td nowrap  width=40><input type="checkbox" name="source_select" id="source_select" value="<?=$VM_ID?>"></td>
            <td nowrap align="center"><?=$V_NUM?></td>
            <td nowrap align="center"><?=$VM_TYPE_DESC?></td>
            <td nowrap align="left" title="<?=$VM_REASON?>"><?=csubstr($VM_REASON,0,10).(strlen($VM_REASON)>10?"...":"")?></td>
            <td nowrap align="center"><?=$VM_REQUEST_DATE?></td>
            <td nowrap align="center"><?=$VM_PERSON?></td>
            <td nowrap align="right"><?=$VM_FEE?></td>
            <td nowrap align="left" title="<?=$VM_REMARK?>"><?=csubstr($VM_REMARK,0,10).(strlen($VM_REMARK)>10?"...":"")?></td>
            <td nowrap align="center">
                <?
                $query1 = "select OPERATOR_NAME from VEHICLE_OPERATOR where OPERATOR_ID=1";
                $cursor1=exequery(TD::conn(),$query1);
                if($ROW=mysql_fetch_array($cursor1))
                    $MANAGERS=$ROW["OPERATOR_NAME"];
                if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($MANAGERS,$_SESSION["LOGIN_USER_ID"]))
                {
                    ?>
                    <a href="new.php?VM_ID=<?=$VM_ID?>"><?=_("修改")?></a>&nbsp;
                    <a href=javascript:delete_vehicle("<?=$VM_ID?>","<?=$parameter?>");><?=_("删除")?></a>
                    <?
                }else echo "-";
                ?>
            </td>
        </tr>
        <?
        }
        if($VM_COUNT>0)
        {
        ?>
        <tr class="TableControl">
            <td colspan = "10"><input type="checkbox" name="allbox" id="allbox_for"><label for="allbox_for"><?=("全选")?></label> &nbsp;
                <a href="javascript:delete_item('<?=$parameter?>');" title="<?=_("删除所选记录")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除所选记录")?></a>&nbsp;</td>
        </tr>
        <tr class="TableControl">
            <td nowrap align="center"><b><?=_("合计：")?></b></td>
            <td nowrap align="center" colspan="5"></td>
            <td nowrap align="right"><?=$VM_FEE_SUM?></td>
            <td nowrap align="center" colspan="2">
            </td>
        </tr>
    </table>
<?
}
else
    Message("",_("暂无车辆维护信息"));
?>
<br>
<br>
<div align="center">
    <input type="button" class="BigButton" value="<?=_("打印")?>" onclick="window.print();">&nbsp;&nbsp;
    <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php';" title="<?=_("返回")?>">
</div>
</body>

</html>

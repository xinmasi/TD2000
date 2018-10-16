<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("证照信息查询");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
    function delete_welfare(WELFARE_ID)
    {
        msg='<?=_("确认要删除该项员工福利信息吗？")?>';
        if(window.confirm(msg))
        {
            URL="delete.php?WELFARE_ID=" + WELFARE_ID+"&PAGE_START=<?=$PAGE_START?>";
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
            alert("<?=_("要删除员工福利信息，请至少选择其中一条。")?>");
            return;
        }

        msg='<?=_("确认要删除该项员工信息吗？")?>';
        if(window.confirm(msg))
        {
            url="delete.php?WELFARE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
            location=url;
        }
    }
</script>


<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());
//-----------合法性校验---------

if($PAYMENT_DATE1!="")
{
    $TIME_OK=is_date($PAYMENT_DATE1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $PAYMENT_DATE1=$PAYMENT_DATE1." 00:00:00";
}

if($PAYMENT_DATE2!="")
{
    $TIME_OK=is_date($PAYMENT_DATE2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $PAYMENT_DATE2=$PAYMENT_DATE2." 23:59:59";
}
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($STAFF_NAME!="")
    $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
if($WELFARE_ITEM!="")
    $CONDITION_STR.=" and WELFARE_ITEM='$WELFARE_ITEM'";
if($TAX_AFFAIRS!="")
    $CONDITION_STR.=" and TAX_AFFAIRS='$TAX_AFFAIRS'";
if($WELFARE_MONTH!="")
    $CONDITION_STR.=" and WELFARE_MONTH like '%".$WELFARE_MONTH."%'";
if($WELFARE_PAYMENT!="")
    $CONDITION_STR.=" and WELFARE_PAYMENT = '$WELFARE_PAYMENT'";
if($FREE_GIFT!="")
    $CONDITION_STR.=" and FREE_GIFT like '%".$FREE_GIFT."%'";
if($PAYMENT_DATE1!="")
    $CONDITION_STR.=" and PAYMENT_DATE>='$PAYMENT_DATE1'";
if($PAYMENT_DATE2!="")
    $CONDITION_STR.=" and PAYMENT_DATE<='$PAYMENT_DATE2'";

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD="NAME,WELFARE_ITEM,PAYMENT_DATE,WELFARE_MONTH,WELFARE_PAYMENT,TAX_AFFAIRS,FREE_GIFT,MEMO";
else
    $OUTPUT_HEAD=array(_("单位员工"),_("福利项目"),_("发放日期"),_("工资月份"),_("福利金额"),_("是否纳税"),_("发放物品"),_("备注"));

?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"><?=_("员工福利查询结果")?></span><br>
        </td>
    </tr>
</table>
<?

require_once ('inc/ExcelWriter.php');
if($func=="2")
{
    $objExcel = new ExcelWriter();
    $objExcel->setFileName(_("员工福利信息"));
    if(MYOA_IS_UN == 1)
        $OUTPUT_HEAD="NAME,WELFARE_ITEM,PAYMENT_DATE,WELFARE_MONTH,WELFARE_PAYMENT,TAX_AFFAIRS,FREE_GIFT,MEMO";
    else
        $OUTPUT_HEAD=array(_("单位员工"),_("福利项目"),_("发放日期"),_("工资月份"),_("福利金额"),_("是否纳税"),_("发放物品"),_("备注"));
    $objExcel->addHead($OUTPUT_HEAD);
}
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from  HR_WELFARE_MANAGE where".$CONDITION_STR."order by PAYMENT_DATE desc";
$cursor= exequery(TD::conn(),$query);
$WELFARE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $WELFARE_COUNT++;

    $WELFARE_ID=format_cvs($ROW["WELFARE_ID"]);
    $CREATE_USER_ID=format_cvs($ROW["CREATE_USER_ID"]);
    $CREATE_DEPT_ID=format_cvs($ROW["CREATE_DEPT_ID"]);
    $STAFF_NAME=$ROW["STAFF_NAME"];
    $WELFARE_MONTH=format_cvs($ROW["WELFARE_MONTH"]);
    $PAYMENT_DATE=format_cvs($ROW["PAYMENT_DATE"]);
    $WELFARE_ITEM=$ROW["WELFARE_ITEM"];
    $WELFARE_PAYMENT=format_cvs($ROW["WELFARE_PAYMENT"]);
    $ADD_TIME=format_cvs($ROW["ADD_TIME"]);
    $TAX_AFFAIRS=$ROW["TAX_AFFAIRS"];
    $REMARK = format_cvs($ROW["REMARK"]);

    if($TAX_AFFAIRS!="")
        $TAX_AFFAIRS = format_cvs($TAX_AFFAIRS==1 ? _("是"):_("否"));

    $FREE_GIFT = format_cvs($ROW["FREE_GIFT"]);


    $STAFF_NAME1=format_cvs(substr(GetUserNameById($STAFF_NAME),0,-1));

    $WELFARE_ITEM=format_cvs(get_hrms_code_name($WELFARE_ITEM,"HR_WELFARE_MANAGE"));
    if($WELFARE_COUNT==1)
    {
    ?>
    <table class="TableList" width="100%">
        <thead class="TableHeader">
        <td nowrap align="center"><?=_("选择")?></td>
        <td nowrap align="center"><?=_("单位员工")?></td>
        <td nowrap align="center"><?=_("福利项目")?></td>
        <td nowrap align="center"><?=_("工资月份")?></td>
        <td nowrap align="center"><?=_("发放日期")?></td>
        <td nowrap align="center"><?=_("福利金额")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
        </thead>

        <?
        }
        if($func=="2")
        {
            $ROW_OUT=$STAFF_NAME1.",".$WELFARE_ITEM.",".$PAYMENT_DATE.",".$WELFARE_MONTH.",".$WELFARE_PAYMENT.",".$TAX_AFFAIRS.",".str_replace(",",_("，"),preg_replace("/\s+/", " ", $FREE_GIFT)).",".str_replace(",",_("，"),preg_replace("/\s+/", " ", $REMARK))."\n";
            $objExcel->addRow($ROW_OUT);
        }
        ?>
        <tr class="TableData">
            <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$WELFARE_ID?>" onClick="check_one(self);">
            <td nowrap align="center"><?=$STAFF_NAME1?></td>
            <td nowrap align="center"><?=$WELFARE_ITEM?></td>
            <td nowrap align="center"><?=$WELFARE_MONTH?></td>
            <td nowrap align="center"><?=$PAYMENT_DATE=="0000-00-00"?"":$PAYMENT_DATE;?></td>
            <td nowrap align="center"><?=$WELFARE_PAYMENT?></td>
            <td nowrap align="center">
                <a href="javascript:;" onClick="window.open('welfare_detail.php?WELFARE_ID=<?=$WELFARE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
                <a href="modify.php?WELFARE_ID=<?=$WELFARE_ID?>"> <?=_("修改")?></a>&nbsp;
                <a href="javascript:delete_welfare(<?=$WELFARE_ID?>);"> <?=_("删除")?></a>&nbsp;
            </td>
        </tr>
        <?
        }

        if($WELFARE_COUNT==0)
        {
            Message("",_("无符合条件的员工福利信息！"));
            Button_Back();
            exit;
        }
        else
        {
        ?>
        <tr class="TableControl">
            <td colspan="19">
                <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
                <a href="javascript:delete_mail();" title="<?=_("删除所选员工福利信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp;
            </td>
        </tr>
    </table>

    <div align="center">
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php';" title="<?=_("返回")?>">
    </div>
<?
}
?>
</body>

</html>
<?
if($func=="2")
{
    ob_end_clean();
    $objExcel->Save();
}
?>
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$PARA_ARRAY = get_sys_para("SMS_REMIND");
$PARA_VALUE=$PARA_ARRAY["SMS_REMIND"];
$REMIND_ARRAY = explode("|", $PARA_VALUE);
$SMS_REMIND = $REMIND_ARRAY[0];
$SMS2_REMIND = $REMIND_ARRAY[1];
$SMS3_REMIND = $REMIND_ARRAY[2];

$HTML_PAGE_TITLE = _("添加油卡信息");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<!--<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>-->
<script Language="JavaScript"> 
    function CheckForm() 
    {
        var oc_id = jQuery('input[name="OC_ID"]').val();
        
        if(jQuery.trim(oc_id) == "")
        {
            alert("<?=_("油卡编号不能为空！")?>"); 
            jQuery('input[name="OC_ID"]').focus();
            return false; 
        }
        
        form1.submit();
    }

    function backFill()
    {
        var url = '/general/vehicle/manage/getvehicleinfo.php';
        var v_num = jQuery('select[name="V_NUM"]').val();
        
        jQuery('input[name="V_ID"]').val("");
        jQuery('input[name="V_DEPT"]').val("");
        jQuery('input[name="V_ONWER"]').val("");
        jQuery('input[name="V_TYPE"]').val("");
        jQuery('input[name="V_USER"]').val("");

        jQuery.ajax
        ({
            url: url,
            type: "POST",
            data: {"V_NUM":v_num},
            success: function(data)
            {    
                var v_onwer = data.v_onwer;
                var v_user = data.v_caruser;
                
                // v_onwer = v_onwer.substr(0, v_onwer.length-1);
                // v_user = v_user.substr(0, v_user.length-1);

                jQuery('input[name="V_ID"]').val(data.v_id);
                jQuery('input[name="V_TYPE"]').val(data.v_type);
                jQuery('input[name="V_DEPT"]').val(data.v_depart);
                jQuery('input[name="V_ONWER"]').val(v_onwer);
                jQuery('input[name="V_USER"]').val(v_user);
            },
        })
    }
    
</script>

<body class="bodycolor">

<?
if($ID!="")
{
    $query = "SELECT * FROM vehicle_oil_card where ID='$ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $OC_ID = $ROW["OC_ID"];
        $OC_DATE = $ROW["OC_DATE"];
        $OC_HANDLED = $ROW["OC_HANDLED"];
        $OC_COMPANY = $ROW["OC_COMPANY"];
        $OC_PASSWORD = $ROW["OC_PASSWORD"];
        $OC_STATUS = $ROW["OC_STATUS"];
        $V_ID = $ROW["V_ID"];
        $V_DEPT = $ROW["V_DEPT"];
        $V_NUM = $ROW["V_NUM"];
        $V_TYPE = $ROW["V_TYPE"];
        $V_ONWER = $ROW["V_ONWER"];
        $V_USER = $ROW["V_USER"];
        
        $OC_DATE = ($OC_DATE=='0000-00-00') ? '' : $OC_DATE;
    }
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" HEIGHT="20"><span class="big3"> <?=_("油卡信息")?></span>
        </td>
    </tr>
</table>
<form enctype="multipart/form-data" action="addoilcard.php" method="post" name="form1">
<table class="TableBlock" align="center" width="50%" id="tableFields">
    <tr>
        <td nowrap class="TableData" width="80"> <?=_("油卡编号：")?></td>
        <td class="TableData">
            <input type="text" name="OC_ID" size="20" maxlength="100" class="BigInput" value="<?=$OC_ID?>">
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("发卡日期：")?></td>
        <td class="TableData">
            <input type="text" name="OC_DATE" size="12" maxlength="10" class="BigInput" value="<?=$OC_DATE?>" onClick="WdatePicker()">
        </td>
    </tr>
   
    <tr>
        <td nowrap class="TableData"> <?=_("经手人：")?></td>
        <td class="TableData">
            <div id="handled">
                <input type='hidden' class='handled' name='RECORDER_HANDLED' value=''>
                <input type='text' name='OC_HANDLED' id='OC_HANDLED'  size='10'  value = '<?=_($OC_HANDLED)?>' class='BigInput' maxlength='20' title="<?=_('经手人')?>">
                <a href = "javascript:viod(0);" onClick ="SelectUserSingle('90','','RECORDER_HANDLED','OC_HANDLED')"><?=_('选择')?></a>
                <a href = "javascript:viod(0);" onClick ="ClearUser('RECORDER_HANDLED','OC_HANDLED')"><?=_('清空')?></a>
            </div>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("油卡发行单位：")?></td>
        <td class="TableData">
            <input type="text" name="OC_COMPANY" size="20" maxlength="100" class="BigInput" value="<?=$OC_COMPANY?>">
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("油卡密码：")?></td>
        <td class="TableData">
            <input type="text" name="OC_PASSWORD" size="20" maxlength="100" class="BigInput" value="<?=$OC_PASSWORD?>">
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("使用状态：")?></td>
        <td class="TableData">        
            <select name="OC_STATUS" class="BigSelect">
                <option value=""></option>
                <?=code_list("VEHICLE_OIL_STATUS",$OC_STATUS)?>
            </select>&nbsp;<?=_("车辆类型可在系统管理->“系统代码设置”模块设置")?>
        </td>
    </tr>

    <tr>
        <td nowrap class="TableData"> <?=_("车牌号：")?></td>
        <td class="TableData">        
            <select name="V_NUM" class="BigSelect" onChange="backFill()">
                <option value=""></option>
                <?
                    $query = "SELECT V_NUM FROM vehicle WHERE 1=1";
                    $cursor = exequery(TD::conn(),$query);
                    
                    while($ROW = mysql_fetch_array($cursor))
                    {
                        $isChecked = ($ROW["V_NUM"] == $V_NUM) ? "selected" : "";
                        echo '<option value="'.$ROW["V_NUM"].'" '.$isChecked.'>'.$ROW["V_NUM"].'</option>';
                    }
                ?>
            </select>
        </td>
    </tr>
    
    <input type="hidden" name="V_ID" value="<?=$V_ID?>">
    
    <tr>
        <td nowrap class="TableData"> <?=_("保管部门：")?></td>
        <td class="TableData">
            <input type="text" name="V_DEPT" class="BigStatic" size="20" maxlength="100" class="BigInput" value="<?=$V_DEPT?>" readonly>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("车辆类型：")?></td>
        <td class="TableData">
            <input type="text" name="V_TYPE" class="BigStatic" size="10" maxlength="100" class="BigInput" value="<?=$V_TYPE?>" readonly>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("车辆所有人：")?></td>
        <td class="TableData">
            <input type="text" name="V_ONWER" class="BigStatic" size="10" maxlength="100" class="BigInput" value="<?=$V_ONWER?>" readonly>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("长期使用人：")?></td>
        <td class="TableData">
            <input type="text" name="V_USER" class="BigStatic" size="10" maxlength="100" class="BigInput" value="<?=$V_USER?>" readonly>
        </td>
    </tr>
    
    <tr class="TableControl">
        <td nowrap colspan="2" align="center">
            <input type="hidden" value="<?=$ID?>" name="ID">
            <input type="button" value="<?=_("保存")?>" class="BigButton" onclick="CheckForm();">&nbsp;&nbsp;
            <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
<?
if($ID!="")
{
?>
            <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='oilcard.php'">
<?
}
?>
        </td>
    </tr>
</table>
</form>

</body>
</html>
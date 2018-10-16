<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("员工福利查询");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet"type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>	
<script src="/module/DatePicker/WdatePicker.js"></script>
<script> 
jQuery(document).ready(function(){      
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});  
</script>
<script Language="JavaScript">
function CheckForm(str)
{
   
 if(str=='1')
    document.form1.func.value="1";
 else
    document.form1.func.value="2";
    
 document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"><?=_("员工福利查询")?></span></td>
  </tr>
</table>
<br>	

<form enctype="multipart/form-data" action="search.php"  method="post" name="form1" id="form1" >
 <table class="TableBlock" width="450" align="center">
  <tr>
    <td nowrap class="TableData"><?=_("单位员工：")?></td>
    <td class="TableData">
      <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="">&nbsp;
      <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1')"><?=_("选择")?></a>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("福利项目：")?></td>
    <td class="TableData">
      <select name="WELFARE_ITEM" style="background: white;" title="<?=_("福利项目可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
        <option value=""><?=_("福利项目")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
        <?=hrms_code_list("HR_WELFARE_MANAGE","")?>
      </select>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("工资月份：")?></td>
    <td class="TableData">
      <input name="WELFARE_MONTH" size="15" class="BigInput" value="<?=$CUR_MONTH?>">
    </td>
  </tr>
  <tr>
   <td nowrap class="TableData"><?=_("福利金额：")?></td>
    <td class="TableData">
      <input type="text"name="WELFARE_PAYMENT" class="BigInput validate[custom[money]]" data-prompt-position="centerRight:20,-6" size="15" value="">&nbsp;<?=_("元")?>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("是否纳税：")?></td>
    <td class="TableData">
       <select name="TAX_AFFAIRS" style="background: white;" title="">
          <option value="" ><?=_("是否纳税")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
          <option value="0"><?=_("否")?></option>
          <option value="1"><?=_("是")?></option>
        </select>
    </td> 
  </tr>
  <tr>
    <td nowrap class="TableData"> <?=_("发放日期：")?></td>
    <td class="TableData">
      <input type="text" name="PAYMENT_DATE1" size="10" maxlength="10" class="BigInput" value="<?=$PAYMENT_DATE1?>" id="start_time" onClick="WdatePicker()"/>
      <?=_("至")?>
      <input type="text" name="PAYMENT_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$PAYMENT_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>   
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("发放物品：")?></td>
    <td class="TableData">
      <input name="FREE_GIFT" size="25" class="BigInput" value="<?=$FREE_GIFT?>">
    </td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="2" nowrap>
    	<input type="hidden" name="func" value="">
      <input type="button" value="<?=_("查询")?>" class="BigButton" onclick="CheckForm(1)">&nbsp;&nbsp;
      <input type="button" value="<?=_("导出")?>" class="BigButton" onclick="CheckForm(2)"> 
    </td>
  </tr>
</form>
</table>
</body>	
</html>
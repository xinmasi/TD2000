<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("维护记录管理");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<body class="bodycolor">
<br>
<table class="TableBlock" width="500"align="center">
  <form enctype="multipart/form-data" action="search.php"  method="get" name="form1">
    <tr>
      <td colspan="2" nowrap class="TableHeader">
      <img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> <?=_("请指定查询条件：")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("车牌号：")?></td>
      <td class="TableData" width="470">
        <select name="V_ID" class="BigSelect">
          <option value=""></option>
<?
$query = "SELECT * from VEHICLE order by V_NUM";
$cursor1= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor1))
{
   $V_ID1=$ROW1["V_ID"];
   $V_NUM=$ROW1["V_NUM"];
?>
          <option value="<?=$V_ID1?>"><?=$V_NUM?></option>
<?
}
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("维护日期：")?></td>
      <td class="TableData">
        <input type="text" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
 <?=_("至")?>
        <input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
   
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("维护类型：")?></td>
      <td class="TableData">
        <SELECT name="VM_TYPE"  class="BigSelect">
          <option value=""></option>
          <?=code_list("VEHICLE_REPAIR_TYPE",$VM_TYPE)?>
        </SELECT>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("维护原因：")?></td>
      <td class="TableData">
        <input type="text" name="VM_REASON" size="30" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("经办人：")?></td>
      <td class="TableData">
          <input type="hidden" name="VM_PERSON_ID" value="<?if($VM_PERSON_ID!=0)echo $VM_PERSON_ID;?>">
          <input type="text" name="VM_PERSON" size="10" maxlength="200" class="BigInput" value="" readonly>
          <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','VM_PERSON_ID','VM_PERSON','vehicle')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("维护费用：")?></td>
      <td class="TableData">
        <input type="text" name="VM_FEE_MIN" size="10" maxlength="200" class="BigInput" value=""> <?=_("至")?>
        <input type="text" name="VM_FEE_MAX" size="10" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("备注：")?></td>
      <td class="TableData">
        <input type="text" name="VM_REMARK" size="30" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("操作：")?></td>
      <td class="TableData">
        <input type="radio" name="OPERATION" id="OPERATION_1" value="search" checked><label for="OPERATION_1"><?=_("查询")?></label>&nbsp;&nbsp;
        <input type="radio" name="OPERATION" id="OPERATION_2" value="export"><label for="OPERATION_2"><?=_("导出")?></label>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>

</body>
</html>
<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("车辆申请查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor">
<br>
<table class="TableTop" width="500" align="center">
  <form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
    <tr>
	  <td class="left"></td>
      <td class="center"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> <?=_("请指定查询条件：")?></td>
      <td class="right"></td>
    </tr>
	</table>
	<table width="500" align="center" class="TableBlock">
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("状态：")?></td>
      <td class="TableData">
        <select name="VU_STATUS" class="BigSelect">
          <option value=""></option>
          <option value="0"><?=_("待批")?></option>
          <option value="1"><?=_("已准")?></option>
          <option value="2"><?=_("使用中")?></option>
          <option value="4"><?=_("结束")?></option>
          <option value="3"><?=_("未准")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("车牌号：")?></td>
      <td class="TableData">
        <select name="V_ID" class="BigSelect" size>1>
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
      <td nowrap class="TableData" width="80"> <?=_("司机：")?></td>
      <td class="TableData"><input type="text" name="VU_DRIVER" size="11" maxlength="100" class="BigInput"></td>
    </tr>    
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("申请日期：")?></td>
      <td class="TableData">
        <input type="text" name="VU_REQUEST_DATE_MIN" size="12" maxlength="10" class="BigInput" value="" 
onClick="WdatePicker()">
      <?=_("至")?>
        <input type="text" name="VU_REQUEST_DATE_MAX" size="12" maxlength="10" class="BigInput" value="" 
onClick="WdatePicker()">
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("用车人：")?></td>
       <td class="TableData">
        <input type="hidden" name="VU_USER" value="<?if ($FLAG=="1") {if($TO_ID!=0)echo $TO_ID;else echo $_SESSION["LOGIN_USER_ID"];} else echo $VU_USER_ID?>"> 	
        <input type="text" name="VU_NAME" size="13"  value="<?if ($FLAG=="1"){ if($TO_ID!=0)echo $TO_NAME;else echo td_trim($OUT_NAME);}else echo $VU_USER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','VU_USER','VU_NAME','vehicle')"><?=_("选择")?></a>
      </td>
    
     <!--<td class="TableData">
        <input type="text" name="VU_USER" size="20" maxlength="100" class="BigInput" value="">
      </td>
    </tr>-->
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("用车部门：")?></td>
      <td class="TableData">
        <input type="hidden" name="VU_DEPT" value="">
        <input type="text" name="VU_DEPT_FIELD_DESC" value="" class=BigStatic size=20 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','VU_DEPT','VU_DEPT_FIELD_DESC')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("起始日期：")?></td>
      <td class="TableData">
        <input type="text" name="VU_START_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
        <?=_("至")?>
        <input type="text" name="VU_START_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("结束日期：")?></td>
      <td class="TableData">
        <input type="text" name="VU_END_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
      <?=_("至")?>
        <input type="text" name="VU_END_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
     
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("申请人：")?></td>
      <td class="TableData">
        <input type="text" name="TO_NAME" size="10" class="BigStatic" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','TO_ID', 'TO_NAME')"><?=_("选择")?></a>
        <input type="hidden" name="TO_ID" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("调度员：")?></td>
      <td class="TableData">
        <select name="VU_OPERATOR" class="BigSelect">
          <option value=""></option>
<?
$query = "SELECT * from VEHICLE_OPERATOR where OPERATOR_ID=1";
$cursor1= exequery(TD::conn(),$query);
if($ROW1=mysql_fetch_array($cursor1))
{
   $OPERATOR_NAME=$ROW1["OPERATOR_NAME"];
   $query = "SELECT * from USER where USER_ID!='' and find_in_set(USER_ID,'$OPERATOR_NAME') order by USER_NO,USER_NAME";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
?>
          <option value="<?=$USER_ID?>"><?=$USER_NAME?></option>
<?
   }
}
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("事由：")?></td>
      <td class="TableData">
        <input type="text" name="VU_REASON" class="BigInput" size="30">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"> <?=_("备注：")?></td>
      <td class="TableData">
        <input type="text" name="VU_REMARK" class="BigInput" size="30">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton" onclick="action='search.php';">&nbsp;&nbsp;
        <input type="submit" value="<?=_("导出")?>" class="BigButton" onclick="action='export.php';">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>

</body>
</html>
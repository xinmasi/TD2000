<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("查岗信息查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("查岗信息查询")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("缺岗人：")?></td>
      <td class="TableData">
        <textarea cols=35 name="CHECK_USER_NAME" rows=2 class="BigStatic" readonly value="<?=substr(GetUserNameById($CHECK_USER_ID),0,-1)?>"></textarea>&nbsp;
        <input type="hidden" name="CHECK_USER_ID" value="<?=$CHECK_USER_ID?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('7','','CHECK_USER_ID', 'CHECK_USER_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("查岗人：")?></td>
      <td class="TableData">
        <textarea cols=35 name="CHECK_MANAGER_NAME" rows=2 class="BigStatic" readonly value="<?=substr(GetUserNameById($CHECK_DUTY_MANAGER),0,-1)?>"></textarea>&nbsp;
        <input type="hidden" name="CHECK_DUTY_MANAGER" value="<?=$CHECK_DUTY_MANAGER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('7','','CHECK_DUTY_MANAGER', 'CHECK_MANAGER_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("查岗时间：")?></td>
      <td class="TableData">
        <input type="text" id="start_time" name="CHECK_DUTY_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$CHECK_DUTY_TIME1?>" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'END_TIME\')}',dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        <?=_("至")?>
        <input type="text" name="CHECK_DUTY_TIME2" size="10" id="END_TIME" maxlength="10" class="BigInput" value="<?=$CHECK_DUTY_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}',dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>    
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("缺岗人说明时间：")?></td>
      <td class="TableData">
        <input type="text" id="start_time2" name="RECORD_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$RECORD_TIME1?>" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'END_TIME2\')})"/>
        <?=_("至")?>
        <input type="text" name="RECORD_TIME2" id="END_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$RECORD_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time2\')}',maxDate:'2020-10-01'})"/>      
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("说明情况：")?></td>
    	<td class="TableData">
        <select name="NOEXP" class="BigSelect">
          <option value="0" selected><?=_("全部")?></option>
          <option value="1"><?=_("未做说明")?></option>
          <option value="2"><?=_("已做说明")?></option>
        </select>
    	</td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </form>
 </table>


</body>
</html>
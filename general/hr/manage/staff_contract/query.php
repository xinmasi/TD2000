<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("合同信息查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor" topmargin="5">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("合同信息查询")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1" >
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("单位员工：")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="13" class="BigStatic" value="">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("合同编号：")?></td>
      <td class="TableData">
        <INPUT type="text"name="STAFF_CONTRACT_NO" class=BigInput size="13" value="<?=$STAFF_CONTRACT_NO?>">
      </td>
   </tr>
   <tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("合同类型：")?></td>
      <td class="TableData">
        <select name="CONTRACT_TYPE" style="background: white;" title="<?=_("合同类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("合同类型")?>&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_CONTRACT1","")?>
        </select>
      </td>
   </tr>
    
    <tr>
      <td nowrap class="TableData"> <?=_("合同签订日期：")?></td>
      <td class="TableData">
        <input type="text" id="start_time1" name="MAKE_CONTRACT1" size="10" maxlength="10" class="BigInput" value="<?=$MAKE_CONTRACT1?>" onClick="WdatePicker()"/>
        <?=_("至")?>
        <input type="text" name="MAKE_CONTRACT2" size="10" maxlength="10" class="BigInput" value="<?=$MAKE_CONTRACT2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time1\')}'})"/>       
      </td>
    </tr>
        <tr>
      <td nowrap class="TableData"> <?=_("试用到期日期：")?></td>
      <td class="TableData">
        <input type="text" id="start_time2" name="TRAIL_OVER_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$TRAIL_OVER_TIME1?>" onClick="WdatePicker()"/> 
        <?=_("至")?>
        <input type="text" name="TRAIL_OVER_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$TRAIL_OVER_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time2\')}'})"/> 
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("合同到期日期：")?></td>
      <td class="TableData">
        <input type="text" id="start_time3" name="CONTRACT_END_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$CONTRACT_END_TIME1?>" onClick="WdatePicker()"/> 
        <?=_("至")?>
        <input type="text" name="CONTRACT_END_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$CONTRACT_END_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time3\')}'})"/>     
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("合同解除日期：")?></td>
      <td class="TableData">
        <input type="text"id="start_time4" name="CONTRACT_REMOVE_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$CONTRACT_REMOVE_TIME1?>" onClick="WdatePicker()"/> 
        <?=_("至")?>
        <input type="text" name="CONTRACT_REMOVE_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$CONTRACT_REMOVE_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time4\')}'})"/>     
      </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"><?=_("合同签约公司：")?></td>
         <td class="TableData">
           <select name="CONTRACT_ENTERPRIES" style="background: white;" title="<?=_("合同签约公司可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
             <option value=""><?=_("合同签约公司")?>&nbsp;&nbsp;</option>
             <?=hrms_code_list("HR_ENTERPRISE","")?>
           </select>
         </td>
   </tr>
   
   <tr>
        <td nowrap class="TableData"><?=_("合同期限属性：")?></td>
         <td class="TableData">
            <select name="CONTRACT_SPECIALIZATION" id="CONTRACT_SPECIALIZATION" onchange="TIME_LIMIT()">
               <option value=""><?=_("合同期限属性")?></option>
                <option value="1"><?=_("固定期限")?></option>
               <option value="2"><?=_("无固定期限")?></option>
               <option value="3"><?=_("以完成一定工作任务为期限")?></option>
           </select>
         </td>
   </tr>
   
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton">
      </td>
    </tr>
  </form>
 </table>


</body>
</html>
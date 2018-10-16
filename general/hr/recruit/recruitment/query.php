<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("招聘录用查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor">
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"><?=_("招聘录用查询")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
<table class="TableBlock" width="600" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("计划编号：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="PLAN_NO" class=BigInput size="15" >
       </td>
      <td nowrap class="TableData"><?=_("应聘人姓名：")?></td>
      <td class="TableData">
        <INPUT type="text"name="APPLYER_NAME" class=BigInput size="15"> 
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData">OA<?=_("中用户名：")?></td>
      <td class="TableData" colspan=3>
        <INPUT type="text"name="OA_NAME" class=BigInput size="15"> 
      </td>
    </tr>
    <tr>
    	</td>
       <td nowrap class="TableData"><?=_("招聘岗位：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="JOB_STATUS" class=BigInput size="15" >
       <td nowrap class="TableData"><?=_("录用负责人：")?></td>
      <td class="TableData">
       <INPUT type="text"name="ASSESSING_OFFICER_NAME" class=BigStatic size="15" readonly value="">
       <input type="hidden" name="ASSESSING_OFFICER" value="<?=$ASSESSING_OFFICER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','ASSESSING_OFFICER', 'ASSESSING_OFFICER_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("录入日期：")?></td>
      <td class="TableData" colspan='3'>
      	<input type="text" id="start_time" name="ASS_PASS_TIME_START" size="10" maxlength="10" class="BigInput" value="<?=$ASS_PASS_TIME_START?>" onClick="WdatePicker()"/> 
      	<?=_("至")?>
      	<input type="text" name="ASS_PASS_TIME_END" size="10" maxlength="10" class="BigInput" value="<?=$ASS_PASS_TIME_END?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("招聘部门：")?></td>
    	<td class="TableData" colspan=3>
    	  <input type="hidden" name="DEPARTMENT">
        <input type="text" name="DEPARTMENT_NAME" value="" class=BigStatic size=15 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','DEPARTMENT','DEPARTMENT_NAME')"><?=_("选择")?></a>             
      </td>
    </tr> 
    <tr>
    	<td nowrap class="TableData" width="100"><?=_("员工类型")?></td>
    	<td class="TableData">
        <select name="TYPE" class="BigSelect">
        <option></option>
				<?=hrms_code_list("STAFF_OCCUPATION",$STAFF_OCCUPATION);?>
        </select>    	
    	</td>   
      <td nowrap class="TableData"><?=_("行政等级：")?></td>
      <td class="TableData" colspan=3>
        <INPUT type="text"name="ADMINISTRATION_LEVEL" class=BigInput size="15" >
    </tr> 
    <tr>
    	 <td nowrap class="TableData"><?=_("职务：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="JOB_POSITION" class=BigInput size="15" >
      <td nowrap class="TableData" width="100"><?=_("职称：")?></td>
      <td class="TableData"  width="180">
        <select name="PRESENT_POSITION" class="BigSelect">
        	<option></option>
				<?=hrms_code_list("PRESENT_POSITION",$PRESENT_POSITION);?>
        </select>
    	</td>
    </tr> 
     <tr>
      <td nowrap class="TableData"><?=_("备注：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="66" rows="5" class="BigInput" value=""></textarea>
      </td>
    </tr>
    <tr align="center" class="TableControl">
	      <td colspan="6" nowrap>
	        <input type="submit" value="<?=_("查询")?>" class="BigButton">&nbsp;&nbsp;
	      </td>
 		</tr>     
</table>
</form>

</table>
</body>
</html>
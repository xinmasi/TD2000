<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�˲ŵ�����ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
}); 
function exreport()
{
  document.form1.action='export.php';
  document.form1.submit();
}
function search()
{
  document.form1.action='search.php';
  document.form1.submit();
}

function ControlContent06()
{
   if(contentid06.style.display == 'none'){
   	  contentid06.style.display = '';
      //imgar.src = '<?=MYOA_STATIC_SERVER?>/static/images/arrow_up.gif';
   }else{
   	 contentid06.style.display = 'none';
   	 //imgar.src = '<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif';
   }	
}
</script>
<body class="bodycolor">
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("�˲ŵ�����ѯ")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" method="post" id="form1" name="form1" >
<table class="TableBlock" width="770" align="center">
  <tr>
    <td nowrap class="TableData" width="100"><?=_("�ƻ���ţ�")?></td>
    <td class="TableData" width="180"><input type="text" name="PLAN_NO" value="<?=$PLAN_NO?>"></td>
    <td nowrap class="TableData" width="100"><?=_("ӦƸ��������")?></td>
    <td class="TableData" width="180"><input type="text" name="EMPLOYEE_NAME" id="EMPLOYEE_NAME" class="BigInput"></td>         
  </tr>
  <tr>
   <td nowrap class="TableData" width="100"><?=_("�Ա�")?></td>
   <td class="TableData"  width="180">
    	<select name="EMPLOYEE_SEX" class="BigSelect">
         <option value=""><?=_("�Ա�")?>&nbsp;&nbsp;</option>
         <option value="0"><?=_("��")?></option>
         <option value="1"><?=_("Ů")?></option>
     </select>
   </td>    	
   <td nowrap class="TableData"><?=_("���᣺")?></td>
   <td class="TableData">
     <select name="EMPLOYEE_NATIVE_PLACE" class="BigSelect">
     	<option value=""><?=_("����")?>&nbsp;&nbsp;</option>
<?=hrms_code_list("AREA",$EMPLOYEE_NATIVE_PLACE);?>
     </select>
   </td>      
  </tr>
  <tr>
   <td nowrap class="TableData"><?=_("������ò��")?></td>
   <td class="TableData">
     <select name="EMPLOYEE_POLITICAL_STATUS" class="BigSelect">
        <option value=""><?=_("������ò")?></option>
        <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$EMPLOYEE_POLITICAL_STATUS); ?>
     </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("��λ��")?></td>
    <td class="TableData">
     <select name="POSITION" class="BigSelect">
			<option value=""><?=_("��λ")?></option>
		  <?=hrms_code_list("POOL_POSITION","");?>		
     </select>
    </td>            
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("�����������ʣ�")?></td>
    <td class="TableData">
    	<select name="JOB_CATEGORY" class="BigSelect">
    		<option value=""><?=_("��������")?></option>
<?=hrms_code_list("JOB_CATEGORY","");?>		
       </select>
    </td> 
    <td nowrap class="TableData"><?=_("��������ְҵ��")?></td>
    <td class="TableData"><input type="text" name="JOB_INTENSION" id="JOB_INTENSION" class="BigInput"></td>           
  </tr>
  <tr>
  	<td nowrap class="TableData"><?=_("�����������У�")?></td>
    <td class="TableData"><input type="text" name="WORK_CITY" id="WORK_CITY" class="BigInput"></td>  
    <td nowrap class="TableData" width="100"><?=_("����нˮ(˰ǰ)��")?></td>
    <td class="TableData"  width="180"><input type="text" name="EXPECTED_SALARY" id="EXPECTED_SALARY" size="10" class="BigInput validate[custom[money]]" data-prompt-position="centerRight:14,-6">&nbsp;<?=_("Ԫ")?></td>            
  </tr>
  <tr>
  	<td nowrap class="TableData" width="100"><?=_("����ʱ�䣺")?></td>
    <td class="TableData"  width="180">
      <select name="START_WORKING" class="BigSelect">
        <option value=""><?=_("����ʱ��")?></option>
        <option value="0">1<?=_("������")?></option>
        <option value="1">1<?=_("������")?></option>
        <option value="2">1~3<?=_("����")?></option>
        <option value="3">3<?=_("���º�")?></option>
        <option value="4"><?=_("��ʱ����")?></option>
      </select>     
    </td>
    <td nowrap class="TableData" width="100"><?=_("��ѧרҵ��")?></td>
    <td class="TableData"  width="180"><input type="text" name="EMPLOYEE_MAJOR" id="EMPLOYEE_MAJOR" class="BigInput"></td> 
   </tr>
   <tr>
   	<td nowrap class="TableData" width="100"><?=_("ѧ����")?></td>
    <td class="TableData"  width="180">
       <select name="EMPLOYEE_HIGHEST_SCHOOL" class="BigSelect">
       	<option value=""><?=_("ѧ��")?>&nbsp;&nbsp;</option>
<?=hrms_code_list("STAFF_HIGHEST_SCHOOL","");?>		
       </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("�־�ס���У�")?></td>
    <td class="TableData" width="180">
    	<input type="text" name="RESIDENCE_PLACE" id="RESIDENCE_PLACE" class="BigInput" value="">
    </td>    
  </tr>         
  <tr class="TableHeader" height="25">
    <td nowrap colspan="4"><span><?=_("�����ѯѡ��")?></span>&nbsp;&nbsp;<img src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" border="0" id="imgar" title="<?=_("չ��/����")?>" onClick="ControlContent06();" style="cursor:hand"></td>
  </tr>
  <tr  style="display:none"  id="contentid06">
  	<td colspan="4" valign="top">
<div>
<table class="TableBlock" width="770" align="center">
  <tr>
		<td nowrap class="TableData" width="100"><?=_("���壺")?></td>
    <td class="TableData"  width="180"><input type="text" name="EMPLOYEE_NATIONALITY" id="EMPLOYEE_NATIONALITY" class="BigInput" value=""></td> 
		<td nowrap class="TableData" width="100"><?=_("����״����")?></td>
    <td class="TableData"  width="180"><input type="text" name="EMPLOYEE_HEALTH " id="EMPLOYEE_HEALTH " class="BigInput"></td>
	</tr>   
	<tr>
		<td nowrap class="TableData" width="100"><?=_("����״����")?></td>
    <td class="TableData" width="180">
      <select name="EMPLOYEE_MARITAL_STATUS" class="BigSelect">
      	<option value=""></option>
        <option value="0"><?=_("δ��")?></option>
        <option value="1"><?=_("�ѻ�")?></option>
        <option value="2"><?=_("����")?></option>
        <option value="3"><?=_("ɥż")?></option>
      </select>    	
    </td>
    <td nowrap class="TableData" width="100"><?=_("�������ڵأ�")?></td>
    <td class="TableData"  width="180"><input type="text" name="EMPLOYEE_DOMICILE_PLACE" id="EMPLOYEE_DOMICILE_PLACE" class="BigInput"></td>                  
	</tr>   
	<tr>
		<td nowrap class="TableData" width="100"><?=_("��ҵѧУ��")?></td>
		<td class="TableData"  width="180"><input type="text" name="GRADUATION_SCHOOL" id="GRADUATION_SCHOOL" class="BigInput"></td>
		<td nowrap class="TableData" width="100"><?=_("�����ˮƽ��")?></td>
		<td class="TableData"  width="180"><input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" class="BigInput"></td>                 
	</tr>       
	<tr>
		<td nowrap class="TableData" width="100"><?=_("��������")?>1<?=_("��")?></td>
		<td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE1" id="FOREIGN_LANGUAGE1" class="BigInput"></td>
		<td nowrap class="TableData" width="100"><?=_("��������")?>2<?=_("��")?></td>
		<td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE2" id="FOREIGN_LANGUAGE2" class="BigInput"></td>                
	</tr>       
	<tr>
		<td nowrap class="TableData" width="100"><?=_("����ˮƽ")?>1<?=_("��")?></td>
		<td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL1" id="FOREIGN_LEVEL1" class="BigInput"></td>
		<td nowrap class="TableData" width="100"><?=_("����ˮƽ")?>2<?=_("��")?></td>
		<td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL2" id="FOREIGN_LEVEL2" class="BigInput"></td>          
	</tr>          
</table>
</div>
</td>
</tr>
	<tr align="center" class="TableControl">
		<td colspan="4" nowrap>
			<input type="submit" value="<?=_("��ѯ")?>" class="BigButton" onClick="search()">&nbsp;&nbsp;
			<input type="button" value="<?=_("����")?>" class="BigButton" onClick="exreport()">&nbsp;&nbsp;
		</td>
  </tr>          
</table>
</form>

</table>
</body>
</html>
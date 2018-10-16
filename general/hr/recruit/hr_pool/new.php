<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�½��˲ŵ���");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
}); 
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?EXPERT_ID=<?=$EXPERT_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
function delete_photo()
{
  msg="<?=_("ȷ��Ҫɾ���ϴ�����Ƭ��")?>?";
  if(window.confirm(msg))
  {
    URL="delete_photo.php?EXPERT_ID=<?=$EXPERT_ID?>";
    window.location=URL;
  }
}
function LoadWindow2()
{
    URL="plan_no_info/?PLAN_NO=<?=$PLAN_NO?>";
    
    var loc_y = (window.screen.height-30-260)/2; //��ô��ڵĴ�ֱλ��;
    var loc_x = (window.screen.width-10-350)/2; //��ô��ڵ�ˮƽλ��;
    //loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    //loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    // window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:350px;dialogHeight:260px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
	window.open(URL,"parent","height=350,width=300,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes");
}

function checkDate()
{
	var birth=document.form1.EMPLOYEE_BIRTH.value;
	var myDate = new Date();
	var month = myDate.getMonth()+1;
	var day = myDate.getDate();
	var birth_day=birth.substr(8,2);
	var birth_month=birth.substr(5,2);
  var age=myDate.getFullYear()-birth.substr(0,4);
	if(birth_month<month || birth_month==month && birth_day<=day)
	{
		age++;
	}
	document.form1.STAFF_AGE.value=age-1;
}
</script>

<body class="bodycolor">

<table border="0" width="770" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½��˲ŵ���")?></span>&nbsp;&nbsp;</td>
  </tr>
</table>

<form enctype="multipart/form-data" action="add.php" method="post" id="form1" name="form1" >
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("������Ϣ��")?></b></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("�ƻ����ƣ�")?></td>
    <td class="TableData" width="180">
      <INPUT type="text"name="PLAN_NAME" class="BigStatic validate[required]"  data-prompt-position="centerRight:0,-8" size="12" readonly>
      <INPUT type="hidden" name="PLAN_NO" value="">
      <a href="javascript:;" class="orgAdd" onClick="LoadWindow2()"><?=_("ѡ��")?></a>
    </td>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("ӦƸ��������")?></td>
    <td class="TableData" width="180" colspan="2">
    	<input type="text" name="EMPLOYEE_NAME"class="BigInput validate[required]"  data-prompt-position="centerRight:0,-8"id="EMPLOYEE_NAME" value="">
    </td>
    <td class="TableData" rowspan="7" colspan="1" align="center">
<?
   if($PHOTO_NAME=="")
      echo "<center>"._("������Ƭ")."</center>";
   else
      echo "<A border=0 href=\"#\"><img src='recruit_pic.php?PHOTO_NAME=$PHOTO_NAME' width='150' border=0></A>";
?>
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"><font color="red">*</font><?=_("�Ա�")?></td>
   <td class="TableData">
    <select name="EMPLOYEE_SEX" class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
         <option value="0"><?=_("��")?></option>
         <option value="1"><?=_("Ů")?></option>
     </select>
   </td>
   <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("�������ڣ�")?></td>
    <td class="TableData" width="180" colspan="2">
       <input type="text" name="EMPLOYEE_BIRTH" size="10" maxlength="10" class="BigInput validate[required]"  data-prompt-position="centerRight:0,-8" value="<?=$EMPLOYEE_BIRTH=="0000-00-00"?"":$EMPLOYEE_BIRTH;?>" onClick="WdatePicker()" onBlur="checkDate()"/>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("���壺")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="EMPLOYEE_NATIONALITY" id="EMPLOYEE_NATIONALITY" class="BigInput" value="">
    </td>
    <td nowrap class="TableData" width="100"><?=_("�־�ס���У�")?></td>
    <td class="TableData" width="180" colspan="2">
    	<input type="text" name="RESIDENCE_PLACE" id="RESIDENCE_PLACE" class="BigInput" value="">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("��ϵ�绰��")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="EMPLOYEE_PHONE" id="EMPLOYEE_PHONE" class="BigInput validate[required]"  data-prompt-position="centerRight:0,-8">
    </td>
    <td nowrap class="TableData" ><font color="red">*</font>E_mail<?=_("��")?></td>
     <td class="TableData"  width="180" colspan="2">
     	<input type="text" name="EMPLOYEE_EMAIL" id="EMPLOYEE_EMAIL" class="BigInput validate[required,custom[email]]" data-prompt-position="centerRight:0,-6">
     </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("���᣺")?></td>
    <td class="TableData">
    	<select name="EMPLOYEE_NATIVE_PLACE" class="BigSelect">
<?=hrms_code_list("AREA",$EMPLOYEE_NATIVE_PLACE);?>
      </select>
      <input type="text" name="EMPLOYEE_NATIVE_PLACE2" size=10 class="BigInput">
    </td>
     <td nowrap class="TableData" width="100"><?=_("�������ڵأ�")?></td>
    <td class="TableData"  width="180" colspan="2">
    	<input type="text" name="EMPLOYEE_DOMICILE_PLACE" id="EMPLOYEE_DOMICILE_PLACE" size="35" class="BigInput">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("����״����")?></td>
    <td class="TableData">
      <select name="EMPLOYEE_MARITAL_STATUS" class="BigSelect">
        <option value=""></option>
        <option value="0"><?=_("δ��")?>&nbsp;&nbsp;</option>
        <option value="1"><?=_("�ѻ�")?></option>
        <option value="2"><?=_("����")?></option>
        <option value="3"><?=_("ɥż")?></option>
      </select>
    </td>
    <td nowrap class="TableData"><?=_("������ò��")?></td>
    <td class="TableData" colspan="2">
        <select name="EMPLOYEE_POLITICAL_STATUS" class="BigSelect">
          <option value=""><?=_("������ò")?></option>
          <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$EMPLOYEE_POLITICAL_STATUS);?>
        </select>
    </td>
  </tr>
  <tr>
  	<td nowrap class="TableData" width="100"><?=_("����״����")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="EMPLOYEE_HEALTH" id="EMPLOYEE_HEALTH" class="BigInput" value=<?=$EMPLOYEE_HEALTH?>>
    </td>
     <td nowrap class="TableData" width="100"><?=_("�μӹ���ʱ�䣺")?></td>
    <td class="TableData"  width="180" colspan="2">
      <input type="text" name="JOB_BEGINNING" size="13" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING?>" onClick="WdatePicker()"/>
    </td>
  </tr>
 <tr>
  <td nowrap class="TableData"><font color="red">*</font><?=_("�����������ʣ�")?></td>
    <td class="TableData">
    	<select name="JOB_CATEGORY" class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
<?=hrms_code_list("JOB_CATEGORY","");?>
       </select>
    </td>
<?
if($PHOTO_NAME=="")
   $PHOTO_STR=_("��Ƭ�ϴ���");
else
   $PHOTO_STR=_("��Ƭ���ģ�");
?>
    <td nowrap class="TableData" width="100"><?=$PHOTO_STR?></td>
    <td class="TableData"  width="180" colspan="3">
       <input type="file" name="ATTACHMENT" size="40"  class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>" >
<?
if($PHOTO_NAME!="")
{
?>
      <br><a href=#this onClick="delete_photo();"><?=_("ɾ����Ƭ")?></a>
<?
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("����������ҵ��")?></td>
    <td class="TableData">
    	<input type="text" name="JOB_INDUSTRY" id="JOB_INDUSTRY" class="BigInput">
    </td>
    <td nowrap class="TableData"><?=_("��������ְҵ��")?></td>
    <td class="TableData">
    	<input type="text" name="JOB_INTENSION" id="JOB_INTENSION" class="BigInput">
    </td>
    <td nowrap class="TableData"><?=_("�����������У�")?></td>
    <td class="TableData">
    	<input type="text" name="WORK_CITY" id="WORK_CITY" class="BigInput">
    </td>
  </tr>
    <tr>
    <td nowrap class="TableData" width="110"><font color="red">*</font><?=_("����нˮ(˰ǰ)��")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="EXPECTED_SALARY" id="EXPECTED_SALARY" size="10" class="BigInput validate[required]" data-prompt-position="centerRight:14,-6">&nbsp;<?=_("Ԫ")?>
    </td>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("��λ��")?></td>
    <td class="TableData">
     <select name="POSITION" class="BigInput validate[required]" data-prompt-position="centerRight:14,-6">
			<option value=""><?=_("��λ")?></option>
		  <?=hrms_code_list("POOL_POSITION","");?>
     </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("����ʱ�䣺")?></td>
    <td class="TableData"  width="180">
      <select name="START_WORKING" class="BigSelect">
        <option value="" <? if($START_WORKING=="") echo "selected";?>></option>
        <option value="0" <? if($START_WORKING==0) echo "selected";?>>1<?=_("������")?></option>
        <option value="1" <? if($START_WORKING==1) echo "selected";?>>1<?=_("������")?></option>
        <option value="2" <? if($START_WORKING==2) echo "selected";?>>1~3<?=_("����")?></option>
        <option value="3" <? if($START_WORKING==3) echo "selected";?>>3<?=_("���º�")?></option>
        <option value="4" <? if($START_WORKING==4) echo "selected";?>><?=_("��ʱ����")?></option>
      </select>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��ҵʱ�䣺")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="GRADUATION_DATE" size="10" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE?>" onClick="WdatePicker()"/>
    </td>
    <td nowrap class="TableData" width="100"><?=_("��ҵѧУ��")?></td>
    <td class="TableData"  width="180" colspan="3">
    	<input type="text" name="GRADUATION_SCHOOL" size="60" id="GRADUATION_SCHOOL" class="BigInput">
    </td>
  </tr>
    <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("��ѧרҵ��")?></td>
    <td class="TableData"  width="180">
    	<select name="EMPLOYEE_MAJOR" class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
			<option value=""><?=_("��ѧרҵ")?></option>
		  <?=hrms_code_list("POOL_EMPLOYEE_MAJOR","");?>
     </select>
    </td>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("ѧ����")?></td>
    <td class="TableData"  width="180">
        <select name="EMPLOYEE_HIGHEST_SCHOOL" class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
        	<option value=""><?=_("ѧ��")?></option>
					<?=hrms_code_list("STAFF_HIGHEST_SCHOOL",6);?>
        </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("ѧλ��")?></td>
    <td class="TableData"  width="180">
    	<select name="EMPLOYEE_HIGHEST_DEGREE" class="BigSelect">
    		<option value=""><?=_("ѧλ")?></option>
			  <?=hrms_code_list("EMPLOYEE_HIGHEST_DEGREE",6);?>
      </select>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��������")?>1<?=_("��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE1" id="FOREIGN_LANGUAGE1" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("��������")?>2<?=_("��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE2" id="FOREIGN_LANGUAGE2" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("��������")?>3<?=_("��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE3" id="FOREIGN_LANGUAGE3" class="BigInput"></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("����ˮƽ")?>1<?=_("��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL1" id="FOREIGN_LEVEL1" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("����ˮƽ")?>2<?=_("��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL2" id="FOREIGN_LEVEL2" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("����ˮƽ")?>3<?=_("��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL3" id="FOREIGN_LEVEL3" class="BigInput"></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("�����ˮƽ��")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" class="BigInput">
    </td>
    
    <!---<td nowrap class="TableData"><?=_("���䣺")?></td>
    <td class="TableData" width="180">
    	<input type="text" name="EMPLOYEE_AGE" id="EMPLOYEE_AGE" size="8" class="BigInput">&nbsp;<?=_("��")?>
    </td>--->
<?
if($EMPLOYEE_BIRTH!="0000-00-00" && $EMPLOYEE_BIRTH!="")
{
 	 $agearray = explode("-",$EMPLOYEE_BIRTH);
 	 $cur = explode("-",$CUR_DATE);
 	 $year=$agearray[0];
 	 $STAFF_AGE=date("Y")-$year;
 	 if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
 	 {
 		  $STAFF_AGE++;
 	 }
}
else
{
  	$STAFF_AGE="";
}
if($STAFF_AGE!="")
{
  	$STAFF_AGE = $STAFF_AGE-1;
    $query1="update HR_RECRUIT_POOL set EMPLOYEE_AGE='$STAFF_AGE' where EXPERT_ID='$EXPERT_ID'";
  	exequery(TD::conn(),$query1);
}
?>
    <td nowrap class="TableData"><?=_("���䣺")?></td>
    <td class="TableData"><input type="text" name="STAFF_AGE" size="4" class="BigInput" value="<?=$STAFF_AGE?>" readonly><?=_("��")?></td>
    
    
    <td nowrap class="TableData"><?=_("��Ƹ������")?></td>
    <td class="TableData" width="180">
    	<select name="RECRU_CHANNEL" style="background: white;" title="<?=_("Ա���ػ����Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>"> 
				<option value=""></option>
			  <?=hrms_code_list("PLAN_DITCH","")?> 
				</select> 
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("�س���")?></td>
    <td class="TableData"  width="180" colspan="5">
    	<textarea name="EMPLOYEE_SKILLS" cols="100" rows="3" class="BigInput" value=""><?=$EMPLOYEE_SKILLS?></textarea>
   </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("ְҵ���ܣ�")?></td>
    <td class="TableData"  width="180" colspan="5">
    	<textarea name="CAREER_SKILLS" cols="100" rows="3" class="BigInput" value=""><?=$CAREER_SKILLS?></textarea>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("�������飺")?></td>
    <td class="TableData"  width="180" colspan="5">
    	<textarea name="WORK_EXPERIENCE" cols="100" rows="3" class="BigInput" value=""><?=$WORK_EXPERIENCE?></textarea>
   </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��Ŀ���飺")?></td>
    <td class="TableData"  width="180" colspan="5">
    	<textarea name="PROJECT_EXPERIENCE" cols="100" rows="3" class="BigInput" value=""><?=$PROJECT_EXPERIENCE?></textarea>
   </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��ע��")?></td>
    <td class="TableData"  width="180" colspan="5"><textarea name="REMARK" cols="100" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("����������")?></b></td>
  </tr>
  <tr>
    <td class="TableData" colspan="6"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);?></td>
  </tr>
  <tr height="25">
    <td nowrap class="TableData"><?=_("����ѡ��")?></td>
    <td class="TableData" colspan="6">
       <script>ShowAddFile('1');</script>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" colspan="6"><?=_("������")?></td>
  </tr>
    <tr>
    <td nowrap class="TableData" colspan="6">
<?
$editor = new Editor('RESUME') ;
$editor->Height = '450';
$editor->Value = $RESUME ;
$editor->Config = array('model_type' => '15') ;
$editor->Create() ;
?>
    </td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan=6 nowrap>
     <input type="submit" value="<?=_("����")?>" class="BigButton" >
    </td>
  </tr>
</table>
<form>
</body>
</html>
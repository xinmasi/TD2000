<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");


$HTML_PAGE_TITLE = _("�˲ŵ����޸�");
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

function upload_attach()
{
  if(true)
   {
     document.form1.submit();
   }
}
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
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  if(window.showModalDialog){
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }else{
    	window.open(URL,"loadwin","height=245,width=320,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
  }
}
</script>

<?
$query="select * from HR_RECRUIT_POOL where EXPERT_ID='$EXPERT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $EXPERT_ID=$ROW["EXPERT_ID"];
  $USER_ID=$ROW["USER_ID"];
	$DEPT_ID=$ROW["DEPT_ID"];
  $PLAN_NO=$ROW["PLAN_NO"];
  $PLAN_NAME=$ROW["PLAN_NAME"];
  $POSITION =$ROW["POSITION"];
  $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];
  $EMPLOYEE_SEX=$ROW["EMPLOYEE_SEX"];
  $EMPLOYEE_BIRTH=$ROW["EMPLOYEE_BIRTH"];
  $EMPLOYEE_NATIVE_PLACE=$ROW["EMPLOYEE_NATIVE_PLACE"];
  $EMPLOYEE_NATIVE_PLACE2=$ROW["EMPLOYEE_NATIVE_PLACE2"];
  $EMPLOYEE_DOMICILE_PLACE=$ROW["EMPLOYEE_DOMICILE_PLACE"];
  $EMPLOYEE_NATIONALITY=$ROW["EMPLOYEE_NATIONALITY"];
  $EMPLOYEE_MARITAL_STATUS=$ROW["EMPLOYEE_MARITAL_STATUS"];
  $EMPLOYEE_POLITICAL_STATUS=$ROW["EMPLOYEE_POLITICAL_STATUS"];
  $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
  $EMPLOYEE_EMAIL=$ROW["EMPLOYEE_EMAIL"];
	$JOB_BEGINNING=$ROW["JOB_BEGINNING"];
  $EMPLOYEE_HEALTH=$ROW["EMPLOYEE_HEALTH"];
  $EMPLOYEE_HIGHEST_SCHOOL =$ROW["EMPLOYEE_HIGHEST_SCHOOL"];
  $EMPLOYEE_HIGHEST_DEGREE=$ROW["EMPLOYEE_HIGHEST_DEGREE"];
  $GRADUATION_DATE=$ROW["GRADUATION_DATE"];
  $GRADUATION_SCHOOL=$ROW["GRADUATION_SCHOOL"];
  $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
  $COMPUTER_LEVEL=$ROW["COMPUTER_LEVEL"];
  $FOREIGN_LANGUAGE1=$ROW["FOREIGN_LANGUAGE1"];
  $FOREIGN_LEVEL1=$ROW["FOREIGN_LEVEL1"];
  $FOREIGN_LANGUAGE2=$ROW["FOREIGN_LANGUAGE2"];
  $FOREIGN_LEVEL2=$ROW["FOREIGN_LEVEL2"];
  $FOREIGN_LANGUAGE3=$ROW["FOREIGN_LANGUAGE3"];
	$FOREIGN_LEVEL3=$ROW["FOREIGN_LEVEL3"];
  $EMPLOYEE_SKILLS=$ROW["EMPLOYEE_SKILLS"];
  $RESUME =$ROW["RESUME"];
  $JOB_INTENSION=$ROW["JOB_INTENSION"];
  $CAREER_SKILLS=$ROW["CAREER_SKILLS"];
  $WORK_EXPERIENCE=$ROW["WORK_EXPERIENCE"];
  $PROJECT_EXPERIENCE=$ROW["PROJECT_EXPERIENCE"];
  $RESIDENCE_PLACE=$ROW["RESIDENCE_PLACE"];
  $JOB_CATEGORY=$ROW["JOB_CATEGORY"];
  $JOB_INDUSTRY=$ROW["JOB_INDUSTRY"];
  $WORK_CITY=$ROW["WORK_CITY"];
  $EXPECTED_SALARY=$ROW["EXPECTED_SALARY"];
  $START_WORKING=$ROW["START_WORKING"];
  $REMARK=$ROW["REMARK"];
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];
  $PHOTO_NAME =$ROW["PHOTO_NAME"];
  $RECRU_CHANNEL =$ROW["RECRU_CHANNEL"];
  $EMPLOYEE_AGE =$ROW["EMPLOYEE_AGE"];


  if($EMPLOYEE_BIRTH=="0000-00-00")
     $EMPLOYEE_BIRTH="";
  if($JOB_BEGINNING=="0000-00-00")
     $JOB_BEGINNING="";
  if($GRADUATION_DATE=="0000-00-00")
     $GRADUATION_DATE="";

  $query1 = "SELECT PLAN_NAME from HR_RECRUIT_PLAN where PLAN_NO='$PLAN_NO'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW1=mysql_fetch_array($cursor1))
     $PLAN_NAME=$ROW1["PLAN_NAME"];
}

?>
<body class="bodycolor">

<table border="0" width="770" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�༭�˲ŵ���")?></span>&nbsp;&nbsp;</td>
  </tr>
</table>

<form enctype="multipart/form-data" action="update.php" method="post" id="form1" name="form1">
<table class="TableBlock" width="770" align="center">
  <tr>
    <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("������Ϣ��")?></b></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("�ƻ����ƣ�")?></td>
    <td class="TableData" width="180">
      <INPUT type="text"name="PLAN_NAME"  class="BigInput validate[required]"  data-prompt-position="centerRight:0,-8" size="12" value="<?=$PLAN_NAME?>">
      <INPUT type="hidden" name="PLAN_NO" value="<?=$PLAN_NO?>">
      <a href="javascript:;" class="orgAdd" onClick="LoadWindow2()"><?=_("ѡ��")?></a>
    </td>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("ӦƸ��������")?></td>
    <td class="TableData" width="180" colspan="2">
    	<input type="text" name="EMPLOYEE_NAME"  class="BigInput validate[required]"  data-prompt-position="centerRight:0,-8" id="EMPLOYEE_NAME" value="<?=$EMPLOYEE_NAME?>">
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
    <select name="EMPLOYEE_SEX" class="BigSelect  validate[required]" data-prompt-position="centerRight:0,-6">
         <option value=""><?=_("�Ա�")?>&nbsp;&nbsp;</option>
         <option value="0" <? if($EMPLOYEE_SEX==0) echo "selected";?>><?=_("��")?></option>
         <option value="1" <? if($EMPLOYEE_SEX==1) echo "selected";?>><?=_("Ů")?></option>
     </select>
   </td>
   <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("�������ڣ�")?></td>
    <td class="TableData" width="180" colspan="2">
      <input type="text" name="EMPLOYEE_BIRTH" size="12" maxlength="10" class="BigInput validate[required]" value="<?=$EMPLOYEE_BIRTH?>" onClick="WdatePicker()"/>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("���壺")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="EMPLOYEE_NATIONALITY" id="EMPLOYEE_NATIONALITY" class="BigInput" value="<?=$EMPLOYEE_NATIONALITY?>">
    </td>
    <td nowrap class="TableData" width="100"><?=_("�־�ס���У�")?></td>
    <td class="TableData" width="180" colspan="2">
    	<input type="text" name="RESIDENCE_PLACE" id="RESIDENCE_PLACE" class="BigInput" value="<?=$RESIDENCE_PLACE?>">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("��ϵ�绰��")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="EMPLOYEE_PHONE" id="EMPLOYEE_PHONE" class="BigInput validate[required]" data-prompt-position="centerRight:0,-6" value="<?=$EMPLOYEE_PHONE?>">
    </td>
    <td nowrap class="TableData" ><font color="red">*</font><?=_("E-mail��")?></td>
     <td class="TableData"  width="180" colspan="2">
     	<input type="text" name="EMPLOYEE_EMAIL" id="EMPLOYEE_EMAIL" class="BigInput validate[required,custom[email]]" data-prompt-position="centerRight:0,-6" value="<?=$EMPLOYEE_EMAIL?>">
     </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("���᣺")?></td>
    <td class="TableData" nowrap>
    	<select name="EMPLOYEE_NATIVE_PLACE" class="BigSelect">
<?=hrms_code_list("AREA",$EMPLOYEE_NATIVE_PLACE);?>
      </select>
      <input type="text" name="EMPLOYEE_NATIVE_PLACE2" style="height: 24px;" value="<?=$EMPLOYEE_NATIVE_PLACE2?>" >
    </td>
     <td nowrap class="TableData" width="100"><?=_("�������ڵأ�")?></td>
    <td class="TableData"  width="180" colspan="2">
    	<input type="text" name="EMPLOYEE_DOMICILE_PLACE" id="EMPLOYEE_DOMICILE_PLACE" size="35" class="BigInput" value="<?=$EMPLOYEE_DOMICILE_PLACE?>">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("����״����")?></td>
    <td class="TableData">
      <select name="EMPLOYEE_MARITAL_STATUS" class="BigSelect">
        <option value="" <? if($EMPLOYEE_MARITAL_STATUS=="") echo "selected";?>></option>
        <option value="0" <? if($EMPLOYEE_MARITAL_STATUS=="0") echo "selected";?>><?=_("δ��")?></option>
        <option value="1" <? if($EMPLOYEE_MARITAL_STATUS=="1") echo "selected";?>><?=_("�ѻ�")?></option>
        <option value="2" <? if($EMPLOYEE_MARITAL_STATUS=="2") echo "selected";?>><?=_("����")?></option>
        <option value="3" <? if($EMPLOYEE_MARITAL_STATUS=="3") echo "selected";?>><?=_("ɥż")?></option>
      </select>
    </td>
    <td nowrap class="TableData"><?=_("������ò��")?></td>
    <td class="TableData" colspan="2">
        <select name="EMPLOYEE_POLITICAL_STATUS" class="BigSelect">
          <option value=""><?=_("������ò")?></option>
          <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$EMPLOYEE_POLITICAL_STATUS); ?>
        </select>
  </tr>
  <tr>
  	<td nowrap class="TableData" width="100"><?=_("����״����")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="EMPLOYEE_HEALTH" id="EMPLOYEE_HEALTH" class="BigInput" value="<?=$EMPLOYEE_HEALTH?>">
    </td>
     <td nowrap class="TableData" width="100"><?=_("�μӹ���ʱ�䣺")?></td>
    <td class="TableData"  width="180" colspan="2">
      <input type="text" name="JOB_BEGINNING" size="13" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING?>" onClick="WdatePicker()"/>
    </td>
  </tr>
 <tr>
  <td nowrap class="TableData"><font color="red">*</font><?=_("�����������ʣ�")?></td>
    <td class="TableData">
    	<select name="JOB_CATEGORY"  class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
<?=hrms_code_list("JOB_CATEGORY",$JOB_CATEGORY);?>
       </select>
    </td>
<?
if($PHOTO_NAME=="")
   $PHOTO_STR=_("��Ƭ�ϴ���");
else
   $PHOTO_STR=_("��Ƭ���ģ�");
?>
    <td nowrap class="TableData" width="100"><?=$PHOTO_STR?></td>
    <td class="TableData" colspan="3">
       <input type="file" name="ATTACHMENT" size="30" class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>">
<?
if($PHOTO_NAME!="")
{
?>
      &nbsp;&nbsp;&nbsp;&nbsp;<a href=#this onClick="delete_photo();"><?=_("ɾ����Ƭ")?></a>
<?
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("����������ҵ��")?></td>
    <td class="TableData">
    	<input type="text" name="JOB_INDUSTRY" id="JOB_INDUSTRY" class="BigInput" value="<?=$JOB_INDUSTRY?>">
    </td>
    <td nowrap class="TableData"><?=_("��������ְҵ��")?></td>
    <td class="TableData">
    	<input type="text" name="JOB_INTENSION" id="JOB_INTENSION" class="BigInput" value="<?=$JOB_INTENSION?>">
    </td>
    <td nowrap class="TableData"><?=_("�����������У�")?></td>
    <td class="TableData">
    	<input type="text" name="WORK_CITY" id="WORK_CITY" class="BigInput" value="<?=$WORK_CITY?>">
    </td>
  </tr>
    <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("����нˮ(˰ǰ)��")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="EXPECTED_SALARY" id="EXPECTED_SALARY" size="10" class="BigInput validate[required,custom[money]]" data-prompt-position="centerRight:14,-6" value="<?=$EXPECTED_SALARY?>">&nbsp;<?=_("Ԫ")?>
    </td>
        <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("��λ��")?></td>
    <td class="TableData">
      <select name="POSITION"  class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
			 <option value=""><?=_("��λ")?></option>
		   <?=hrms_code_list("POOL_POSITION","$POSITION");?>
      </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("����ʱ�䣺")?></td>
    <td class="TableData"  width="180">
    	<select name="START_WORKING" class="BigSelect">
        <option value=""  <? if($START_WORKING=="") echo "selected";?>></option>
        <option value="0" <? if($START_WORKING==0) echo "selected";?>><?=_("1������")?></option>
        <option value="1" <? if($START_WORKING==1) echo "selected";?>><?=_("1������")?></option>
        <option value="2" <? if($START_WORKING==2) echo "selected";?>><?=_("1~3����")?></option>
        <option value="3" <? if($START_WORKING==3) echo "selected";?>><?=_("3���º�")?></option>
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
    	<input type="text" name="GRADUATION_SCHOOL" size="60" id="GRADUATION_SCHOOL" class="BigInput" value="<?=$GRADUATION_SCHOOL?>">
    </td>
  </tr>
    <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("��ѧרҵ��")?></td>
    <td class="TableData"  width="180">
    	<select name="EMPLOYEE_MAJOR"  class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
			<option value=""><?=_("��ѧרҵ")?></option>
		  <?=hrms_code_list("POOL_EMPLOYEE_MAJOR","$EMPLOYEE_MAJOR");?>
      </select>
    </td>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("ѧ����")?></td>
    <td class="TableData"  width="180">
        <select name="EMPLOYEE_HIGHEST_SCHOOL"  class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
			<option value="1">Сѧ</option>
			<option value="2">����</option>
			<option value="3">����</option>
			<option value="4">��ר</option>
			<option value="5">��ר</option>
			<option value="6">����</option>
			<option value="7">�о���</option>
			<option value="8">��ʿ</option>
			<option value="9">��ʿ��</option>
			<option value="11">��У</option>
		</select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("ѧλ��")?></td>
    <td class="TableData"  width="180">
    	<select name="EMPLOYEE_HIGHEST_DEGREE" class="BigSelect">
<?=hrms_code_list("EMPLOYEE_HIGHEST_DEGREE",$EMPLOYEE_HIGHEST_DEGREE);?>
        </select>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��������1��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE1" id="FOREIGN_LANGUAGE1" class="BigInput" value="<?=$FOREIGN_LANGUAGE1?>"></td>
    <td nowrap class="TableData" width="100"><?=_("��������2��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE2" id="FOREIGN_LANGUAGE2" class="BigInput" value="<?=$FOREIGN_LANGUAGE2?>"></td>
    <td nowrap class="TableData" width="100"><?=_("��������3��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE3" id="FOREIGN_LANGUAGE3" class="BigInput" value="<?=$FOREIGN_LANGUAGE3?>"></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("����ˮƽ1��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL1" id="FOREIGN_LEVEL1" class="BigInput" value="<?=$FOREIGN_LEVEL1?>"></td>
    <td nowrap class="TableData" width="100"><?=_("����ˮƽ2��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL2" id="FOREIGN_LEVEL2" class="BigInput" value="<?=$FOREIGN_LEVEL2?>"></td>
    <td nowrap class="TableData" width="100"><?=_("����ˮƽ3��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL3" id="FOREIGN_LEVEL3" class="BigInput" value="<?=$FOREIGN_LEVEL3?>"></td>
  </tr>
    <tr>
    <td nowrap class="TableData" width="100"><?=_("�����ˮƽ��")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" class="BigInput" value="<?=$COMPUTER_LEVEL?>">
    </td>
    <td nowrap class="TableData"><?=_("��Ƹ������")?></td>
    <td class="TableData" width="180" colspan="3">
    	<select name="RECRU_CHANNEL" style="background: white;" title="<?=_("Ա���ػ����Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>"> 
				<option value=""></option>
			  <?=hrms_code_list("PLAN_DITCH","$RECRU_CHANNEL")?> 
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
    <td class="TableData"  width="180" colspan="5"><textarea name="REMARK" cols="100" rows="3" class="BigInput validate[maxSize[60]]" value=""><?=$REMARK?></textarea>
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
     <input type="hidden" value="<?=$EXPERT_ID?>" name="EXPERT_ID">
     <input type="hidden" value="<?=$ATTACHMENT_ID?>" name="ATTACHMENT_ID_OLD">
     <input type="hidden" value="<?=$ATTACHMENT_NAME?>" name="ATTACHMENT_NAME_OLD">
     <input type="submit" value="<?=_("����")?>" class="BigButton" >
     <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
    </td>
  </tr>
</table>
<form>
</body>
</html>
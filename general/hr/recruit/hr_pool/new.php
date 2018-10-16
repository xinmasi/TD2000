<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("新建人才档案");
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
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?EXPERT_ID=<?=$EXPERT_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
function delete_photo()
{
  msg="<?=_("确定要删除上传的照片吗")?>?";
  if(window.confirm(msg))
  {
    URL="delete_photo.php?EXPERT_ID=<?=$EXPERT_ID?>";
    window.location=URL;
  }
}
function LoadWindow2()
{
    URL="plan_no_info/?PLAN_NO=<?=$PLAN_NO?>";
    
    var loc_y = (window.screen.height-30-260)/2; //获得窗口的垂直位置;
    var loc_x = (window.screen.width-10-350)/2; //获得窗口的水平位置;
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
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建人才档案")?></span>&nbsp;&nbsp;</td>
  </tr>
</table>

<form enctype="multipart/form-data" action="add.php" method="post" id="form1" name="form1" >
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("基本信息：")?></b></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("计划名称：")?></td>
    <td class="TableData" width="180">
      <INPUT type="text"name="PLAN_NAME" class="BigStatic validate[required]"  data-prompt-position="centerRight:0,-8" size="12" readonly>
      <INPUT type="hidden" name="PLAN_NO" value="">
      <a href="javascript:;" class="orgAdd" onClick="LoadWindow2()"><?=_("选择")?></a>
    </td>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("应聘人姓名：")?></td>
    <td class="TableData" width="180" colspan="2">
    	<input type="text" name="EMPLOYEE_NAME"class="BigInput validate[required]"  data-prompt-position="centerRight:0,-8"id="EMPLOYEE_NAME" value="">
    </td>
    <td class="TableData" rowspan="7" colspan="1" align="center">
<?
   if($PHOTO_NAME=="")
      echo "<center>"._("暂无照片")."</center>";
   else
      echo "<A border=0 href=\"#\"><img src='recruit_pic.php?PHOTO_NAME=$PHOTO_NAME' width='150' border=0></A>";
?>
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"><font color="red">*</font><?=_("性别：")?></td>
   <td class="TableData">
    <select name="EMPLOYEE_SEX" class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
         <option value="0"><?=_("男")?></option>
         <option value="1"><?=_("女")?></option>
     </select>
   </td>
   <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("出生日期：")?></td>
    <td class="TableData" width="180" colspan="2">
       <input type="text" name="EMPLOYEE_BIRTH" size="10" maxlength="10" class="BigInput validate[required]"  data-prompt-position="centerRight:0,-8" value="<?=$EMPLOYEE_BIRTH=="0000-00-00"?"":$EMPLOYEE_BIRTH;?>" onClick="WdatePicker()" onBlur="checkDate()"/>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("民族：")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="EMPLOYEE_NATIONALITY" id="EMPLOYEE_NATIONALITY" class="BigInput" value="">
    </td>
    <td nowrap class="TableData" width="100"><?=_("现居住城市：")?></td>
    <td class="TableData" width="180" colspan="2">
    	<input type="text" name="RESIDENCE_PLACE" id="RESIDENCE_PLACE" class="BigInput" value="">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("联系电话：")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="EMPLOYEE_PHONE" id="EMPLOYEE_PHONE" class="BigInput validate[required]"  data-prompt-position="centerRight:0,-8">
    </td>
    <td nowrap class="TableData" ><font color="red">*</font>E_mail<?=_("：")?></td>
     <td class="TableData"  width="180" colspan="2">
     	<input type="text" name="EMPLOYEE_EMAIL" id="EMPLOYEE_EMAIL" class="BigInput validate[required,custom[email]]" data-prompt-position="centerRight:0,-6">
     </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("籍贯：")?></td>
    <td class="TableData">
    	<select name="EMPLOYEE_NATIVE_PLACE" class="BigSelect">
<?=hrms_code_list("AREA",$EMPLOYEE_NATIVE_PLACE);?>
      </select>
      <input type="text" name="EMPLOYEE_NATIVE_PLACE2" size=10 class="BigInput">
    </td>
     <td nowrap class="TableData" width="100"><?=_("户口所在地：")?></td>
    <td class="TableData"  width="180" colspan="2">
    	<input type="text" name="EMPLOYEE_DOMICILE_PLACE" id="EMPLOYEE_DOMICILE_PLACE" size="35" class="BigInput">
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("婚姻状况：")?></td>
    <td class="TableData">
      <select name="EMPLOYEE_MARITAL_STATUS" class="BigSelect">
        <option value=""></option>
        <option value="0"><?=_("未婚")?>&nbsp;&nbsp;</option>
        <option value="1"><?=_("已婚")?></option>
        <option value="2"><?=_("离异")?></option>
        <option value="3"><?=_("丧偶")?></option>
      </select>
    </td>
    <td nowrap class="TableData"><?=_("政治面貌：")?></td>
    <td class="TableData" colspan="2">
        <select name="EMPLOYEE_POLITICAL_STATUS" class="BigSelect">
          <option value=""><?=_("政治面貌")?></option>
          <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$EMPLOYEE_POLITICAL_STATUS);?>
        </select>
    </td>
  </tr>
  <tr>
  	<td nowrap class="TableData" width="100"><?=_("健康状况：")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="EMPLOYEE_HEALTH" id="EMPLOYEE_HEALTH" class="BigInput" value=<?=$EMPLOYEE_HEALTH?>>
    </td>
     <td nowrap class="TableData" width="100"><?=_("参加工作时间：")?></td>
    <td class="TableData"  width="180" colspan="2">
      <input type="text" name="JOB_BEGINNING" size="13" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING?>" onClick="WdatePicker()"/>
    </td>
  </tr>
 <tr>
  <td nowrap class="TableData"><font color="red">*</font><?=_("期望工作性质：")?></td>
    <td class="TableData">
    	<select name="JOB_CATEGORY" class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
<?=hrms_code_list("JOB_CATEGORY","");?>
       </select>
    </td>
<?
if($PHOTO_NAME=="")
   $PHOTO_STR=_("照片上传：");
else
   $PHOTO_STR=_("照片更改：");
?>
    <td nowrap class="TableData" width="100"><?=$PHOTO_STR?></td>
    <td class="TableData"  width="180" colspan="3">
       <input type="file" name="ATTACHMENT" size="40"  class="BigInput" title="<?=_("选择附件文件")?>" >
<?
if($PHOTO_NAME!="")
{
?>
      <br><a href=#this onClick="delete_photo();"><?=_("删除照片")?></a>
<?
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("期望从事行业：")?></td>
    <td class="TableData">
    	<input type="text" name="JOB_INDUSTRY" id="JOB_INDUSTRY" class="BigInput">
    </td>
    <td nowrap class="TableData"><?=_("期望从事职业：")?></td>
    <td class="TableData">
    	<input type="text" name="JOB_INTENSION" id="JOB_INTENSION" class="BigInput">
    </td>
    <td nowrap class="TableData"><?=_("期望工作城市：")?></td>
    <td class="TableData">
    	<input type="text" name="WORK_CITY" id="WORK_CITY" class="BigInput">
    </td>
  </tr>
    <tr>
    <td nowrap class="TableData" width="110"><font color="red">*</font><?=_("期望薪水(税前)：")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="EXPECTED_SALARY" id="EXPECTED_SALARY" size="10" class="BigInput validate[required]" data-prompt-position="centerRight:14,-6">&nbsp;<?=_("元")?>
    </td>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("岗位：")?></td>
    <td class="TableData">
     <select name="POSITION" class="BigInput validate[required]" data-prompt-position="centerRight:14,-6">
			<option value=""><?=_("岗位")?></option>
		  <?=hrms_code_list("POOL_POSITION","");?>
     </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("到岗时间：")?></td>
    <td class="TableData"  width="180">
      <select name="START_WORKING" class="BigSelect">
        <option value="" <? if($START_WORKING=="") echo "selected";?>></option>
        <option value="0" <? if($START_WORKING==0) echo "selected";?>>1<?=_("周以内")?></option>
        <option value="1" <? if($START_WORKING==1) echo "selected";?>>1<?=_("个月内")?></option>
        <option value="2" <? if($START_WORKING==2) echo "selected";?>>1~3<?=_("个月")?></option>
        <option value="3" <? if($START_WORKING==3) echo "selected";?>>3<?=_("个月后")?></option>
        <option value="4" <? if($START_WORKING==4) echo "selected";?>><?=_("随时到岗")?></option>
      </select>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("毕业时间：")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="GRADUATION_DATE" size="10" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE?>" onClick="WdatePicker()"/>
    </td>
    <td nowrap class="TableData" width="100"><?=_("毕业学校：")?></td>
    <td class="TableData"  width="180" colspan="3">
    	<input type="text" name="GRADUATION_SCHOOL" size="60" id="GRADUATION_SCHOOL" class="BigInput">
    </td>
  </tr>
    <tr>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("所学专业：")?></td>
    <td class="TableData"  width="180">
    	<select name="EMPLOYEE_MAJOR" class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
			<option value=""><?=_("所学专业")?></option>
		  <?=hrms_code_list("POOL_EMPLOYEE_MAJOR","");?>
     </select>
    </td>
    <td nowrap class="TableData" width="100"><font color="red">*</font><?=_("学历：")?></td>
    <td class="TableData"  width="180">
        <select name="EMPLOYEE_HIGHEST_SCHOOL" class="BigSelect validate[required]"  data-prompt-position="centerRight:0,-8">
        	<option value=""><?=_("学历")?></option>
					<?=hrms_code_list("STAFF_HIGHEST_SCHOOL",6);?>
        </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("学位：")?></td>
    <td class="TableData"  width="180">
    	<select name="EMPLOYEE_HIGHEST_DEGREE" class="BigSelect">
    		<option value=""><?=_("学位")?></option>
			  <?=hrms_code_list("EMPLOYEE_HIGHEST_DEGREE",6);?>
      </select>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("外语语种")?>1<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE1" id="FOREIGN_LANGUAGE1" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语语种")?>2<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE2" id="FOREIGN_LANGUAGE2" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语语种")?>3<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE3" id="FOREIGN_LANGUAGE3" class="BigInput"></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("外语水平")?>1<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL1" id="FOREIGN_LEVEL1" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语水平")?>2<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL2" id="FOREIGN_LEVEL2" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语水平")?>3<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL3" id="FOREIGN_LEVEL3" class="BigInput"></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("计算机水平：")?></td>
    <td class="TableData"  width="180">
    	<input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" class="BigInput">
    </td>
    
    <!---<td nowrap class="TableData"><?=_("年龄：")?></td>
    <td class="TableData" width="180">
    	<input type="text" name="EMPLOYEE_AGE" id="EMPLOYEE_AGE" size="8" class="BigInput">&nbsp;<?=_("岁")?>
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
    <td nowrap class="TableData"><?=_("年龄：")?></td>
    <td class="TableData"><input type="text" name="STAFF_AGE" size="4" class="BigInput" value="<?=$STAFF_AGE?>" readonly><?=_("岁")?></td>
    
    
    <td nowrap class="TableData"><?=_("招聘渠道：")?></td>
    <td class="TableData" width="180">
    	<select name="RECRU_CHANNEL" style="background: white;" title="<?=_("员工关怀类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>"> 
				<option value=""></option>
			  <?=hrms_code_list("PLAN_DITCH","")?> 
				</select> 
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("特长：")?></td>
    <td class="TableData"  width="180" colspan="5">
    	<textarea name="EMPLOYEE_SKILLS" cols="100" rows="3" class="BigInput" value=""><?=$EMPLOYEE_SKILLS?></textarea>
   </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("职业技能：")?></td>
    <td class="TableData"  width="180" colspan="5">
    	<textarea name="CAREER_SKILLS" cols="100" rows="3" class="BigInput" value=""><?=$CAREER_SKILLS?></textarea>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("工作经验：")?></td>
    <td class="TableData"  width="180" colspan="5">
    	<textarea name="WORK_EXPERIENCE" cols="100" rows="3" class="BigInput" value=""><?=$WORK_EXPERIENCE?></textarea>
   </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("项目经验：")?></td>
    <td class="TableData"  width="180" colspan="5">
    	<textarea name="PROJECT_EXPERIENCE" cols="100" rows="3" class="BigInput" value=""><?=$PROJECT_EXPERIENCE?></textarea>
   </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("备注：")?></td>
    <td class="TableData"  width="180" colspan="5"><textarea name="REMARK" cols="100" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("附件简历：")?></b></td>
  </tr>
  <tr>
    <td class="TableData" colspan="6"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);?></td>
  </tr>
  <tr height="25">
    <td nowrap class="TableData"><?=_("附件选择：")?></td>
    <td class="TableData" colspan="6">
       <script>ShowAddFile('1');</script>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" colspan="6"><?=_("简历：")?></td>
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
     <input type="submit" value="<?=_("保存")?>" class="BigButton" >
    </td>
  </tr>
</table>
<form>
</body>
</html>
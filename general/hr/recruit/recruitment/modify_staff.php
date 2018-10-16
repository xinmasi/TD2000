<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");



$query="select * from HR_STAFF_INFO where USER_ID='$OA_NAME'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   if($DEPT_ID!=0)
      $STAFF_DEPT_NAME=substr(GetDeptNameById($DEPT_ID),0,-1);
   else
      $STAFF_DEPT_NAME=_("离职人员");
   $STAFF_NO=$ROW["STAFF_NO"];
   $WORK_NO=$ROW["WORK_NO"];
   $WORK_TYPE=$ROW["WORK_TYPE"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $STAFF_E_NAME=$ROW["STAFF_E_NAME"];
   $STAFF_CARD_NO=$ROW["STAFF_CARD_NO"];   
   $STAFF_SEX=$ROW["STAFF_SEX"];
   $STAFF_BIRTH=$ROW["STAFF_BIRTH"];
   $STAFF_AGE=$ROW["STAFF_AGE"];
   $STAFF_NATIVE_PLACE=$ROW["STAFF_NATIVE_PLACE"];
   $STAFF_NATIVE_PLACE2=$ROW["STAFF_NATIVE_PLACE2"];
   $STAFF_DOMICILE_PLACE=$ROW["STAFF_DOMICILE_PLACE"];
   $STAFF_NATIONALITY=$ROW["STAFF_NATIONALITY"];
   $STAFF_MARITAL_STATUS=$ROW["STAFF_MARITAL_STATUS"];
   $STAFF_POLITICAL_STATUS=$ROW["STAFF_POLITICAL_STATUS"];
   $PHOTO_NAME=$ROW["PHOTO_NAME"];
   $COMPUTER_LEVEL=$ROW["COMPUTER_LEVEL"];   
   $JOIN_PARTY_TIME=$ROW["JOIN_PARTY_TIME"];
   $STAFF_PHONE=$ROW["STAFF_PHONE"];
   $STAFF_MOBILE=$ROW["STAFF_MOBILE"];
   $STAFF_LITTLE_SMART=$ROW["STAFF_LITTLE_SMART"];
   $STAFF_EMAIL=$ROW["STAFF_EMAIL"];   
   $STAFF_MSN=$ROW["STAFF_MSN"];
   $JOB_POSITION=$ROW["JOB_POSITION"];  
   $STAFF_QQ=$ROW["STAFF_QQ"];
   $HOME_ADDRESS=$ROW["HOME_ADDRESS"];
   $OTHER_CONTACT=$ROW["OTHER_CONTACT"];
   $JOB_BEGINNING=$ROW["JOB_BEGINNING"];
   $WORK_AGE=$ROW["WORK_AGE"];
   $BEGIN_SALSRY_TIME=$ROW["BEGIN_SALSRY_TIME"];
   $STAFF_HEALTH=$ROW["STAFF_HEALTH"];
   $STAFF_HIGHEST_SCHOOL=$ROW["STAFF_HIGHEST_SCHOOL"];
   $STAFF_HIGHEST_DEGREE=$ROW["STAFF_HIGHEST_DEGREE"];
   $GRADUATION_DATE=$ROW["GRADUATION_DATE"];
   $GRADUATION_SCHOOL=$ROW["GRADUATION_SCHOOL"];
   $STAFF_MAJOR=$ROW["STAFF_MAJOR"];
   $FOREIGN_LANGUAGE1=$ROW["FOREIGN_LANGUAGE1"];
   $FOREIGN_LEVEL1=$ROW["FOREIGN_LEVEL1"];
   $FOREIGN_LANGUAGE2=$ROW["FOREIGN_LANGUAGE2"];
   $FOREIGN_LEVEL2=$ROW["FOREIGN_LEVEL2"];
   $FOREIGN_LANGUAGE3=$ROW["FOREIGN_LANGUAGE3"];
   $FOREIGN_LEVEL3=$ROW["FOREIGN_LEVEL3"];
   $STAFF_SKILLS=$ROW["STAFF_SKILLS"];

   $STAFF_OCCUPATION=$ROW["STAFF_OCCUPATION"];
   $ADMINISTRATION_LEVEL=$ROW["ADMINISTRATION_LEVEL"];
   $PRESENT_POSITION=$ROW["PRESENT_POSITION"];
   $DATES_EMPLOYED=$ROW["DATES_EMPLOYED"];
   $JOB_AGE=$ROW["JOB_AGE"];
   $STAFF_CS=$ROW["STAFF_CS"];
   $WORK_STATUS=$ROW["WORK_STATUS"];
   $STAFF_CTR=$ROW["STAFF_CTR"];
   
   $REMARK=$ROW["REMARK"];
   $STAFF_COMPANY=$ROW["STAFF_COMPANY"];
   $RESUME=$ROW["RESUME"];
   
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"]; 
}

$HTML_PAGE_TITLE = _("更改人事档案新建");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
   form1.submit();
}

function get_work_age(str)
{
   var today=new Date();
   var during = today.getFullYear() - str.substring(0,4);
   if(during > 0)
      document.form1.WORK_AGE.value= during;
   else
   	  document.form1.WORK_AGE.value = 0;
}

function get_job_age(str)
{
   var today=new Date();
   var during = today.getFullYear() - str.substring(0,4);
   if(during > 0)
      document.form1.JOB_AGE.value= during;
   else
   	  document.form1.JOB_AGE.value = 0;
}
</script>

<body class="bodycolor">

<table border="0" width="770" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("更改人事档案")?></span>&nbsp;&nbsp;</td>
  </tr>
</table>

<form enctype="multipart/form-data" action="update_staff.php" method="post" name="form1">
<table class="TableBlock" width="770" align="center">
  <tr>
    <td nowrap class="TableData" width="100">OA<?=_("用户名：")?></td>
    <td class="TableData" width="180">
      <input type="text" name="USER_ID" value="<?=$OA_NAME?>" class="BigStatic" readonly >   	
    </td>
    <td nowrap class="TableData" width="100"><?=_("编号：")?></td>
    <td class="TableData" width="180" colspan="2"><input type="text" name="STAFF_NO" value="<?=$STAFF_NO?>" class="BigInput"></td>  
    <td class="TableData" align="center" rowspan="7" colspan="2">
<?
   if($PHOTO_NAME=="")
      echo "<center>"._("暂无照片")."</center>";
   else
      echo "<A border=0 href=\"#\"><img src='hrms_pic.php?PHOTO_NAME=$PHOTO_NAME' width='150' border=0></A>";
?>    
    </td>           
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("姓名：")?></td>
    <td class="TableData">
    	<input type="text" name="STAFF_NAME" value="<?=$STAFF_NAME?>" class="BigInput">
    </td>  	
    <td nowrap class="TableData"><?=_("工号：")?></td>
    <td class="TableData" colspan="2"><input type="text" name="WORK_NO" value="<?=$WORK_NO?>" class="BigInput"></td>          
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("英文名：")?></td>
    <td class="TableData"><input type="text" name="STAFF_E_NAME" value="<?=$STAFF_E_NAME?>" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("身份证号：")?></td>
    <td class="TableData" colspan="2"><input type="text" name="STAFF_CARD_NO" value="<?=$STAFF_CARD_NO?>" class="BigInput"></td>           
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("性别：")?></td>
    <td class="TableData">
    	<select name="STAFF_SEX" class="BigSelect">
          <option value="0" <? if($STAFF_SEX=="0") echo "selected";?>><?=_("男")?></option>
          <option value="1" <? if($STAFF_SEX=="1") echo "selected";?>><?=_("女")?></option>
      </select>
    </td>
    <td nowrap class="TableData"><?=_("出生日期：")?></td>
    <td class="TableData" colspan="2"><input type="text" name="STAFF_BIRTH" size="10" maxlength="10" class="BigInput" value="<?=$STAFF_BIRTH?>" onClick="WdatePicker()"/></td>             
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("年龄：")?></td>
    <td class="TableData"><input type="text" name="STAFF_AGE" size="8" class="BigInput" value="<?=$STAFF_AGE?>"></td>
    <td nowrap class="TableData"><?=_("籍贯：")?></td>
    <td class="TableData" colspan="2">
    	<select name="STAFF_NATIVE_PLACE" class="BigSelect">
<?=hrms_code_list("AREA",$STAFF_NATIVE_PLACE);?>
      </select>
      <input type="text" name="STAFF_NATIVE_PLACE2" style="height: 24px;" value="<?=$STAFF_NATIVE_PLACE2?>" > 
    </td>             
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("异地户口：")?></td>
    <td class="TableData">
    	<input type="checkbox" name="YES_OTHER_P" <? if($YES_OTHER_P==1) echo "checked=";?>><?=_("是")?>
    </td>
    <td nowrap class="TableData"><?=_("民族：")?></td>
    <td class="TableData" colspan="2"><input type="text" name="STAFF_NATIONALITY" class="BigInput" value="<?=$STAFF_NATIONALITY?>"></td>            
  </tr>  
  <tr>
    <td nowrap class="TableData"><?=_("婚姻状况：")?></td>
    <td class="TableData">
      <select name="STAFF_MARITAL_STATUS" class="BigSelect">
        <option value="" <? if($STAFF_MARITAL_STATUS=="") echo "selected";?>></option>
        <option value="0" <? if($STAFF_MARITAL_STATUS=="0") echo "selected";?>><?=_("未婚")?></option>
        <option value="1" <? if($STAFF_MARITAL_STATUS=="1") echo "selected";?>><?=_("已婚")?></option>
        <option value="2" <? if($STAFF_MARITAL_STATUS=="2") echo "selected";?>><?=_("离异")?></option>
        <option value="3" <? if($STAFF_MARITAL_STATUS=="3") echo "selected";?>><?=_("丧偶")?></option>
      </select>    	
    </td>
    <td nowrap class="TableData"><?=_("政治面貌：")?></td>
    <td class="TableData" colspan="2">
        <select name="STAFF_POLITICAL_STATUS" class="BigSelect">
          <option value="" <? if($STAFF_POLITICAL_STATUS=="") echo "selected";?>></option>
          <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$STAFF_POLITICAL_STATUS); ?>
        </select>    	
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("在职状态：")?></td>
    <td class="TableData"  width="180">
    	<select name="WORK_STATUS" class="BigSelect">
        	<option value="" <? if($WORK_STATUS=="") echo "selected";?>></option>
        	<?=hrms_code_list("WORK_STATUS",$WORK_STATUS);?>
      </select>    	
    </td>
    <td nowrap class="TableData" width="100"><?=_("照片：")?></td>
    <td class="TableData"  width="180" colspan="3">
       <input type="file" name="ATTACHMENT" size="40"  class="BigInput" title="<?=_("选择附件文件")?>" >
    </td>                 
  </tr> 
  <tr>
    <td nowrap class="TableData" width="100"><?=_("入党时间：")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="JOIN_PARTY_TIME" size="10" maxlength="10" class="BigInput" value="<?=$JOIN_PARTY_TIME?>" onClick="WdatePicker()"/>
    </td>
    <td nowrap class="TableData" width="100"><?=_("联系电话：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_PHONE" class="BigInput" value="<?=$STAFF_PHONE?>"></td>
    <td nowrap class="TableData" width="100"><?=_("手机号码：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_MOBILE" class="BigInput" value="<?=$STAFF_MOBILE?>"></td>                 
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("小灵通：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_LITTLE_SMART" class="BigInput" value="<?=$STAFF_LITTLE_SMART?>"></td>
    <td nowrap class="TableData" width="100">MSN<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_MSN" class="BigInput" value="<?=$STAFF_MSN?>"></td>
    <td nowrap class="TableData" width="100">QQ<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_QQ" class="BigInput" value="<?=$STAFF_QQ?>"></td>                 
  </tr>   
  
  <tr>
    <td nowrap class="TableData" width="100"><?=_("电子邮件：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_EMAIL" class="BigInput" value="<?=$STAFF_EMAIL?>"></td>
    <td nowrap class="TableData" width="100"><?=_("家庭地址：")?></td>
    <td class="TableData"  width="180" colspan="3"><input type="text" name="HOME_ADDRESS" size="50" value="<?=$HOME_ADDRESS?>" class="BigInput"></td>                
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("参加工作时间：")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="JOB_BEGINNING" size="10" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING?>" onClick="WdatePicker()" onchange="get_work_age(this.value)"/>   	
    </td>
    <td nowrap class="TableData" width="100"><?=_("其他联系方式：")?></td>
    <td class="TableData"  width="180" colspan="3"><input type="text" name="OTHER_CONTACT" size="50" value="<?=$OTHER_CONTACT?>" class="BigInput"></td>                
  </tr>        
  <tr>
    <td nowrap class="TableData" width="100"><?=_("总工龄：")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_AGE" value="<?=$WORK_AGE?>" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("健康状况：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_HEALTH" value="<?=$STAFF_HEALTH?>" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("户口所在地")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_DOMICILE_PLACE" value="<?=$STAFF_DOMICILE_PLACE?>"  class="BigInput"></td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("学历：")?></td>
    <td class="TableData"  width="180">
        <select name="STAFF_HIGHEST_SCHOOL" class="BigSelect">
<?=hrms_code_list("STAFF_HIGHEST_SCHOOL","$STAFF_HIGHEST_SCHOOL");?>		
        </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("学位：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_HIGHEST_DEGREE" value="<?=$STAFF_HIGHEST_DEGREE?>" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("毕业时间：")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="GRADUATION_DATE" size="10" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE?>" onClick="WdatePicker()"/>   
    </td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("毕业学校：")?></td>
    <td class="TableData"  width="180"><input type="text" name="GRADUATION_SCHOOL" value="<?=$GRADUATION_SCHOOL?>" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("专业：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_MAJOR" value="<?=$STAFF_MAJOR?>" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("计算机水平：")?></td>
    <td class="TableData"  width="180"><input type="text" name="COMPUTER_LEVEL" value="<?=$COMPUTER_LEVEL?>" class="BigInput"></td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("外语语种")?>1<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE1" value="<?=$FOREIGN_LANGUAGE1?>" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语语种")?>2<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE2" value="<?=$FOREIGN_LANGUAGE2?>" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语语种")?>3<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE3" value="<?=$FOREIGN_LANGUAGE3?>" class="BigInput"></td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("外语水平")?>1<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL1" value="<?=$FOREIGN_LEVEL1?>" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语水平")?>2<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL2" value="<?=$FOREIGN_LEVEL2?>" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语水平")?>3<?=_("：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL3" value="<?=$FOREIGN_LEVEL3?>" class="BigInput"></td>                 
  </tr>  
  <tr>
    <td nowrap class="TableData" width="100"><?=_("特长：")?></td>
    <td class="TableData"  width="180" colspan="5"><input type="text" name="STAFF_SKILLS" value="<?=$STAFF_SKILLS?>" size="80" class="BigInput"></td>             
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("部门：")?></td>
    <td class="TableData"  width="180">
    	<input type="hidden" name="STAFF_DEPT" value="<?=$DEPT_ID?>">
      <input type="text" name="STAFF_DEPT_NAME" value="<?=$STAFF_DEPT_NAME?>" class=BigStatic size=12 readonly>
      <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingleHrms('','STAFF_DEPT','STAFF_DEPT_NAME')"><?=_("选择")?></a>
    </td>
    <td nowrap class="TableData" width="100"><?=_("工种：")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_TYPE" class="BigInput" value="<?=$WORK_TYPE?>"></td>
    <td nowrap class="TableData" width="100"><?=_("行政级别：")?></td>
    <td class="TableData"  width="180"><input type="text" name="ADMINISTRATION_LEVEL" class="BigInput" value="<?=$ADMINISTRATION_LEVEL?>"></td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("职务：")?></td>
    <td class="TableData"  width="180"><input type="text" name="JOB_POSITION" class="BigInput" value="<?=$JOB_POSITION?>"></td>
    <td nowrap class="TableData" width="100"><?=_("职称：")?></td>
    <td class="TableData"  width="180">
        <select name="PRESENT_POSITION" class="BigSelect">
<?=hrms_code_list("PRESENT_POSITION",$PRESENT_POSITION);?>
        </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("入职时间：")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="DATES_EMPLOYED" size="10" maxlength="10" class="BigInput" value="<?=$DATES_EMPLOYED?>" onClick="WdatePicker()"  onchange="get_job_age(this.value)"/>   	
    </td>                 
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("本单位工龄")?></td>
    <td class="TableData"  width="180"><input type="text" name="JOB_AGE" class="BigInput" value="<?=$JOB_AGE?>"></td>
    <td nowrap class="TableData" width="100"><?=_("起薪时间")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="BEGIN_SALSRY_TIME" size="10" maxlength="10" class="BigInput" value="<?=$BEGIN_SALSRY_TIME?>" onClick="WdatePicker()"/>
    </td>
    <td nowrap class="TableData" width="100"><?=_("员工类型")?></td>
    <td class="TableData">
        <select name="STAFF_OCCUPATION" class="BigSelect">
<?=hrms_code_list("STAFF_OCCUPATION",$STAFF_OCCUPATION);?>
        </select>    	
    </td>                 
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("备注：")?></td> 
    <td nowrap class="TableData" colspan="5"><textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""><?=$REMARK?></textarea></td>               
  </tr>         
  <tr>
    <td nowrap class="TableData" colspan="6"><?=_("简历")?></td>                
  </tr>       
  <tr>
    <td nowrap class="TableData" colspan="6">
<?
$editor = new Editor('RESUME') ;
$editor->Height = '450';
$editor->Value = $RESUME ;
$editor->Config = array('model_type' => '14') ;
$editor->Create() ;
?>    
    </td>                 
  </tr>  
  <tr align="center" class="TableControl">
    <td colspan=6 nowrap>
     <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="CheckForm();">
    </td>
  </tr>             
</table>
<form>
</body>
</html>
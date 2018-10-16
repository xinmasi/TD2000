<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("编辑人事档案");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
   if (document.form1.ATTACHMENT.value!="")
   {
     var file_temp=document.form1.ATTACHMENT.value,ext_name;
     var user_id = document.form1.USER_ID.value;
     var Pos;
     Pos=file_temp.lastIndexOf(".");
     ext_name=file_temp.substring(Pos,file_temp.length);
     document.form1.ATTACHMENT_NAME.value = user_id + ext_name;
   }

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
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建人事档案")?></span>&nbsp;&nbsp;</td>
  </tr>
</table>

<form enctype="multipart/form-data" action="update.php" method="post" name="form1">
<table class="TableBlock" width="770" align="center">
  <tr>
    <td nowrap class="TableData" width="100">OA<?=_("用户名：")?></td>
    <td class="TableData" width="180">
      <input type="text" name="USER_ID" value="<?=$USER_ID?>">   	
    </td>
    <td nowrap class="TableData" width="100"><?=_("编号：")?></td>
    <td class="TableData" width="180"><input type="text" name="STAFF_NO" id="STAFF_NO" class="BigInput"></td>  
    <td class="TableData" width="180" rowspan="7" colspan="2"></td>           
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("姓名：")?></td>
    <td class="TableData">
    	<input type="text" name="STAFF_NAME" id="STAFF_NAME" class="BigInput">
    </td>  	
    <td nowrap class="TableData"><?=_("工号：")?></td>
    <td class="TableData"><input type="text" name="WORK_NO" id="WORK_NO" class="BigInput"></td>          
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("英文名：")?></td>
    <td class="TableData"><input type="text" name="STAFF_E_NAME" id="STAFF_E_NAME" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("身份证号：")?></td>
    <td class="TableData"><input type="text" name="STAFF_CARD_NO" id="STAFF_CARD_NO" class="BigInput"></td>           
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("性别：")?></td>
    <td class="TableData">
    	<select name="STAFF_SEX" class="BigSelect">
          <option value="0"><?=_("男")?></option>
          <option value="1"><?=_("女")?></option>
      </select>
    </td>
    <td nowrap class="TableData"><?=_("出生日期：")?></td>
    <td class="TableData">
      <input type="text" name="STAFF_BIRTH" size="10" maxlength="10" class="BigInput" value="<?=$STAFF_BIRTH?>" onClick="WdatePicker()"/>
    </td>             
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("年龄：")?></td>
    <td class="TableData"><input type="text" name="STAFF_AGE" id="STAFF_AGE" size="8" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("籍贯：")?></td>
    <td class="TableData" nowrap>
    	<select name="STAFF_NATIVE_PLACE" class="BigSelect">
<?=hrms_code_list("AREA",$STAFF_NATIVE_PLACE);?>
      </select>
      <input type="text" name="STAFF_NATIVE_PLACE2" style="height: 24px;" >  
    </td>             
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("异地户口：")?></td>
    <td class="TableData">
    	<input type="checkbox" name="YES_OTHER_P"><?=_("是")?>
    </td>
    <td nowrap class="TableData"><?=_("民族：")?></td>
    <td class="TableData"><input type="text" name="STAFF_NATIONALITY" id="STAFF_NATIONALITY" class="BigInput"></td>            
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
    <td class="TableData">
        <select name="STAFF_POLITICAL_STATUS" class="BigSelect">
          <option value="" <? if($POLITICS=="") echo "selected";?>></option>
          <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$STAFF_POLITICAL_STATUS); ?>
        </select>    	
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("在职状态：")?></td>
    <td class="TableData"  width="180">
    	<select name="WORK_STATUS" class="BigSelect">
        	<option value=""></option>
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
    <td class="TableData"  width="180"><input type="text" name="STAFF_PHONE" id="STAFF_PHONE" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("手机号码：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_MOBILE" id="STAFF_MOBILE" class="BigInput"></td>                 
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("小灵通：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_LITTLE_SMART" id="STAFF_LITTLE_SMART" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("MSN：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_MSN" id="STAFF_MSN" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("QQ：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_QQ" id="STAFF_QQ" class="BigInput"></td>                 
  </tr>   
  
  <tr>
    <td nowrap class="TableData" width="100"><?=_("电子邮件：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_EMAIL" id="STAFF_EMAIL" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("家庭地址：")?></td>
    <td class="TableData"  width="180" colspan="3"><input type="text" name="HOME_ADDRESS" size="50" id="HOME_ADDRESS" class="BigInput"></td>                
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("参加工作时间：")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="JOB_BEGINNING" size="10" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING?>" onClick="WdatePicker()" onchange="get_work_age(this.value)"/>
    </td>
    <td nowrap class="TableData" width="100"><?=_("其他联系方式：")?></td>
    <td class="TableData"  width="180" colspan="3"><input type="text" name="OTHER_CONTACT" size="50"  id="OTHER_CONTACT" class="BigInput"></td>                
  </tr>        
  <tr>
    <td nowrap class="TableData" width="100"><?=_("总工龄：")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_AGE" id="WORK_AGE" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("健康状况：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_HEALTH" id="STAFF_HEALTH" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("户口所在地")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_DOMICILE_PLACE" id="STAFF_DOMICILE_PLACE" class="BigInput"></td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("学历：")?></td>
    <td class="TableData"  width="180">
        <select name="STAFF_HIGHEST_SCHOOL" class="BigSelect">
<?=hrms_code_list("STAFF_HIGHEST_SCHOOL",6);?>		
        </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("学位：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_HIGHEST_DEGREE" id="STAFF_HIGHEST_DEGREE" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("毕业时间：")?></td>
    <td class="TableData"  width="180">
      <input type="text" name="GRADUATION_DATE" size="10" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE?>" onClick="WdatePicker()"/>
   
    </td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("毕业学校：")?></td>
    <td class="TableData"  width="180"><input type="text" name="GRADUATION_SCHOOL" id="GRADUATION_SCHOOL" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("专业：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_MAJOR" id="STAFF_MAJOR" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("计算机水平：")?></td>
    <td class="TableData"  width="180"><input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" class="BigInput"></td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("外语语种1：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE1" id="FOREIGN_LANGUAGE1" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语语种2：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE2" id="FOREIGN_LANGUAGE2" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语语种3：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE3" id="FOREIGN_LANGUAGE3" class="BigInput"></td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("外语水平1：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL1" id="FOREIGN_LEVEL1" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语水平2：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL2" id="FOREIGN_LEVEL2" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("外语水平3：")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL3" id="FOREIGN_LEVEL3" class="BigInput"></td>                 
  </tr>  
  <tr>
    <td nowrap class="TableData" width="100"><?=_("特长：")?></td>
    <td class="TableData"  width="180" colspan="5"><input type="text" name="STAFF_SKILLS" size="80" id="STAFF_SKILLS" class="BigInput"></td>             
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("部门：")?></td>
    <td class="TableData"  width="180">
    	<input type="hidden" name="DEPT_ID" value="">
      <input type="text" name="DEPT_NAME" value="" class=BigStatic size=12 maxlength=100 readonly>
      <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','DEPT_ID','DEPT_NAME')"><?=_("选择")?></a>
    </td>
    <td nowrap class="TableData" width="100"><?=_("工种：")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_TYPE" id="WORK_TYPE" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("行政级别：")?></td>
    <td class="TableData"  width="180"><input type="text" name="ADMINISTRATION_LEVEL" id="ADMINISTRATION_LEVEL" class="BigInput"></td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("职务：")?></td>
    <td class="TableData"  width="180"><input type="text" name="JOB_POSITION" id="JOB_POSITION" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("职称：")?></td>
    <td class="TableData"  width="180">
        <select name="PRESENT_POSITION" class="BigSelect">
<?=hrms_code_list("PRESENT_POSITION",$TECH_POST);?>
        </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("入职时间：")?></td>
    <td class="TableData"  width="180">
       <input type="text" name="DATES_EMPLOYED" size="10" maxlength="10" class="BigInput" value="<?=$DATES_EMPLOYED?>" onClick="WdatePicker()"  onchange="get_job_age(this.value)"/>	
    </td>                 
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("本单位工龄")?></td>
    <td class="TableData"  width="180"><input type="text" name="JOB_AGE" id="JOB_AGE" class="BigInput"></td>
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
    <td nowrap class="TableData" colspan="5"><textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""></textarea></td>               
  </tr>         
  <tr>
    <td nowrap class="TableData" colspan="6"><?=_("简历")?></td>                
  </tr>       
  <tr>
    <td class="TableData" colspan="6">
<?
$editor = new Editor('RESUME') ;
$editor->Height = '450';
$editor->Value = $RESUME ;
//$editor->Config = array("EditorAreaStyles" => "body{font-size:12pt;}");
$editor->Config = array('model_type' => '14') ;
$editor->Create() ;
?>    
    </td>                 
  </tr>  
  <tr align="center" class="TableControl">
    <td colspan=6 nowrap>
     <input type="hidden" value="" name="ATTACHMENT_NAME">
     <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="CheckForm();">
    </td>
  </tr>             
</table>
<form>
</body>
</html>
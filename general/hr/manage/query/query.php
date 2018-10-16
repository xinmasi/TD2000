<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("人事档案查询");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<link rel="stylesheet"type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script> 
jQuery(document).ready(function(){      
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});  
</script>
<script type="text/javascript">
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

</script>


<body class="bodycolor">
  
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("人事档案查询")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" action="#"  method="POST" name="form1" id="form1" >
<table class="TableBlock" width="770" align="center">
  <tr>
    <td nowrap class="TableHeader" colspan="6">
     <img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> &nbsp;<?=_("查询范围")?>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"> <?=_("所属部门：")?></td>
    <td class="TableData" colspan="3">
     <input type="hidden" name="TO_ID">
     <textarea cols=35 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly></textarea>
     <!--缺少相应参数，增加参数-->
     <a href="javascript:;" class="orgAdd" onClick="SelectDept('9','TO_ID','TO_NAME')"><?=_("添加")?></a>
     <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
    </td>
    <td nowrap class="TableData"> <?=_("角色：")?></td>
    <td class="TableData" width="180">
       <select name="USER_PRIV" class="BigSelect">
        <option value=""></option>
<?
      $query = "SELECT * from USER_PRIV order by PRIV_NO desc";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $USER_PRIV1=$ROW["USER_PRIV"];
         $PRIV_NAME=$ROW["PRIV_NAME"];

?>
          <option value="<?=$USER_PRIV1?>"><?=$PRIV_NAME?></option>
<?
      }
?>
        </select>
      </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("姓名：")?></td>
    <td class="TableData"><input type="text" name="STAFF_NAME" id="STAFF_NAME" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("曾用名：")?></td>
    <td class="TableData"><input type="text" name="BEFORE_NAME" id="BEFORE_NAME" class="BigInput"></td>   
    <td nowrap class="TableData"><?=_("英文名：")?></td>
    <td class="TableData"><input type="text" name="STAFF_E_NAME" id="STAFF_E_NAME" class="BigInput"></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("编号：")?></td>
    <td class="TableData" width="180"><input type="text" name="STAFF_NO" id="STAFF_NO" class="BigInput validate[custom[number],maxSize[6]]" data-prompt-position="centerRight:-5,-6"></td>     
    <td nowrap class="TableData"><?=_("工号：")?></td>
    <td class="TableData"><input type="text" name="WORK_NO" id="WORK_NO" class="BigInput validate[custom[number],maxSize[6]]" data-prompt-position="centerRight:-5,-6"></td> 
    <td nowrap class="TableData"><?=_("性别：")?></td>
    <td class="TableData">
      <select name="STAFF_SEX" class="BigSelect">
       <option ></option>
       <option value="0"><?=_("男")?></option>
       <option value="1"><?=_("女")?></option>
      </select>
    </td>      
  </tr>
  <tr>    
    <td nowrap class="TableData"><?=_("身份证号：")?></td>
    <td class="TableData"><input type="text" name="STAFF_CARD_NO" id="STAFF_CARD_NO" class="BigInput validate[maxSize[22]]" data-prompt-position="centerRight:-5,-6"></td>
    <td nowrap class="TableData"><?=_("出生日期：")?></td>
    <td class="TableData" nowrap colspan="3">
      <input type="text" name="BIRTHDAY_MIN" size="10" maxlength="10" class="BigInput " id="birth_start_time" value="<?=$BIRTHDAY_MIN?>" onClick="WdatePicker()"/>
      <?=_("至")?>
      <input type="text" name="BIRTHDAY_MAX" size="10" maxlength="10" class="BigInput" value="<?=$BIRTHDAY_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'birth_start_time\')}'})"/>
      <?=_("日期格式形如")?> 1999-1-2
    </td>    
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("年龄：")?></td>
    <td class="TableData" nowrap>
      <input type="text" name="AGE_MIN" size="5" maxlength="10" class="BigInput" value="">
      <?=_("至")?> <input type="text" name="AGE_MAX" size="5" maxlength="10" class="BigInput" value="">
    </td>
    <td nowrap class="TableData"><?=_("民族：")?></td>
    <td class="TableData"><input type="text" name="STAFF_NATIONALITY" id="STAFF_NATIONALITY" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("籍贯：")?></td>
    <td class="TableData">
      <select name="STAFF_NATIVE_PLACE" class="BigSelect">
        <option ></option>
        <?=hrms_code_list("AREA",$STAFF_NATIVE_PLACE);?>
      </select>
    </td>         
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("工种：")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_TYPE" id="WORK_TYPE" class="BigInput"></td> 
    <td nowrap class="TableData" width="100"><?=_("在职状态：")?></td>
    <td class="TableData"  width="180" colspan="3">
      <select name="WORK_STATUS" class="BigSelect">
        <option value=""></option>
        <?=hrms_code_list("WORK_STATUS",$WORK_STATUS);?>
      </select>     
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("户口所在地")?></td>
    <td class="TableData"  width="280" colspan="5"><input type="text" name="STAFF_DOMICILE_PLACE" id="STAFF_DOMICILE_PLACE" size="40" class="BigInput"></td>              
  </tr>  
  <tr>
    <td nowrap class="TableData"><?=_("婚姻状况：")?></td>
    <td class="TableData">
      <select name="STAFF_MARITAL_STATUS" class="BigSelect">
        <option value=""></option>    
        <option value="0"><?=_("未婚")?></option>
        <option value="1"><?=_("已婚")?></option>
        <option value="2"><?=_("离异")?></option>
        <option value="3"><?=_("丧偶")?></option>
      </select>     
    </td>
    <td nowrap class="TableData" width="100"><?=_("健康状况：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_HEALTH" id="STAFF_HEALTH" class="BigInput"></td>                         
    <td nowrap class="TableData"><?=_("政治面貌：")?></td>
    <td class="TableData">
        <select name="STAFF_POLITICAL_STATUS" class="BigSelect">
          <option value="" <? if($POLITICS=="") echo "selected";?>></option>
          <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$STAFF_POLITICAL_STATUS); ?>
        </select>   
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("行政级别：")?></td>
    <td class="TableData"  width="180"><input type="text" name="ADMINISTRATION_LEVEL" id="ADMINISTRATION_LEVEL" class="BigInput"></td>
    <td nowrap class="TableData" width="100"  ><?=_("员工类型：")?></td>
    <td class="TableData">
        <select name="STAFF_OCCUPATION" class="BigSelect">
          <option ></option>
<?=hrms_code_list("STAFF_OCCUPATION",$STAFF_OCCUPATION);?>
        </select>     
    </td>          
    <td nowrap class="TableData" width="100"><?=_("计算机水平：")?></td>
    <td class="TableData"  width="180"><input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" class="BigInput"></td>                  
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("学历：")?></td>
    <td class="TableData"  width="180">
      <select name="STAFF_HIGHEST_SCHOOL" class="BigSelect">
        <option ></option>
        <?=hrms_code_list("STAFF_HIGHEST_SCHOOL");?>    
      </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("学位：")?></td>
    <td class="TableData"  width="180">
      <select name="STAFF_HIGHEST_DEGREE" class="BigSelect">
        <option value=""></option>
        <?=hrms_code_list("EMPLOYEE_HIGHEST_DEGREE","");?>    
      </select>
    </td>   
    <td nowrap class="TableData" width="100"><?=_("专业：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_MAJOR" id="STAFF_MAJOR" class="BigInput"></td>                   
  </tr>     
  <tr>
    <td nowrap class="TableData" width="100"><?=_("毕业学校：")?></td>
    <td class="TableData"  width="180"><input type="text" name="GRADUATION_SCHOOL" id="GRADUATION_SCHOOL" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("职务：")?></td>
    <td class="TableData"  width="180"><input type="text" name="JOB_POSITION" id="JOB_POSITION" class="BigInput"></td>               
    <td nowrap class="TableData" width="100"><?=_("职称：")?></td>
    <td class="TableData"  width="180">
        <select name="PRESENT_POSITION" class="BigSelect">
          <option ></option>
<?=hrms_code_list("PRESENT_POSITION",$TECH_POST);?>
        </select>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("毕业时间：")?></td>
    <td class="TableData" nowrap colspan="2">
      <input type="text" name="GRADUATION_MIN" size="10" maxlength="10" class="BigInput" id="graduate_start_time" value="<?=$GRADUATION_MIN?>" onClick="WdatePicker()"/>
      <?=_("至")?> 
      <input type="text" name="GRADUATION_MAX" size="10" maxlength="10" class="BigInput" value="<?=$GRADUATION_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'graduate_start_time\')}'})"/>   
    </td> 
    <td nowrap class="TableData" width="100"><?=_("入党时间：")?></td>
    <td class="TableData"  nowrap colspan="2">
     <input type="text" name="JOIN_PARTY_MIN" size="10" maxlength="10" class="BigInput" id="party_start_time" value="<?=$JOIN_PARTY_MIN?>" onClick="WdatePicker()"/>  
      <?=_("至")?>
      <input type="text" name="JOIN_PARTY_MAX" size="10" maxlength="10" class="BigInput" value="<?=$JOIN_PARTY_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'party_start_time\')}'})"/>  
    </td> 
  </tr>        
  <tr>
    <td nowrap class="TableData" width="100"><?=_("参加工作时间：")?></td>
    <td class="TableData"  nowrap colspan="2">
      <input type="text" name="BEGINNING_MIN" size="10" maxlength="10" class="BigInput" id="work_start_time" value="<?=$BEGINNING_MIN?>" onClick="WdatePicker()"/> 
      <?=_("至")?>
      <input type="text" name="BEGINNING_MAX" size="10" maxlength="10" class="BigInput" value="<?=$BEGINNING_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'work_start_time\')}'})"/>      
    </td>
    <td nowrap class="TableData" width="100"><?=_("入职时间：")?></td>
    <td class="TableData" nowrap colspan="2">
      <input type="text" name="EMPLOYED_MIN" size="10" maxlength="10" class="BigInput" id="position_start_time" value="<?=$EMPLOYED_MIN?>" onClick="WdatePicker()"/>  
      <?=_("至")?> 
      <input type="text" name="EMPLOYED_MAX" size="10" maxlength="10" class="BigInput" value="<?=$EMPLOYED_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'position_start_time\')}'})"/>      
    </td>      
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("总工龄：")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_AGE_MIN" size="5" class="BigInput"> <?=_("至")?> <input type="text" name="WORK_AGE_MAX" size="5" class="BigInput"></td>    
    <td nowrap class="TableData" width="100"><?=_("本单位工龄：")?></td>
    <td class="TableData"  width="180"><input type="text" name="JOB_AGE_MIN" size="5" class="BigInput"> <?=_("至")?> <input type="text" name="JOB_AGE_MAX" size="5" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("年休假：")?></td>
    <td class="TableData"><input type="text" name="LEAVE_TYPE_MIN" size="5" class="BigInput"> <?=_("至")?> <input type="text" name="LEAVE_TYPE_MAX" size="5" class="BigInput"></td>                    
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
    <td colspan="6" >
     <?=get_field_table(get_field_html("HR_STAFF_INFO","","1"))?>
    </td>
  </tr>
  <tr align="center" class="TableControl">
      <td colspan="6" nowrap>
        <input type="hidden" value="<?=$is_leave?>" name="is_leave" />
        <input type="submit" value="<?=_("查询")?>" class="BigButton" onClick="search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("导出")?>" class="BigButton" onClick="exreport()">
      </td>
   </tr>          
</table>
</form>

</table>
</body>
</html>
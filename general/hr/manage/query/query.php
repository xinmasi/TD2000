<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("���µ�����ѯ");
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
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("���µ�����ѯ")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" action="#"  method="POST" name="form1" id="form1" >
<table class="TableBlock" width="770" align="center">
  <tr>
    <td nowrap class="TableHeader" colspan="6">
     <img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> &nbsp;<?=_("��ѯ��Χ")?>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"> <?=_("�������ţ�")?></td>
    <td class="TableData" colspan="3">
     <input type="hidden" name="TO_ID">
     <textarea cols=35 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly></textarea>
     <!--ȱ����Ӧ���������Ӳ���-->
     <a href="javascript:;" class="orgAdd" onClick="SelectDept('9','TO_ID','TO_NAME')"><?=_("���")?></a>
     <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
    </td>
    <td nowrap class="TableData"> <?=_("��ɫ��")?></td>
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
    <td nowrap class="TableData"><?=_("������")?></td>
    <td class="TableData"><input type="text" name="STAFF_NAME" id="STAFF_NAME" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("��������")?></td>
    <td class="TableData"><input type="text" name="BEFORE_NAME" id="BEFORE_NAME" class="BigInput"></td>   
    <td nowrap class="TableData"><?=_("Ӣ������")?></td>
    <td class="TableData"><input type="text" name="STAFF_E_NAME" id="STAFF_E_NAME" class="BigInput"></td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��ţ�")?></td>
    <td class="TableData" width="180"><input type="text" name="STAFF_NO" id="STAFF_NO" class="BigInput validate[custom[number],maxSize[6]]" data-prompt-position="centerRight:-5,-6"></td>     
    <td nowrap class="TableData"><?=_("���ţ�")?></td>
    <td class="TableData"><input type="text" name="WORK_NO" id="WORK_NO" class="BigInput validate[custom[number],maxSize[6]]" data-prompt-position="centerRight:-5,-6"></td> 
    <td nowrap class="TableData"><?=_("�Ա�")?></td>
    <td class="TableData">
      <select name="STAFF_SEX" class="BigSelect">
       <option ></option>
       <option value="0"><?=_("��")?></option>
       <option value="1"><?=_("Ů")?></option>
      </select>
    </td>      
  </tr>
  <tr>    
    <td nowrap class="TableData"><?=_("���֤�ţ�")?></td>
    <td class="TableData"><input type="text" name="STAFF_CARD_NO" id="STAFF_CARD_NO" class="BigInput validate[maxSize[22]]" data-prompt-position="centerRight:-5,-6"></td>
    <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
    <td class="TableData" nowrap colspan="3">
      <input type="text" name="BIRTHDAY_MIN" size="10" maxlength="10" class="BigInput " id="birth_start_time" value="<?=$BIRTHDAY_MIN?>" onClick="WdatePicker()"/>
      <?=_("��")?>
      <input type="text" name="BIRTHDAY_MAX" size="10" maxlength="10" class="BigInput" value="<?=$BIRTHDAY_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'birth_start_time\')}'})"/>
      <?=_("���ڸ�ʽ����")?> 1999-1-2
    </td>    
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("���䣺")?></td>
    <td class="TableData" nowrap>
      <input type="text" name="AGE_MIN" size="5" maxlength="10" class="BigInput" value="">
      <?=_("��")?> <input type="text" name="AGE_MAX" size="5" maxlength="10" class="BigInput" value="">
    </td>
    <td nowrap class="TableData"><?=_("���壺")?></td>
    <td class="TableData"><input type="text" name="STAFF_NATIONALITY" id="STAFF_NATIONALITY" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("���᣺")?></td>
    <td class="TableData">
      <select name="STAFF_NATIVE_PLACE" class="BigSelect">
        <option ></option>
        <?=hrms_code_list("AREA",$STAFF_NATIVE_PLACE);?>
      </select>
    </td>         
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("���֣�")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_TYPE" id="WORK_TYPE" class="BigInput"></td> 
    <td nowrap class="TableData" width="100"><?=_("��ְ״̬��")?></td>
    <td class="TableData"  width="180" colspan="3">
      <select name="WORK_STATUS" class="BigSelect">
        <option value=""></option>
        <?=hrms_code_list("WORK_STATUS",$WORK_STATUS);?>
      </select>     
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("�������ڵ�")?></td>
    <td class="TableData"  width="280" colspan="5"><input type="text" name="STAFF_DOMICILE_PLACE" id="STAFF_DOMICILE_PLACE" size="40" class="BigInput"></td>              
  </tr>  
  <tr>
    <td nowrap class="TableData"><?=_("����״����")?></td>
    <td class="TableData">
      <select name="STAFF_MARITAL_STATUS" class="BigSelect">
        <option value=""></option>    
        <option value="0"><?=_("δ��")?></option>
        <option value="1"><?=_("�ѻ�")?></option>
        <option value="2"><?=_("����")?></option>
        <option value="3"><?=_("ɥż")?></option>
      </select>     
    </td>
    <td nowrap class="TableData" width="100"><?=_("����״����")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_HEALTH" id="STAFF_HEALTH" class="BigInput"></td>                         
    <td nowrap class="TableData"><?=_("������ò��")?></td>
    <td class="TableData">
        <select name="STAFF_POLITICAL_STATUS" class="BigSelect">
          <option value="" <? if($POLITICS=="") echo "selected";?>></option>
          <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$STAFF_POLITICAL_STATUS); ?>
        </select>   
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��������")?></td>
    <td class="TableData"  width="180"><input type="text" name="ADMINISTRATION_LEVEL" id="ADMINISTRATION_LEVEL" class="BigInput"></td>
    <td nowrap class="TableData" width="100"  ><?=_("Ա�����ͣ�")?></td>
    <td class="TableData">
        <select name="STAFF_OCCUPATION" class="BigSelect">
          <option ></option>
<?=hrms_code_list("STAFF_OCCUPATION",$STAFF_OCCUPATION);?>
        </select>     
    </td>          
    <td nowrap class="TableData" width="100"><?=_("�����ˮƽ��")?></td>
    <td class="TableData"  width="180"><input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" class="BigInput"></td>                  
  </tr>   
  <tr>
    <td nowrap class="TableData" width="100"><?=_("ѧ����")?></td>
    <td class="TableData"  width="180">
      <select name="STAFF_HIGHEST_SCHOOL" class="BigSelect">
        <option ></option>
        <?=hrms_code_list("STAFF_HIGHEST_SCHOOL");?>    
      </select>
    </td>
    <td nowrap class="TableData" width="100"><?=_("ѧλ��")?></td>
    <td class="TableData"  width="180">
      <select name="STAFF_HIGHEST_DEGREE" class="BigSelect">
        <option value=""></option>
        <?=hrms_code_list("EMPLOYEE_HIGHEST_DEGREE","");?>    
      </select>
    </td>   
    <td nowrap class="TableData" width="100"><?=_("רҵ��")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_MAJOR" id="STAFF_MAJOR" class="BigInput"></td>                   
  </tr>     
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��ҵѧУ��")?></td>
    <td class="TableData"  width="180"><input type="text" name="GRADUATION_SCHOOL" id="GRADUATION_SCHOOL" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("ְ��")?></td>
    <td class="TableData"  width="180"><input type="text" name="JOB_POSITION" id="JOB_POSITION" class="BigInput"></td>               
    <td nowrap class="TableData" width="100"><?=_("ְ�ƣ�")?></td>
    <td class="TableData"  width="180">
        <select name="PRESENT_POSITION" class="BigSelect">
          <option ></option>
<?=hrms_code_list("PRESENT_POSITION",$TECH_POST);?>
        </select>
  </tr>
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��ҵʱ�䣺")?></td>
    <td class="TableData" nowrap colspan="2">
      <input type="text" name="GRADUATION_MIN" size="10" maxlength="10" class="BigInput" id="graduate_start_time" value="<?=$GRADUATION_MIN?>" onClick="WdatePicker()"/>
      <?=_("��")?> 
      <input type="text" name="GRADUATION_MAX" size="10" maxlength="10" class="BigInput" value="<?=$GRADUATION_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'graduate_start_time\')}'})"/>   
    </td> 
    <td nowrap class="TableData" width="100"><?=_("�뵳ʱ�䣺")?></td>
    <td class="TableData"  nowrap colspan="2">
     <input type="text" name="JOIN_PARTY_MIN" size="10" maxlength="10" class="BigInput" id="party_start_time" value="<?=$JOIN_PARTY_MIN?>" onClick="WdatePicker()"/>  
      <?=_("��")?>
      <input type="text" name="JOIN_PARTY_MAX" size="10" maxlength="10" class="BigInput" value="<?=$JOIN_PARTY_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'party_start_time\')}'})"/>  
    </td> 
  </tr>        
  <tr>
    <td nowrap class="TableData" width="100"><?=_("�μӹ���ʱ�䣺")?></td>
    <td class="TableData"  nowrap colspan="2">
      <input type="text" name="BEGINNING_MIN" size="10" maxlength="10" class="BigInput" id="work_start_time" value="<?=$BEGINNING_MIN?>" onClick="WdatePicker()"/> 
      <?=_("��")?>
      <input type="text" name="BEGINNING_MAX" size="10" maxlength="10" class="BigInput" value="<?=$BEGINNING_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'work_start_time\')}'})"/>      
    </td>
    <td nowrap class="TableData" width="100"><?=_("��ְʱ�䣺")?></td>
    <td class="TableData" nowrap colspan="2">
      <input type="text" name="EMPLOYED_MIN" size="10" maxlength="10" class="BigInput" id="position_start_time" value="<?=$EMPLOYED_MIN?>" onClick="WdatePicker()"/>  
      <?=_("��")?> 
      <input type="text" name="EMPLOYED_MAX" size="10" maxlength="10" class="BigInput" value="<?=$EMPLOYED_MAX?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'position_start_time\')}'})"/>      
    </td>      
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("�ܹ��䣺")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_AGE_MIN" size="5" class="BigInput"> <?=_("��")?> <input type="text" name="WORK_AGE_MAX" size="5" class="BigInput"></td>    
    <td nowrap class="TableData" width="100"><?=_("����λ���䣺")?></td>
    <td class="TableData"  width="180"><input type="text" name="JOB_AGE_MIN" size="5" class="BigInput"> <?=_("��")?> <input type="text" name="JOB_AGE_MAX" size="5" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("���ݼ٣�")?></td>
    <td class="TableData"><input type="text" name="LEAVE_TYPE_MIN" size="5" class="BigInput"> <?=_("��")?> <input type="text" name="LEAVE_TYPE_MAX" size="5" class="BigInput"></td>                    
  </tr>        
  <tr>
    <td nowrap class="TableData" width="100"><?=_("��������1��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE1" id="FOREIGN_LANGUAGE1" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("��������2��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE2" id="FOREIGN_LANGUAGE2" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("��������3��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE3" id="FOREIGN_LANGUAGE3" class="BigInput"></td>                 
  </tr>       
  <tr>
    <td nowrap class="TableData" width="100"><?=_("����ˮƽ1��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL1" id="FOREIGN_LEVEL1" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("����ˮƽ2��")?></td>
    <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL2" id="FOREIGN_LEVEL2" class="BigInput"></td>
    <td nowrap class="TableData" width="100"><?=_("����ˮƽ3��")?></td>
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
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton" onClick="search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="exreport()">
      </td>
   </tr>          
</table>
</form>

</table>
</body>
</html>
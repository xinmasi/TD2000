<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("���µ�����ѯ");
include_once("inc/header.inc.php");

?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
function ControlContent06()
{
   if(contentid06.style.display == 'none'){
   	  contentid06.style.display = '';
      document.getElementById("imgar").src = '<?=MYOA_STATIC_SERVER?>/static/images/arrow_up.gif';
   }else{
   	 contentid06.style.display = 'none';
   	 document.getElementById("imgar").src = '<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif';
   }	
}
</script>

<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("���µ�����ѯ")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1" >
<table class="TableBlock" width="820" align="center">
  <tr>
    <td nowrap class="TableData" width="100">OA<?=_("�û�����")?></td>
    <td class="TableData" width="180"><input type="text" name="USER_ID" value="<?=$USER_ID?>"></td>
    <td nowrap class="TableData"><?=_("������")?></td>
    <td class="TableData"><input type="text" name="STAFF_NAME" id="STAFF_NAME" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("��������")?></td>
    <td class="TableData"><input type="text" name="BEFORE_NAME" id="BEFORE_NAME" class="BigInput"></td> 	           
  </tr>
  <tr>

    <td nowrap class="TableData" width="100"><?=_("��ţ�")?></td>
    <td class="TableData" width="180"><input type="text" name="STAFF_NO" id="STAFF_NO" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("Ӣ������")?></td>
    <td class="TableData"><input type="text" name="STAFF_E_NAME" id="STAFF_E_NAME" class="BigInput"></td> 
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
    <td class="TableData"><input type="text" name="STAFF_CARD_NO" id="STAFF_CARD_NO" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
    <td class="TableData" colspan="3"><input type="text" name="STAFF_BIRTH1" size="10" id="birth_start_time" maxlength="10" class="BigInput" value="<?=$STAFF_BIRTH1?>" onClick="WdatePicker()"/> <?=_("��")?>
    <input type="text" name="STAFF_BIRTH2" size="10" maxlength="10" class="BigInput" value="<?=$STAFF_BIRTH2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'birth_start_time\')}'})"></td>
  </tr>
  <tr>
   <td nowrap class="TableData" width="100"  ><?=_("Ա�����ͣ�")?></td>
   <td class="TableData">
        <select name="STAFF_OCCUPATION" class="BigSelect">
        	<option ></option>
<?=hrms_code_list("STAFF_OCCUPATION",$STAFF_OCCUPATION);?>
        </select>    	
		</td>           
		<td nowrap class="TableData" width="100"><?=_("��ְʱ�䣺")?></td>
    <td class="TableData" colspan="3"><input type="text" name="DATES_EMPLOYED1" size="10" maxlength="10" class="BigInput" id="position_start_time" value="<?=$DATES_EMPLOYED1?>" onClick="WdatePicker()"/> <?=_("��")?>
    	<input type="text" name="DATES_EMPLOYED2" size="10" maxlength="10" class="BigInput" value="<?=$DATES_EMPLOYED2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'position_start_time\')}'})"/>
    </td>
  </tr>
  <tr> 
    <td nowrap class="TableData"><?=_("���ţ�")?></td>
    <td class="TableData"><input type="text" name="WORK_NO" id="WORK_NO" class="BigInput"></td> 
    <td nowrap class="TableData" width="100"><?=_("���֣�")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_TYPE" id="WORK_TYPE" class="BigInput"></td>   
    <td nowrap class="TableData" width="100"><?=_("���ţ�")?></td>
    <td class="TableData"  width="180">
    	<input type="hidden" name="DEPT_ID">
      <input type="text" name="DEPT_NAME" value="" class=BigStatic size=12 maxlength=100 readonly>
      <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','DEPT_ID','DEPT_NAME')"><?=_("ѡ��")?></a>
    </td>        
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
  	<td nowrap class="TableData"><?=_("���壺")?></td>
    <td class="TableData"><input type="text" name="STAFF_NATIONALITY" id="STAFF_NATIONALITY" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("���᣺")?></td>
    <td class="TableData">
    	<select name="STAFF_NATIVE_PLACE" class="BigSelect">
    		<option ></option>
				<?=hrms_code_list("AREA",$STAFF_NATIVE_PLACE);?>
      </select>
    </td>
    <td nowrap class="TableData"><?=_("����״����")?></td>
    <td class="TableData">
      <select name="STAFF_MARITAL_STATUS" class="BigSelect">
      	<option value="" <? if($STAFF_MARITAL_STATUS=="") echo "selected";?>></option>    
        <option value="0" <? if($STAFF_MARITAL_STATUS=="0") echo "selected";?>><?=_("δ��")?></option>
        <option value="1" <? if($STAFF_MARITAL_STATUS=="1") echo "selected";?>><?=_("�ѻ�")?></option>
        <option value="2" <? if($STAFF_MARITAL_STATUS=="2") echo "selected";?>><?=_("����")?></option>
        <option value="3" <? if($STAFF_MARITAL_STATUS=="3") echo "selected";?>><?=_("ɥż")?></option>
      </select>    	
    </td>                   
  </tr>
  <tr>

		<td nowrap class="TableData"><?=_("������ò��")?></td>
    <td class="TableData">
        <select name="STAFF_POLITICAL_STATUS" class="BigSelect">
          <option value="" <? if($POLITICS=="") echo "selected";?>></option>
          <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$STAFF_POLITICAL_STATUS); ?>
        </select>
    </td>          
    
    <td nowrap class="TableData" width="100"><?=_("�뵳ʱ�䣺")?></td>
    <td class="TableData" colspan="1" nowrap>
    	<input type="text" name="JOIN_PARTY_TIME1" size="7.5" maxlength="10" class="BigInput" value="<?=$JOIN_PARTY_TIME1?>" id="party_start_time" onClick="WdatePicker()"/> <?=_("��")?>
    	<input type="text" name="JOIN_PARTY_TIME2" size="7.5" maxlength="10" class="BigInput" value="<?=$JOIN_PARTY_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'party_start_time\')}'})"/>
    </td>
    
    <td nowrap class="TableData" width="100"><?=_("��ְ״̬��")?></td>
    <td class="TableData"  width="180" colspan="1">
    	<select name="WORK_STATUS" class="BigSelect">
        <option value=""></option>
        <?=hrms_code_list("WORK_STATUS",$WORK_STATUS);?>
      </select>    	
    </td>  
  </tr>  
  <tr>    
    <td nowrap class="TableData" width="100"><?=_("��ϵ�绰��")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_PHONE" id="STAFF_PHONE" class="BigInput"></td>
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
       <td nowrap class="TableData"><?=_("��ʾ��ְ��Ա��")?></td>
    <td class="TableData">
      <select name="SHOW_LEAVE" class="BigSelect">
      	<option value="" <? //if($SHOW_LEAVE=="") echo "selected";?>></option>    
        <option value="0" <? //if($SHOW_LEAVE=="0") echo "selected";?>><?=_("��ʾ")?></option>
        <option value="1" selected <? //if($SHOW_LEAVE=="1") echo "selected";?>><?=_("����ʾ")?></option>
      </select>    	
    </td>     
  </tr>   
  <tr class="TableHeader" height="25">
    <td nowrap colspan="6"><span><?=_("�����ѯѡ��")?></span>&nbsp;&nbsp;<img src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" border="0" id="imgar" title="<?=_("չ��/����")?>" onClick="ControlContent06();" style="cursor:hand"></td>
  </tr>
  <tr  style="display:none"  id="contentid06">
  	<td colspan="6" valign="top">
			<div>
				<table class="TableBlock" align="center">
  				<tr>
				    
				    <td nowrap class="TableData" width="100"><?=_("��������")?></td>
				    <td class="TableData"  width="180"><input type="text" name="ADMINISTRATION_LEVEL" id="ADMINISTRATION_LEVEL" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("�ֻ����룺")?></td>
            		    <td class="TableData"  width="180"><input type="text" name="STAFF_MOBILE" id="STAFF_MOBILE" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("�����ʼ���")?></td>
           		    <td class="TableData"  width="180"><input type="text" name="STAFF_EMAIL" id="STAFF_EMAIL" class="BigInput"></td>				                    
				  </tr>   
				  <tr>
				    <td nowrap class="TableData" width="100"><?=_("�س���")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_SKILLS" size="" id="STAFF_SKILLS" class="BigInput"></td>
				    <!--<td nowrap class="TableData" width="100"><?=_("С��ͨ��")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_LITTLE_SMART" id="STAFF_LITTLE_SMART" class="BigInput"></td>-->
				    <td nowrap class="TableData" width="100"><?=_("MSN��")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_MSN" id="STAFF_MSN" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("QQ��")?></td>
            		    <td class="TableData"  width="180"><input type="text" name="STAFF_QQ" id="STAFF_QQ" class="BigInput"></td>  				                   
				  </tr>   				  
				  <tr>				    
				    <td nowrap class="TableData" width="100"><?=_("��ͥ��ַ��")?></td>
				    <td class="TableData"  width="180"><input type="text" name="HOME_ADDRESS" id="HOME_ADDRESS" class="BigInput"></td> 
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
				    <td nowrap class="TableData" width="100"><?=_("�μӹ���ʱ�䣺")?></td>
				    <td class="TableData">  	
				     <input type="text" name="JOB_BEGINNING1" size="7.5" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING1?>" onClick="WdatePicker()"/> <?=_("��")?>
    					<input type="text" name="JOB_BEGINNING2" size="7.5" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING2?>" onClick="WdatePicker()"/>
				    </td>
				    <td nowrap class="TableData" width="100"><?=_("��ҵʱ�䣺")?></td>
				    <td class="TableData" >
				     <input type="text" name="GRADUATION_DATE1" size="7.5" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE1?>" onClick="WdatePicker()"/> <?=_("��")?>
    					<input type="text" name="GRADUATION_DATE2" size="7.5" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE2?>" onClick="WdatePicker()"/>
				    </td> 
				    <td nowrap class="TableData" width="100"><?=_("����λ���䣺")?></td>
                        <td class="TableData"  width="180"><input type="text" name="JOB_AGE" id="JOB_AGE" class="BigInput"></td>   	  
				  </tr>        
				  <tr>
				    <td nowrap class="TableData" width="100"><?=_("�ܹ��䣺")?></td>
				    <td class="TableData"  width="180"><input type="text" name="WORK_AGE" id="WORK_AGE" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("����״����")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_HEALTH" id="STAFF_HEALTH" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("�������ڵأ�")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_DOMICILE_PLACE" id="STAFF_DOMICILE_PLACE" class="BigInput"></td>                 
				  </tr>       
				  <tr>
				    <td nowrap class="TableData" width="100"><?=_("��ҵѧУ��")?></td>
				    <td class="TableData"  width="180"><input type="text" name="GRADUATION_SCHOOL" id="GRADUATION_SCHOOL" class="BigInput"></td>				    
				    <td nowrap class="TableData" width="100"><?=_("�����ˮƽ��")?></td>
				    <td class="TableData"  width="180"><input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("��нʱ�䣺")?></td>
				    <td class="TableData"  width="180">
				     <input type="text" name="BEGIN_SALSRY_TIME1" size="7.5" maxlength="10" class="BigInput" value="<?=$BEGIN_SALSRY_TIME1?>" onClick="WdatePicker()"/> <?=_("��")?>
    					<input type="text" name="BEGIN_SALSRY_TIME2" size="7.5" maxlength="10" class="BigInput" value="<?=$BEGIN_SALSRY_TIME2?>" onClick="WdatePicker()"/>				    	
				    </td>                 
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
				  <!--<tr>				    				    
				    <td nowrap class="TableData" width="100"><?=_("�س���")?></td>
				    <td class="TableData"  width="180" colspan="3"><input type="text" name="STAFF_SKILLS" size="60" id="STAFF_SKILLS" class="BigInput"></td>
				  </tr>  -->
          <tr>
            <td colspan="6" >
             <?=get_field_table(get_field_html("HR_STAFF_INFO","","1"))?>
            </td>
          </tr>         
			</table>
		</div>
	</td>	
  </tr> 
  <tr align="center" class="TableControl">
	  <td colspan="6" nowrap><input type="submit" value="<?=_("��ѯ")?>" class="BigButton">&nbsp;&nbsp;</td>
 </tr>          
</table>
</form>

</table>
</body>
</html>
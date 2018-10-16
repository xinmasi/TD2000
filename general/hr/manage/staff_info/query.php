<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("人事档案查询");
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
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("人事档案查询")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1" >
<table class="TableBlock" width="820" align="center">
  <tr>
    <td nowrap class="TableData" width="100">OA<?=_("用户名：")?></td>
    <td class="TableData" width="180"><input type="text" name="USER_ID" value="<?=$USER_ID?>"></td>
    <td nowrap class="TableData"><?=_("姓名：")?></td>
    <td class="TableData"><input type="text" name="STAFF_NAME" id="STAFF_NAME" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("曾用名：")?></td>
    <td class="TableData"><input type="text" name="BEFORE_NAME" id="BEFORE_NAME" class="BigInput"></td> 	           
  </tr>
  <tr>

    <td nowrap class="TableData" width="100"><?=_("编号：")?></td>
    <td class="TableData" width="180"><input type="text" name="STAFF_NO" id="STAFF_NO" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("英文名：")?></td>
    <td class="TableData"><input type="text" name="STAFF_E_NAME" id="STAFF_E_NAME" class="BigInput"></td> 
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
    <td class="TableData"><input type="text" name="STAFF_CARD_NO" id="STAFF_CARD_NO" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("出生日期：")?></td>
    <td class="TableData" colspan="3"><input type="text" name="STAFF_BIRTH1" size="10" id="birth_start_time" maxlength="10" class="BigInput" value="<?=$STAFF_BIRTH1?>" onClick="WdatePicker()"/> <?=_("到")?>
    <input type="text" name="STAFF_BIRTH2" size="10" maxlength="10" class="BigInput" value="<?=$STAFF_BIRTH2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'birth_start_time\')}'})"></td>
  </tr>
  <tr>
   <td nowrap class="TableData" width="100"  ><?=_("员工类型：")?></td>
   <td class="TableData">
        <select name="STAFF_OCCUPATION" class="BigSelect">
        	<option ></option>
<?=hrms_code_list("STAFF_OCCUPATION",$STAFF_OCCUPATION);?>
        </select>    	
		</td>           
		<td nowrap class="TableData" width="100"><?=_("入职时间：")?></td>
    <td class="TableData" colspan="3"><input type="text" name="DATES_EMPLOYED1" size="10" maxlength="10" class="BigInput" id="position_start_time" value="<?=$DATES_EMPLOYED1?>" onClick="WdatePicker()"/> <?=_("到")?>
    	<input type="text" name="DATES_EMPLOYED2" size="10" maxlength="10" class="BigInput" value="<?=$DATES_EMPLOYED2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'position_start_time\')}'})"/>
    </td>
  </tr>
  <tr> 
    <td nowrap class="TableData"><?=_("工号：")?></td>
    <td class="TableData"><input type="text" name="WORK_NO" id="WORK_NO" class="BigInput"></td> 
    <td nowrap class="TableData" width="100"><?=_("工种：")?></td>
    <td class="TableData"  width="180"><input type="text" name="WORK_TYPE" id="WORK_TYPE" class="BigInput"></td>   
    <td nowrap class="TableData" width="100"><?=_("部门：")?></td>
    <td class="TableData"  width="180">
    	<input type="hidden" name="DEPT_ID">
      <input type="text" name="DEPT_NAME" value="" class=BigStatic size=12 maxlength=100 readonly>
      <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','DEPT_ID','DEPT_NAME')"><?=_("选择")?></a>
    </td>        
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
  	<td nowrap class="TableData"><?=_("民族：")?></td>
    <td class="TableData"><input type="text" name="STAFF_NATIONALITY" id="STAFF_NATIONALITY" class="BigInput"></td>
    <td nowrap class="TableData"><?=_("籍贯：")?></td>
    <td class="TableData">
    	<select name="STAFF_NATIVE_PLACE" class="BigSelect">
    		<option ></option>
				<?=hrms_code_list("AREA",$STAFF_NATIVE_PLACE);?>
      </select>
    </td>
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
  </tr>
  <tr>

		<td nowrap class="TableData"><?=_("政治面貌：")?></td>
    <td class="TableData">
        <select name="STAFF_POLITICAL_STATUS" class="BigSelect">
          <option value="" <? if($POLITICS=="") echo "selected";?>></option>
          <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$STAFF_POLITICAL_STATUS); ?>
        </select>
    </td>          
    
    <td nowrap class="TableData" width="100"><?=_("入党时间：")?></td>
    <td class="TableData" colspan="1" nowrap>
    	<input type="text" name="JOIN_PARTY_TIME1" size="7.5" maxlength="10" class="BigInput" value="<?=$JOIN_PARTY_TIME1?>" id="party_start_time" onClick="WdatePicker()"/> <?=_("到")?>
    	<input type="text" name="JOIN_PARTY_TIME2" size="7.5" maxlength="10" class="BigInput" value="<?=$JOIN_PARTY_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'party_start_time\')}'})"/>
    </td>
    
    <td nowrap class="TableData" width="100"><?=_("在职状态：")?></td>
    <td class="TableData"  width="180" colspan="1">
    	<select name="WORK_STATUS" class="BigSelect">
        <option value=""></option>
        <?=hrms_code_list("WORK_STATUS",$WORK_STATUS);?>
      </select>    	
    </td>  
  </tr>  
  <tr>    
    <td nowrap class="TableData" width="100"><?=_("联系电话：")?></td>
    <td class="TableData"  width="180"><input type="text" name="STAFF_PHONE" id="STAFF_PHONE" class="BigInput"></td>
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
       <td nowrap class="TableData"><?=_("显示离职人员：")?></td>
    <td class="TableData">
      <select name="SHOW_LEAVE" class="BigSelect">
      	<option value="" <? //if($SHOW_LEAVE=="") echo "selected";?>></option>    
        <option value="0" <? //if($SHOW_LEAVE=="0") echo "selected";?>><?=_("显示")?></option>
        <option value="1" selected <? //if($SHOW_LEAVE=="1") echo "selected";?>><?=_("不显示")?></option>
      </select>    	
    </td>     
  </tr>   
  <tr class="TableHeader" height="25">
    <td nowrap colspan="6"><span><?=_("更多查询选项")?></span>&nbsp;&nbsp;<img src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" border="0" id="imgar" title="<?=_("展开/收缩")?>" onClick="ControlContent06();" style="cursor:hand"></td>
  </tr>
  <tr  style="display:none"  id="contentid06">
  	<td colspan="6" valign="top">
			<div>
				<table class="TableBlock" align="center">
  				<tr>
				    
				    <td nowrap class="TableData" width="100"><?=_("行政级别：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="ADMINISTRATION_LEVEL" id="ADMINISTRATION_LEVEL" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("手机号码：")?></td>
            		    <td class="TableData"  width="180"><input type="text" name="STAFF_MOBILE" id="STAFF_MOBILE" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("电子邮件：")?></td>
           		    <td class="TableData"  width="180"><input type="text" name="STAFF_EMAIL" id="STAFF_EMAIL" class="BigInput"></td>				                    
				  </tr>   
				  <tr>
				    <td nowrap class="TableData" width="100"><?=_("特长：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_SKILLS" size="" id="STAFF_SKILLS" class="BigInput"></td>
				    <!--<td nowrap class="TableData" width="100"><?=_("小灵通：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_LITTLE_SMART" id="STAFF_LITTLE_SMART" class="BigInput"></td>-->
				    <td nowrap class="TableData" width="100"><?=_("MSN：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_MSN" id="STAFF_MSN" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("QQ：")?></td>
            		    <td class="TableData"  width="180"><input type="text" name="STAFF_QQ" id="STAFF_QQ" class="BigInput"></td>  				                   
				  </tr>   				  
				  <tr>				    
				    <td nowrap class="TableData" width="100"><?=_("家庭地址：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="HOME_ADDRESS" id="HOME_ADDRESS" class="BigInput"></td> 
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
				    <td nowrap class="TableData" width="100"><?=_("参加工作时间：")?></td>
				    <td class="TableData">  	
				     <input type="text" name="JOB_BEGINNING1" size="7.5" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING1?>" onClick="WdatePicker()"/> <?=_("到")?>
    					<input type="text" name="JOB_BEGINNING2" size="7.5" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING2?>" onClick="WdatePicker()"/>
				    </td>
				    <td nowrap class="TableData" width="100"><?=_("毕业时间：")?></td>
				    <td class="TableData" >
				     <input type="text" name="GRADUATION_DATE1" size="7.5" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE1?>" onClick="WdatePicker()"/> <?=_("到")?>
    					<input type="text" name="GRADUATION_DATE2" size="7.5" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE2?>" onClick="WdatePicker()"/>
				    </td> 
				    <td nowrap class="TableData" width="100"><?=_("本单位工龄：")?></td>
                        <td class="TableData"  width="180"><input type="text" name="JOB_AGE" id="JOB_AGE" class="BigInput"></td>   	  
				  </tr>        
				  <tr>
				    <td nowrap class="TableData" width="100"><?=_("总工龄：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="WORK_AGE" id="WORK_AGE" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("健康状况：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_HEALTH" id="STAFF_HEALTH" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("户口所在地：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="STAFF_DOMICILE_PLACE" id="STAFF_DOMICILE_PLACE" class="BigInput"></td>                 
				  </tr>       
				  <tr>
				    <td nowrap class="TableData" width="100"><?=_("毕业学校：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="GRADUATION_SCHOOL" id="GRADUATION_SCHOOL" class="BigInput"></td>				    
				    <td nowrap class="TableData" width="100"><?=_("计算机水平：")?></td>
				    <td class="TableData"  width="180"><input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" class="BigInput"></td>
				    <td nowrap class="TableData" width="100"><?=_("起薪时间：")?></td>
				    <td class="TableData"  width="180">
				     <input type="text" name="BEGIN_SALSRY_TIME1" size="7.5" maxlength="10" class="BigInput" value="<?=$BEGIN_SALSRY_TIME1?>" onClick="WdatePicker()"/> <?=_("到")?>
    					<input type="text" name="BEGIN_SALSRY_TIME2" size="7.5" maxlength="10" class="BigInput" value="<?=$BEGIN_SALSRY_TIME2?>" onClick="WdatePicker()"/>				    	
				    </td>                 
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
				  <!--<tr>				    				    
				    <td nowrap class="TableData" width="100"><?=_("特长：")?></td>
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
	  <td colspan="6" nowrap><input type="submit" value="<?=_("查询")?>" class="BigButton">&nbsp;&nbsp;</td>
 </tr>          
</table>
</form>

</table>
</body>
</html>
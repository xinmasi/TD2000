<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$query="select DEPT_ID from USER where USER_ID='$USER_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $DEPT_ID=$ROW["DEPT_ID"];

$HTML_PAGE_TITLE = _("新建证照信息");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet"type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script> 
jQuery(document).ready(function(){      
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});  
</script>
<script Language="JavaScript">
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
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?LICENSE_ID=<?=$LICENSE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}

function expandIt() 
{
  whichEl =document.getElementById("menu");
  if (document.form1.EXPIRATION_PERIOD[0].checked == true) 
  {
    whichEl.style.display = '';
    document.getElementById("menu2").style.display ='';
    document.getElementById("menu3").style.display ='';
  }
  if (document.form1.EXPIRATION_PERIOD[1].checked == true)
  { 
  	whichEl.style.display = 'none'; 
    document.getElementById("menu2").style.display ='none';
    document.getElementById("menu3").style.display ='none';
  }
}
function resetTime()
{
   document.form1.REMIND_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
}

</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建证照信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" name="form1" id="form1">
<table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("单位员工：")?></td>
      <td class="TableData">       
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic validate[required]"  data-prompt-position="centerRight:60,-9" readonly value="">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>" >
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("选择")?></a>
      </td>
      <td nowrap class="TableData"><?=_("证照类型：")?></td>
      <td class="TableData" >
        <select name="LICENSE_TYPE" style="background: white;" title="<?=_("证照类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>" class="validate[required]" data-prompt-position="centerRight:0,-8">
          <option value=""><?=_("证照类型")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_LICENSE1","")?>
        </select>
      </td> 
    </tr>
    <tr>
    	 <td nowrap class="TableData"><?=_("证照编号：")?></td>
      <td class="TableData">
        <INPUT type="text"name="LICENSE_NO"  class="validate[required]" data-prompt-position="centerRight:0,-8" size="15" value="<?=$LICENSE_NO?>">
      </td>
    	<td nowrap class="TableData"><?=_("证照名称：")?></td>
      <td class="TableData">
       <INPUT type="text"name="LICENSE_NAME" class="validate[required]" data-prompt-position="centerRight:0,-8" size="15" value="<?=$LICENSE_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("取证日期：")?></td>
      <td class="TableData">
       <input type="text" name="GET_LICENSE_DATE" id="evidence_time" size="15" maxlength="10" class="BigInput" value="<?=$GET_LICENSE_DATE?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"><?=_("生效日期：")?></td>
      <td class="TableData">
       <input type="text" name="EFFECTIVE_DATE" id="start_time" size="15" maxlength="10" class="BigInput" value="<?=$EFFECTIVE_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'evidence_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("状态：")?></td>
      <td class="TableData">
        <select name="STATUS" style="background: white;" title="<?=_("状态可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("状态")?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_LICENSE2","")?>
        </select>
      </td>
      <td nowrap class="TableData"><?=_("期限限制：")?></td>
      <td class="TableData">
      	<INPUT type="radio" name="EXPIRATION_PERIOD" value="1" onClick="expandIt()"> <?=_("是")?>&nbsp;&nbsp;  
			  <INPUT type="radio" name="EXPIRATION_PERIOD" value="0" onClick="expandIt()" checked> <?=_("否")?> 
      </td> 
    </tr>
    <tr>
      <!--<td nowrap class="TableData"><?=_("证照岗位：")?></td>
      <td class="TableData">
        <select name="LICENSE_POST" style="background: white;" title="<?=_("状态可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("证照岗位")?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_POST_LICENSE","")?>
        </select>
      </td>-->
      <td nowrap class="TableData"><?=_("部门：")?></td>
      <td class="TableData" colspan=3>
      	<input type="hidden" name="LICENSE_DEPT" value="<?=$DEPT_ID?>">
        
        <input type="text" name="LICENSE_DEPT_NAME" value="<?=substr(GetDeptNameById($DEPT_ID),0,-1)?>" class=BigStatic size=15 maxlength=100 readonly>
         <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','LICENSE_DEPT','LICENSE_DEPT_NAME')"><?=_("选择")?></a>      
      </td> 
    </tr>
    <tr style="display:none" id="menu">
    	<td nowrap class="TableData"><?=_("到期日期：")?></td>
      <td class="TableData">
       <input type="text" name="EXPIRE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$EXPIRE_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
      <td nowrap class="TableData"><?=_("定时到期提醒时间：")?></td>
      <td class="TableData">
        <input type="text" name="REMIND_TIME" size="20" maxlength="20" class="BigInput" value="<?=$REMIND_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("置为当前时间")?></a>
        <?=_("（系统会提前3天发送提醒,为空则不提醒）")?>
      </td>
         </tr>
      <tr style="display:none" id="menu3">
      <td nowrap class="TableData"> <?=_("定时提醒类型：")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(59);?>
      </td>
   	</tr>
    <tr style="display:none" id="menu2">
      <td nowrap class="TableData"><?=_("定时提醒人员：")?></td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="TO_ID" id="TO_ID">
        <textarea name="TO_NAME" id="TO_NAME" cols="82" rows="3"  class="SmallStatic" wrap="yes" readonly></textarea>
        <a href="#" class="orgAdd" onClick="SelectUser('27','','TO_ID', 'TO_NAME')" title="<?=_("添加提醒人员")?>"><?=_("添加")?></a>
        <a href="#" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')" title="<?=_("清空提醒人员")?>"><?=_("清空")?></a>        
      </td>
    </tr>    
   <tr>
      <td nowrap class="TableData"><?=_("发证机构：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="NOTIFIED_BODY" cols="70" rows="3" class="BigInput validate[required]" data-prompt-position="centerRight:0,15"  value=""><?=$NOTIFIED_BODY?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("备注：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
      </td>
    </tr> 
    <tr class="TableData" id="attachment2">
      <td nowrap><?=_("附件文档：")?></td>
      <td nowrap colspan=3>
<?
if($ATTACHMENT_ID=="")
   echo _("无附件");
else
   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
?>      
      </td>
   </tr>  
   <tr height="25" id="attachment1">
      <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
      <td class="TableData" colspan=3>
        <script>ShowAddFile();</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
   </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
        <input type="submit" value="<?=_("保存")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("修改培训计划");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
var pattern1 = /^[a-zA-Z]+\d/;
function CheckForm()
{
   if(document.form1.T_PLAN_NO.value == "" )
   {
      alert("<?=_("计划编号不能为空")?>");
      return false;
   }
   if(!pattern1.test(document.form1.T_PLAN_NO.value))
   {
       alert("<?=_("计划编号必须为字母和数字")?>");
        return false;
   }
   if(/.*[\u4e00-\u9fa5]+.*$/.test(document.form1.T_PLAN_NO.value))
   {
	   alert("<?=_("计划编号不能包含中文")?>");
        return false;
   }
   if(document.form1.T_PLAN_NAME.value == "" )
   {
      alert("<?=_("培训计划名称不能为空")?>");
      return false;
   }
   if(document.form1.CHARGE_PERSON.value == "")
   {
      alert("<?=_("负责人不能为空！")?>");
      return false;
   }
   if(document.form1.ASSESSING_OFFICER.value == "")
   {
      alert("<?=_("审批人不能为空")?>");
      return false;
   }
   if(document.form1.COURSE_START_TIME.value!="" && document.form1.COURSE_END_TIME.value!="" && document.form1.COURSE_START_TIME.value >= document.form1.COURSE_END_TIME.value)
   {
      alert("<?=_("结课时间不能小时开课时间！")?>");
      return false;
   }
   if(document.form1.T_JOIN_PERSON.value =="")
   {
	   msg='<?=_("没有设置参与培训人员，确定提交吗？")?>';
	   if(!window.confirm(msg))
	   {
		   return false;
	   }
   }
   return true;
}
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?T_PLAN_ID=<?=$T_PLAN_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>	


<body class="bodycolor">
<?
$query = "SELECT * FROM HR_TRAINING_PLAN WHERE T_PLAN_ID='$T_PLAN_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $T_PLAN_ID              = $ROW["T_PLAN_ID"];
   $T_PLAN_NO              = $ROW["T_PLAN_NO"];
   $T_PLAN_NAME            = $ROW["T_PLAN_NAME"];
   $T_CHANNEL              = $ROW["T_CHANNEL"];
   $T_BCWS                 = $ROW["T_BCWS"];
   $COURSE_START_TIME      = $ROW["COURSE_START_TIME"];
   $COURSE_END_TIME        = $ROW["COURSE_END_TIME"];
   $ASSESSING_OFFICER      = $ROW["ASSESSING_OFFICER"];
   $ASSESSING_STATUS       = $ROW["ASSESSING_STATUS"];
   $T_JOIN_NUM             = $ROW["T_JOIN_NUM"];
   $T_JOIN_DEPT            = $ROW["T_JOIN_DEPT"];
   $T_JOIN_PERSON          = $ROW["T_JOIN_PERSON"];
   $T_REQUIRES             = $ROW["T_REQUIRES"];
   $T_INSTITUTION_NAME     = $ROW["T_INSTITUTION_NAME"];
   $T_INSTITUTION_INFO     = $ROW["T_INSTITUTION_INFO"];
   $T_INSTITUTION_CONTACT  = $ROW["T_INSTITUTION_CONTACT"];
   $T_INSTITU_CONTACT_INFO = $ROW["T_INSTITU_CONTACT_INFO"];
   $T_COURSE_NAME          = $ROW["T_COURSE_NAME"];
   $SPONSORING_DEPARTMENT  = $ROW["SPONSORING_DEPARTMENT"];
   $CHARGE_PERSON          = $ROW["CHARGE_PERSON"];
   $COURSE_HOURS           = $ROW["COURSE_HOURS"]; 
   $COURSE_PAY             = $ROW["COURSE_PAY"];
   $T_COURSE_TYPES         = $ROW["T_COURSE_TYPES"];
   $T_DESCRIPTION          = $ROW["T_DESCRIPTION"];
   $REMARK                 = $ROW["REMARK"]; 
   $T_ADDRESS              = $ROW["T_ADDRESS"];
   $T_CONTENT              = $ROW["T_CONTENT"];
   $ADD_TIME               = $ROW["ADD_TIME"];
   $ATTACHMENT_ID          = $ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME        = $ROW["ATTACHMENT_NAME"];
   
   $SPONSORING_DEPARTMENT_NAME = substr(GetDeptNameById($ROW["SPONSORING_DEPARTMENT"]),0,-1);
   $CHARGE_PERSON_NAME         = substr(GetUserNameById($ROW["CHARGE_PERSON"]),0,-1);
   $ASSESSING_OFFICER_NAME     = substr(GetUserNameById($ROW["ASSESSING_OFFICER"]),0,-1);
   $T_JOIN_PERSON_NAME         = GetUserNameById($ROW["T_JOIN_PERSON"]);
	if($T_JOIN_DEPT=="ALL_DEPT")
   $T_JOIN_DEPT_NAME=_("全体部门");
	else
   $T_JOIN_DEPT_NAME=GetDeptNameById($T_JOIN_DEPT);
   
   if($COURSE_START_TIME=="0000-00-00 00:00:00")
     $COURSE_START_TIME="";
   if($COURSE_END_TIME=="0000-00-00 00:00:00")
     $COURSE_END_TIME="";    
	}
	if($ASSESSING_STATUS==1)
		header("location: index1.php");
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"><?=_("修改培训计划")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
    <tr>
      <td nowrap class="TableData"><span style="color: red;">*</span><?=_("计划编号：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_PLAN_NO" maxlength="20" class=BigInput size="20" value="<?=$T_PLAN_NO?>">
      </td>
       <td nowrap class="TableData"><span style="color: red;">*</span><?=_("计划名称：")?></td>
      <td class="TableData">
        <INPUT type="text"name="T_PLAN_NAME" maxlength="20" class=BigInput size="20" value="<?=$T_PLAN_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("培训渠道：")?></td>
      <td class="TableData" >
        <select name="T_CHANNEL" style="background: white;" title="">
          <option value="" <? if($T_CHANNEL=="") echo "selected";?>><?=_("请选择")?></option>
          <option value="0" <? if($T_CHANNEL==0) echo "selected";?>><?=_("内部培训")?></option>
          <option value="1" <? if($T_CHANNEL==1) echo "selected";?>><?=_("渠道培训")?></option>
        </select>
      </td>
       <td nowrap class="TableData"><?=_("培训形式：")?></td>
      <td class="TableData">
        <select name="T_COURSE_TYPES" style="background: white;" title="<?=_("培训形式可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("请选择")?></option>
          <?=hrms_code_list("T_COURSE_TYPE",$T_COURSE_TYPES)?>
        </select>
      </td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("主办部门：")?></td>
      <td class="TableData">
    	  <input type="hidden" name="SPONSORING_DEPARTMENT" value="<?=$SPONSORING_DEPARTMENT?>">
        <input type="text" name="SPONSORING_DEPARTMENT_NAME" value="<?=$SPONSORING_DEPARTMENT_NAME?>" class=BigStatic size=20 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','SPONSORING_DEPARTMENT','SPONSORING_DEPARTMENT_NAME')"><?=_("选择")?></a>             
      </td>
       <td nowrap class="TableData"><span style="color: red;">*</span><?=_("负责人：")?></td>
        <td class="TableData">
      <input type="text" name="CHARGE_PERSON_NAME" size="20" class="BigStatic" readonly value="<?=$CHARGE_PERSON_NAME?>">&nbsp;
        <input type="hidden" name="CHARGE_PERSON" value="<?=$CHARGE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','CHARGE_PERSON', 'CHARGE_PERSON_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("计划参与培训人数：")?></td>
      <td class="TableData" >
      <INPUT type="text"name="T_JOIN_NUM" class=BigInput size="20" value="<?=$T_JOIN_NUM?>">&nbsp;<?=_("人")?>
      </td>
      <td nowrap class="TableData"><?=_("培训地点：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_ADDRESS" class=BigInput size="20" value="<?=$T_ADDRESS?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("培训机构名称：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_INSTITUTION_NAME" class=BigInput size="20" value="<?=$T_INSTITUTION_NAME?>">
      </td>
      <td nowrap class="TableData"><?=_("培训机构联系人：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_INSTITUTION_CONTACT" class=BigInput size="20" value="<?=$T_INSTITUTION_CONTACT?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("培训课程名称：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_COURSE_NAME" class=BigInput size="20" value="<?=$T_COURSE_NAME?>">
      </td>
       <td nowrap class="TableData"><?=_("总课时：")?></td>
      <td class="TableData">
        <INPUT type="text"name="COURSE_HOURS" class=BigInput size="20" value="<?=$COURSE_HOURS?>">&nbsp;<?=_("小时")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("开课时间：")?></td>
      <td class="TableData">
       <input type="text" name="COURSE_START_TIME" size="20" class="BigInput" value="<?=$COURSE_START_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
       <td nowrap class="TableData"><?=_("结课时间：")?></td>
      <td class="TableData">
       <input type="text" name="COURSE_END_TIME" size="20" class="BigInput" value="<?=$COURSE_END_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("培训预算：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_BCWS" class=BigInput size="20" value="<?=$T_BCWS?>">&nbsp;<?=_("元")?>
      </td>
       <td nowrap class="TableData"><span style="color: red;">*</span><?=_("审批人：")?></td>
        <td class="TableData">
        <input type="text" name="ASSESSING_OFFICER_NAME" size="20" class="BigStatic" readonly value="<?=$ASSESSING_OFFICER_NAME?>">&nbsp;
        <input type="hidden" name="ASSESSING_OFFICER" value="<?=$ASSESSING_OFFICER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','ASSESSING_OFFICER', 'ASSESSING_OFFICER_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
    <td nowrap class="TableData"><?=_("参与培训部门")?>: </td>
      <td class="TableData" colspan=3>
      <input type="hidden" name="T_JOIN_DEPT" value="<?=$T_JOIN_DEPT?>">
        <textarea cols=70 name="T_JOIN_DEPT_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$T_JOIN_DEPT_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','T_JOIN_DEPT', 'T_JOIN_DEPT_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('T_JOIN_DEPT', 'T_JOIN_DEPT_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("参与培训人员：")?></td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="T_JOIN_PERSON" value="<?=$T_JOIN_PERSON?>">
        <textarea cols=70 name="T_JOIN_PERSON_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$T_JOIN_PERSON_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','T_JOIN_PERSON', 'T_JOIN_PERSON_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('T_JOIN_PERSON', 'T_JOIN_PERSON_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("培训机构相关信息：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_INSTITUTION_INFO" cols="82" rows="3" class="BigInput" value=""><?=$T_INSTITUTION_INFO?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("培训机构联系人相关信息：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_INSTITU_CONTACT_INFO" cols="82" rows="3" class="BigInput" value=""><?=$T_INSTITU_CONTACT_INFO?></textarea>
      </td>
    </tr> 
    <tr>
    <tr>
      <td nowrap class="TableData"><?=_("培训要求：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_REQUIRES" cols="82" rows="3" class="BigInput" value=""><?=$T_REQUIRES?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("培训说明：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_DESCRIPTION" cols="82" rows="3" class="BigInput" value=""><?=$T_DESCRIPTION?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("备注：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="82" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
      </td>
    </tr>
     <tr class="TableData" id="attachment2">
      <td nowrap><?=_("附件文档：")?></td>
      <td nowrap colspan=3><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1)?></td>
    </tr>
    <tr height="25" id="attachment1">
      <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
      <td class="TableData" colspan=3>
        <script>ShowAddFile();</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
    </tr>
   <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(61);?>
      </td>
   </tr>
    <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("培训内容：")?>
<?
$editor = new Editor('T_CONTENT') ;
$editor->Height = '300';
$editor->Value = $T_CONTENT ;
$editor->Config = array('model_type' => '16') ;
$editor->Create() ;
?>
      </td>
    </tr> 
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
	    <input type="hidden" name="RESUBMIT" value="0">
        <?if($ASSESSING_STATUS==2) echo '<input type="submit" value='._("重新提交").' class="BigButton" onclick="document.form1.RESUBMIT.value=1">';
		else echo '<input type="submit" value='._("保存").' class="BigButton">';?>
        <input type="hidden" value="<?=$T_PLAN_ID?>" name="T_PLAN_ID">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
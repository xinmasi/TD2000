<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("新建奖惩信息");
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

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?INCENTIVE_ID=<?=$INCENTIVE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>

<body class="bodycolor">
<?
$CUR_MONTH=substr(date("Y-m-d",time()),0,-3);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建奖惩信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" name="form1" id="form1" >
<table class="TableBlock" width="80%" align="center">
  <tr>
  	<td nowrap class="TableData"><?=_("单位员工：")?></td>
  	<td class="TableData" colspan=3>
      <input type="hidden" name="STAFF_NAME"  value="<?=$STAFF_NAME?>">
      <textarea cols=45 name="STAFF_NAME1" rows=2 class="BigInput validate[required]" data-prompt-position="centerRight:0,-6"  wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('STAFF_NAME', 'STAFF_NAME1')"><?=_("清空")?></a>
   </td>
  </tr>
  <tr>
     <td nowrap class="TableData"><?=_("奖惩项目：")?></td>
      <td class="TableData" colspan=3>
        <select name="INCENTIVE_ITEM" style="background: white;" title="<?=_("奖惩项目名称可在“人力资源设置”->“HRMS代码设置”模块设置。")?>" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8" >
          <option value=""><?=_("项目名称")?></option>
          <?=hrms_code_list("HR_STAFF_INCENTIVE1","")?>
        </select>
      </td> 
    </tr>
    <tr>
    	 <td nowrap class="TableData"><?=_("奖惩日期：")?></td>
      <td class="TableData">
       <input type="text" name="INCENTIVE_TIME" size="10" maxlength="10" class="BigInput" value="<?=$INCENTIVE_TIME?>" onClick="WdatePicker()"/>
      </td>
    	<td nowrap class="TableData"><?=_("工资月份：")?></td>
      <td class="TableData">
       <INPUT type="text"name="SALARY_MONTH" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8"  size="8" value="<?=$CUR_MONTH?>">
      </td>
    </tr>
    	<td nowrap class="TableData"><?=_("奖惩属性：")?></td>
      <td class="TableData">
        <select name="INCENTIVE_TYPE" style="background: white;" title="<?=_("奖惩项目名称可在“人力资源设置”->“HRMS代码设置”模块设置。")?>"  class="BigInput validate[required]" data-prompt-position="centerRight:0,-8" >
          <option value=""><?=_("请选择")?></option>
          <?=hrms_code_list("INCENTIVE_TYPE","")?>
        </select>
      </td> 
      <td nowrap class="TableData"><?=_("奖惩金额：")?></td>
      <td class="TableData">
        <INPUT type="text"name="INCENTIVE_AMOUNT" class="BigInput validate[custom[money]]" data-prompt-position="centerRight:20,-6" size="8" value="<?=$INCENTIVE_AMOUNT?>">&nbsp;<?=_("元")?>
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"><?=_("备注：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="74" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
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
   <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(58);?>
      </td>
   </tr>
    <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("奖惩说明：")?>
<?
$editor = new Editor('INCENTIVE_DESCRIPTION') ;
$editor->Height = '300';
$editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
$editor->Value = $INCENTIVE_DESCRIPTION ;
//$editor->Config = array('model_type' => '14') ;
$editor->Create() ;
?>
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
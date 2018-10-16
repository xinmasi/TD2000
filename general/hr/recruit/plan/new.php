<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");


$HTML_PAGE_TITLE = _("新增招聘计划");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script Language="JavaScript">
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
var pattern = new RegExp(/^\s+$/);
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
      URL="delete_attach.php?PLAN_ID=<?=$PLAN_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
      window.location=URL;
   }
}
</script>


<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新增招聘计划")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" id="form1" name="form1" >
<table class="TableBlock" width="60%" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("计划编号：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="PLAN_NO" maxlength="20" class="BigInput validate[required,custom[onlyNumberSp]]" data-prompt-position="centerRight:-3,-6" size="15" >
      </td>
      <td nowrap class="TableData"><?=_("计划名称：")?></td>
      <td class="TableData">
        <INPUT type="text"name="PLAN_NAME" maxlength="20" class="BigInput validate[required]" data-prompt-position="centerRight:-3,-7"  size="15" >
      </td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"><?=_("招聘渠道：")?></td>
       <td class="TableData" >
          <select name="PLAN_DITCH" style="background: white;" title="<?=_("员工关怀类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>"> 
					<option value=""><?=_("请选择")?></option>
					<?=hrms_code_list("PLAN_DITCH","")?> 
				  </select> 
       </td>
       <td nowrap class="TableData"><?=_("预算费用：")?></td>
       <td class="TableData">
          <INPUT type="text"name="PLAN_BCWS" class="BigInput validate[custom[money]]" data-prompt-position="centerRight:15,-7" size="15"> <?=_("元")?>
       </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("开始日期：")?></td>
      <td class="TableData">
      	<input type="text" id="start_time" name="START_DATE" size="15" maxlength="10" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8"   value="<?=$START_DATE?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"><?=_("结束日期：")?></td>
      <td class="TableData">
      	<input type="text" name="END_DATE" size="15" maxlength="10" class="BigInput validate[required]" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("招聘人数：")?></td>
      <td class="TableData">
        <INPUT type="text"name="PLAN_RECR_NO" class="BigInput validate[required,custom[onlyNumberSp]]" data-prompt-position="centerRight:0,-8" size="15" >&nbsp;<?=_("人")?>
      </td>
    	<td nowrap class="TableData"><?=_("审批人：")?></td>
    	<td class="TableData">
      <input type="text" name="APPROVE_PERSON_NAME" size="15" class="BigStatic validate[required]" data-prompt-position="centerRight:0,-8"  readonly value="">&nbsp;
        <input type="hidden" name="APPROVE_PERSON" value="<?=$APPROVE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','APPROVE_PERSON', 'APPROVE_PERSON_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("招聘说明：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="RECRUIT_DIRECTION" cols="66" rows="5" class="BigInput" data-prompt-position="centerRight:0,35" value=""></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("招聘备注：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="RECRUIT_REMARK" cols="66" rows="5" class="BigInput" data-prompt-position="centerRight:0,35" value=""></textarea>
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
			   <?=sms_remind(62);?>
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
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("�½���ѵ�ƻ�");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script Language="JavaScript">
jQuery(document).ready(function(){
	jQuery("#form1").validationEngine({promptPosition:"centerRight"});
}); 
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
//        var pattern = new RegExp(/^\s+$/);
var pattern1 = /^[a-zA-Z]+\d/;
        
function CheckForm()
{
   if(!pattern1.test(document.form1.T_PLAN_NO.value))
   {
	   alert("<?=_("�ƻ���ű���Ϊ��ĸ���������")?>");
	   return false;
   }
   if(/.*[\u4e00-\u9fa5]+.*$/.test(document.form1.T_PLAN_NO.value))
   {
	   alert("<?=_("�ƻ���Ų��ܰ�������")?>");
        return false;
   }

   if(document.form1.T_JOIN_PERSON.value =="")
   {
	   msg='<?=_("û�����ò�����ѵ��Ա��ȷ���ύ��")?>';
	   if(!window.confirm(msg))
	   {
		   return false;
	   }
   }
   return true;
}
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?T_PLAN_ID=<?=$T_PLAN_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
function check_plan_no(id)
{
   if(id=="")
   {
	 return;  
   }else if(/.*[\u4e00-\u9fa5]+.*$/.test(id))
   {
	   $("plan_no_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'><?=_("���ܰ�������")?>";
	   return; 
   }else if(!pattern1.test(id))
   {
	   $("plan_no_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'><?=_("����Ϊ��ĸ���������")?>";
	   return;  
   }
      
   $("plan_no_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading_16.gif' align='absMiddle'> <?=_("����У����Ժ򡭡�")?>";
   _get("check_plan_no.php","T_PLAN_NO="+id, show_msg);
}
function show_msg(req)
{
   if(req.status==200)
   {
      if(req.responseText=="+OK")
         $("plan_no_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/correct.gif' align='absMiddle'>";
      else
      {
         $("plan_no_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("�ñ���Ѵ���")?>";
         document.form1.T_PLAN_NO.focus();
      }
   }
   else
   {
      $("plan_no_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("����")?>"+req.status;
   }
}
</script>	


<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½���ѵ�ƻ�")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" id="form1" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
    <tr>
      <td nowrap class="TableData"><span style="color: red;">*</span><?=_("�ƻ���ţ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_PLAN_NO" maxlength="20" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8" size="20" onBlur="check_plan_no(this.value)">&nbsp;<span id="plan_no_msg"></span>
      </td>
       <td nowrap class="TableData"><span style="color: red;">*</span><?=_("�ƻ����ƣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="T_PLAN_NAME" maxlength="20" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8" size="20" >
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ������")?></td>
      <td class="TableData" >
        <select name="T_CHANNEL" style="background: white;" title="">
          <option value="" ><?=_("��ѡ��")?></option>
          <option value="0"><?=_("�ڲ���ѵ")?></option>
          <option value="1"><?=_("������ѵ")?></option>
        </select>
      </td>
       <td nowrap class="TableData"><?=_("��ѵ��ʽ��")?></td>
      <td class="TableData">
        <select name="T_COURSE_TYPES" style="background: white;" title="<?=_("��ѵ��ʽ���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ѡ��")?></option>
          <?=hrms_code_list("T_COURSE_TYPE","")?>
        </select>
      </td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("���첿�ţ�")?></td>
      <td class="TableData">
    	  <input type="hidden" name="SPONSORING_DEPARTMENT">
        <input type="text" name="SPONSORING_DEPARTMENT_NAME" value="" class=BigStatic size=12 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','SPONSORING_DEPARTMENT','SPONSORING_DEPARTMENT_NAME')"><?=_("ѡ��")?></a>             
      </td>
       <td nowrap class="TableData"><span style="color: red;">*</span><?=_("�����ˣ�")?></td>
        <td class="TableData">
        <input type="text" name="CHARGE_PERSON_NAME" size="20" class="BigStatic validate[required]" data-prompt-position="centerRight:0,-8" readonly value="">&nbsp;
        <input type="hidden" name="CHARGE_PERSON" value="">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','CHARGE_PERSON', 'CHARGE_PERSON_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�ƻ�������ѵ������")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_JOIN_NUM" class="BigInput validate[custom[onlyNumberSp]]" data-prompt-position="centerRight:15,-7" size="20">&nbsp;<?=_("��")?>
      </td>
      <td nowrap class="TableData"><?=_("��ѵ�ص㣺")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_ADDRESS" class=BigInput size="20">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ�������ƣ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_INSTITUTION_NAME" class=BigInput size="20" >
      </td>
      <td nowrap class="TableData"><?=_("��ѵ������ϵ�ˣ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_INSTITUTION_CONTACT" class=BigInput size="20" >
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ�γ����ƣ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_COURSE_NAME" class=BigInput size="20" >
      </td>
       <td nowrap class="TableData"><?=_("�ܿ�ʱ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="COURSE_HOURS" class=BigInput size="20" >&nbsp;<?=_("Сʱ")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ʱ�䣺")?></td>
      <td class="TableData">
       <input type="text" id="start_time" name="COURSE_START_TIME" size="20" class="BigInput" value="<?=$COURSE_START_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
       <td nowrap class="TableData"><?=_("���ʱ�䣺")?></td>
      <td class="TableData">
       <input type="text" name="COURSE_END_TIME" size="20" class="BigInput" value="<?=$COURSE_END_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵԤ�㣺")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_BCWS" class="BigInput validate[custom[money]]" data-prompt-position="centerRight:15,-6" size="20">&nbsp;<?=_("Ԫ")?>
      </td>
       <td nowrap class="TableData"><span style="color: red;">*</span><?=_("�����ˣ�")?></td>
        <td class="TableData">
        <input type="text" name="ASSESSING_OFFICER_NAME" size="20" class="BigStatic validate[required]" data-prompt-position="centerRight:0,-8" readonly value="">&nbsp;
        <input type="hidden" name="ASSESSING_OFFICER" value="">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','ASSESSING_OFFICER', 'ASSESSING_OFFICER_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
    <td nowrap class="TableData"><?=_("������ѵ����")?>: </td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="T_JOIN_DEPT" value="">
        <textarea cols=70 name="T_JOIN_DEPT_NAME" rows=3 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','T_JOIN_DEPT', 'T_JOIN_DEPT_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('T_JOIN_DEPT', 'T_JOIN_DEPT_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������ѵ��Ա��")?></td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="T_JOIN_PERSON" value="">
        <textarea cols=70 name="T_JOIN_PERSON_NAME" rows=3 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','T_JOIN_PERSON', 'T_JOIN_PERSON_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('T_JOIN_PERSON', 'T_JOIN_PERSON_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ���������Ϣ��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_INSTITUTION_INFO" cols="82" rows="3" class="BigInput validate[maxSize[60]]" data-prompt-position="centerRight:0,13" value=""><?T_INSTITUTION_INFO?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ������ϵ�������Ϣ��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_INSTITU_CONTACT_INFO" cols="82" rows="3" class="BigInput validate[maxSize[60]]" data-prompt-position="centerRight:0,13" value=""><?=$T_INSTITU_CONTACT_INFO?></textarea>
      </td>
    </tr> 
    <tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵҪ��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_REQUIRES" cols="82" rows="3" class="BigInput validate[maxSize[60]]" data-prompt-position="centerRight:0,13" value=""><?=$T_REQUIRES?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ˵����")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_DESCRIPTION" cols="82" rows="3" class="BigInput validate[maxSize[60]]" data-prompt-position="centerRight:0,13" value=""><?=$T_DESCRIPTION?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("��ע��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="82" rows="3" class="BigInput validate[maxSize[60]]" data-prompt-position="centerRight:0,13" value=""><?=$REMARK?></textarea>
      </td>
    </tr>
     <tr height="25" id="attachment1">
      <td nowrap class="TableData" ><span id="ATTACH_LABEL"><?=_("�����ϴ���")?></span></td>
      <td class="TableData"colspan=3>
        <script>ShowAddFile();</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"> <?=_("���ѣ�")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(61);?>
      </td>
   </tr>
    <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("��ѵ���ݣ�")?>
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
        <input type="submit" value="<?=_("����")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
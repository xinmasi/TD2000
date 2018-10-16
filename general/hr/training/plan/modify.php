<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�޸���ѵ�ƻ�");
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
      alert("<?=_("�ƻ���Ų���Ϊ��")?>");
      return false;
   }
   if(!pattern1.test(document.form1.T_PLAN_NO.value))
   {
       alert("<?=_("�ƻ���ű���Ϊ��ĸ������")?>");
        return false;
   }
   if(/.*[\u4e00-\u9fa5]+.*$/.test(document.form1.T_PLAN_NO.value))
   {
	   alert("<?=_("�ƻ���Ų��ܰ�������")?>");
        return false;
   }
   if(document.form1.T_PLAN_NAME.value == "" )
   {
      alert("<?=_("��ѵ�ƻ����Ʋ���Ϊ��")?>");
      return false;
   }
   if(document.form1.CHARGE_PERSON.value == "")
   {
      alert("<?=_("�����˲���Ϊ�գ�")?>");
      return false;
   }
   if(document.form1.ASSESSING_OFFICER.value == "")
   {
      alert("<?=_("�����˲���Ϊ��")?>");
      return false;
   }
   if(document.form1.COURSE_START_TIME.value!="" && document.form1.COURSE_END_TIME.value!="" && document.form1.COURSE_START_TIME.value >= document.form1.COURSE_END_TIME.value)
   {
      alert("<?=_("���ʱ�䲻��Сʱ����ʱ�䣡")?>");
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
   $T_JOIN_DEPT_NAME=_("ȫ�岿��");
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
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"><?=_("�޸���ѵ�ƻ�")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
    <tr>
      <td nowrap class="TableData"><span style="color: red;">*</span><?=_("�ƻ���ţ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_PLAN_NO" maxlength="20" class=BigInput size="20" value="<?=$T_PLAN_NO?>">
      </td>
       <td nowrap class="TableData"><span style="color: red;">*</span><?=_("�ƻ����ƣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="T_PLAN_NAME" maxlength="20" class=BigInput size="20" value="<?=$T_PLAN_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ������")?></td>
      <td class="TableData" >
        <select name="T_CHANNEL" style="background: white;" title="">
          <option value="" <? if($T_CHANNEL=="") echo "selected";?>><?=_("��ѡ��")?></option>
          <option value="0" <? if($T_CHANNEL==0) echo "selected";?>><?=_("�ڲ���ѵ")?></option>
          <option value="1" <? if($T_CHANNEL==1) echo "selected";?>><?=_("������ѵ")?></option>
        </select>
      </td>
       <td nowrap class="TableData"><?=_("��ѵ��ʽ��")?></td>
      <td class="TableData">
        <select name="T_COURSE_TYPES" style="background: white;" title="<?=_("��ѵ��ʽ���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ѡ��")?></option>
          <?=hrms_code_list("T_COURSE_TYPE",$T_COURSE_TYPES)?>
        </select>
      </td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("���첿�ţ�")?></td>
      <td class="TableData">
    	  <input type="hidden" name="SPONSORING_DEPARTMENT" value="<?=$SPONSORING_DEPARTMENT?>">
        <input type="text" name="SPONSORING_DEPARTMENT_NAME" value="<?=$SPONSORING_DEPARTMENT_NAME?>" class=BigStatic size=20 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','SPONSORING_DEPARTMENT','SPONSORING_DEPARTMENT_NAME')"><?=_("ѡ��")?></a>             
      </td>
       <td nowrap class="TableData"><span style="color: red;">*</span><?=_("�����ˣ�")?></td>
        <td class="TableData">
      <input type="text" name="CHARGE_PERSON_NAME" size="20" class="BigStatic" readonly value="<?=$CHARGE_PERSON_NAME?>">&nbsp;
        <input type="hidden" name="CHARGE_PERSON" value="<?=$CHARGE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','CHARGE_PERSON', 'CHARGE_PERSON_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�ƻ�������ѵ������")?></td>
      <td class="TableData" >
      <INPUT type="text"name="T_JOIN_NUM" class=BigInput size="20" value="<?=$T_JOIN_NUM?>">&nbsp;<?=_("��")?>
      </td>
      <td nowrap class="TableData"><?=_("��ѵ�ص㣺")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_ADDRESS" class=BigInput size="20" value="<?=$T_ADDRESS?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ�������ƣ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_INSTITUTION_NAME" class=BigInput size="20" value="<?=$T_INSTITUTION_NAME?>">
      </td>
      <td nowrap class="TableData"><?=_("��ѵ������ϵ�ˣ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_INSTITUTION_CONTACT" class=BigInput size="20" value="<?=$T_INSTITUTION_CONTACT?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ�γ����ƣ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_COURSE_NAME" class=BigInput size="20" value="<?=$T_COURSE_NAME?>">
      </td>
       <td nowrap class="TableData"><?=_("�ܿ�ʱ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="COURSE_HOURS" class=BigInput size="20" value="<?=$COURSE_HOURS?>">&nbsp;<?=_("Сʱ")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ʱ�䣺")?></td>
      <td class="TableData">
       <input type="text" name="COURSE_START_TIME" size="20" class="BigInput" value="<?=$COURSE_START_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
       <td nowrap class="TableData"><?=_("���ʱ�䣺")?></td>
      <td class="TableData">
       <input type="text" name="COURSE_END_TIME" size="20" class="BigInput" value="<?=$COURSE_END_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵԤ�㣺")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_BCWS" class=BigInput size="20" value="<?=$T_BCWS?>">&nbsp;<?=_("Ԫ")?>
      </td>
       <td nowrap class="TableData"><span style="color: red;">*</span><?=_("�����ˣ�")?></td>
        <td class="TableData">
        <input type="text" name="ASSESSING_OFFICER_NAME" size="20" class="BigStatic" readonly value="<?=$ASSESSING_OFFICER_NAME?>">&nbsp;
        <input type="hidden" name="ASSESSING_OFFICER" value="<?=$ASSESSING_OFFICER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','ASSESSING_OFFICER', 'ASSESSING_OFFICER_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
    <td nowrap class="TableData"><?=_("������ѵ����")?>: </td>
      <td class="TableData" colspan=3>
      <input type="hidden" name="T_JOIN_DEPT" value="<?=$T_JOIN_DEPT?>">
        <textarea cols=70 name="T_JOIN_DEPT_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$T_JOIN_DEPT_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','T_JOIN_DEPT', 'T_JOIN_DEPT_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('T_JOIN_DEPT', 'T_JOIN_DEPT_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������ѵ��Ա��")?></td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="T_JOIN_PERSON" value="<?=$T_JOIN_PERSON?>">
        <textarea cols=70 name="T_JOIN_PERSON_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$T_JOIN_PERSON_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','T_JOIN_PERSON', 'T_JOIN_PERSON_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('T_JOIN_PERSON', 'T_JOIN_PERSON_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ���������Ϣ��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_INSTITUTION_INFO" cols="82" rows="3" class="BigInput" value=""><?=$T_INSTITUTION_INFO?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ������ϵ�������Ϣ��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_INSTITU_CONTACT_INFO" cols="82" rows="3" class="BigInput" value=""><?=$T_INSTITU_CONTACT_INFO?></textarea>
      </td>
    </tr> 
    <tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵҪ��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_REQUIRES" cols="82" rows="3" class="BigInput" value=""><?=$T_REQUIRES?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ˵����")?></td>
      <td class="TableData" colspan=3>
        <textarea name="T_DESCRIPTION" cols="82" rows="3" class="BigInput" value=""><?=$T_DESCRIPTION?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("��ע��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="82" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
      </td>
    </tr>
     <tr class="TableData" id="attachment2">
      <td nowrap><?=_("�����ĵ���")?></td>
      <td nowrap colspan=3><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1)?></td>
    </tr>
    <tr height="25" id="attachment1">
      <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("�����ϴ���")?></span></td>
      <td class="TableData" colspan=3>
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
	    <input type="hidden" name="RESUBMIT" value="0">
        <?if($ASSESSING_STATUS==2) echo '<input type="submit" value='._("�����ύ").' class="BigButton" onclick="document.form1.RESUBMIT.value=1">';
		else echo '<input type="submit" value='._("����").' class="BigButton">';?>
        <input type="hidden" value="<?=$T_PLAN_ID?>" name="T_PLAN_ID">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
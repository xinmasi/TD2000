<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");


$HTML_PAGE_TITLE = _("�༭��Ƹ�ƻ���Ϣ");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
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
function upload_attach()
{
   if(CheckForm())
   {
     document.form1.submit();
   }
}
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
   var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
   if(window.confirm(msg))
   {
      URL="delete_attach.php?PLAN_ID=<?=$PLAN_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
      window.location=URL;
   }
}
</script>

<?
$query="select * from HR_RECRUIT_PLAN where PLAN_ID='$PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   
   $PLAN_ID=$ROW["PLAN_ID"];
   $PLAN_NO=$ROW["PLAN_NO"];
   $PLAN_NAME=$ROW["PLAN_NAME"];
   $PLAN_DITCH=$ROW["PLAN_DITCH"];
   $PLAN_BCWS=$ROW["PLAN_BCWS"];
   $PLAN_RECR_NO=$ROW["PLAN_RECR_NO"];
   $REGISTER_TIME=$ROW["REGISTER_TIME"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $RECRUIT_DIRECTION=$ROW["RECRUIT_DIRECTION"]; 
   $RECRUIT_REMARK=$ROW["RECRUIT_REMARK"];
   $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
   $PLAN_STATUS=$ROW["PLAN_STATUS"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   
   $PLAN_DITCH_NAME=get_hrms_code_name($PLAN_DITCH,"PLAN_DITCH");
   $APPROVE_PERSON_NAME=substr(GetUserNameById($APPROVE_PERSON),0,-1);
}

$CUR_TIME=date("Y-m-d H:i:s",time());
?>
<body class="bodycolor">

<table border="0" width="770" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�༭��Ƹ�ƻ���Ϣ")?></span>&nbsp;&nbsp;</td>
  </tr>
</table>

<form enctype="multipart/form-data" action="update.php"  method="post" id="form1" name="form1" >
<table class="TableBlock" width="60%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("�ƻ���ţ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="PLAN_NO" maxlength="20"  class="BigInput validate[required,custom[onlyNumberSp]]" data-prompt-position="centerRight:0,-8" size="15" value="<?=$PLAN_NO?>">
      </td>
       <td nowrap class="TableData"><?=_("�ƻ����ƣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="PLAN_NAME" maxlength="20"  class="BigInput validate[required]" data-prompt-position="centerRight:0,-8"  size="15" value="<?=$PLAN_NAME?>">
      </td>
   </tr>
   <tr>
    	 <td nowrap class="TableData"><?=_("��Ƹ������")?></td>
      <td class="TableData" >
        <select name="PLAN_DITCH" style="background: white;" title="<?=_("Ա���ػ����Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""<?if($PLAN_DITCH=="") echo _("��ѡ��"); else echo "selected"?>></option>
          <?=hrms_code_list("PLAN_DITCH",$PLAN_DITCH)?>
        </select>
      </td>
       <td nowrap class="TableData"><?=_("Ԥ����ã�")?></td>
      <td class="TableData">
        <INPUT type="text"name="PLAN_BCWS" class="BigInput validate[required,custom[money]]" data-prompt-position="centerRight:0,-8" size="15"  value="<?=$PLAN_BCWS?>"><?=_("Ԫ")?>
      </td>
   </tr>
   <tr>
    	<td nowrap class="TableData"><?=_("��ʼ���ڣ�")?></td>
      <td class="TableData">
       <input type="text" id="start_time" name="START_DATE" size="15" maxlength="10" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8" value="<?=$START_DATE=="0000-00-00"?"":$START_DATE;?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
      <td class="TableData">
       <input type="text" name="END_DATE" size="15" maxlength="10" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8"  value="<?=$END_DATE=="0000-00-00"?"":$END_DATE;?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("��Ƹ������")?></td>
      <td class="TableData">
       <INPUT type="text"name="PLAN_RECR_NO" class="BigInput validate[required,custom[onlyNumberSp]]" data-prompt-position="centerRight:0,-8" size="15"  value="<?=$PLAN_RECR_NO?>">&nbsp;<?=_("��")?>
      </td>
    	<td nowrap class="TableData"><?=_("�����ˣ�")?></td>
      <td class="TableData">
      <input type="text" name="APPROVE_PERSON_NAME" size="15" class="BigStatic validate[required]" data-prompt-position="centerRight:0,-8" readonly value="<?=$APPROVE_PERSON_NAME?>">&nbsp;
        <input type="hidden" name="APPROVE_PERSON" value="<?=$APPROVE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','APPROVE_PERSON', 'APPROVE_PERSON_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>      
    <tr>
      <td nowrap class="TableData"><?=_("��Ƹ˵����")?></td>
      <td class="TableData" colspan=3>
        <textarea name="RECRUIT_DIRECTION" cols="66" rows="5" class="BigInput" ><?=$RECRUIT_DIRECTION?></textarea>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData"><?=_("��Ƹ��ע��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="RECRUIT_REMARK" cols="66" rows="5" class="BigInput" ><?=$RECRUIT_REMARK?></textarea>
      </td>
    </tr> 
    <tr class="TableData" id="attachment2">
      <td nowrap><?=_("�����ĵ���")?></td>
      <td nowrap colspan=3>
			<?
			if($ATTACHMENT_ID=="")
			   echo _("�޸���");
			else
			   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,0);
			?>      
      </td>
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
			   <?=sms_remind(62);?>
       </td>
    </tr>
   <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" name="PLAN_ID" size="20" value="<?=$PLAN_ID?>">
        <input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="location='index1.php';">
      </td>
   </tr>
</table>
</form>
</body>
</html>
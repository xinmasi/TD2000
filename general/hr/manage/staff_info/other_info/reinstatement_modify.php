<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("Ա����ְ��Ϣ�޸�");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
	function CheckForm()
{
   if(document.form1.REINSTATEMENT_PERSON.value=="")
   { 
      alert("<?=_("��ѡ��ְ��Ա��")?>");
      return (false);
   }
   if(document.form1.REAPPOINTMENT_DEPT.value=="")
   { 
      alert("<?=_("��ѡ��ְ���ţ�")?>");
      return (false);
   }
   if(getEditorHtml('REAPPOINTMENT_STATE') == "" && getEditorText('REAPPOINTMENT_STATE').length == 0)
   { 
      alert("<?=_("��ְ˵������Ϊ�գ�")?>");
      return (false);
   }
/*    if(document.form1.FIRST_SALARY_TIME.value=="")
   { 
      alert("<?=_("���ʻָ����ڲ���Ϊ�գ�")?>");
      return (false);
   } */
   if(document.form1.FIRST_SALARY_TIME.value!="" && document.form1.REAPPOINTMENT_TIME_FACT.value!="" && document.form1.REAPPOINTMENT_TIME_FACT.value > document.form1.FIRST_SALARY_TIME.value)
   { 
      alert("<?=_("���ʻָ����ڲ���С��ʵ�ʸ�ְ���ڣ�")?>");
      return (false);
   }
 return (true);
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
    URL="delete_attach.php?REINSTATEMENT_ID=<?=$REINSTATEMENT_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>

<?
$query="select * from HR_STAFF_REINSTATEMENT where REINSTATEMENT_ID='$REINSTATEMENT_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
   $REINSTATEMENT_ID=$ROW["REINSTATEMENT_ID"];
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $REAPPOINTMENT_TIME_FACT=$ROW["REAPPOINTMENT_TIME_FACT"];
   $REAPPOINTMENT_TYPE=$ROW["REAPPOINTMENT_TYPE"];
   $REAPPOINTMENT_STATE=$ROW["REAPPOINTMENT_STATE"];
   $REMARK=$ROW["REMARK"];
   $REINSTATEMENT_PERSON=$ROW["REINSTATEMENT_PERSON"];
   $REAPPOINTMENT_TIME_PLAN=$ROW["REAPPOINTMENT_TIME_PLAN"];
   $NOW_POSITION=$ROW["NOW_POSITION"];
   $APPLICATION_DATE=$ROW["APPLICATION_DATE"];
   $MATERIALS_CONDITION=$ROW["MATERIALS_CONDITION"];
   $FIRST_SALARY_TIME=$ROW["FIRST_SALARY_TIME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
   $REAPPOINTMENT_DEPT =$ROW["REAPPOINTMENT_DEPT"];
  
  $REINSTATEMENT_PERSON_NAME=substr(GetUserNameById($REINSTATEMENT_PERSON),0,-1);
  $REAPPOINTMENT_DEPT_NAME=substr(GetDeptNameById($REAPPOINTMENT_DEPT),0,-1);
  
}
 
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�༭Ա����ְ��Ϣ")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<form action="reinstatement_update.php"  method="post" name="form1" enctype="multipart/form-data"  onsubmit="return CheckForm();">
 <table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("��ְ��Ա��")?></td>
      <td class="TableData">
        <input type="text" name="REINSTATEMENT_PERSON_NAME" size="15" class="BigStatic" readonly value="<?=$REINSTATEMENT_PERSON_NAME?>"">&nbsp;
        <input type="hidden" name="REINSTATEMENT_PERSON" value="<?=$REINSTATEMENT_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','REINSTATEMENT_PERSON', 'REINSTATEMENT_PERSON_NAME')"><?=_("ѡ��")?></a>
      </td>
      <td nowrap class="TableData"> <?=_("��ְ���ͣ�")?></td>
      <td class="TableData" >
        <select name="REAPPOINTMENT_TYPE" style="background: white;" title="<?=_("��ְ���Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ְ����")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_REINSTATEMENT",$REAPPOINTMENT_TYPE)?>
        </select>
      </td> 
    </tr>
    <tr>
    	 <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
      <td class="TableData">
       <input type="text" name="APPLICATION_DATE" size="15" maxlength="10" class="BigInput" value="<?=$APPLICATION_DATE=="0000-00-00"?"":$APPLICATION_DATE;?>" onClick="WdatePicker()"/>
      </td>
    	<td nowrap class="TableData"><?=_("����ְ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="NOW_POSITION" class=BigInput size="15" value="<?=$NOW_POSITION?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�⸴ְ���ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="REAPPOINTMENT_TIME_PLAN" size="15" maxlength="10" class="BigInput" value="<?=$REAPPOINTMENT_TIME_PLAN=="0000-00-00"?"":$REAPPOINTMENT_TIME_PLAN;?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"> <?=_("ʵ�ʸ�ְ���ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="REAPPOINTMENT_TIME_FACT" size="15" maxlength="10" class="BigInput" value="<?=$REAPPOINTMENT_TIME_FACT=="0000-00-00"?"":$REAPPOINTMENT_TIME_FACT;?>" onClick="WdatePicker()"/>
      </td>
    </tr>    
     <tr>
      <td nowrap class="TableData"><?=_("���ʻָ����ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="FIRST_SALARY_TIME" size="15" maxlength="10" class="BigInput" value="<?=$FIRST_SALARY_TIME=="0000-00-00"?"":$FIRST_SALARY_TIME;?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"><?=_("��ְ���ţ�")?></td>
      <td class="TableData" colspan=3>
    	  <input type="hidden" name="REAPPOINTMENT_DEPT" value="<?=$REAPPOINTMENT_DEPT?>">
        <input type="text" name="REAPPOINTMENT_DEPT_NAME" value="<?=$REAPPOINTMENT_DEPT_NAME?>" class=BigStatic size=18 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','REAPPOINTMENT_DEPT','REAPPOINTMENT_DEPT_NAME')"><?=_("ѡ��")?></a>        	
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ְ��������")?></td>
      <td class="TableData" colspan=3>
        <textarea name="MATERIALS_CONDITION" cols="70" rows="3" class="BigInput" value=""><?=$MATERIALS_CONDITION?></textarea>
      </td>
    </tr>
   <tr>
      <td nowrap class="TableData"><?=_("��ע��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
      </td>
    </tr> 
    <tr class="TableData" id="attachment2">
      <td nowrap><?=_("�����ĵ���")?></td>
      <td nowrap colspan=3>
<?
if($ATTACHMENT_ID=="")
   echo _("�޸���");
else
   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
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
     <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("��ְ˵����")?>
<?
$editor = new Editor('REAPPOINTMENT_STATE') ;
$editor->Height = '200';
$editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
$editor->Value = $REAPPOINTMENT_STATE ;
//$editor->Config = array('model_type' => '14') ;
$editor->Create() ;
?>
      </td>
    </tr>     
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" value="<?=$REINSTATEMENT_ID?>" name="REINSTATEMENT_ID">
        <input type="submit" value="<?=_("����")?>" class="BigButton">
        <!--<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">-->
      </td>
    </tr>
  </table>
</form>

</body>
</html>
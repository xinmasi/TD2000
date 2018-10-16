<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("����ְ��������Ϣ");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.BY_EVALU_STAFFS.value=="")
   { 
      alert("<?=_("��ѡ����������")?>");
      return (false);
   }
 	 if(document.form1.POST_NAME.value=="")
   { 
      alert("<?=_("����д��ȡְ�Ƶ����ƣ�")?>");
      return (false);
   }
   if(document.form1.REPORT_TIME.value!="" && document.form1.RECEIVE_TIME.value!="" && document.form1.REPORT_TIME.value >= document.form1.RECEIVE_TIME.value)
   { 
      alert("<?=_("��ȡʱ�䲻��С���걨ʱ�䣡")?>");
      return (false);
   }
   if(document.form1.APPROVE_NEXT_TIME.value!="" && document.form1.RECEIVE_TIME.value!="" && document.form1.RECEIVE_TIME.value >= document.form1.APPROVE_NEXT_TIME.value)
   { 
      alert("<?=_("�´��걨ʱ�䲻��С�ڻ�ȡʱ�䣡")?>");
      return (false);
   }
   if(document.form1.START_DATE.value!="" && document.form1.END_DATE.value!="" && document.form1.START_DATE.value >= document.form1.END_DATE.value)
   { 
      alert("<?=_("Ƹ�ý���ʱ�䲻��С��Ƹ�ÿ�ʼʱ�䣡")?>");
      return (false);
   }
   if(document.form1.RECEIVE_TIME.value!="" && document.form1.END_DATE.value!="" && document.form1.RECEIVE_TIME.value >= document.form1.END_DATE.value)
   { 
      alert("<?=_("Ƹ�ÿ�ʼʱ�䲻��С��Ƹ�û�ȡʱ�䣡")?>");
      return (false);
   }
 return (true);
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("����ְ��������Ϣ")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="evaluation_add.php"  method="post" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("��������")?></td>
      <td class="TableData">
<?
$BY_EVALU_STAFFS =$USER_ID;
$BY_EVALU_NAME = substr( getUserNameById($USER_ID), 0, -1);
?>
        <input type="text" name="BY_EVALU_NAME" size="15" class="BigStatic" readonly value="<?=_("$BY_EVALU_NAME")?>">&nbsp;
        <input type="hidden" name="BY_EVALU_STAFFS" value="<?=$BY_EVALU_STAFFS?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','BY_EVALU_STAFFS', 'BY_EVALU_NAME','1')"><?=_("ѡ��")?></a>
      </td>
      <td nowrap class="TableData"><?=_("��׼�ˣ�")?></td>
      <td class="TableData">
        <input type="text" name="APPROVE_PERSON_NAME" size="15" class="BigStatic" readonly value="<?=substr(GetUserNameById($APPROVE_PERSON),0,-1)?>">&nbsp;
        <input type="hidden" name="APPROVE_PERSON" value="<?=$APPROVE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','APPROVE_PERSON', 'APPROVE_PERSON_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"> <?=_("��ȡְ�ƣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="POST_NAME" class=BigInput size="15" value="<?=$POST_NAME?>">
      </td>
      <td nowrap class="TableData"> <?=_("��ȡ��ʽ��")?></td>
      <td class="TableData" >
        <select name="GET_METHOD" style="background: white;" title="<?=_("ְ�ƻ�ȡ��ʽ���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("ְ�ƻ�ȡ��ʽ")?>&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_TITLE_EVALUATION","")?>
        </select>
      </td> 
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�걨ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="REPORT_TIME" size="15" maxlength="10" class="BigInput" value="<?=$REPORT_TIME?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"> <?=_("��ȡʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="RECEIVE_TIME" size="15" maxlength="10" class="BigInput" value="<?=$RECEIVE_TIME?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    
     <tr>
       <td nowrap class="TableData"> <?=_("�´��걨ְ�ƣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="APPROVE_NEXT" class=BigInput size="15" value="<?=$APPROVE_NEXT?>">
      </td>
      <td nowrap class="TableData"><?=_("�´��걨ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="APPROVE_NEXT_TIME" size="15" maxlength="10" class="BigInput" value="<?=$APPROVE_NEXT_TIME?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    
    <tr>
       <td nowrap class="TableData"> <?=_("Ƹ��ְ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="EMPLOY_POST" class=BigInput size="15" value="<?=$EMPLOY_POST?>">
      </td>
      <td nowrap class="TableData"> <?=_("Ƹ�õ�λ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="EMPLOY_COMPANY" class=BigInput size="15" value="<?=$EMPLOY_COMPANY?>">
      </td> 
    </tr>
    <tr>
       <td nowrap class="TableData"> <?=_("Ƹ�ÿ�ʼʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="START_DATE" size="15" maxlength="10" class="BigInput" value="<?=$START_DATE?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"><?=_("Ƹ�ý���ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="END_DATE" size="15" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker()"/>
      </td>
    </tr>
     <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("�������飺")?>
<?
$editor = new Editor('REMARK') ;
$editor->Height = '200';
$editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
$editor->Value = $REMARK ;
//$editor->Config = array('model_type' => '14') ;
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
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("新增职称评定信息");
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
      alert("<?=_("请选择评定对象！")?>");
      return (false);
   }
 	 if(document.form1.POST_NAME.value=="")
   { 
      alert("<?=_("请填写获取职称的名称！")?>");
      return (false);
   }
   if(document.form1.REPORT_TIME.value!="" && document.form1.RECEIVE_TIME.value!="" && document.form1.REPORT_TIME.value >= document.form1.RECEIVE_TIME.value)
   { 
      alert("<?=_("获取时间不能小于申报时间！")?>");
      return (false);
   }
   if(document.form1.APPROVE_NEXT_TIME.value!="" && document.form1.RECEIVE_TIME.value!="" && document.form1.RECEIVE_TIME.value >= document.form1.APPROVE_NEXT_TIME.value)
   { 
      alert("<?=_("下次申报时间不能小于获取时间！")?>");
      return (false);
   }
   if(document.form1.START_DATE.value!="" && document.form1.END_DATE.value!="" && document.form1.START_DATE.value >= document.form1.END_DATE.value)
   { 
      alert("<?=_("聘用结束时间不能小于聘用开始时间！")?>");
      return (false);
   }
   if(document.form1.RECEIVE_TIME.value!="" && document.form1.END_DATE.value!="" && document.form1.RECEIVE_TIME.value >= document.form1.END_DATE.value)
   { 
      alert("<?=_("聘用开始时间不能小于聘用获取时间！")?>");
      return (false);
   }
 return (true);
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新增职称评定信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="evaluation_add.php"  method="post" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("评定对象：")?></td>
      <td class="TableData">
<?
$BY_EVALU_STAFFS =$USER_ID;
$BY_EVALU_NAME = substr( getUserNameById($USER_ID), 0, -1);
?>
        <input type="text" name="BY_EVALU_NAME" size="15" class="BigStatic" readonly value="<?=_("$BY_EVALU_NAME")?>">&nbsp;
        <input type="hidden" name="BY_EVALU_STAFFS" value="<?=$BY_EVALU_STAFFS?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','BY_EVALU_STAFFS', 'BY_EVALU_NAME','1')"><?=_("选择")?></a>
      </td>
      <td nowrap class="TableData"><?=_("批准人：")?></td>
      <td class="TableData">
        <input type="text" name="APPROVE_PERSON_NAME" size="15" class="BigStatic" readonly value="<?=substr(GetUserNameById($APPROVE_PERSON),0,-1)?>">&nbsp;
        <input type="hidden" name="APPROVE_PERSON" value="<?=$APPROVE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','APPROVE_PERSON', 'APPROVE_PERSON_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"> <?=_("获取职称：")?></td>
      <td class="TableData">
        <INPUT type="text"name="POST_NAME" class=BigInput size="15" value="<?=$POST_NAME?>">
      </td>
      <td nowrap class="TableData"> <?=_("获取方式：")?></td>
      <td class="TableData" >
        <select name="GET_METHOD" style="background: white;" title="<?=_("职称获取方式可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("职称获取方式")?>&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_TITLE_EVALUATION","")?>
        </select>
      </td> 
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("申报时间：")?></td>
      <td class="TableData">
        <input type="text" name="REPORT_TIME" size="15" maxlength="10" class="BigInput" value="<?=$REPORT_TIME?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"> <?=_("获取时间：")?></td>
      <td class="TableData">
        <input type="text" name="RECEIVE_TIME" size="15" maxlength="10" class="BigInput" value="<?=$RECEIVE_TIME?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    
     <tr>
       <td nowrap class="TableData"> <?=_("下次申报职称：")?></td>
      <td class="TableData">
        <INPUT type="text"name="APPROVE_NEXT" class=BigInput size="15" value="<?=$APPROVE_NEXT?>">
      </td>
      <td nowrap class="TableData"><?=_("下次申报时间：")?></td>
      <td class="TableData">
        <input type="text" name="APPROVE_NEXT_TIME" size="15" maxlength="10" class="BigInput" value="<?=$APPROVE_NEXT_TIME?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    
    <tr>
       <td nowrap class="TableData"> <?=_("聘用职务：")?></td>
      <td class="TableData">
        <INPUT type="text"name="EMPLOY_POST" class=BigInput size="15" value="<?=$EMPLOY_POST?>">
      </td>
      <td nowrap class="TableData"> <?=_("聘用单位：")?></td>
      <td class="TableData">
        <INPUT type="text"name="EMPLOY_COMPANY" class=BigInput size="15" value="<?=$EMPLOY_COMPANY?>">
      </td> 
    </tr>
    <tr>
       <td nowrap class="TableData"> <?=_("聘用开始时间：")?></td>
      <td class="TableData">
        <input type="text" name="START_DATE" size="15" maxlength="10" class="BigInput" value="<?=$START_DATE?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"><?=_("聘用结束时间：")?></td>
      <td class="TableData">
        <input type="text" name="END_DATE" size="15" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker()"/>
      </td>
    </tr>
     <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("评定详情：")?>
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
        <input type="submit" value="<?=_("保存")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
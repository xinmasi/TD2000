<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("职称评定信息修改");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
	function CheckForm()
{
   if(document.form1.BY_EVALU_STAFFS.value=="")
   { 
      alert("<?=_("评定对象不能为空！")?>");
      return (false);
   }
 	 if(document.form1.POST_NAME.value=="")
   { 
      alert("<?=_("获取职称的名称不能为空！")?>");
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

<?
$query="select * from HR_STAFF_TITLE_EVALUATION where EVALUATION_ID='$EVALUATION_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $EVALUATION_ID=$ROW["EVALUATION_ID"];
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $POST_NAME=$ROW["POST_NAME"];
   $GET_METHOD=$ROW["GET_METHOD"];
   $REPORT_TIME=$ROW["REPORT_TIME"];
   $RECEIVE_TIME=$ROW["RECEIVE_TIME"];
   $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
   $APPROVE_NEXT=$ROW["APPROVE_NEXT"];
   $APPROVE_NEXT_TIME=$ROW["APPROVE_NEXT_TIME"];
   $REMARK=$ROW["REMARK"];
   $EMPLOY_POST=$ROW["EMPLOY_POST"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $EMPLOY_COMPANY=$ROW["EMPLOY_COMPANY"];
   $BY_EVALU_STAFFS=$ROW["BY_EVALU_STAFFS"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
  
  $BY_EVALU_NAME=substr(GetUserNameById($BY_EVALU_STAFFS),0,-1);
   $SELECT_FLAG=0;
   if($BY_EVALU_NAME=="")
   {
      $SELECT_FLAG=1;
      $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$BY_EVALU_STAFFS'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $BY_EVALU_NAME=$ROW1["STAFF_NAME"];
   }
  $APPROVE_PERSON_NAME=GetUserNameById($ROW["APPROVE_PERSON"]);
  
  if($REPORT_TIME=="0000-00-00")
     $REPORT_TIME="";
  if($RECEIVE_TIME=="0000-00-00")
     $RECEIVE_TIME="";
	if($APPROVE_NEXT_TIME=="0000-00-00")
     $APPROVE_NEXT_TIME="";
	if($START_DATE=="0000-00-00")
     $START_DATE="";
  if($END_DATE=="0000-00-00")
     $END_DATE="";  
}
 
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑职称评定信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<form action="update.php"  method="post" name="form1" enctype="multipart/form-data" onSubmit="return CheckForm();">
 <table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("评定对象：")?></td>
      <td class="TableData">
        <input type="text" name="BY_EVALU_NAME" size="15" class="BigStatic" readonly value="<?=$BY_EVALU_NAME?>">&nbsp;
        <input type="hidden" name="BY_EVALU_STAFFS" value="<?=$BY_EVALU_STAFFS?>">
<?
if($SELECT_FLAG==0) 
{ 
?>
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','BY_EVALU_STAFFS', 'BY_EVALU_NAME','1')"><?=_("选择")?></a>
<?
}
?>
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
      <td class="TableData">
       <select name="GET_METHOD" style="background: white;" title="<?=_("职称获取方式可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""<?if($GET_METHOD=="") echo " selected";?>></option>
          <?=hrms_code_list("HR_STAFF_TITLE_EVALUATION",$GET_METHOD)?>
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
        <input type="text" name="START_DATE" size="15" maxlength="10" class="BigInput" id="start_time" value="<?=$START_DATE?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"><?=_("聘用结束时间：")?></td>
      <td class="TableData">
        <input type="text" name="END_DATE" size="15" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>s
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
      	<input type="hidden" value="<?=$EVALUATION_ID?>" name="EVALUATION_ID">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

echo "<meta http-equiv=X-UA-Compatible content=IE=EmulateIE7>";
$HTML_PAGE_TITLE = _("新建招聘筛选信息");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script>
function CheckForm()
{
   if(document.form1.PLAN_NAME.value == "")
   {
      alert("<?=_("请选择计划名称")?>");
      return false;
   }
   if(document.form1.EMPLOYEE_NAME.value == "")
   {
      alert("<?=_("请选择应聘人姓名")?>");
      return false;
   }
   if(document.form1.EMPLOYEE_PHONE.value == "")
   {
      alert("<?=_("联系电话不能为空")?>");
      return false;
   }
   return true;
}
function read_info(recruiter)
{
   _get("load_recruiter.php","EXPERT_ID="+recruiter, show_info);
}
function show_info(req)
{
   if(req.status==200)
   {
      if(req.responseText!=";;")
      {
      	 var sliceOfArray = req.responseText.split(";")
         document.form1.POSITION.value=sliceOfArray[0];
         document.form1.EMPLOYEE_MAJOR.value=sliceOfArray[1];
         document.form1.EMPLOYEE_PHONE.value=sliceOfArray[2];
         //document.form1.PLAN_NAME.value=sliceOfArray[3];
         //document.form1.PLAN_NO.value=sliceOfArray[4];
         var plan_name=document.form1.PLAN_NAME.options;
         var val=sliceOfArray[4];
         for(i=0;i<plan_name.length;i++)
         {
         		if(plan_name[i].value===val)
         			plan_name[i].selected="selected";
         }

      }
      else
      	 alert("<?=_("没有这个人的信息")?>");
   }
}

function LoadWindow()
{
    var userAgent = navigator.userAgent.toLowerCase();
    var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
    var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
    var ua_match = /(trident)(?:.*rv:([\w.]+))?/.exec(userAgent) || /(msie) ([\w.]+)/.exec(userAgent);
    var is_ie = ua_match && (ua_match[1] == 'trident' || ua_match[1] == 'msie') ? true : false;
    URL="employee_name_select";
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
        loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
        window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
    }
    else
    {
        event =arguments.callee.caller.arguments[0];
        loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
        loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
        window.open(URL,"parent","status=0,resizable=yes,top="+loc_y+",left="+loc_x+",dialog=yes,modal=yes,dependent=yes,minimizable=no,toolbar=no,menubar=no,location=no,scrollbars=yes",true);
    }
  
}

function LoadWindow2()
{
  URL="plan_no_info/?PLAN_NO=<?=$PLAN_NO?>";
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  if(window.showModalDialog){
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }else{
      window.open(URL,"parent","height=245,width=320,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);

  }
}

function sendform(deliver)
{
	document.form1.op.value="1";
	document.form1.deliver.value=deliver;
	if(CheckForm())
	   document.form1.submit();
}
function resetTime()
{
   document.form1.NEXT_DATE_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
}

</script>


<body class="bodycolor">
	<?
		$STEP_FLAG=1
	?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建招聘筛选信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" id="form1" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
  <tr>
    <td nowrap class="TableData" colspan="4" align="center"><?=_("筛选准备")?></td>
  </tr>
  <tr>
    <td nowrap class="TableData" style="width: 20%"><?=_("应聘者姓名：")?></td>
    <td class="TableData" style="width: 20%">
      <INPUT type="text" name="EMPLOYEE_NAME" class=BigInput size="15"  readonly value="<?=$EMPLOYEE_NAME?>">
      <INPUT type="hidden" name="EXPERT_ID" value="">
      <a href="javascript:;" class="orgAdd" onClick="LoadWindow()"><?=_("选择")?></a>
	  
    </td>
   <td nowrap class="TableData" style="width: 20%"><?=_("计划名称：")?></td>
    <td class="TableData" style="width: 20%">
     <select name="PLAN_NAME" class="BigSelect">
     	  <option value=""></option>

<?
      $query = "SELECT PLAN_NAME,ADD_TIME,PLAN_NO from HR_RECRUIT_PLAN where PLAN_STATUS='1' order by ADD_TIME desc";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $PLAN_NAME=$ROW["PLAN_NAME"];
         $PLAN_NO=$ROW["PLAN_NO"];

?>
          <option value="<?=$PLAN_NO?>"><?=$PLAN_NAME?></option>
<?
      }
?>
     </select>
    </td>  
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("应聘岗位：")?></td>
    <td class="TableData" >
      <INPUT type="text" name="POSITION" class=BigInput size="15" value="<?=$POSITION?>">
    </td> 
    <td nowrap class="TableData"><?=_("所学专业：")?></td>
    <td class="TableData" >
      <INPUT type="text"name="EMPLOYEE_MAJOR" class=BigInput size="15" value="<?=$EMPLOYEE_MAJOR?>">
    </td>    
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("联系电话：")?></td>
    <td class="TableData">
      <INPUT type="text"name="EMPLOYEE_PHONE" class="BigInput" size="15" value="<?=$EMPLOYEE_PHONE?>">
    </td>
    <td nowrap class="TableData"><?=_("发起人：")?></td>
    <td class="TableData" >
      <INPUT type="text" name="TRANSACTOR_STEP_NAME" size="15" class="BigStatic" readonly value="<?=$_SESSION["LOGIN_USER_NAME"]?>">&nbsp;
      <INPUT type="hidden" name="TRANSACTOR_STEP" value="<?=$_SESSION["LOGIN_USER_ID"]?>">
    </td>     
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("下一次筛选办理人：")?></td>
    <td class="TableData">
      <INPUT type="text" name="NEXT_TRANSA_STEP_NAME" size="15" class="BigStatic" readonly value="">
      <INPUT type="hidden" name="NEXT_TRANSA_STEP" value="<?=$NEXT_TRANSA_STEP?>">
      <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','NEXT_TRANSA_STEP', 'NEXT_TRANSA_STEP_NAME')"><?=_("选择")?></a>
    </td>
    <td nowrap class="TableData"><?=_("下一次筛选时间：")?></td>
    <td class="TableData">
      <input type="text" name="NEXT_DATE_TIME" size="20" maxlength="20" class="BigInput" value="<?=$NEXT_DATE_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("置为当前时间")?></a>
    </td>    
  </tr>
  <tr>
    <td nowrap class="TableData"> <?=_("提醒：")?></td>
    <td class="TableData" colspan=3><?=sms_remind(65);?></td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan=4 nowrap>
    	<input type="hidden" name="$STEP_FLAG">
      <input type="submit" value="<?=_("确定")?>" class="BigButton">
    </td>
  </tr>
</table>
</form>
</body>
</html>
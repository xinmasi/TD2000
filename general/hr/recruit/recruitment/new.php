<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");

echo "<meta http-equiv=X-UA-Compatible content=IE=EmulateIE8>";
$HTML_PAGE_TITLE = _("������Ƹ¼����Ϣ");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
  var userAgent = navigator.userAgent.toLowerCase();
  var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
  var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
  var ua_match = /(trident)(?:.*rv:([\w.]+))?/.exec(userAgent) || /(msie) ([\w.]+)/.exec(userAgent);
  var is_ie = ua_match && (ua_match[1] == 'trident' || ua_match[1] == 'msie') ? true : false;
function CheckForm()
{
   if(document.form1.PLAN_NO.value == "")
   {
      alert("<?=_("�ƻ���Ų���Ϊ��")?>");
      return false;
   }
   if(document.form1.ASSESSING_OFFICER.value == "")
   {
      alert("<?=_("¼�ø����˲���Ϊ��")?>");
      return false;
   }
   if(document.form1.DEPARTMENT.value == "")
   {
      alert("<?=_("��Ƹ���Ų���Ϊ��")?>");
      return false;
   }
   if(document.form1.ON_BOARDING_TIME.value == "")
   {
      alert("<?=_("��ʽ��ְʱ�䲻��Ϊ��")?>");
      return false;
   }
   if(document.form1.STARTING_SALARY_TIME.value == "")
   {
      alert("<?=_("��ʽ��нʱ�䲻��Ϊ��")?>");
      return false;
   }
   if(document.form1.JOB_STATUS.value == "")
   {
      alert("<?=_("��Ƹ��λ����Ϊ��")?>");
      return false;
   }
   if(document.form1.APPLYER_NAME.value == "")
   {
      alert("<?=_("ӦƸ����������Ϊ��")?>");
      return false;
   }
   return true;
}

function LoadWindow()
{
  URL="plan_no_info/?PLAN_NO=<?=$PLAN_NO?>";
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
function LoadWindow1()
{ 
  URL="employee_name_select/?EXPERT_ID=<?=$EXPERT_ID?>";
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
         document.form1.JOB_STATUS.value=sliceOfArray[0];
         //document.form1.EMPLOYEE_MAJOR.value=sliceOfArray[1];
         //document.form1.EMPLOYEE_PHONE.value=sliceOfArray[2];
      }
      else
      	 alert("<?=_("û������˵���Ϣ")?>");
   }
}
</script>


<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("������Ƹ¼����Ϣ")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
<table class="TableBlock" width="60%" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("�ƻ����ƣ�")?></td>
      <td class="TableData" >
        <INPUT type="text"name="PLAN_NAME" class=BigStatic size="15"  readonly>
        <INPUT type="hidden" name="PLAN_NO" value="">
        <a href="javascript:;" class="orgAdd" onClick="LoadWindow()"><?=_("ѡ��")?></a>
      </td>
      <td nowrap class="TableData"><?=_("ӦƸ��������")?></td>
      <td class="TableData" >
        <INPUT type="text" name="APPLYER_NAME" class=BigStatic size="15"  value="<?=$EMPLOYEE_NAME?>">
        <INPUT type="hidden" name="EXPERT_ID" value="">
        <a href="javascript:;" class="orgAdd" onClick="LoadWindow1()"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��Ƹ��λ��")?></td>
      <td class="TableData" >
        <INPUT type="text"name="JOB_STATUS" class=BigStatic size="15" readonly>
      </td>
      <td nowrap class="TableData">OA<?=_("���û�����")?></td>
      <td class="TableData" colspan=3>
        <INPUT type="text"name="OA_NAME" class=BigInput size="15" >
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("¼�ø����ˣ�")?></td>
      <td class="TableData">
       <INPUT type="text"name="ASSESSING_OFFICER_NAME" class="BigStatic"  size="15" readonly value="">
       <input type="hidden" name="ASSESSING_OFFICER" value="<?=$ASSESSING_OFFICER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','ASSESSING_OFFICER', 'ASSESSING_OFFICER_NAME')"><?=_("ѡ��")?></a>
      </td>
    	<td nowrap class="TableData"><?=_("¼�����ڣ�")?></td>
      <td class="TableData"><input type="text" name="ASS_PASS_TIME" size="15" maxlength="10" class="BigInput" value="<?=$ASS_PASS_TIME?>" onClick="WdatePicker()"/></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��Ƹ���ţ�")?></td>
    	<td class="TableData" colspan=3>
    	  <input type="hidden" name="DEPARTMENT">
        <input type="text" name="DEPARTMENT_NAME" value="" class=BigStatic size=15 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','DEPARTMENT','DEPARTMENT_NAME')"><?=_("ѡ��")?></a>             
      </td>
    </tr> 
    <tr>
    	<td nowrap class="TableData" width="100"><?=_("Ա������")?></td>
    	<td class="TableData">
        <select name="TYPE" class="BigSelect">
				<?=hrms_code_list("STAFF_OCCUPATION",$STAFF_OCCUPATION);?>
        </select>    	
    	</td>   
      <td nowrap class="TableData"><?=_("�����ȼ���")?></td>
      <td class="TableData" colspan=3>
        <INPUT type="text"name="ADMINISTRATION_LEVEL" class=BigInput size="15" >
      </td>
    </tr> 
    <tr>
    	<td nowrap class="TableData"><?=_("ְ��")?></td>
      <td class="TableData" >
        <INPUT type="text"name="JOB_POSITION" class=BigInput size="15" >
      <td nowrap class="TableData" width="100"><?=_("ְ�ƣ�")?></td>
      <td class="TableData"  width="180">
        <select name="PRESENT_POSITION" class="BigSelect">
				<?=hrms_code_list("PRESENT_POSITION",$PRESENT_POSITION);?>
        </select>
    	</td>
    </tr> 
    <tr>
    	<td nowrap class="TableData"><?=_("��ʽ��ְʱ�䣺")?><font color=red>(*)</font></td>
      <td class="TableData">
      	<input type="text" name="ON_BOARDING_TIME" size="15" maxlength="10" class="BigInput" value="<?=$ON_BOARDING_TIME?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"><?=_("��ʽ��нʱ�䣺")?></td>
      <td class="TableData"><input type="text" name="STARTING_SALARY_TIME" size="15" maxlength="10" class="BigInput" value="<?=$STARTING_SALARY_TIME?>" onClick="WdatePicker()"/></td>
    </tr>   
     <tr>
      <td nowrap class="TableData"><?=_("��ע��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="66" rows="5" class="BigInput" value=""></textarea>
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
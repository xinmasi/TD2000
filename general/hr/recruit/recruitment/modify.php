<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");


$HTML_PAGE_TITLE = _("�༭��Ƹ¼����Ϣ");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.PLAN_NAME.value == "")
   {
      alert("<?=_("�ƻ����Ʋ���Ϊ��")?>");
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
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  if(window.showModalDialog){
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }else{
  	window.open(URL,"loadwin","height=245,width=320,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
  }
}
function LoadWindow1()
{
  URL="employee_name_select/?EXPERT_ID=<?=$EXPERT_ID?>";
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  if(window.showModalDialog){
     window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }else{
  	 window.open(URL,"loadwin1","height=245,width=320,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
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

<?
$query="select * from HR_RECRUIT_RECRUITMENT where RECRUITMENT_ID='$RECRUITMENT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   
   $REQUIREMENTS_COUNT++;

   $RECRUITMENT_ID=$ROW["RECRUITMENT_ID"];
   $PLAN_NO=$ROW["PLAN_NO"];
   $APPLYER_NAME=$ROW["APPLYER_NAME"];
   $JOB_STATUS=$ROW["JOB_STATUS"];
   $ASSESSING_OFFICER=$ROW["ASSESSING_OFFICER"];
   $ASS_PASS_TIME=$ROW["ASS_PASS_TIME"];
   $DEPARTMENT=$ROW["DEPARTMENT"];
   $TYPE=$ROW["TYPE"];
   $ADMINISTRATION_LEVEL=$ROW["ADMINISTRATION_LEVEL"];
   $JOB_POSITION=$ROW["JOB_POSITION"];
   $PRESENT_POSITION=$ROW["PRESENT_POSITION"];
   $ON_BOARDING_TIME=$ROW["ON_BOARDING_TIME"];
   $STARTING_SALARY_TIME=$ROW["STARTING_SALARY_TIME"];
   $REMARK=$ROW["REMARK"];
   $OA_NAME=$ROW["OA_NAME"];
   
   $ASSESSING_OFFICER_NAME=substr(GetUserNameById($ASSESSING_OFFICER),0,-1);
   $DEPARTMENT_NAME=substr(GetDeptNameById($DEPARTMENT),0,-1);
   $TYPE_NAME=get_hrms_code_name($TYPE,"STAFF_OCCUPATION");
   $PRESENT_POSITION_NAME=get_hrms_code_name($PRESENT_POSITION,"PRESENT_POSITION");
}

?>
<body class="bodycolor">

<table border="0" width="770" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�༭��Ƹ¼����Ϣ")?></span>&nbsp;&nbsp;</td>
  </tr>
</table>

<form enctype="multipart/form-data" action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
<table class="TableBlock" width="60%" align="center">
   <tr>
   	<td nowrap class="TableData"><?=_("�ƻ����ƣ�")?></td>
     <td class="TableData" >
        <INPUT type="text"name="PLAN_NAME" class=BigStatic size="15"  readonly value="<?=$PLAN_NO?>">
        <INPUT type="hidden" name="PLAN_NO" value="<?=$PLAN_NO?>">
        <a href="javascript:;" class="orgAdd" onClick="LoadWindow()"><?=_("ѡ��")?></a>
      </td>
      <td nowrap class="TableData"><?=_("ӦƸ��������")?></td>
      <td class="TableData" >
        <INPUT type="text" name="APPLYER_NAME" class=BigStatic size="15"  value="<?=$APPLYER_NAME?>" >
        <INPUT type="hidden" name="EXPERT_ID" value="">
        <a href="javascript:;" class="orgAdd" onClick="LoadWindow1()"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��Ƹ��λ��")?></td>
      <td class="TableData" >
        <INPUT type="text"name="JOB_STATUS" class=BigStatic size="15" value="<?=$JOB_STATUS?>" readonly>
      </td>
      <td nowrap class="TableData">OA<?=_("���û�����")?></td>
      <td class="TableData" colspan=3>
        <INPUT type="text"name="OA_NAME" class=BigInput size="15" value="<?=$OA_NAME?>">
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("¼�ø����ˣ�")?></td>
      <td class="TableData">
       <INPUT type="text"name="ASSESSING_OFFICER_NAME" class=BigStatic size="15" readonly value="<?=$ASSESSING_OFFICER_NAME?>">
       <input type="hidden" name="ASSESSING_OFFICER" value="<?=$ASSESSING_OFFICER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','ASSESSING_OFFICER', 'ASSESSING_OFFICER_NAME')"><?=_("ѡ��")?></a>
      </td>
    	<td nowrap class="TableData"><?=_("¼�����ڣ�")?></td>
      <td class="TableData">
      	<input type="text" name="ASS_PASS_TIME" size="10" maxlength="10" class="BigInput" value="<?=$ASS_PASS_TIME?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��Ƹ���ţ�")?></td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="DEPARTMENT" value=<?=$DEPARTMENT?>> 
			  <input type="text" name="DEPARTMENT_NAME" value="<?=$DEPARTMENT_NAME?>" class=BigStatic size=15 maxlength=100 readonly> 
    	</td>
    </tr> 
    <tr>
    	 <td nowrap class="TableData" width="100"><?=_("Ա������")?></td>
    	 <td class="TableData">
        	<select name="TYPE" class="BigSelect">
					<?=hrms_code_list("STAFF_OCCUPATION",$TYPE);?>
        </select>    	
    		</td>              
        <td nowrap class="TableData"><?=_("�����ȼ���")?></td>
      <td class="TableData" colspan=3>
        <INPUT type="text"name="ADMINISTRATION_LEVEL" class=BigInput size="15" value="<?=$ADMINISTRATION_LEVEL?>">
    </tr> 
    <tr>
    	 <td nowrap class="TableData"><?=_("ְ��")?></td>
      <td class="TableData" >
        <INPUT type="text"name="JOB_POSITION" class=BigInput size="15" value="<?=$JOB_POSITION?>">
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
      	<input type="text" name="ON_BOARDING_TIME" size="10" maxlength="10" class="BigInput" value="<?=$ON_BOARDING_TIME?>" onClick="WdatePicker()"/>
      </td>
      <td nowrap class="TableData"><?=_("��ʽ��нʱ�䣺")?></td>
      <td class="TableData">
      	<input type="text" name="STARTING_SALARY_TIME" size="10" maxlength="10" class="BigInput" value="<?=$STARTING_SALARY_TIME?>" onClick="WdatePicker()"/>
      </td>
    </tr>
     <tr>
      <td nowrap class="TableData"><?=_("��ע��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="66" rows="5" class="BigInput" ><?=$REMARK?></textarea>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" name="RECRUITMENT_ID" size="20" value="<?=$RECRUITMENT_ID?>">
        <input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="location='index1.php';">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
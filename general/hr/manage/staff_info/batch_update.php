<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("�������µ���");
include_once("inc/header.inc.php");
$PARA_ARRAY=get_sys_para("LEAVE_BY_SENIORITY,ENTRY_RESET_LEAVE");
$entry_reset_leave = $PARA_ARRAY["ENTRY_RESET_LEAVE"];//�Ƿ�������ְ���ڼ������
$leave_by_seniority = $PARA_ARRAY["LEAVE_BY_SENIORITY"];//�Ƿ���������������
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script language="javascript">
function CheckForm()
{
  with(document.form1)
  {
    if (TO_ID.value=="")
    { 
       alert("<?=_("����Ӹ��¶���")?>");
       return false;
    }
    if (MODE.value=="")
    { 
       alert("<?=_("��ѡ����·�ʽ��")?>");
       return false;
    } 
    if (SELECTITEM.value!="-1"&&TContext.value=="")
    { 
       alert("<?=_("��������Ҫ���µ����ݣ�")?>");
	   TContext.focus();
       return false;
    }
	  if (isNaN(LEAVE_TYPE.value)||LEAVE_TYPE.value<0||LEAVE_TYPE.value>365)
    {
       alert("<?=_("��������Ϊ���֣�")?>");
	   LEAVE_TYPE.focus();
	   return false;
	  } 
  }
  form1.submit();
}
function changeit(sel)
{
	if(sel=="overwrite")
	{
	  document.getElementById("HOLIDAY").style.display="";
	  document.getElementById("WORK").style.display="";
	  document.getElementById("USERDEF").style.display="";
	}
	else
	{
	  document.getElementById("HOLIDAY").style.display="none";
	  document.getElementById("WORK").style.display="none";
	  document.getElementById("USERDEF").style.display="none";
  }
}
</script>

<body class="bodycolor">
<form enctype="multipart/form-data" action="batch_submit.php" method="post" name="form1">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" HEIGHT="20">
     <span class="big3">
        <?=_("�������µ���")?>
     </span>
    </td>
  </tr>
</table>

<table align="center" width="90%" class="TableBlock">
	<tr>
     <td nowrap class="TableData"><b><?=_("ѡ����¶���")?></b></td>
		 <td nowrap class="TableData" colspan="3"> 
		    <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols="40" name="TO_NAME" rows="2" style="overflow-y:auto;" class="SmallStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','9','TO_ID', 'TO_NAME','','','',1)"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
		</td>
	</tr>
  <tr>
    <td nowrap class="TableData"><b><?=_("���·�ʽ��")?></b></td>
	  <td colspan="3" class="TableData"><input type="radio" name="MODE" value="overwrite" checked="checked" onClick="javascript:changeit(this.value)"><?=_("��д")?>
	  <input type="radio" name="MODE" value="append" onClick="javascript:changeit(this.value)"><?=_("׷��")?></td>
	</tr>
  <tr>
      <td nowrap class="TableData" colspan="4"><b><?=_("�������ݣ�")?></b></td>
  </tr>
  <tr class="TableData" id="HOLIDAY">
	  <td colspan="4" class="TableData">&nbsp;<?=_("���������")?>
	  	<input type="text" name="LEAVE_TYPE" size="6" maxlength="4" <?if($leave_by_seniority=='1'){?>readonly class="BigStatic"<?}else{?>class="BigInput"<?}?>> <?=_("��")?></td>
    </tr>
  <tr class="TableData" id="WORK">
	  <td colspan="4" class="TableData">&nbsp;<?=_("��ְ״̬��")?>
	  	<select name="WORK_STATUS" class="SmallSelect">
	  	  <option value=""></option>
	  	  <?=hrms_code_list("WORK_STATUS",$WORK_STATUS); ?>
	  	</select>
  </tr>
  <tr class="TableData" id="WORK">
  	<td colspan="4" class="TableData">
  		 &nbsp;<label for="count_age"><?=_("���ݳ������ڸ�������")?></label><input type="checkbox" id="count_age" name="count_age" class="SmallSelect"/>
  		 &nbsp;&nbsp;<label for="count_job_age"><?=_("������ְ���ڸ��±���λ����")?></label><input type="checkbox" id="count_job_age" name="count_job_age" class="SmallSelect"/>
  		 &nbsp;&nbsp;<label for="count_work_age"><?=_("���ݲμӹ������ڸ����ܹ���")?></label><input type="checkbox" id="count_work_age" name="count_work_age" class="SmallSelect"/>
  	</td>
  	
  	
  </tr>
	<tr>
      <td nowrap class="TableData" colspan="4"> 
    	<select name="SELECTITEM" class="SmallSelect">
        <option value="-1"><?=_("ѡ��Ҫ���µ��ֶ�")?></option>
        <option value="CERTIFICATE"><?=_("ְ�����")?></option>
        <option value="SURETY"><?=_("������¼")?></option>
        <option value="INSURE"><?=_("�籣�������")?></option>
        <option value="BODY_EXAMIM"><?=_("����¼")?></option>
        <option value="REMARK"><?=_("��ע")?></option>
      </select>
      <textarea cols="45" name="TContext" rows="2" class="BigInput" wrap="on"></textarea>
		</td>
	</tr>	
  <tr>
    <td colspan="6" >
     <?=get_field_table(get_field_html("HR_STAFF_INFO","$USER_ID"))?>
    </td>
  </tr>
    <tfoot align="center" class="TableFooter">
      <td nowrap colspan="4" align="center">
        <input type="hidden" value="<?=$DEPT_ID?>" name="DEPT_ID">
		<input type="button" value="<?=_("����")?>" class="BigButton" onClick="CheckForm()">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='user_list.php?DEPT_ID=<?=$DEPT_ID?>'">
      </td>
    </tfoot>
    </table>
</form>
</body>
</html>
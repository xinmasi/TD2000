<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("����͵���");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<script Language="JavaScript">
function my_export()
{
	 var CONTENT_NAME="";
   var to_id=document.form1.TO_ID.value;
   var to_name=document.form1.TO_NAME.value;

	 var priv_id=document.form1.PRIV_ID.value;
	 var priv_name=document.form1.PRIV_NAME.value;

	 var to_id2=document.form1.TO_ID2.value;
	 var to_name2=document.form1.TO_NAME2.value;

   if(document.form1.BEGIN_DATE.value!="" && document.form1.END_DATE.value!="")
   {
      if(document.form1.BEGIN_DATE.value > document.form1.END_DATE.value)
      { alert("<?=_("�����н�������Ҫ���ڿ�ʼ����")?>");
        return (false);
      }
    }
	 for (i=0; i< document.form1.select1.options.length; i++)
	 		CONTENT_NAME+=document.form1.select1.options[i].text+',';
   document.form1.CONTENT_NAME.value= CONTENT_NAME;
   mysubmit();
}

// �ֶ�ѡ��Javascrpt
	function selected(){
		var isSelectedObj = document.getElementById("isSelected");
		var trObj = document.getElementById("conditionTr");
		if(isSelectedObj.checked){
			trObj.style.display="";
		}else{
			trObj.style.display="none";
		}
	}

function func_insert()
{
 for (i=0; i<document.form1.select2.options.length; i++)
 {
   if(document.form1.select2.options[i].selected)
   {
     option_text=document.form1.select2.options[i].text;
     option_value=document.form1.select2.options[i].value;
     option_style_color=document.form1.select2.options[i].style.color;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;
     my_option.style.color=option_style_color;

     pos=document.form1.select2.options.length;
     document.form1.select1.options.add(my_option,pos);
     document.form1.select2.remove(i);
     i--;
  }
 }//for
}

function func_delete()
{
 for (i=0;i<document.form1.select1.options.length;i++)
 {
   if(document.form1.select1.options[i].selected)
   {
     option_text=document.form1.select1.options[i].text;
     option_value=document.form1.select1.options[i].value;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;

     if(option_text.indexOf("[<?=_("��ѡ")?>]")>0)
        continue;//  return;
     pos=document.form1.select2.options.length;
     document.form1.select2.options.add(my_option,pos);
     document.form1.select1.remove(i);
     i--;
  }
 }//for
}

function func_select_all1()
{
 for (i=document.form1.select1.options.length-1; i>=0; i--)
   document.form1.select1.options[i].selected=true;
}

function func_select_all2()
{
 for (i=document.form1.select2.options.length-1; i>=0; i--)
   document.form1.select2.options[i].selected=true;
}

function func_up()
{
  sel_count=0;
  for (i=document.form1.select1.options.length-1; i>=0; i--)
  {
    if(document.form1.select1.options[i].selected)
       sel_count++;
  }

  if(sel_count==0)
  {
     alert("<?=_("��ѡ������һ�")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("ֻ��ѡ������һ�")?>");
     return;
  }

  i=document.form1.select1.selectedIndex;

  if(i!=0)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=document.form1.select1.options[i].text;
    my_option.value=document.form1.select1.options[i].value;

    document.form1.select1.options.add(my_option,i-1);
    document.form1.select1.remove(i+1);
    document.form1.select1.options[i-1].selected=true;
  }
}

function func_down()
{
  sel_count=0;
  for (i=document.form1.select1.options.length-1; i>=0; i--)
  {
    if(document.form1.select1.options[i].selected)
       sel_count++;
  }

  if(sel_count==0)
  {
     alert("<?=_("��ѡ������һ�")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("ֻ��ѡ������һ�")?>");
     return;
  }

  i=document.form1.select1.selectedIndex;

  if(i!=document.form1.select1.options.length-1)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=document.form1.select1.options[i].text;
    my_option.value=document.form1.select1.options[i].value;

    document.form1.select1.options.add(my_option,i+2);
    document.form1.select1.remove(i);
    document.form1.select1.options[i+1].selected=true;
  }
}

function mysubmit()
{
   fld_str="";
   for (i=0; i< document.form1.select1.options.length; i++)
   {
      options_value=document.form1.select1.options[i].value;
      fld_str+=options_value+",";
    }
   document.form1.fieldArrStr.value = fld_str;
   window.document.form1.submit();
}

function daoru(){
  str="in_out/import/index.php";
  window.open (str, 'newwindow', 'height=600, width=600, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');

}

function CheckForm()
{
	if(document.form2.EXCEL_FILE.value=="")
	{
		alert("<?=_("��ѡ��Ҫ������ļ���")?>");
		return (false);
	}
	if(document.form2.EXCEL_FILE.value!="")
	{
		var file_temp=document.form2.EXCEL_FILE.value,file_name;
		var Pos;
		Pos=file_temp.lastIndexOf("\\");
		file_name=file_temp.substring(Pos+1,file_temp.length);
		document.form2.FILE_NAME.value=file_name;
	}
	return (true);
}

</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/export.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��ͬ���ݵ���")?></span>
    </td>
  </tr>
</table>

<br>

<form action="export.php" enctype="multipart/form-data"  method="post" name="form1">
<table class="TableBlock" width="60%" align="center">
  <tr>
    <td nowrap class="TableData"><?=_("ѡ��Χ(����)��")?></td>
    <td class="TableData">
      <input type="hidden" id="TO_ID" name="TO_ID" value="">
      <textarea cols=35 id="TO_NAME" name="TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectDept('')"><?=_("���")?></a>
     <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
     <span style="color: #666666;">��������Ϊ���š���ɫ����Ա���ߵĲ���</span>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("ѡ��Χ(��ɫ)��")?></td>
    <td class="TableData">
      <input type="hidden" id="PRIV_ID" name="PRIV_ID" value="">
      <textarea cols=35 id="PRIV_NAME" name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("ѡ��Χ(��Ա)��")?></td>
    <td class="TableData">
      <input type="hidden" id="TO_ID2" name="TO_ID2" value="">
      <textarea cols=35 name="TO_NAME2" id="TO_NAME2" rows="2" class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','TO_ID2', 'TO_NAME2')"><?=_("���")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID2', 'TO_NAME2')"><?=_("���")?></a>
    </td>
  </tr>

    <tr>
      <td nowrap class="TableData" width="100"><?=_("��ͬ�Ǽ�ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" id="start_time" name="BEGIN_DATE" size="12" maxlength="10" class="BigInput"  onClick="WdatePicker()">
         <?=_("��")?>&nbsp;
        <input type="text" name="END_DATE" size="12" maxlength="10" class="BigInput"  onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})">

      </td>
	  <tr>
	  <td  class='efCellCtrl'><input type="checkbox" name="isSelected" id="isSelected" value="selected" onClick="selected()" /> <?=_("ѡ�񵼳��ֶ�")?></td><td></td>

	  </tr>
	  <tr id="conditionTr" style="display:none">
		<td colspan="2" class='efCellCtrl'>

			<table width="500" border="1" cellspacing="0" cellpadding="3" align="center" bordercolorlight="#F7F7F7" bordercolordark="#F7F7F7" class="big">
			  <tr bgcolor="#CCCCCC">
				<td align="center"><?=_("����")?></td>
				<td align="center"><b><?=_("��ѡ���ֶ�")?></b></td>
				<td align="center"><?=_("ѡ��")?></td>
				<td align="center" valign="top"><b><?=_("��ѡ�ֶ�")?></b></td>
			  </tr>
			  <tr>
				<td align="center" bgcolor="#F7F7F7">
				  <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_up();"/>
				  <br><br>
				  <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_down();"/>				</td>
				<td valign="top" align="center" bgcolor="#F7F7F7">
				<select  name="select1[]" id="select1" ondblclick="func_delete();" MULTIPLE style=" min-width: 107px; min-height: 100px;*width: 107px;">
				</select>
				<input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all1();" class="SmallInput"/>				</td>

				<td align="center" bgcolor="#F7F7F7">
				  <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_insert();"/>
				  <br><br>
				  <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_delete();"/>				</td>

				<td align="center" valign="top" bgcolor="#F7F7F7">
				<select  name="select2[]" id="select2" ondblclick="func_insert();" MULTIPLE style=" min-width: 107px;min-height: 100px; *width: 107px;"/>
<?
//$thArr		= array(_("����"),_("�û���"),_("����"),_("��ͬ���"),_("��ͬ����"),_("��ͬ����"),_("��ͬǩ������"),_("������Ч����"),_("��������"),_("���õ�������"),_("��ͬ�Ƿ�ת��"),_("��ͬת������"),_("��ͬ��Ч����"),_("��ͬ����"),_("��ͬ��������"),_("��ͬ�Ƿ���"),_("��ͬ�������"),_("��ͬ״̬"),_("ǩԼ����"),_("�Ǽ�ʱ��"),_("����ʱ��"),_("������Ա"),_("��ע"));
//$fieldArr	= array('NAME','USER_ID','DEPTNAME','CONTRACT_NO','CONTRACT_TYPE','CONTRACT_SPECIALIZATION','MAKE_CONTRACT','TRAIL_EFFECTIVE_TIME','PROBATIONARY_PERIOD','TRAIL_OVER_TIME','PASS_OR_NOT','PROBATION_END_DATE','PROBATION_EFFECTIVE_DATE','ACTIVE_PERIOD','CONTRACT_END_TIME','REMOVE_OR_NOT','CONTRACT_REMOVE_TIME','STATUS','SIGN_TIMES','ADD_TIME','REMIND_TIME','REMIND_USER_NAME','REMARK');
$thArr		= array(_("�û���"),_("����"),_("��ͬ���"),_("��ͬ����"),_("��ͬ״̬"),_("��ͬ��������"),_("��ͬǩԼ��˾"),_("��ͬǩ������"),_("��ͬ��Ч����"),_("��ͬ��ֹ����"),_("�Ƿ�������"),_("���ý�ֹ����"),_("��Ա�Ƿ�ת��"),_("��ͬ�Ƿ��ѽ��"),_("��ͬ�������"),_("��ͬ�Ƿ���ǩ"),_("��ǩ��������"),_("��ע"));
$fieldArr	= array('USER_ID','STAFF_NAME','STAFF_CONTRACT_NO','CONTRACT_TYPE','STATUS','CONTRACT_SPECIALIZATION','CONTRACT_ENTERPRIES','MAKE_CONTRACT','PROBATION_EFFECTIVE_DATE','CONTRACT_END_TIME','IS_TRIAL','TRAIL_OVER_TIME','PASS_OR_NOT','REMOVE_OR_NOT','CONTRACT_REMOVE_TIME','IS_RENEW','RENEW_TIME','REMARK');
for($i = 0; $i < count($fieldArr); $i ++)
{
    echo "<option value='".$fieldArr[$i]."'>$thArr[$i]</option>";
}
?>
				</select>
				<input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all2();" class="SmallInput">				</td>
			  </tr>

			  <tr bgcolor="#CCCCCC">
				<td align="center" valign="top" colspan="4">
				<?=_("�����Ŀʱ���������CTRL��SHIFT�����ж�ѡ")?><br>				</td>
			  </tr>
			</table>		</td>
	</tr>
	<tr>
	  <td class='efCellCtrl' align="center">
			<input type="hidden" name="ID" id="ID" value=<?=$ID?> />
			<input type="hidden" name="ORDER_CLAUSE" id="ORDER_CLAUSE" value="" />
			<input type="hidden" name="LIMIT_CLAUSE" id="LIMIT_CLAUSE" value="" />
		<input type="hidden" name="fieldArrStr" value="" />
		</td>
	</tr>
    </tr>

    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
      	<input type="hidden" name="CONTENT_NAME" value="">
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="my_export();">
      </td>
    </tr>
  </table>
</form>

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/import.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��ͬ���ݵ���")?></span>
    </td>
  </tr>
</table>
<br>
<form name="form2" method="post" action="import.php?" enctype="multipart/form-data" onSubmit="return CheckForm();">
<table class="TableBlock" align="center" width="60%">  
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b><?=_("���ص���ģ�壺")?></b></td>
   <td width="400" align="left">
   	<a href="#" onClick="window.location='templet_export.php'"><?=_("��ͬ����ģ������")?></a>
   </td>
 </tr>
 <tr class="TableData" align="center" height="30">
   <td width="150" align="right"><b>&nbsp;&nbsp;<?=_("ѡ�����ļ���")?></b></td>
   <td align="left" width="400">
    <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
    <input type="hidden" name="FILE_NAME">
   </td>
 </tr> 
 <tr class="TableData" align="center" height="30">
  <td width="150" align="right"><b><?=_("˵����")?></b></td>
  <td width="400" align="left">
  	<span>
    <?=_("1���뵼��.xls�ļ���")?>
    <br>
    <?=_("2����ͬ����ģ���У�Ҫ���û�������Ϊ�գ������ܵ��룻")?>
    <br>
    <?=_("3����ͬ����ģ���У�Ҫ���ͬ��Ų��ظ��������ܵ��룻")?>
    <br>
    <?=_("4�����ݺ�ͬ��д��ͬ״̬�������С���ת�����ѽ����")?>
    <br>
    <?=_("5�����ڵĸ�ʽӦ�磺2009-10-15��")?>
    </span>
  </td>
 </tr>  
<tr>
  <td nowrap  class="TableControl" colspan="3" align="center">
  	<input type="submit" value="<?=_("����")?>" class="BigButton">
  </td>
 </tr> 
</table>
</form>
</body>
</html>
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("���õ�����Ϣ�����ֶ�");
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">

function func_find(select_obj,option_text)
{
 pos=option_text.indexOf("] ")+1;
 option_text=option_text.substr(0,pos);

 for (j=0; j<select_obj.options.length; j++)
 {
   str=select_obj.options[j].text;
   if(str.indexOf(option_text)>=0)
      return j;
 }//for

 return j;
}
function func_insert()
{
 for (i=document.form1.select2.options.length-1; i>=0; i--)
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

     //pos=func_find(select1,option_text);
     document.form1.select1.options.add(my_option);
     document.form1.select2.remove(i);
  }
 }//for
}
function func_delete()
{
 for (i=document.form1.select1.options.length-1; i>=0; i--)
 {
   if(document.form1.select1.options[i].selected)
   {
     option_text=document.form1.select1.options[i].text;
     option_value=document.form1.select1.options[i].value;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;

     //pos=func_find(select2,option_text);
     document.form1.select2.options.add(my_option);
     document.form1.select1.remove(i);
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
function exreport()
{
	 fld_str="";
	 fld_str1="";
    for (i=0; i< document.form1.select1.options.length; i++)
    {
     options_value=document.form1.select1.options[i].value;
     options_text=document.form1.select1.options[i].text;
     fld_str+=options_value+",";
     fld_str1+=options_text+",";
    }

  document.form1.FIELDMSG.value=fld_str;
  document.form1.FIELDMSGNAME.value=fld_str1;
  document.form1.submit();
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
     alert("<?=_("����˳��ʱ����ѡ������һ�")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("����˳��ʱ��ֻ��ѡ������һ�")?>");
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
     alert("<?=_("����˳��ʱ����ѡ������һ�")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("����˳��ʱ��ֻ��ѡ������һ�")?>");
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
</script>

<body class="bodycolor" topmargin="5" onLoad="">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/send.gif" width="18" HEIGHT="18"><span class="big3"> <?=_("���µ�����ѯ�б��ֶ�����")?></span>
    	<span class="small1"> <?=_("ע�⣺���µ�����ѯ�б��ֶ�����֮�����µ��������µ�����ѯ�б��ɿ�������������ã�")?></span>
    </td>
  </tr>
</table>
<?
$ALL_FIELD=array(array("STAFF_E_NAME",_("Ӣ����")),array("BEFORE_NAME",_("������")),array("STAFF_SEX",_("�Ա�")),array("STAFF_NO",_("���")),array("WORK_NO",_("����")),array("STAFF_CARD_NO",_("���֤����")),array("STAFF_BIRTH",_("��������")),
array("STAFF_AGE",_("����")),array("STAFF_NATIVE_PLACE",_("����")),array("STAFF_NATIONALITY",_("����")),array("STAFF_MARITAL_STATUS",_("����״��")),array("STAFF_POLITICAL_STATUS",_("������ò")),array("WORK_STATUS",_("��ְ״̬")),
array("JOIN_PARTY_TIME",_("�뵳ʱ��")),array("STAFF_PHONE",_("��ϵ�绰")),array("STAFF_MOBILE",_("�ֻ�����")),array("STAFF_MSN","MSN"),array("STAFF_QQ","QQ"),array("STAFF_EMAIL",_("�����ʼ�")),
array("HOME_ADDRESS",_("��ͥ��ַ")),array("JOB_BEGINNING",_("�μӹ���ʱ��")),array("OTHER_CONTACT",_("������ϵ��ʽ")),array("WORK_AGE",_("�ܹ���")),array("STAFF_HEALTH",_("����״��")),array("STAFF_DOMICILE_PLACE",_("�������ڵ�")),
array("STAFF_TYPE",_("�������")),array("DATES_EMPLOYED",_("��ְʱ��")),array("STAFF_HIGHEST_SCHOOL",_("ѧ��")),array("STAFF_HIGHEST_DEGREE",_("ѧλ")),array("GRADUATION_DATE",_("��ҵʱ��")),array("STAFF_MAJOR",_("רҵ")),array("GRADUATION_SCHOOL",_("��ҵԺУ")),
array("COMPUTER_LEVEL",_("�����ˮƽ")),array("FOREIGN_LANGUAGE1",_("��������1")),array("FOREIGN_LANGUAGE2",_("��������2")),array("FOREIGN_LANGUAGE3",_("��������3")),array("FOREIGN_LEVEL1",_("����ˮƽ1")),array("FOREIGN_LEVEL2",_("����ˮƽ2")),array("FOREIGN_LEVEL3",_("����ˮƽ3")),
array("STAFF_SKILLS",_("�س�")),array("WORK_TYPE",_("����")),array("ADMINISTRATION_LEVEL",_("��������")),array("STAFF_OCCUPATION",_("Ա������")),array("JOB_POSITION",_("ְ��")),array("PRESENT_POSITION",_("ְ��")),array("JOB_AGE",_("����λ����")),array("BEGIN_SALSRY_TIME",_("��нʱ��")),
array("LEAVE_TYPE",_("���ݼ�")),array("RESUME",_("����")),array("SURETY",_("������¼")),array("CERTIFICATE",_("ְ�����")),array("INSURE",_("�籣�������")),array("BODY_EXAMIM",_("����¼")),
array("PHOTO_NAME",_("��Ƭ")),array("WORK_LEVEL",_("ְ�Ƽ���")),array("WORK_JOB",_("��λ")),array("BANK1",_("������1")),array("BANK_ACCOUNT1",_("�˻�1")),array("BANK2",_("������2")),array("BANK_ACCOUNT2",_("�˻�2")),
array("REMARK",_("�� ע")));

$query = "select * from FIELDSETTING where TABLENAME='HR_STAFF_INFO' ORDER BY ORDERNO ASC ";
$cursor1=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor1))
{
 $temparray=array($ROW["FIELDNO"],$ROW["FIELDNAME"]);
 array_push($ALL_FIELD,$temparray);
}


$SYS_PARA_ARRAY = get_sys_para("HR_MANAGER_ARCHIVES");
$HRMS_OPEN_FIELDS=$SYS_PARA_ARRAY["HR_MANAGER_ARCHIVES"];

$OPEN_ARRAY=explode("|",$HRMS_OPEN_FIELDS);
$FIELD_ARRAY=explode(",",$OPEN_ARRAY[0]);
$NAME_ARRAY=explode(",",$OPEN_ARRAY[1]);
?>
<form action="archives_submit.php" method="post" name="form1">
  	<table width="600" height="100" align="center" border="1" cellspacing="0" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF" class="TableBlock" style="margin-top: 20px;">
      <tr bgcolor="#CCCCCC">
     <td align="center"><?=_("����")?></td>
    <td align="center"><b><?=_("���µ�����ѯ�б��ֶ�")?></b></td>
    <td align="center">&nbsp;</td>
    <td align="center" valign="top"><b><?=_("��ѡ�ֶ�")?></b></td>
  </tr>
  <tr>
  	<td align="center" bgcolor="#999999">
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_up();">
      <br><br>
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_down();">
    </td>
    <td valign="top" align="center" bgcolor="#CCCCCC">
    <select  name="select1" ondblclick="func_delete();" MULTIPLE style=" min-width: 107px; min-height: 330px; *height: 330px;*width: 107px;">
<?
for($I=0;$I<count($FIELD_ARRAY);$I++)
{
   if($FIELD_ARRAY[$I]=="" || $NAME_ARRAY[$I]=="")
      continue;
   echo "<option value=\"".$FIELD_ARRAY[$I]."\">".$NAME_ARRAY[$I]."</option>";
}
?>
    </select>
    <input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all1();" class="SmallInput">
    </td>
    <td align="center" bgcolor="#999999">
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_insert();">
      <br><br>
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_delete();">
    </td>
    <td align="center" valign="top" bgcolor="#CCCCCC">
    <select  name="select2" ondblclick="func_insert();" MULTIPLE style=" min-width: 107px; min-height: 330px; *height: 330px;*width: 107px;">
<?
for($I=0;$I<count($ALL_FIELD);$I++)
{
   if($ALL_FIELD[$I][0]=="" || $ALL_FIELD[$I][1]=="" || in_array($ALL_FIELD[$I][0],$FIELD_ARRAY))
      continue;

   echo "<option value=\"".$ALL_FIELD[$I][0]."\">".$ALL_FIELD[$I][1]."</option>";
}
?>
    </select>
    <input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all2();" class="SmallInput">
    </td>
  </tr>
    <tfoot align="center" class="TableFooter">
      <td nowrap colspan="4" align="center">
        <input type="button" value="<?=_("ȷ��")?>" class="BigButton" onClick="exreport()">
        <input type="hidden" name="FIELDMSG">
        <input type="hidden" name="FIELDMSGNAME">
      </td>
    </tfoot>
    </table>
</form>
</body>
</html>

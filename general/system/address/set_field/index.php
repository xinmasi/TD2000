<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�����б���ʾ�ֶ�");
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

<body class="bodycolor" onLoad="">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/send.gif" width="18" HEIGHT="18"><span class="big3"> <?=_("�����б���ʾ�ֶ�")?></span>
    	<span class="small1"> </span>
    </td>
  </tr>
</table>
<?
$ALL_FIELD=array(array("PSN_NAME",_("����")),array("SEX",_("�Ա�")),array("BIRTHDAY",_("����")),array("NICK_NAME",_("�ǳ�")),array("MINISTRATION",_("ְ��")),array("MATE",_("��ż")),
array("CHILD",_("��Ů")),array("DEPT_NAME",_("��λ����")),array("ADD_DEPT",_("��λ��ַ")),array("POST_NO_DEPT",_("��λ�ʱ�")),
array("TEL_NO_DEPT",_("�����绰")),array("FAX_NO_DEPT",_("��������")),array("ADD_HOME",_("��ͥסַ")),array("POST_NO_HOME",_("��ͥ�ʱ�")),array("TEL_NO_HOME",_("��ͥ�绰")),
array("MOBIL_NO",_("�ֻ�")),array("BP_NO",_("С��ͨ")),array("EMAIL",_("�����ʼ�")),array("OICQ_NO",_("QQ����")),array("ICQ_NO","MSN"),
array("NOTES",_("��ע")),array("PSN_NO",_("�����")));

/*
$query = "select * from FIELDSETTING where TABLENAME='ADDRESS' ORDER BY ORDERNO ASC ";
$cursor1=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor1))
{
 $temparray=array($ROW["FIELDNO"],$ROW["FIELDNAME"]);
 array_push ($ALL_FIELD,$temparray);
}
*/
$PARA_ARRAY=get_sys_para("ADDRESS_SHOW_FIELDS");
$HRMS_OPEN_FIELDS=$PARA_ARRAY["ADDRESS_SHOW_FIELDS"];
$OPEN_ARRAY=explode("|",$HRMS_OPEN_FIELDS);
$FIELD_ARRAY=explode(",",$OPEN_ARRAY[0]);
$NAME_ARRAY=explode(",",$OPEN_ARRAY[1]);
?>
<form action="open_submit.php" method="post" name="form1">
<center>	
	<table class="TableTop" width="600">
   <tr>
      <td class="left"></td>
      <td class="center"><?=_("��ʾ�ֶ�����")?></td>
      <td class="right"></td>
   </tr>
</table>
  <table class="TableBlock no-top-border" width="600">
    <tr class="TableContent">
     <td align="center"><?=_("����")?></td>
    <td align="center"><b><?=_("��ʾ�ֶ�")?></b></td>
    <td align="center">&nbsp;</td>
    <td align="center" valign="top"><b><?=_("��ѡ�ֶ�")?></b></td>
  </tr>
  <tr>
  	<td align="center" class="TableData">
      <input type="button" class="SmallInput" value=" <?=_("��")?> " onClick="func_up();">
      <br><br>
      <input type="button" class="SmallInput" value=" <?=_("��")?>" onClick="func_down();">
    </td>
    <td valign="top" align="center" class="TableData">
    <select  name="select1" ondblclick="func_delete();" MULTIPLE style="width:200px;height:320px">
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
    <td align="center" class="TableData">
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_insert();">
      <br><br>
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_delete();">
    </td>
    <td align="center" valign="top" class="TableData">
    <select  name="select2" ondblclick="func_insert();" MULTIPLE style="width:200px;height:320px">
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
        <input type="button" value="<?=_("ȷ��")?>" class="BigButton" onClick="exreport()">&nbsp;&nbsp;
        <input type="reset" value="<?=_("����")?>" class="BigButton" onClick="history.back();">
        <input type="hidden" name="FIELDMSG">
        <input type="hidden" name="FIELDMSGNAME">
      </td>
    </tfoot>
  </table></center>
</form>
</body>
</html>

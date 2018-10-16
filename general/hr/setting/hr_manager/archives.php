<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("设置档案信息公开字段");
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
     alert("<?=_("调整顺序时，请选择其中一项！")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("调整顺序时，只能选择其中一项！")?>");
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
     alert("<?=_("调整顺序时，请选择其中一项！")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("调整顺序时，只能选择其中一项！")?>");
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/send.gif" width="18" HEIGHT="18"><span class="big3"> <?=_("人事档案查询列表字段设置")?></span>
    	<span class="small1"> <?=_("注意：人事档案查询列表字段设置之后，人事档案及人事档案查询列表即可看到，请谨慎设置！")?></span>
    </td>
  </tr>
</table>
<?
$ALL_FIELD=array(array("STAFF_E_NAME",_("英文名")),array("BEFORE_NAME",_("曾用名")),array("STAFF_SEX",_("性别")),array("STAFF_NO",_("编号")),array("WORK_NO",_("工号")),array("STAFF_CARD_NO",_("身份证号码")),array("STAFF_BIRTH",_("出生日期")),
array("STAFF_AGE",_("年龄")),array("STAFF_NATIVE_PLACE",_("籍贯")),array("STAFF_NATIONALITY",_("民族")),array("STAFF_MARITAL_STATUS",_("婚姻状况")),array("STAFF_POLITICAL_STATUS",_("政治面貌")),array("WORK_STATUS",_("在职状态")),
array("JOIN_PARTY_TIME",_("入党时间")),array("STAFF_PHONE",_("联系电话")),array("STAFF_MOBILE",_("手机号码")),array("STAFF_MSN","MSN"),array("STAFF_QQ","QQ"),array("STAFF_EMAIL",_("电子邮件")),
array("HOME_ADDRESS",_("家庭地址")),array("JOB_BEGINNING",_("参加工作时间")),array("OTHER_CONTACT",_("其他联系方式")),array("WORK_AGE",_("总工龄")),array("STAFF_HEALTH",_("健康状况")),array("STAFF_DOMICILE_PLACE",_("户口所在地")),
array("STAFF_TYPE",_("户口类别")),array("DATES_EMPLOYED",_("入职时间")),array("STAFF_HIGHEST_SCHOOL",_("学历")),array("STAFF_HIGHEST_DEGREE",_("学位")),array("GRADUATION_DATE",_("毕业时间")),array("STAFF_MAJOR",_("专业")),array("GRADUATION_SCHOOL",_("毕业院校")),
array("COMPUTER_LEVEL",_("计算机水平")),array("FOREIGN_LANGUAGE1",_("外语语种1")),array("FOREIGN_LANGUAGE2",_("外语语种2")),array("FOREIGN_LANGUAGE3",_("外语语种3")),array("FOREIGN_LEVEL1",_("外语水平1")),array("FOREIGN_LEVEL2",_("外语水平2")),array("FOREIGN_LEVEL3",_("外语水平3")),
array("STAFF_SKILLS",_("特长")),array("WORK_TYPE",_("工种")),array("ADMINISTRATION_LEVEL",_("行政级别")),array("STAFF_OCCUPATION",_("员工类型")),array("JOB_POSITION",_("职务")),array("PRESENT_POSITION",_("职称")),array("JOB_AGE",_("本单位工龄")),array("BEGIN_SALSRY_TIME",_("起薪时间")),
array("LEAVE_TYPE",_("年休假")),array("RESUME",_("简历")),array("SURETY",_("担保记录")),array("CERTIFICATE",_("职务情况")),array("INSURE",_("社保缴纳情况")),array("BODY_EXAMIM",_("体检记录")),
array("PHOTO_NAME",_("照片")),array("WORK_LEVEL",_("职称级别")),array("WORK_JOB",_("岗位")),array("BANK1",_("开户行1")),array("BANK_ACCOUNT1",_("账户1")),array("BANK2",_("开户行2")),array("BANK_ACCOUNT2",_("账户2")),
array("REMARK",_("备 注")));

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
     <td align="center"><?=_("排序")?></td>
    <td align="center"><b><?=_("人事档案查询列表字段")?></b></td>
    <td align="center">&nbsp;</td>
    <td align="center" valign="top"><b><?=_("可选字段")?></b></td>
  </tr>
  <tr>
  	<td align="center" bgcolor="#999999">
      <input type="button" class="SmallInput" value="<?=_(" ↑ ")?>" onClick="func_up();">
      <br><br>
      <input type="button" class="SmallInput" value="<?=_(" ↓ ")?>" onClick="func_down();">
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
    <input type="button" value=" <?=_("全选")?> " onClick="func_select_all1();" class="SmallInput">
    </td>
    <td align="center" bgcolor="#999999">
      <input type="button" class="SmallInput" value="<?=_(" ← ")?>" onClick="func_insert();">
      <br><br>
      <input type="button" class="SmallInput" value="<?=_(" → ")?>" onClick="func_delete();">
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
    <input type="button" value=" <?=_("全选")?> " onClick="func_select_all2();" class="SmallInput">
    </td>
  </tr>
    <tfoot align="center" class="TableFooter">
      <td nowrap colspan="4" align="center">
        <input type="button" value="<?=_("确定")?>" class="BigButton" onClick="exreport()">
        <input type="hidden" name="FIELDMSG">
        <input type="hidden" name="FIELDMSGNAME">
      </td>
    </tfoot>
    </table>
</form>
</body>
</html>

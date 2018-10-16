<?
include_once("inc/auth.inc.php");

$query = "SELECT UID from MODULE_PRIV where UID='".$_SESSION["LOGIN_UID"]."' and MODULE_ID='5'";
$cursor= exequery(TD::conn(),$query);
if(mysql_num_rows($cursor)>0)
{
	$IS_MODULE_PRIV_SET=1;
}	

$BAOXIAN_XIANG_ARRAY = array(
                          "ALL_BASE"=>_("保险基数"),
                          "PENSION_BASE"=>_("养老保险"),                          
                          "PENSION_U"=>_("单位养老"), 
                          "PENSION_U"=>_("个人养老"),                   
                          "MEDICAL_BASE"=>_("医疗保险"),
                          "MEDICAL_U"=>_("单位医疗"),
                          "MEDICAL_P"=>_("个人医疗"),
                          "FERTILITY_BASE"=>_("生育保险"),
                          "FERTILITY_U"=>_("单位生育"),
                          "UNEMPLOYMENT_BASE"=>_("失业保险"),
                          "UNEMPLOYMENT_U"=>_("单位失业"),
                          "UNEMPLOYMENT_P"=>_("个人失业"),
                          "INJURIES_BASE"=>_("工伤保险"),
                          "INJURIES_U"=>_("单位工伤"),
                          "HOUSING_BASE"=>_("住房公积金"),                          
                          "HOUSING_U"=>_("单位住房"),
                          "HOUSING_P"=>_("个人住房"),
                          "INSURANCE_DATE"=>_("投保日期")                                                    
                         );

$HTML_PAGE_TITLE = _("工资报表");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
function func_insert()
{
  for(i=0; i<document.form1.select2.options.length; i++)
  {
     if(document.form1.select2.options[i].selected)
     {
        var my_option = document.createElement("OPTION");
        my_option.text=document.form1.select2.options[i].text;
        my_option.value=document.form1.select2.options[i].value;
        my_option.style.color=document.form1.select2.options[i].style.color;   
        //alert(my_option.text);alert(my_option.value);
        document.form1.select1.options.add(my_option, document.form1.select1.options.length);
     }
 }//for

 for(i=document.form1.select2.options.length-1; i>=0; i--)
 {
    if(document.form1.select2.options[i].selected)
       document.form1.select2.remove(i);
 }//for
}

function func_delete()
{
 for(i=0; i<document.form1.select1.options.length; i++)
 {
   if(document.form1.select1.options[i].selected)
   {
      var my_option = document.createElement("OPTION");
      my_option.text=document.form1.select1.options[i].text;
      my_option.value=document.form1.select1.options[i].value;
      document.form1.select2.options.add(my_option, document.form1.select2.options.length);
  }
 }//for
 
 for(i=document.form1.select1.options.length-1; i>=0; i--)
 {
    if(document.form1.select1.options[i].selected)
       document.form1.select1.remove(i);
 }//for

}

function func_select_all1()
{
   for(i=document.form1.select1.options.length-1; i>=0; i--)
      document.form1.select1.options[i].selected=true;
}

function func_select_all2()
{
   for(i=document.form1.select2.options.length-1; i>=0; i--)
       document.form1.select2.options[i].selected=true;
}

function mysubmit()
{
   fld_str="";
   for (i=0; i< document.form1.select1.options.length; i++)
   {
      options_value=document.form1.select1.options[i].value;
      fld_str+=options_value+",";
   }
   document.all("STYLE").value=fld_str;
}

function exreport(event)
{
   var fld_str="";
   for (i=0; i< document.form1.select1.options.length; i++)
   {
      options_value=document.form1.select1.options[i].value;
      fld_str+=options_value+",";
   }
   for(i=0;i<document.getElementsByName("DEPTFLAG").length;i++)
     {
        el=document.getElementsByName("DEPTFLAG").item(i);
        if(el.checked)
        {
        	 val=el.value;
        }
     }
  
  if(document.getElementsByName("SUMFIELD").item(0).checked)
  { 
        loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
        loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
        if(val=="1")
            URL="report.php?COPY_TO_ID="+document.all("COPY_TO_ID").value+"&fld_str="+fld_str+"&FLOW_ID="+document.all("FLOW_ID").value+"&TOID="+document.all("TO_ID").value+"&PRIV_ID="+document.all("PRIV_ID").value;
        if(val=="0")
            URL="report.php?fld_str="+fld_str+"&FLOW_ID="+document.all("FLOW_ID").value+"&DEPT_FLAG=1";
        window.open(URL,"report","height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=150,resizable=yes");
  }	
  if(document.getElementsByName("SUMFIELD").item(1).checked)
  { 
        if(val=="1")
            URL="excel_report.php?COPY_TO_ID="+document.all("COPY_TO_ID").value+"&fld_str="+fld_str+"&FLOW_ID="+document.all("FLOW_ID").value+"&TOID="+document.all("TO_ID").value+"&PRIV_ID="+document.all("PRIV_ID").value;
        if(val=="0")
            URL="excel_report.php?fld_str="+fld_str+"&FLOW_ID="+document.all("FLOW_ID").value+"&DEPT_FLAG=1";
        window.location.href=URL
}
if(document.getElementsByName("SUMFIELD").item(2).checked)
{ 
        if(document.all("END_DATE").value=="" || document.all("START_DATE").value=="")
            {
                alert("<?=_("请输入流程起始日期和截止日期！")?>");
                return (false);
            }
        if(val=="2")
            URL="extend_report.php?COPY_TO_ID="+document.all("COPY_TO_ID").value+"&fld_str="+fld_str+"&FLOW_ID="+document.all("FLOW_ID").value+"&TOID="+document.all("TO_ID").value+"&PRIV_ID="+document.all("PRIV_ID").value+"&START_DATE="+document.all("START_DATE").value+"&END_DATE="+document.all("END_DATE").value;
        window.location.href=URL
        //window.open(URL,"report","height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=150,resizable=yes");
}  

}

function sel_change()
{    
        //alert(document.getElementsByName("DEPTFLAG").value);
	  for(i=0;i<document.getElementsByName("DEPTFLAG").length;i++)
     {
        el=document.getElementsByName("DEPTFLAG").item(i);
        if(el.checked)
        {
        	 val=el.value;                 
        }
     }
     if(val=="1")
     {
     	document.all("SER").style.display="";
     	document.all("SER1").style.display="";
     	document.all("SER2").style.display="none";
     	document.getElementsByName("SUMFIELD").item(0).checked="on";
     	document.form1.SUMFIELD1.disabled=false;
     	document.form1.SUMFIELD2.disabled=false;
     	document.form1.SUMFIELD3.disabled=true;
     }
     else if(val=="0")
     {
     	document.all("SER").style.display="none";
     	document.all("SER1").style.display="none";
     	document.all("SER2").style.display="none";
     	document.getElementsByName("SUMFIELD").item(0).checked="on";
     	document.form1.SUMFIELD1.disabled=false;
     	document.form1.SUMFIELD2.disabled=false;
     	document.form1.SUMFIELD3.disabled=true;
     }
     else
     {
     	document.all("SER").style.display="";
     	document.all("SER1").style.display="";
     	document.all("SER2").style.display="";
     	document.getElementsByName("SUMFIELD").item(2).checked="on";
     	document.form1.SUMFIELD1.disabled=true;
     	document.form1.SUMFIELD2.disabled=true;
     	document.form1.SUMFIELD3.disabled=false;
     }
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
     alert("<?=_("调整桌面模块的顺序时，请选择其中一项！")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("调整桌面模块的顺序时，只能选择其中一项！")?>");
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

<body class="bodycolor" >
<form method="post" name="form1">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3">&nbsp;<?=_("工资报表")?></span>
   </td>
  </tr>
</table>
<div align="center">

<table class="TableBlock" align="center"   style="text-align: left;">
   <tr>
   	<td nowrap class="TableContent">&nbsp;&nbsp;<?=_("人员范围：")?> </td>
    <td nowrap class="TableData" colspan="3" >
    <input type="radio" name='DEPTFLAG' value="1" checked  onclick="sel_change()"><?=_("在职人员")?> &nbsp;<input type="radio" name='DEPTFLAG' value="0"  onclick="sel_change()"><?=_("外部人员")?>&nbsp;<input type="radio" name='DEPTFLAG' value="2"  onclick="sel_change()"><?=_("跨流程")?>&nbsp;
    </td> 
  </tr>
  <tr id="SER2" style="display:none">
   	<td nowrap class="TableContent">&nbsp;&nbsp;<?=_("起始日期：")?></td>
    <td nowrap class="TableData">
     <input type="text" name="START_DATE" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()"/>
    </td> 
   	<td nowrap class="TableContent">&nbsp;&nbsp;<?=_("截止日期：")?></td>
    <td nowrap class="TableData">
     <input type="text" name="END_DATE" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()"/>
    </td> 
  </tr>
  <tr id="SER">
    <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("部门：")?> </td>
    <td nowrap class="TableData">
        <input type="hidden" name="TO_ID">
        <textarea cols=21 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
    </td>
    <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("人员：")?> </td>
    <td nowrap class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="">
        <textarea cols=21 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("选择")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
    </td>
  </tr>
  <tr id="SER1">
    <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("角色：")?></td>
    <td class="TableData" colspan="3">
      <input type="hidden" name="PRIV_ID" value="">
      <textarea cols=42 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
      <? if ($IS_MODULE_PRIV_SET==1)
         {
      ?>
           <?=_("添加")?>
           <?=_("清空")?><br>
      <?
         }
        else
         {
       ?>  	
      <a href="javascript:;" class="orgAdd" onClick="SelectPriv('9','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a><br>
      <?
         }
      ?>
      <?=_("查询范围取部门（短部门）、人员和角色的交集")?>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("输出形式：")?><? echo $disabled;?> </td>
    <td nowrap class="TableData" colspan="3">
     <input type="radio" name='SUMFIELD' value="htm" id="SUMFIELD1" checked>html<?=_("报表")?> &nbsp;
     <input type="radio" name='SUMFIELD' value="excel" id="SUMFIELD2" >EXCEL<?=_("报表")?>&nbsp;
     <input type="radio" name='SUMFIELD' value="extend_excel" id="SUMFIELD3" disabled><?=_("跨流程报表")?>&nbsp;
    </td> 
  </tr>
  <tr>	
  <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("输出内容：")?></td>
  <td nowrap class="TableData" colspan="3" align="left">
  	<table width="150" class="TableBlock">
      <tr bgcolor="#CCCCCC">
    <td align="center"><?=_("排序")?></td>
    <td align="center"><b><?=_("显示字段")?></b></td>
    <td align="center"><?=_("选择")?></td>
    <td align="center" valign="top"><b><?=_("可选字段")?></b></td>
  </tr>
  <tr>
  	 <td align="center" bgcolor="#999999">
      <input type="button" class="SmallInput" value="<?=_(" ↑ ")?>" onClick="func_up();">
      <br><br>
      <input type="button" class="SmallInput" value="<?=_(" ↓ ")?>" onClick="func_down();">
    </td>
    <td valign="top" align="center" bgcolor="#CCCCCC">
    <select name="select1" ondblclick="func_delete();" MULTIPLE style="width:200px;height:280px">
<?
$query = "SELECT STYLE from SAL_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $STYLE=$ROW["STYLE"];
}
if($STYLE!="")
{
   $STYLE_ARRAY=explode(",",$STYLE);
   $ARRAY_COUNT=sizeof($STYLE_ARRAY);
   if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
   for($I=0;$I < $ARRAY_COUNT;$I++)
   {
      $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
      $cursor1= exequery(TD::conn(),$query1);
      $ITEM_NAME="";
      if($ROW=mysql_fetch_array($cursor1))
      {
	       $ITEM_NAME=$ROW["ITEM_NAME"];
         $ITEM_ID="S".$ROW["ITEM_ID"];
      }
      else if($STYLE_ARRAY[$I]=="MEMO")
      {
	       $ITEM_NAME=_("备注");
         $ITEM_ID="MEMO";
      }
      else     
      {
	       $ITEM_NAME=$BAOXIAN_XIANG_ARRAY[$STYLE_ARRAY[$I]];
         $ITEM_ID=$STYLE_ARRAY[$I];
         $BAOXIAN_XIANG_ARRAY2[$STYLE_ARRAY[$I]]=$BAOXIAN_XIANG_ARRAY[$STYLE_ARRAY[$I]];
      }      
      
      if($ITEM_NAME!="")
      {
?>
         <option value="<?=$ITEM_ID?>"><?=$ITEM_NAME?></option>

<?
      }
   }
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
    <select  name="select2" ondblclick="func_insert();" MULTIPLE style="width:200px;height:280px">
<?
$query = "SELECT ITEM_ID,ITEM_NAME from SAL_ITEM where ISPRINT='1' ORDER BY `ITEM_ID`";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $ITEM_ID="S".$ROW["ITEM_ID"];
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $sign=0;
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
   	 if($STYLE_ARRAY[$I]==$ROW["ITEM_ID"])
  	    $sign=1;
   }
   if($sign==0)
   {

?>
     <option value="<?=$ITEM_ID?>"><?=$ITEM_NAME?></option>
<?
   }
}
if(!find_id($STYLE,"MEMO"))
   echo "<option value=\"MEMO\">"._("备注")."</option>";

if(empty($BAOXIAN_XIANG_ARRAY2)) 
   $TMP_ARRAY=$BAOXIAN_XIANG_ARRAY;
else
   $TMP_ARRAY=array_diff_key($BAOXIAN_XIANG_ARRAY,$BAOXIAN_XIANG_ARRAY2);
if(is_array($TMP_ARRAY) && !empty($TMP_ARRAY))
{
    foreach($TMP_ARRAY as $key => $value)
    {
    ?>
      <option value="<?=$key?>"><?=$value?></option>
    <?
    }
}      
?>
    </select>
    <input type="button" value=" <?=_("全选")?> " onClick="func_select_all2();" class="SmallInput">
    </td>
  </tr>
</table>
 <img src="<?=MYOA_STATIC_SERVER?>/static/images/attention.gif" height="18"> <?=_("管理员提示：输出字段为空,则输出全部字段；按Ctrl+鼠标左键可以选择多个项目。")?>
</td>
  </tr>
  <tr align="center" class="TableFooter">
    <td nowrap colspan="4" align="center">
    	 <input type="hidden" name="FLOW_ID" value="<?=$FLOW_ID?>">
       <input type="hidden" name="STYLE" value="">
       <input type="button" value="<?=_("确定")?>" class="SmallButton"  name="submit" onClick="exreport(event)">&nbsp;&nbsp;
       <input type="button" value="<?=_("返回")?>" class="SmallButton"  name="button" onClick="javascript:location='../index.php'">
    </td>
   </tr>
</table>
</div>
</form>
</body>
</html>

<?
include_once("inc/auth.inc.php");

$query = "SELECT UID from MODULE_PRIV where UID='".$_SESSION["LOGIN_UID"]."' and MODULE_ID='5'";
$cursor= exequery(TD::conn(),$query);
if(mysql_num_rows($cursor)>0)
{
	$IS_MODULE_PRIV_SET=1;
}	

$HTML_PAGE_TITLE = _("���ʲ�ѯͳ��");
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
   for(i=0;i<document.all("DEPTFLAG").length;i++)
     {
        el=document.all("DEPTFLAG").item(i);
        if(el.checked)
        {
        	 val=el.value;
        }
     }
  if(document.all("SUMFIELD").item(0).checked)
  { 
  	 alert(val);
  	 if(val=="1")URL="report.php?COPY_TO_ID="+document.all("COPY_TO_ID").value+"&fld_str="+fld_str+"&FLOW_ID="+document.all("FLOW_ID").value+"&TOID="+document.all("TO_ID").value+"&PRIV_ID="+document.all("PRIV_ID").value+"&START_DATE="+document.all("START_DATE").value+"&END_DATE="+document.all("END_DATE").value;
     if(val=="0")URL="report.php?fld_str="+fld_str+"&FLOW_ID="+document.all("FLOW_ID").value+"&START_DATE="+document.all("START_DATE").value+"&END_DATE="+document.all("END_DATE").value+"&DEPT_FLAG=1";
  }	
  if(document.all("SUMFIELD").item(1).checked)
  { 
  	 if(val=="1")URL="excel_report.php?COPY_TO_ID="+document.all("COPY_TO_ID").value+"&fld_str="+fld_str+"&FLOW_ID="+document.all("FLOW_ID").value+"&TOID="+document.all("TO_ID").value+"&PRIV_ID="+document.all("PRIV_ID").value+"&START_DATE="+document.all("START_DATE").value+"&END_DATE="+document.all("END_DATE").value;
     if(val=="0")URL="excel_report.php?fld_str="+fld_str+"&FLOW_ID="+document.all("FLOW_ID").value+"&START_DATE="+document.all("START_DATE").value+"&END_DATE="+document.all("END_DATE").value+"&DEPT_FLAG=1";
  }	
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  window.open(URL,"report","height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=150,resizable=yes");          
}

function sel_change()
{
	  for(i=0;i<document.all("DEPTFLAG").length;i++)
     {
        el=document.all("DEPTFLAG").item(i);
        if(el.checked)
        {
        	 val=el.value;
        }
     }
     if(val=="1")
     {
     	document.all("SER").style.display="";
     	document.all("SER1").style.display="";
     }
    else
     {
     	document.all("SER").style.display="none";
     	document.all("SER1").style.display="none";
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
     alert("<?=_("��������ģ���˳��ʱ����ѡ������һ�")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("��������ģ���˳��ʱ��ֻ��ѡ������һ�")?>");
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3">&nbsp;<?=_("���ʲ�ѯͳ��")?></span>
   </td>
  </tr>
</table>
<div align="center">

<table class="TableBlock" align="center" >
   <tr>
   	<td nowrap class="TableContent">&nbsp;&nbsp;<?=_("��Ա��Χ��")?> </td>
    <td nowrap class="TableData" colspan="3">
    <input type="radio" name='DEPTFLAG' value="1" checked  onclick="sel_change()"><?=_("��ְ��Ա")?> &nbsp;<input type="radio" name='DEPTFLAG' value="0"  onclick="sel_change()"><?=_("�ⲿ��Ա")?>&nbsp;
    </td> 
  </tr>
  <tr id="SER">
    <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("���ţ�")?> </td>
    <td nowrap class="TableData">
        <input type="hidden" name="TO_ID">
        <textarea cols=21 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("���")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
    </td>
    <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("��Ա��")?> </td>
    <td nowrap class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="">
        <textarea cols=21 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("ѡ��")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
    </td>
  </tr>
  <tr id="SER1">
    <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("��ɫ��")?></td>
    <td class="TableData" colspan="3">
      <input type="hidden" name="PRIV_ID" value="">
      <textarea cols=42 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
      <? if ($IS_MODULE_PRIV_SET==1)
         {
      ?>
           <?=_("���")?>
           <?=_("���")?><br>
      <?
         }
        else
         {
       ?>  	
      <a href="javascript:;" class="orgAdd" onClick="SelectPriv('9','PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a><br>
      <?
         }
      ?>
      <?=_("��ѯ��Χȡ���š���Ա�ͽ�ɫ�Ĳ���")?>
    </td>
  </tr>
  <tr>
   	<td nowrap class="TableContent">&nbsp;&nbsp;<?=_("��ʼ���ڣ�")?></td>
    <td nowrap class="TableData">
     <input type="text" name="START_DATE" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()"/>
    </td> 
   	<td nowrap class="TableContent">&nbsp;&nbsp;<?=_("��ֹ���ڣ�")?></td>
    <td nowrap class="TableData">
     <input type="text" name="END_DATE" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()"/>
    </td> 
  </tr>
  <tr>
    <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("������")?> </td>
    <td nowrap class="TableData" colspan="3">
    <input type="radio" name='SUMFIELD' value="htm" checked><?=_("��ѯ")?> &nbsp;<input type="radio" name='SUMFIELD' value="excel"><?=_("����")?> &nbsp;
    </td> 
  </tr>
  <tr>	
  <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("������ݣ�")?></td>
  <td nowrap class="TableData" colspan="3" align="left">
  	<table width="150" class="TableBlock">
     <tr bgcolor="#CCCCCC">
     	 <td align="center"><?=_("����")?></td>
    <td align="center"><b><?=_("����ֶ�")?></b></td>
    <td align="center"><?=_("ѡ��")?></td>
    <td align="center" valign="top"><b><?=_("��ѡ�ֶ�")?></b></td>
  </tr>
  <tr>
  	 <td align="center" bgcolor="#999999">
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_up();">
      <br><br>
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_down();">
    </td>
    <td valign="top" align="center" bgcolor="#CCCCCC">
    <select  name="select1" ondblclick="func_delete();" MULTIPLE style="width:200px;height:280px">
<?
				$FLOW_ID = intval($FLOW_ID);
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
           $COUNT=0;
           if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
           for($I=0;$I<$ARRAY_COUNT;$I++)
           {
      	      $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
              $cursor1= exequery(TD::conn(),$query1);
              if($ROW=mysql_fetch_array($cursor1))
              {
           	     $ITEM_NAME=$ROW["ITEM_NAME"];
                 $ITEM_ID=$ROW["ITEM_ID"];
              }
?>        
              <option value="<?=$ITEM_ID?>"><?=$ITEM_NAME?></option>
<?
          }
?>         
         <option value="ALL_BASE"><?=���ջ���?></option>
         <option value="PENSION_BASE"><?=���ϱ���?></option>
         <option value="PENSION_U"><?=��λ����?></option>
         <option value="PENSION_P"><?=��������?></option>
         <option value="MEDICAL_BASE"><?=ҽ�Ʊ���?></option>
         <option value="MEDICAL_U"><?=��λҽ��?></option>
         <option value="MEDICAL_P"><?=����ҽ��?></option>
         <option value="FERTILITY_BASE"><?=��������?></option>
         <option value="FERTILITY_U"><?=��λ����?></option>
         <option value="UNEMPLOYMENT_BASE"><?=ʧҵ����?></option>
         <option value="UNEMPLOYMENT_U"><?=��λʧҵ?></option>
         <option value="UNEMPLOYMENT_P"><?=����ʧҵ?></option>
         <option value="INJURIES_BASE"><?=���˱���?></option>
         <option value="INJURIES_U"><?=��λ����?></option>
         <option value="HOUSING_BASE"><?=ס��������?></option>
         <option value="HOUSING_U"><?=��λס��?></option>
         <option value="HOUSING_P"><?=����ס��?></option>
         <option value="INSURANCE_DATE"><?=Ͷ������?></option>         
<?
        }
?>   
   
    </select>
    <input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all1();" class="SmallInput">
    </td>

    <td align="center" bgcolor="#999999">
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>onClick="func_insert();">
      <br><br>
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_delete();">
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
          	if($STYLE_ARRAY[$I]==$ITEM_ID)$sign=1;
          }
          if($sign==0)
          {

       ?>
       <option value="<?=$ITEM_ID?>"><?=$ITEM_NAME?></option>
       <?
         }
        }
 ?>       
        <option value="ALL_BASE"><?=���ջ���?></option>
         <option value="PENSION_BASE"><?=���ϱ���?></option>
         <option value="PENSION_U"><?=��λ����?></option>
         <option value="PENSION_P"><?=��������?></option>
         <option value="MEDICAL_BASE"><?=ҽ�Ʊ���?></option>
         <option value="MEDICAL_U"><?=��λҽ��?></option>
         <option value="MEDICAL_P"><?=����ҽ��?></option>
         <option value="FERTILITY_BASE"><?=��������?></option>
         <option value="FERTILITY_U"><?=��λ����?></option>
         <option value="UNEMPLOYMENT_BASE"><?=ʧҵ����?></option>
         <option value="UNEMPLOYMENT_U"><?=��λʧҵ?></option>
         <option value="UNEMPLOYMENT_P"><?=����ʧҵ?></option>
         <option value="INJURIES_BASE"><?=���˱���?></option>
         <option value="INJURIES_U"><?=��λ����?></option>
         <option value="HOUSING_BASE"><?=ס��������?></option>
         <option value="HOUSING_U"><?=��λס��?></option>
         <option value="HOUSING_P"><?=����ס��?></option>
         <option value="INSURANCE_DATE"><?=Ͷ������?></option>       
       <option value="MEMO"><?=_("��ע")?></option>
    </select>
    <input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all2();" class="SmallInput">
   
    </td>
  </tr>
</table>
 <img src="<?=MYOA_STATIC_SERVER?>/static/images/attention.gif" height="18"> <?=_("����Ա��ʾ������ֶ�Ϊ��,�����ȫ���ֶΣ���CTRL+����������ѡ������Ŀ��")?>
</td>
  </tr>
  <tr align="center" class="TableFooter">
    <td nowrap colspan="4" align="center">
    	 <input type="hidden" name="FLOW_ID" value="<?=$FLOW_ID?>">
       <input type="hidden" name="STYLE" value="">
       <input type="button" value="<?=_("ȷ��")?>" class="SmallButton"  name="submit" onClick="exreport(event)">&nbsp;&nbsp;
    </td>
   </tr>
</table>
</div>
</form>
</body>
</html>

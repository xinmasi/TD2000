<?
include_once("inc/auth.inc.php");
$BAOXIAN_XIANG_ARRAY = array(
    "ALL_BASE"=>_("���ջ���"),
    "PENSION_BASE"=>_("���ϱ���"),
    "PENSION_U"=>_("��λ����"),
    "PENSION_U"=>_("��������"),
    "MEDICAL_BASE"=>_("ҽ�Ʊ���"),
    "MEDICAL_U"=>_("��λҽ��"),
    "MEDICAL_P"=>_("����ҽ��"),
    "FERTILITY_BASE"=>_("��������"),
    "FERTILITY_U"=>_("��λ����"),
    "UNEMPLOYMENT_BASE"=>_("ʧҵ����"),
    "UNEMPLOYMENT_U"=>_("��λʧҵ"),
    "UNEMPLOYMENT_P"=>_("����ʧҵ"),
    "INJURIES_BASE"=>_("���˱���"),
    "INJURIES_U"=>_("��λ����"),
    "HOUSING_BASE"=>_("ס��������"),
    "HOUSING_U"=>_("��λס��"),
    "HOUSING_P"=>_("����ס��")
);
$HTML_PAGE_TITLE = _("΢�Ź�����");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<script type="text/javascript">
function func_insert()
{
 for (i=0; i<form1.select2.options.length; i++)
 {
   if(form1.select2.options[i].selected)
   {
     var my_option = document.createElement("OPTION");
     my_option.text=form1.select2.options[i].text;
     my_option.value=form1.select2.options[i].value;
     my_option.style.color=form1.select2.options[i].style.color;

     form1.select1.options.add(my_option, form1.select1.options.length);
  }
 }//for

 for (i=form1.select2.options.length-1; i>=0; i--)
 {
   if(form1.select2.options[i].selected)
     form1.select2.remove(i);
 }//for
}

function func_delete()
{
 for (i=0; i<form1.select1.options.length; i++)
 {
   if(form1.select1.options[i].selected)
   {
     var my_option = document.createElement("OPTION");
     my_option.text=form1.select1.options[i].text;
     my_option.value=form1.select1.options[i].value;

     form1.select2.options.add(my_option, form1.select2.options.length);
  }
 }//for

 for (i=form1.select1.options.length-1; i>=0; i--)
 {
   if(form1.select1.options[i].selected)
     form1.select1.remove(i);
 }//for

}

function func_select_all1()
{
 for (i=form1.select1.options.length-1; i>=0; i--)
   form1.select1.options[i].selected=true;
}

function func_select_all2()
{
 for (i=form1.select2.options.length-1; i>=0; i--)
   form1.select2.options[i].selected=true;
}

function exreport()
{
	 fld_str="";
   for (i=0; i< form1.select1.options.length; i++)
   {
      options_value=form1.select1.options[i].value;
      fld_str+=options_value+",";
   }
 document.form1.fld_str.value=fld_str;
 document.form1.submit();
}

function func_up()
{
  sel_count=0;
  for (i=form1.select1.options.length-1; i>=0; i--)
  {
    if(form1.select1.options[i].selected)
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

  i=form1.select1.selectedIndex;

  if(i!=0)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=form1.select1.options[i].text;
    my_option.value=form1.select1.options[i].value;

    form1.select1.options.add(my_option,i-1);
    form1.select1.remove(i+1);
    form1.select1.options[i-1].selected=true;
  }
}

function func_down()
{
  sel_count=0;
  for (i=form1.select1.options.length-1; i>=0; i--)
  {
    if(form1.select1.options[i].selected)
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

  i=form1.select1.selectedIndex;

  if(i!=form1.select1.options.length-1)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=form1.select1.options[i].text;
    my_option.value=form1.select1.options[i].value;

    form1.select1.options.add(my_option,i+2);
    form1.select1.remove(i);
    form1.select1.options[i+1].selected=true;
  }
}
</script>


<body class="bodycolor" >
<form name="form1" method="post" action="weixin_wage.php">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/mobile_sms.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3">&nbsp;<?=_("΢�Ź�����")?></span>
   </td>
  </tr>
</table>
<br>
<div align="center">
<table class="TableBlock" align="center" >
  <tr>
    <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("��Ա��")?> </td>
    <td nowrap class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="">
        <textarea cols=40 name="COPY_TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME','1')"><?=_("ѡ��")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
    </td>
  </tr>
  <tr>
  <td nowrap class="TableContent">&nbsp;&nbsp;<?=_("������ݣ�")?></td>
  <td nowrap class="TableData" align="left">
  	<table width="150" class="TableBlock">
      <tr bgcolor="#CCCCCC">
    <td align="center"><?=_("����")?></td>
    <td align="center"><b><?=_("��ʾ�ֶ�")?></b></td>
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
    <select name="select1" ondblclick="func_delete();" MULTIPLE style="width:200px;height:280px">
<?
$query = "SELECT YES_OTHER from HR_INSURANCE_PARA";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $YES_OTHER=$ROW["YES_OTHER"];
}
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
	       $ITEM_NAME=_("��ע");
         $ITEM_ID="MEMO";
      }
      else
      {
            if($YES_OTHER==1)
            {
                $ITEM_NAME=$BAOXIAN_XIANG_ARRAY[$STYLE_ARRAY[$I]];
                $ITEM_ID=$STYLE_ARRAY[$I];
                $BAOXIAN_XIANG_ARRAY2[$STYLE_ARRAY[$I]]=$BAOXIAN_XIANG_ARRAY[$STYLE_ARRAY[$I]];
            }
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
    <input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all1();" class="SmallInput">
    </td>

    <td align="center" bgcolor="#999999">
      <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_insert();">
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
   echo "<option value=\"MEMO\">"._("��ע")."</option>";
if($YES_OTHER==1)
{
    if(empty($BAOXIAN_XIANG_ARRAY2))
       $TMP_ARRAY=$BAOXIAN_XIANG_ARRAY;
    else
       $TMP_ARRAY=array_diff_key($BAOXIAN_XIANG_ARRAY,$BAOXIAN_XIANG_ARRAY2);
    foreach($TMP_ARRAY as $key => $value)
    {
?>
        <option value="<?=$key?>"><?=$value?></option>
<?
    }
}
?>
    </select>
    <input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all2();" class="SmallInput">
    </td>
  </tr>
</table>
</td>
  </tr>
  <tfoot align="center" class="TableFooter">
    <td nowrap colspan="4" align="center">
       <input type="hidden" name="FLOW_ID" value="<?=$FLOW_ID?>">
       <input type="hidden" name="fld_str" value="">
       <input type="button" value="<?=_("ȷ��")?>" class="BigButton"  onClick="exreport()">&nbsp;&nbsp;
       <input type="button" value="<?=_("����")?>" class="BigButton"  onClick="location='index.php?FLOW_ID=<?=$FLOW_ID?>&PAGE_START=<?=$PAGE_START?>'">
    </td>
  </tfoot>
</table>
</div>
</form>
</body>
</html>

<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("�̶��ʲ���ѯ");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
//verify for netscape/mozilla
var isNS4 = (navigator.appName=="Netscape")?1:0;

function check_input()
{
  if(!isNS4)
  {
  	 if(event.keyCode < 45 || event.keyCode > 57)
  	    event.returnValue = false;
  }
  else
  {
  	 if(event.which < 45 || event.which > 57)
  	    return false;
  }  
}

function  ForDight(Dight,How)  
{  
           Dight  =  Math.round  (Dight*Math.pow(10,How))/Math.pow(10,How);  
           return  Dight;  
} 

function check_value(obj)
{
   
   if(obj.value=="")
      return;
   val=parseFloat(obj.value);
   if(isNaN(val))
   {
      alert("<?=_("�Ƿ�������")?>");
      obj.focus();
      return;
   }
   if(val<0)
   {
      alert("<?=_("��ֵ����С��")?>0");
      obj.focus();
      return;
   }
   
   obj.value=ForDight(val,2);

   value_array=obj.value.split(".");
   if(value_array.length==1)
   {
   	  obj.value=value_array[0]+".00";
   	  return;
   }
   else if(value_array.length==2)
   {
   	  if(value_array[1].length==0)
   	    obj.value=value_array[0]+".00";
   	  else if(value_array[1].length==1)
   	    obj.value=value_array[0]+"."+value_array[1]+"0";
   	  else if(value_array[1].length>=2)
   	    obj.value=value_array[0]+"."+value_array[1].substr(0,2);
   }
}

</script>

<?
 $query = "SELECT * from CP_ASSETCFG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $BAL_SORT=$ROW["BAL_SORT"];
?>
<body class="bodycolor" onload="form1.CPTL_NO.focus();">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif"  width="24" height="24"><span class="big3"> <?=_("�̶��ʲ���ѯ")?></span>
    <input type="button" value="<?=_("�ʲ�����")?>" class="BigButton" onclick="window.open('report.php','','height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=210,resizable=yes')">
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="index.php"  method="post" name="form1">
<table class="TableBlock"  width="90%" align="center">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ���ţ�")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NO" size="20" maxlength="100" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ����ƣ�")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NAME" size="30" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ����")?></td>
      <td class="TableData"> 
      <select name="TYPE_ID" class="BigSelect">
        <option value=""><?=_("�������")?></option>
        <option value="0"><?=_("δָ�����")?></option>
<?
 $query = "SELECT * from CP_ASSET_TYPE order by TYPE_NO";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_ID=$ROW["TYPE_ID"];
    $TYPE_NAME=$ROW["TYPE_NAME"];
?>
        <option value="<?=$TYPE_ID?>"><?=$TYPE_NAME?></option>
<?
 }
?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ţ�")?></td>
      <td class="TableData">
      <select name="DEPT_ID" class="BigSelect">
        <option value=""><?=_("���в���")?></option>
        <option value="0"><?=_("δָ������")?></option>
<?
      echo my_dept_tree(0,$DEPT_ID,0);
?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ����ʣ�")?></td>
      <td class="TableData"> 
      <select name="CPTL_KIND" class="BigSelect">
        <option value="" selected></option>
        <option value="01"><?=_("�ʲ�")?></option>
        <option value="02"><?=_("����")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ͣ�")?></td>
      <td class="TableData"> 
      <select name="PRCS_ID" class="BigSelect">
        <option value=""></option>
<?
 $query = "SELECT * from CP_PRCS_PROP where left(PRCS_CLASS,1)='A' order by PRCS_CLASS";

 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $PRCS_ID=$ROW["PRCS_ID"];
    $PRCS_LONG_DESC=$ROW["PRCS_LONG_DESC"];
?>
        <option value="<?=$PRCS_ID?>"><?=$PRCS_LONG_DESC?></option>
<?
 }
?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ͣ�")?></td>
      <td class="TableData"> 
      <select name="DCR_PRCS_ID" class="BigSelect">
        <option value=""></option>
<?
 $query = "SELECT * from CP_PRCS_PROP where left(PRCS_CLASS,1)='D' order by PRCS_CLASS";

 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $PRCS_ID=$ROW["PRCS_ID"];
    $PRCS_LONG_DESC=$ROW["PRCS_LONG_DESC"];
?>
        <option value="<?=$PRCS_ID?>"><?=$PRCS_LONG_DESC?></option>
<?
 }
?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�۾ɣ�")?></td>
      <td class="TableData"> 
        <select name="FINISH_FLAG" class="BigSelect">
          <option value="" selected></option>
          <option value="1"><?=_("����")?></option>
          <option value="0"><?=_("δ����")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ�ԭֵ��")?></td>
      <td class="TableData"> 
        <?=_("���ڵ��ڣ�")?> <input type="text" name="CPTL_VAL_MIN" size="10" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="02") echo "%";?>&nbsp;&nbsp;&nbsp;
        <?=_("С�ڵ��ڣ�")?> <input type="text" name="CPTL_VAL_MAX" size="10" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="02") echo "%";?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?if($BAL_SORT=="01") echo _("��ֵ��"); else echo _("��ֵ�ʣ�");?></td>
      <td class="TableData"> 
        <?=_("���ڵ��ڣ�")?> <input type="text" name="CPTL_BAL_MIN" size="10" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="02") echo "%";?>&nbsp;&nbsp;&nbsp;
        <?=_("С�ڵ��ڣ�")?> <input type="text" name="CPTL_BAL_MAX" size="10" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="02") echo "%";?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ڣ�")?></td>
      <td class="TableData"> 
        <?=_("��ʼ���ڣ�")?><input type="text" name="CREATE_DATE_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
        <?=_("�������ڣ�")?><input type="text" name="CREATE_DATE_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
      
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ڣ�")?></td>
      <td class="TableData"> 
        <?=_("��ʼ���ڣ�")?><input type="text" name="DCR_DATE_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
        <?=_("�������ڣ�")?><input type="text" name="DCR_DATE_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ڣ�")?></td>
      <td class="TableData"> 
        <?=_("��ʼ���ڣ�")?><input type="text" name="FROM_YYMM_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
        <?=_("�������ڣ�")?><input type="text" name="FROM_YYMM_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
        
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�����ˣ�")?></td>
      <td class="TableData"> 
		<input type="hidden" name="TO_ID" value="<?=$KEEP_USER?>">
		<input type="text" name="TO_NAME" size="20" class="BigInput" maxlength="20"  value="<?=$KEEPER?>">&nbsp;
		<input type="button" value="<?=_("ѡ��")?>" class="SmallButton" onClick="SelectUserSingle('108','','TO_ID','TO_NAME')" title="<?=_("ѡ��")?>" name="button">&nbsp;
		<input type="button" value="<?=_("���")?>" class="SmallButton" onClick="ClearUser()" title="<?=_("���")?>" name="button">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ע��")?></td>
      <td class="TableData"> 
        <textarea cols="35" name="REMARK" rows="2" class="BigInput" wrap="yes"></textarea>
      </td>
    </tr>
    </tr>
      <td class="TableData" colspan="4">
        <?=get_field_table(get_field_html("CP_CPTL_INFO","",1))?>
      </td>
    </tr>    
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="location='index.php'">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>
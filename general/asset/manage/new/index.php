<?
include_once("inc/auth.inc.php");
include_once("../check_cfg.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
include_once("inc/utility_all.php");

//---------------- ����۾���ز����Ƿ����ú� ----------------------------
check_assetcfg();

$CUR_DATE=date("Y-m-d",time());

//2011-06-07 LP jQuery��ѯ�̶��ʲ�IDΨһ
$uni_search = $_POST['uni_search'];
$uni_cptl_no = $_POST['uni_cptl_no'];
$uni_cptl_no = td_iconv($uni_cptl_no, "utf-8", MYOA_CHARSET);

if($uni_search=="search") {
	$query1="select CPTL_NO from CP_CPTL_INFO where CPTL_NO = '$uni_cptl_no'";
  $cursor1= exequery(TD::conn(),$query1);
  $NUM=mysql_num_rows($cursor1);
	ob_clean();
	if($NUM>0) {
		echo "1";
	}else{
		echo "0";
	}	
	exit;
}


$HTML_PAGE_TITLE = _("���ӹ̶��ʲ�");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
$(document).ready(function () {
	$("input[name=CPTL_NO]").blur(function(){
			var cptl_no = $(this).val();
			if(cptl_no=="") {return;}
			$.ajax({
					type: "POST",
					url: "index.php",
					data: "uni_search=search&uni_cptl_no="+cptl_no,
					success: function(data1){
							if(data1=="1") {
								alert("<?=_("�̶��ʲ�����Ѵ��ڣ�������������")?>");
								$("input[name=CPTL_NO]").val("").focus();
								return;
							}
					}
			});
});

});

function CheckForm()
{
	 if(document.form1.CPTL_NO.value=="")
   { alert("<?=_("�ʲ���Ų���Ϊ�գ�")?>");
     document.form1.CPTL_NO.focus();
     return (false);
   }
   if(document.form1.CPTL_NAME.value=="")
   { alert("<?=_("�ʲ����Ʋ���Ϊ�գ�")?>");
     document.form1.CPTL_NAME.focus();
     return (false);
   }
   if(document.form1.DEPT_ID.value=="")
   { alert("<?=_("�������Ų���Ϊ�գ�")?>");
     document.form1.DEPT_ID.focus();
     return (false);
   }
   if(document.form1.CPTL_VAL.value=="")
   { alert("<?=_("�ʲ�ԭֵ����Ϊ�գ�")?>");
     document.form1.CPTL_VAL.focus();
     return (false);
   }
   if(document.form1.CPTL_BAL.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("��ֵ�ʲ���Ϊ�գ�")?>");
     document.form1.CPTL_BAL.focus();
     return (false);
   }
   if(document.form1.DPCT_YY.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("�۾����޲���Ϊ�գ�")?>");
     document.form1.DPCT_YY.focus();
     return (false);
   }
   if(document.form1.SUM_DPCT.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("�ۼ��۾ɲ���Ϊ�գ�")?>");
     document.form1.SUM_DPCT.focus();
     return (false);
   }
   if(document.form1.MON_DPCT.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("���۾ɶ��Ϊ�գ�")?>");
     document.form1.MON_DPCT.focus();
     return (false);
   }
   if(document.form1.CPTL_KIND.value=="")
   { alert("<?=_("�ʲ����ʲ���Ϊ�գ�")?>");
     document.form1.CPTL_KIND.focus();
     return (false);
   }
   if(document.form1.PRCS_ID.value=="")
   { alert("<?=_("�������Ͳ���Ϊ�գ�")?>");
     document.form1.PRCS_ID.focus();
     return (false);
   }
   if(document.form1.FINISH_FLAG.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("�۾ɲ���Ϊ�գ�")?>");
     document.form1.FINISH_FLAG.focus();
     return (false);
   }
   if(document.form1.TO_NAME.value=="")
   { alert("<?=_("�����˲���Ϊ�գ�")?>");
     document.form1.TO_NAME.focus();
     return (false);
   }
   
   return true;
}
function sendForm()
{
   if(CheckForm())
      document.form1.submit();
}

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
      alert("<?=_("��ֵ����С��0")?>");
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

//���ʲ�ԭֵ-��ֵ-�ۼ��۾ɣ�/���۾�����*12��---��ֵ
//���ʲ�ԭֵ-�ۼ��۾ɣ�*��1-��ֵ��/100��/���۾�����*12��---��ֵ��
function cal_mon_dpct()
{
   if(form1.CPTL_VAL.value=="")
   {
      alert("<?=_("�ʲ�ԭֵ����Ϊ��")?>");
      document.form1.CPTL_VAL.focus();
      return;
   }
   if(form1.CPTL_BAL.value=="")
   {
      alert("<?=_("��ֵ�ʲ���Ϊ��")?>");
      document.form1.CPTL_BAL.focus();
      return;
   }
   if(form1.DPCT_YY.value=="")
   {
      alert("<?=_("�۾����޲���Ϊ��")?>");
      document.form1.DPCT_YY.focus();
      return;
   }
   if(form1.SUM_DPCT.value=="")
   {
      form1.SUM_DPCT.value="0.00";
      
   }
   
   cptl_val=ForDight(parseFloat(form1.CPTL_VAL.value),2);
   sum_dpct=ForDight(parseFloat(form1.SUM_DPCT.value),2);
   cptl_bal=ForDight(parseFloat(form1.CPTL_BAL.value),2);
   dpct_yy=ForDight(parseFloat(form1.DPCT_YY.value),2);
   if(cptl_val<0)
   {
      alert("<?=_("�ʲ�ԭֵ����С��0")?>");
      document.form1.CPTL_VAL.focus();
      return;
   }
   if(sum_dpct<0)
   {
      alert("<?=_("�ۼ��۾ɲ���С��0")?>");
      document.form1.SUM_DPCT.focus();
      return;
   }
   if(cptl_bal<0)
   {
      alert("<?=_("��ֵ�ʲ���С��0")?>");
      document.form1.CPTL_BAL.focus();
      return;
   }
   if(dpct_yy<0)
   {
      alert("<?=_("�۾����޲���С��0")?>");
      document.form1.DPCT_YY.focus();
      return;
   }
<?
 $query = "SELECT * from CP_ASSETCFG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $BAL_SORT=$ROW["BAL_SORT"];
 if($BAL_SORT=="01")
 {
?>
    if(cptl_val<sum_dpct+cptl_bal)
    {
       alert("<?=_("��ֵ���ۼ��۾�֮�Ͳ��ܴ����ʲ�ԭֵ")?>\"");
       return;
    }
    mon_dpct=(cptl_val-sum_dpct-cptl_bal)/(dpct_yy*12);
<?
 }
 else if($BAL_SORT=="02")
 {
?>
    if(cptl_val<sum_dpct)
    {
       alert("<?=_("�ۼ��۾ɲ��ܴ����ʲ�ԭֵ")?>");
       return;
    }
    if(cptl_bal>100)
    {
       alert("<?=_("��ֵ�ʲ��ܴ���100%")?>");
       return;
    }
    mon_dpct=(cptl_val-sum_dpct)*(1-cptl_bal/100)/(dpct_yy*12);
<?
 }
?>
   form1.MON_DPCT.value=ForDight(mon_dpct,2);
 
   check_value(form1.MON_DPCT);
}

</script>


<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("���ӹ̶��ʲ�")?></span>
    </td>
  </tr>
</table>
<form enctype="multipart/form-data" action="submit.php"  method="post" name="form1">
  <table class="TableBlock" width="60%" align="center">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ���ţ�")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NO" size="30" maxlength="100" class="BigInput" value=""><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ����ƣ�")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NAME" size="30" maxlength="200" class="BigInput" value=""><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ʲ����")?></td>
      <td class="TableData"> 
      <select name="TYPE_ID" class="BigSelect">
        <option value="0"></option>
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
        <option value="0"></option>
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
        <option value="01" selected><?=_("�ʲ�")?></option>
        <option value="02"><?=_("����")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ͣ�")?></td>
      <td class="TableData"> 
      <select name="PRCS_ID" class="BigSelect">
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
      <td nowrap class="TableData" width="100"> <?=_("�ʲ�ԭֵ��")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_VAL" size="20" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?=_("����")?><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?if($BAL_SORT=="01") echo _("��ֵ��"); else echo _("��ֵ�ʣ�");?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_BAL" size="20" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="01") echo _("����"); else echo "%";?><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�۾����ޣ�")?></td>
      <td class="TableData"> 
        <input type="text" name="DPCT_YY" size="20" maxlength="13" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?=_("����")?><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ۼ��۾ɣ�")?></td>
      <td class="TableData"> 
        <input type="text"  name="SUM_DPCT" size="20" maxlength="23" class="BigInput" value="0.00" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?=_("����")?>
        <?=_("Ϊ�ձ�ʾ���ʲ�")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("���۾ɶ")?></td>
      <td class="TableData"> 
        <input type="text" name="MON_DPCT" size="20" maxlength="23" class="BigStatic" value="" style="text-align:right;" readonly>&nbsp<span class="red">(*)</span>
        <input type="button" name="MON_DPCT_CAL"  class="SmallButton" onclick="cal_mon_dpct()" value="<?=_("����")?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�۾ɣ�")?></td>
      <td class="TableData"> 
        <input type="radio" name="FINISH_FLAG" id="FINISH_FLAG1" value="1"><label for="FINISH_FLAG1"><?=_("����")?></label>
        <input type="radio" name="FINISH_FLAG" id="FINISH_FLAG2" value="0" checked ><label for="FINISH_FLAG2"><?=_("δ����")?></label>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ڣ�")?></td>
      <td class="TableData"> 
        <input type="text" name="FROM_YYMM" id="FROM_YYMM" size="12" maxlength="10" class="BigInput" value="<?=$CUR_DATE?>" onClick="WdatePicker()">
        <input type="button" value="<?=_("���")?>" class="SmallButton" title="<?=_("���")?>" onclick="document.getElementById('FROM_YYMM').value = ''"> <?=_("Ϊ�ձ�ʾδ����")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�����ˣ�")?></td>
      <td class="TableData"> 
		<input type="hidden" name="TO_ID" value="">
		<input type="text" name="TO_NAME" size="20" class="BigInput" maxlength="20"  value="">&nbsp;
		<input type="button" value="<?=_("ѡ��")?>" class="SmallButton" onClick="SelectUserSingle('108','','TO_ID','TO_NAME')" title="<?=_("ѡ��")?>" name="button">&nbsp;
		<input type="button" value="<?=_("���")?>" class="SmallButton" onClick="ClearUser()" title="<?=_("���")?>" name="button"><span class="red">(*)</span>
      </td>
    </tr>
		<tr>
      <td nowrap class="TableData" width="100"> <?=_("�̶��ʲ���Ƭ��")?></td>
      <td class="TableData"> 
				 <input type="file" name="PHOTO" size="40" class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>">
				 <br /><?=_("��Ƭ�ļ�Ҫ����gif,jpg,png��ʽ")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ע��")?></td>
      <td class="TableData"> 
        <textarea name="REMARK" cols="80" rows="6" class="BigInput" wrap="yes"></textarea>
      </td>
    </tr>
    <tr>
      <td class="TableData" colspan="4">
<?
  echo get_field_table(get_field_html("CP_CPTL_INFO")); 
?>
      </td>
    </tr>        
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="sendForm();">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="location='index.php'">&nbsp;&nbsp;
      </td>
    </tr>

  </table>
 </form>
</body>
</html>
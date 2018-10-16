<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_org.php");

$PARA_ARRAY   = get_sys_para("SMS_REMIND");
$PARA_VALUE   = $PARA_ARRAY["SMS_REMIND"];
$REMIND_ARRAY = explode("|", $PARA_VALUE);
$SMS_REMIND   = $REMIND_ARRAY[0];
$SMS2_REMIND  = $REMIND_ARRAY[1];
$SMS3_REMIND  = $REMIND_ARRAY[2];


$HTML_PAGE_TITLE = _("����ʹ������");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script Language="JavaScript">
function IsNumber(str)
{
   return str.match(/^[0-9]*$/)!=null;
}

function CheckForm() {
    if (document.form1.RD.value == "0") {
        if (document.form1.V_ID.value == "") {
            alert("<?=_("��ָ��������")?>");
            return (false);
        } else {
            if (document.form1.VU_SUITE_ID.value != "") {
                var VU_SUITE_ID = document.form1.VU_SUITE_ID.value;
                var VU_ID = document.form1.V_ID.value;
                VU_ID = VU_ID.split("*")[0];
                $is = 1;
                jQuery.ajax({
                    url: "check_suite.php",
                    type: "GET",
                    async: false,
                    data: "VU_ID=" + VU_ID + "&VU_SUITE_ID=" + encodeURIComponent(VU_SUITE_ID),
                    success: function (data) {
                        if (data == "error") {
                            var msg = "<?=_("��ѡ�����Ա�ѳ�����λ���ƣ��Ƿ������")?>"
                            if (!window.confirm(msg)) {
                                $is = 0;
                            }
                        }
                    }
                });
                if ($is == 0) {
                    return false;
                }
            }
        }
        if (document.form1.VU_DESTINATION.value == "") {
            alert("<?=_("Ŀ�ĵز���Ϊ�գ�")?>");
            return (false);
        }
        if (document.form1.VU_START.value == "") {
            alert("<?=_("��ʼʱ�䲻��Ϊ�գ�")?>");
            return (false);
        }
        if (document.form1.VU_END.value == "") {
            alert("<?=_("����ʱ�䲻��Ϊ�գ�")?>");
            return (false);
        }
        if (document.form1.VU_START.value == document.form1.VU_END.value) {
            alert("<?=_("��ʼʱ�������ʱ�䲻����ȣ�")?>");
            return (false);
        }
        if (document.form1.VU_MILEAGE.value != "" && !IsNumber(document.form1.VU_MILEAGE.value)) {
            alert("<?=_("�������ӦΪ���֣�")?>");
            return (false);
        }
        if (document.form1.VU_OPERATOR.value == "") {
            alert("<?=_("��ָ��������Ա��")?>");
            return (false);
        }
        if (document.form1.TO_ID1.value == "") {
            alert("<?=_("��ָ���ó��ˣ� ") ?>");
            return (false);
        }
        if (jQuery('input[name="bench"]:checked').val() == "1" && jQuery('#VU_OPERATOR1').val() == "") {
            alert("<?=_("��ָ����ѡ������Ա��") ?>");
            return false;
        }
        if (document.form1.VU_OPERATOR1.value == jQuery('#VU_OPERATOR').val()) {
            alert("<?=_("������Ա�ͱ�ѡ������Ա������ͬ��") ?>");
            return false;
        }
        if (document.form1.VU_START.value != "" && document.form1.VU_END.value != "") {
            var VU_START = document.form1.VU_START.value;
            var VU_END = document.form1.VU_END.value;
            var VU_ID = document.form1.V_ID.value;
            VU_ID = VU_ID.split("*")[0];
            VU_START = VU_START.split(" ")[0];
            VU_END = VU_END.split(" ")[0];
            _get("checkMaintain.php?VU_ID=" + VU_ID + "&VU_START=" + VU_START + "&VU_END=" + VU_END, '', function (req) {
                if (req.status == 200) {
                    if (req.responseText == "error") {
                        alert("<?=_("��ѡ��ĳ����������ʱ����ڽ���ά����") ?>");
                        return false;
                    } else {
                        form1.submit();
                    }
                }
            });
        }
    }else{
        form1.submit();
    }
}
function time_status(str)
{
    if(str=="1")
    {
        document.form1.action="out.php";
    }else{
        document.form1.action="add_1.php";
    }
}
function showDetail()
{
	
   var tem1 = form1.V_ID.value.indexOf("*");  
   var tem2 = form1.V_ID.value.substr(0,tem1);  
   var tem3 = form1.V_ID.value.substr(tem1+1);
    
   form1.VU_DRIVER.value=tem3;
   document.getElementById("vehicle_detail").src="show_detail.php?V_ID="+tem2;
}

function changeRadio(value)
{
    if(value == 1)
    {
        jQuery("#VU_OPERATOR1").removeAttr("disabled");
        jQuery("#SMS_REMIND2").removeAttr("disabled");
        jQuery("#SMS_REMIND2").prop("checked", true);
        jQuery("#SMS2_REMIND2").removeAttr("disabled");
        jQuery("#SMS2_REMIND2").prop("checked", true);
    }
    else
    {
        jQuery("#VU_OPERATOR1").find("[value='']").prop("selected", true);
        jQuery("#VU_OPERATOR1").prop("disabled", true);
        jQuery("#SMS_REMIND2").prop("disabled", true);
        jQuery("#SMS_REMIND2").removeAttr("checked");
        jQuery("#SMS2_REMIND2").prop("disabled", true);
        jQuery("#SMS2_REMIND2").removeAttr("checked");
        
    }
}
</script>


<body class="bodycolor">

<?
if ($TO_ID!="")
{
	$query  = "SELECT USER_NAME FROM user WHERE USER_ID='$TO_ID'";
	$cursor = exequery(TD::conn(),$query);
	if($ROW = mysql_fetch_array($cursor))
	{
		$TO_NAME = $ROW['USER_NAME'];
	}
}

if($V_NUM!='')
{
	$V_NUM1 = $V_NUM;
}
$CUR_TIME = date("Y-m-d H:i:s",time());
if($VU_ID!="")
{
   $query  = "SELECT * FROM VEHICLE_USAGE  WHERE VU_ID='$VU_ID'";
   $cursor = exequery(TD::conn(),$query);
   if($ROW = mysql_fetch_array($cursor))
   {
      $V_ID            = $ROW["V_ID"];
      $VU_PROPOSER     = $ROW["VU_PROPOSER"];
      $VU_REQUEST_DATE = $ROW["VU_REQUEST_DATE"];
      $VU_USER         = $ROW["VU_USER"];
      $VU_SUITE        = $ROW["VU_SUITE"];
      $VU_REASON       = $ROW["VU_REASON"];
      $VU_START        = $ROW["VU_START"];
      $VU_END          = $ROW["VU_END"];
      $VU_MILEAGE      = $ROW["VU_MILEAGE"];
      $VU_DEPT         = $ROW["VU_DEPT"];
      $VU_STATUS       = $ROW["VU_STATUS"];
      $VU_REMARK       = $ROW["VU_REMARK"];
      $VU_DESTINATION  = $ROW["VU_DESTINATION"];
      $VU_OPERATOR     = $ROW["VU_OPERATOR"];
      $VU_DRIVER       = $ROW["VU_DRIVER"];
      $DEPT_MANAGER    = $ROW["DEPT_MANAGER"];
      $VU_OPERATOR1    = $ROW["VU_OPERATOR1"];
      $VU_OPERATOR1_SMS_TYPE    = $ROW["VU_OPERATOR1_SMS_TYPE"];
      $query_name  = "SELECT USER_NAME FROM user WHERE USER_ID = '$VU_USER'";
   	  $cursor_name = exequery(TD::conn(),$query_name);
	  if($ROW_NAME=mysql_fetch_array($cursor_name))
	  {
		  $VU_USER_ID = $ROW_NAME["USER_NAME"] != ""? $VU_USER:"";
		  $VU_USER = $ROW_NAME["USER_NAME"]    != ""? $ROW_NAME["USER_NAME"]:$VU_USER;
   	  }
	  if($VU_SUITE!="")
	  {
		  $VU_SUITE_ID ="";
		  $sql = "SELECT USER_ID FROM user WHERE find_in_set(USER_NAME,'$VU_SUITE')";
		  $cur = exequery(TD::conn(),$sql);
		  while($arr=mysql_fetch_array($cur))
		  {
			  $VU_SUITE_ID .= $arr['USER_ID'].",";
		  }
	  }
      if($VU_START=="0000-00-00 00:00:00")
	  {
		 $VU_START = ""; 
	  }    
      if($VU_END=="0000-00-00 00:00:00")
	  {
		 $VU_END = ""; 
	  }
         
   }
}
else
{
   $VU_START = $CUR_TIME;
   $VU_END   = $CUR_TIME;
}

$query  = "SELECT USER_NAME FROM user WHERE USER_ID='$DEPT_MANAGER'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
   $DEPT_MANAGER_NAME = $ROW["USER_NAME"];

if($VU_REQUEST_DATE=="0000-00-00 00:00:00" || $VU_REQUEST_DATE=="")
   $VU_REQUEST_DATE = $CUR_TIME;
if($VU_PROPOSER=="")
   $VU_PROPOSER = $_SESSION["LOGIN_USER_ID"];

$query  = "SELECT USER_NAME FROM user WHERE USER_ID='$VU_PROPOSER'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
   $VU_PROPOSER_NAME = $ROW["USER_NAME"];

if($VU_DEPT!="")
{
   $query  = "SELECT DEPT_NAME FROM department WHERE DEPT_ID='$VU_DEPT'";
   $cursor = exequery(TD::conn(),$query);
   if($ROW = mysql_fetch_array($cursor))
      $VU_DEPT_FIELD_DESC = $ROW["DEPT_NAME"];
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" HEIGHT="20"><span class="big3"> <?=_("����ʹ������")?></span>
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="<?if ($FLAG=="1") echo "add_1";else echo "add"?>.php" method="post" name="form1">
<? 
if ($FLAG=="1")
{
	$OUT_NAME = GetUserNameByUid($_SESSION["LOGIN_UID"]);

?>
		<table class="TableBlock" width="90%" align="center">
			<tr>
			<td nowrap class="TableData" width="10%"> <?=_("����ˣ�")?></td>
			<td nowrap class="TableData">
			<input type="hidden" name="TO_ID2" value="<?if($TO_ID!=""){echo $TO_ID;}else{echo $_SESSION["LOGIN_USER_ID"];}?>">
			<input type="text" name="TO_NAME2" value="<?if($TO_ID!="")echo $TO_NAME;else echo td_trim($OUT_NAME);?>">
			</td>
			</tr>
			<tr>
                <td nowrap class="TableData"> <?=_("���ԭ��")?></td>
                <td class="TableData">
                    <textarea name="OUT_TYPE" class="BigInput" cols="60" rows="3"><?=$OUT_TYPE?></textarea>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("���ʱ�䣺")?></td>
                <td class="TableData">
                    <?=_("����")?> <input type="text" name="OUT_DATE" size="15" maxlength="10" class="BigInput" value="<?=$OUT_DATE?>" onClick="WdatePicker()"/>
                    <?=_("��")?> <input type="text" name="OUT_TIME1" size="5" maxlength="5" class="BigInput" value="<?=$OUT_TIME1?>">
                    <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" align="absMiddle" style="cursor:hand" onclick="td_clock('form1.OUT_TIME1');">
                    <?=_("��")?> <input type="text" name="OUT_TIME2" size="5" maxlength="5" class="BigInput" value="<?=$OUT_TIME2?>">
                    <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" align="absMiddle" style="cursor:hand" onclick="td_clock('form1.OUT_TIME2');"><br>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("�����ˣ�")?></td>
                <td class="TableData">
                    <select name="LEADER_ID" class="BigSelect">
                    <?
                        include_once("../attendance/personal/manager.inc.php");
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("�������ѣ�")?></td>
                <?if (find_id($SMS3_REMIND, 6)){?>
                <td class="TableData"><input type="checkbox" name="SMS_REMIND_OUT" id="SMS_REMIND_OUT" <?if (find_id($SMS_REMIND, 6)) echo "checked";?>><label for="SMS_REMIND_OUT"><?=_("��������������Ϣ")?></label>&nbsp;&nbsp;
                <?}
                $query  = "SELECT * FROM SMS2_PRIV";
                $cursor = exequery(TD::conn(),$query);
                if($ROW = mysql_fetch_array($cursor))
                {
                    $TYPE_PRIV        = $ROW["TYPE_PRIV"];
                    $SMS2_REMIND_PRIV = $ROW["SMS2_REMIND_PRIV"];
                }
                if (find_id($TYPE_PRIV, 6) && find_id($SMS2_REMIND_PRIV, $_SESSION["LOGIN_USER_ID"]))
                {
                ?>
                <input type="checkbox" name="SMS2_REMIND_OUT" id="SMS2_REMIND_OUT" <?if(find_id($SMS2_REMIND, 6)) echo " checked";?>><label for="SMS2_REMIND_OUT"><?=_("ʹ���ֻ���������")?></label>
                <?}?>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData" style="color: red"><?=_("�����ó���")?></td>
                <td nowrap class="TableData" style="color: red">
                    <input type="radio" name="RD" value="1" onClick="time_status('1')"><?=_("��")?>
                    <input type="radio" name="RD" value="0" onClick="time_status('0')" checked="true"><?=_("�� (��������ó��ˣ���ѡ�� �� )")?>
                </td>
            </tr>
		</table>
<?
}else{
?>
    <input type="hidden" name="RD" value="0">
<?
}
?>
<table class="TableBlock" align="center" width="90%">
    <tr>
      <td nowrap class="TableData" width="10%"> <?=_("���ƺţ�")?></td>
      <td class="TableData" width="230">
        <select name="V_ID" class="BigSelect" onChange="showDetail();">
        <option value=""></option>
<?
$query  = "SELECT OPERATOR_NAME FROM vehicle_operator WHERE OPERATOR_ID='1'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
   $MANAGERS = $ROW["OPERATOR_NAME"];

if(find_id($MANAGERS,$_SESSION["LOGIN_USER_ID"]))
   $query = "SELECT V_ID,V_NUM,V_DRIVER FROM vehicle WHERE V_STATUS='0' order by V_NUM";
else
   $query = "SELECT V_ID,V_NUM,V_DRIVER FROM vehicle WHERE V_STATUS ='0' and (DEPT_RANGE = 'ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_RANGE) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_RANGE) or DEPT_RANGE='' and USER_RANGE='') order by V_NUM";
$cursor1= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor1))
{
   $V_ID1    = $ROW1["V_ID"];
   $V_NUM    = $ROW1["V_NUM"];
   $V_DRIVER = $ROW1["V_DRIVER"]; 
   
?>
          <option value="<?=$V_ID1?>*<?=$V_DRIVER?>" <? if($V_ID==$V_ID1||$V_ID1==$V_NUM1) echo "selected";?>><?=$V_NUM?></option>
<?
}
?>
        </select>
      &nbsp;<a href="javascript:;" onClick="window.open('prearrange.php','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=100,resizable=yes');"><?=_("ԤԼ���")?></a>        
      </td>
      <td nowrap class="TableData" width="80"></td>
      <td class="TableData" width="230"><input type="text" style="display:none"  name="VU_DRIVER" size="11" maxlength="100" class="BigInput" value="<?=$VU_DRIVER?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ó��ˣ�")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID1" value="<?if ($FLAG=="1") {if($TO_ID!=0)echo $TO_ID;else echo $_SESSION["LOGIN_USER_ID"];} else echo $VU_USER_ID?>"> 	
        <input type="text" name="TO_NAME1" size="13"  value="<?if ($FLAG=="1"){ if($TO_ID!=0)echo $TO_NAME;else echo td_trim($OUT_NAME);}else echo $VU_USER?>" readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','TO_ID1','TO_NAME1','vehicle')"><?=_("ѡ��")?></a>
      </td>
      <td nowrap class="TableData"> <?=_("�ó����ţ�")?></td>
      <td class="TableData">
        <input type="hidden" name="VU_DEPT" value="<?=$VU_DEPT?>">
        <input type="text" name="VU_DEPT_FIELD_DESC" value="<?=$VU_DEPT_FIELD_DESC?>" class=BigStatic size=20 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','VU_DEPT','VU_DEPT_FIELD_DESC')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("������Ա��")?></td>
      <td class="TableData" colspan="3">
        <input type="hidden" name="VU_SUITE_ID" id="VU_SUITE_ID" value="<?=$VU_SUITE_ID?>">
        <textarea name="VU_SUITE" class="BigStatic" cols="50" rows="2" wrap="yes"  readonly><?=$VU_SUITE?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('90','','VU_SUITE_ID','VU_SUITE')"><?=_("ѡ��")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('VU_SUITE_ID', 'VU_SUITE')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ʼʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="VU_START" size="20" maxlength="19" class="BigInput" value="<?=$VU_START?>" 
onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
     
      </td>
      <td nowrap class="TableData"> <?=_("����ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="VU_END" size="20" maxlength="19" class="BigInput" value="<?=$VU_END?>" 
onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("Ŀ�ĵأ�")?></td>
      <td class="TableData">
        <input type="text" name="VU_DESTINATION" size="20" maxlength="100" class="BigInput" value="<?=$VU_DESTINATION?>">
      </td>
      <td nowrap class="TableData"> <?=_("������̣�")?></td>
      <td class="TableData">
        <input type="text" name="VU_MILEAGE" size="10" maxlength="14" class="BigInput" value="<?=$VU_MILEAGE?>"> (<?=_("����")?>)
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("���������ˣ�")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$DEPT_MANAGER?>"> 	
        <input type="text" name="TO_NAME" size="13" class="BigStatic" value="<?=$DEPT_MANAGER_NAME?>" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','TO_ID', 'TO_NAME','vehicle')"><?=_("ѡ��")?></a><br><?=_("Ϊ��ʱ��ֱ���ɵ���Ա��������Ϊ��ʱ���Ƚ����������������������ɵ���Ա����")?>
      </td>
      <td nowrap class="TableData"> <?=_("����Ա��")?></td>
      <td class="TableData">
        <select style="margin-right:3px;" id="VU_OPERATOR" name="VU_OPERATOR" class="BigSelect">
<?
$query   = "SELECT OPERATOR_NAME FROM VEHICLE_OPERATOR WHERE OPERATOR_ID=1";
$cursor1 = exequery(TD::conn(),$query);
if($ROW1 = mysql_fetch_array($cursor1))
{
   $OPERATOR_NAME = $ROW1["OPERATOR_NAME"];
   $query  = "SELECT USER_ID,USER_NAME FROM user WHERE find_in_set(USER_ID,'$OPERATOR_NAME') and LEAVE_DEPT='0' and DEPT_ID!=0 and (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) order by USER_NO,USER_NAME";
   $cursor = exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID   = $ROW["USER_ID"];
      $USER_NAME = $ROW["USER_NAME"];
?>
          <option value="<?=$USER_ID?>" <? if($VU_OPERATOR==$USER_ID) echo "selected";?>><?=$USER_NAME?></option>
<?
   }
}
?>
        </select>
        <?=_("�Ƿ����ñ�ѡ����Ա")?>
        
        <input type="radio" id="bench1" name="bench" value="1" checked onClick="changeRadio(this.value)"><?=_("��")?>
        <input type="radio" id="bench0" name="bench" value="0" checked onClick="changeRadio(this.value)"><?=_("��")?>
        <?=_("(ע�������������ջ�)")?>
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("���ߵ���")?><br><?=_("��Ա��")?></td>
      <td class="TableData">
<?
$POSTFIX = _("��");
$ONLINE_USER_NAME="";
$query = "SELECT USER_ID,USER_NAME FROM user,user_online WHERE user.UID=user_online.UID and USER_ID!='' and find_in_set(USER_ID,'$OPERATOR_NAME') and user.DEPT_ID!=0 and (user.NOT_LOGIN = 0 or user.NOT_MOBILE_LOGIN = 0) group by user_online.UID order by USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $USER_ID           = $ROW["USER_ID"];
   $USER_NAME         = $ROW["USER_NAME"];
   $ONLINE_USER_NAME .= $USER_NAME.$POSTFIX;
}
echo substr($ONLINE_USER_NAME,0,-strlen($POSTFIX));

?>
     </td>
     <td nowrap class="TableData"> <?=_("��ѡ����Ա��")?></td>
     <td class="TableData">
        <select id="VU_OPERATOR1" name="VU_OPERATOR1" class="BigSelect" disabled>
            <option value=""><?=_("--��ѡ��--")?></option>
            
<?
$query   = "SELECT OPERATOR_NAME FROM VEHICLE_OPERATOR WHERE OPERATOR_ID=1";
$cursor1 = exequery(TD::conn(),$query);
if($ROW1 = mysql_fetch_array($cursor1))
{
   $OPERATOR_NAME = $ROW1["OPERATOR_NAME"];
   $query  = "SELECT USER_ID,USER_NAME FROM user WHERE find_in_set(USER_ID,'$OPERATOR_NAME') and DEPT_ID!=0 and (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) order by USER_NO,USER_NAME";
   $cursor = exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID   = $ROW["USER_ID"];
      $USER_NAME = $ROW["USER_NAME"];
?>
       <option value="<?=$USER_ID?>" <? if($VU_OPERATOR1==$USER_ID) echo "selected";?>><?=$USER_NAME?></option>
<?
   }
}
?>
        </select>  <?=_("(ע��������Ա���ڣ������ñ�ѡ����Ա��ֻ�����ջ�)")?>
      </td>
     
    </tr>   
    <tr>
      <td nowrap class="TableData"> <?=_("���ɣ�")?></td>
      <td class="TableData" colspan="3">
        <textarea name="VU_REASON" class="BigInput" cols="74" rows="2"><?=$VU_REASON?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ע��")?></td>
      <td class="TableData" colspan="3">
        <textarea name="VU_REMARK" class="BigInput" cols="76" rows="2"><?=$VU_REMARK?></textarea>
      </td>
    </tr>  
    <tr>
      <td nowrap class="TableData"> <?=_("���ѵ���Ա��")?></td>
      <td class="TableData" colspan="3">
      <?if (find_id($SMS3_REMIND, 9)){?>
    	  <input type="checkbox" name="SMS_REMIND1" id="SMS_REMIND1"<?if(find_id($SMS_REMIND,"9")) echo " checked";?>><label for="SMS_REMIND1"><?=_("��������������Ϣ")?></label>&nbsp;
<?}
//$REMIND_CONTENT=_("����Ϊ˾����������׼����");
$query  = "SELECT * FROM sms2_priv";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
   $TYPE_PRIV        = $ROW["TYPE_PRIV"];
   $SMS2_REMIND_PRIV = $ROW["SMS2_REMIND_PRIV"];
}

if(find_id($TYPE_PRIV,9) && find_id($SMS2_REMIND_PRIV, $_SESSION["LOGIN_USER_ID"])) //����ģ���Ƿ������ֻ�����
{
?>
       <input type="checkbox" name="SMS2_REMIND1" id="SMS2_REMIND1"<?if(find_id($SMS2_REMIND,"9")) echo " checked";?>><label for="SMS2_REMIND1"><?=_("ʹ���ֻ���������")?></label>
<?
}
?>&nbsp;&nbsp;˵���������Ƿ�֪ͨ����Ա����
    </td>
  </tr>
  
    <tr>
        <td nowrap class="TableData"><?=_("���ѱ�ѡ����Ա��")?></td>
        <td class="TableData" colspan="3">
            <?if (find_id($SMS3_REMIND, 9)){?>
                <input type="checkbox" name="SMS_REMIND2" id="SMS_REMIND2" disabled><label for="SMS_REMIND2"><?=_("��������������Ϣ")?></label>&nbsp;
            <?}
            $query  = "SELECT * FROM sms2_priv";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
            {
                $TYPE_PRIV        = $ROW["TYPE_PRIV"];
                $SMS2_REMIND_PRIV = $ROW["SMS2_REMIND_PRIV"];
            }

            if(find_id($TYPE_PRIV,9) && find_id($SMS2_REMIND_PRIV, $_SESSION["LOGIN_USER_ID"])) //����ģ���Ƿ������ֻ�����
            {
                ?>
                <input type="checkbox" name="SMS2_REMIND2" id="SMS2_REMIND2" disabled><label for="SMS2_REMIND2"><?=_("ʹ���ֻ���������")?></label>
                <?
            }
            ?>&nbsp;&nbsp;˵�������������ɹ����Ƿ�֪ͨ��ѡ����Ա
        </td>
    </tr> 
  
  <tr>
      <td nowrap class="TableData"><?=_("֪ͨ�����ˣ�")?></td>
      <td class="TableData" colspan="3">
    	  <?=sms_remind(9);?>&nbsp;&nbsp;˵���������������Ƿ�֪ͨ������
    </td>
  </tr>  
    <tr class="TableControl">
      <td nowrap colspan="4" align="center">
        <input type="hidden" value="<?=$VU_ID?>" name="VU_ID">
        <input type="hidden" name="VU_PROPOSER" value="<?=$_SESSION["LOGIN_USER_ID"]?>">
        <input type="hidden" name="VU_REQUEST_DATE" value="<?=$VU_REQUEST_DATE?>">
        <input type="hidden" name="VU_PROPOSER_NAME" value="<?=$VU_PROPOSER_NAME?>">
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="CheckForm();">&nbsp;&nbsp;
        <input type="reset" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
<?
if($VU_ID!="" && isset($DMER_STATUS))
{
?>
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='/general/vehicle/dept_manage/query.php?DMER_STATUS=<?=$DMER_STATUS?>'">
<?
}elseif($VU_ID!="")
{
?>
		<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='query.php?VU_STATUS=<?=$VU_STATUS?>'">
<?
}
?>
      </td>
    </tr>
    </table>
</form>

<iframe id="vehicle_detail" style="margin: 0 auto; height:220px;width:90%;" src="show_detail.php" frameBorder="0" frameSpacing="0" scrolling="yes" align="center"></iframe>

<script>
   var tem1 = form1.V_ID.value.indexOf("*");
   var tem2 = form1.V_ID.value.substr(0,tem1);	

   document.getElementById("vehicle_detail").src="show_detail.php?V_ID="+tem2;
</script>
<?
    if($VU_OPERATOR1_SMS_TYPE == 1 || $VU_OPERATOR1_SMS_TYPE == 3)
    {
        ?>
            <script type="text/javascript">
                document.getElementById('bench0').checked = false;  
                document.getElementById('bench1').checked = true;                          
                jQuery("#SMS_REMIND2").removeAttr("disabled");
                jQuery("#SMS_REMIND2").prop("checked", true);
                jQuery("#VU_OPERATOR1").removeAttr("disabled");
                //document.getElementById('checked_delete').selected = false;  
                jQuery("#SMS2_REMIND2").removeAttr("disabled");
                jQuery("#SMS2_REMIND2").prop("checked", true);
            </script>
        <?
    }
?>
</body>
</html>
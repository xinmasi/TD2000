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


$HTML_PAGE_TITLE = _("车辆使用申请");
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
            alert("<?=_("请指定车辆！")?>");
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
                            var msg = "<?=_("您选择的人员已超出座位限制！是否继续？")?>"
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
            alert("<?=_("目的地不能为空！")?>");
            return (false);
        }
        if (document.form1.VU_START.value == "") {
            alert("<?=_("起始时间不能为空！")?>");
            return (false);
        }
        if (document.form1.VU_END.value == "") {
            alert("<?=_("结束时间不能为空！")?>");
            return (false);
        }
        if (document.form1.VU_START.value == document.form1.VU_END.value) {
            alert("<?=_("开始时间与结束时间不能相等！")?>");
            return (false);
        }
        if (document.form1.VU_MILEAGE.value != "" && !IsNumber(document.form1.VU_MILEAGE.value)) {
            alert("<?=_("申请里程应为数字！")?>");
            return (false);
        }
        if (document.form1.VU_OPERATOR.value == "") {
            alert("<?=_("请指定调度人员！")?>");
            return (false);
        }
        if (document.form1.TO_ID1.value == "") {
            alert("<?=_("请指定用车人！ ") ?>");
            return (false);
        }
        if (jQuery('input[name="bench"]:checked').val() == "1" && jQuery('#VU_OPERATOR1').val() == "") {
            alert("<?=_("请指定备选调度人员！") ?>");
            return false;
        }
        if (document.form1.VU_OPERATOR1.value == jQuery('#VU_OPERATOR').val()) {
            alert("<?=_("调度人员和备选调度人员不能相同！") ?>");
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
                        alert("<?=_("您选择的车辆在申请的时间段内将会维护！") ?>");
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" HEIGHT="20"><span class="big3"> <?=_("车辆使用申请")?></span>
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
			<td nowrap class="TableData" width="10%"> <?=_("外出人：")?></td>
			<td nowrap class="TableData">
			<input type="hidden" name="TO_ID2" value="<?if($TO_ID!=""){echo $TO_ID;}else{echo $_SESSION["LOGIN_USER_ID"];}?>">
			<input type="text" name="TO_NAME2" value="<?if($TO_ID!="")echo $TO_NAME;else echo td_trim($OUT_NAME);?>">
			</td>
			</tr>
			<tr>
                <td nowrap class="TableData"> <?=_("外出原因：")?></td>
                <td class="TableData">
                    <textarea name="OUT_TYPE" class="BigInput" cols="60" rows="3"><?=$OUT_TYPE?></textarea>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("外出时间：")?></td>
                <td class="TableData">
                    <?=_("日期")?> <input type="text" name="OUT_DATE" size="15" maxlength="10" class="BigInput" value="<?=$OUT_DATE?>" onClick="WdatePicker()"/>
                    <?=_("从")?> <input type="text" name="OUT_TIME1" size="5" maxlength="5" class="BigInput" value="<?=$OUT_TIME1?>">
                    <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" align="absMiddle" style="cursor:hand" onclick="td_clock('form1.OUT_TIME1');">
                    <?=_("至")?> <input type="text" name="OUT_TIME2" size="5" maxlength="5" class="BigInput" value="<?=$OUT_TIME2?>">
                    <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" align="absMiddle" style="cursor:hand" onclick="td_clock('form1.OUT_TIME2');"><br>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("审批人：")?></td>
                <td class="TableData">
                    <select name="LEADER_ID" class="BigSelect">
                    <?
                        include_once("../attendance/personal/manager.inc.php");
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("事务提醒：")?></td>
                <?if (find_id($SMS3_REMIND, 6)){?>
                <td class="TableData"><input type="checkbox" name="SMS_REMIND_OUT" id="SMS_REMIND_OUT" <?if (find_id($SMS_REMIND, 6)) echo "checked";?>><label for="SMS_REMIND_OUT"><?=_("发送事务提醒消息")?></label>&nbsp;&nbsp;
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
                <input type="checkbox" name="SMS2_REMIND_OUT" id="SMS2_REMIND_OUT" <?if(find_id($SMS2_REMIND, 6)) echo " checked";?>><label for="SMS2_REMIND_OUT"><?=_("使用手机短信提醒")?></label>
                <?}?>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData" style="color: red"><?=_("撤销用车：")?></td>
                <td nowrap class="TableData" style="color: red">
                    <input type="radio" name="RD" value="1" onClick="time_status('1')"><?=_("是")?>
                    <input type="radio" name="RD" value="0" onClick="time_status('0')" checked="true"><?=_("否 (如果不想用车了，请选择 是 )")?>
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
      <td nowrap class="TableData" width="10%"> <?=_("车牌号：")?></td>
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
      &nbsp;<a href="javascript:;" onClick="window.open('prearrange.php','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=100,resizable=yes');"><?=_("预约情况")?></a>        
      </td>
      <td nowrap class="TableData" width="80"></td>
      <td class="TableData" width="230"><input type="text" style="display:none"  name="VU_DRIVER" size="11" maxlength="100" class="BigInput" value="<?=$VU_DRIVER?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("用车人：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID1" value="<?if ($FLAG=="1") {if($TO_ID!=0)echo $TO_ID;else echo $_SESSION["LOGIN_USER_ID"];} else echo $VU_USER_ID?>"> 	
        <input type="text" name="TO_NAME1" size="13"  value="<?if ($FLAG=="1"){ if($TO_ID!=0)echo $TO_NAME;else echo td_trim($OUT_NAME);}else echo $VU_USER?>" readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','TO_ID1','TO_NAME1','vehicle')"><?=_("选择")?></a>
      </td>
      <td nowrap class="TableData"> <?=_("用车部门：")?></td>
      <td class="TableData">
        <input type="hidden" name="VU_DEPT" value="<?=$VU_DEPT?>">
        <input type="text" name="VU_DEPT_FIELD_DESC" value="<?=$VU_DEPT_FIELD_DESC?>" class=BigStatic size=20 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','VU_DEPT','VU_DEPT_FIELD_DESC')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("随行人员：")?></td>
      <td class="TableData" colspan="3">
        <input type="hidden" name="VU_SUITE_ID" id="VU_SUITE_ID" value="<?=$VU_SUITE_ID?>">
        <textarea name="VU_SUITE" class="BigStatic" cols="50" rows="2" wrap="yes"  readonly><?=$VU_SUITE?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('90','','VU_SUITE_ID','VU_SUITE')"><?=_("选择")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('VU_SUITE_ID', 'VU_SUITE')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("起始时间：")?></td>
      <td class="TableData">
        <input type="text" name="VU_START" size="20" maxlength="19" class="BigInput" value="<?=$VU_START?>" 
onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
     
      </td>
      <td nowrap class="TableData"> <?=_("结束时间：")?></td>
      <td class="TableData">
        <input type="text" name="VU_END" size="20" maxlength="19" class="BigInput" value="<?=$VU_END?>" 
onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("目的地：")?></td>
      <td class="TableData">
        <input type="text" name="VU_DESTINATION" size="20" maxlength="100" class="BigInput" value="<?=$VU_DESTINATION?>">
      </td>
      <td nowrap class="TableData"> <?=_("申请里程：")?></td>
      <td class="TableData">
        <input type="text" name="VU_MILEAGE" size="10" maxlength="14" class="BigInput" value="<?=$VU_MILEAGE?>"> (<?=_("公里")?>)
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("部门审批人：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$DEPT_MANAGER?>"> 	
        <input type="text" name="TO_NAME" size="13" class="BigStatic" value="<?=$DEPT_MANAGER_NAME?>" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','TO_ID', 'TO_NAME','vehicle')"><?=_("选择")?></a><br><?=_("为空时，直接由调度员审批；不为空时，先交给部门审批人审批，再由调度员审批")?>
      </td>
      <td nowrap class="TableData"> <?=_("调度员：")?></td>
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
        <?=_("是否启用备选调度员")?>
        
        <input type="radio" id="bench1" name="bench" value="1" checked onClick="changeRadio(this.value)"><?=_("是")?>
        <input type="radio" id="bench0" name="bench" value="0" checked onClick="changeRadio(this.value)"><?=_("否")?>
        <?=_("(注：负责审批和收回)")?>
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("在线调度")?><br><?=_("人员：")?></td>
      <td class="TableData">
<?
$POSTFIX = _("，");
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
     <td nowrap class="TableData"> <?=_("备选调度员：")?></td>
     <td class="TableData">
        <select id="VU_OPERATOR1" name="VU_OPERATOR1" class="BigSelect" disabled>
            <option value=""><?=_("--请选择--")?></option>
            
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
        </select>  <?=_("(注：若调度员不在，可启用备选调度员，只负责收回)")?>
      </td>
     
    </tr>   
    <tr>
      <td nowrap class="TableData"> <?=_("事由：")?></td>
      <td class="TableData" colspan="3">
        <textarea name="VU_REASON" class="BigInput" cols="74" rows="2"><?=$VU_REASON?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("备注：")?></td>
      <td class="TableData" colspan="3">
        <textarea name="VU_REMARK" class="BigInput" cols="76" rows="2"><?=$VU_REMARK?></textarea>
      </td>
    </tr>  
    <tr>
      <td nowrap class="TableData"> <?=_("提醒调度员：")?></td>
      <td class="TableData" colspan="3">
      <?if (find_id($SMS3_REMIND, 9)){?>
    	  <input type="checkbox" name="SMS_REMIND1" id="SMS_REMIND1"<?if(find_id($SMS_REMIND,"9")) echo " checked";?>><label for="SMS_REMIND1"><?=_("发送事务提醒消息")?></label>&nbsp;
<?}
//$REMIND_CONTENT=_("任您为司机，请做好准备！");
$query  = "SELECT * FROM sms2_priv";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
   $TYPE_PRIV        = $ROW["TYPE_PRIV"];
   $SMS2_REMIND_PRIV = $ROW["SMS2_REMIND_PRIV"];
}

if(find_id($TYPE_PRIV,9) && find_id($SMS2_REMIND_PRIV, $_SESSION["LOGIN_USER_ID"])) //检查该模块是否允许手机提醒
{
?>
       <input type="checkbox" name="SMS2_REMIND1" id="SMS2_REMIND1"<?if(find_id($SMS2_REMIND,"9")) echo " checked";?>><label for="SMS2_REMIND1"><?=_("使用手机短信提醒")?></label>
<?
}
?>&nbsp;&nbsp;说明：设置是否通知调度员审批
    </td>
  </tr>
  
    <tr>
        <td nowrap class="TableData"><?=_("提醒备选调度员：")?></td>
        <td class="TableData" colspan="3">
            <?if (find_id($SMS3_REMIND, 9)){?>
                <input type="checkbox" name="SMS_REMIND2" id="SMS_REMIND2" disabled><label for="SMS_REMIND2"><?=_("发送事务提醒消息")?></label>&nbsp;
            <?}
            $query  = "SELECT * FROM sms2_priv";
            $cursor = exequery(TD::conn(),$query);
            if($ROW = mysql_fetch_array($cursor))
            {
                $TYPE_PRIV        = $ROW["TYPE_PRIV"];
                $SMS2_REMIND_PRIV = $ROW["SMS2_REMIND_PRIV"];
            }

            if(find_id($TYPE_PRIV,9) && find_id($SMS2_REMIND_PRIV, $_SESSION["LOGIN_USER_ID"])) //检查该模块是否允许手机提醒
            {
                ?>
                <input type="checkbox" name="SMS2_REMIND2" id="SMS2_REMIND2" disabled><label for="SMS2_REMIND2"><?=_("使用手机短信提醒")?></label>
                <?
            }
            ?>&nbsp;&nbsp;说明：设置审批成功后是否通知备选调度员
        </td>
    </tr> 
  
  <tr>
      <td nowrap class="TableData"><?=_("通知申请人：")?></td>
      <td class="TableData" colspan="3">
    	  <?=sms_remind(9);?>&nbsp;&nbsp;说明：设置审批后是否通知申请人
    </td>
  </tr>  
    <tr class="TableControl">
      <td nowrap colspan="4" align="center">
        <input type="hidden" value="<?=$VU_ID?>" name="VU_ID">
        <input type="hidden" name="VU_PROPOSER" value="<?=$_SESSION["LOGIN_USER_ID"]?>">
        <input type="hidden" name="VU_REQUEST_DATE" value="<?=$VU_REQUEST_DATE?>">
        <input type="hidden" name="VU_PROPOSER_NAME" value="<?=$VU_PROPOSER_NAME?>">
        <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="CheckForm();">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
<?
if($VU_ID!="" && isset($DMER_STATUS))
{
?>
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='/general/vehicle/dept_manage/query.php?DMER_STATUS=<?=$DMER_STATUS?>'">
<?
}elseif($VU_ID!="")
{
?>
		<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php?VU_STATUS=<?=$VU_STATUS?>'">
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
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");

$query = "select * from HR_STAFF_CONTRACT where CONTRACT_ID='$CONTRACT_ID'";
$cursor = exequery(TD::conn(), $query);
if ($ROW = mysql_fetch_array($cursor)) {
    $CONTRACT_ID = $ROW["CONTRACT_ID"];
    $USER_ID = $ROW["USER_ID"];
    $DEPT_ID = $ROW["DEPT_ID"];
    $STAFF_NAME = $ROW["STAFF_NAME"];
    $STAFF_CONTRACT_NO = $ROW["STAFF_CONTRACT_NO"];
    $CONTRACT_TYPE = $ROW["CONTRACT_TYPE"];
    $CONTRACT_SPECIALIZATION = $ROW["CONTRACT_SPECIALIZATION"];
    $MAKE_CONTRACT = $ROW["MAKE_CONTRACT"];
    $TRAIL_EFFECTIVE_TIME = $ROW["TRAIL_EFFECTIVE_TIME"];
    $PROBATIONARY_PERIOD = $ROW["PROBATIONARY_PERIOD"];
    $TRAIL_OVER_TIME = $ROW["TRAIL_OVER_TIME"];
    $PASS_OR_NOT = $ROW["PASS_OR_NOT"];
    $PROBATION_EFFECTIVE_DATE = $ROW["PROBATION_EFFECTIVE_DATE"];
    $CONTRACT_END_TIME = $ROW["CONTRACT_END_TIME"];
    $REMOVE_OR_NOT = $ROW["REMOVE_OR_NOT"];
    $CONTRACT_REMOVE_TIME = $ROW["CONTRACT_REMOVE_TIME"];
    $STATUS = $ROW["STATUS"];
    $SIGN_TIMES = $ROW["SIGN_TIMES"];
    $REMARK = $ROW["REMARK"];
    $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
    $ADD_TIME = $ROW["ADD_TIME"];
    $REMIND_TIME = $ROW["REMIND_TIME"];
    $REMIND_USER = $ROW["REMIND_USER"];
    $RENEW_TIME = $ROW["RENEW_TIME"];
    $CONTRACT_ENTERPRIES=$ROW["CONTRACT_ENTERPRIES"];
    $IS_TRIAL=$ROW["IS_TRIAL"];
    $IS_RENEW=$ROW["IS_RENEW"];

    $RENEW_TIME1 = trim($RENEW_TIME,"|");
    $A_RENEW_TIME = explode("|",$RENEW_TIME1);
    $COUNT_RENEW_TIME= count($A_RENEW_TIME);
    if($A_RENEW_TIME[0]=="" || $A_RENEW_TIME[0]=="0000-00-00")
    {
        $COUNT_RENEW_TIME=0;
    }
    $STAFF_NAME1 = substr(GetUserNameById($STAFF_NAME), 0, -1);
    $SELECT_FLAG = 0;
    if ($STAFF_NAME1 == "") {
        $SELECT_FLAG = 1;
        $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor1 = exequery(TD::conn(), $query1);
        if ($ROW1 = mysql_fetch_array($cursor1))
            $STAFF_NAME1 = $ROW1["STAFF_NAME"];
    }
    $REMIND_USER_NAME = substr(GetUserNameById($REMIND_USER), 0, -1);
    if($REMIND_USER_NAME != ""){
        $REMIND_USER_NAME=$REMIND_USER_NAME.',';
    }

    if ($TRAIL_EFFECTIVE_TIME == "0000-00-00")
        $TRAIL_EFFECTIVE_TIME = "";
    if ($TRAIL_OVER_TIME == "0000-00-00")
        $TRAIL_OVER_TIME = "";
    if ($CONTRACT_END_TIME == "0000-00-00")
        $CONTRACT_END_TIME = "";
    if ($PROBATION_EFFECTIVE_DATE == "0000-00-00")
        $PROBATION_EFFECTIVE_DATE = "";
    if ($CONTRACT_END_TIME == "0000-00-00")
        $CONTRACT_END_TIME = "";
    if ($CONTRACT_REMOVE_TIME == "0000-00-00")
        $CONTRACT_REMOVE_TIME = "";
    if ($REMIND_TIME == "0000-00-00 00:00:00")
        $REMIND_TIME = "";
    if ($A_RENEW_TIME[$COUNT_RENEW_TIME-1] == "0000-00-00")
        $A_RENEW_TIME[$COUNT_RENEW_TIME-1]="";
    if ($MAKE_CONTRACT == "0000-00-00")
        $MAKE_CONTRACT="";
}
function DiffDate($date1,$date2)
{
    if(strtotime($date1)>strtotime($date2))
    {
        $tmp   = $date2;
        $date2 = $date1;
        $date1 = $tmp;
    }
    list($y1,$m1,$d1)=explode('-',$date1);

    list($y2,$m2,$d2)=explode('-',$date2);

    $y = $y2-$y1;
    $m = $m2-$m1;
    $d = $d2-$d1;

    if($d<0)
    {
        $d+=(int)date('t',strtotime("-1 month $date2"));
        $m--;
    }
    if($m<0)
    {
        $m+=12;
        $y--;
    }
    return array($y, $m, $d);
}


$HTML_PAGE_TITLE = _("合同信息修改");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?= MYOA_STATIC_SERVER ?>/static/theme/<?= $_SESSION["LOGIN_THEME"] ?>/bbs.css" />
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?= MYOA_JS_SERVER ?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script src="<?= MYOA_JS_SERVER ?>/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
String.prototype.trim= function()
{
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
function CheckForm()
{
    var TRAIL_PASS_OR_NOT_VALUE="";
    var REMOVE_OR_NOT_VALUE="";
    var RENEW_OR_NOT_VALUE="";
    var a = document.getElementsByName("TRAIL_PASS_OR_NOT");
    var b = document.getElementsByName("REMOVE_OR_NOT");
    var c = document.getElementsByName("RENEW_OR_NOT");
    for(i=0;i<a.length;i++)
    {
        if(a[i].checked)
            TRAIL_PASS_OR_NOT_VALUE = a[i].value;
    }

    for(i=0;i<b.length;i++)
    {
        if(b[i].checked)
            REMOVE_OR_NOT_VALUE = b[i].value;
    }

    for(i=0;i<c.length;i++)
    {
        if(c[i].checked)
            RENEW_OR_NOT_VALUE = c[i].value;
    }

    if(document.form1.STAFF_NAME.value=="")
    {
        alert("<?=_("雇员姓名不能为空！")?>");
        return false;
    }
    if(document.form1.CONTRACT_ENTERPRIES.value=="")
    {
        alert("<?=_("请选择合同签约公司！")?>");
        return false;
    }
    if(document.form1.CONTRACT_TYPE.value=="")
    {
        alert("<?=_("请选择合同类型！")?>");
        return false;
    }

    if(document.form1.CONTRACT_EFFECTIVE_TIME.value=="")
    {
        alert("<?=_("合同生效日期不能为空！")?>");
        return false;
    }

    if(document.form1.CONTRACT_EFFECTIVE_TIME.value=="")
    {
        alert("<?=_("合同生效日期不能为空！")?>");
        return false;
    }

    if(TRAIL_PASS_OR_NOT_VALUE==1 && document.form1.TRAIL_OVER_TIME.value == "")
    {
        alert("<?=_("请输入试用截止日期！")?>");
        return false;
    }

    if(REMOVE_OR_NOT_VALUE==1 && document.form1.CONTRACT_REMOVE_TIME.value == "")
    {
        alert("<?=_("请输入合同解除日期！")?>");
        return false;
    }
    if(RENEW_OR_NOT_VALUE==1 && document.form1.CONTRACT_RENEW_TIME.value == "")
    {
        alert("<?=_("请输入续签到期日期！")?>");
        return false;
    }

    if(document.form1.CONTRACT_EFFECTIVE_TIME.value!="" && document.form1.MAKE_CONTRACT.value!="" && document.form1.MAKE_CONTRACT.value > document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("合同生效日期不能小于合同签订日期！")?>");
        return false;
    }

    if(TRAIL_PASS_OR_NOT_VALUE==1 && document.form1.TRAIL_OVER_TIME.value!="" && document.form1.CONTRACT_END_DATE.value!="" && document.form1.TRAIL_OVER_TIME.value > document.form1.CONTRACT_END_DATE.value)
    {
        alert("<?=_("试用截止日期不能大于合同终止日期！")?>");
        return false;
    }

    if(TRAIL_PASS_OR_NOT_VALUE==1 && document.form1.TRAIL_OVER_TIME.value!="" && document.form1.TRAIL_OVER_TIME.value < document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("试用截止日期不能小于同生效日期！")?>");
        return false;
    }

    if(document.form1.CONTRACT_END_DATE.value!="" && document.form1.CONTRACT_END_DATE.value < document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("合同终止日期不能小于合同生效日期！")?>");
        return false;
    }

    if(REMOVE_OR_NOT_VALUE==1 && document.form1.CONTRACT_REMOVE_TIME.value!="" && document.form1.CONTRACT_REMOVE_TIME.value < document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("合同解除日期不能小于合同生效日期！")?>");
        return false;
    }

    if(RENEW_OR_NOT_VALUE==1 && document.form1.CONTRACT_RENEW_TIME.value!="" && document.form1.CONTRACT_RENEW_TIME.value < document.form1.CONTRACT_END_DATE.value)
    {
        alert("<?=_("合同续签日期不能小于合同终止日期！")?>");
        return false;
    }

    if(RENEW_OR_NOT_VALUE==1 && document.form1.CONTRACT_RENEW_TIME.value!="" && document.form1.CONTRACT_RENEW_TIME.value < document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("合同续签日期不能小于同生效日期！")?>");
        return false;
    }

    return true;
}
function upload_attach()
{
    if(CheckForm())
    {
        document.form1.submit();
    }
}
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?CONTRACT_ID=<?=$CONTRACT_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}

Date.prototype.format = function(formatter)
{
    if(!formatter || formatter == "")
    {
        formatter = "yyyy-MM-dd";
    }
    var year = this.getYear().toString();
    var month = (this.getMonth() + 1).toString();
    var day = this.getDate().toString();
    var yearMarker = formatter.replace(/[^y|Y]/g,'');
    if(yearMarker.length == 2)
    {
        year = year.substring(2,4);
    }
    var monthMarker = formatter.replace(/[^m|M]/g,'');
    if(monthMarker.length > 1)
    {
        if(month.length == 1)
        {
            month = "0" + month;
        }
    }

    var dayMarker = formatter.replace(/[^d]/g,'');
    if(dayMarker.length > 1)
    {
        if(day.length == 1)
        {
            day = "0" + day;
        }
    }
    return formatter.replace(yearMarker,year).replace(monthMarker,month).replace(dayMarker,day);
}

Date.parseString = function(dateString,formatter)
{
    var today = new Date();
    if(!dateString || dateString == "")
    {
        return today;
    }
    if(!formatter || formatter == "")
    {
        formatter = "yyyy-MM-dd";
    }
    var yearMarker = formatter.replace(/[^y|Y]/g,'');
    var monthMarker = formatter.replace(/[^m|M]/g,'');
    var dayMarker = formatter.replace(/[^d]/g,'');
    var yearPosition = formatter.indexOf(yearMarker);
    var yearLength = yearMarker.length;
    var year =  dateString.substring(yearPosition ,yearPosition + yearLength) * 1;
    if( yearLength == 2)
    {
        if(year < 50 )
        {
            year += 2000;
        }
        else
        {
            year += 1900;
        }
    }
    var monthPosition = formatter.indexOf(monthMarker);
    var month = dateString.substring(monthPosition,monthPosition + monthMarker.length) * 1 - 1;
    var dayPosition = formatter.indexOf(dayMarker);
    var day = dateString.substring( dayPosition,dayPosition + dayMarker.length )* 1;
    return new Date(year,month,day);
}


function DateAdd(strInterval,   NumDay,   dtDate)
{
    var dtTmp   =   new   Date(dtDate);
    if(isNaN(dtTmp))   dtTmp   =   new   Date();
    switch (strInterval)
    {
        case   "s":return   new   Date(Date.parse(dtTmp)   +   (1000   *   NumDay));
        case   "n":return   new   Date(Date.parse(dtTmp)   +   (60000   *   NumDay));
        case   "h":return   new   Date(Date.parse(dtTmp)   +   (3600000   *   NumDay));
        case   "d":return   new   Date(Date.parse(dtTmp)   +   (86400000   *   NumDay));
        case   "w":return   new   Date(Date.parse(dtTmp)   +   ((86400000   *   7)   *   NumDay));
        case   "m":return   new   Date(dtTmp.getFullYear(),   (dtTmp.getMonth())   +   NumDay,   dtTmp.getDate(),   dtTmp.getHours(),   dtTmp.getMinutes(),   dtTmp.getSeconds());
        case   "y":return   new   Date((dtTmp.getFullYear()   +   NumDay),   dtTmp.getMonth(),   dtTmp.getDate(),   dtTmp.getHours(),   dtTmp.getMinutes(),   dtTmp.getSeconds());
    }
}

function calcu_date(day_num)
{
    var d  = new Date.parseString(document.form1.TRAIL_EFFECTIVE_TIME.value);
    document.form1.TRAIL_OVER_TIME.value  =  DateAdd("d",day_num,d).format();
}

function expandIt1()
{

    whichE1 =document.getElementById("id_msg1");
    whichE2 =document.getElementById("id_msg11");
    whichE10 =document.getElementById("hetongzhuanzheng");
    if (document.form1.TRAIL_PASS_OR_NOT[0].checked == true)
    {
        whichE1.style.display = '';
        whichE2.style.display = '';
        whichE10.style.display = '';
    }
    if (document.form1.TRAIL_PASS_OR_NOT[1].checked == true)
    {
        whichE1.style.display = 'none';
        whichE2.style.display = 'none';
        whichE10.style.display = 'none';
    }
}
function expandIt2()
{
    whichE3 =document.getElementById("id_msg2");
    whichE4 =document.getElementById("id_msg21");
    if (document.form1.REMOVE_OR_NOT[0].checked == true)
    {
        whichE3.style.display = '';
        whichE4.style.display = '';
    }
    if (document.form1.REMOVE_OR_NOT[1].checked == true)
    {
        whichE3.style.display = 'none';
        whichE4.style.display = 'none';
    }
}
function expandIt3()
{
    whichE5 =document.getElementById("id_msg3");
    whichE6 =document.getElementById("id_msg31");
    if (document.form1.RENEW_OR_NOT[0].checked == true)
    {
        whichE5.style.display = '';
        whichE6.style.display = '';
    }
    if (document.form1.RENEW_OR_NOT[1].checked == true)
    {
        whichE5.style.display = 'none';
        whichE6.style.display = 'none';
    }
}


function TIME_LIMIT()
{
    which7 =document.getElementById("CONTRACT_SPECIALIZATION").value;
    if(which7=="1")
    {
        document.getElementById("CONTRACT_END_DATE").style.display = '';
        document.getElementById("shifoujiechu").innerHTML="<?=_("合同是否已解除:")?>";
    }
    if(which7=="2")
    {
        document.getElementById("CONTRACT_END_DATE").style.display = 'none';
        document.getElementById("shifoujiechu").innerHTML="<?=_("合同是否已解除:")?>";
    }
    if(which7=="3")
    {
        document.getElementById("CONTRACT_END_DATE").style.display = 'none';
        document.getElementById("shifoujiechu").innerHTML="<?=_("合同是否已终止:")?>";
    }
}

function check_no(str,contract_id)
{
    if(str.trim()=="")
    {
        byname_msg.innerHTML=" ";
        return;
    }else
    {
        _get("check_no.php","STAFF_CONTRACT_NO="+str+"&CONTRACT_ID="+contract_id, show_msg);
    }
}

function TYPE_CHANGE()
{
    which8 =document.getElementById("CONTRACT_TYPE").value;
    if(which8=="1" || which8=="4")
    {
        document.getElementById("shiyong").style.display = '';
    }
    else
    {
        document.getElementById("shiyong").style.display = 'none';
    }
}

function show_msg(req)
{
    if(req.status==200)
    {
        if(req.responseText=="+OK")
        {
            byname_msg.innerHTML=" ";
            return true;
        }
        else
        {
            //alert("<?=_("合同编号重复")?>");
            byname_msg.innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("合同编号重复")?>";
            document.form1.STAFF_CONTRACT_NO.focus();
        }
    }
}
</script>


<body class="bodycolor" topmargin="5">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?= _("编辑合同信息") ?></span>&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td><?= _("说明：1、合同试用日期应小于合同终止日期；2、合同解除日期应大于合同终止日期；3、合同续签日期应大于合同续签日期；4、修改最后一次续签日期，点击合同续签历史信息中的“续签日期修改”进行修改就行，不需保存；5、如需合同续签，需要调整合同续签日期，但需保存。") ?></td>
    </tr>
</table>
<br>
<!--    <div>说明：</div>-->
<form enctype="multipart/form-data" action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><span style="color: red;">*</span><?= _("雇员：") ?></td>
            <td class="TableData">
                <input type="text" name="STAFF_NAME1" size="12" class="BigStatic" readonly value="<?= $STAFF_NAME1 ?>">&nbsp;
                <input type="hidden" name="STAFF_NAME" value="<?= $STAFF_NAME ?>">
            </td>
            <td nowrap class="TableData"><?= _("合同编号：") ?></td>
            <td class="TableData" >
                <INPUT type="text"name="STAFF_CONTRACT_NO" class=BigInput size="11" value="<?= $STAFF_CONTRACT_NO ?>" onBlur="check_no(this.value,<?=$CONTRACT_ID?>)">&nbsp;<span id="byname_msg"></span>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"><span style="color: red;">*</span><?= _("合同签约公司：") ?></td>
            <td class="TableData">
                <select name="CONTRACT_ENTERPRIES" style="background: white;" title="<?= _("合同签约公司可在“人力资源设置”->“HRMS代码设置”模块设置。") ?>">
                    <option value=""><?= _("请选择合同签约公司") ?>&nbsp;&nbsp;</option>
                    <?= hrms_code_list("HR_ENTERPRISE", $CONTRACT_ENTERPRIES) ?>
                </select>
            </td>
            <td nowrap class="TableData"><span style="color: red;">*</span><?= _("合同类型：") ?></td>
            <td class="TableData">
                <select name="CONTRACT_TYPE" style="background: white;" id="CONTRACT_TYPE" onChange="TYPE_CHANGE()"  title="<?= _("合同类型可在“人力资源设置”->“HRMS代码设置”模块设置。") ?>">
                    <option value=""><?= _("合同类型") ?>&nbsp;&nbsp;</option>
                    <?= hrms_code_list("HR_STAFF_CONTRACT1", $CONTRACT_TYPE) ?>
                </select>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"><?= _("合同期限属性：") ?></td>
            <td class="TableData">
                <select name="CONTRACT_SPECIALIZATION" id="CONTRACT_SPECIALIZATION" onChange="TIME_LIMIT()">
                    <option value="1" <?if($CONTRACT_SPECIALIZATION=="1") echo "selected";?>><?=_("固定期限")?></option>
                    <option value="2" <?if($CONTRACT_SPECIALIZATION=="2") echo "selected";?>><?=_("无固定期限")?></option>
                    <option value="3" <?if($CONTRACT_SPECIALIZATION=="3") echo "selected";?>><?=_("以完成一定工作任务为期限")?></option>
                </select>
            </td>
            <td nowrap class="TableData"><?=_("角色：")?></td>
            <td nowrap class="TableData">
                <select name="role" style="background: white;" title="<?=_("角色可在“系统管理”->“组织机构设置”->“角色与权限管理”模块设置。")?>">
                    <option value="">请选择角色</option>
                    <?
                    $query = "SELECT USER_PRIV from user where USER_ID='".$STAFF_NAME."' ;";
                    $cursor= exequery(TD::conn(),$query);
                    if($ROW=mysql_fetch_array($cursor))
                    {
                        $CHECK_NUMBER=$ROW["USER_PRIV"];
                    }
                    $query = "SELECT USER_PRIV,PRIV_NAME from  user_priv;";
                    $cursor= exequery(TD::conn(),$query);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                        if($_SESSION["LOGIN_USER_PRIV"]=="1")
                        {
                            ?>
                            <option value=<?=$ROW["USER_PRIV"]?> <?if($ROW["USER_PRIV"]==$CHECK_NUMBER) echo "selected"?>><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>
                            <?
                        }
                        else
                        {
                            $query2 = "SELECT * from  hr_role_manage WHERE FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',HR_ROLE_MANAGE);";
                            $cursor2= exequery(TD::conn(),$query2);
                            while($ROW2=mysql_fetch_array($cursor2))
                            {
                                $NEW_NAME_ARRAY=  explode(',', $ROW2["HR_USER_PRIV"]);
                                if(in_array($ROW["USER_PRIV"],$NEW_NAME_ARRAY))
                                {
                                    $NEW_NAME="USER_P".$ROW["USER_PRIV"];
                                    $$NEW_NAME=1;
                                }
                            }
                            ?>
                            <option value=<?=$ROW["USER_PRIV"]?> class="<?$NEW_NAME="USER_P".$ROW["USER_PRIV"]; if($$NEW_NAME != 1) echo _("xinxiyinchneg") ?>" <?if($ROW["USER_PRIV"]==$CHECK_NUMBER) echo "selected"?>><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>;
                            <?
                        }
                    }
                    ?>
                </select>
                <span style="font-size: 12px;color: #666666;">(不选择角色，用户将保持原始角色)</span>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"><?= _("合同签订日期：") ?></td>
            <td class="TableData">
                <input type="text" id="start_time1" name="MAKE_CONTRACT" size="12" maxlength="10" class="BigInput" value="<?= $MAKE_CONTRACT ?>" onClick="WdatePicker()"/>
            </td>

            <td nowrap class="TableData"><span style="color: red;">*</span><span><?= _("合同生效日期：") ?></span></td>
            <td class="TableData">
                    <span >
                        <input type="text" id="start_time2" name="CONTRACT_EFFECTIVE_TIME" size="12" maxlength="10" class="BigInput" value="<?= $PROBATION_EFFECTIVE_DATE ?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time1\')}'})"/>
                    </span>
            </td>
        </tr>

        <tr id="CONTRACT_END_DATE">
            <td nowrap class="TableData" ><span ><?= _("合同终止日期：") ?></span> </td>
            <td class="TableData">
                    <span>
                        <input type="text" name="CONTRACT_END_DATE" size="12" maxlength="10" class="BigInput" value="<?= $CONTRACT_END_TIME ?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time2\')}'})"/>
                    </span>
            </td>
            <td nowrap class="TableData" ></td>
            <td nowrap class="TableData" ></td>
        </tr>

        <tr  id="shiyong">
            <td nowrap class="TableData"><?= _("是否含试用期：") ?></td>
            <td class="TableData">
                <INPUT type="radio" name="TRAIL_PASS_OR_NOT" value="1" onClick="expandIt1()" <? if ($IS_TRIAL == "1" || $TRAIL_OVER_TIME != "0000-00-00") echo "checked"; ?>> <?= _("是") ?>&nbsp;&nbsp;
                <INPUT type="radio" name="TRAIL_PASS_OR_NOT" value="0" onClick="expandIt1()" <? if ($IS_TRIAL == "0" || $TRAIL_OVER_TIME == "0000-00-00") echo "checked"; ?>> <?= _("否") ?>
            </td>
            <td nowrap class="TableData"><span id="id_msg1" style="display:<? if ($IS_TRIAL == "0") echo "none"; ?>"><?= _("试用截止日期：") ?></span></td>
            <td class="TableData">
                    <span id="id_msg11" style="display:<? if ($IS_TRIAL == "0") echo "none"; ?>">
                        <input type="text" name="TRAIL_OVER_TIME" size="12" maxlength="10" class="BigInput" value="<?= $TRAIL_OVER_TIME ?>" onClick="WdatePicker()"/>
                    </span>
            </td>
        </tr>

        <tr  style="display:<? if ($IS_TRIAL == "0") echo "none"; ?>" id="hetongzhuanzheng">
            <td nowrap class="TableData" ><?= _("雇员是否转正：") ?></td>
            <td class="TableData" >
                <INPUT type="radio" name="PASS_OR_NOT" onClick="expandIt()" value="1" <? if ($PASS_OR_NOT == "1") echo "checked"; ?>> <?= _("是") ?>&nbsp;&nbsp;
                <INPUT type="radio" name="PASS_OR_NOT" onClick="expandIt()" value="0" <? if ($PASS_OR_NOT == "0") echo "checked"; ?>> <?= _("否") ?>

            </td>
            </td>
            <td nowrap class="TableData">
            </td>
            <td nowrap class="TableData">
            </td>
        </tr>

        <tr id="hetongjiechu">
            <td nowrap class="TableData" id="shifoujiechu"><?= _("合同是否已解除：") ?></td>
            <td class="TableData">
                <INPUT type="radio" name="REMOVE_OR_NOT" value="1" <? if ($REMOVE_OR_NOT == "1" ) echo "checked"; ?> onClick="expandIt2()"> <?= _("是") ?>&nbsp;&nbsp;
                <INPUT type="radio" name="REMOVE_OR_NOT" value="0" <? if ($REMOVE_OR_NOT == "0") echo "checked"; ?> onClick="expandIt2()"> <?= _("否") ?>
            </td>

            <td nowrap class="TableData"><span id="id_msg2" style="display:<? if ($REMOVE_OR_NOT == "0") echo "none"; ?>"><?= _("合同解除日期：") ?></span></td>
            <td class="TableData">
                    <span id="id_msg21" style="display:<? if ($REMOVE_OR_NOT == "0") echo "none"; ?>">
                        <input type="text" name="CONTRACT_REMOVE_TIME" size="12" maxlength="10" class="BigInput" value="<?= $CONTRACT_REMOVE_TIME ?>" onClick="WdatePicker()"/>
                    </span>
            </td>
        </tr>

        <tr id="hetongxuqian">
            <td nowrap class="TableData"><?= _("合同是否续签：") ?></td>
            <td class="TableData">
                <INPUT type="radio" name="RENEW_OR_NOT" value="1" <? if ($IS_RENEW == "1" || $A_RENEW_TIME[$COUNT_RENEW_TIME-1] != "0000-00-00") echo "checked"; ?> onClick="expandIt3()"> <?= _("是") ?>&nbsp;&nbsp;
                <INPUT type="radio" name="RENEW_OR_NOT" value="0" <? if ($IS_RENEW == "0" || $A_RENEW_TIME[$COUNT_RENEW_TIME-1] == "0000-00-00") echo "checked"; ?> onClick="expandIt3()"> <?= _("否") ?>
            </td>
            <td nowrap class="TableData"><span id="id_msg3" style="display:<? if ($IS_RENEW == "0" || $A_RENEW_TIME[$COUNT_RENEW_TIME-1] == "0000-00-00") echo "none"; ?>"><?= _("续签到期日期：") ?></span></td>
            <td class="TableData">
                    <span id="id_msg31" style="display:<? if ($IS_RENEW == "0" || $A_RENEW_TIME[$COUNT_RENEW_TIME-1] == "0000-00-00") echo "none"; ?>">

                        <input type="text" name="CONTRACT_RENEW_TIME" id="CONTRACT_RENEW_TIME" size="12" maxlength="10" class="BigInput" value="<?= $A_RENEW_TIME[$COUNT_RENEW_TIME-1] ?>" onClick="WdatePicker()"/>
                    </span>
            </td>
        </tr>
        <?
        if($COUNT_RENEW_TIME!=0)
        {
            ?>
            <tr>
            <td nowrap class="TableData" rowspan="<?=($COUNT_RENEW_TIME+1)?>"><?= _("合同续签历史信息：</br>（可以修改最后一次续签的日期，</br>无需点击页面下面的保存。</br>如需合同续签，调整续签到期日期，</br>然后点击页面下面的保存）") ?></td>
            <?
            if($RENEW_TIME != "0000-00-00" && $RENEW_TIME !="")
            {
                for($i=0;$i<$COUNT_RENEW_TIME;$i++)
                {
                    if($i==($COUNT_RENEW_TIME-1))
                    {
                        $time1 = $A_RENEW_TIME[$i];
                        if($i==0)
                        {
                            $time2 = $CONTRACT_END_TIME;
                        }
                        else
                        {
                            $time2 = $A_RENEW_TIME[$i-1];
                        }

                        $thistime = DiffDate($time1,$time2);
                        $start = 0;
                        $string = _("合同续签期限：");
                        $string .= $thistime[0]._("年");
                        $string .= $thistime[1]._("月");
                        $string .= abs($thistime[2])._("天");
                        ?>
                        <tr id="lastinfo">
                            <td nowrap class="TableData"><?= _("第".($i+1)."次续签日期：") ?></td>
                            <?
                            if($i==0 && $CONTRACT_END_TIME=="")
                            {
                                ?>
                                <td class="TableData" id="neirongtiaozheng" hetongid="<?=$CONTRACT_ID?>" name="<?=$A_RENEW_TIME[$i]?>" colspan="2"><? echo _("续签到期日期：").$A_RENEW_TIME[$i]."&nbsp;&nbsp;&nbsp;"; ?><a href="javascript:void(0);" onClick="XUQIAN()"><?= _("续签日期修改") ?></a></td>
                                <?
                            }
                            else
                            {
                                ?>
                                <td class="TableData" id="neirongtiaozheng" hetongid="<?=$CONTRACT_ID?>" name="<?=$A_RENEW_TIME[$i]?>" colspan="2"><? echo $string."&nbsp;&nbsp;&nbsp;".$time2._("&nbsp;到&nbsp;").$A_RENEW_TIME[$i]."&nbsp;&nbsp;&nbsp;"; ?><a href="javascript:void(0);" onClick="XUQIAN()"><?= _("续签日期修改") ?></a></td>
                                <?
                            }
                            ?>

                        </tr>
                        <?
                    }
                    else
                    {
                        $time1 = $A_RENEW_TIME[$i];
                        if($i==0)
                        {
                            $time2 = $CONTRACT_END_TIME;
                        }
                        else
                        {
                            $time2 = $A_RENEW_TIME[$i-1];
                        }
                        $thistime = DiffDate($time1,$time2);
                        $start = 0;
                        $string = _("合同续签期限：");
                        $string .= $thistime[0]._("年");
                        $string .= $thistime[1]._("月");
                        $string .= abs($thistime[2])._("天");
                        ?>
                        <tr>
                            <td nowrap class="TableData"><?= _("第".($i+1)."次续签日期：") ?></td>
                            <?
                            if($i==0 && $CONTRACT_END_TIME=="")
                            {
                                ?>
                                <td class="TableData" colspan="2"><? echo _("合同续签到期日期：").$A_RENEW_TIME[$i]; ?></td>
                                <?
                            }
                            else
                            {
                                ?>
                                <td class="TableData" colspan="2"><? echo $string."&nbsp;&nbsp;&nbsp;".$time2._("&nbsp;到&nbsp;").$A_RENEW_TIME[$i]; ?></td>
                                <?
                            }
                            ?>
                        </tr>
                        <?
                    }
                }
            }
        }
        ?>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("提醒方式：")?></td>
            <td class="TableData" colspan=3><?=sms_remind(63);?></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?= _("提醒人员：") ?></td>
            <td class="TableData" colspan=3>
                <input type="hidden" name="TO_ID" id="TO_ID" value="<?= $REMIND_USER ?>">
                <textarea name="TO_NAME" id="TO_NAME" cols="75" rows="3"  class="SmallStatic" wrap="yes" readonly><?= $REMIND_USER_NAME ?></textarea>
                <a href="#" class="orgAdd" onClick="SelectUser('27','','TO_ID', 'TO_NAME')" title="<?= _("添加提醒人员") ?>"><?= _("添加") ?></a>
                <a href="#" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')" title="<?= _("清空提醒人员") ?>"><?= _("清空") ?></a>
                <span style="margin-left: 10px; color: #999999;">（除提醒以上人员外，还将提醒本人和人力资源管理员）</span>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?= _("备注：") ?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""><?= $REMARK ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="TableData" colspan=4>
                <?
                echo get_field_table(get_field_html("CONTRACT",$CONTRACT_ID));
                ?>
            </td>
        </tr>
        <tr class="TableData" id="attachment2">
            <td nowrap><?= _("附件文档：") ?></td>
            <td nowrap colspan=3>
                <?
                if ($ATTACHMENT_ID == "")
                    echo _("无附件");
                else
                    echo attach_link($ATTACHMENT_ID, $ATTACHMENT_NAME, 1, 1, 1, 1, 1, 1, 1, 1);
                ?>
            </td>
        </tr>
        <tr height="25" id="attachment1">
            <td nowrap class="TableData"><span id="ATTACH_LABEL"><?= _("附件上传：") ?></span></td>
            <td class="TableData" colspan=3>
                <script>ShowAddFile();</script>
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?= $ATTACHMENT_ID ?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?= $ATTACHMENT_NAME ?>">
            </td>
        </tr>

        <tr align="center" class="TableControl">
            <td colspan=4 nowrap>
                <input type="hidden" value="<?=$CONTRACT_ID?>" name="CONTRACT_ID">
                <input type="submit" value="<?=_("保存")?>" class="BigButton">
                <?
                if($xianshiinfo=="fou")
                {
                    ?>
                    <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
                    <?
                }
                else
                {
                    ?>
                    <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="TJF_window_close();" title="<?=_("关闭窗口")?>">
                    <?
                }
                ?>
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $(".xinxiyinchneg").remove();

});
function XUQIAN()
{
    var zhi = $("#neirongtiaozheng").attr("name");
    var htmlinfo='<input type="text" id="xinriqi" size="12" maxlength="10" class="BigInput" value="'+zhi+'" onclick="WdatePicker()">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("保存")?>" class="BigButtonA" onclick="CHUANZHI()">';
    $("#neirongtiaozheng").html(htmlinfo);
}
function CHUANZHI()
{
    var chuanzhi = $("#xinriqi").attr("value");
    var hetongid = $("#neirongtiaozheng").attr("hetongid");
    $.post("change_renew.php",
        {
            chuanzhi:chuanzhi,
            hetongid:hetongid
        },
        function(data){
            if(data=="<?=_("续签日期不能小于上次续签日期或者小于合同终止日期")?>")
            {
                alert(data);
            }
            else if (data=="<?=_("请输入续签日期")?>")
            {
                alert(data);
            }
            else if (data=="<?=_("续签日期不能等于上次续签日期")?>")
            {
                alert(data);
            }
            else
            {
                $("#CONTRACT_RENEW_TIME").val(chuanzhi);
                $("#lastinfo").html(data);
            }
        })
}
</script>
</body>
</html>
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�½���ͬ��Ϣ");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
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
        alert("<?=_("��Ա��������Ϊ�գ�")?>");
        return (false);
    }
    if(document.form1.CONTRACT_ENTERPRIES.value=="")
    {
        alert("<?=_("��ѡ���ͬǩԼ��˾��")?>");
        return (false);
    }
    if(document.form1.CONTRACT_TYPE.value=="")
    {
        alert("<?=_("��ѡ���ͬ���ͣ�")?>");
        return (false);
    }

    if(document.form1.CONTRACT_EFFECTIVE_TIME.value=="")
    {
        alert("<?=_("��ͬ��Ч���ڲ���Ϊ�գ�")?>");
        return (false);
    }
    if(TRAIL_PASS_OR_NOT_VALUE==1 && document.form1.TRAIL_OVER_TIME.value == "")
    {
        alert("<?=_("���������ý�ֹ���ڣ�")?>");
        return (false);
    }

    if(REMOVE_OR_NOT_VALUE==1 && document.form1.CONTRACT_REMOVE_TIME.value == "")
    {
        alert("<?=_("�������ͬ������ڣ�")?>");
        return (false);
    }
    if(RENEW_OR_NOT_VALUE==1 && document.form1.CONTRACT_RENEW_TIME.value == "")
    {
        alert("<?=_("��������ǩ�������ڣ�")?>");
        return (false);
    }

    if(document.form1.CONTRACT_EFFECTIVE_TIME.value!="" && document.form1.MAKE_CONTRACT.value!="" && document.form1.MAKE_CONTRACT.value > document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("��ͬ��Ч���ڲ���С�ں�ͬǩ�����ڣ�")?>");
        return (false);
    }

    if(TRAIL_PASS_OR_NOT_VALUE==1 && document.form1.TRAIL_OVER_TIME.value!="" && document.form1.CONTRACT_END_DATE.value!="" && document.form1.TRAIL_OVER_TIME.value > document.form1.CONTRACT_END_DATE.value)
    {
        alert("<?=_("���ý�ֹ���ڲ��ܴ��ں�ͬ��ֹ���ڣ�")?>");
        return (false);
    }

    if(TRAIL_PASS_OR_NOT_VALUE==1 && document.form1.TRAIL_OVER_TIME.value!="" && document.form1.TRAIL_OVER_TIME.value < document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("���ý�ֹ���ڲ���С��ͬ��Ч���ڣ�")?>");
        return (false);
    }

    if(document.form1.CONTRACT_END_DATE.value!="" && document.form1.CONTRACT_END_DATE.value < document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("��ͬ��ֹ���ڲ���С�ں�ͬ��Ч���ڣ�")?>");
        return (false);
    }

    if(REMOVE_OR_NOT_VALUE==1 && document.form1.CONTRACT_REMOVE_TIME.value!="" && document.form1.CONTRACT_REMOVE_TIME.value < document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("��ͬ������ڲ���С�ں�ͬ��Ч���ڣ�")?>");
        return (false);
    }

    if(RENEW_OR_NOT_VALUE==1 && document.form1.CONTRACT_RENEW_TIME.value!="" && document.form1.CONTRACT_RENEW_TIME.value < document.form1.CONTRACT_END_DATE.value)
    {
        alert("<?=_("��ͬ��ǩ���ڲ���С�ں�ͬ��ֹ���ڣ�")?>");
        return (false);
    }

    if(RENEW_OR_NOT_VALUE==1 && document.form1.CONTRACT_RENEW_TIME.value!="" && document.form1.CONTRACT_RENEW_TIME.value < document.form1.CONTRACT_EFFECTIVE_TIME.value)
    {
        alert("<?=_("��ͬ��ǩ���ڲ���С��ͬ��Ч���ڣ�")?>");
        return (false);
    }

    return (true);
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
    var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
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
        document.getElementById("shifoujiechu").innerHTML="<?=_("��ͬ�Ƿ��ѽ��:")?>";
    }
    if(which7=="2")
    {
        document.getElementById("CONTRACT_END_DATE").style.display = 'none';
        document.getElementById("shifoujiechu").innerHTML="<?=_("��ͬ�Ƿ��ѽ��:")?>";
    }
    if(which7=="3")
    {
        document.getElementById("CONTRACT_END_DATE").style.display = 'none';
        document.getElementById("shifoujiechu").innerHTML="<?=_("��ͬ�Ƿ�����ֹ:")?>";
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

function check_no(str)
{
    if(str!="")
        _get("check_no.php","STAFF_CONTRACT_NO="+str, show_msg);
    else
        return;
}

function show_msg(req)
{
    if(req.status==200)
    {
        if(req.responseText=="+OK")
            return (true);
        else
        {
            alert("<?=_("��ͬ����ظ�")?>");
            document.form1.STAFF_CONTRACT_NO.focus();
        }
    }
}
</script>

<body class="bodycolor" topmargin="5">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td>
            <img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½���ͬ��Ϣ")?></span>&nbsp;&nbsp;
        </td>
    </tr>
    <tr>
        <td><?= _("˵����1����ͬ��������ӦС�ں�ͬ��ֹ���ڣ�2����ͬ�������Ӧ���ں�ͬ��ֹ���ڣ�3����ͬ��ǩ����Ӧ���ں�ͬ��ǩ���ڡ�") ?></td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="contract_add.php"  method="post" name="form1" onSubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><span style="color: red;">*</span><?=_("��Ա��")?></td>
            <td class="TableData">
                <input type="text" name="STAFF_NAME1" size="12" class="BigStatic" readonly value="<?=$STAFF_NAME1?>">&nbsp;
                <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("ѡ��")?></a>
            </td>
            <td nowrap class="TableData"><?=_("��ͬ��ţ�")?></td>
            <td class="TableData" >
                <INPUT type="text"  name="STAFF_CONTRACT_NO" class=BigInput size="11" value="<?=$STAFF_CONTRACT_NO?>" onBlur="check_no(this.value)" />
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"><span style="color: red;">*</span><?=_("��ͬǩԼ��˾��")?></td>
            <td class="TableData">
                <select name="CONTRACT_ENTERPRIES" style="background: white;" title="<?=_("��ͬǩԼ��˾���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                    <option value=""><?=_("��ѡ���ͬǩԼ��˾")?>&nbsp;&nbsp;</option>
                    <?=hrms_code_list("HR_ENTERPRISE","")?>
                </select>
            </td>
            <td nowrap class="TableData"><span style="color: red;">*</span><?=_("��ͬ���ͣ�")?></td>
            <td class="TableData">
                <select name="CONTRACT_TYPE" style="background: white;" id="CONTRACT_TYPE" onchange="TYPE_CHANGE()" title="<?=_("��ͬ���Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                    <option value=""><?=_("��ͬ����")?>&nbsp;&nbsp;</option>
                    <?=hrms_code_list("HR_STAFF_CONTRACT1","")?>
                </select>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"><?=_("��ͬ�������ԣ�")?></td>
            <td class="TableData">
                <select name="CONTRACT_SPECIALIZATION" id="CONTRACT_SPECIALIZATION" onchange="TIME_LIMIT()">
                    <option value="1"><?=_("�̶�����")?></option>
                    <option value="2"><?=_("�޹̶�����")?></option>
                    <option value="3"><?=_("�����һ����������Ϊ����")?></option>
                </select>
            </td>
            <td nowrap class="TableData"><?=_("��ɫ��")?></td>
            <td nowrap class="TableData">
                <select name="role" style="background: white;"title="<?=_("��ɫ���ڡ�ϵͳ����->����֯�������á�->����ɫ��Ȩ�޹���ģ�����á�")?>">
                    <option value="">��ѡ���ɫ</option>
                    <?
                    $query = "SELECT USER_PRIV,PRIV_NAME from  user_priv;";
                    $cursor= exequery(TD::conn(),$query, true);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                        if($_SESSION["LOGIN_USER_PRIV"]=="1")
                        {

                            ?>
                            <option value=<?=$ROW["USER_PRIV"]?>><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>
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
                            <option value=<?=$ROW["USER_PRIV"]?> class="<?$NEW_NAME="USER_P".$ROW["USER_PRIV"]; if($$NEW_NAME != 1) echo _("xinxiyinchneg") ?>"><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>
                            <?
                        }
                    }
                    ?>
                </select>
                <span style="font-size: 12px;color: #666666;">(��ѡ���ɫ���û�������ԭʼ��ɫ)</span>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"><?=_("��ͬǩ�����ڣ�")?></td>
            <td class="TableData">
                <input type="text" name="MAKE_CONTRACT" size="12" maxlength="10" class="BigInput" value="<?=$MAKE_CONTRACT?>" onClick="WdatePicker()"/>
            </td>

            <td nowrap class="TableData"><span><span style="color: red;">*</span><?=_("��ͬ��Ч���ڣ�")?></span></td>
            <td class="TableData">
      	<span >
         <input type="text" name="CONTRACT_EFFECTIVE_TIME" size="12" maxlength="10" class="BigInput" value="<?=$TRAIL_EFFECTIVE_TIME?>" onClick="WdatePicker()"/>
        </span>
            </td>
        </tr>

        <tr id="CONTRACT_END_DATE">
            <td nowrap class="TableData" ><span ><?=_("��ͬ��ֹ���ڣ�")?></span> </td>
            <td class="TableData">
            <span>
             <input type="text" name="CONTRACT_END_DATE" size="12" maxlength="10" class="BigInput" value="<?=$PROBATION_END_DATE?>" onClick="WdatePicker()"/>
            </span>
            </td>
            <td nowrap class="TableData"></td>
            <td nowrap class="TableData"></td>
        </tr>
        <tr id="shiyong">
            <td nowrap class="TableData"><?=_("�Ƿ������ڣ�")?></td>
            <td class="TableData">
                <INPUT type="radio" name="TRAIL_PASS_OR_NOT" value="1" onClick="expandIt1()"> <?=_("��")?>&nbsp;&nbsp;
                <INPUT type="radio" name="TRAIL_PASS_OR_NOT" value="0" onClick="expandIt1()" checked> <?=_("��")?>
            </td>
            <td nowrap class="TableData"><span id="id_msg1" style="display:none"><?=_("���ý�ֹ���ڣ�")?></span></td>
            <td class="TableData">
              <span id="id_msg11" style="display:none">
                <input type="text" name="TRAIL_OVER_TIME" size="12" maxlength="10" class="BigInput" value="<?=$TRAIL_OVER_TIME?>" onClick="WdatePicker()"/>
              </span>
            </td>
        </tr>

        <tr  style="display: none" id="hetongzhuanzheng">
            <td nowrap class="TableData" ><?=_("��Ա�Ƿ�ת����")?></td>
            <td class="TableData" >
                <INPUT type="radio" name="PASS_OR_NOT" value="1" > <?=_("��")?>&nbsp;&nbsp;
                <INPUT type="radio" name="PASS_OR_NOT" value="0" checked> <?=_("��")?>
            </td>
            </td>
            <td nowrap class="TableData">
            </td>
            <td nowrap class="TableData">
            </td>
        </tr>

        <tr id="hetongjiechu">
            <td nowrap class="TableData" id="shifoujiechu"><?=_("��ͬ�Ƿ��ѽ����")?></td>
            <td class="TableData">
                <INPUT type="radio" name="REMOVE_OR_NOT" value="1" onClick="expandIt2()"> <?=_("��")?>&nbsp;&nbsp;
                <INPUT type="radio" name="REMOVE_OR_NOT" value="0" onClick="expandIt2()" checked> <?=_("��")?>
            </td>

            <td nowrap class="TableData"><span id="id_msg2" style="display:none"><?=_("��ͬ������ڣ�")?></span></td>
            <td class="TableData">
      <span id="id_msg21" style="display:none">
       <input type="text" name="CONTRACT_REMOVE_TIME" size="12" maxlength="10" class="BigInput" value="<?=$CONTRACT_REMOVE_TIME?>" onClick="WdatePicker()"/>
      </span>
            </td>
        </tr>

        <tr id="hetongxuqian">
            <td nowrap class="TableData"><?=_("��ͬ�Ƿ���ǩ��")?></td>
            <td class="TableData">
                <INPUT type="radio" name="RENEW_OR_NOT" value="1" onClick="expandIt3()"> <?=_("��")?>&nbsp;&nbsp;
                <INPUT type="radio" name="RENEW_OR_NOT" value="0" onClick="expandIt3()" checked> <?=_("��")?>
            </td>
            <td nowrap class="TableData"><span id="id_msg3" style="display:none"><?=_("��ǩ�������ڣ�")?></span></td>
            <td class="TableData">
      <span id="id_msg31" style="display:none">
       <input type="text" name="CONTRACT_RENEW_TIME" size="12" maxlength="10" class="BigInput" value="<?=$CONTRACT_REMOVE_TIME?>" onClick="WdatePicker()"/>
      </span>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("���ѷ�ʽ��")?></td>
            <td class="TableData" colspan=3>
                <?=sms_remind(63);?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("������Ա��")?></td>
            <td class="TableData" colspan=3>
                <input type="hidden" name="TO_ID" id="TO_ID">
                <textarea name="TO_NAME" id="TO_NAME" cols="75" rows="3"  class="SmallStatic" wrap="yes" readonly></textarea>
                <a href="#" class="orgAdd" onClick="SelectUser('27','','TO_ID', 'TO_NAME')" title="<?=_("���������Ա")?>"><?=_("���")?></a>
                <a href="#" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')" title="<?=_("���������Ա")?>"><?=_("���")?></a>
                <span style="margin-left: 10px; color: #999999;">��������������Ա�⣬�������ѱ��˺�������Դ����Ա��</span>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ע��")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
            </td>
        </tr>
        <tr class="TableData" id="attachment2" style="display: none;">
            <td nowrap><?=_("�����ĵ���")?></td>
            <td nowrap colspan=3>
                <?
                if($ATTACHMENT_ID=="")
                    echo _("�޸���");
                else
                    echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
                ?>
            </td>
        </tr>
        <tr height="25" id="attachment1">
            <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("�����ϴ���")?></span></td>
            <td class="TableData" colspan=3>
                <script>ShowAddFile();</script>
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
            </td>
        </tr>

        <tr align="center" class="TableControl">
            <td colspan=4 nowrap>
                <input type="submit" value="<?=_("����")?>" class="BigButton">
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $(".xinxiyinchneg").remove();
});
</script>
</body>
</html>
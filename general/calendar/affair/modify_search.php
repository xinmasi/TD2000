<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");

$query="select * from AFFAIR where AFF_ID='$AFF_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $BEGIN_TIME=$ROW["BEGIN_TIME"];
    $BEGIN_TIME=date("Y-m-d",$BEGIN_TIME);
    $BEGIN_TIME_TIME=$ROW["BEGIN_TIME_TIME"];
    $END_TIME=$ROW["END_TIME"];
    if($END_TIME!=0)
        $END_TIME=date("Y-m-d",$END_TIME);
    $END_TIME_TIME=$ROW["END_TIME_TIME"];
    $USER_ID=$ROW["USER_ID"];
    $TYPE=$ROW["TYPE"];
    $REMIND_DATE=$ROW["REMIND_DATE"];
    $REMIND_TIME=$ROW["REMIND_TIME"];
    $CONTENT=$ROW["CONTENT"];
    $SMS2_REMIND=$ROW["SMS2_REMIND"];
    $CAL_TYPE=$ROW["CAL_TYPE"];
    $TAKER=$ROW["TAKER"];
    $TAKER_NAME=GetUserInfoByUID(UserId2Uid($TAKER),"USER_NAME");
    if($USER_ID!=$_SESSION["LOGIN_USER_ID"])
        exit;

    $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
    if($TYPE=="5")
    {
        $REMIND_ARR=explode("-",$REMIND_DATE);
        $REMIND_DATE_MON=$REMIND_ARR[0];
        $REMIND_DATE_DAY=$REMIND_ARR[1];
    }
}

$HTML_PAGE_TITLE = _("修改周期性事务");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.CONTENT.value=="")
    {
        alert("<?=_("事务内容不能为空！")?>");
        return (false);
    }
    return (true);
}

<?
if($TYPE=="2")
    echo "var aff_type=\"day\";\n";
if($TYPE=="3")
    echo "var aff_type=\"week\";\n";
if($TYPE=="4")
    echo "var aff_type=\"mon\";\n";
if($TYPE=="5")
    echo "var aff_type=\"year\";\n";
?>
function sel_change()
{
    if(aff_type!="")
        document.getElementById(aff_type).style.display="none";
    if(form1.TYPE.value=="2")
        aff_type="day";
    if(form1.TYPE.value=="3")
        aff_type="week";
    if(form1.TYPE.value=="4")
        aff_type="mon";
    if(form1.TYPE.value=="5")
        aff_type="year";
    document.getElementById(aff_type).style.display="";
}

function td_clock1(fieldname)
{
    document.form1.REMIND_TIME2.value="";
    document.form1.REMIND_TIME3.value="";
    document.form1.REMIND_TIME4.value="";
    document.form1.REMIND_TIME5.value="";
    myleft=document.body.scrollLeft+event.clientX-event.offsetX-80;
    mytop=document.body.scrollTop+event.clientY-event.offsetY+140;

    window.showModalDialog("/inc/clock.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:120px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
}
</script>

<?
$CUR_DATE_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("H:i:s",time());
?>
<body class="bodycolor" onLoad="document.form1.CONTENT.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><span class="big3"> <?=_("修改周期性事务")?></span>
        </td>
    </tr>
</table>

<br>
<table class="" style="width:500px; margin:0 auto;">
    <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
        <tr>
            <td nowrap ><?=_("起始日期：")?></td>
            <td>
                <INPUT type="text"name="BEGIN_TIME" size="20" value="<?=$BEGIN_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">

                &nbsp;&nbsp;<?=_("为空为当前时间")?>
            </td>
        </tr>
        <tr>
            <td nowrap><?=_("结束日期：")?></td>
            <td>
                <INPUT type="text"name="END_TIME"  size="20" value="<?=$END_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                &nbsp;&nbsp;<?=_("为空则不结束")?>
            </td>
        </tr>
        <tr>
            <td nowrap><?=_("开始时间：")?></td>
            <td>
                <INPUT type="text"name="BEGIN_TIME_TIME" class="input-small"  size="15" value="<?=$BEGIN_TIME_TIME?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})" >

                &nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td nowrap><?=_("结束时间：")?></td>
            <td>
                <INPUT type="text"name="END_TIME_TIME"  class="input-small" size="15" value="<?=$END_TIME_TIME?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})">

                &nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td nowrap> <?=_("事务类型：")?></td>
            <td >
                <select name="CAL_TYPE" >
                    <?=code_list("CAL_TYPE",$CAL_TYPE)?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap > <?=_("重复类型：")?></td>
            <td >
                <select name="TYPE"  onChange="sel_change()">
                    <option value="2" <?if($TYPE=="2") echo "selected";?>><?=_("按日重复")?></option>
                    <option value="3" <?if($TYPE=="3") echo "selected";?>><?=_("按周重复")?></option>
                    <option value="4" <?if($TYPE=="4") echo "selected";?>><?=_("按月重复")?></option>
                    <option value="5" <?if($TYPE=="5") echo "selected";?>><?=_("按年重复")?></option>
                </select>
            </td>
        </tr>
        <tr id="day" <?if($TYPE!="2") echo "style=\"display:none\"";?>>
            <td nowrap > <?=_("重复时间：")?></td>
            <td >
                <input type="text" name="REMIND_TIME2" class="input-small" size="10"  value="<?if($TYPE=="2") echo $REMIND_TIME; else echo $CUR_TIME;?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" style="cursor:hand" onClick="td_clock1('form1.REMIND_TIME2');">
                &nbsp;&nbsp;<?=_("为空为当前时间")?>
            </td>
        </tr>
        <tr id="week" <?if($TYPE!="3") echo "style=\"display:none\"";?>>
            <td nowrap > <?=_("重复时间：")?></td>
            <td >
                <select name="REMIND_DATE3" class="smallSelect">
                    <option value="1" <?if($TYPE=="3"&&$REMIND_DATE==1 || $TYPE!="3"&&date("w",time())==1) echo "selected";?>><?=_("星期一")?></option>
                    <option value="2" <?if($TYPE=="3"&&$REMIND_DATE==2 || $TYPE!="3"&&date("w",time())==2) echo "selected";?>><?=_("星期二")?></option>
                    <option value="3" <?if($TYPE=="3"&&$REMIND_DATE==3 || $TYPE!="3"&&date("w",time())==3) echo "selected";?>><?=_("星期三")?></option>
                    <option value="4" <?if($TYPE=="3"&&$REMIND_DATE==4 || $TYPE!="3"&&date("w",time())==4) echo "selected";?>><?=_("星期四")?></option>
                    <option value="5" <?if($TYPE=="3"&&$REMIND_DATE==5 || $TYPE!="3"&&date("w",time())==5) echo "selected";?>><?=_("星期五")?></option>
                    <option value="6" <?if($TYPE=="3"&&$REMIND_DATE==6 || $TYPE!="3"&&date("w",time())==6) echo "selected";?>><?=_("星期六")?></option>
                    <option value="0" <?if($TYPE=="3"&&$REMIND_DATE==0 || $TYPE!="3"&&date("w",time())==0) echo "selected";?>><?=_("星期日")?></option>
                </select>&nbsp;&nbsp;
                <input type="text" name="REMIND_TIME3" class="input-small" size="10"  value="<?if($TYPE=="3") echo $REMIND_TIME; else echo $CUR_TIME;?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" style="cursor:hand" onClick="td_clock1('form1.REMIND_TIME3');">
                &nbsp;&nbsp;<?=_("为空为当前时间")?>
            </td>
        </tr>
        <tr id="mon" <?if($TYPE!="4") echo "style=\"display:none\"";?>>
            <td nowrap > <?=_("重复时间：")?></td>
            <td >
                <select name="REMIND_DATE4" class="smallSelect">
                    <?
                    for($I=1;$I<=31;$I++)
                    {
                        ?>
                        <option value="<?=$I?>" <?if($TYPE=="4"&&$REMIND_DATE==$I || $TYPE!="4"&&date("j",time())==$I) echo "selected";?>><?=$I?><?=_("日")?></option>
                        <?
                    }
                    ?>
                </select>&nbsp;&nbsp;
                <input type="text" name="REMIND_TIME4" class="input-small" size="10"  value="<?if($TYPE=="4") echo $REMIND_TIME; else echo $CUR_TIME;?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" style="cursor:hand" onClick="td_clock1('form1.REMIND_TIME4');">
                &nbsp;&nbsp;<?=_("为空为当前时间")?>
            </td>
        </tr>
        <tr id="year" <?if($TYPE!="5") echo "style=\"display:none\"";?>>
            <td nowrap > <?=_("重复时间：")?></td>
            <td >
                <select name="REMIND_DATE5_MON" class="smallSelect">
                    <?
                    for($I=1;$I<=12;$I++)
                    {
                        ?>
                        <option value="<?=$I?>" <?if($TYPE=="5"&&$REMIND_DATE_MON==$I || $TYPE!="5"&&date("n",time())==$I) echo "selected";?>><?=$I?><?=_("月")?></option>
                        <?
                    }
                    ?>
                </select>&nbsp;&nbsp;
                <select name="REMIND_DATE5_DAY" class="smallSelect">
                    <?
                    for($I=1;$I<=31;$I++)
                    {
                        ?>
                        <option value="<?=$I?>" <?if($TYPE=="5"&&$REMIND_DATE_DAY==$I || $TYPE!="5"&&date("j",time())==$I) echo "selected";?>><?=$I?><?=_("日")?></option>
                        <?
                    }
                    ?>
                </select>&nbsp;&nbsp;
                <input type="text" name="REMIND_TIME5" class="input-small" size="10"  value="<?if($TYPE=="5") echo $REMIND_TIME; else echo $CUR_TIME;?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" style="cursor:hand" onClick="td_clock1('form1.REMIND_TIME5');">
                &nbsp;&nbsp;<?=_("为空为当前时间")?>
            </td>
        </tr>
        <tr>
            <td nowrap > <?=_("事务内容：")?></td>
            <td >
                <textarea name="CONTENT" ><?=$CONTENT?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap > <?=_("参与者：")?></td>
            <td  colspan=3>
                <input type="hidden" name="TAKER" id="TAKER" value="<?=$TAKER?>">
                <textarea cols=35 name="TAKER_NAME" rows=2  wrap="yes" readonly><?=$TAKER_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TAKER', 'TAKER_NAME','','form1')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('TAKER', 'TAKER_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <?
        $query = "select TYPE_PRIV from SMS2_PRIV where find_in_set('45',TYPE_PRIV)";
        $cursor=exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            if($SMS2_REMIND==1)
                $ISCHECKED="checked";
            ?>
            <tr>
                <td nowrap > <?=_("手机短信提醒：")?></td>
                <td >
                    <input type="checkbox" name="SMS2_REMIND" id="SMS2_REMIND" <?=$ISCHECKED?>>
                    <label for="SMS2_REMIND"><?=_("发送手机短信提醒")?></label>
                </td>
            </tr>
            <?
        }
        ?>
        <tr align="center">
            <td colspan="2" nowrap style="text-align:center">
                <input type="hidden" name="AFF_ID" value="<?=$AFF_ID?>" >
                <button type="submit" class="btn btn-info"><?=_("确定")?></button>
                <button type="button" class="btn" onClick="location='search.php'";><?=_("返回")?></button>
            </td>
        </tr>
</table>
</form>

</body>
</html>
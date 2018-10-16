<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("网络会议");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.TO_ID.value=="")
    {
        alert("<?=_("请指定参会范围！")?>");
        return (false);
    }

    if(document.form1.SUBJECT.value=="")
    {
        alert("<?=_("会议主题不能为空！")?>");
        return (false);
    }
    return (true);
}

function CheckDate()
{
    mon=document.form1.MEET_MON.value;
    year=document.form1.MEET_YEAR.value;

    if(mon=="04" || mon=="06" || mon=="09" || mon=="11")
    {
        if(document.form1.MEET_DAY.options.length>30)
            document.form1.MEET_DAY.remove(30);
        else if(document.form1.MEET_DAY.options.length<30)
        {
            if(document.form1.MEET_DAY.options.length==28)
            {
                var my_option = document.createElement("OPTION");
                my_option.text="29";
                my_option.value="29";
                document.form1.MEET_DAY.add(my_option);
            }

            var my_option = document.createElement("OPTION");
            my_option.text="30";
            my_option.value="30";
            document.form1.MEET_DAY.add(my_option);
        }
    }

    else if(mon=="02")
    {
        document.form1.MEET_DAY.remove(30);
        document.form1.MEET_DAY.remove(29);

        if(document.form1.MEET_DAY.options.length>28)
            if (!(year%400==0 || (year%4==0 && year%100!=0)))
                document.form1.MEET_DAY.remove(28);

        if(document.form1.MEET_DAY.options.length<29)
            if ((year%400==0 || (year%4==0 && year%100!=0)))
            {
                var my_option = document.createElement("OPTION");
                my_option.text="29";
                my_option.value="29";
                document.form1.MEET_DAY.add(my_option);
            }
    }
    else
    {
        if(document.form1.MEET_DAY.options.length<31)
        {
            if(document.form1.MEET_DAY.options.length==28)
            {
                var my_option = document.createElement("OPTION");
                my_option.text="29";
                my_option.value="29";
                document.form1.MEET_DAY.add(my_option);
            }

            if(document.form1.MEET_DAY.options.length==29)
            {
                var my_option = document.createElement("OPTION");
                my_option.text="30";
                my_option.value="30";
                document.form1.MEET_DAY.add(my_option);
            }

            var my_option = document.createElement("OPTION");
            my_option.text="31";
            my_option.value="31";
            document.form1.MEET_DAY.add(my_option);
        }
    }
}

function load_do()
{
    CheckDate();
    document.form1.SUBJECT.focus();
}
</script>


<?
$CUR_YEAR=date("Y",time());
$CUR_MON=date("m",time());
$CUR_DAY=date("d",time());

$CUR_HOUR=date("H",time());
$CUR_MIN=date("i",time());
?>

<body class="bodycolor" onload="load_do();">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建网络会议")?></span>
        </td>
    </tr>
</table>

<br>

<table class="TableBlock" width="500" align="center">
    <form action="submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
        <tr>
            <td nowrap class="TableData"><?=_("参会范围（人员）：")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols=30 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('25','','TO_ID', 'TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("会议主题：")?></td>
            <td class="TableData">
                <input type="text" name="SUBJECT" size="40" maxlength="100" class="BigInput" value="<?=$SUBJECT?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("开始时间：")?></td>
            <td class="TableData">

                <!-------------- 年 ------------>
                <select name="MEET_YEAR" class="BigSelect" onchange="CheckDate();">
                    <?
                    for($I=2010;$I<=2020;$I++)
                    {
                        ?>
                        <option value="<?=$I?>" <? if($I==$CUR_YEAR) echo "selected";?>><?=$I?></option>
                        <?
                    }
                    ?>
                </select><?=_("年")?>

                <!-------------- 月 ------------>
                <select name="MEET_MON" class="BigSelect" onchange="CheckDate();">
                    <?
                    for($I=1;$I<=12;$I++)
                    {
                        if($I<10)
                            $I="0".$I;
                        ?>
                        <option value="<?=$I?>" <? if($I==$CUR_MON) echo "selected";?>><?=$I?></option>
                        <?
                    }
                    ?>
                </select><?=_("月")?>

                <!-------------- 日 ------------>
                <select name="MEET_DAY" class="BigSelect">
                    <?
                    for($I=1;$I<=31;$I++)
                    {
                        if($I<10)
                            $I="0".$I;
                        ?>
                        <option value="<?=$I?>" <? if($I==$CUR_DAY) echo "selected";?>><?=$I?></option>
                        <?
                    }
                    ?>
                </select><?=_("日")?>&nbsp;&nbsp;&nbsp;

                <!-------------- 时 ------------>
                <select name="MEET_HOUR" class="BigSelect">
                    <?
                    for($I=0;$I<=23;$I++)
                    {
                        if($I<10)
                            $I="0".$I;
                        ?>
                        <option value="<?=$I?>" <? if($I==$CUR_HOUR) echo "selected";?>><?=$I?></option>
                        <?
                    }
                    ?>
                </select>:

                <!-------------- 分 ------------>
                <select name="MEET_MIN" class="BigSelect">
                    <?
                    for($I=0;$I<=59;$I++)
                    {
                        if($I<10)
                            $I="0".$I;
                        ?>
                        <option value="<?=$I?>" <? if($I==$CUR_MIN) echo "selected";?>><?=$I?></option>
                        <?
                    }
                    ?>
                </select>

            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=sprintf(_("会议开始时%s提醒参会者："),"<br>")?></td>
            <td class="TableData">
                <?=sms_remind(3);?>
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
                <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='../'">
            </td>
        </tr>
</table>
</form>

</body>
</html>
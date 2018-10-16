<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("check_func.func.php");

$HTML_PAGE_TITLE = _("上下班记录查询");
include_once("inc/header.inc.php");
?>


<script language="JavaScript">
    function remark(USER_ID,REGISTER_TYPE,REGISTER_TIME)
    {
        URL="../user_manage/remark.php?USER_ID="+USER_ID+"&REGISTER_TYPE="+REGISTER_TYPE+"&REGISTER_TIME="+REGISTER_TIME;
        myleft=(screen.availWidth-650)/2;
        window.open(URL,"formul_edit","height=250,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
    }

</script>


<body class="bodycolor">
<?
$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;

$MSG = sprintf(_("共 %d 天"), $DAY_TOTAL);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
            <span class="big3"> <?=_("上下班查询结果")?> - <?=$USER_NAME?>-[<?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>]</span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<table class="TableList"  width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("登记信息")?></td>
    </tr>
    <?
    //查询考勤记录
    $query1 = "SELECT * from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)>=to_days('$DATE1') and to_days(REGISTER_TIME)<=to_days('$DATE2')";
    $cursor1= exequery(TD::conn(),$query1);
    $LINE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor1))
    {
        $LINE_COUNT++;
        $REGISTER_TIME=$ROW["REGISTER_TIME"];
        $REGISTER_IP=$ROW["REGISTER_IP"];
        $SXB=$ROW["SXB"];
        if($SXB=="1")
            $REGISTER_TIME=$REGISTER_TIME._("[上班]");
        else if($SXB=="2")
            $REGISTER_TIME=$REGISTER_TIME._("[下班]");
        ?>
        <tr class="TableData">
            <td nowrap align="center"><?=$REGISTER_TIME?>(<?=$REGISTER_IP?>)</td>
        </tr>
        <?
    }
    ?>
</table>
<?
Button_Back();
?>
</body>
</html>
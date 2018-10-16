<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("排行结果");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../func.func.php");

?>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>

<body class="bodycolor" >
<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/hr/rank/rank.png" WIDTH="22" HEIGHT="20" align="absmiddle">
            <span class="big3"><?=_("积分排行榜")?></span>
        </td>
    </tr>
</table>
<?
$rank_info=get_integrals();
// $end=$start+$ITEMS_IN_PAGE;
arsort($rank_info);
$USER_COUNT=0;
foreach($rank_info as $rank_key=>$rank_value)
{
    $USER_COUNT++;
    $USER_ID=$rank_key;
    $SUMALL=round($rank_value,2);

    $query_user="select USER_NAME,DEPT_ID,USER.USER_PRIV,SEX,USER_PRIV.PRIV_NAME from USER_PRIV,USER where USER.USER_PRIV = USER_PRIV.USER_PRIV and USER_ID='$USER_ID'";
    $cursor_user=exequery(TD::conn(),$query_user);
    if($ROW_USER=mysql_fetch_array($cursor_user))
    {
        if($ROW_USER["DEPT_ID"]==0)
        {
            $USER_COUNT--;
            continue;
        }
        $USER_NAME = $ROW_USER["USER_NAME"];
        $DEPT_ID1  = $ROW_USER["DEPT_ID"];
        $USER_PRIV = $ROW_USER["USER_PRIV"];
        $SEX       = $ROW_USER["SEX"];
        $PRIV_NAME = $ROW_USER["PRIV_NAME"];
    }

    if($DEPT_ID1 != '0' && $DEPT_ID1 !="")
    {
        $query1 = "SELECT * from DEPARTMENT where DEPT_ID='".$DEPT_ID1."'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
            $DEPT_NAME=$ROW["DEPT_NAME"];
    }

    $DEPT_LONG_NAME=dept_long_name($DEPT_ID1);

    if($USER_COUNT==1)
    {
?>
<table class="TableList" width="90%"  align="center">
    <?
    }
    if($USER_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    //所有人排行
    $All_Rank = $start+$USER_COUNT;
    //按照部门排行
    $Dept_Rank = getUser_Rank_depa($USER_ID,$DEPT_ID1);
    //按照角色排行
    $Priv_Rank = getUser_Rank_priv($USER_ID,$USER_PRIV);
    ?>
    <tr class="<?=$TableLine?>" title="<?=$TR_TITLE?>" style="<?=$STYLE_STR?>">
        <!--<td nowrap align="center"><?=$USER_NAME?></td>-->
        <?
        if($USER_IDS!="" || $USER_IDS!=null){
            if($USER_IDS==$USER_ID){
                $css_str = "style='font-weight:bold;font-size:16px;COLOR:#FF0000;'";
            }
            else
            {
                $css_str = "";
            }
        }
        ?>
        <td <?=$css_str?> align="center"><?=$USER_NAME?></td>
        <td <?=$css_str?> align="center"><?=$DEPT_NAME?></td>
        <td <?=$css_str?> align="center"><?=$PRIV_NAME?></td>
        <td <?=$css_str?> align="center"><?=$SUMALL?></td>

        <!--总排行榜-->
        <td  align="center"><?
            if($All_Rank=='1')
                echo "<img src='".MYOA_STATIC_SERVER."/static/images/hr/rank/01.png' WIDTH='27' HEIGHT='30'>";
            if($All_Rank=='2')
                echo "<img src='".MYOA_STATIC_SERVER."/static/images/hr/rank/02.png' WIDTH='25' HEIGHT='28'>";
            if($All_Rank=='3')
                echo "<img src='".MYOA_STATIC_SERVER."/static/images/hr/rank/03.png' WIDTH='24' HEIGHT='26'>";
            if($All_Rank != '1' && $All_Rank != '2' && $All_Rank != '3')
                echo "<span style='WIDTH:20px;HEIGHT:20px;display:block;font-size:14px;font-weight:bold;background:url(".MYOA_STATIC_SERVER."/static/images/hr/rank/num_bg.png);color:#6c6c6c' >".$All_Rank."</span>";
            ?></td>
        <!--部门-->
        <td align="center"><?
            if($Dept_Rank=='1')
                echo "<img src='".MYOA_STATIC_SERVER."/static/images/hr/rank/01.png' WIDTH='27' HEIGHT='30'>";
            if($Dept_Rank=='2')
                echo "<img src='".MYOA_STATIC_SERVER."/static/images/hr/rank/02.png' WIDTH='25' HEIGHT='28'>";
            if($Dept_Rank=='3')
                echo "<img src='".MYOA_STATIC_SERVER."/static/images/hr/rank/03.png' WIDTH='24' HEIGHT='26'>";
            if($Dept_Rank != '1' && $Dept_Rank != '2' && $Dept_Rank != '3')
                echo "<span style='WIDTH:20px;HEIGHT:20px;display:block;font-size:14px;font-weight:bold;background:url(".MYOA_STATIC_SERVER."/static/images/hr/rank/num_bg.png);color:#6c6c6c' >".$Dept_Rank."</span>";
            ?></td>
        <!--角色-->
        <td align="center"><?
            if($Priv_Rank=='1')
                echo "<img src='".MYOA_STATIC_SERVER."/static/images/hr/rank/01.png' WIDTH='27' HEIGHT='30'>";
            if($Priv_Rank=='2')
                echo "<img src='".MYOA_STATIC_SERVER."/static/images/hr/rank/02.png' WIDTH='25' HEIGHT='28'>";
            if($Priv_Rank=='3')
                echo "<img src='".MYOA_STATIC_SERVER."/static/images/hr/rank/03.png' WIDTH='24' HEIGHT='26'>";
            if($Priv_Rank != '1' && $Priv_Rank != '2' && $Priv_Rank != '3')
                echo "<span style='WIDTH:20px;HEIGHT:20px;display:block;font-size:14px;font-weight:bold;background:url(".MYOA_STATIC_SERVER."/static/images/hr/rank/num_bg.png);color:#6c6c6c' >".$Priv_Rank."</span>";
            ?></td>
    </tr>
    <?
    }
    if($USER_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td align="center"><?=_("姓名")?></td>
    <td align="center"><?=_("所属部门")?></td>
    <td align="center"><?=_("角色")?></td>
    <td align="center"><?=_("个人积分")?></td>
    <td align="center"><?=_("总排行")?></td>
    <td align="center"><?=_("部门排行")?></td>
    <td align="center"><?=_("角色排行")?></td>
    </thead>
</table>
<?
}
else
{
    Message("",_("无积分信息"));
}
?>
<br>
</body>

</html>

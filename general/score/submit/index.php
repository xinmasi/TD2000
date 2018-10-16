<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

//修改事务提醒状态--yc
update_sms_status('15',0);

?>
<script>
function show_reader(GROUP_ID)
{
    URL="show_reader.php?GROUP_ID="+GROUP_ID;
    myleft=(screen.availWidth-500)/2;
    window.open(URL,"read_vote","height=500,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
</script>
<?
$HTML_PAGE_TITLE = _("进行考核");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" WIDTH="22" HEIGHT="22" align="absmiddle"><span class="big3">&nbsp;<?=_("考核待办流程")?></span>
        </td>
    </tr>
</table>

<br>
<div align="center">
<?
$CUR_DATE=date("Y-m-d",time());

//============================显示已发布考核任务=======================================
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from SCORE_FLOW where(find_in_set('".$_SESSION["LOGIN_USER_ID"]."',RANKMAN)) and BEGIN_DATE<='$CUR_DATE' and (END_DATE>='$CUR_DATE' or END_DATE is null) order by SEND_TIME desc";
$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $VOTE_COUNT++;
    $FLOW_ID=$ROW["FLOW_ID"];
    $FLOW_TITLE=$ROW["FLOW_TITLE"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $RANKMAN=$ROW["RANKMAN"];
    $login_user = $_SESSION['LOGIN_USER_ID'];
    $PARTICIPANT =$ROW["PARTICIPANT"];
    $GROUP_ID=$ROW["GROUP_ID"];
    $ANONYMITY=$ROW["ANONYMITY"];
    if($ANONYMITY=="0")
        $ANONYMITY_DESC=_("不允许");
    else
        $ANONYMITY_DESC=_("允许");

    if($END_DATE=="0000-00-00")
        $END_DATE="";
    $RAN_NAME=$_SESSION["LOGIN_USER_NAME"];
    $query1="select * from SCORE_GROUP where GROUP_ID='$GROUP_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $GROUP_NAME=$ROW["GROUP_NAME"];

    if($VOTE_COUNT==1)
    {
    ?>
    <table width="95%" class="TableList">
        <?
        }
        if($VOTE_COUNT%2==1)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";
        ?>
        <tr class="<?=$TableLine?>">
            <td nowrap align="center"><?=$FLOW_TITLE?></td>
            <td align="center"><?=$RAN_NAME?></td>

            <td align="center"><a href="javascript:show_reader('<?=$GROUP_ID?>');" title="<?=_("点击查看考核项目")?>"><?=$GROUP_NAME?></a></td>
            <td align="center"><?=$ANONYMITY_DESC?></td>
            <td nowrap align="center"><?=$BEGIN_DATE?></td>
            <td nowrap align="center"><?=$END_DATE?></td>
            <td nowrap align="center">
                <?
                $query1 = "select PARTICIPANT,FLOW_FLAG from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
                $cursor1= exequery(TD::conn(),$query1);
                if($ROW=mysql_fetch_array($cursor1))
                {
                    $PARTICIPANT=substr($ROW["PARTICIPANT"],0,-1);
                    $FLOW_FLAG=$ROW["FLOW_FLAG"];
                }

                $tempcount=0;
                $NEW = array();
                if($FLOW_FLAG==0)
                {
                    $MY_ARRAY=explode(",",$PARTICIPANT);
                    foreach ($MY_ARRAY as $value)
                    {
                        if($value != $login_user)
                        {
                            $NEW[] = $value;
                        }
                    }
                }
                else
                {
                    $TEMP_ARRAY=explode(",",$PARTICIPANT);
                    foreach ($TEMP_ARRAY as $value)
                    {
                        if($value != $login_user)
                        {
                            $NEW[] = $value;
                        }
                    }
                    $ARRAY_COUNT=sizeof($NEW);
                    if($NEW[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
                    for($I=0;$I < $ARRAY_COUNT;$I++)
                    {
                        $query1 = "select DEPT_ID from USER where USER_ID='$NEW[$I]'";
                        $cursor1= exequery(TD::conn(),$query1);
                        if($ROW=mysql_fetch_array($cursor1))
                            $DEPT_ID=$ROW["DEPT_ID"];
                        if(is_dept_priv($DEPT_ID)==1)
                        {
                            $MY_ARRAY[$tempcount]=$NEW[$I];
                            $tempcount=$tempcount+1;
                        }
                    }
                }
                $OLD_COUNT=count($NEW);
                $MY_SCORE_COUNT=0;
                $query1="SELECT count(*) from SCORE_DATE where FLOW_ID='$FLOW_ID' and RANKMAN='".$_SESSION["LOGIN_USER_ID"]."'";
                $cursor1= exequery(TD::conn(),$query1);
                if($ROW=mysql_fetch_array($cursor1))
                    $MY_SCORE_COUNT=$ROW[0];

                //echo $MY_SCORE_COUNT."|".$OLD_COUNT;
                if($OLD_COUNT > $MY_SCORE_COUNT)
                    echo "<font color=red>"._("未考核")."</font>";
                else
                    echo _("已考核");
                ?>

            </td>
            <td nowrap align="center">
                <a href="score_index.php?FLOW_ID=<?=$FLOW_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"><?=_("考核")?></a>
            </td>
        </tr>
        <?
        }

        if($VOTE_COUNT>0)
        {
        ?>
        <thead class="TableHeader">
        <td nowrap align="center"><?=_("考核任务名称")?></td>
        <td nowrap align="center"><?=_("考核人")?></td>
        <td nowrap align="center"><?=_("考核指标集")?></td>
        <td nowrap align="center"><?=_("匿名")?></td>
        <td nowrap align="center"><?=_("生效日期")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
        <td nowrap align="center"><?=_("终止日期")?></td>
        <td nowrap align="center"><?=_("本人考核状态")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
        </thead>
    </table>
<?
}
else
    Message("",_("尚未定义"));
?>

</div>
</body>
</html>
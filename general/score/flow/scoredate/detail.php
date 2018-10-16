<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query="SELECT FLOW_TITLE from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $FLOW_TITLE=$ROW["FLOW_TITLE"];

$HTML_PAGE_TITLE = _("考核数据");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//============================被考核人员名称、部门、角色=======================================
$CUR_DATE=date("Y-m-d",time());
$USER_ID=array();
$USER_ID=explode(",",$STAFF_ID_STR);
$query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where find_in_set(a.USER_ID,'$STAFF_ID_STR')";

$cursor1= exequery(TD::conn(),$query1);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor1))
{
    $USER_ID[$VOTE_COUNT]=$ROW["USER_ID"];
    $USER_NAME[$VOTE_COUNT]=$ROW["USER_NAME"];
    $USER_PRIV[$VOTE_COUNT]=$ROW["PRIV_NAME"];
    $USER_DEPT[$VOTE_COUNT]=$ROW["DEPT_NAME"];
    $VOTE_COUNT++;
}
//============================考核项目========================================
$query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $ITEM_NAME[$VOTE_COUNT]=$ROW["ITEM_NAME"];
    $VOTE_COUNT++;
}

//===========================考核分数,评分人名称、部门、角色==================================
$ARRAY_COUNT=sizeof($USER_ID);

for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $query1 = "select MEMO,a.SCORE,USER_NAME,PRIV_NAME,DEPT_NAME FROM SCORE_DATE a LEFT OUTER JOIN USER b ON a.RANKMAN = b.USER_ID LEFT OUTER JOIN USER_PRIV c ON b.USER_PRIV = c.USER_PRIV LEFT OUTER JOIN DEPARTMENT d ON d.DEPT_ID = b.DEPT_ID where PARTICIPANT='$USER_ID[$I]' and FLOW_ID='$FLOW_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    //echo $USER_ID[$I]."<br>";
    $COUNT=0;
    while($ROW=mysql_fetch_array($cursor1))
    {
        $SCORE=$ROW["SCORE"];
        $MEMO=$ROW["MEMO"];

        $MY_SCORE[$I][$COUNT]=explode(",",$SCORE);
        $MY_MEMO[$I][$COUNT]=explode(",",$MEMO);
        if($ANONYMITY=="0")
        {
            $RANK_NAME[$I][$COUNT]=$ROW["USER_NAME"];
            $RANK_PRIV[$I][$COUNT]=$ROW["PRIV_NAME"];
            $RANK_DEPT[$I][$COUNT]=$ROW["DEPT_NAME"];
        }
        else
        {
            $RANK_NAME[$I][$COUNT]="****";
            $RANK_PRIV[$I][$COUNT]="****";
            $RANK_DEPT[$I][$COUNT]="****";
        }
        $COUNT++;
    }
}


$USER_COUNT=sizeof($USER_ID);
$field_count=sizeof($MY_SCORE[0][0]);

for ($count=0;$count<$field_count;$count++)
{
    for($I=0;$I<$USER_COUNT;$I++)
    {
        $RECORD_COUNT= sizeof($MY_SCORE[$I]);

        for ($field=0;$field<$RECORD_COUNT;$field++)
        {
            $MY_SCORESAM[$I][$count]=$MY_SCORESAM[$I][$count]+$MY_SCORE[$I][$field][$count];
            //if($MY_SCORE[$I][$field][$count]<>0)
                $MY_SCORECOUNT[$I][$count]=$MY_SCORECOUNT[$I][$count]+1;
        }
    }

}

//--------------求取平均分----------
$ARRAY_COUNT=sizeof($USER_NAME);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $ARRAY_COUNT1=sizeof($MY_SCORESAM[$I]);
    for($count=0;$count<$ARRAY_COUNT1-1;$count++)
    {

        if($MY_SCORECOUNT[$I][$count]=="")
        {$MY_AVE[$I][$count]=0;}
        else
        {$MY_AVE[$I][$count]=round($MY_SCORESAM[$I][$count]/$MY_SCORECOUNT[$I][$count],2);}
    }

}


?>

<table border="0" width="100%" cellspacing="0" cellpadding="4" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("详细信息")?></span>
        </td>
    </tr>
</table>


<table width="100%" class="TableList">
    <thead class="TableHeader">
    <td nowrap align="center"><?=_("部门")?></td>
    <td nowrap align="center"><?=_("姓名")?></td>
    <td nowrap align="center"><?=_("角色")?></td>
    <?
    $ARRAY_COUNT=sizeof($ITEM_NAME);
    for($I=0;$I<$ARRAY_COUNT;$I++)
    {
        ?>
        <td nowrap align="center"><?=$ITEM_NAME[$I]?></td>

        <?
    }
    ?>
    <td nowrap align="center"><?=_("总计")?></td>
    </thead>
    <?

    $ARRAY_COUNT=sizeof($USER_NAME);
    for($I=0;$I<$ARRAY_COUNT;$I++)
    {$TOTAL=0;
        ?>
        <tr class="TableLine1"  style="cursor:hand">
            <td align="center" onClick="td_detail('<?=$I?>');"><?=$USER_DEPT[$I]?></td>
            <td align="center" onClick="td_detail('<?=$I?>');"><?=$USER_NAME[$I]?></td>
            <td align="center" onClick="td_detail('<?=$I?>');"><?=$USER_PRIV[$I]?></td>
            <?
            $ARRAY_COUNT1=sizeof($MY_AVE[$I]);
            $colnumber=$ARRAY_COUNT1+4;
            $SCORE_STR="";
            for($count=0;$count<$ARRAY_COUNT1;$count++)
            {  $TOTAL=$TOTAL+$MY_AVE[$I][$count];
                $SCORE_STR.=$MY_AVE[$I][$count].",";
                ?>
                <td align="center" onClick="td_detail('<?=$I?>');"><?=$MY_AVE[$I][$count]?></td>

                <?
            }
            $SCORE_STR.=$TOTAL;
            ?>
            <td nowrap align="center" onClick="td_detail('<?=$I?>');"><?=$TOTAL?></td>
        </tr>
        <?
    }
    ?>


</table>
<br>
<center><input type="button"  value="<?=_("关闭")?>" class="BigButton" onClick="window.close();"></center>
</body>
</html>
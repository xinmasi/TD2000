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


<script Language="JavaScript">
function send_email(USER_ID,GROUP_ID,SCORE_STR)
{
    URL="send_email.php?USER_ID="+USER_ID+"&GROUP_ID="+GROUP_ID+"&SCORE_STR="+SCORE_STR+"&FLOW_TITLE=<?=urlencode($FLOW_TITLE)?>&ANONYMITY=<?=$ANONYMITY?>";
    window.location=URL;
}
function send_email_all(GROUP_ID,FLOW_ID)
{
    URL="send_email_all.php?GROUP_ID="+GROUP_ID+"&FLOW_ID="+FLOW_ID;
    window.location=URL;
}
</script>


<body class="bodycolor">

<?
//============================被考核人员名称、部门、角色=======================================
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from SCORE_DATE where FLOW_ID='$FLOW_ID' group by PARTICIPANT";
$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{

    $USER_ID[$VOTE_COUNT]=$ROW["PARTICIPANT"];
    $query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$USER_ID[$VOTE_COUNT]'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
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
    $ITEM_NAME[$VOTE_COUNT]=str_replace("\n","<br/>",$ITEM_NAME[$VOTE_COUNT]);
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

        //echo $SCORE."<br>";

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

for($count=0;$count<$field_count;$count++)
{
    for($I=0;$I<$USER_COUNT;$I++)
    {
        $RECORD_COUNT= sizeof($MY_SCORE[$I]);

        for($field=0;$field<$RECORD_COUNT;$field++)
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
        {
            $MY_AVE[$I][$count]=0;
        }
        else
        {
            $MY_AVE[$I][$count]=round($MY_SCORESAM[$I][$count]/$MY_SCORECOUNT[$I][$count],2);
        }
    }

}


?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("考核数据查询")?></span>
            &nbsp; &nbsp;<input type="button"  value="<?=_("关闭")?>" class="SmallButton" onClick="window.close();">
        </td>
        <td class="Small"><b><?=_("点击条目显示详细打分情况")?></b></td>
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
    <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    <?

    $ARRAY_COUNT=sizeof($USER_NAME);
    for($I=0;$I<$ARRAY_COUNT;$I++)
    {
        $TOTAL=0;
        ?>
        <tr class="TableLine1"  style="cursor:hand" title="<?=_("单击查看评分明细")?>">
            <td align="center" onClick="td_detail('<?=$I?>');"><?=$USER_DEPT[$I]?></td>
            <td align="center" onClick="td_detail('<?=$I?>');"><?=$USER_NAME[$I]?></td>
            <td align="center" onClick="td_detail('<?=$I?>');"><?=$USER_PRIV[$I]?></td>
            <?
            $ARRAY_COUNT1=sizeof($MY_AVE[$I]);
            $colnumber=$ARRAY_COUNT1+4;
            $SCORE_STR="";
            for($count=0;$count<$ARRAY_COUNT1;$count++)
            {
                $TOTAL=$TOTAL+$MY_AVE[$I][$count];
                $SCORE_STR.=$MY_AVE[$I][$count].",";
                ?>
                <td align="center" onClick="td_detail('<?=$I?>');"><?=$MY_AVE[$I][$count]?></td>

                <?
            }
            $SCORE_STR.=$TOTAL;
            ?>
            <td nowrap align="center" onClick="td_detail('<?=$I?>');"><?=$TOTAL?></td>
            <td align="center"><a href="javascript:send_email('<?=$USER_ID[$I]?>','<?=$GROUP_ID?>','<?=$SCORE_STR?>');" title="<?=_("考核结果发送EMAIL")?>"><?=_("发送EMAIL")?></a></td>
        </tr>
        <tr class="TableData" id=<?=$I?> style="display:none" onDblClick="td_close('<?=$I?>');" title="<?=_("双击关闭子窗口")?>">

            <td align="left" colspan=<?=$colnumber+1?>>
                <br>
                <table border="0" width="60%" cellspacing="1" cellpadding="3" bgcolor="#000000" class="small">

                    <thead class="TableHeader">
                    <td nowrap align="center" style="color:black;"><?=_("部门")?></td>
                    <td nowrap align="center" style="color:black;"><?=_("评分人姓名")?></td>
                    <td nowrap align="center" style="color:black;"><?=_("角色")?></td>
                    <?
                    $ARRAY_COUNT2=sizeof($ITEM_NAME);
                    for($I2=0;$I2<$ARRAY_COUNT2;$I2++)
                    {
                        ?>
                        <td nowrap align="center" style="color:black;"><?=$ITEM_NAME[$I2]?></td>

                        <?
                    }
                    ?>

                    </thead>
                    <?

                    $SON_COUNT=sizeof($RANK_NAME[$I]);
                    for($SON_I=0;$SON_I<$SON_COUNT;$SON_I++)
                    {$SON_TOTAL=0;
                        ?>
                        <tr class="TableData" >
                            <td align="center"><?=$RANK_DEPT[$I][$SON_I]?></td>
                            <td align="center"><?=$RANK_NAME[$I][$SON_I]?></td>
                            <td align="center"><?=$RANK_PRIV[$I][$SON_I]?></td>
                            <?
                            $SON_COUNT1=sizeof($MY_SCORE[$I][$SON_I]);

                            for($soncount=0;$soncount<$SON_COUNT1-1;$soncount++)
                            { if ($MY_SCORE[$I][$SON_I][$soncount]=="")
                            {$SON_TOTAL=$SON_TOTAL+0;}
                            else
                            {$SON_TOTAL=$SON_TOTAL+$MY_SCORE[$I][$SON_I][$soncount];}

                                ?>
                                <td align="center" nowrap><?=$MY_SCORE[$I][$SON_I][$soncount]?><br>
                                    <?
                                    if($MY_MEMO[$I][$SON_I][$soncount]!="")
                                    {
                                        ?>
                                        <span class="big4">(<?=_("批注：")?><?=$MY_MEMO[$I][$SON_I][$soncount]?>)</span>
                                        <?
                                    }
                                    ?>

                                </td>

                                <?
                            }
                            ?>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <br>
            </td>
        </tr>
        <?
    }
    ?>


</table>
<br>
<center><input type="button" value="<?=_("给所有人发EMAIL")?>" title="<?=_("给以上列表人员发EMAIL")?>" onClick="send_email_all('<?=$GROUP_ID?>','<?=$FLOW_ID?>');" class="BigButton"></center>
</body>
</html>
<script Language="JavaScript">

function td_detail(I)
{
    if(document.getElementById(I).style.display=="none")
    {
        document.getElementById(I).style.display="";
        document.getElementById(I).style.cursor="hand";
    }
    else
        document.getElementById(I).style.display="none";
}

function td_close(I)
{
    document.document.getElementById(I).style.display="none";
}
</script>
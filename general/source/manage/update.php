<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
function CHEXIAO($SOURCE_ID,$APPLY_DATE,$APPLY_J)
{
    $query="select USER_ID from OA_SOURCE_USED where SOURCEID='$SOURCE_ID' and APPLY_DATE='$APPLY_DATE'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $USER_ID=$ROW["USER_ID"];
    $USER_ARRAY=explode(",",$USER_ID);
    $ARRAY_COUNT=sizeof($USER_ARRAY);
    if($USER_ARRAY[$ARRAY_COUNT-1]=="")
        $ARRAY_COUNT--;
    $USER_ID_NEWSTR="";
    for($U=0;$U<$ARRAY_COUNT;$U++)
    {
        if($U==$APPLY_J)
        {
            $USER_ARRAY[$U]="0";
        }
        $USER_ID_NEWSTR.=$USER_ARRAY[$U].",";

    }
    $query="update OA_SOURCE_USED set USER_ID='$USER_ID_NEWSTR' where APPLY_DATE='$APPLY_DATE' and SOURCEID='$SOURCE_ID'";
    $cur=exequery(TD::conn(),$query);
    if($cur)
        return true;
    else
        return false;
}
if($SOURCE_ID!="")
{
    $query="select TIME_TITLE,DAY_LIMIT,WEEKDAY_SET,SOURCENAME from OA_SOURCE where SOURCEID='$SOURCE_ID' ";
    $cursor = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $TIME_TITLE=$ROW["TIME_TITLE"];
        $DAY_LIMIT=$ROW["DAY_LIMIT"];
        $WEEKDAY_SET=$ROW["WEEKDAY_SET"];
        $SOURCENAME=$ROW["SOURCENAME"];
    }
    $CUR_DATE=date("Y-m-d",time());
    $TIME_ARRAY=explode(",",$TIME_TITLE);
    $ARRAY_COUNT=sizeof($TIME_ARRAY);
    if($TIME_ARRAY[$ARRAY_COUNT-1]=="")
        $ARRAY_COUNT--;
}
if($SAVE==1)
{
    $query="select * from OA_SOURCE_USED  where APPLY_DATE='$APPLY_DATE_VALUE' and SOURCEID='$SOURCE_ID'";
    $cursor = exequery(TD::conn(),$query);
    $num=mysql_num_rows($cursor);
    if($num>0)
    {
        $ROWS=mysql_fetch_array($cursor);
        $USER_ID=$ROWS['USER_ID'];
        $USER_ARRAY=explode(",",$USER_ID);
        $USER_ARRAY_COUNT=sizeof($USER_ARRAY);
        if($USER_ARRAY[$USER_ARRAY_COUNT-1]=="")
            $USER_ARRAY_COUNT--;
        $NEW_USER_ID_STR="";
        for($M=0;$M<$USER_ARRAY_COUNT;$M++)
        {
            if($M==$APPLY_DATE)
            {
                $CUE_USER=$USER_ARRAY[$M];
                if($CUE_USER!="" && $CUE_USER!="0")
                {
                    Message("",_("您选择的时间段已有人预定，请重新选择时间段或者撤消该时间段预定信息！"));
                    ?>
                    <div align=center>
                        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="window.close()"><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">
                    </div>
                    <script>opener.location.reload();</script>
                    <?
                    exit;
                }
                else
                    $USER_ARRAY[$M]=$USER_ID_UP;

            }
            $NEW_USER_ID_STR.=$USER_ARRAY[$M].",";

        }
        $query="update OA_SOURCE_USED set USER_ID='$NEW_USER_ID_STR' where APPLY_DATE='$APPLY_DATE_VALUE' and SOURCEID='$SOURCE_ID'";
        exequery(TD::conn(),$query);
        if(CHEXIAO($SOURCE_ID,$APPLY_DATE_CUR,$APPLY_J))
        {
            $APPLY_TIME_NOW=date("Y-m-d,H:i:s");
            $MSG = sprintf(_("您申请的资源:%s，使用时间已从%s修改为%s"),$SOURCENAME,$APPLY_DATE_CUR.",".TimeChange($APPLY_J,$TIME_ARRAY),$APPLY_DATE_VALUE.",".TimeChange($APPLY_DATE,$TIME_ARRAY));
            $SMS_CONTENT=$MSG." ";
            send_sms($APPLY_TIME_NOW, $_SESSION["LOGIN_USER_ID"],$USER_ID_UP,76, $SMS_CONTENT, $REMIND_URL);
            Message("",_("预定信息修改成功"));
        }
        ?>
        <DIV align=center>
            <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">
        </DIV>
        <script>opener.location.reload();</script>
        <?
        exit;
    }
    else
    {
        $USER_ID_STR="";
        for($L=0;$L<$ARRAY_COUNT;$L++)
        {
            if($L==$APPLY_DATE)
            {
                $USER=$USER_ID_UP;
            }
            else
            {
                $USER="0";
            }
            $USER_ID_STR.=$USER.",";
        }
        $query="insert into OA_SOURCE_USED (SOURCEID,APPLY_DATE,USER_ID) values('$SOURCE_ID','$APPLY_DATE_VALUE','$USER_ID_STR') ";
        exequery(TD::conn(),$query);
        if(CHEXIAO($SOURCE_ID,$APPLY_DATE_CUR,$APPLY_J))
        {
            $APPLY_TIME_NOW=date("Y-m-d,H:i:s");
            $MSG2 = sprintf(_("您申请的资源:%s，使用时间已从%s修改为%s"),$SOURCENAME,$APPLY_DATE_CUR.",".TimeChange($APPLY_J,$TIME_ARRAY),$APPLY_DATE_VALUE.",".TimeChange($APPLY_DATE,$TIME_ARRAY));
            $SMS_CONTENT=$MSG2." ";
            send_sms($APPLY_TIME_NOW, $_SESSION["LOGIN_USER_ID"],$USER_ID_UP, 76, $SMS_CONTENT, $REMIND_URL);
            Message("",_("预定信息修改成功"));
        }
        ?>
        <DIV align=center>
            <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">
        </DIV>
        <script>opener.location.reload();</script>
        <?
        exit;
    }
}
?>
<body>
<script>
function add_onclick()
{
    document.addform.submit();
}
</script>
<FORM id=addform name=addform action=update.php?SAVE=1&SOURCE_ID=<?=$SOURCE_ID?>&USER_NAME=<?=$USER_NAME?>&USER_ID_UP=<?=$APPLY_VALUE?>&APPLY_DATE_CUR=<?=$APPLY_DATE_CUR?>&APPLY_J=<?=$APPLY_J?> method=post>
    <table class=small width=100% border="0" cellspacing="1" cellpadding="3" bgcolor="#000000">
        <TR class=TableHeader>
            <?
            $MSG3 = sprintf(_("修改%s的预定时间"),$USER_NAME);
            ?>
            <TD colspan=2><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absmiddle"><?=$MSG3?></TD>
        </TR>
        <TR class=TableData>
            <TD nowrap ><b><?=_("日期")?></b></TD>
            <TD>
                <select name="APPLY_DATE_VALUE" onChange="change()">
                    <?
                    for($I=0;$I<$DAY_LIMIT;$I++) //循换XX天
                    {
                        $APPLY_DATE=time()+$I*24*3600;
                        if(!find_id($WEEKDAY_SET,date("w", $APPLY_DATE)))
                        {
                            $DAY_LIMIT++;
                            continue;
                        }

                        $APPLY_DATE=date("Y-m-d",$APPLY_DATE);
                        $APPLY_DATE_DESC=substr($APPLY_DATE,5);
                        $WEEK_DAY=get_week($APPLY_DATE);
                        ?>
                        <option value="<?=$APPLY_DATE?>" ><?=$APPLY_DATE_DESC.$WEEK_DAY ?></option>
                        <?
                    }
                    ?>
                </select>
            </TD>
        </TR>
        <TR class=TableData>
            <TD nowrap ><b><?=_("时间段")?></b></TD>
            <TD>
                <select name="APPLY_DATE" onChange="change()">
                    <?
                    for($N=0;$N<$ARRAY_COUNT;$N++)
                    {
                        ?>
                        <option value="<?=$N?>" ><?=$TIME_ARRAY[$N]?></option>
                        <?
                    }
                    ?>
                </select>
            </TD>
        </TR>
        <TR class=TableControl>
            <TD colspan=2 align=center width="500">
                <INPUT TYPE="hidden" NAME="CYCID" value="<?=$CYCID?>">
                <INPUT TYPE="hidden" NAME="WEEKDAY_SET2" value="<?=$WEEKDAY_SET2?>">
                <INPUT TYPE="hidden" NAME="TIME_TITLE2" value="<?=$TIME_TITLE2?>">
                <INPUT type="button" class="BigButton" value="<?=_("保存")?>" id=post name=post LANGUAGE=javascript onClick="return add_onclick()">&nbsp;&nbsp;
                <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close()">
            </TD>
        </TR>
    </table>
</form>
</body>
<?
function TimeChange($APPLY_J,$TIME_ARRAY)
{
    $NUM=count($TIME_ARRAY);
    for($I=0;$I<$NUM;$I++)
    {
        if($I==$APPLY_J)
            $APPLY_J_NAME=$TIME_ARRAY[$I];
    }
    return $APPLY_J_NAME;
}
?>
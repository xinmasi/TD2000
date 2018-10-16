<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
//-- 合法性检验 --
for($I=1;$I<=$ITEM_COUNT;$I++)
{
    $NAME=$I."NAME";
    $MIN=$I."MIN";
    $MAX=$I."MAX";
    $value="value".$I;

    if($$value!="")
        if(!is_decimal($$value))
        {
            $MSG1 = sprintf(_("输入的 %s 格式不对，应为数字类型"),$$NAME);
            Message(_("错误"),$MSG1);

            $URL="self_assessment.php?RECALL=1&GROUP_ID=$GROUP_ID&FLOW_ID=$FLOW_ID&USER_ID=$PARTICIPANT&OPERATION=$OPERATION";
            for($I=1;$I<=$ITEM_COUNT;$I++)
            {
                $value="value".$I;
                $URL.="&".$value."=".$$value;
                $MEMO=$I."MEMO";
                $URL.="&".$MEMO."=".$$MEMO;
            }

?>
            <br>
            <div align="center">
                <input type="button" value="<?=_("返回")?>" class="BigButton" name="button" onClick="location='<?=$URL?>'">
            </div>

<?
            exit;
        }
        else
        {
            if($$value>$$MAX or $$MIN>$$value)
            {
                $MSG2 = sprintf(_("输入的 %s 范围不对，应为%.1f ～ %.1f"),$$NAME,$$MIN,$$MAX);
                Message(_("错误"),$MSG2);
                $URL="self_assessment.php?RECALL=1&GROUP_ID=$GROUP_ID&FLOW_ID=$FLOW_ID&USER_ID=$PARTICIPANT&OPERATION=$OPERATION";
                for($I=1;$I<=$ITEM_COUNT;$I++)
                {
                    $value="value".$I;
                    $URL.="&".$value."=".$$value;
                    $MEMO=$I."MEMO";
                    $URL.="&".$MEMO."=".$$MEMO;
                }

 ?>
            <br>
            <div align="center">
                <input type="button" value="<?=_("返回")?>" class="BigButton" name="button" onClick="location='<?=$URL?>'">
            </div>

 <?
                exit;
            }
        }
}

//-- 保存 --
if($OPERATION==1)
{
    for($I=1;$I<=$ITEM_COUNT;$I++)
    {
        $value="value".$I;
        $SCORE.=$$value;
        $SCORE.=",";
        $MEMO=$I."MEMO";
        $MEMO_DATA.=$$MEMO;
        $MEMO_DATA.=",";
    }
    $RANK_DATE=date("Y-m-d",time());
    $query="insert into SCORE_SELF_DATA (FLOW_ID,PARTICIPANT,SCORE,RANK_DATE,MEMO) values ('$FLOW_ID','".$_SESSION["LOGIN_USER_ID"]."','$SCORE','$RANK_DATE','$MEMO_DATA')";
}
else
{
    for($I=1;$I<=$ITEM_COUNT;$I++)
    {
        $value="value".$I;
        $SCORE.=$$value;
        $SCORE.=",";
        $MEMO=$I."MEMO";
        $MEMO_DATA.=$$MEMO;
        $MEMO_DATA.=",";
    }
    $RANK_DATE=date("Y-m-d",time());
    $query="update SCORE_SELF_DATA set SCORE='$SCORE',RANK_DATE='$RANK_DATE',MEMO='$MEMO_DATA' where FLOW_ID='$FLOW_ID' and PARTICIPANT='".$_SESSION["LOGIN_USER_ID"]."'";

}
exequery(TD::conn(),$query);

$query1="select USER_NAME from USER where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
    $USER_NAME=$ROW["USER_NAME"];
header("index1.php?FLOW_ID=$FLOW_ID");
$MSG3 = sprintf(_("%s 的自评数据已提交"),$USER_NAME);
Message(_("提示"),$MSG3);
Button_Back();
?>

<br>
</body>
</html>
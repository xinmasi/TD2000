<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");

$query="SELECT FLOW_TITLE from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $FLOW_TITLE=$ROW["FLOW_TITLE"];
$FLOW_TITLE=_("考核结果")."-".$FLOW_TITLE;
?>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from SCORE_DATE where FLOW_ID='$FLOW_ID' group by PARTICIPANT";
$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{

    $USER_ID[$VOTE_COUNT]=$ROW["PARTICIPANT"];
    $query1="SELECT `USER_ID`,`USER_NAME` FROM `USER` where USER_ID='$USER_ID[$VOTE_COUNT]'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $USER_NAME[$VOTE_COUNT]=$ROW["USER_NAME"];
    $VOTE_COUNT++;

}
if($VOTE_COUNT < 1)
{
    Message(_("提示"), _("发送失败：暂时没有考核数据"));
    Button_Back();
    exit;
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

        //echo $SCORE."<br>";

        $MY_SCORE[$I][$COUNT]=explode(",",$SCORE);
        $MY_MEMO[$I][$COUNT]=explode(",",$MEMO);
        $RANK_NAME[$I][$COUNT]=$ROW["USER_NAME"];
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

$CONTENT_HRED="<table width=\"100%\" class=\"TableList\"><thead class=\"TableHeader\"><td nowrap align=\"center\">"._("姓名")."</td>";
$ARRAY_COUNT=sizeof($ITEM_NAME);
for($I=0;$I<$ARRAY_COUNT;$I++)
    $CONTENT_HRED.=" <td nowrap align=\"center\">$ITEM_NAME[$I]</td>";
$CONTENT_HRED.="<td nowrap align=\"center\">"._("总计")."</td></thead>";

$ARRAY_COUNT=sizeof($USER_NAME);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $TOTAL=0;
    $CONTENT= $CONTENT_HRED."<tr class=\"TableLine1\"><td align=\"center\">$USER_NAME[$I]</td>";

    $ARRAY_COUNT1=sizeof($MY_AVE[$I]);
    $colnumber=$ARRAY_COUNT1+4;
    $SCORE_STR="";
    for($count=0;$count<$ARRAY_COUNT1;$count++)
    {
        $TOTAL=$TOTAL+$MY_AVE[$I][$count];
        $SCORE_STR.=$MY_AVE[$I][$count].",";
        $CONTENT .= "<td align=\"center\">".$MY_AVE[$I][$count]."</td>";
    }
    $SCORE_STR.=$TOTAL;
    $CONTENT .= "<td nowrap align=\"center\">".$TOTAL."</td></tr></table>";

    $SEND_TIME=time();
    $query="INSERT
         INTO EMAIL_BODY (FROM_ID,TO_ID2,COPY_TO_ID,SUBJECT,CONTENT,SEND_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,SEND_FLAG,IMPORTANT)
         SELECT '".$_SESSION["LOGIN_USER_ID"]."' as FROM_ID,'$USER_ID[$I],' as TO_ID2,'' as COPY_TO_ID ,'$FLOW_TITLE' as SUBJECT,'$CONTENT' as CONTENT,
         '$SEND_TIME' as SEND_TIME,'' as ATTACHMENT_ID,'' as ATTACHMENT_NAME,'1' as SEND_FLAG,'0' as IMPORTANT
         from USER where USER_ID='$USER_ID[$I]'";
    exequery(TD::conn(),$query);
    $BODY_ID=mysql_insert_id();

    $query="INSERT INTO EMAIL(TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID) values('$USER_ID[$I]','0','0','0','$BODY_ID')";
    exequery(TD::conn(),$query);
    $ROW_ID=mysql_insert_id();

    $REMIND_URL="email/inbox/read_email/?BOX_ID=0&EMAIL_ID=".$ROW_ID;
    $SMS_CONTENT=_("请查收邮件！")."\n"._("主题：考核结果");
    send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID[$I],15,$SMS_CONTENT,$REMIND_URL,$ROW_ID);
}

Message(_("提示"),_("发送成功"));
Button_Back();
?>



<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$CUR_DATE=date("Y-m-d",time());

//============================考核人员名称、部门、角色=======================================
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from SCORE_DATE where FLOW_ID='$FLOW_ID' group by PARTICIPANT";

$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $USER_ID[$VOTE_COUNT]=$ROW["PARTICIPANT"];
    $query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN user_priv c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$USER_ID[$VOTE_COUNT]'";
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
    $VOTE_COUNT++;
}

//===========================考核分数==================================
$ARRAY_COUNT=sizeof($USER_ID);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $query1 = "select SCORE from SCORE_DATE  where PARTICIPANT='$USER_ID[$I]' and FLOW_ID='$FLOW_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    //echo $USER_ID[$I]."<br>";
    $COUNT=0;
    while($ROW=mysql_fetch_array($cursor1))
    {
        $SCORE=$ROW["SCORE"];
        //echo $SCORE."<br>";
        $MY_SCORE[$I][$COUNT]=explode(",",$SCORE);
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
        {$MY_AVE[$I][$count]=0;}
        else
        {$MY_AVE[$I][$count]=round($MY_SCORESAM[$I][$count]/$MY_SCORECOUNT[$I][$count],2);}
    }
}
if(MYOA_IS_UN == 1)
{
    $EXCEL_OUT.="DEPTNAME,";
    $EXCEL_OUT.="NAME,";
    $EXCEL_OUT.="ROLE,";
}
else
{
    $EXCEL_OUT.=_("部门").",";
    $EXCEL_OUT.=_("姓名").",";
    $EXCEL_OUT.=_("角色").",";
}
$ARRAY_COUNT=sizeof($ITEM_NAME);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $EXCEL_OUT.=format_cvs($ITEM_NAME[$I]).",";
}
if(MYOA_IS_UN == 1)
    $EXCEL_OUT.="TOTAL,";
else
    $EXCEL_OUT.=_("总分");

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
if(strlen($FLOW_TITLE) < 25)
    $objExcel->setFileName(_("考核报表")."-".$FLOW_TITLE);
else
    $objExcel->setFileName(_("考核报表"));
$objExcel->addHead($EXCEL_OUT);

$ARRAY_COUNT=sizeof($USER_NAME);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $TOTAL=0;
    $EXCEL_OUT="";
    $EXCEL_OUT.=$USER_DEPT[$I].",";
    $EXCEL_OUT.=$USER_NAME[$I].",";
    $EXCEL_OUT.=$USER_PRIV[$I].",";
    $ARRAY_COUNT1=sizeof($MY_AVE[$I]);
    for($count=0;$count<$ARRAY_COUNT1;$count++)
    {
        $TOTAL=$TOTAL+$MY_AVE[$I][$count];
        $EXCEL_OUT.=$MY_AVE[$I][$count].",";
    }
    $EXCEL_OUT.=$TOTAL.",";
    $objExcel->addRow($EXCEL_OUT);
}

$objExcel->Save();
?>
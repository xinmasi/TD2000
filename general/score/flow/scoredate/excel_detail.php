<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$CUR_DATE=date("Y-m-d",time());

$query = "SELECT ANONYMITY from SCORE_FLOW  where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $ANONYMITY=$ROW["ANONYMITY"];
}


//============================������Ŀ========================================
$query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $ITEM_NAME[$VOTE_COUNT]=$ROW["ITEM_NAME"];
    $VOTE_COUNT++;
}
if(MYOA_IS_UN == 1)
{
    $EXCEL_OUT.="DEPTNAME,";
    $EXCEL_OUT.="RATINGS,";
    $EXCEL_OUT.="ROLE,";
    $EXCEL_OUT.="ASSESSED,";
}
else
{
    $EXCEL_OUT.=_("����").",";
    $EXCEL_OUT.=_("������").",";
    $EXCEL_OUT.=_("��ɫ").",";
    $EXCEL_OUT.=_("��������").",";
}

$ARRAY_COUNT=sizeof($ITEM_NAME);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $EXCEL_OUT.=format_cvs($ITEM_NAME[$I]).",";
}
ob_end_clean();
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("���˷�����ϸ����")."-".$FLOW_TITLE);
$objExcel->addHead($EXCEL_OUT);

//===========================���˷���,���������ơ����š���ɫ==================================
$query1 = "select e.USER_NAME as PARTICIPANT,a.MEMO,a.SCORE,b.USER_NAME as USER_NAME,PRIV_NAME,d.DEPT_NAME
           FROM SCORE_DATE a LEFT OUTER JOIN USER b ON a.RANKMAN = b.USER_ID
                             LEFT OUTER JOIN USER_PRIV c ON b.USER_PRIV = c.USER_PRIV
                             LEFT OUTER JOIN DEPARTMENT d ON d.DEPT_ID = b.DEPT_ID
                             LEFT OUTER JOIN USER e ON a.PARTICIPANT = e.USER_ID
                             where a.FLOW_ID='$FLOW_ID'";
$cursor1= exequery(TD::conn(),$query1);
//echo $USER_ID[$I]."<br>";
$COUNT=0;
while($ROW=mysql_fetch_array($cursor1))
{
    $SCORE=$ROW["SCORE"];
    $MEMO=$ROW["MEMO"];
    $PARTICIPANT=$ROW["PARTICIPANT"];

    $EXCEL_OUT = "";
    if($ANONYMITY=="0")
    {
        $EXCEL_OUT.=$ROW["DEPT_NAME"].",";
        $EXCEL_OUT.=$ROW["USER_NAME"].",";
        $EXCEL_OUT.=$ROW["PRIV_NAME"].",";
    }
    else
    {
        $EXCEL_OUT.="****".",";
        $EXCEL_OUT.="****".",";
        $EXCEL_OUT.="****".",";
    }

    $EXCEL_OUT.=$ROW["PARTICIPANT"].",";

    $MY_SCORE=explode(",",$SCORE);
    $ARRAY_COUNT1=sizeof($MY_SCORE);
    $MY_MEMO=explode(",",$MEMO);
    for($I=0;$I< $ARRAY_COUNT1;$I++)
    {
        if($MY_MEMO[$I]=="")
            $EXCEL_OUT.=$MY_SCORE[$I].",";
        else
            $EXCEL_OUT.=$MY_SCORE[$I]."("._("��ע:").$MY_MEMO[$I].")".",";
    }
    $objExcel->addRow($EXCEL_OUT);
}

$objExcel->Save();
?>
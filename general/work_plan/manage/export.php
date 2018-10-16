<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$CUR_DATE=date("Y-m-d",time());

$EXCEL_OUT=array(_("�ƻ�����"),_("�ƻ�����"),_("��ʼʱ��"),_("����ʱ��"),_("�ƻ����"),_("���Ų���"),_("������Ա"),_("������"),_("������"),_("������"),_("��������"),_("״̬"),_("��ע"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("�����ƻ�"));
$objExcel->addHead($EXCEL_OUT);

$query5 = "SELECT * from WORK_PLAN".str_replace("`","'",$CONDITION_STR1)." order by CREATE_DATE desc";
$cursor5= exequery(TD::conn(),$query5);
while($ROW=mysql_fetch_array($cursor5))
{
    $PLAN_ID=$ROW["PLAN_ID"];
    $NAME=$ROW["NAME"];
    $CONTENT=$ROW["CONTENT"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $TYPE=$ROW["TYPE"];
    $TO_ID=$ROW["TO_ID"];
    $MANAGER=$ROW["MANAGER"];
    $PARTICIPATOR=$ROW["PARTICIPATOR"];
    $CREATOR=$ROW["CREATOR"];
    $CREATE_DATE=$ROW["CREATE_DATE"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $ATTACHMENT_COMMENT=$ROW["ATTACHMENT_COMMENT"];
    $REMARK=$ROW["REMARK"];
    $SUSPEND_FLAG=$ROW["SUSPEND_FLAG"];
    $TO_PERSON_ID=$ROW["TO_PERSON_ID"];

    $CONTENT = str_replace("\n","<br>",$CONTENT);
    $CONTENT = strip_tags($CONTENT);
    $CONTENT = str_replace("&nbsp;","  ",$CONTENT);

    $query1 = "SELECT * from PLAN_TYPE where TYPE_ID='$TYPE'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
        $TYPE_DESC=$ROW1["TYPE_NAME"];
    else
        $TYPE_DESC="";

    if($TO_ID=="ALL_DEPT")
        $TO_NAME=_("ȫ�岿��");
    else
    {
        $TO_NAME="";
        $TOK=strtok($TO_ID,",");
        while($TOK!="")
        {
            if($TO_NAME!="")
                $TO_NAME.=_("��");
            $TOK=intval($TOK);
            $query1="select * from DEPARTMENT where DEPT_ID='$TOK'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
                $TO_NAME.=$ROW["DEPT_NAME"];

            $TOK=strtok(",");
        }
    }

    $TO_PERSON_NAME="";
    $TOK=strtok($TO_PERSON_ID,",");
    while($TOK!="")
    {
        if($TO_PERSON_NAME!="")
            $TO_PERSON_NAME.=_("��");
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
        {
            $DEPT_ID=$ROW1["DEPT_ID"];
            $DEPT_NAME=dept_long_name($DEPT_ID);
            $TO_PERSON_NAME.=$ROW1["USER_NAME"];
        }

        $TOK=strtok(",");
    }

    $PARTICIPATOR_NAME="";
    $TOK=strtok($PARTICIPATOR,",");
    while($TOK!="")
    {
        if($PARTICIPATOR_NAME!="")
            $PARTICIPATOR_NAME.=_("��");
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $DEPT_ID=$ROW["DEPT_ID"];
            $DEPT_NAME=dept_long_name($DEPT_ID);
            $PARTICIPATOR_NAME.=$ROW["USER_NAME"];
        }

        $TOK=strtok(",");
    }
    //$PARTICIPATOR_NAME=substr($PARTICIPATOR_NAME,0,-1);

    $MANAGE_NAME="";
    $TOK=strtok($MANAGER,",");
    while($TOK!="")
    {
        if($MANAGE_NAME!="")
            $MANAGE_NAME.=_("��");
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $DEPT_ID=$ROW["DEPT_ID"];
            $DEPT_NAME=dept_long_name($DEPT_ID);
            $MANAGE_NAME.=$ROW["USER_NAME"];
        }

        $TOK=strtok(",");
    }
    //$MANAGE_NAME=substr($MANAGE_NAME,0,-1);

    $query1="select * from USER where USER_ID='$CREATOR'";
    $cursor= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor))
    {
        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_NAME=dept_long_name($DEPT_ID);
        $CREATOR_NAME=$ROW["USER_NAME"];
    }
    // $CREATOR_NAME=substr($CREATOR_NAME,0,-1);

    if($SUSPEND_FLAG==1)
    {
        if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
            $STATUS_DESC=_("δ��ʼ");
        else
            $STATUS_DESC=_("������");

        if($END_DATE!="0000-00-00")
        {
            if(compare_date($CUR_DATE,$END_DATE)>=0)
                $STATUS_DESC=_("�ѽ���");
        }
        else
            $END_DATE="";
    }
    else
        $STATUS_DESC=_("��ͣ");

    $EXCEL_OUT="$NAME,$CONTENT,$BEGIN_DATE,$END_DATE,$TYPE_DESC,$TO_NAME,$TO_PERSON_NAME,$MANAGE_NAME,$PARTICIPATOR_NAME,$CREATOR_NAME,$CREATE_DATE,$STATUS_DESC,$REMARK";
    $objExcel->addRow($EXCEL_OUT);
}

$objExcel->Save();
?>
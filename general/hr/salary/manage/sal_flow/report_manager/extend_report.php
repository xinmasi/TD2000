<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$query = "SELECT count(*) from SAL_ITEM";
$ITEM_COUNT=0;
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $ITEM_COUNT=$ROW[0];
if($ITEM_COUNT==0)
    exit;

// $FLOW_ID = intval($FLOW_ID);
// $query = "SELECT * from SAL_FLOW where FLOW_ID='$FLOW_ID'";
// $cursor= exequery(TD::conn(),$query);
// if($ROW=mysql_fetch_array($cursor))
//    $CONTENT=$ROW["CONTENT"];
$FLOW_COUNT=0;
$EXCEL_OUT.=_("部门").",";
$EXCEL_OUT_UN.="DEPTNAME,";
$EXCEL_OUT.=_("姓名").",";
$EXCEL_OUT_UN.="NAME,";
$EXCEL_OUT.=_("角色").",";
$EXCEL_OUT_UN.="ROLE,";
if($fld_str=="")
{
    $query = "SELECT ITEM_ID from SAL_ITEM";
    $cursor= exequery(TD::conn(),$query);
    $FLOW_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $STYLE.="S".$ROW["ITEM_ID"].",";
    }
    $STYLE.="ALL_BASE,PENSION_BASE,PENSION_U,PENSION_P,MEDICAL_BASE,MEDICAL_U,MEDICAL_P,FERTILITY_BASE,FERTILITY_U,UNEMPLOYMENT_BASE,UNEMPLOYMENT_U,UNEMPLOYMENT_P,INJURIES_BASE,INJURIES_U,HOUSING_BASE,HOUSING_U,HOUSING_P,INSURANCE_DATE,BANK1,BANK_ACCOUNT1,BANK2,BANK_ACCOUNT2,";

    $STYLE.="MEMO";
}
else
{
    //去掉多于的","
    $STYLE=substr($fld_str,0,-1);
}

$STYLE_ARRAY=explode(",",$STYLE);
$ARRAY_COUNT=sizeof($STYLE_ARRAY);
$COUNT=0;
if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
for($I=0;$I < $ARRAY_COUNT;$I++)
{
    if(substr($STYLE_ARRAY[$I],0,1)=="S")
    {
        $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='".substr($STYLE_ARRAY[$I],1)."'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $ITEM_NAME=$ROW["ITEM_NAME"];
            $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
        }
        $COUNT++;
        $EXCEL_OUT.=$ITEM_NAME.",";
    }
    else
    {
        $EXCEL_OUT_UN.=$STYLE_ARRAY[$I].",";
        if($STYLE_ARRAY[$I]=="ALL_BASE")
        {
            $COUNT++;
            $EXCEL_OUT.=_("保险基数").",";
        }
        if($STYLE_ARRAY[$I]=="PENSION_BASE")
        {
            $COUNT++;
            $EXCEL_OUT.=_("养老保险").",";
        }
        if($STYLE_ARRAY[$I]=="PENSION_U")
        {
            $COUNT++;
            $EXCEL_OUT.=_("单位养老").",";
        }
        if($STYLE_ARRAY[$I]=="PENSION_P")
        {
            $COUNT++;
            $EXCEL_OUT.=_("个人养老").",";
        }
        if($STYLE_ARRAY[$I]=="MEDICAL_BASE")
        {
            $COUNT++;
            $EXCEL_OUT.=_("医疗保险").",";
        }
        if($STYLE_ARRAY[$I]=="MEDICAL_U")
        {
            $COUNT++;
            $EXCEL_OUT.=_("单位医疗").",";
        }
        if($STYLE_ARRAY[$I]=="MEDICAL_P")
        {
            $COUNT++;
            $EXCEL_OUT.=_("个人医疗").",";
        }
        if($STYLE_ARRAY[$I]=="FERTILITY_BASE")
        {
            $COUNT++;
            $EXCEL_OUT.=_("生育保险").",";
        }
        if($STYLE_ARRAY[$I]=="FERTILITY_U")
        {  $COUNT++;
            $EXCEL_OUT.=_("单位生育").",";
        }
        if($STYLE_ARRAY[$I]=="UNEMPLOYMENT_BASE")
        {
            $COUNT++;
            $EXCEL_OUT.=_("失业保险").",";
        }
        if($STYLE_ARRAY[$I]=="UNEMPLOYMENT_U")
        {  $COUNT++;
            $EXCEL_OUT.=_("单位失业").",";
        }
        if($STYLE_ARRAY[$I]=="UNEMPLOYMENT_P")
        {
            $COUNT++;
            $EXCEL_OUT.=_("个人失业").",";
        }
        if($STYLE_ARRAY[$I]=="INJURIES_BASE")
        {
            $COUNT++;
            $EXCEL_OUT.=_("工伤保险").",";
        }
        if($STYLE_ARRAY[$I]=="INJURIES_U")
        {
            $COUNT++;
            $EXCEL_OUT.=_("单位工伤").",";
        }
        if($STYLE_ARRAY[$I]=="HOUSING_BASE")
        {  $COUNT++;
            $EXCEL_OUT.=_("住房公积金").",";
        }
        if($STYLE_ARRAY[$I]=="HOUSING_U")
        {
            $COUNT++;
            $EXCEL_OUT.=_("单位住房").",";
        }
        if($STYLE_ARRAY[$I]=="HOUSING_P")
        {
            $COUNT++;
            $EXCEL_OUT.=_("个人住房").",";
        }
        if($STYLE_ARRAY[$I]=="INSURANCE_DATE")
        {
            $COUNT++;
            $EXCEL_OUT.=_("投保日期").",";
        }
        if($STYLE_ARRAY[$I]=="BANK1")
        {
            $COUNT++;
            $EXCEL_OUT.=_("开户行1").",";
        }
        if($STYLE_ARRAY[$I]=="BANK_ACCOUNT1")
        {
            $COUNT++;
            $EXCEL_OUT.=_("个人账户1").",";
        }
        if($STYLE_ARRAY[$I]=="BANK2")
        {
            $COUNT++;
            $EXCEL_OUT.=_("开户行2").",";
        }
        if($STYLE_ARRAY[$I]=="BANK_ACCOUNT2")
        {
            $COUNT++;
            $EXCEL_OUT.=_("个人账户2").",";
        }
    }

    if($STYLE_ARRAY[$I]=="MEMO")
    {
        $COUNT++;
        $EXCEL_OUT.=_("备注").",";
    }
}//end for
$EXCEL_OUT.="\n";

if(MYOA_IS_UN == 1)
    $EXCEL_OUT=$EXCEL_OUT_UN;
else
    $EXCEL_OUT=$EXCEL_OUT;

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("跨流程报表"));
$objExcel->addHead($EXCEL_OUT);

if($COPY_TO_ID!="")
{
    $COPY_TO_ID="'".str_replace(",","','",substr($COPY_TO_ID,0,-1))."'";
    $WHERE_STR.=" and USER.USER_ID in ($COPY_TO_ID)";
}
if($TOID!="" and $TOID!="ALL_DEPT")
{
    $TOID="'".str_replace(",","','",substr($TOID,0,-1))."'";
    $WHERE_STR.=" and DEPARTMENT.DEPT_ID in ($TOID)";
}
if($PRIV_ID!="")
{
    $PRIV_ID="'".str_replace(",","','",substr($PRIV_ID,0,-1))."'";
    $WHERE_STR.=" and  USER.USER_PRIV in ($PRIV_ID)";
}

$START_DATE_OLD=$START_DATE." 00:00:00";
$END_DATE_OLD=$END_DATE." 23:59:59";
if($START_DATE_OLD!="")
    $CONDITION_STR.=" and SEND_TIME>='$START_DATE_OLD'";
if($END_DATE_OLD!="")
    $CONDITION_STR.=" and SEND_TIME<='$END_DATE_OLD'";

$query2="select * from SAL_FLOW where 1=1".$CONDITION_STR;
$cursor2= exequery(TD::conn(),$query2);
while($ROW=mysql_fetch_array($cursor2))
{
    $FLOW_ID_OLD=$ROW["FLOW_ID"];
    if($DEPT_FLAG!=1)
    {
        $query = "SELECT * from USER,USER_PRIV,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV".$WHERE_STR." order by DEPT_NO,PRIV_NO,USER_NAME";
    }
    else
    {
        $query = "SELECT * from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and DEPT_ID=0";
    }
    $cursor= exequery(TD::conn(),$query);
    $USER_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $USER_COUNT++;
        $ROW_OUT="";
        $USER_ID=$ROW["USER_ID"];
        $USER_NAME=$ROW["USER_NAME"];
        $DEPT_ID=$ROW["DEPT_ID"];
        $USER_PRIV=$ROW["USER_PRIV"];

        if($DEPT_ID==0)
            $DEPT_NAME=_("离职人员/外部人员");
        else
        {
            $DEPT_ID = intval($DEPT_ID);
            $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
                $DEPT_NAME=$ROW["DEPT_NAME"];
        }
        $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
            $USER_PRIV=$ROW["PRIV_NAME"];

        $ROW_OUT.=format_cvs($DEPT_NAME).",";
        $ROW_OUT.=format_cvs($USER_NAME).",";
        $ROW_OUT.=format_cvs($USER_PRIV).",";
        $FLOW_ID_OLD = intval($FLOW_ID_OLD);
        $query9="select * from SAL_DATA,HR_STAFF_INFO where SAL_DATA.USER_ID = HR_STAFF_INFO.USER_ID and FLOW_ID='$FLOW_ID_OLD' and HR_STAFF_INFO.USER_ID='$USER_ID'";
        $cursor9= exequery(TD::conn(),$query9);
        if($ROW9=mysql_fetch_array($cursor9))
        {
            for($I=0; $I<$COUNT; $I++)
            {
                $STR=$STYLE_ARRAY[$I];
                if($STR!="MEMO" && $STR!="BANK1" && $STR!="BANK_ACCOUNT1" && $STR!="BANK2" && $STR!="BANK_ACCOUNT2")
                    $$STR=format_money($ROW9[$STR]);
                else if($STR=="BANK1")
                    $$STR=$ROW9["BANK1"];
                else if($STR=="BANK_ACCOUNT1")
                    $$STR=$ROW9["BANK_ACCOUNT1"];
                else if($STR=="BANK2")
                    $$STR=$ROW9["BANK2"];
                else
                    $$STR=$ROW9["BANK_ACCOUNT2"];
            }
        }
        else
        {
            for($I=0; $I<$COUNT; $I++)
            {
                $STR=$STYLE_ARRAY[$I];
                $$STR="";
            }
        }
        for($I=0; $I<$COUNT; $I++)
        {
            $STR=$STYLE_ARRAY[$I];
            $STR_COUNT=$STR."_COUNT";
            $$STR_COUNT+=$$STR;
            $ROW_OUT.=$$STR.",";
        }
        $objExcel->addRow($ROW_OUT);
    }
}//end while
$ROW_OUT="";
if($USER_COUNT!=0)
{
    $ROW_OUT.=_("合计").",,,";
    for($I=0; $I<$COUNT; $I++)
    {
        $STR=$STYLE_ARRAY[$I];
        $STR_COUNT=$STR."_COUNT";
        $DATA=format_money($$STR_COUNT);
        $ROW_OUT.=$DATA.",";
    }
    $objExcel->addRow($ROW_OUT);
}
$objExcel->Save();
?>
<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="select * from SAL_ITEM where ISREPORT='1' OR ITEM_NAME='计算项' order by ITEM_ID";
$cursor= exequery(TD::conn(),$query);
$ITEM_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $ITEMARRY[$ITEM_COUNT]=$ROW["ITEM_ID"];
    $ITEM_COUNT++;
}

//-- 合法性检验 --
for($I=0;$I< $ITEM_COUNT;$I++)
{
    $STR="S".$ITEMARRY[$I];
    $STR1="S".$ITEMARRY[$I]."_NAME";

    if($$STR!="")
        if(!is_number($$STR) && !is_money($$STR) )
        {
            Message(_("错误"),_("输入的金额格式不对，应形如 123 或 123.45"));

            $URL="sal_data.php?RECALL=1&FLOW_ID=$FLOW_ID&USER_ID=$USER_ID&USER_NAME=$USER_NAME&OPERATION=$OPERATION";
            for($I=0;$I< $ITEM_COUNT;$I++)
            {
                $STR="S".$ITEMARRY[$I];
                $URL.="&".$STR."=".$$STR;
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

//-- 保存 --
$query1="select * from HR_SAL_DATA where USER_ID='$USER_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
{
    $ALL_BASE = $ROW1["ALL_BASE"];
    $PENSION_BASE = $ROW1["PENSION_BASE"];
    $PENSION_U = $ROW1["PENSION_U"];
    $PENSION_P = $ROW1["PENSION_P"];
    $MEDICAL_BASE = $ROW1["MEDICAL_BASE"];
    $MEDICAL_U = $ROW1["MEDICAL_U"];
    $MEDICAL_P = $ROW1["MEDICAL_P"];
    $FERTILITY_BASE = $ROW1["FERTILITY_BASE"];
    $FERTILITY_U = $ROW1["FERTILITY_U"];
    $UNEMPLOYMENT_BASE = $ROW1["UNEMPLOYMENT_BASE"];
    $UNEMPLOYMENT_U = $ROW1["UNEMPLOYMENT_U"];
    $UNEMPLOYMENT_P = $ROW1["UNEMPLOYMENT_P"];
    $INJURIES_BASE = $ROW1["INJURIES_BASE"];
    $INJURIES_U = $ROW1["INJURIES_U"];
    $HOUSING_BASE = $ROW1["HOUSING_BASE"];
    $HOUSING_U = $ROW1["HOUSING_U"];
    $HOUSING_P = $ROW1["HOUSING_P"];
    $YES_OTHER = $ROW1["YES_OTHER"];
}
$query3 = "SELECT ITEM_ID from sal_item where ITEM_NAME='税前工资';";
$cursor3= exequery(TD::conn(),$query3);
if($ROW3=mysql_fetch_array($cursor3))
{
    $SAL_DATA_NUMBER="S".$ROW3["ITEM_ID"];
}
if($OPERATION==1)
{
    $query="insert into SAL_DATA(FLOW_ID,USER_ID,MEMO,IS_DEPT_INPUT,IS_FINA_INPUT";
    for($I=0;$I< $ITEM_COUNT;$I++)
        $query.=",S".$ITEMARRY[$I];
    $query.=",ALL_BASE,PENSION_BASE,PENSION_U,PENSION_P,MEDICAL_BASE,MEDICAL_U,MEDICAL_P,FERTILITY_BASE,FERTILITY_U,UNEMPLOYMENT_BASE,UNEMPLOYMENT_U,UNEMPLOYMENT_P,INJURIES_BASE,INJURIES_U,HOUSING_BASE,HOUSING_U,HOUSING_P,INSURANCE_OTHER,INSURANCE_DATE,REPORT";
    $query.=") values ($FLOW_ID,'$USER_ID','$MEMO','1','1',";
    for($I=0;$I< $ITEM_COUNT;$I++)
    {
        $STR="S".$ITEMARRY[$I];
        if($$STR=="")
            $$STR="0";
        $query.=$$STR;
        if($I!=$ITEM_COUNT-1)
            $query.=",";
    }
    if(substr($query,-1)==",")
    {
        $query.= "'".$ALL_BASE."',";
    }
    else
    {
        $query.= ",'".$ALL_BASE."',";
    }

    $query.= "'".$PENSION_BASE."',";
    $query.= "'".$PENSION_U."',";
    $query.= "'".$PENSION_P."',";
    $query.= "'".$MEDICAL_BASE."',";
    $query.= "'".$MEDICAL_U."',";
    $query.= "'".$MEDICAL_P."',";
    $query.= "'".$FERTILITY_BASE."',";
    $query.= "'".$FERTILITY_U."',";
    $query.= "'".$UNEMPLOYMENT_BASE."',";
    $query.= "'".$UNEMPLOYMENT_U."',";
    $query.= "'".$UNEMPLOYMENT_P."',";
    $query.= "'".$INJURIES_BASE."',";
    $query.= "'".$INJURIES_U."',";
    $query.= "'".$HOUSING_BASE."',";
    $query.= "'".$HOUSING_U."',";
    $query.= "'".$HOUSING_P."',";
    $query.= "'".$INSURANCE_OTHER."',";
    $query.= "'".$INSURANCE_DATE."',";
    $query.= "'".$$SAL_DATA_NUMBER."'";
    $query.=")";
}
else
{
    $query="update SAL_DATA set ";
    for($I=0;$I< $ITEM_COUNT;$I++)
    {
        $STR="S".$ITEMARRY[$I];
        if($$STR=="")
            $$STR="0";

        $query.=$STR."=".$$STR;

        if($I!=$ITEM_COUNT-1)
            $query.=",";
    }
    if($ITEM_COUNT>0)
        $query.=",MEMO='$MEMO',";
    $query.= "ALL_BASE='".$PENSION_BASE."',";
    $query.= "PENSION_BASE='".$PENSION_BASE."',";
    $query.= "PENSION_U='".$PENSION_U."',";
    $query.= "PENSION_P='".$PENSION_P."',";
    $query.= "MEDICAL_BASE='".$MEDICAL_BASE."',";
    $query.= "MEDICAL_U='".$MEDICAL_U."',";
    $query.= "MEDICAL_P='".$MEDICAL_P."',";
    $query.= "FERTILITY_BASE='".$FERTILITY_BASE."',";
    $query.= "FERTILITY_U='".$FERTILITY_U."',";
    $query.= "UNEMPLOYMENT_BASE='".$UNEMPLOYMENT_BASE."',";
    $query.= "UNEMPLOYMENT_U='".$UNEMPLOYMENT_U."',";
    $query.= "UNEMPLOYMENT_P='".$UNEMPLOYMENT_P."',";
    $query.= "INJURIES_BASE='".$INJURIES_BASE."',";
    $query.= "INJURIES_U='".$INJURIES_U."',";
    $query.= "HOUSING_BASE='".$HOUSING_BASE."',";
    $query.= "HOUSING_U='".$HOUSING_U."',";
    $query.= "HOUSING_P='".$HOUSING_P."',";
    $query.= "INSURANCE_OTHER='".$INSURANCE_OTHER."',";
    $query.= "IS_DEPT_INPUT='1',";
    $query.= "REPORT='".$$SAL_DATA_NUMBER."'";
    $query.=" where FLOW_ID=$FLOW_ID and USER_ID='$USER_ID'";
}
exequery(TD::conn(),$query);
Message(_("提示"),sprintf(_("员工 %s 的工资数据已上报%s请继续选择其他员工"), $USER_NAME, "<br><br>"));
?>

<br>
<div align="center">
    <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='sal_data.php?UID=<?=$UID?>&USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>&DEPT_ID=<?=$DEPT_ID?>'">&nbsp;
</div>


</body>
</html>

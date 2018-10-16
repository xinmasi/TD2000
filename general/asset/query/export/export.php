<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
ob_end_clean();

$query="select * from FIELDSETTING where TABLENAME='CP_CPTL_INFO'";
$cursor=exequery(TD::conn(),$query);
$COUNT='0';
while($ROW=mysql_fetch_array($cursor))
{
    $COUNT++;
    $FIELDNAME.=$ROW["FIELDNAME"].",";
    $FIELDNO.=$ROW["FIELDNO"].",";
    $ISQUERY=$ROW["ISQUERY "];
    $type1.="V".",";
    $colWidth1.="15".",";
}

$FIELDNAME = td_trim($FIELDNAME);
$FIELDNO   = td_trim($FIELDNO);

$thArr= array(_("资产编号"),_("资产名称"),_("资产类别"),_("所属部门"),_("资产性质"),_("增加类型"),_("资产原值"),_("残值率"),_("折旧年限"),_("累计折旧"),_("月折旧额"),_("启用日期"),_("保管人"),_("备注"));

$thArr1 = explode(",",_("$FIELDNAME"));
$thArr  = array_merge($thArr,$thArr1);

$fieldArr= array('CPTL_NO','CPTL_NAME','TYPE_NAME','DEPT_NAME','CPTL_KIND','PRCS_LONG_DESC','CPTL_VAL','CPTL_BAL','DPCT_YY','SUM_DPCT','MON_DPCT','FROM_YYMM','KEEPER','REMARK');
$fieldArr1 = explode(",",$FIELDNO);
$fieldArr  = array_merge($fieldArr,$fieldArr1);


if($condition == "all"){//所有行
    $LIMIT_CLAUSE = "";
}

$WHERE_CLAUSE = stripslashes(stripslashes($WHERE_CLAUSE));
if($condition != "query"){    //查询条件
    $WHERE_CLAUSE = " WHERE 1=1 ";
}else{
    $LIMIT_CLAUSE = "";
}

if($isSelected == "selected" && $fieldArrStr!="")//选择字段
{
    $thArrTmp     = array();
    $fieldArrTmp  = array();

    $fieldArrStr  = substr($fieldArrStr, 0, -1);
    $selected     = explode(",", $fieldArrStr);

    for($i=0;$i<count($selected);$i++)
    {
        for($k=0;$k<count($thArr);$k++)
        {
            if($selected[$i] == $fieldArr[$k])
            {
                $fieldArrTmp[]    = $fieldArr[$k];
                $thArrTmp[]        = $thArr[$k];
            }
        }
    }
    $fieldArr    = $fieldArrTmp;
    $thArr        = $thArrTmp;
    
    if(MYOA_IS_UN == 1)
        $OUTPUT_HEAD = $fieldArr;
    else
        $OUTPUT_HEAD = $thArr;   
}
else
{
    if(MYOA_IS_UN == 1)
        $OUTPUT_HEAD = $fieldArr;
    else
        $OUTPUT_HEAD = $thArr;
}
//获取部门缓存
$DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("固定资产"));
$objExcel->addHead($OUTPUT_HEAD);

$subject    = _("固定资产");

$LIST_CLAUSE = " CP_CPTL_INFO.*,
                 CP_PRCS_PROP.PRCS_LONG_DESC,
                 CP_ASSET_TYPE.TYPE_NAME";

$FROM_CLAUSE = " FROM CP_CPTL_INFO ";
$JOIN_CLAUSE = " LEFT OUTER JOIN CP_PRCS_PROP ON CP_PRCS_PROP.PRCS_ID = CP_CPTL_INFO.PRCS_ID 
                 LEFT OUTER JOIN CP_ASSET_TYPE ON CP_ASSET_TYPE.TYPE_ID = CP_CPTL_INFO.TYPE_ID";

$query = "SELECT ".$LIST_CLAUSE.$FROM_CLAUSE.$JOIN_CLAUSE.$WHERE_CLAUSE.$ORDER_CLAUSE.$LIMIT_CLAUSE;
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $CPTL_ID         = format_cvs($ROW["CPTL_ID"]);
    $CPTL_NO         = format_cvs($ROW["CPTL_NO"]);
    $CPTL_NAME       = format_cvs($ROW["CPTL_NAME"]);
    $DEPT_NAME       = format_cvs($DEPARTMENT_ARRAY[$ROW["DEPT_ID"]]["DEPT_NAME"]);
    $CPTL_KIND       = format_cvs($ROW["CPTL_KIND"]=="01"?"资产":"费用");
    $TYPE_NAME       = format_cvs($ROW["TYPE_NAME"]);
    $PRCS_LONG_DESC  = format_cvs($ROW["PRCS_LONG_DESC"]);
    $CPTL_VAL        = format_cvs($ROW["CPTL_VAL"]);
    $CPTL_BAL        = format_cvs($ROW["CPTL_BAL"]);
    $DPCT_YY         = format_cvs($ROW["DPCT_YY"]);
    $MON_DPCT        = format_cvs($ROW["MON_DPCT"]);
    $SUM_DPCT        = format_cvs($ROW["SUM_DPCT"]);
    $FROM_YYMM       = format_cvs($ROW["FROM_YYMM"]);
    $KEEPER          = format_cvs(substr(GetUserNameById($ROW["KEEPER"]),0,-1)!=""?substr(GetUserNameById($ROW["KEEPER"]),0,-1):$ROW["KEEPER"]);
    $REMARK          = format_cvs($ROW["REMARK"]);

    /*****自定义字段赋值*****/
    if($FIELDNAME!="")
    {
        $DEF_FIELD_ARRAY = array();
        $EXCEL_OUT       = array();
        $DEF_FIELD_ARRAY = get_field_text("CP_CPTL_INFO", $CPTL_ID);
    
        while(list($key, $value) = each($DEF_FIELD_ARRAY))
        {
            $EXCEL_OUT[$key] = $value["HTML"];
        }
        extract($EXCEL_OUT);
    }    
    $OUT_PUT="";
    foreach($fieldArr as $value)
        $OUT_PUT.=$$value.",";

    $objExcel->addRow("$OUT_PUT");    
}

$objExcel->Save();
?>
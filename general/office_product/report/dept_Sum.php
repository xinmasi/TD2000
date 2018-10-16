<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include("inc/FusionCharts/FusionCharts.php");

$HTML_PAGE_TITLE = _("部门领用汇总");


if($_SESSION["LOGIN_USER_PRIV"]!=1)
{
    $O_PROTYPE = "";
    $sql = "SELECT b.ID FROM office_depository AS a LEFT JOIN office_type AS b ON a.ID = b.TYPE_DEPOSITORY WHERE find_in_set('".$_SESSION["LOGIN_USER_ID"]."',a.MANAGER)";
    $cur = exequery(TD::conn(),$sql);
    while($arr=mysql_fetch_array($cur))
    {
        $O_PROTYPE .= $arr[0].",";
    }
    $O_PROTYPE = td_trim($O_PROTYPE);
    if($O_PROTYPE=="")
    {
        Message("",_("您没有查询权限！"));
        exit;
    }
}


//include_once("inc/header.inc.php");
?>
<!--
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/reportform.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script language="Javascript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>
<body>
-->
<div class="clearfix">
    <h4 class="pull-left"><?=_("部门领用汇总")?></h4>
    <div class="pull-right" >
        <input type="button" class="btn btn-small" value="<?=_("打印")?>" onClick="window.print();">&nbsp;&nbsp;
        <input type="button" class="btn btn-small" value="<?=_("导出")?>" onClick="jQuery('#OPERATION').val('excel');document.form1.submit();">
    </div>
</div>
<table class="table table-striped table-bordered" style="display:none;">
    <tr>
        <td align="right">
            <form name="form1" action="dept_Sum_data.php" style="margin-bottom:5px;">
                <input type="hidden" id="OPERATION" name="OPERATION" value="">
                <input type="hidden" id="OFFICE_DEPOSITORY" name="OFFICE_DEPOSITORY" value="<?=$OFFICE_DEPOSITORY?>">
                <input type="hidden" id="OFFICE_PROTYPE" name="OFFICE_PROTYPE" value="<?=$OFFICE_PROTYPE?>">
                <input type="hidden" id="PRO_ID" name="PRO_ID" value="<?=$PRO_ID?>">
                <input type="hidden" id="FROM_DATE" name="FROM_DATE" value="<?=$FROM_DATE?>">
                <input type="hidden" id="TO_DATE" name="TO_DATE" value="<?=$TO_DATE?>">
            </form>
        </td>
    </tr>
</table>

<?
if($OPERATION=="excel")
{
    ob_end_clean();
    Header("Cache-control: private");
    Header("Content-type: application/vnd.ms-excel");
    Header("Content-Disposition: attachment; ".get_attachment_filename(_("部门领用汇总").".xls"));
    ?>
    <!--
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=MYOA_CHARSET?>">

<body>
-->
    <?
}
?>

<?
//---------合成SQL--------
$TOTAL_SUM=0;
if($FROM_DATE!="")
{
    $WHERE_STR.=" and c.TRANS_DATE>='$FROM_DATE'";
    $WHERE_STR1.=" and a.TRANS_DATE>='$FROM_DATE'";
}
if($TO_DATE!="")
{
    $WHERE_STR.=" and c.TRANS_DATE<='$TO_DATE'";
    $WHERE_STR1.=" and a.TRANS_DATE<='$TO_DATE'";
}
if($PRO_ID!=-1 and $PRO_ID!="")
{
    $WHERE_STR.=" and c.PRO_ID='$PRO_ID'";
    $WHERE_STR1.=" and a.PRO_ID='$PRO_ID'";
}
if($OFFICE_DEPOSITORY!="")
{
    $WHERE_STR.=" and d.OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)";
    $WHERE_STR1.=" and OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)";
}
if($OFFICE_PROTYPE!=-1 and $OFFICE_PROTYPE!="")
{
    $WHERE_STR.=" and d.OFFICE_PROTYPE='$OFFICE_PROTYPE'";
    $WHERE_STR1.=" and c.OFFICE_PROTYPE='$OFFICE_PROTYPE'";
}

function dept_sum_list($DEPT_ID,$WHERE_STR)
{
    global $TOTAL_SUM;

    if ($_SESSION["LOGIN_USER_PRIV"]==1)
    {
        $query = "SELECT b.USER_NAME as BORROWER,d.PRO_ID as PRO_ID,d.PRO_NAME as PRO_NAME,d.PRO_UNIT as PRO_UNIT,d.PRO_PRICE as old_Price,SUM(c.FACT_QTY) AS QTY, c.TRANS_DATE as TRANS_DATE,c.PRICE as Price,SUM(c.FACT_QTY*d.PRO_PRICE) AS OLD_TOTAL_PRICE,SUM(c.FACT_QTY*c.PRICE) AS TOTAL_PRICE
            FROM `DEPARTMENT` a
            LEFT OUTER JOIN `USER` b ON a.DEPT_ID = b.DEPT_ID
            LEFT OUTER JOIN OFFICE_TRANSHISTORY c ON `BORROWER`= b.USER_ID
            LEFT OUTER JOIN OFFICE_PRODUCTS d ON d.PRO_ID = c.PRO_ID
            where FIND_IN_SET(b.DEPT_ID,'$DEPT_ID') AND c.TRANS_FLAG='1'".$WHERE_STR." AND (c.TRANS_STATE='' or c.TRANS_STATE='1') GROUP BY b.DEPT_ID,BORROWER,c.PRO_ID";
    }
    else
    {
        $query = "SELECT b.USER_NAME as BORROWER,d.PRO_ID as PRO_ID,d.PRO_NAME as PRO_NAME,d.PRO_UNIT as PRO_UNIT,d.PRO_PRICE as old_Price,SUM(c.FACT_QTY) AS QTY, c.TRANS_DATE as TRANS_DATE,c.PRICE as Price,SUM(c.FACT_QTY*d.PRO_PRICE) AS OLD_TOTAL_PRICE,SUM(c.FACT_QTY*c.PRICE) AS TOTAL_PRICE
            FROM `DEPARTMENT` a
            LEFT OUTER JOIN `USER` b ON a.DEPT_ID = b.DEPT_ID
            LEFT OUTER JOIN OFFICE_TRANSHISTORY c ON `BORROWER`= b.USER_ID
            LEFT OUTER JOIN OFFICE_PRODUCTS d ON d.PRO_ID = c.PRO_ID
            LEFT OUTER JOIN OFFICE_TYPE e ON  d.OFFICE_PROTYPE=e.ID
            LEFT OUTER JOIN OFFICE_DEPOSITORY f ON f.ID=e.TYPE_DEPOSITORY
            where FIND_IN_SET(b.DEPT_ID,'$DEPT_ID') AND c.TRANS_FLAG='1'".$WHERE_STR." and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',f.MANAGER)
            AND (c.TRANS_STATE='' or c.TRANS_STATE='1') GROUP BY b.DEPT_ID,BORROWER,c.PRO_ID";
    }
    $cursor= exequery(TD::conn(),$query);
    $SUM_TEXT="";
    $TOTAL_SUM=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $PRO_ID=$ROW["PRO_ID"];
        $PRO_NAME=$ROW["PRO_NAME"];
        $QTY=$ROW["QTY"];

        if($ROW["Price"]=="0"&&$ROW["TOTAL_PRICE"]=="0")
        {
            $Price=$ROW["old_Price"];
            $TOTAL_PRICE=$ROW["OLD_TOTAL_PRICE"];
        }
        else
        {
            $Price=$ROW["Price"];
            $TOTAL_PRICE=$ROW["TOTAL_PRICE"];
        }
        $UNIT=$ROW["PRO_UNIT"];
        $TRANS_DATE=$ROW["TRANS_DATE"];
        $BORROWER=$ROW["BORROWER"];
        $TOTAL_SUM=$TOTAL_SUM+$TOTAL_PRICE;
        $SUM_TEXT.="
  <tr>
    <td>".$BORROWER."</td>
    <td>".$PRO_ID."</td>
    <td>".$PRO_NAME."</td>
    <td>".$QTY.$UNIT."</td>
    <td>".$TRANS_DATE."</td>
    <td>".$Price."</td>
    <td>".$TOTAL_PRICE."</td>
   ";

    }//while
    return $SUM_TEXT;
}

function disdept_sum_list($WHERE_STR1)
{
    global $TOTAL_SUM;
    $query = "SELECT b.USER_NAME as BORROWER, a.PRO_ID as PRO_ID,c.PRO_NAME as PRO_NAME,c.PRO_UNIT as PRO_UNIT ,SUM(a.FACT_QTY) AS QTY, a.TRANS_DATE as TRANS_DATE ,c.PRO_PRICE as Price,SUM(a.FACT_QTY*a.PRICE) AS TOTAL_PRICE
        FROM `OFFICE_TRANSHISTORY` a
        LEFT OUTER JOIN USER b ON a.BORROWER= b.USER_ID
        LEFT OUTER JOIN OFFICE_PRODUCTS c ON a.PRO_ID = c.PRO_ID
        where b.DEPT_ID='0' AND a.TRANS_FLAG='1'".$WHERE_STR1."
        AND (a.TRANS_STATE='' or a.TRANS_STATE='1') GROUP BY BORROWER,a.PRO_ID";


    $cursor= exequery(TD::conn(),$query);
    $SUM_TEXT="";
    $TOTAL_SUM=0;
    while($ROW=mysql_fetch_array($cursor))
    {

        $PRO_ID=$ROW["PRO_ID"];
        $PRO_NAME=$ROW["PRO_NAME"];
        $QTY=$ROW["QTY"];
        $Price=$ROW["Price"];
        $TOTAL_PRICE=$ROW["TOTAL_PRICE"];
        $UNIT=$ROW["PRO_UNIT"];
        $TRANS_DATE=$ROW["TRANS_DATE"];
        $BORROWER=$ROW["BORROWER"];
        $TOTAL_SUM=$TOTAL_SUM+$TOTAL_PRICE;
        $SUM_TEXT.="
  <tr>
    <td>".$BORROWER."</td>
    <td>".$PRO_ID."</td>
    <td>".$PRO_NAME."</td>
    <td>".$QTY.$UNIT."</td>
    <td>".$TRANS_DATE."</td>
    <td>".$Price."</td>
    <td>".$TOTAL_PRICE."</td>
   ";

    }//while
    return $SUM_TEXT;
}

function dept_tree_arr($DEPT_ID,$PRIV_OP,$WHERE_STR)
{
    global $DEEP_COUNT;
    global $TOTAL_SUM;
    $DEPT_ID=intval($DEPT_ID);
    $query = "SELECT * from DEPARTMENT where DEPT_PARENT='$DEPT_ID'";
    $cursor= exequery(TD::conn(),$query);
    $OPTION_TEXT="";
    $COUNT = 0;
    $DEEP_COUNT1=$DEEP_COUNT;
    $DEEP_COUNT.=_("　");
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_NAME=$ROW["DEPT_NAME"];
        $DEPT_PARENT=$ROW["DEPT_PARENT"];
        //if(is_dept_priv($DEPT_ID)!="1")continue;
        $DEPT_NAME=str_replace("<","&lt",$DEPT_NAME);
        $DEPT_NAME=str_replace(">","&gt",$DEPT_NAME);
        $DEPT_NAME=stripslashes($DEPT_NAME);


        $DEPT_STR = GetUnionSetOfChildDeptId($DEPT_ID);
        // $OPTION_TEXT_CHILD=dept_tree_arr($DEPT_ID,$PRIV_OP,$WHERE_STR);

        //------领用数量--------
        $query1="SELECT count(distinct c.PRO_ID)
                 FROM `DEPARTMENT` a
                 LEFT OUTER JOIN `USER` b ON a.DEPT_ID = b.DEPT_ID
                 LEFT OUTER JOIN OFFICE_TRANSHISTORY c ON `BORROWER`= b.USER_ID
                 LEFT OUTER JOIN OFFICE_PRODUCTS d ON d.PRO_ID = c.PRO_ID  WHERE FIND_IN_SET(a.DEPT_ID,'$DEPT_STR') and c.TRANS_FLAG='1'".$WHERE_STR;
        // exit($query1);
        $cursor1= exequery(TD::conn(),$query1);
        $VOTE_COUNT=0;
        if($ROW=mysql_fetch_array($cursor1))
            $VOTE_COUNT=$ROW[0];

        //---------------------
        dept_sum_list($DEPT_STR,$WHERE_STR);
        $OPTION_ARR[] = array($DEPT_NAME,$TOTAL_SUM);

    }
    return $OPTION_ARR;
}

if($DEPT_ID=="")
    $DEPT_ID=0;

$OPTION_ARR=dept_tree_arr($DEPT_ID,1,$WHERE_STR);

//echo "<pre>";print_r($OPTION_ARR);

$xmlStr="<chart caption='"._("部门领用汇总")."' formatNumberScale='0'>";
if($OPTION_ARR!="")
{
    foreach($OPTION_ARR as $value)
    {
        $PRO_NAME=td_htmlspecialchars($value[0]);
        $PRO_NAME=str_replace("\"","&quot;",$PRO_NAME);
        $xmlStr .= "<set label='" .$PRO_NAME. "' value='" . $value[1] . "' />";
    }
}
$xmlStr .= "</chart>";
if($MAP_TYPE == 0)
{
    echo renderChart(MYOA_JS_SERVER."/inc/FusionCharts/Pie3D.swf", "", $xmlStr, 'FactorySum', "600", "300", false, false);
}
else
{
    echo renderChart(MYOA_JS_SERVER."/inc/FusionCharts/Column3D.swf", "", $xmlStr, "myFirst", "600", "300", false, false);
}


?>
<!--
</body>
</html>
-->

<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include("inc/FusionCharts/FusionCharts.php");

if($TRANS_FLAG == 0)
{
    $HTML_PAGE_TITLE = _("采购物品报表");
}
else if($TRANS_FLAG == 4)
{
    $HTML_PAGE_TITLE = _("报废物品报表");
}
//include_once("inc/header.inc.php");
$O_PROTYPE = "";
if($_SESSION["LOGIN_USER_PRIV"]!=1)
{
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
    <h4 class="pull-left">
        <?
        if($TRANS_FLAG==0) echo _("采购物品报表");
        if($TRANS_FLAG==4) echo _("报废物品报表");
        ?>
    </h4>
    <div class="pull-right" id="topid" >
        <input type="button" class="btn btn-small" value="<?=_("打印")?>" onClick="window.print();">&nbsp;&nbsp;
        <input type="button" class="btn btn-small" value="<?=_("导出")?>" onClick="jQuery('#OPERATION').val('excel');document.form1.submit();">

    </div>
</div>
<table class="table table-striped table-bordered" style="display:none;">
    <tr>
        <td align="right">
            <form name="form1" action="purchase.php" style="margin-bottom:5px;">
                <input type="hidden" id="OPERATION" name="OPERATION" value="">
                <input type="hidden" id="OFFICE_PROTYPE" name="OFFICE_PROTYPE" value="<?=$OFFICE_PROTYPE?>">
                <input type="hidden" id="OFFICE_DEPOSITORY" name="OFFICE_DEPOSITORY" value="<?=$OFFICE_DEPOSITORY?>">
                <input type="hidden" id="PRO_ID" name="PRO_ID" value="<?=$PRO_ID?>">
                <input type="hidden" id="FROM_DATE" name="FROM_DATE" value="<?=$FROM_DATE?>">
                <input type="hidden" id="TO_DATE" name="TO_DATE" value="<?=$TO_DATE?>">
                <input type="hidden" id="TRANS_FLAG" name="TRANS_FLAG" value="<?=$TRANS_FLAG?>">
            </form>
        </td>
    </tr>
</table>

<div>
    <?
    //-----------合成SQL语句-----------
    $CUR_DATE=date("Y-m-d",time());
    $WHERE_STR.=" where a.TRANS_FLAG='0'";
    if($FROM_DATE!="")
        $WHERE_STR.=" and a.TRANS_DATE>='$FROM_DATE'";
    if($TO_DATE!="")
        $WHERE_STR.=" and a.TRANS_DATE<='$TO_DATE'";
    if($PRO_ID!=-1 and $PRO_ID!="")
        $WHERE_STR.=" and a.PRO_ID='$PRO_ID'";
    if($OFFICE_PROTYPE!=-1 and $OFFICE_PROTYPE!="")
        $WHERE_STR.=" and b.OFFICE_PROTYPE='$OFFICE_PROTYPE'";
    if($OFFICE_DEPOSITORY!="")
        $WHERE_STR.=" and b.OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)";
    //----------确定管理范围
    /*$query1 = "select DEPT_ID from DEPARTMENT";
    $cursor1= exequery(TD::conn(),$query1);
    while($ROW=mysql_fetch_array($cursor1))
    {
        $DEPT_ID=$ROW["DEPT_ID"];
        if(is_dept_priv($DEPT_ID)==1)
        {
            $TO_ID=$TO_ID.$DEPT_ID.",";
        }
    }
    $TO_ID="'".str_replace(",","','",substr($TO_ID,0,-1))."'";
    $query1 = "SELECT POST_PRIV FROM USER where UID='".$_SESSION["LOGIN_UID"]."'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $POST_PRIV=$ROW["POST_PRIV"];
    if($POST_PRIV!="1")
    {
        if($WHERE_STR=="")
            $WHERE_STR.=" where c.DEPT_ID in ($TO_ID)";
        else
            $WHERE_STR.=" and c.DEPT_ID in ($TO_ID)";
    }
    */
    //添加库管权限条件
    if($_SESSION["LOGIN_USER_PRIV"]!=1)
        $WHERE_STR.=" and b.OFFICE_PROTYPE in($O_PROTYPE)";

    //============================显示操作结果=======================================
    $CUR_DATE=date("Y-m-d",time());
    $query = "SELECT a.PRO_ID, b.PRO_NAME,a.TRANS_FLAG ,a.TRANS_QTY,a.BORROWER,b.PRO_UNIT,b.PRO_PRICE as PRO_PRICE,a.PRICE as TRANS_PRICE,a.TRANS_DATE,a.OPERATOR,a.REMARK from OFFICE_TRANSHISTORY a
          LEFT OUTER JOIN OFFICE_PRODUCTS b ON a.PRO_ID = b.PRO_ID
          LEFT OUTER JOIN USER  c ON a.OPERATOR = c.USER_ID
          ".$WHERE_STR." order by b.PRO_NAME,a.TRANS_DATE DESC";
    $cursor= exequery(TD::conn(),$query);
    $cursor2=exequery(TD::conn(),$query);
    $VOTE_COUNT=0;
    $EXCEL_HEAD = array(_("办公用品名称"),_("登记类型"),_("数量"),_("单价"),_("操作日期"),_("操作员"),_("备注"));
    $EXCEL_OUT_ARRAY = array();

    $ROW1=mysql_fetch_array($cursor2);
    $SET_PRONMAE=$ROW1["PRO_NAME"];
    $TOTAL_SUM1=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $ROW1=mysql_fetch_array($cursor2);
        $SET_PRONMAE=$ROW1["PRO_NAME"];
        $VOTE_COUNT++;
        $FLAG=0;
        $PROID=$ROW["PRO_ID"];
        $PRONAME=$ROW["PRO_NAME"];
        if($SET_PRONMAE!=$PRONAME)
        {
            $FLAG=1;
        }
        $PRO_UNIT=$ROW["PRO_UNIT"];
        $BORROWER=$ROW["BORROWER"];
        $TRANSFLAG=$ROW["TRANS_FLAG"];
        $REMARK=$ROW["REMARK"];
        if($TRANS_FLAG==0){
            $TRANS_QTY=$ROW["TRANS_QTY"];
            $PRICE =$ROW["PRO_PRICE"];
        }
        $TOTAL_SUM = "";
        $TOTAL_SUM=$TOTAL_SUM+round($TRANS_QTY*$PRICE,2);

        $TOTAL_SUM=sprintf('%.2f',$TOTAL_SUM);

        //获取数组下标
        if($PRO_ARR == ""){
            $PRO_ARR = array();
        }
        $subscript = array_keys($PRO_ARR);

        if($PROID==end($subscript))
        {
            $TOTAL_SUM = $TOTAL_SUM+ $PRO_ARR[$PROID][1];
            $PRO_ARR[$PROID] = array($PRONAME,$TOTAL_SUM);
        }
        else
        {
            $PRO_ARR[$PROID] = array($PRONAME,$TOTAL_SUM);
        }

        $EXCEL_OUT = "";
        $EXCEL_OUT.=format_cvs($PRONAME).",";
        $EXCEL_OUT.=format_cvs($TRANS_NAME).",";
        $EXCEL_OUT.=format_cvs($TRANS_QTY.$PRO_UNIT).",";
        $EXCEL_OUT.=format_cvs($PRICE).",";
        $EXCEL_OUT.=format_cvs($TRANS_DATE).",";
        $EXCEL_OUT.=format_cvs($OPERATOR_NAME).",";
        $EXCEL_OUT.=format_cvs($REMARK);
        $EXCEL_OUT_ARRAY[] = $EXCEL_OUT;
    }


    if($PRO_ARR == '')
    {
        ?>
        <script>
            document.getElementById("topid").style.display="none";
        </script>
        <?
        Message(_("提示"),_("无符合条件的记录！"));
        exit;
    }
    $xmlStr="<chart caption='"._("采购物品")."' formatNumberScale='0'>";
    foreach($PRO_ARR as $value)
    {
        $PRO_NAME=td_htmlspecialchars($value[0]);
        $PRO_NAME=str_replace("\"","&quot;",$PRO_NAME);
        $xmlStr .= "<set label='" .$PRO_NAME. "' value='" . $value[1] . "' />";
        echo $xmlStr;
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
    if($OPERATION=="excel")
    {
        if($TRANS_FLAG==0)
            $FILENAME = _("采购物品报表");
        else
            $FILENAME = _("报废物品报表");

        ob_end_clean();
        require_once ('inc/ExcelWriter.php');

        $objExcel = new ExcelWriter();
        $objExcel->setFileName($FILENAME);
        $objExcel->addHead($EXCEL_HEAD);

        foreach($EXCEL_OUT_ARRAY as $EXCEL_OUT)
            $objExcel->addRow($EXCEL_OUT);

        $objExcel->Save();
        exit;
    }
    ?>
</div>
<!--
</body>
</html>
-->
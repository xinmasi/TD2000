<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

if($TRANS_FLAG == 2)
{
    $HTML_PAGE_TITLE = _("借用物品报表");
    $auion = "TRANS_DATE";
}
else if($TRANS_FLAG == 3)
{
    $HTML_PAGE_TITLE = _("归还物品报表");
    $auion = "RETURN_DATE";
}

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
        Message("",_("您没有查询权限!"));
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
<body>
-->
    <div class="clearfix">
        <h4 class="pull-left">
            <?
            if($TRANS_FLAG==2) echo _("借用物品报表");
            if($TRANS_FLAG==3) echo _("归还物品报表");
            ?>      </h4>
        <div class="pull-right" >
            <input type="button" class="btn btn-small" value="<?=_("打印")?>" onClick="window.print();">&nbsp;&nbsp;
            <input type="button" class="btn btn-small" value="<?=_("导出")?>" onClick="jQuery('#OPERATION').val('excel');document.form1.submit();">
        </div>
    </div>
    <table>
        <tr>
            <td align="right">
                <form name="form1" action="borrow_sum.php" style="margin-bottom:5px;">
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

        if($TRANS_FLAG == 2)
        {
            $WHERE_STR.=" where a.TRANS_FLAG='$TRANS_FLAG'";
        }else
        {
            $WHERE_STR.=" where ((a.TRANS_FLAG='2' AND a.RETURN_STATUS='1') or (a.TRANS_FLAG='3' AND a.TRANS_STATE=1)) ";
        }
        if($FROM_DATE!="")
            $WHERE_STR.=" and a.".$auion.">='$FROM_DATE'";
        if($TO_DATE!="")
            $WHERE_STR.=" and a.".$auion."<='$TO_DATE'";
        if($PRO_ID!=-1 and $PRO_ID!="")
            $WHERE_STR.=" and a.PRO_ID='$PRO_ID'";
        if($OFFICE_DEPOSITORY!='' && $OFFICE_PROTYPE =='')
            $WHERE_STR .=" and b.OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)  ";
        if($OFFICE_PROTYPE!='' && $OFFICE_PROTYPE!="")
            $WHERE_STR.=" and b.OFFICE_PROTYPE='$OFFICE_PROTYPE'";
        if($TO_ID1!="")
            $WHERE_STR.=" and a.BORROWER = '$TO_ID1'";

        //添加库管权限条件
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
            $WHERE_STR.=" and OFFICE_PROTYPE in($O_PROTYPE)";


        //============================显示操作结果=======================================
        $CUR_DATE=date("Y-m-d",time());
        $query = "SELECT a.PRO_ID, b.PRO_NAME,a.TRANS_FLAG ,a.FACT_QTY,a.BORROWER,b.PRO_UNIT,a.PRICE,a.TRANS_DATE,a.RETURN_DATE,a.OPERATOR,a.REMARK,a.RETURN_STATUS from OFFICE_TRANSHISTORY a
          LEFT OUTER JOIN OFFICE_PRODUCTS b ON a.PRO_ID = b.PRO_ID
          LEFT OUTER JOIN USER  c ON a.OPERATOR = c.USER_ID
          ".$WHERE_STR." and a.TRANS_STATE = 1 order by b.PRO_NAME,a.".$auion." DESC";
        $cursor= exequery(TD::conn(),$query);
        $VOTE_COUNT=0;
        if($TRANS_FLAG==2)
        {
            $EXCEL_HEAD=array(_("办公用品名称"),_("登记类型"),_("数量"),_("计量单位"),_("借用人"),_("登记日期"),_("操作员"),_("备注"));
            $FILENAME = _("借用物品报表");
        }
        else
        {
            $EXCEL_HEAD=array(_("办公用品名称"),_("登记类型"),_("数量"),_("计量单位"),_("归还人"),_("登记日期"),_("操作员"),_("备注"));
            $FILENAME = _("归还物品报表");
        }
        
        $EXCEL_OUT_ARRAY = array();
        while($ROW=mysql_fetch_array($cursor))
        {
            $VOTE_COUNT++;
            $FLAG=0;
            $PROID         = $ROW["PRO_ID"];
            $PRONAME       = $ROW["PRO_NAME"];
            $PRO_UNIT      = $ROW["PRO_UNIT"];
            $BORROWER      = $ROW["BORROWER"];
            $TRANSFLAG     = $ROW["TRANS_FLAG"];
            $REMARK        = $ROW["REMARK"];
            $RETURN_STATUS = $ROW['$RETURN_STATUS'];
            if($TRANS_FLAG==2)
            {
                $TRANS_QTY=abs($ROW["FACT_QTY"]);
            }
            if($TRANS_FLAG==3)
            {
                $TRANS_QTY=$ROW["FACT_QTY"];
            }
            $PRICE      = $ROW["PRICE"];
            $TRANS_DATE = $ROW[$auion];
            $OPERATOR   = $ROW["OPERATOR"];

            $query1="select USER_NAME from USER where USER_ID='$OPERATOR'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
                $OPERATOR_NAME=$ROW["USER_NAME"];

            $query1="select USER_NAME from USER where USER_ID='$BORROWER'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
                $BORROWER_NAME=$ROW["USER_NAME"];

            if($TRANSFLAG=="2")
            {
                $TRANS_NAME=_("借用");
            }
            if($TRANSFLAG=="2" && $RETURN_STATUS=="1")
            {
                $TRANS_NAME=_("归还");
            }
            if($VOTE_COUNT==1)
            {
            ?>
            <table border="1" width="100%" class="table table-striped table-bordered">
                <?
                if($VOTE_COUNT>0)
                {
                ?>
                <thead>
                <th nowrap style="text-align: center;"><?=_("办公用品名称")?></th>
                <th nowrap style="text-align: center;"><?=_("登记类型")?></th>
                <th nowrap style="text-align: center;"><?=_("数量")?></th>
                <th nowrap style="text-align: center;"><?=_("计量单位")?></th>
                <th nowrap style="text-align: center;">
                    <?
                    if($TRANS_FLAG==2) echo _("借用人");
                    if($TRANS_FLAG==3) echo _("归还人");
                    ?>
                </th>
                <th nowrap style="text-align: center;"><?=_("登记日期")?></th>
                <th nowrap style="text-align: center;"><?=_("登记员")?></th>
                <th nowrap style="text-align: center;"><?=_("备注")?></th>
                </thead>
                <?
                }
                ?>
            <?
            }
            ?>
                <tr>
                    <td nowrap style="text-align: center;"><?=$PRONAME?></td>
                    <td style="text-align: center;"><?=$TRANS_NAME?></td>
                    <td nowrap style="text-align: center;"><?=$TRANS_QTY?>
                    <td nowrap style="text-align: center;"><?=$PRO_UNIT?$PRO_UNIT:"-";?></td>
                    <td nowrap style="text-align: center;"><?=$BORROWER_NAME?></td>
                    <td nowrap style="text-align: center;"><?=$TRANS_DATE?></td>
                    <td nowrap style="text-align: center;"><?=$OPERATOR_NAME?$OPERATOR_NAME:"-";?></td>
                    <td nowrap style="text-align: center;"><?=$REMARK?></td>
                </tr>
                <?
                $EXCEL_OUT = "";
                $EXCEL_OUT.=format_cvs($PRONAME).",";
                $EXCEL_OUT.=format_cvs($TRANS_NAME).",";
                $EXCEL_OUT.=format_cvs($TRANS_QTY).",";
                $EXCEL_OUT.=format_cvs($PRO_UNIT).",";
                $EXCEL_OUT.=format_cvs($BORROWER_NAME).",";
                $EXCEL_OUT.=format_cvs($TRANS_DATE).",";
                $EXCEL_OUT.=format_cvs($OPERATOR_NAME).",";
                $EXCEL_OUT.=format_cvs($REMARK);
                $EXCEL_OUT_ARRAY[] = $EXCEL_OUT;
            }
            if($VOTE_COUNT=='0')
                Message("",_("无符合条件的记录!"));
            ?>

        </table>
    </div>
    <!--
    </body>
    </html>
    -->
<?
if($OPERATION=="excel")
{
    ob_end_clean();
    require_once ('inc/ExcelWriter.php');

    $objExcel = new ExcelWriter();
    $objExcel->setFileName($HTML_PAGE_TITLE);
    $objExcel->addHead($EXCEL_HEAD);

    foreach($EXCEL_OUT_ARRAY as $EXCEL_OUT)
        $objExcel->addRow($EXCEL_OUT);

    $objExcel->Save();
}
?>
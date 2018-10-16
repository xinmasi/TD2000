<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

if($TRANS_FLAG == 0)
{
    $HTML_PAGE_TITLE = _("采购物品报表");
}
else if($TRANS_FLAG == 4)
{
    $HTML_PAGE_TITLE = _("报废物品报表");
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

<body style="height:100%; overflow:hidden">
-->
    <div class="clearfix">
        <h4 class="pull-left">
            <?
            if($TRANS_FLAG==0) echo _("采购物品报表");
            if($TRANS_FLAG==4) echo _("报废物品报表");
            ?>
        </h4>
        <div class="pull-right" >
            <input type="button" class="btn btn-small" value="<?=_("打印")?>" onClick="window.print();">&nbsp;&nbsp;
            <input type="button" class="btn btn-small" value="<?=_("导出")?>" onClick="jQuery('#OPERATION').val('excel');document.form1.submit();">
        </div>
    </div>
    <table style="display:none;">
        <tr>
            <td align="right">
                <form name="form1" action="report.php" style="margin-bottom:5px;">
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
        $WHERE_STR.=" where a.TRANS_FLAG='$TRANS_FLAG'";
        if($FROM_DATE!="")
            $WHERE_STR.=" and a.TRANS_DATE>='$FROM_DATE'";
        if($TO_DATE!="")
            $WHERE_STR.=" and a.TRANS_DATE<='$TO_DATE'";
        if($PRO_ID!=-1 and $PRO_ID!="")
            $WHERE_STR.=" and a.PRO_ID='$PRO_ID'";
        if($OFFICE_PROTYPE!=-1 and $OFFICE_PROTYPE!="")
            $WHERE_STR.=" and b.OFFICE_PROTYPE='$OFFICE_PROTYPE'";
        if($OFFICE_DEPOSITORY!="")
            $WHERE_STR.="and OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)";
        //----------确定管理范围
        /*$query1 = "select DEPT_ID from DEPARTMENT";
        $cursor1= exequery(TD::conn(),$query1);
        while($ROW=mysql_fetch_array($cursor1))
        {
          $DEPT_ID=$ROW["DEPT_ID"];
          if (is_dept_priv($DEPT_ID)==1)
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
        }*/

        //添加库管权限条件
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
            $WHERE_STR.=" and b.OFFICE_PROTYPE in($O_PROTYPE)";

        //============================显示操作结果=======================================
        $CUR_DATE=date("Y-m-d",time());
        $query = "SELECT a.PRO_ID, b.PRO_NAME,a.TRANS_FLAG ,a.FACT_QTY,a.TRANS_QTY,a.BORROWER,b.PRO_UNIT,b.PRO_PRICE as PRO_PRICE,a.PRICE as TRANS_PRICE,a.TRANS_DATE,a.OPERATOR,a.REMARK from OFFICE_TRANSHISTORY a
          LEFT OUTER JOIN OFFICE_PRODUCTS b ON a.PRO_ID = b.PRO_ID
          LEFT OUTER JOIN USER  c ON a.OPERATOR = c.USER_ID
          ".$WHERE_STR." order by b.PRO_NAME,a.TRANS_DATE DESC";
        $cursor= exequery(TD::conn(),$query);
        $cursor2=exequery(TD::conn(),$query);
        $VOTE_COUNT=0;
        $EXCEL_HEAD = array(_("办公用品名称"),_("登记类型"),_("数量"),_("计量单位"),_("单价"),_("操作日期"),_("操作员"),_("备注"));
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
            if($TRANS_FLAG==0)$FACT_QTY==0?$TRANS_QTY=$ROW["TRANS_QTY"]:$TRANS_QTY=$ROW["FACT_QTY"];
            if($TRANS_FLAG==4)$TRANS_QTY=$ROW["FACT_QTY"]*(-1);
            if($TRANS_FLAG==0)$PRICE =$ROW["PRO_PRICE"];
            if($TRANS_FLAG==4)$PRICE =$ROW["PRO_PRICE"];
            $TOTAL_SUM=$TOTAL_SUM+round($TRANS_QTY*$PRICE,2);
            $TRANS_DATE=$ROW["TRANS_DATE"];
            $OPERATOR=$ROW["OPERATOR"];
            $query1="select USER_NAME from USER where USER_ID='$OPERATOR'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
                $OPERATOR_NAME=$ROW["USER_NAME"];
            if ($TRANSFLAG=="0")
            {
                $TRANS_NAME=_("采购入库");
                $BORROWER_NAME="";
            }
            if ($TRANSFLAG=="4")
            {
                $TRANS_NAME=_("报废");
                $BORROWER_NAME="";
            }
            if($VOTE_COUNT==1)
            {
        ?>
        <table border="1" width="100%" class="table table-striped table-bordered">
            <?
            }
            if($VOTE_COUNT%2==1)
                $TableLine="TableLine1";
            else
                $TableLine="TableLine2";
            ?>
            <tr>
                <td nowrap style="text-align: center;"><?=$PRONAME?></td>
                <td style="text-align: center;"><?=$TRANS_NAME?></td>
                <td nowrap style="text-align: center;"><?=$TRANS_QTY?></td>
                <td nowrap style="text-align: center;"><?=$PRO_UNIT?></td>
                <td nowrap style="text-align: center;"><?=$PRICE?></td>
                <td nowrap style="text-align: center;"><?=$TRANS_DATE?></td>
                <td nowrap style="text-align: center;"><?=$OPERATOR_NAME?></td>
                <td nowrap style="text-align: center;"><?=$REMARK?></td>
            </tr>
            <?
            if($FLAG==1)
            {

                ?>
                <tr>
                    <td nowrap align="right" colspan="7"><b><?=_("合计：")?><?=$TOTAL_SUM?> <?=_("元")?></b></td>
                </tr>

                <?
                $TOTAL_SUM1=$TOTAL_SUM1+$TOTAL_SUM;
                $TOTAL_SUM=0;
            }
            $EXCEL_OUT = "";
            $EXCEL_OUT.=format_cvs($PRONAME).",";
            $EXCEL_OUT.=format_cvs($TRANS_NAME).",";
            $EXCEL_OUT.=format_cvs($TRANS_QTY).",";
            $EXCEL_OUT.=format_cvs($PRO_UNIT).",";
            $EXCEL_OUT.=format_cvs($PRICE).",";
            $EXCEL_OUT.=format_cvs($TRANS_DATE).",";
            $EXCEL_OUT.=format_cvs($OPERATOR_NAME).",";
            $EXCEL_OUT.=format_cvs($REMARK);
            $EXCEL_OUT_ARRAY[] = $EXCEL_OUT;
            }

            if($VOTE_COUNT>0)
            {
            ?>
            <tr>
                <td nowrap align="right" colspan="7"><b><?=_("共计：")?><?=$TOTAL_SUM1?> <?=_("元")?></b></td>
            </tr>
            <thead>
            <th nowrap style="text-align: center;"><?=_("办公用品名称")?></th>
            <th nowrap style="text-align: center;"><?=_("登记类型")?></th>
            <th nowrap style="text-align: center;"><?=_("数量")?></th>
            <th nowrap style="text-align: center;"><?=_("计量单位")?></th>
            <th nowrap style="text-align: center;"><?=_("单价")?></th>
            <th nowrap style="text-align: center;"><?=_("操作日期")?></th>
            <th nowrap style="text-align: center;"><?=_("操作员")?></th>
            <th nowrap style="text-align: center;"><?=_("备注")?></th>
            </thead>
        </table>
    <?
    }
    else
        Message(_("提示"),_("无符合条件的记录!"));
    ?>
    </div>
    <!--
    </body>
    </html>
    -->
<?
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
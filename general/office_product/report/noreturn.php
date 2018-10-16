<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("未归还物品报表");
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
        <h4 class="pull-left"><?=_("未归还物品报表")?></h4>
        <div class="pull-right" >
            <input type="button" class="btn btn-small" value="<?=_("打印")?>" onClick="window.print();">&nbsp;&nbsp;
            <input type="button" class="btn btn-small" value="<?=_("导出")?>" onClick="jQuery('#OPERATION').val('excel');document.form1.submit();">

        </div>
    </div>
    <table >
        <tr>
            <td align="right">
                <form name="form1" action="noreturn.php" style="margin-bottom:5px;">
                    <input type="hidden" id="OPERATION" name="OPERATION" value="">
                    <input type="hidden" id="OFFICE_PROTYPE" name="OFFICE_PROTYPE" value="<?=$OFFICE_PROTYPE?>">
                    <input type="hidden" id="OFFICE_DEPOSITORY" name="OFFICE_DEPOSITORY" value="<?=$OFFICE_DEPOSITORY?>">
                    <input type="hidden" id="PRO_ID" name="PRO_ID" value="<?=$PRO_ID?>">
                    <input type="hidden" id="FROM_DATE" name="FROM_DATE" value="<?=$FROM_DATE?>">
                    <input type="hidden" id="TO_DATE" name="TO_DATE" value="<?=$TO_DATE?>">

                </form>
            </td>
        </tr>
    </table>

    <div>
        <?
        //-----------合成SQL语句-----------
        $CUR_DATE=date("Y-m-d",time());
        $WHERE_STR.=" where a.TRANS_FLAG='2' and a.TRANS_STATE='1' and a.GRANT_STATUS='1' and a.RETURN_STATUS!='1'";
        if($FROM_DATE!="")
            $WHERE_STR.=" and a.TRANS_DATE>='$FROM_DATE'";
        if($TO_DATE!="")
            $WHERE_STR.=" and a.TRANS_DATE<='$TO_DATE'";
        if($PRO_ID!=-1 and $PRO_ID!="")
            $WHERE_STR.=" and a.PRO_ID='$PRO_ID'";
        if($OFFICE_DEPOSITORY!='' && $OFFICE_PROTYPE =='')
            $WHERE_STR .=" and b.OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)  ";
        if($OFFICE_PROTYPE!='' && $OFFICE_PROTYPE!="")
            $WHERE_STR.=" and b.OFFICE_PROTYPE='$OFFICE_PROTYPE'";
        if($TO_ID1!="")
            $WHERE_STR.=" and a.BORROWER = '$TO_ID1'";
        $user_id = $_SESSION["LOGIN_USER_ID"];
        if($user_id != 'admin')
        {
            $WHERE_STR.=" and a.OPERATOR = '$user_id'";

        }
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
        if($WHERE_STR=="")
          $WHERE_STR.=" where c.DEPT_ID in ($TO_ID)";
        else
            $WHERE_STR.=" and c.DEPT_ID in ($TO_ID)"; */
        //============================显示操作结果=======================================
        $CUR_DATE=date("Y-m-d",time());
        $query = "SELECT sum(a.TRANS_QTY) as TRANS_QTY,a.PRO_ID, b.PRO_NAME,a.TRANS_FLAG ,a.BORROWER,b.PRO_UNIT,a.PRICE,a.TRANS_DATE,a.OPERATOR,a.REMARK from OFFICE_TRANSHISTORY a
          LEFT OUTER JOIN OFFICE_PRODUCTS b ON a.PRO_ID = b.PRO_ID
          LEFT OUTER JOIN USER  c ON a.OPERATOR = c.USER_ID
          ".$WHERE_STR." group by a.BORROWER,a.PRO_ID order by b.PRO_NAME,a.TRANS_DATE DESC";
        $cursor= exequery(TD::conn(),$query);
        $VOTE_COUNT=0;
        $EXCEL_HEAD = array(_("办公用品名称"),_("未归还数量"),_("计量单位"),_("借用人"),_("借用日期"));
        $EXCEL_OUT_ARRAY = array();

        while($ROW=mysql_fetch_array($cursor))
        {
            $PROID=$ROW["PRO_ID"];
            $PRONAME=$ROW["PRO_NAME"];
            $PRO_UNIT=$ROW["PRO_UNIT"];
            $BORROWER=$ROW["BORROWER"];
            $TRANSFLAG=$ROW["TRANS_FLAG"];
            $REMARK=$ROW["REMARK"];
            $TRANS_QTY=$ROW["TRANS_QTY"]*(-1);
            $PRICE =$ROW["PRICE"];
            $TRANS_DATE=$ROW["TRANS_DATE"];
            $OPERATOR=$ROW["OPERATOR"];

            $query1="select USER_NAME from USER where USER_ID='$OPERATOR'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
                $OPERATOR_NAME=$ROW["USER_NAME"];

            $query1="select USER_NAME from USER where USER_ID='$BORROWER'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
                $BORROWER_NAME=$ROW["USER_NAME"];

            if($TRANS_QTY>0)
                $VOTE_COUNT++;
            else
                continue;

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
                <td nowrap style="text-align: center;"><?=$TRANS_QTY?></td>
                <td nowrap style="text-align: center;"><?=$PRO_UNIT?></td>
                <td nowrap style="text-align: center;"><?=$BORROWER_NAME?></td>
                <td nowrap style="text-align: center;"><?=$TRANS_DATE?></td>
            </tr>
            <?
            $EXCEL_OUT = "";
            $EXCEL_OUT.=format_cvs($PRONAME).",";
            $EXCEL_OUT.=format_cvs($TRANS_QTY).",";
            $EXCEL_OUT.=format_cvs($PRO_UNIT).",";
            $EXCEL_OUT.=format_cvs($BORROWER_NAME).",";
            $EXCEL_OUT.=format_cvs($TRANS_DATE);
            $EXCEL_OUT_ARRAY[] = $EXCEL_OUT;
            }
            if($VOTE_COUNT>0)
            {
            ?>
            <thead >
            <th nowrap style="text-align: center;"><?=_("办公用品名称")?></th>
            <th nowrap style="text-align: center;"><?=_("未归还数量")?></th>
            <th nowrap style="text-align: center;"><?=_("计量单位")?></th>
            <th nowrap style="text-align: center;"><?=_("借用人")?></th>
            <th nowrap style="text-align: center;"><?=_("借用日期")?></th>
            </thead>
        </table>
    <?
    }
    else
        Message("",_("无符合条件的记录！"));
    ?>
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
    $objExcel->setFileName(_("未归还物品报表"));
    $objExcel->addHead($EXCEL_HEAD);

    foreach($EXCEL_OUT_ARRAY as $EXCEL_OUT)
        $objExcel->addRow($EXCEL_OUT);

    $objExcel->Save();
}
?>
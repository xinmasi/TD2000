<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
$HTML_PAGE_TITLE = _("̨�ʱ���");
//include_once("inc/header.inc.php");

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
        Message("",_("��û�в�ѯȨ��!"));
        exit;
    }
}

?>
    <!--
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/reportform.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<body style="height:100%; overflow:hidden">
-->
    <div class="clearfix">
        <h4 class="pull-left"><?=_("̨�˱���")?></h4>
        <div class="pull-right" id="topid">
            <input type="button" class="btn btn-small" value="<?=_("��ӡ")?>" onClick="window.print();">&nbsp;&nbsp;
            <input type="button" class="btn btn-small" value="<?=_("����")?>" onClick="jQuery('#OPERATION').val('excel');document.form1.submit();">
        </div>
    </div>
    <table>
        <tr>
            <td align="right">
                <form name="form1" action="ledger.php" style="margin-bottom:5px;">
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
        //-----------�ϳ�SQL���-----------
        $CUR_DATE=date("Y-m-d",time());
        $WHERE_STR.=" where 1=1 ";
        if($FROM_DATE!="")
            $WHERE_STR.=" and a.TRANS_DATE>='$FROM_DATE'";
        if($TO_DATE!="")
            $WHERE_STR.=" and a.TRANS_DATE<='$TO_DATE'";
        if($PRO_ID!=-1 and $PRO_ID!="")
            $WHERE_STR.=" and a.PRO_ID='$PRO_ID'";
        if($OFFICE_DEPOSITORY!="")
            $WHERE_STR.="and OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)";
        if($OFFICE_PROTYPE!=-1 and $OFFICE_PROTYPE!="")
            $WHERE_STR.=" and b.OFFICE_PROTYPE='$OFFICE_PROTYPE'";

        //��ӿ��Ȩ������
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
            $WHERE_STR.=" and b.OFFICE_PROTYPE in($O_PROTYPE)";
        //============================��ʾ�������=======================================
        $CUR_DATE=date("Y-m-d",time());
        $query = "SELECT
                a.TRANS_DATE,
                a.REMARK,
                b.PRO_NAME,
                a.TRANS_FLAG,
                a.TRANS_STATE,
                a.FACT_QTY,
                a.TRANS_QTY,
                a.RETURN_STATUS,
                b.PRO_UNIT,
                b.PRO_PRICE,
                c.USER_NAME
                 FROM office_transhistory a
                 LEFT JOIN office_products b ON b.PRO_ID = a.PRO_ID
                 LEFT OUTER JOIN user  c ON c.USER_ID = a.OPERATOR  $WHERE_STR and a.TRANS_STATE = 1 order by a.TRANS_ID desc";
        $cursor= exequery(TD::conn(),$query);

        $EXCEL_HEAD = array(_("��������"),_("�칫��Ʒ����"),_("�Ǽ�����"),_("����"),_("������λ"),_("����"),_("����Ա"),_("��ע"));
        $EXCEL_OUT_ARRAY = array();

        $TRANS_FLAG_ARR = array(_("�ɹ����"),_("����"),_("����"),_("�黹"),_("����"),_("ά��"),_("EXCEL����"));
        $VOTE_COUNT = 0;
        while($ROW=mysql_fetch_array($cursor))
        {
        $VOTE_COUNT++;

        if($VOTE_COUNT==1)
        {
        ?>
        <table  class="table table-striped table-bordered tableview" style="text-align: center;">
            <?
            if($VOTE_COUNT>0)
            {
                ?>
                <thead>
                <tr>
                    <th style="text-align: center"><?=_("��������")?></th>
                    <th style="text-align: center"><?=_("�칫��Ʒ����")?></th>
                    <th style="text-align: center"><?=_("�Ǽ�����")?></th>
                    <th style="text-align: center"><?=_("����")?></th>
                    <th style="text-align: center"><?=_("������λ")?></th>
                    <th style="text-align: center"><?=_("����")?></th>
                    <th style="text-align: center"><?=_("����Ա")?></th>
                    <th style="text-align: center"><?=_("��ע")?></th>
                </tr>
                </thead>

                <?
            }
            }
            ?>
            <tr>
                <td><?=$ROW['TRANS_DATE']?></td>
                <td><?=$ROW['PRO_NAME']?></td>
                <td><?=$TRANS_FLAG_ARR[$ROW['TRANS_FLAG']];if($ROW['TRANS_FLAG']==2 && $ROW['RETURN_STATUS']=="1"){ echo "(�ѹ黹)";}elseif($ROW['TRANS_FLAG']==2 && $ROW['RETURN_STATUS']!="1"){ echo "(δ�黹)";}?></td>
                <td><?=$ROW['TRANS_FLAG']==5?"-":($ROW['FACT_QTY'] != 0 ? abs($ROW['FACT_QTY']) : abs($ROW['TRANS_QTY']))?></td>
                <td><?=$ROW['PRO_UNIT']?></td>
                <td><?=$ROW['PRO_PRICE']?></td>
                <td><?=$ROW['USER_NAME']?></td>
                <td><?=csubstr($ROW['REMARK'],0,30)?></td>
            </tr>
            <?
            if($FLAG==1)
            {
                ?>
                <tr>
                    <td colspan="7"><b><?=_("�ϼƣ�")?><?=$TOTAL_SUM?> <?=_("Ԫ")?></b></td>
                </tr>
                <?
                $TOTAL_SUM1=$TOTAL_SUM1+$TOTAL_SUM;
                $TOTAL_SUM=0;
            }
            $EXCEL_OUT = "";
            $EXCEL_OUT.=format_cvs($ROW['TRANS_DATE']).",";
            $EXCEL_OUT.=format_cvs($ROW['PRO_NAME']).",";
            $EXCEL_OUT.=format_cvs($TRANS_FLAG_ARR[$ROW['TRANS_FLAG']]).",";
            $EXCEL_OUT.=format_cvs($ROW['FACT_QTY'] < 0 ? $ROW['FACT_QTY'] * (-1) : $ROW['FACT_QTY']).",";
            $EXCEL_OUT.=format_cvs($ROW['PRO_UNIT']).",";
            $EXCEL_OUT.=format_cvs($ROW['PRO_PRICE']).",";
            $EXCEL_OUT.=format_cvs($ROW['USER_NAME']).",";
            $EXCEL_OUT.=format_cvs($ROW['REMARK']);
            $EXCEL_OUT_ARRAY[] = $EXCEL_OUT;
            }
            if($VOTE_COUNT==0)
            {
                ?>
                <script>
                    document.getElementById("topid").style.display="none";
                </script>
                <?
                Message(_("��ʾ"),_("�޷��������ļ�¼!"));
                exit;
            }
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
    $objExcel->setFileName(_("̨�ʱ���"));
    $objExcel->addHead($EXCEL_HEAD);

    foreach($EXCEL_OUT_ARRAY as $EXCEL_OUT)
        $objExcel->addRow($EXCEL_OUT);

    $objExcel->Save();
}
?>
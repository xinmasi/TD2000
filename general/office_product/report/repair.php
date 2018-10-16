<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("ά����¼����");
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
        Message("",_("��û�в�ѯȨ�ޣ�"));
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
        <h4 class="pull-left"><?=_("ά����¼����")?></h4>
        <div class="pull-right" >
            <input type="button" class="btn btn-small" value="<?=_("��ӡ")?>" onClick="window.print();">&nbsp;&nbsp;
            <input type="button" class="btn btn-small" value="<?=_("����")?>" onClick="jQuery('#OPERATION').val('excel');document.form1.submit();">
        </div>
    </div>
    <table >
        <tr>
            <td align="right">
                <form name="form1" action="repair.php" style="margin-bottom:5px;">
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
        if((!is_date($FROM_DATE))&&$FROM_DATE!="")
        {
            Message(_("����"),_("��ѯ��ʼ���ڸ�ʽ���ԣ�Ӧ���� 2009-06-30"));
            exit;
        }

        if((!is_date($TO_DATE))&&$TO_DATE!="")
        {
            Message(_("����"),_("��ѯ��ֹ���ڸ�ʽ���ԣ�Ӧ���� 2009-06-30"));
            exit;
        }

        if($TO_DATE!=""&&$FROM_DATE!="")
        {
            if(compare_date($FROM_DATE,$TO_DATE)==1)
            {
                Message(_("����"),_("��ѯ��ʼ���ڲ������ڲ�ѯ��ֹ����!"));
                exit;
            }
        }

        //-----------�ϳ�SQL���-----------
        if($PRO_ID!=-1 and $PRO_ID!="")
            $WHERE_STR1.=" AND a.PRO_ID='$PRO_ID'";
        if($OFFICE_PROTYPE!=-1 and $OFFICE_PROTYPE!="")
            $WHERE_STR1.=" AND a.OFFICE_PROTYPE='$OFFICE_PROTYPE'";
        if($OFFICE_DEPOSITORY!="")
            $WHERE_STR1.=" and a.OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)";
        if($FROM_DATE!="")
            $WHERE_STR1.=" and b.TRANS_DATE>='$FROM_DATE'";
        if($TO_DATE!="")
            $WHERE_STR1.=" and b.TRANS_DATE<='$TO_DATE'";

        //��ӿ��Ȩ������
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
            $WHERE_STR1.=" and a.OFFICE_PROTYPE in($O_PROTYPE)";

        //============================��ʾ�������=======================================
        $query = "SELECT a.PRO_ID,a.PRO_NAME,a.PRO_UNIT,a.PRO_DESC,b.AVAILABLE,b.TRANS_DATE,b.OPERATOR,b.REMARK FROM OFFICE_PRODUCTS a LEFT OUTER JOIN OFFICE_TRANSHISTORY b on a.PRO_ID=b.PRO_ID where b.TRANS_FLAG ='5' ".$WHERE_STR1." ORDER BY PRO_ORDER,PRO_ID";
        $cursor= exequery(TD::conn(),$query);
        $VOTE_COUNT=0;
        $EXCEL_HEAD = array(_("�칫��ƷID"),_("�칫��Ʒ����"),_("ά��ʱ���"),_("����Ա"),_("��ע"));
        $EXCEL_OUT_ARRAY = array();

        while($ROW=mysql_fetch_array($cursor))
        {
            $title="";
            $VOTE_COUNT++;
            $PRO_ID     = $ROW["PRO_ID"];
            $PRO_NAME   = $ROW["PRO_NAME"];
            $PRO_UNIT   = $ROW["PRO_UNIT"];
            $TRANS_DATE = $ROW["TRANS_DATE"];
            $OPERATOR   = substr(GetUserNameById($ROW["OPERATOR"]),0,-1);
            $REMARK     = $ROW["REMARK"];
            $PRO_DESC   = $ROW["PRO_DESC"];
            $AVAILABLE  = $ROW["AVAILABLE"];

            if($AVAILABLE!="")
            {
                $time_array =  explode("|",$AVAILABLE);
                $time1 = date("Y-m-d",$time_array[0]);
                $time2 = date("Y-m-d",$time_array[1]);

                $thistime = $time1."��".$time2;
            }
            if($VOTE_COUNT==1)
            {

            if($FROM_DATE!=""||$TO_DATE!="")
                echo $FROM_DATE." "._("��")." ".$TO_DATE;
            ?>
        <table  class="table table-striped table-bordered">
            <?
            if($VOTE_COUNT>0)
            {
                ?>
                <thead>
                <th nowrap style="text-align: center;"><?=_("����")?></th>
                <th nowrap style="text-align: center;"><?=_("�칫��Ʒ����")?></th>
                <th nowrap style="text-align: center;"><?=_("���/�ͺ�")?></th>
                <th nowrap style="text-align: center;"><?=_("ά��ʱ��")?></th>
                <th nowrap style="text-align: center;"><?=_("����Ա")?></th>
                <th nowrap style="text-align: center;"><?=_("��ע")?></th>
                </thead>
                <?
            }
            ?>
            <?
            }
            if($VOTE_COUNT%2==1)
                $TableLine="TableLine1";
            else
                $TableLine="TableLine2";
            ?>
            <tr>
                <td nowrap style="text-align: center;"><?=$VOTE_COUNT?></td>
                <td nowrap style="text-align: center;"><?=$PRO_NAME?></td>
                <td nowrap style="text-align: center;"><?=$PRO_DESC?></td>
                <td nowrap style="text-align: center;"><?=$AVAILABLE==""?"$TRANS_DATE":"$thistime";?></td>
                <td nowrap style="text-align: center;"><?=$OPERATOR?></td>
                <td nowrap style="text-align: center;"><?=$REMARK?></td>
            </tr>
            <?
            $EXCEL_OUT = "";
            $EXCEL_OUT.=format_cvs($PRO_ID).",";
            $EXCEL_OUT.=format_cvs($PRO_NAME).",";
            $EXCEL_OUT.=format_cvs($TRANS_DATE).",";
            $EXCEL_OUT.=format_cvs($OPERATOR).",";
            $EXCEL_OUT.=format_cvs($REMARK);
            $EXCEL_OUT_ARRAY[] = $EXCEL_OUT;
            }
            if($VOTE_COUNT=='0')
                Message("",_("�޷��������ļ�¼!"));
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
    $objExcel->setFileName(_("ά����¼����"));

    $objExcel->addHead($EXCEL_HEAD);

    foreach($EXCEL_OUT_ARRAY as $EXCEL_OUT)
        $objExcel->addRow($EXCEL_OUT);

    $objExcel->Save();
}
?>
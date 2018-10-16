<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("归还查看");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/reportform.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>

<body>
    <div>
        <h4 ><?=_("归还查看")?></h4>
    </div>

<div style="text-align: center;">
    <table class="table table-striped table-bordered">
        <tr class="TableHeader">
          <th nowrap style="text-align: center;"><?=_("办公用品")?>ID</th>
          <th nowrap style="text-align: center;"><?=_("办公用品名称")?></th>
          <th nowrap style="text-align: center;"><?=_("归还数量")?></th>
          <th nowrap style="text-align: center;"><?=_("归还人")?></th>
          <th nowrap style="text-align: center;"><?=_("归还部门")?></th>
          <th nowrap style="text-align: center;"><?=_("操作员")?></th>
          <th nowrap style="text-align: center;"><?=_("归还日期")?></th>
        </tr>
        <?
            //============================显示明细查询=======================================
            $query1="SELECT a.PRO_ID, b.PRO_NAME ,a.TRANS_QTY ,b.PRO_UNIT,a.TRANS_DATE,a.OPERATOR,a.BORROWER  FROM `OFFICE_TRANSHISTORY` a LEFT OUTER JOIN OFFICE_PRODUCTS b ON a.PRO_ID = b.PRO_ID LEFT OUTER JOIN USER c ON `BORROWER`= c.USER_ID LEFT OUTER JOIN DEPARTMENT d ON d.DEPT_ID = c.DEPT_ID where a.PRO_ID='$PRO_ID' and  a.TRANS_FLAG= '3' and TRANS_DATE>='$DATE' and c.DEPT_ID='$DEP_ID'";

            $cursor1= exequery(TD::conn(),$query1);
            $DETAIL_COUNT=0;
            while($ROW=mysql_fetch_array($cursor1))
            {
                 $DETAIL_COUNT++;
                 $DETAIL_PROID=$ROW["PRO_ID"];
                 $DETAIL_PRONAME=$ROW["PRO_NAME"];
                 $DETAIL_TRANSQTY=$ROW["TRANS_QTY"];
                 $DETAIL_PROUNIT=$ROW["PRO_UNIT"];
                 $DETAIL_PRICE=$ROW["PRICE"];
                 $DETAIL_USER=$ROW["OPERATOR"];
                 $DETAIL_BORROWER=$ROW["BORROWER"];
                 $DETAIL_DATE=$ROW["TRANS_DATE"];
                 $TOK=strtok($DETAIL_USER,",");
                 $query1="select USER_NAME from USER where USER_ID='$TOK'";
                 $cursor2= exequery(TD::conn(),$query1);
                 if($ROW=mysql_fetch_array($cursor2))
                 $DETAIL_USERNAME=$ROW["USER_NAME"];
                 $TOK=strtok($DETAIL_BORROWER,",");
                 $query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME,a.DEPT_ID as DEPT_ID FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$TOK'";
                 $cursor2= exequery(TD::conn(),$query1);
                 if($ROW=mysql_fetch_array($cursor2))
                 {
                     $DETAIL_BORROWERNAME=$ROW["USER_NAME"];
                     $DETAIL_BORROWERDEPT=$ROW["DEPT_NAME"];
                     $DETAIL_BORROWERDEPTID=$ROW["DEPT_ID"];
                 }
                 if($DETAIL_COUNT%2==1)
                     $TableLine="TableLine1";
                 else
                    $TableLine="TableLine2";
        ?>
       <tr>
       <td nowrap style="text-align: center;"><?=$DETAIL_PROID?></td>
       <td style="text-align: center;"><?=$DETAIL_PRONAME?></td>
       <td nowrap style="text-align: center;"><?=$DETAIL_TRANSQTY?><?=$DETAIL_PROUNIT?></td>
       <td nowrap style="text-align: center;"><?=$DETAIL_BORROWERNAME?></td>
       <td nowrap style="text-align: center;"><?=$DETAIL_BORROWERDEPT?></td>
       <td nowrap style="text-align: center;"><?=$DETAIL_USERNAME?></td>
       <td nowrap style="text-align: center;"><?=$DETAIL_DATE?></td>
       </tr>
<?
           }
?>
    </table>
   <br>
</div>
</body>
</html>
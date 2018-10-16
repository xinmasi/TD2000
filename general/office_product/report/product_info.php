<?
if(isset($_GET["curpage"])){
    $_GET["url"] = "product_info.php";
    include_once("main.php");
}
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("办公用品总表");
include_once("inc/header.inc.php");
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
<div class="clearfix" style="width: 99%;">
    <h4 class="pull-left" style="font-weight: 600;"><?=_("物品总量信息")?></h4>
    <div class="pull-right" >
        <input type="button" class="btn btn-small" value="<?=_("打印")?>" onClick="window.print();">&nbsp;&nbsp;
        <input type="button" class="btn btn-small" value="<?=_("导出")?>" onClick="jQuery('#OPERATION').val('excel');document.form1.submit();">
    </div>
</div>
<table style="width: 99%;" id="table_border">
    <tr>
        <td>
            <form name="form1" action="product_info.php" style="margin-bottom:5px;">
                <input type="hidden" id="OPERATION" name="OPERATION" value="">
                <input type="hidden" id="OFFICE_PROTYPE" name="OFFICE_PROTYPE" value="<?=$OFFICE_PROTYPE?>">
                <input type="hidden" id="OFFICE_DEPOSITORY" name="OFFICE_DEPOSITORY" value="<?=$OFFICE_DEPOSITORY?>">
                <input type="hidden" id="PRO_ID" name="PRO_ID" value="<?=$PRO_ID?>">
                <input type="hidden" id="FROM_DATE" name="FROM_DATE" value="<?=$FROM_DATE?>">
                <input type="hidden" id="TO_DATE" name="TO_DATE" value="<?=$TO_DATE?>">
            </form>
    </tr>
</table>
<div id="table_info">
<?
if((!is_date($FROM_DATE))&&$FROM_DATE!="")
{
    Message(_("错误"),_("查询起始日期格式不对，应形如 2009-06-30"));
    exit;
}

if((!is_date($TO_DATE))&&$TO_DATE!="")
{
    Message(_("错误"),_("查询终止日期格式不对，应形如 2009-06-30"));
    exit;
}

if($TO_DATE!=""&&$FROM_DATE!="")
{
    if(compare_date($FROM_DATE,$TO_DATE)==1)
    {
        Message(_("错误"),_("查询起始日期不能晚于查询终止日期!"));
        exit;
    }
}
?>

<?
//-----------合成SQL语句-----------
if($OFFICE_DEPOSITORY!="")
    $WHERE_STR1.=" AND OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)";//物品所属类别

if($PRO_ID!=-1 and $PRO_ID!="")
    $WHERE_STR1.=" AND PRO_ID='$PRO_ID'";

if($OFFICE_PROTYPE!=-1 and $OFFICE_PROTYPE!="")
    $WHERE_STR1.="AND OFFICE_PROTYPE='$OFFICE_PROTYPE'";

/*if($FROM_DATE!="")
    $WHERE_STR2.=" and TRANS_DATE>='$FROM_DATE'";
if($TO_DATE!="")
    $WHERE_STR2.=" and TRANS_DATE<='$TO_DATE'";*/
//---------合成SQL语句(分页)------------
if($_GET['OFFICE_DEPOSITORY']!="")
    $WHERE_STR1.="AND OFFICE_PROTYPE in (".$_GET['OFFICE_DEPOSITORY'].")";

if($_GET['PRO_ID']!=-1 and $_GET['PRO_ID']!="")
    $WHERE_STR1.=" AND PRO_ID=".$_GET['PRO_ID'];

if($_GET['OFFICE_PROTYPE']!=-1 and $_GET['OFFICE_PROTYPE']!="")
    $WHERE_STR1.=" AND OFFICE_PROTYPE=".$_GET['OFFICE_PROTYPE'];

/*if($_GET['FROM_DATE']!="")
    $WHERE_STR3.=" and TRANS_DATE>=".$_GET['FROM_DATE'];
if($_GET['TO_DATE']!="")
    $WHERE_STR3.=" and TRANS_DATE<=".$_GET['TO_DATE'];*/

//添加库管权限条件
if($_SESSION["LOGIN_USER_PRIV"]!=1)
    $WHERE_STR1.=" and OFFICE_PROTYPE in($O_PROTYPE)";
//============================显示操作结果=======================================
$TYPE = "OFFICE_DEPOSITORY=".$OFFICE_DEPOSITORY."&PRO_ID=".$PRO_ID."&OFFICE_PROTYPE=".$OFFICE_PROTYPE."&FROM_DATE=".$FROM_DATE."&TO_DATE=".$TO_DATE."&";
$query1 = "SELECT count(*) as totle_nums FROM OFFICE_PRODUCTS where 1=1 ".$WHERE_STR1;
$cursor1= exequery(TD::conn(),$query1);
while($ROW=mysql_fetch_array($cursor1)){
    $nums = $ROW["totle_nums"];
}
$page_limit = 4;
$page_total = intval(ceil($nums/$page_limit));
$curpage = $_GET['curpage'];
$curpage = ($curpage ? $curpage : 1);
$start = ($curpage -1)*$page_limit;
$limit = ' LIMIT '.$start.','.$page_limit;
//=============================分页=============================================
$query = "SELECT PRO_ID,PRO_NAME,PRO_UNIT,PRO_STOCK,PRO_PRICE,PRO_DESC FROM OFFICE_PRODUCTS where 1=1 ".$WHERE_STR1." ORDER BY PRO_ORDER,PRO_ID".$limit;
$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;

$EXCEL_HEAD = array(_("办公用品ID"),_("办公用品名称"),_("计量单位"),_("单价"),_("导入数量"),_("当前库存"),_("库存金额"),_("采购量"),_("领用量"),_("借出量"),_("归还量"),_("未归还量"),_("报废量"));
$EXCEL_OUT_ARRAY = array();

while($ROW=mysql_fetch_array($cursor))
{
    $title="";
    $VOTE_COUNT++;
    $PRO_ID    = $ROW["PRO_ID"];
    $PRO_PRICE = $ROW["PRO_PRICE"];
    $PRO_NAME  = $ROW["PRO_NAME"];
    $PRO_UNIT  = $ROW["PRO_UNIT"];
    $PRO_STOCK = $ROW["PRO_STOCK"];
    $PRO_DESC  = $ROW['PRO_DESC'];

    $Tquery = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID'  AND TRANS_FLAG='0'".$WHERE_STR2;
    $Tcursor= exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
        $SUM_BUY=$TROW["SUM_TRANS_QTY"];
    if($SUM_BUY<0)
        $SUM_BUY=$SUM_BUY*(-1);

    $Tquery = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='1'  AND (TRANS_STATE='' or TRANS_STATE='1')".$WHERE_STR2;
    $Tcursor= exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
        $SUM_USE2=$TROW["SUM_TRANS_QTY"];
    if($SUM_USE2<0)
        $SUM_USE2=$SUM_USE2*(-1);


    $SUM_USE=$SUM_USE2;
    $Tquery = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='2'  AND (TRANS_STATE ='1' or TRANS_STATE='')".$WHERE_STR2;
    $Tcursor= exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
        $SUM_BORROW1=$TROW["SUM_TRANS_QTY"];
    if($SUM_BORROW1<0)
        $SUM_BORROW1=$SUM_BORROW1*(-1);
    $SUM_BORROW = $SUM_BORROW1;
    $Tquery = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY   WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='2'  AND TRANS_STATE ='1' AND RETURN_STATUS = '1'".$WHERE_STR2;
    $Tcursor= exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
        $SUM_RETURN=$TROW["SUM_TRANS_QTY"];
    if($SUM_RETURN<0)
        $SUM_RETURN=$SUM_RETURN*(-1);
    $Tquery = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY   WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='2'  AND TRANS_STATE ='1' AND RETURN_STATUS != '1'".$WHERE_STR2;
    $Tcursor= exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
        $NO_RETURN=$TROW["SUM_TRANS_QTY"];
    if($NO_RETURN<0)
        $NO_RETURN=$NO_RETURN*(-1);

    $Tquery = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID'  AND TRANS_FLAG='4'".$WHERE_STR2;
    $Tcursor= exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
        $SUM_REJECT=$TROW["SUM_TRANS_QTY"];
    if($SUM_REJECT<0)
        $SUM_REJECT=$SUM_REJECT*(-1);
    $CREATE_QTY = 0;
    $Tquery = "SELECT TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='6' ";
    $Tcursor= exequery(TD::conn(),$Tquery);
    while($TROW=mysql_fetch_array($Tcursor))
    {
        $CREATE_QTY = $CREATE_QTY+$TROW["TRANS_QTY"];
    }
    if($VOTE_COUNT==1)
    {
    ?>
    <table  class="table table-striped table-bordered tableview" style="text-align: center;" style="width: 99%;">
        <thead>
        <tr>
            <th nowrap style="text-align: center;"><?=_("排序")?></th>
            <th nowrap style="text-align: center;"><?=_("办公用品名称")?></th>
            <th nowrap style="text-align: center;"><?=_("计量单位")?></th>
            <th nowrap style="text-align: center;"><?=_("单价")?></th>
            <th nowrap style="text-align: center;"><?=_("规格")?></th>
            <th nowrap style="text-align: center;"><?=_("当前库存")?></th>
            <th nowrap style="text-align: center;"><?=_("库存金额")?></th>
            <th nowrap style="text-align: center;"><?=_("采购量")?></th>
            <th nowrap style="text-align: center;"><?=_("领用量")?></th>
            <th nowrap style="text-align: center;"><?=_("借出量")?></th>
            <th nowrap style="text-align: center;"><?=_("归还量")?></th>
            <th nowrap style="text-align: center;"><?=_("未归还量")?></th>
            <th nowrap style="text-align: center;"><?=_("报废量")?></th>
        </tr>
        </thead>
        <?
        if($FROM_DATE!=""||$TO_DATE!="")
            echo $FROM_DATE." "._("至")." ".$TO_DATE;
        }
        ?>
        <tr>
            <td nowrap style="text-align: center;"><?=$VOTE_COUNT+($curpage-1)*$page_limit?></td>
            <td style="text-align: center;"><?=$PRO_NAME?></td>
            <td nowrap style="text-align: center;"><?=$PRO_UNIT?></td>
            <td nowrap style="text-align: center;"><?=$PRO_PRICE?></td>
            <td style="text-align: center;"><?=$PRO_DESC?></td>
            <td nowrap style="text-align: center;"><?=$PRO_STOCK?></td>
            <td nowrap style="text-align: center;"><?=$PRO_STOCK*$PRO_PRICE?></td>
            <td nowrap style="text-align: center;"><?=$SUM_BUY?></td>
            <td nowrap style="text-align: center;"><?=$SUM_USE2?></td>
            <td nowrap style="text-align: center;"><?=$SUM_BORROW1?></td>
            <td nowrap style="text-align: center;"><?=$SUM_RETURN?></td>
            <td nowrap style="text-align: center;"><?=$NO_RETURN?></td>
            <td nowrap style="text-align: center;"><?=$SUM_REJECT?></td>
        </tr>
        <?
}
//导出
$query  = "SELECT PRO_ID,PRO_NAME,PRO_UNIT,PRO_STOCK,PRO_PRICE,PRO_DESC FROM OFFICE_PRODUCTS where 1=1 ".$WHERE_STR1." ORDER BY PRO_ORDER,PRO_ID";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $title="";
    $VOTE_COUNT++;
    $PRO_ID    = $ROW["PRO_ID"];
    $PRO_PRICE = $ROW["PRO_PRICE"];
    $PRO_NAME  = $ROW["PRO_NAME"];
    $PRO_UNIT  = $ROW["PRO_UNIT"];
    $PRO_STOCK = $ROW["PRO_STOCK"];

    $Tquery  = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID'  AND TRANS_FLAG='0'";
    $Tcursor = exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
    {
        $SUM_BUY=$TROW["SUM_TRANS_QTY"];
    }
    if($SUM_BUY<0)
    {
        $SUM_BUY=$SUM_BUY*(-1);
    }

    $Tquery  = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='1'  AND (TRANS_STATE='' or TRANS_STATE='1')";
    $Tcursor = exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
    {
        $SUM_USE2=$TROW["SUM_TRANS_QTY"];
    }
    if($SUM_USE2<0)
    {
        $SUM_USE2=$SUM_USE2*(-1);
    }


    $SUM_USE = $SUM_USE2;
    $Tquery  = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='2'  AND (TRANS_STATE ='1' or TRANS_STATE='')";
    $Tcursor = exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
    {
        $SUM_BORROW1=$TROW["SUM_TRANS_QTY"];
    }
    if($SUM_BORROW1<0)
    {
        $SUM_BORROW1=$SUM_BORROW1*(-1);
    }


    $SUM_BORROW = $SUM_BORROW1;

    $Tquery  = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY   WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='2'  AND TRANS_STATE ='1' AND RETURN_STATUS = '1'";
    $Tcursor = exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
    {
        $SUM_RETURN=$TROW["SUM_TRANS_QTY"];
    }
    if($SUM_RETURN<0)
    {
        $SUM_RETURN=$SUM_RETURN*(-1);
    }
    $Tquery  = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY   WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='2'  AND TRANS_STATE ='1' AND RETURN_STATUS != '1'";
    $Tcursor = exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
    {
        $NO_RETURN=$TROW["SUM_TRANS_QTY"];
    }
    if($NO_RETURN<0)
    {
        $NO_RETURN=$NO_RETURN*(-1);
    }

    $Tquery  = "SELECT SUM(TRANS_QTY) AS SUM_TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID'  AND TRANS_FLAG='4'";
    $Tcursor = exequery(TD::conn(),$Tquery);
    if($TROW=mysql_fetch_array($Tcursor))
    {
        $SUM_REJECT=$TROW["SUM_TRANS_QTY"];
    }
    if($SUM_REJECT<0)
    {
        $SUM_REJECT=$SUM_REJECT*(-1);
    }
    $CREATE_QTY = 0;
    $Tquery  = "SELECT TRANS_QTY FROM OFFICE_TRANSHISTORY  WHERE PRO_ID='$PRO_ID' AND TRANS_FLAG='6' ";
    $Tcursor = exequery(TD::conn(),$Tquery);
    while($TROW=mysql_fetch_array($Tcursor))
    {
        $CREATE_QTY = $CREATE_QTY+$TROW["TRANS_QTY"];
    }

    $EXCEL_OUT = "";
    $EXCEL_OUT.=format_cvs($PRO_ID).",";
    $EXCEL_OUT.=format_cvs($PRO_NAME).",";
    $EXCEL_OUT.=format_cvs($PRO_UNIT).",";
    $EXCEL_OUT.=format_cvs($PRO_PRICE).",";
    $EXCEL_OUT.=format_cvs($CREATE_QTY).",";
    $EXCEL_OUT.=format_cvs($PRO_STOCK).",";
    $EXCEL_OUT.=format_cvs($PRO_PRICE*$PRO_STOCK).",";
    $EXCEL_OUT.=format_cvs($SUM_BUY).",";
    $EXCEL_OUT.=format_cvs($SUM_USE).",";
    $EXCEL_OUT.=format_cvs($SUM_BORROW).",";
    $EXCEL_OUT.=format_cvs($SUM_RETURN).",";
    $EXCEL_OUT.=format_cvs($NO_RETURN).",";
    $EXCEL_OUT.=format_cvs($SUM_REJECT);
    $EXCEL_OUT_ARRAY[] = $EXCEL_OUT;
}
if($VOTE_COUNT==0)
{
    Message(_("提示"),_("无符合条件的记录！"));
    exit;
}
?>
    </table>
    </div>
<?
if($page_total != 1)
{
    echo paginator('id', $curpage, $page_total, $page_limit, 'product_info.php?'.$TYPE.'curpage={curpage}', 'right');
}
if($OPERATION=="excel")
{
    ob_end_clean();
    require_once ('inc/ExcelWriter.php');

    $objExcel = new ExcelWriter();
    $objExcel->setFileName(_("物品总量信息"));

    $objExcel->addHead($EXCEL_HEAD);

    foreach($EXCEL_OUT_ARRAY as $EXCEL_OUT)
        $objExcel->addRow($EXCEL_OUT);

    $objExcel->Save();
}
?>

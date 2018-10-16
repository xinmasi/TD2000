<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

function my_dept_priv_id1($POST_PRIV="",$POST_DEPT="",$USER_FLAG=false)
{
    if($POST_PRIV=="")
    {
        $query = "SELECT POST_PRIV,POST_DEPT from USER where UID='".$_SESSION["LOGIN_UID"]."'";
        $cursor= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $POST_PRIV=$ROW["POST_PRIV"];
            $POST_DEPT=$ROW["POST_DEPT"];
        }
    }

    $DEPT_ID_STR = "";
    $DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');
    while(list($DEPT_ID, $DEPT) = each($DEPARTMENT_ARRAY))
    {
        $DEPT_ID_STR .= $DEPT_ID.",";
    }

    return $DEPT_ID_STR;
}

$HTML_PAGE_TITLE = _("部门领用汇总");
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
    <h4 class="pull-left"><?=_("部门领用汇总")?></h4>
    <div class="pull-right" >
        <input type="button" class="btn btn-small" value="<?=_("打印")?>" onClick="window.print();">&nbsp;&nbsp;
        <input type="button" class="btn btn-small" value="<?=_("导出")?>" onClick="jQuery('#OPERATION').val('excel');document.form1.submit();">
    </div>
</div>
<table>
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

<br>
<div>
    <?
    if($OPERATION=="excel")
    {
        ob_end_clean();
        Header("Cache-control: private");
        Header("Content-type: application/vnd.ms-excel");
        Header("Content-Disposition: attachment; ".get_attachment_filename(_("部门领用汇总").".xls"));
    }
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
            where b.DEPT_ID='$DEPT_ID' AND c.TRANS_FLAG='1'".$WHERE_STR."
            AND (c.TRANS_STATE='' or c.TRANS_STATE='1') GROUP BY b.DEPT_ID,BORROWER,c.PRO_ID";
        }else
        {
            $query = "SELECT b.USER_NAME as BORROWER,d.PRO_ID as PRO_ID,d.PRO_NAME as PRO_NAME,d.PRO_UNIT as PRO_UNIT,d.PRO_PRICE as old_Price,SUM(c.FACT_QTY) AS QTY, c.TRANS_DATE as TRANS_DATE,c.PRICE as Price,SUM(c.FACT_QTY*d.PRO_PRICE) AS OLD_TOTAL_PRICE,SUM(c.FACT_QTY*c.PRICE) AS TOTAL_PRICE
            FROM `DEPARTMENT` a
            LEFT OUTER JOIN `USER` b ON a.DEPT_ID = b.DEPT_ID
            LEFT OUTER JOIN OFFICE_TRANSHISTORY c ON `BORROWER`= b.USER_ID
            LEFT OUTER JOIN OFFICE_PRODUCTS d ON d.PRO_ID = c.PRO_ID
            LEFT OUTER JOIN OFFICE_TYPE e ON  d.OFFICE_PROTYPE=e.ID
            LEFT OUTER JOIN OFFICE_DEPOSITORY f  ON f.ID=e.TYPE_DEPOSITORY
            where b.DEPT_ID='$DEPT_ID' AND c.TRANS_FLAG='1'".$WHERE_STR." and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',f.MANAGER)
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
                <tr class=TableData>
                    <td>".$BORROWER."</td>
                    <td>".$PRO_ID."</td>
                    <td>".$PRO_NAME."</td>
                    <td>".$QTY."</td>
                    <td>".$UNIT."</td>
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
            $PRO_ID       = $ROW["PRO_ID"];
            $PRO_NAME     = $ROW["PRO_NAME"];
            $QTY          = $ROW["QTY"]*(-1);
            $Price        = $ROW["Price"];
            $TOTAL_PRICE  = $ROW["TOTAL_PRICE"];
            if($TOTAL_PRICE==0)
            {
                $TOTAL_PRICE = $Price*$QTY;
            }
            $UNIT         = $ROW["PRO_UNIT"];
            $TRANS_DATE   = $ROW["TRANS_DATE"];
            $BORROWER     = $ROW["BORROWER"];
            $TOTAL_SUM    = $TOTAL_SUM+$TOTAL_PRICE;

            $SUM_TEXT.="
                <tr class=TableData>
                    <td>".$BORROWER."</td>
                    <td>".$PRO_ID."</td>
                    <td>".$PRO_NAME."</td>
                    <td>".$QTY."</td>
                    <td>".$UNIT."</td>
                    <td>".$TRANS_DATE."</td>
                    <td>".$Price."</td>
                    <td>".$TOTAL_PRICE."</td>
                ";
        }//while
        return $SUM_TEXT;
    }

    //------ 递归显示部门列表，支持按管理范围显示 --------

    function dept_tree_list($DEPT_ID,$DEPT_CHOOSE,$POST_OP,$WHERE_STR,$NO_CHILD_DEPT=0)
    {
        global $TOTAL_SUM;
        $OPTION_TEXT = "";

        if(is_array($POST_OP))
        {
            $DEPT_PRIV=$POST_OP["DEPT_PRIV"];
            $DEPT_ID_STR=$POST_OP["DEPT_ID_STR"];
        }
        if($DEPT_ID==0)
            $LEVEL=0;

        $DEPT_PRIV_ID_STR = my_dept_priv_id1($DEPT_PRIV, $DEPT_ID_STR);
        $DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');
        while($DEPT = current($DEPARTMENT_ARRAY))
        {
            $ID=key($DEPARTMENT_ARRAY);
            if($ID==$DEPT_ID)
                $LEVEL=$DEPT["DEPT_LEVEL"];

            if(!isset($LEVEL) && $ID!=$DEPT_ID)
            {
                next($DEPARTMENT_ARRAY);
                continue;
            }
            if($NO_CHILD_DEPT && $NO_CHILD_DEPT==$ID)
            {
                while($DEPT_NEXT = next($DEPARTMENT_ARRAY))
                {
                    if($DEPT_NEXT["DEPT_LEVEL"]<=$DEPARTMENT_ARRAY[$NO_CHILD_DEPT]["DEPT_LEVEL"])
                        break;
                }
                prev($DEPARTMENT_ARRAY);
            }

            if($LEVEL>=$DEPT["DEPT_LEVEL"] && $ID!=$DEPT_ID)
                break;

            $DEPT_NAME=$DEPT["DEPT_NAME"];
            $DEPT_NAME=td_htmlspecialchars($DEPT_NAME);
            if ($_SESSION["LOGIN_USER_PRIV"]==1)
            {
                $query1="SELECT count(distinct c.PRO_ID)
                 FROM `DEPARTMENT` a
                 LEFT OUTER JOIN `USER` b ON a.DEPT_ID = b.DEPT_ID
                 LEFT OUTER JOIN OFFICE_TRANSHISTORY c ON `BORROWER`= b.USER_ID
                 LEFT OUTER JOIN OFFICE_PRODUCTS d ON d.PRO_ID = c.PRO_ID  WHERE a.DEPT_ID ='$ID' and c.TRANS_FLAG='1' and(c.TRANS_STATE='' or c.TRANS_STATE=1)".$WHERE_STR;
            }else
            {
                $query1="SELECT count(distinct c.PRO_ID)
                 FROM `DEPARTMENT` a
                 LEFT OUTER JOIN `USER` b ON a.DEPT_ID = b.DEPT_ID
                 LEFT OUTER JOIN OFFICE_TRANSHISTORY c ON `BORROWER`= b.USER_ID
                 LEFT OUTER JOIN OFFICE_PRODUCTS d ON d.PRO_ID = c.PRO_ID
                 LEFT OUTER JOIN OFFICE_TYPE e ON  d.OFFICE_PROTYPE=e.ID
                 LEFT OUTER JOIN OFFICE_DEPOSITORY f ON f.ID=e.TYPE_DEPOSITORY
                 WHERE a.DEPT_ID ='$ID' and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',f.MANAGER) and c.TRANS_FLAG='1' and(c.TRANS_STATE='' or c.TRANS_STATE=1)".$WHERE_STR;
            }
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
                $VOTE_COUNT=$ROW[0];

            $DEPT_LINE=str_replace("│", "&nbsp;&nbsp;", $DEPT['DEPT_LINE']);

            if(!$POST_OP || find_id($DEPT_PRIV_ID_STR, $ID))
            {
                if ($VOTE_COUNT!=0)
                {
                    $OPTION_TEXT.="
        <tr >
            <td>".$DEPT_LINE.$DEPT_NAME."</a></td>
            <td>
                <table  class="."'table table-bordered'".">
                    <tr >
                    <th nowrap align=center>"._("领用人")."</th>
                    <th nowrap align=center>"._("办公用品ID")."</th>
                    <th nowrap align=center>"._("办公用品名称")."</th>
                    <th nowrap align=center>"._("领用总量")."</th>
                    <th nowrap align=center>"._("计量单位")."</th>
                    <th nowrap align=center>"._("领用日期")."</th>
                    <th nowrap align=center>"._("单价")."</th>
                    <th nowrap align=center><b>"._("总价")."</b></th>
                    </tr>".dept_sum_list($ID,$WHERE_STR)."
                </table>"._("总价合计：").$TOTAL_SUM."
            </td>
        </tr>";
                }
                else
                {
                    $OPTION_TEXT.="
        <tr>
            <td>".$DEPT_LINE.$DEPT_NAME."</a></td>
            <td>
            </td>
        </tr>";
                }
            }
            next($DEPARTMENT_ARRAY);
        }
        return $OPTION_TEXT;
    }

    if($DEPT_ID=="")
        $DEPT_ID=0;

    $OPTION_TEXT = "";

    $OPTION_TEXT_ALL=dept_tree_list(0,$DEPT_ID,1,$WHERE_STR);
    if($OPTION_TEXT_ALL=="")
    {
        Message(_("提示"),_("未定义或无可管理部门"));
        ?>
        <div style="text-align: center;"><input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="javascript:window.close();"></div>
        <?
    }
    else
    {
        ?>
        <table class="table table-bordered">
            <?
            echo dept_tree_list(0,$DEPT_ID,1,$WHERE_STR);
            ?>
            <tr>
                <td><?=_("离职或外部人员")?></td>
                <?
                $con_str="where b.DEPT_ID=0";
                if($FROM_DATE!="")
                    $con_str.=" and a.TRANS_DATE>='$FROM_DATE'";
                if($TO_DATE!="")
                    $con_str.=" and a.TRANS_DATE<='$TO_DATE'";
                if($PRO_ID!="")
                    $con_str.=" and a.PRO_ID='$PRO_ID'";
                if($OFFICE_PROTYPE!="")
                    $con_str.=" and c.OFFICE_PROTYPE='$OFFICE_PROTYPE'";
                $query="SELECT count(*) FROM office_transhistory a LEFT OUTER JOIN USER b ON a.BORROWER = b.USER_ID LEFT OUTER JOIN office_products c ON a.PRO_ID = c.PRO_ID ".$con_str;
                $cursor= exequery(TD::conn(),$query);
                $num_temp=0;
                if($ROW=mysql_fetch_array($cursor))
                    $num_temp=$ROW[0];

                if($num_temp!=0)
                {
                    ?>
                    <td nowrap valign=\"bottom\">
                        <table class="table table-bordered">
                            <tr>
                                <th nowrap align=center><?=_("领用人")?></th>
                                <th nowrap align=center><?=_("办公用品")?>ID</th>
                                <th nowrap align=center><?=_("办公用品名称")?></th>
                                <th nowrap align=center><?=_("领用总量")?></th>
                                <th nowrap align=center><?=_("计量单位")?></th>
                                <th nowrap align=center><?=_("领用日期")?></th>
                                <th nowrap align=center><?=_("单价")?></th>
                                <th nowrap align=center><?=_("总价")?></th>
                            </tr><?=disdept_sum_list($WHERE_STR1)?>
                        </table><?=_("合计：")?><?=$TOTAL_SUM?>
                    </td>
                    <?
                }
                else
                {
                    ?>
                    <td></td>
                    <?
                }
                ?>
            </tr>
        </table>
        <?
    }
    ?>
</div>
<!--
</body>
</html>
-->

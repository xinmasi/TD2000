<?
include_once("inc/auth.inc.php");
include_once ("inc/utility_all.php");
include_once ("inc/utility_org.php");
include_once ("../function_type.php");

$curpage    = ($curpage ? $curpage : 1);
if(!$page_limit)
    $page_limit = get_page_size("OFFICE_PRODECT", 10);
//$page_limit = ($page_limit ? $page_limit : 10);
$start      = ($curpage -1)*$page_limit;

$HTML_PAGE_TITLE = _("办公用品查询列表");
include_once("inc/header.inc.php");



$where = "";
//合成SQL语句
if($TRANS_FLAG == "a1")
{
    if($_SESSION["LOGIN_USER_PRIV"]!=1)
    {
        $where = " AND user_id = '{$_SESSION['LOGIN_USER_ID']}'";
    }
    if($FROM_DATE!="")
    {
        $where .= " AND from_unixtime(add_time,'%Y-%m-%d')>='$FROM_DATE'";
    }
    if($TO_DATE!="")
    {
        $where .= " AND from_unixtime(add_time,'%Y-%m-%d')><='$TO_DATE'";
    }

    $TYPE="FROM_DATE=$FROM_DATE&TO_DATE=$TO_DATE";
    $query = "SELECT * FROM office_log WHERE 1=1".$where." ORDER BY add_time DESC";
    $Tcursor= exequery(TD::conn(),$query);
}
else
{
    if($TRANS_FLAG!="" && $TRANS_FLAG!="-1")
    {
        $where .= " AND a.TRANS_FLAG = '$TRANS_FLAG'";
    }
    if($PRO_ID!="" && $PRO_ID!="-1")
    {
        $where .= " AND a.PRO_ID = '$PRO_ID'";
    }
    if($PRO_NAME!="")
    {
        $where .= " AND b.PRO_NAME like '%".$PRO_NAME."%'";
    }
    if($FROM_DATE!="")
    {
        $where .= " AND a.TRANS_DATE>='$FROM_DATE'";
    }
    if($TO_DATE!="")
    {
        $where .= " AND a.TRANS_DATE<='$TO_DATE'";
    }
    if($TO_ID!="")
    {
        $where .= " AND a.BORROWER = '$TO_ID'";
    }
    if($_SESSION["LOGIN_USER_PRIV"]!=1)
    {
        $num = get_transhistory($_SESSION['LOGIN_USER_ID']);
        $where .= " AND a.PRO_ID in ({$num})";
    }

    $TYPE="TRANS_FLAG=$TRANS_FLAG&PRO_ID=$PRO_ID&PRO_NAME=$PRO_NAME&FROM_DATE=$FROM_DATE&TO_DATE=$TO_DATE&TO_ID=$TO_ID";

    $query = "SELECT a.*,b.PRO_NAME,b.PRO_PRICE,b.PRO_UNIT,b.OFFICE_PROTYPE,c.MANAGER FROM office_transhistory as a left join  office_products as b on a.PRO_ID = b.PRO_ID left join office_depository as c on FIND_IN_SET(b.OFFICE_PROTYPE,c.OFFICE_TYPE_ID) WHERE 1=1 ".$where." ORDER BY a.TRANS_DATE DESC";
    $Tcursor= exequery(TD::conn(),$query);
}

$total_nums = mysql_num_rows($Tcursor);
$page_total = intval(ceil($total_nums/$page_limit));

$query .= " limit $start, $page_limit";
$office_cursor2 = exequery(TD::conn(), $query);



$flag_name = array(1 => _('领用'),2 => _('借用'),0 => _('采购入库'),4 => _('报废'),5 => _('维护'),6 => _('EXCEL导入'));
$log_name  = array(1 => _('调拨'));
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/style.css" />
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js"></script>
<body>
<div class="row-fluid" align="center" >
    <div class="span11" style='float:none;'>
        <div class="set-pagenum">
            <input type="button" class="btn btn-small btn-primary" onClick="javascrtpt:window.location.href='office_query.php'" value="返回">
        </div>
        <h3 style="text-align:left;"><?=_("办公用品查询列表")?></h3>
        <?
        $count = 0;
        $no_count = 0;
        while($office_row1 = @mysql_fetch_array($office_cursor2))
        {
            $count++;
            $OFFICE_PROTYPE = $office_row1["OFFICE_PROTYPE"];
            $sql="select * from office_depository where find_in_set('".$OFFICE_PROTYPE."',OFFICE_TYPE_ID)";
            $result= exequery(TD::conn(),$sql);
            if(mysql_num_rows($result)<=0)
            {
                $no_count++;
            }
            $office_row_arr[] = $office_row1;
        }
        if(mysql_num_rows($office_cursor2)==0 || $count==$no_count)
        {
            Message(_("提示"),_("没有符合条件的记录"));
            //Button_Back();
        }
        elseif($TRANS_FLAG != "a1")
        {

            ?>
            <table  class="table table-bordered center table-hover">
                <thead>
                <tr>
                    <th><?=_("办公用品名称")?></th>
                    <th><?=_("登记类型")?></th>
                    <th><?=_("申请人")?></th>
                    <th><?=_("数量")?></th>
                    <th><?=_("单价")?></th>
                    <th><?=_("操作日期")?></th>
                    <th><?=_("操作员")?></th>
                    <th><?=_("备注")?></th>
                    <th><?=_("附加信息")?></th>
                </tr>
                </thead>
                <?

                foreach($office_row_arr as $key=>$office_row)
                {
                    $sql="select * from office_depository where find_in_set('".$office_row['OFFICE_PROTYPE']."',OFFICE_TYPE_ID)";
                    $result= exequery(TD::conn(),$sql);
                    if(mysql_num_rows($result)<=0)
                    {
                        continue;
                    }

                    ?>
                    <tr id='tr_<?=$office_row['PRO_ID']?>'>
                        <td width="120"><?=$office_row['PRO_NAME']?></td>
                        <td><?=$flag_name["{$office_row['TRANS_FLAG']}"]?></td>
                        <td><?=substr(GetUserNameById($office_row['BORROWER']),0,-1)?></td>
                        <td><?=$office_row['TRANS_FLAG']==5?"-":abs($office_row['TRANS_QTY']).$office_row['PRO_UNIT']?></td>
                        <td><?=$office_row['PRO_PRICE']?></td>
                        <td><?=$office_row['TRANS_DATE']?></td>
                        <td><?=substr(GetUserNameById($office_row['OPERATOR']),0,-1)?></td>
                        <td><?=$office_row['REMARK']?></td>
                        <td><? if($office_row['TRANS_FLAG']=='2'){
                                if($office_row['RETURN_STATUS']==2)
                                {
                                    echo _("归还审批未通过");
                                }
                                elseif($office_row['RETURN_STATUS']==1)
                                {
                                    echo _("已归还");
                                }
                                elseif($office_row['RETURN_STATUS']==0 && $office_row['RETURN_DATE'] =='0000-00-00')
                                {
                                    echo _("未归还");
                                }
                                else
                                {
                                    echo _("归还审批中");
                                }
                                ?>
                            <? }if($office_row['TRANS_FLAG']=='5' && $office_row["AVAILABLE"]!="")
                            {
                                $office_row_arr = explode("|",$office_row["AVAILABLE"]);

                                echo date("Y-m-d",$office_row_arr[0])._("至").date("Y-m-d",$office_row_arr[1]);


                            }?></td>
                    </tr>
                <? }?>
            </table>
            <?
            if($page_total > 1)
            {
                echo paginator('id', $curpage, $page_total, $page_limit, '?'.$TYPE.'&curpage={curpage}', 'right');
            }
        }else{?>
            <table  class="table table-bordered center table-hover">
                <thead>
                <tr>
                    <th><?=_("序号")?></th>
                    <th><?=_("类别")?></th>
                    <th><?=_("操作员")?></th>
                    <th><?=_("操作时间")?></th>
                    <th><?=_("日志内容")?></th>
                    <th><?=_("备注")?></th>
                </tr>
                </thead>
                <?
                $total = 0;
                foreach($office_row_arr as $key=>$office_row)
                {
                    $total++;
                    ?>
                    <tr id='tr_<?=$office_row['PRO_ID']?>'>
                        <td><?=$total?></td>
                        <td><?=$log_name["{$office_row['type']}"]?></td>
                        <td><?=substr(GetUserNameById($office_row['user_id']),0,-1)?></td>
                        <td><?=date("Y-m-d H:i:s",$office_row['add_time'])?></td>
                        <td><?=$office_row['office_str']?></td>
                        <td><?=$office_row['remark']?></td>
                    </tr>
                <? }?>
            </table>
            <?
            if($page_total > 1)
            {
                echo paginator('id', $curpage, $page_total, $page_limit, '?'.$TYPE.'&curpage={curpage}', 'right');
            }
        }
        ?>
    </div>
</div>
</body>
</html>
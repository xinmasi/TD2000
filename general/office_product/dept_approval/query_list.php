<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");
include_once ("inc/utility_all.php");
include_once("../function_type.php");

$curpage    = ($curpage ? $curpage : 1);
if(!$page_limit)
    $page_limit = get_page_size("OFFICE_PRODECT", 10);
//$page_limit = ($page_limit ? $page_limit : 10);
$start      = ($curpage -1)*$page_limit;

$HTML_PAGE_TITLE = _("申领记录");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css"    href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/style.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js"></script>
<body>
<div class="container-fluid">
    <div style='float:none;'>
        <div class="set-pagenum">
            <input type="button" class="btn btn-small btn-primary" onClick="javascrtpt:window.location.href='query.php'" value="<?=_("返回")?>">
        </div>
        <div class="pagination" id="email-pagination"></div>
    </div>
    <h3><?=_("申领记录")?></h3>

    <?
    $num= get_transhistory($_SESSION['LOGIN_USER_ID']);

    if(!empty($RECORDER_ID))
    {
        $where.=" and borrower='{$RECORDER_ID}' ";
    }
    if($PRO_ID!='-1'&&$PRO_ID!='')
    {
        $where.=" and PRO_ID='{$PRO_ID}'";
    }
    if($_GET['project-id']!='')
    {
        $where.=" and PRO_ID='{$_GET['project-id']}'";
    }
    if(isset($GRANT_STATUS) && $GRANT_STATUS!="")
    {
        $where.=" and grant_status='{$GRANT_STATUS}'";
    }
    if(!empty($FROM_DATE))
    {
        $where .= " and trans_date>='{$FROM_DATE}'";
    }
    if(!empty($TO_DATE))
    {
        $where.=" and trans_date<='{$TO_DATE}'";
    }

    if(empty($num))
    {
        $query = "SELECT * FROM office_transhistory WHERE FIND_IN_SET('{$_SESSION['LOGIN_USER_ID']}',DEPT_MANAGER)";
    }else
    {
        $query = "SELECT * FROM office_transhistory WHERE ((TRANS_FLAG in(1,2) and pro_id in ({$num})) or (FIND_IN_SET('{$_SESSION['LOGIN_USER_ID']}',DEPT_MANAGER))) ";
    }


    $TYPE="RECORDER_ID=$RECORDER_ID&PRO_ID=$PRO_ID&GRANT_STATUS=$GRANT_STATUS&FROM_DATE=$FROM_DATE&TO_DATE=$TO_DATE";

    $query  = $query.$where."order by TRANS_DATE desc";
    $cursor = exequery(TD::conn(),$query);

    $total_nums = mysql_num_rows($cursor);
    $page_total = intval(ceil($total_nums/$page_limit));

    $query1 = $query." limit $start, $page_limit";
    $office_cursor2 = exequery(TD::conn(), $query1);

    if(mysql_num_rows($office_cursor2)==0)
    {
        Message(_('提示'), _('没有符合条件的数据'));
        exit;
    }
    ?>
    <div class="row-fluid">
        <div>
            <table class="table table-bordered table-hover center">
                <thead>
                <tr>
                    <th nowrap><?=_("序号")?></th>
                    <th nowrap><?=_("办公用品名称")?></th>
                    <th nowrap><?=_("登记类型")?></th>
                    <th nowrap><?=_("申请人")?></th>
                    <th nowrap><?=_("数量")?></th>
                    <th nowrap><?=_("申请日期")?></th>
                    <th nowrap><?=_("审批状态")?></th>
                    <th style="width: 100px;"><?=_("备注")?></th>
                    <th nowrap><?=_("状态")?></th>
                    <th nowrap><?=_("操作")?></th>
                </tr>
                </thead>
                <tbody>
                <?
                $i=0;
                while($ROW = mysql_fetch_array($office_cursor2))
                {
                    $i++;
                    $name=get_office_name($ROW['PRO_ID']);
                    if(!empty($ROW['DEPT_MANAGER']))
                    {
                        if($ROW['DEPT_STATUS']==0)
                        {
                            $state=00;
                        }elseif($ROW['DEPT_STATUS']==2)
                        {
                            $state=22;
                        }else
                        {
                            $state='1'.$ROW['TRANS_STATE'];
                        }
                    }else
                    {
                        $state='1'.$ROW['TRANS_STATE'];
                    }
                    ?>
                    <tr>
                        <td nowrap><?=$i?></td>
                        <td nowrap><?=$name?></td>
                        <td nowrap><?=$ROW['TRANS_FLAG']==1?_('领用'):($ROW['TRANS_FLAG']==2?_('借用'):_('归还'))?></td>
                        <td nowrap><?=substr(GetUserNameById($ROW['BORROWER']),0,-1)?></td>
                        <td nowrap><?=abs($ROW['FACT_QTY'])?></td>
                        <td nowrap><?=$ROW['TRANS_DATE']?></td>
                        <td>
                            <?
                            switch($state){
                                case '00':
                                    echo _("等待部门审批人审核");
                                    break;
                                case '11':
                                    echo _("审核通过");
                                    break;
                                case '22':
                                    echo _("部门审批未通过");
                                    break;
                                case '10':
                                    echo _("等待仓库管理员审批");
                                    break;
                                case '12':
                                    echo _("仓库管理员审批未通过");
                                    break;
                            }
                            ?>
                        </td>
                        <td><?=$ROW['REMARK']?></td>
                        <td nowrap><?=$state=='11'?($ROW['RETURN_STATUS']==1?_('已归还'):($ROW['GRANT_STATUS']==1?_('已发放'):_('等待发放'))):_('未通过审核')?></td>
                        <td nowrap>
                            <?
                            if($state=='11'&& $_SESSION['LOGIN_USER_ID']=='admin' && $ROW['GRANT_STATUS']!=1){
                                ?>
                                <a href="apply_info.php?TRANS_ID=<?=$ROW['TRANS_ID']?>&repeat=repeat"><span><?=_("再次审批")?></span></a>
                                <?
                            }
                            ?>
                        </td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
            <?
            if($page_total > 1)
            {
                echo paginator('id', $curpage, $page_total, $page_limit, '?'.$TYPE.'&curpage={curpage}', 'right');
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
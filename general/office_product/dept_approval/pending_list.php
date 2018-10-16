<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("../function_type.php");

$curpage    = ($curpage ? $curpage : 1);
if(!$page_limit)
    $page_limit = get_page_size("OFFICE_PRODECT", 10);
//$page_limit = ($page_limit ? $page_limit : 10);
$start      = ($curpage -1)*$page_limit;
$stype = 0;


//修改事务提醒状态--yc
update_sms_status('43',0);

$HTML_PAGE_TITLE = _("待批申请");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css"	href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js"></script>


<body>
<div class="container-fluid">
    <div style='float:none;'>
        <div class="pagination" id="email-pagination"></div>
    </div>
    <h3><?=_("待批申请")?></h3>
    <div >
        <div class="row-fluid">
            <div>
                <table class="table table-bordered table-hover center">
                    <thead>
                    <?
                    $num= get_transhistory($_SESSION['LOGIN_USER_ID']);
                    if(empty($num))
                    {
                        $query = "SELECT * FROM office_transhistory WHERE dept_status=0 and FIND_IN_SET('{$_SESSION['LOGIN_USER_ID']}',dept_manager) order by TRANS_ID desc";
                    }else
                    {
                        $query = "SELECT * FROM office_transhistory WHERE (TRANS_FLAG in(1,2,3) and pro_id in ({$num}) and trans_state=0 and  dept_status=1) or (dept_status=0 and FIND_IN_SET('{$_SESSION['LOGIN_USER_ID']}',DEPT_MANAGER)) or (trans_state=1 and dept_status=1 and TRANS_FLAG=2 and GRANT_STATUS=1 and RETURN_STATUS=0 and RETURN_DATE!='0000-00-00') order by TRANS_ID desc";
                    }
                    $cursor = exequery(TD::conn(), $query);

                    $total_nums = mysql_num_rows($cursor);
                    $page_total = intval(ceil($total_nums/$page_limit));

                    $query .= " limit $start, $page_limit";
                    $office_cursor2 = exequery(TD::conn(), $query);


                    if(mysql_num_rows($office_cursor2)==0)
                    {
                        Message(_('提示'), _('没有待批申请'));
                        exit;
                    }
                    $i=0;
                    ?>
                    <tr>
                        <th><?=_("序号")?></th>
                        <th><?=_("办公用品库")?></th>
                        <th><?=_("办公用品名称")?></th>
                        <th><?=_("登记类型")?></th>
                        <th><?=_("申请人")?></th>
                        <th><?=_("总数量")?></th>
                        <th><?=_("申请日期")?></th>
                        <th><?=_("操作")?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?
                    $arr_set=array();
                    while($ROW = mysql_fetch_array($office_cursor2))
                    {
                        $FACT_QTY = 0;
                        if($ROW['CYCLE_NO']>0)
                        {
                            if(in_array($ROW['CYCLE_NO'], $arr_set))
                            {
                                continue;
                            }else{
                                array_push($arr_set,$ROW['CYCLE_NO']);
                            }
                            $sql="SELECT sum(b.fact_qty) AS num FROM office_products a LEFT JOIN office_transhistory b ON a.pro_id=b.pro_id where (b.cycle_no='{$ROW['CYCLE_NO']}' and b.trans_state=0 and b.dept_status=1 and b.pro_id in ($num)) or(b.cycle_no='{$ROW['CYCLE_NO']}' and b.dept_status=0 and FIND_IN_SET('{$_SESSION['LOGIN_USER_ID']}',b.dept_manager)) or (b.cycle_no='{$ROW['CYCLE_NO']}' and b.trans_state=1 and b.dept_status=1 and b.pro_id in ($num) and GRANT_STATUS=1 and RETURN_DATE!='0000-00-00' and RETURN_STATUS = 0)";
                            $re=exequery(TD::conn(),$sql);
                            if($row1=mysql_fetch_array($re))
                            {
                                $FACT_QTY=$row1['num'];
                            }
                        }else
                        {
                            $FACT_QTY = $ROW['FACT_QTY'];
                            //获取用品库名称
                            $de_array = get_depository_id($ROW['PRO_ID']);
                            $de_name = $de_array['depository_name'];
                        }
                        $i++;
                        $name=get_office_name($ROW['PRO_ID']);
                        ?>
                        <tr class="suc">
                            <td><?=$i?></td>
                            <td><?=$ROW['CYCLE_NO']>0?"-":"$de_name";?></td>
                            <td><? if($ROW['CYCLE_NO']>0 && $ROW['TRANS_FLAG']!=3 && $ROW['RETURN_DATE']=='0000-00-00')
                                {
                                    echo _("批量申领");
                                }elseif($ROW['CYCLE_NO']>0 && ($ROW['TRANS_FLAG']==2 && $ROW['TRANS_STATE']==1 && $ROW['DEPT_STATUS']==1 && $ROW['GRANT_STATUS']==1 && $ROW['RETURN_STATUS']==0 && $ROW['RETURN_DATE']!='0000-00-00'))
                                {
                                    echo _("批量归还");
                                    $stype = 1;
                                }
                                else
                                {
                                    echo $name;
                                }
                                ?></td>
                            <td><?=$ROW['TRANS_FLAG']==1?_('领用'):($ROW['TRANS_FLAG']==2 && $ROW['RETURN_DATE']=='0000-00-00'?_('借用'):_('归还'))?></td>
                            <td><?=substr(GetUserNameById($ROW['BORROWER']),0,-1)?></td>
                            <td><?=abs($FACT_QTY)?></td>
                            <td><?=$ROW['RETURN_DATE']!='0000-00-00'?$ROW['RETURN_DATE']:$ROW['TRANS_DATE'];?></td>
                            <td>
                                <?
                                if($ROW['CYCLE_NO']){
                                    ?>
                                    <a href="apply_info.php?CYCLE_NO=<?=$ROW['CYCLE_NO']?>&stype=<?=$stype?>"><span class="status1" id="y_<?=$ROW['TRANS_ID']?>_DEPT"><?=_("批量处理")?></span></a>&nbsp
                                <? }else{ ?>
                                    <a href="apply_info.php?TRANS_ID=<?=$ROW['TRANS_ID']?>"><span class="status1" id="y_<?=$ROW['TRANS_ID']?>_<?=$ROW['TRANS_FLAG']?>_<?=$ROW['FACT_QTY']?>"><?=_("处理")?></span></a>&nbsp
                                <? } ?>
                            </td>
                        </tr>
                    <? } ?>
                    </tbody>
                </table>
                <?
                if($page_total > 1)
                {
                    echo paginator('id', $curpage, $page_total, $page_limit, '?TYPE='.$TYPE.'&curpage={curpage}', 'right');
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
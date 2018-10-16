<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("../function_type.php");

$curpage    = ($curpage ? $curpage : 1);
if(!$page_limit)
    $page_limit = get_page_size("OFFICE_PRODECT", 5);
//$page_limit = ($page_limit ? $page_limit : 5);
$start      = ($curpage -1)*$page_limit;

//修改事务提醒状态--yc
update_sms_status('75',0);

$HTML_PAGE_TITLE = _("办公用品发放管理");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css"	href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/style.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js"></script>

<style>
    #id.pagination ul{
        box-shadow: none;
    }
</style>
<body>
<h4><?=_("发放列表")?></h4>
<div >
    <?

    $sql="SELECT id from office_type where TYPE_DEPOSITORY in(SELECT id from office_depository where FIND_IN_SET('{$_SESSION['LOGIN_USER_ID']}',PRO_KEEPER))";
    $cursor = exequery(TD::conn(),$sql);
    while($ROW = mysql_fetch_array($cursor))
    {
        $str.=$ROW['id'].',';
    }
    $num = substr($str,0,-1);
    if(empty($num))
    {
        Message(_('提示'), _('暂无数据'));
        exit;
    }
    $sql = "select pro_id from office_products where office_protype in ({$num})";

    $cursor = exequery(TD::conn(),$sql);
    $str='';
    while($ROW = mysql_fetch_array($cursor))
    {
        $str.=$ROW['pro_id'].',';
    }

    $str = substr($str,0,-1);

    if(empty($str))
    {
        Message(_('提示'), _('暂无数据'));
        exit;
    }

    //默认显示 审核通过 登录账户为库调度员
    $where="where TRANS_STATE=1 and trans_flag in(1,2) and pro_id in ({$str})";
    if($RECORDER_ID!=''){
        $where.=" and BORROWER like '%{$RECORDER_ID}%' ";
    }
    if($GRANT_STATUS!=''){
        $where.=" and GRANT_STATUS=$GRANT_STATUS ";
    }
    if($FROM_DATE!=''){
        $where.=" and TRANS_DATE >='{$FROM_DATE}' ";
    }
    if($TO_DATE!=''){
        $where.=" and TRANS_DATE <='{$TO_DATE}' ";
    }
    $query  = "SELECT * FROM office_transhistory ".$where."order by TRANS_ID desc";
    $cursor = exequery(TD::conn(),$query);

    $total_nums = mysql_num_rows($cursor);
    $page_total = intval(ceil($total_nums/$page_limit));

    $TYPE="RECORDER_ID=$RECORDER_ID&GRANT_STATUS=$GRANT_STATUS&FROM_DATE=$FROM_DATE&TO_DATE=$TO_DATE";

    $query .= " limit $start, $page_limit";
    $office_cursor2 = exequery(TD::conn(), $query);

    if(mysql_num_rows($office_cursor2)==0)
    {
        Message(_('提示'), _('暂无数据'));
        exit;
    }
    ?>
<script>
    function check_one(el)
    {
        if(!el.checked)
        {
            document.getElementsByName("allbox")[0].checked=false;
        }
    }
    function get_checked()
    {
        checked_str="";
        for(i=0;i<document.getElementsByName('search_select').length;i++)
        {
            el=document.getElementsByName('search_select').item(i);
            if(el.checked)
            {
                val=el.value;
                checked_str+=val + ",";
            }
        }

        if(i==0)
        {
            el=document.getElementsByName('search_select');
            if(el.checked)
            {
                val=el.value;
                checked_str+=val + ",";
            }
        }
        return checked_str;
    }
    function group_send()
    {
        send_str=get_checked();
        if(send_str=="")
        {
            alert("<?=_("请至少选择一条记录")?>");
            return false;
        }
        msg='<?=_("确认要发放所选的办公用品？")?>';
        if(window.confirm(msg))
        {
            document.getElementById("TRANS_ID").value=send_str;
            document.form2.submit();
        }
    }
</script>
    <div class="container-fluid">
        <div class="row-fluid">
            <div>
                <table class="table table-bordered center">
                    <thead>
                    <tr>
                        <th><?=_("选择")?></th>
                        <th nowrap><?=_("序号")?></th>
                        <th nowrap><?=_("办公用品名称")?></th>
                        <th nowrap><?=_("登记类型")?></th>
                        <th nowrap><?=_("申请人")?></th>
                        <th nowrap><?=_("数量")?></th>
                        <th nowrap><?=_("申请日期")?></th>
                        <th nowrap><?=_("审批状态")?></th>
                        <th nowrap><?=_("状态")?></th>
                        <th nowrap><?=_("操作人")?></th>
                        <th nowrap><?=_("备注")?></th>
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
                        ?>
                        <tr id="transhistory_<?=$ROW['TRANS_ID'].'_'.abs($ROW['FACT_QTY']).'_'.$ROW['PRO_ID']?> ">
                            
                            <td>
                            <?if($ROW['GRANT_STATUS']!=1){
                                echo '<input type="checkbox" name="search_select" id="search_select" value="'.$ROW['TRANS_ID'].'" onClick="check_one(self);">';
                                }else{
                                 echo "--";
                                }?>
                            </td>
                            <td><?=$i?></td>
                            <td><?=$name ?></td>
                            <td><?=$ROW['TRANS_FLAG']==1?_('领用'):($ROW['TRANS_FLAG']==2?_('借用'):'')?></td>
                            <td><?=substr(GetUserNameById($ROW['BORROWER']),0,-1)?></td>
                            <td><?=abs($ROW['FACT_QTY'])?></td>
                            <td nowrap><?=$ROW['TRANS_DATE']?></td>
                            <td><?=_("已批准")?></td>
                            <td class="GRANT_STATUS" nowrap>
                                <?
                                if(empty($ROW['DEPT_MANAGER'])){
                                    echo $ROW['GRANT_STATUS']==1?_('已发放'):_('等待发放');
                                }else{
                                    if($ROW['DEPT_STATUS']==1){
                                        echo $ROW['GRANT_STATUS']==1?_('已发放'):_('等待发放');
                                    }else{
                                        echo _('等待部门审批人审批');
                                    }
                                }
                                ?>
                            </td>
                            <td class="OPERATER" nowrap>
                                <? if($ROW['GRANT_STATUS']==1){echo substr(GetUserNameById($ROW['GRANTOR']),0,-1);}else{echo '--';}?></td>
                            <td><?=$ROW['REMARK']?></td>
                            <td nowrap>
                                <? if($ROW['GRANT_STATUS']!=1){?>
                                    <a href="javascript::" ><span class="update_grant" id="<?=$ROW['TRANS_ID'].'_'.abs($ROW['FACT_QTY']).'_'.$ROW['PRO_ID']?>"><?=_("发放")?></span></a>
                                <? }else{?>
                                    <?=_("已发放")?>
                                <? }?>
                            </td>
                        </tr>
                    <? } ?>
                    <tr class="TableControl" style="background:#fff">
                    <td colspan="12" class="form-inline" style="text-align:left;">
                        &nbsp;<label class="checkbox" for="allbox_for"><input type="checkbox" name="allbox" id="allbox_for"><?=_("全选")?></label>&nbsp;
                        &nbsp;<button type="button" class="btn" onClick="group_send();" title="<?=_("归还所选办公用品")?>"><?=_("批量发放")?></button>
                    </td>
                </tr>
                    </tbody>
                </table>
                <?
                if($page_total > 1)
                {
                    echo paginator('id', $curpage, $page_total, $page_limit, '?TYPE='.$TYPE.'&curpage={curpage}', 'right');
                }
                ?>
                <form name="form2" method="post" action="set_search.php">
                    <input type="hidden" name="TRANS_STR_ID" id="TRANS_ID" value="">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
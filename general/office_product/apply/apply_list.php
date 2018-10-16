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

$HTML_PAGE_TITLE = _("办公用品申领");
include_once("inc/header.inc.php");

//判断借用商品归还状态
function get_return($TRANS_ID)
{
    $sql="SELECT RETURN_STATUS FROM office_transhistory WHERE TRANS_ID = '$TRANS_ID' and  TRANS_STATE=1 and DEPT_STATUS=1 and GRANT_STATUS=1";
    $cursor= exequery(TD::conn(),$sql);
    if(mysql_affected_rows()>0)
    {
        $row=mysql_fetch_array($cursor);
        if($row['RETURN_STATUS']==0 || $row['RETURN_STATUS']==2)
        {
            return true;
        }else
        {
            return false;
        }
    }
}

//修改事务提醒状态--yc
update_sms_status('75,43',0);

?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/style.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js"></script>
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
        for(i=0;i<document.getElementsByName('add_select').length;i++)
        {
            el=document.getElementsByName('add_select').item(i);
            if(el.checked)
            {
                val=el.value;
                checked_str+=val + ",";
            }
        }

        if(i==0)
        {
            el=document.getElementsByName('add_select');
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
        msg='<?=_("确认要登记归还所选的办公用品？")?>';
        if(window.confirm(msg))
        {
            document.getElementById("TRANS_ID").value=send_str;
            document.form2.submit();
        }
    }
    function get_return(id)
    {
        msg='<?=_("确认要登记归还此办公用品？")?>';
        if(window.confirm(msg))
        {
            URL="set_return.php?TRANS_ID="+id;
            window.location=URL;
        }
    }
</script>

<body>
<div class="container-fluid">
    <h3><?=_("我的申领记录")?></h3>
    <div style='float:none;'>
        <div class="pagination" id="email-pagination"></div>
    </div>
    <div class="row-fluid">
        <div>
            <?
            $query  = "SELECT * FROM office_transhistory WHERE borrower='{$_SESSION['LOGIN_USER_ID']}' ORDER BY TRANS_ID desc";
            $cursor = exequery (TD::conn(), $query);

            $total_nums = mysql_num_rows($cursor);
            $page_total = intval(ceil($total_nums/$page_limit));

            $query .= " limit $start, $page_limit";
            $office_cursor2 = exequery(TD::conn(), $query);

            if(mysql_num_rows($office_cursor2)==0)
            {
                Message(_('提示'),_('暂无申领记录'));
                exit;
            }
            ?>
            <table class="table table-bordered center table-hover">
                <thead>
                <tr>
                    <th><?=_("选择")?></th>
                    <th><?=_("办公用品名称")?></th>
                    <th><?=_("登记类型")?></th>
                    <th><?=_("申请数量")?></th>
                    <th><?=_("操作日期")?></th>
                    <th><?=_("部门审批人")?></th>
                    <th><?=_("备注")?></th>
                    <th><?=_("状态")?></th>
                    <th><?=_("操作")?></th>
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
                    <tr id="tr_<?=$ROW['TRANS_ID']?>">
                        <td><? if($ROW['TRANS_FLAG']==2 && $ROW['TRANS_STATE']==1 && get_return($ROW['TRANS_ID']) && ($ROW['RETURN_DATE']=='0000-00-00' && $ROW['RETURN_STATUS']==0) || ($ROW['RETURN_DATE']!='0000-00-00' && $ROW['RETURN_STATUS']==2)){ echo '<input type="checkbox" name="add_select" value="'.$ROW['TRANS_ID'].'" onClick="check_one(self);">';}else{ echo "-";}?></td>
                        <td><?=$name ?></td>
                        <td><?=$ROW['TRANS_FLAG']==1?_('领用'):($ROW['TRANS_FLAG']==2?_('借用'):_('归还'))?></td>
                        <td><?=abs($ROW['FACT_QTY'])?></td>
                        <td><?=$ROW['TRANS_DATE']?></td>
                        <td><?=substr(GetUserNameById($ROW['DEPT_MANAGER']),0,-1)?></td>
                        <td><?=$ROW['REMARK']?></td>
                        <td>
                            <?
                            if($ROW['TRANS_STATE']==0 && $ROW['DEPT_STATUS']==0)
                            {
                                echo _('待部门审批人审批');
                            }elseif($ROW['TRANS_STATE']==0 && $ROW['DEPT_STATUS']==1)
                            {
                                echo _('待库管员审批');
                            }elseif($ROW['TRANS_STATE']==2)
                            {
                                echo _('库管员驳回');
                            }elseif($ROW['DEPT_STATUS']==2)
                            {
                                echo _('部门审批驳回');
                            }elseif($ROW['TRANS_STATE']==1 && $ROW['DEPT_STATUS']==1)
                            {
                                echo _('审批通过');
                            }
                            ?>
                        </td>
                        <td>
                            <?
                            if($ROW['TRANS_STATE']==0 && $ROW['DEPT_STATUS']!=2){
                                ?>
                                <a href="javascript::"><span class="delete" name="OFFICE_TRANSHISTORY" id="<?=$ROW['TRANS_ID']?>" action="TRANSHISTORY_DEL"><?=_("删除")?></span></a>&nbsp;
                                <a href="../apply/apply_one.php?id=<?=$ROW['TRANS_ID']?>&type=1&curpage=<?=$curpage?>"><span><?=_("修改")?></span></a>
                            <? }elseif($ROW['TRANS_STATE']==2 || $ROW['DEPT_STATUS']==2){ ?>
                                <a href="javascript::"><span class="delete" name="OFFICE_TRANSHISTORY" id="<?=$ROW['TRANS_ID']?>" action="TRANSHISTORY_DEL"><?=_("删除")?></span></a>
                                &nbsp;<a href="apply_detail.php?id=<?=$ROW['TRANS_ID']?>"><span><?=_("详情")?></span></a>
                            <? }else{ ?>
                                <a href="apply_detail.php?id=<?=$ROW['TRANS_ID']?>"><span><?=_("详情")?></span></a>&nbsp;
                                <?
                                if($ROW['TRANS_FLAG']==2 && get_return($ROW['TRANS_ID']) && $ROW['GRANT_STATUS']==1 && $ROW['RETURN_STATUS']==0){
                                    if($ROW['RETURN_DATE']=='0000-00-00')
                                    {?>
                                        <a href="javascript:;" onClick="get_return(<?=$ROW['TRANS_ID']?>)"><span>归还</span></a>
                                        <?
                                    }else
                                    {
                                        ?>
                                        <span><?=_("归还待审批")?></span>
                                        <?
                                    }
                                    ?>

                                <? }elseif($ROW['TRANS_FLAG']==2 && $ROW['GRANT_STATUS']==1 && $ROW['RETURN_STATUS']==1)
                                {
                                    ?>
                                    <span><?=_("已归还")?></span>
                                <? }elseif($ROW['TRANS_FLAG']==2 && $ROW['GRANT_STATUS']==1 && $ROW['RETURN_STATUS']==2){
                                    ?>
                                    <span title="<?=$ROW['RETURN_REASON']?>"><?=_("未通过归还审核")?></span>&nbsp;<a href="javascript:;" onClick="get_return(<?=$ROW['TRANS_ID']?>)"><span><?=_("归还")?></span></a>
                                    <?
                                }elseif(($ROW['TRANS_FLAG']==2 || $ROW['TRANS_FLAG']==1)&& $ROW['GRANT_STATUS']==0 && $ROW['TRANS_STATE']==1){?>
                                    <span><?=_("未发放")?></span>
                                    <?
                                }}?>
                        </td>
                    </tr>
                <? } ?>
                <tr class="TableControl" style="background:#fff">
                    <td colspan="9" class="form-inline" style="text-align:left;">
                        &nbsp;<label class="checkbox" for="allbox_for"><input type="checkbox" name="allbox" id="allbox_for"><?=_("全选")?></label>&nbsp;
                        &nbsp;<button type="button" class="btn" onClick="group_send();" title="<?=_("归还所选办公用品")?>"><?=_("批量归还")?></button>
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
        </div>
    </div>
</div>
<form name="form2" method="post" action="set_return.php">
    <input type="hidden" name="TRANS_STR_ID" id="TRANS_ID" value="">
</form>
</body>
</html>
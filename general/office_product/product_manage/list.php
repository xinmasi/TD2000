<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("../function_type.php");

$curpage = ($curpage ? $curpage : 1);
if(!$page_limit)
    $page_limit = get_page_size("OFFICE_PRODECT", 10);
//$page_limit = ($page_limit ? $page_limit : 10);
$start = ($curpage -1)*$page_limit;

$HTML_PAGE_TITLE = _("办公用品列表");
include_once("inc/header.inc.php");

$where ="";
if($_SESSION["LOGIN_USER_PRIV"]!=1)
{
    $where = " AND ((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',PRO_DEPT)) or (PRO_MANAGER='' and PRO_DEPT='') or PRO_DEPT='ALL_DEPT' or PRO_CREATOR='".$_SESSION["LOGIN_USER_ID"]."')";
}

$OFFICE_TYPE_ID = "";
//获取所能查询到的物品类表ID
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
    $query = "select OFFICE_TYPE_ID from OFFICE_DEPOSITORY ";
}else
{
    $query = "select OFFICE_TYPE_ID from OFFICE_DEPOSITORY where (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) or DEPT_ID = '' or  DEPT_ID = 'ALL_DEPT') and find_in_set('{$_SESSION["LOGIN_USER_ID"]}',MANAGER)";
}
$cursor = exequery(TD::conn(),$query);
while($ROW = mysql_fetch_array($cursor))
{
    $OFFICE_TYPE_ID .= $ROW["OFFICE_TYPE_ID"].",";
}
$OFFICE_TYPE_ID = td_trim($OFFICE_TYPE_ID);

$where .= " AND find_in_set(OFFICE_PROTYPE,'".$OFFICE_TYPE_ID."')";

$office_query = "select * from office_products where OFFICE_PROTYPE = '$_GET[TYPE]'".$where;

// 查询条件搜索出办公用品列表
if($action=='query')
{
    if($_POST['project-id']=='')
    {
        if($mytag=='0' || $PRO_NAME != "")
        {
            $office_query = "select * from office_products where PRO_NAME LIKE '%$PRO_NAME%'".$where;
        }
        else
        {
            if($OFFICE_DEPOSITORY==-1)
            {
                $office_query = "select * from office_products WHERE 1=1".$where;
            }elseif($OFFICE_PROTYPE==-1)
            {
                $OFFICE_DEPOSITORY=$OFFICE_DEPOSITORY==''?'0':$OFFICE_DEPOSITORY;
                $office_query="select * from office_products where OFFICE_PROTYPE in ($OFFICE_DEPOSITORY)".$where;
            }elseif($PRO_ID==-1)
            {
                $office_query = "select * from office_products where OFFICE_PROTYPE = '".$OFFICE_PROTYPE."'".$where;
            }else
            {
                $office_query = "select * from office_products where PRO_ID = '".$PRO_ID."'".$where;
            }
        }
    }else
    {
        $office_query = "select * from office_products where PRO_ID = '".$_POST['project-id']."'".$where;
    }
    $TYPE = "PRO_NAME=$PRO_NAME&OFFICE_PROTYPE=$OFFICE_PROTYPE&PRO_ID=$PRO_ID&OFFICE_DEPOSITORY=$OFFICE_DEPOSITORY&action=query";
}else
{
    $TYPE ="TYPE=$_GET[TYPE]";
}
$office_cursor = exequery(TD::conn(), $office_query);
$total_nums = mysql_num_rows($office_cursor);

$page_total = intval(ceil($total_nums/$page_limit));

?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/style.css" />
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js"></script>

<div class="row-fluid" align="center" >
    <div class="span11" style='float:none;'>
        <div class='top_right'>
            <input type="button" class="btn btn-small btn-primary" onclick="javascrtpt:window.location.href='query.php'" value="<?=_("返回")?>">
            <input type="button" class="btn btn-small btn-primary" style="margin-left:10px;" onClick="javascrtpt:window.location.href='new.php'" value="<?=_("新建办公用品")?>">
        </div>
        <h3 style="text-align:left;"><?=_("办公用品列表")?></h3>
        <?
        $office_query .= " limit $start, $page_limit";
        $office_cursor2 = exequery(TD::conn(), $office_query);
        $total_nums = mysql_num_rows($office_cursor);
        while($office_row1 = @mysql_fetch_array($office_cursor2))
        {
            $office_row_arr[] = $office_row1;
        }
        if($total_nums == 0)
        {
            Message(_("提示"),_("没有符合条件的办公用品"));
        }
        else
        {
            ?>
            <table  class="table table-bordered center table-hover">
                <thead>
                <tr>
                    <th nowrap><?=_("选择")?></th>
                    <th nowrap><?=_("办公用品名称")?></th>
                    <th nowrap><?=_("办公用品类别")?></th>
                    <th nowrap><?=_("计量单位")?></th>
                    <th nowrap><?=_("供应商")?></th>
                    <th nowrap><?=_("警戒库存")?></th>
                    <th nowrap><?=_("当前库存")?></th>
                    <th nowrap><?=_("创建人")?></th>
                    <th nowrap><?=_("审批人")?></th>
                    <th nowrap><?=_("操作")?></th>
                </tr>
                </thead>
                <?
                foreach((array)$office_row_arr as $key=>$office_row)
                {
                    $arr=array();
                    $sql="select a.TYPE_DEPOSITORY,b.OFFICE_TYPE_ID from OFFICE_TYPE as a,office_depository as b where a.id='{$office_row['OFFICE_PROTYPE']}' and a.TYPE_DEPOSITORY = b.ID";
                    $res = exequery(TD::conn(), $sql);
                    $arr = @mysql_fetch_array($res);
                    ?>
                    <tr id='tr_<?=$office_row['PRO_ID']?>'>
                        <td nowrap><input type="checkbox" name="office_select" value="<?=$office_row['PRO_ID']?>" onClick="check_one(self);" />  </td>
                        <td nowrap><? echo $office_row['PRO_NAME']?></td>
                        <td nowrap><? echo get_type_name($office_row['OFFICE_PROTYPE'])?></td>
                        <td><? echo $office_row['PRO_UNIT']?></td>
                        <td><? echo $office_row['PRO_SUPPLIER']?></td>
                        <td><? echo $office_row['PRO_LOWSTOCK']?></td>
                        <td><? echo $office_row['PRO_STOCK']?></td>
                        <td nowrap><? echo substr(GetUserNameById($office_row['PRO_CREATOR']),0,-1)?></td>
                        <td nowrap><? echo td_trim(GetUserNameById($office_row['PRO_AUDITER']))?></td>
                        <td width="80">
                            <a href="edit.php?iExpand=<?=$iExpand?>&PRO_ID=<?=$office_row['PRO_ID']?>&DEPOSITORY_ID=<?=$arr['TYPE_DEPOSITORY']?>&DEPOSITORY=<?=$arr['OFFICE_TYPE_ID']?>"><?=_("编辑")?></a>&nbsp;&nbsp;
                        </td>
                    </tr>
                <? }?>
                <tr>
                    <td colspan="19" style='text-align: left;padding-left:12px;'>
                        <input type="checkbox" name="allbox" id="allbox_for" style="margin-top:-3px;">
                        <span for="allbox_for"><?=_("全选")?></span> &nbsp;&nbsp;
                        <a href="javascript:delete_office();" title="<?=_("删除所选办公用品")?>">
                            <img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle">
                            <span><?=_("删除所选办公用品")?></span>
                        </a>
                    </td>
                </tr>
            </table>
            <?
        }

        if($page_total > 1)
        {
            echo paginator('id', $curpage, $page_total, $page_limit, '?'.$TYPE.'&curpage={curpage}', 'right');
        }
        ?>
    </div>
</div>
</body>
</html>
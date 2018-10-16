<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("办公用品物品分类设置");
include_once("inc/header.inc.php");


?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/office_type.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>

<body>
<!-- 新建和编辑模态窗口设置的开始 -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 id="do_type"></h4>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <button class="btn btn-primary" onClick="check_type_form()">保存</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>
<!--  新建和编辑模态窗口设置结束-->
<div class="row-fluid" align="center">
    <div class="span9" style='float:none;'>
        <div class='top_right'>
            <input type="button" onClick="location.href='index.php'" class="btn btn-small" style="margin-top:5px;" value="返回">
            <input type="button" onClick="edit_office_type2('','<?=$_GET['id']?>');" class="btn btn-small btn-primary" style="margin-top:5px;" value="<?=_("新建办公用品库分类")?>">
        </div>
        <div style="text-align:left;">
            <h3><?=_("办公用品物品分类设置")?></h3>
        </div>
        <?
        $query = "select a.ID,a.TYPE_NAME,a.TYPE_ORDER,a.TYPE_DEPOSITORY,b.DEPOSITORY_NAME,b.DEPT_ID from OFFICE_TYPE a left outer join OFFICE_DEPOSITORY b on a.TYPE_DEPOSITORY=b.ID where a.TYPE_DEPOSITORY='{$_GET['id']}'  order by a.TYPE_ORDER";
        $cursor = exequery ( TD::conn (), $query );
        if(mysql_num_rows ( $cursor )==0)
        {
            Message(_('提示'), _('办公用品物品分类为空'));
            exit;
        }
        ?>
        <table class="table table-bordered table-hover center">
            <thead>
            <tr>
                <th class='th_1'><?=_("序号")?></th>
                <th class='th_2'><?=_("办公用品类别")?></th>
                <th class='th_3'><?=_("所属库")?></th>
                <th class='th_4'><?=_("排序号")?></th>
                <th class='th_5'><?=_("操作")?></th>
            </tr>
            </thead>
            <?
            $i=0;
            while ( $ROW = mysql_fetch_array ( $cursor ) ) {
                $i++;
                ?>
                <tr id='tr_<?=$ROW['ID']?>'>
                    <td><?=$i?></td>
                    <td><?=$ROW['TYPE_NAME']?></td>
                    <td><?=$ROW['DEPOSITORY_NAME']?></td>
                    <td><?=$ROW['TYPE_ORDER']?></td>
                    <td style="text-align: center;">
                        <a href="#" onClick="edit_office_type2('<?=$ROW['ID']?>','<?=$_GET['id']?>>')"><?=_("编辑")?></a>
                        <a href="#"><span name="OFFICE_TYPE" id="<?=$ROW['ID']?>"  action="depository_del" class='delete'><?=_("删除")?></span></a>
                    </td>
                </tr>
            <? } ?>
        </table>
    </div>
</div>

</body>
</html>
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("�칫��Ʒ��Ʒ��������");
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
<!-- �½��ͱ༭ģ̬�������õĿ�ʼ -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
        <h4 id="do_type"></h4>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <button class="btn btn-primary" onClick="check_type_form()">����</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">�ر�</button>
    </div>
</div>
<!--  �½��ͱ༭ģ̬�������ý���-->
<div class="row-fluid" align="center">
    <div class="span9" style='float:none;'>
        <div class='top_right'>
            <input type="button" onClick="location.href='index.php'" class="btn btn-small" style="margin-top:5px;" value="����">
            <input type="button" onClick="edit_office_type2('','<?=$_GET['id']?>');" class="btn btn-small btn-primary" style="margin-top:5px;" value="<?=_("�½��칫��Ʒ�����")?>">
        </div>
        <div style="text-align:left;">
            <h3><?=_("�칫��Ʒ��Ʒ��������")?></h3>
        </div>
        <?
        $query = "select a.ID,a.TYPE_NAME,a.TYPE_ORDER,a.TYPE_DEPOSITORY,b.DEPOSITORY_NAME,b.DEPT_ID from OFFICE_TYPE a left outer join OFFICE_DEPOSITORY b on a.TYPE_DEPOSITORY=b.ID where a.TYPE_DEPOSITORY='{$_GET['id']}'  order by a.TYPE_ORDER";
        $cursor = exequery ( TD::conn (), $query );
        if(mysql_num_rows ( $cursor )==0)
        {
            Message(_('��ʾ'), _('�칫��Ʒ��Ʒ����Ϊ��'));
            exit;
        }
        ?>
        <table class="table table-bordered table-hover center">
            <thead>
            <tr>
                <th class='th_1'><?=_("���")?></th>
                <th class='th_2'><?=_("�칫��Ʒ���")?></th>
                <th class='th_3'><?=_("������")?></th>
                <th class='th_4'><?=_("�����")?></th>
                <th class='th_5'><?=_("����")?></th>
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
                        <a href="#" onClick="edit_office_type2('<?=$ROW['ID']?>','<?=$_GET['id']?>>')"><?=_("�༭")?></a>
                        <a href="#"><span name="OFFICE_TYPE" id="<?=$ROW['ID']?>"  action="depository_del" class='delete'><?=_("ɾ��")?></span></a>
                    </td>
                </tr>
            <? } ?>
        </table>
    </div>
</div>

</body>
</html>
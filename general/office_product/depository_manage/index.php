<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("�칫��Ʒ������");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/office_type.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<body>
<div class="row-fluid" align="center" >
    <div class="span10" style='float:none;'>

        <div class='top_right'>
            <input type="button" onClick="edit_office_type('');" class="btn btn-small btn-primary" value="�½��칫��Ʒ��">
        </div>
        <div style="text-align:left;">
            <h3><?=_("�칫��Ʒ������")?></h3>
        </div>
        <table align="center" class="table table-bordered table-hover center" id="office_type_list" style="table-layout: fixed;">
            <col width="10" />
            <col width="10" />
            <col width="20" />
            <col width="10" />
            <col width="10" />
            <col width="10" />
            <col width="20" />
            <thead>
            <tr>
                <th><?=_("���")?></th>
                <th><?=_("�칫��Ʒ��")?></th>
                <th><?=_("�칫��Ʒ���")?></th>
                <th><?=_("��������")?></th>
                <th><?=_("�ֿ����Ա")?></th>
                <th><?=_("��Ʒ����Ա")?></th>
                <th><?=_("����")?></th>
            </tr>
            </thead>
            <?
            $query = "SELECT * FROM OFFICE_DEPOSITORY";
            $cursor = exequery(TD::conn (),$query);
            if(mysql_num_rows ( $cursor )==0)
            {
                Message(_('��ʾ'), _('�칫��Ʒ��Ʒ��Ϊ��'));
            }
            $i=0;
            while($ROW = mysql_fetch_array($cursor))
            {
                $i++;
                $TYPE_NAME_STR = "";
                $ID = $ROW['ID'];
                $DEPOSITORY_NAME = $ROW['DEPOSITORY_NAME'];
                $DEPT_ID = $ROW['DEPT_ID'];
                $MANAGER = $ROW['MANAGER'];
                $MANAGER_NAME = "";
                if($MANAGER != "")
                {
                    $MANAGER_NAME = td_trim(GetUserNameById($MANAGER));
                }
                $PRO_KEEPER = $ROW ['PRO_KEEPER'];
                $PRO_KEEPER_NAME = "";
                if($PRO_KEEPER != "")
                {
                    $PRO_KEEPER_NAME = td_trim(GetUserNameById($PRO_KEEPER));
                }
                if($DEPT_ID == "ALL_DEPT")
                {
                    $DEPT_ID = _("ȫ�岿��");
                }else
                {
                    $DEPT_ID = GetDeptNameById($DEPT_ID);
                }
                $DEPT_NAME = $DEPT_ID;
                if(strlen($DEPT_ID) > 64)
                {
                    $DEPT_ID = mb_substr($DEPT_ID,0,64,"GB2312");
                    $DEPT_ID = $DEPT_ID . "....";
                }
                $query = "SELECT type_name as name FROM office_type WHERE type_depository=$ID";
                $RES   = exequery(TD::conn(),$query);
                while($U = mysql_fetch_array($RES))
                {
                    $TYPE_NAME_STR.=$U['name'].',';
                }
                $TYPE_NAME_STR = substr($TYPE_NAME_STR,0,-1);
                ?>
                <tr id='tr_<?=$ID?>'>
                    <td><?=$i?></td>
                    <td><?=$DEPOSITORY_NAME?></td>
                    <td style="white-space:nowrap;text-overflow:ellipsis;overflow:hidden;"><?=$TYPE_NAME_STR?></td>
                    <td><?=$DEPT_ID?></td>
                    <td><?=$MANAGER_NAME?></td>
                    <td><?=$PRO_KEEPER_NAME?></td>
                    <td style="text-align:center;">
                        <?
                        if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($MANAGER, $_SESSION["LOGIN_USER_ID"]))
                        {
                            ?>
                            <a href="javascript:;" onClick="edit_office_type('<?=$ID?>');" ><?=_("�༭")?></a>|
                            <a href="javascript:;"><span class="delete" name="OFFICE_DEPOSITORY" action="depository_del" id="<?=$ID?>"><?=_("ɾ��")?></span></a>|
                            <a href="type_manage.php?id=<?=$ID?>"><?=_("�������")?></a>
                            <?
                        }
                        ?>
                    </td>
                </tr>
            <? } ?>
        </table>
    </div>
</div>
<!-- �½��ͱ༭ģ̬�������õĿ�ʼ -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
        <h4 id="do_type"></h4>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" onClick="check_office_type()"><?=_("����")?></button>
        <button class="btn" data-dismiss="modal" aria-hidden="true" onClick="userSaveAndBind['PRO_KEEPER']=[];userSaveAndBind['PRO_KEEPER_NAME']=[];userSaveAndBind['MANAGER']=[];userSaveAndBind['MANAGER_NAME']=[];userSaveAndBind['PRIV_ID']=[];userSaveAndBind['PRIV_NAME']=[]"><?=_("�ر�")?></button>
    </div>
</div>
<!--  �½��ͱ༭ģ̬�������ý���-->
</body>
</html>
<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$HTML_PAGE_TITLE = _("�칫��Ʒ�б�");
//# ����Ŀ¼��
if(!isset($xtree_id) || $xtree_id == "")
    $xtree_id = "product_manage_tree";

if(!isset($xtree) || $xtree == "")
    $xtree = "tree.php";
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/tree_style.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/images/org/ui.dynatree.css<?=$GZIP_POSTFIX?>" />

<style type="text/css">
ul{
    list-style: none;
    margin: 0px;
    padding: 0px;
    width: 99%;
    line-height: 20px;
}
.set_border{
    background: #9ac5e8;
    border:1px solid #5b99ca;
    line-height: 32px;
    text-align:center;
    padding-left:10px;
    color: #000000;
    font-weight: bold;
    border-collapse:collapse;
}

li a:link span, li a:visited span{
    /*background: url("../img/ar.png") no-repeat center left;*/
    color: #445599;
    font-size: 9pt;
    display: block;
    font-weight: bold;
    height: 32px;
    font-weight: bold;
    text-align:left;
    padding-left:20px;
}
li a:hover span{
    /*background: url("../img/ar.png") no-repeat center left;*/
    color: #000000;
    font-weight: bold;
    text-decoration: none;
    display: block;
    height: 32px;
    text-align:left;
    padding-left:20px;
}
</style>
<body>
<div>
    <ul>
        <li class='set_border'><?=_("�칫��Ʒ�б�")?></li>
        <div>
            <img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif"
                 WIDTH="22" HEIGHT="20" align="absmiddle" style="margin-left: 15px;">
            <b><?=_("�칫��Ʒ�б�")?></b>
            <div id="<?=$xtree_id?>"></div>
        </div>
        <li class='set_border'>
            <a href="new.php" title="<?=_("����µİ칫��Ʒ")?>" target="listall"><span><?=_("�½��칫��Ʒ")?></span></a>
        </li>
        <li class='set_border'>
            <a href='query.php' title="<?=_("��ѯ�칫��Ʒ��Ϣ")?>" target="listall"><span><?=_("�칫��Ʒ��ѯ")?></span></a>
        </li>
        <li class='set_border'>
            <a href='pro_import.php' title="<?=_("����칫��Ʒ��Ϣ")?>" target="listall"><span><?=_("�칫��Ʒ����")?></span></a>
        </li>
    </ul>
</div>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/tree.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript">
    var tree = new Tree("<?=$xtree_id?>", "<?=$xtree?>", '<?=MYOA_STATIC_SERVER?>/static/images/',false, 3);
    tree.BuildTree();
</script>
</body>
</html>
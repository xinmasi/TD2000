<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("Í¼Æ¬ä¯ÀÀ");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<style type="text/css">
.PageHeader{padding: 0 5px 5px 5px;height: 60px !important;}
.PageHeader img.module_icon{float: left;}
.PageHeader h2.PageTitle{font-size: 16px;font-weight: bold;float: left;height: 48px;line-height: 48px;margin-left: 10px;}
.module_icon{height: 48px;width: 48px;}
</style>
<div class="PageHeader">
    <img src="<?=MYOA_STATIC_SERVER?>/static/theme/13/images/app_icons/picture.png" class="module_icon"/>
    <h2 class="PageTitle"><?=_("Í¼Æ¬ä¯ÀÀ")?></h2>
</div>
<div id='xtree'></div>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/images/file_tree/ui.dynatree.css">
<link rel='stylesheet' type='text/css' href='<?=MYOA_STATIC_SERVER?>/static/images/org/ui.dynatree.css<?=$GZIP_POSTFIX?>'>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/tree.js<?=$GZIP_POSTFIX?>"></script>
<script language="javascript" type="text/javascript">
function tree_loaded(tree, isReloading, isError)
{
   if(tree.count() <= 1)
   {
      $('#xtree').hide();
      $('#msg').show();
   }
}

var xtree = new Tree("xtree", "tree.php?CUR_DIR=<?=$CUR_DIR?>&PIC_ID=<?=$PIC_ID?>", "<?=MYOA_STATIC_SERVER?>/static/images/file_tree/");
xtree.BuildTree();
</script>
</body>
</html>

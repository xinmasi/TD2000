<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("办公用品列表");
include_once("inc/header.inc.php");
if(!isset($xtree_id) || $xtree_id=="")
	$xtree_id="product_tree";
 
if(!isset($xtree) || $xtree=="")
	$xtree="tree.php";
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/images/org/ui.dynatree.css<?=$GZIP_POSTFIX?>" />
<body style="background:white;overflow:">
	<img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" WIDTH="22" HEIGHT="20" align="absmiddle" style="margin-left:15px;"> <b><?=_("办公用品列表")?></b>  
	<div id="<?=$xtree_id?>" style="margin-left: 15px;"></div>
	<script type="text/javascript" src="/inc/js_lang.php"></script>
	<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/tree.js<?=$GZIP_POSTFIX?>"></script>
	<script type="text/javascript">
   		var tree = new Tree("<?=$xtree_id?>", "<?=$xtree?>", '<?=MYOA_STATIC_SERVER?>/static/images/',false, 3);
   		tree.BuildTree();
	</script>
</body>
</html>

<?
include_once("general/crm/inc/header.php");
?>

<ul>
   <li><a href="javascript:;" target="<?=$TARGET?>" title="<?=_("产品分类")?>" id="link_1" class="active"><span><?=_("产品分类")?></span></a></li>
   <div>
   <?
    if(!isset($xtree) || $xtree==""){
        $xtree="./type_tree.php?PARENT_ID=-1&PARA_TARGET=$PARA_TARGET&PARA_URL=$PARA_URL";
        //$xtree="/inc/dept_list/tree.php";
    }
   ?>
   <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/images/org/ui.dynatree.css<?=$GZIP_POSTFIX?>">
   <script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/tree.js<?=$GZIP_POSTFIX?>"></script>
   <div id="tree"></div>
   <script language="javascript" type="text/javascript">
   var tree = new Tree("tree", "<?=$xtree?>");
   tree.BuildTree();
   </script>
   </div>
</ul>


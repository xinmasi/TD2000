<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("办公用品发放");
include_once("inc/header.inc.php");
?>
<style>

.query_wrapper{}
.query_l iframe{width:100%;height:400px;}
.query_r iframe{width:100%;min-height:400px;}
</style>
<div class="query_wrapper">
    <div class="query_l"><iframe name="query_area" src="query.php" frameborder="0"></iframe></div>
    <div class="query_r"><iframe name="search_area" src="search.php" frameborder="0"></iframe></div>
</div>
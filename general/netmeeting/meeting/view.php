<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���������ʾ��������");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="inc.js"></script>

<body bgcolor="#F1FAF5" class="small" onLoad="getAjax('<?=$MEET_ID?>')">
</body>
</html>
<script>

window.setInterval(function(){ 
   getAjax('<?=$MEET_ID?>');
},3000);

</script>
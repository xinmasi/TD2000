<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��������ʾ��������");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="inc.js"></script>

<body bgcolor="#F1FAF5" class="small" onLoad="getAjax('<?=$CHAT_ID?>')">
</body>
</html>
<script>
	window.setInterval(function(){ 
   getAjax('<?=$CHAT_ID?>');
},3000);//�趨3�����һ��

</script>
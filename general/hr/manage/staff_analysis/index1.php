<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("人事分析");
include_once("inc/header.inc.php");
?>
<script>
//如果从OA精灵打开，则最大化窗口
if(window.external && typeof window.external.OA_SMS != 'undefined')
{        
    var h = Math.min(800, screen.availHeight - 180),
        w = Math.min(1280, screen.availWidth - 180);
    window.external.OA_SMS(w, h, "SET_SIZE");
}
</script>
<style>
html,body{height:100%;}
.analysis{position:relative;}
.analysis_top{height:400px;}
.analysis_bottom{
    height: 500px;
}
</style>
<div class="analysis">
    <div class="analysis_top"><iframe name="select" src="select.php?MODULE=<?=$MODULE?>" noresize frameborder="0" style="height:100%;width:100%"></iframe></div>
    <div class="analysis_bottom"><iframe name="tu_main" src="analysis.php?MODULE=<?=$MODULE?>" noresize frameborder="0" style="height:100%;width:100%"></iframe></div>
</div>
</body>
</html>

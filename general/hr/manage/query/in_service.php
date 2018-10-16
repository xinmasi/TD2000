<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("在职人员");
//include_once("user_list.php");
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
.service_wrapper{
    position: relative;
    height: 100%;
}
.service_wrapper_l{
    position: absolute;
    top: 0;
    left: 0;
    width: 200px;
    height: 100%;
}
.service_wrapper_r{
    margin-left: 201px;
    border-left: 1px solid #ddd;
    height: 100%;
}
</style>
<div class="service_wrapper">
    <div class="service_wrapper_l"><iframe name="left_tree" src="user_list.php" noresize frameborder="0" style="height:100%;width:100%"></iframe></div>
    <div class="service_wrapper_r"><iframe name="hrms" src="query.php" noresize frameborder="0" style="height:100%;width:100%"></iframe></div>
</div>
</body>
</html>

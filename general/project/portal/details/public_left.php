<script>
jQuery(document).ready(function(){
    jQuery("#notify_menu li").removeClass();
    jQuery("#sidebar_li_<?=$_GET['VALUE']? $_GET['VALUE'] : 2 ;?>").addClass("active");
    
    jQuery("#notify_menu li a").click(function(e){
        e.preventDefault();
        window.location = jQuery(this).attr("href") + "&PROJ_ID=<?=$i_proj_id?>";
    });
});
</script>
<div class="sidebar_content">
    <ul id="notify_menu" class="nav nav-list">
        <li id="sidebar_li_1">
            <a href="proj_detail.php?VALUE=1"><span class="search-1"></span><?=_("��Ŀ����")?></a>
        </li>
        <li id="sidebar_li_2">
            <a href="proj_progression.php?VALUE=2"><span class="progress-1"></span><?=_("��Ŀ����")?></a>
        </li>
        <li id="sidebar_li_3">
            <a href="proj_time.php?VALUE=3"><span class="time-1"></span><?=_("ʱ�����")?></a>
        </li>
        <li id="sidebar_li_4">
            <a href="proj_budget.php?VALUE=4"><span class="money-1"></span><?=_("Ԥ�㼰�ɱ�")?></a>
        </li>
        <li id="sidebar_li_5">
            <a href="proj_resource.php?VALUE=5"><span class="setting-1"></span><?=_("��Դ����")?></a>
        </li>
        <li id="sidebar_li_6">
            <a href="proj_report.php?VALUE=6"><span class="paper-1"></span><?=_("���ܱ���")?></a>
        </li>
    </ul>
</div>
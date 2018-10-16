<html>
<body>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active">
            <a id="Jlink" href="#Jzone" data-toggle="tab"><?=_("基本信息")?></a>
        </li>
        <li>
            <a id="Plink" href="#Pzone" data-toggle="tab"><?=_("项目文档")?></a>
        </li>
        <li>
            <a id="Tlink" href="#Tzone" data-toggle="tab"><?=_("任务列表")?></a>
        </li> 
        <li>
            <a id="Blink" href="#Bzone" data-toggle="tab"><?=_("问题追踪")?></a>
        </li>  
        <li>
            <a id="Dlink" href="#Dzone" data-toggle="tab"><?=_("讨论区")?></a>
        </li>  
        <li>
            <a id="Clink" href="#Czone" data-toggle="tab"><?=_("项目批注")?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="Jzone" style="overflow-y:auto;height:90%">
            <? include_once("basic.php");?>
        </div>
        <div class="tab-pane " id="Pzone" style="height:90%;overflow:hidden">
            <iframe  name="content_frame" marginwidth=0 marginheight=0 width=100% height=500 src="../../proj/file/index.php?PROJ_ID=<?=$i_proj_id?>" frameborder=0></iframe>
        </div>
        <div class="tab-pane " id="Tzone" style="height:90%;overflow:hidden">
            <iframe  name="content_frame" marginwidth=0 marginheight=0 width=100% height=500 src="../../proj/task/index.php?PROJ_ID=<?=$i_proj_id?>" frameborder=0></iframe>
        </div>
        <div class="tab-pane " id="Bzone" style="height:90%;overflow:hidden">
            <iframe   name="content_frame" marginwidth=0 marginheight=0 width=100% height=500 src="../../proj/bug/index.php?PROJ_ID=<?=$i_proj_id?>" frameborder=0></iframe>
        </div>
        <div class="tab-pane " id="Dzone" style="height:90%;overflow:hidden">
            <iframe  name="content_frame" marginwidth=0 marginheight=0 width=100% height=500 src="../../proj/forum/index.php?PROJ_ID=<?=$i_proj_id?>" frameborder=0></iframe>
        </div>
        <div class="tab-pane " id="Czone" style="height:90%;overflow:hidden">
            <iframe  name="content_frame" marginwidth=0 marginheight=0 width=100% height=500 src="../../proj/comment/index.php?PROJ_ID=<?=$i_proj_id?>" frameborder=0></iframe>
        </div>
    </div>
</div>
</body>
</html>

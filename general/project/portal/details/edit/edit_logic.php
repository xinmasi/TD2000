<html>
<body>
<div class="tabbable"><br/>
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#Ezone" data-toggle="tab"><?=_("基本信息")?></a>
        </li>
        <li>
            <a  href="#Uzone" data-toggle="tab"><?=_("项目成员")?></a>
        </li>
        <li>
            <a  href="#Tzone" data-toggle="tab"><?=_("任务信息")?></a>
        </li>
        <li>
            <a href="#Fzone" data-toggle="tab"><?=_("项目文档")?></a>
        </li>
    </ul>
</div>
<div class="tabbable" >
    <div class="tab-content" style="overflow:auto;">
        <!-- 基本信息修改 -->
        <div class="tab-pane active" id="Ezone" style="overflow-y:auto;height:90%">
            <?php include_once("edit/edit_essential.php");?>
        </div>
        <!-- 项目成员修改 -->
        <div class="tab-pane" id="Uzone" style="overflow-y:auto;height:90%">
            <iframe name="content_frame" marginwidth=0 marginheight=0 width=100% height=450 src="../../proj/new/user/index.php?PROJ_ID=<?=$i_proj_id?>&EDIT_FLAG=1" frameborder=0></iframe>
        </div>
        <!-- 任务信息修改 -->
        <div class="tab-pane" id="Tzone" style="overflow-y:auto;height:90%">
            <iframe name="content_frame" marginwidth=0 marginheight=0 width=100% height=800 src="../../proj/new/task/index.php?PROJ_ID=<?=$i_proj_id?>&EDIT_FLAG=1" frameborder=0></iframe>
        </div>
        <!-- 项目文档修改-->
        <div class="tab-pane" id="Fzone" style="overflow-y:auto;height:90%">
            <iframe name="content_frame" marginwidth=0 marginheight=0 width=100% height=450 src="../../proj/new/file/index.php?PROJ_ID=<?=$i_proj_id?>&EDIT_FLAG=1" frameborder=0></iframe>
        </div>
    </div>
</div>
</body>
</html>

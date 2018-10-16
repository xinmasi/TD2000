<?
include_once("inc/auth.inc.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("人员关注");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/concern.css?v=150309">
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.10.2/template/jquery.tmpl.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_STATIC_SERVER?>/static/js/module.js?v=141029"></script>
<body class="bodycolor">
<div class="concernwrapper clearfix">
    <div id="concern_l">
        <div class="concern_hd clearfix">
            <span id="group-title"><?=_("全部")?></span><?=_("关注")?><span id="count" class="color-orange"></span><?=_("人")?><button type="button" class="btn btn-small pull-right add_concern" title="<?=_("管理人员")?>" id="add_concern"><i class="icon-user"></i></button>
        </div>
        <input type="hidden" id="userid" name="TO_UID" value="">
        <input type="hidden" id="username" name="TO_NAME" value="">
        <div class="concern_control">
            <button type="button" class="btn btn-small" id="manage"><?=_("批量管理")?></button>
            <div class="manage">
                <button type="button" class="btn btn-small btn-info allset pull-right" disabled id="managepriv"><?=_("设置权限")?></button>
                <button type="button" class="btn btn-small btn-info allset pull-right" disabled id="pacth_group"><?=_("设置分组")?></button>
                <button type="button" class="btn btn-small allset pull-right" id="pacth_cancel" disabled><?=_("取消关注")?></button>
                <button type="button" class="btn btn-small" id="closemanage"><?=_("退出批量管理")?></button>
            </div>
        </div>
        <div class="concern_left clearfix feedline" id="feedline">
        </div>
        <div id="loading"><div><?=_("努力加载中...")?></div><img src="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/images/loading.gif" /></div>
        <div id="empty-tips" class="empty-tips"><?=_("暂未添加成员")?></div>
        <div id="concern-pagination" class="pagination pull-right">                   
        </div>
    </div>
    <div class="concern_right">
        <div class="side">
            <div class="side_hd"><i class="iconfont" style="margin-right: 5px;">&#xe659;</i><?=_("我的分组")?><i class="iconfont set-icon pull-right" id="editgroup">&#xe64c;</i></div>
            <div class="side_bd">
                <ul id="group">
                    <li><a href="javascript:;" g_name="<?=_("全部")?>" class="active allgroup" id="allgroup"><i class="iconfont all-icon">&#xe658;</i><?=_("全部")?></a></li>
                    <li><a href="javascript:;" gid="0" g_name="<?=_("未分组")?>"><?=_("未分组")?></a></li>
                    <ul id="define-group" class="define-group">
                    </ul>
                </ul>
                <ul>
                    <li><a href="javascript:;" style="text-align: right;cursor: default;" class="clearfix"><button id="addgroup" type="button" class="btn btn-primary btn-small"><i class="iconfont">&#xe654;</i><?=_("添加分组")?></button></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="concern_layer" id="layer">
    <div class="layer_arrow"></div>
    <div class="layer_title"><?=_("请选择关注内容：")?></div>
    <div class="layer_bd" id="layer_bd"></div>
    <input type="hidden" id="concernuser" value="" />
    <div class="layer_ft">
        <button type="button" class="btn btn-small btn-primary l_save"><?=_("保存")?></button>
        <button type="button" class="btn btn-small l_cancel"><?=_("取消")?></button>
    </div>
</div>
<div class="concern_layer" id="layer_patch">
    <div class="layer_arrow"></div>
    <div class="layer_title"><?=_("请选择批量关注内容：")?></div>
    <div class="layer_bd" id="layer_bd_patch">
    </div>
    <div class="layer_ft">
        <button type="button" class="btn btn-small btn-primary" id="pacth_save"><?=_("保存")?></button>
        <button type="button" class="btn btn-small" id="pacth_close"><?=_("取消")?></button>
    </div>
</div>
<div class="concern_layer" id="group_layer">
    <div class="layer_arrow"></div>
    <div class="layer_title"><?=_("请选择分组：")?></div>
    <div class="layer_bd" id="group_layer_bd">    
    </div>
    <input type="hidden" id="group_user" value="" />
    <div class="layer_ft">
        <button type="button" class="btn btn-small btn-primary" id="group_save"><?=_("保存")?></button>
        <button type="button" class="btn btn-small" id="group_cancel"><?=_("取消")?></button>
    </div>
</div>
<div class="concern_layer" id="patch_group_layer">
    <div class="layer_arrow"></div>
    <div class="layer_title"><?=_("请选择分组：")?></div>
    <div class="layer_bd" id="patch_group_bd">
    </div>
    <div class="layer_ft">
        <button type="button" class="btn btn-small btn-primary" id="pacth_save_group"><?=_("保存")?></button>
        <button type="button" class="btn btn-small" id="pacth_close_group"><?=_("取消")?></button>
    </div>
</div>
<div id="newgroup" class="modal hide in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?=_("新建分组")?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="groupname" id="grouptitle">分组名称<input type="text" id="groupname"></label>
        </div>
    </div>               
    <div class="modal-footer">
        <button class="btn btn-primary" id="add-group"><?=_("确定")?></button>
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("取消")?></button>
    </div>
</div>
<div id="edit-modal" class="modal hide in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?=_("编辑分组")?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="editgroupname" id="edittitle" style="text-align:center"><?=_("分组名称")?><input type="text" gid="" id="editgroupname" style="margin-left: 20px;"></label>
        </div>
    </div>               
    <div class="modal-footer">
        <button class="btn btn-primary" id="save-edit-group"><?=_("确定")?></button>
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("取消")?></button>
    </div>
</div>
</body>
<script id="feedTmpl" type="text/x-jquery-tmpl">
<div class="feedlist" id="feed_${user_id}" userid="${user_id}">
    <div class="feed_check"></div>
    <i class="iconfont cancel">&#xe651;</i>
    <div class="feed_avator"><img src="${path}" /></div>
    <div class="feed_profile">
        <div class="feed_name">${user_name}</div>
        <div class="feed_info">
            <p><?=_("部门：")?>${dept_name}</p>
            <p><?=_("职位：")?>${user_priv}</p>
            {{if pc_login_priv==1 && mobile_login_priv==0}}
            <p style="color: #FF5D5D;"><?=_("注：该用户被限制使用电脑登录")?></p>
            {{/if}}
            {{if mobile_login_priv==1 && pc_login_priv == 0 }}
            <p style="color: #FF5D5D;"><?=_("注：该用户被限制登录手机客户端")?></p>
            {{/if}}
            {{if mobile_login_priv==1 && pc_login_priv == 1 }}
            <p style="color: #FF5D5D;"><?=_("注：该用户被限制登录OA")?></p>
            {{/if}}
        </div>
        <input type="hidden" class="concern_content" value="${concern_key}" />
        <input type="hidden" class="groupid" value="${group_id}" />
    </div>
    <div class="feed_btn">
        <div class="drop-layer"></div>
        <div class="group-toggle"><span class="feed_group_name">${group_name}</span><em class="group_down"></em></div>
        <div class="setpriv_icon"><i class="iconfont setpriv">&#xe64c;</i></div>
    </div>
</div>
</script>
<script id="groupTmpl" type="text/x-jquery-tmpl">
<li><a href="#" gid="${group_id}" orderid="" g_name="${group_name}" class="groupitem"><i class="iconfont draghandle">&#xe652;</i><span class="grouptext" title="${group_name}">${group_name}</span><i class="iconfont edithandle">&#xe657;</i><i class="iconfont delhandle">&#xe655;</i></a></li>
</script>
<script>
//create by tianlin 20141028
(function($){
    var tConcern = {
        searchtimer: null,
        params: {
            curpage: 1,
            pagelimit: 10
        },
        init: function(){
            this.bindEvent();
            this.getCount();
            this.getFeed();
            this.getGroup();
            this.getAllUser();
        },
        lastRequestTime: null,
        lastCountRequestTime: null,
        bindEvent: function(){
            var self = this;
            //点击创建分组
            $("#addgroup").click(function(){
                $("#newgroup").modal('show');
            });
            //新建分组保存
            $("#add-group").click(function(){
                var groupname = $("#groupname").val();
                if($.trim(groupname) == ""){
                    alert("分组名称不能为空");
                    return;
                }
                $.ajax({
            		type: 'GET',
            		url: 'concern_function.php',
            		data: {
            		    load: 'add_group',
            		    groupname: groupname
            		},
            		cache: false,
            		success: function(data){
            		    if(data == "ok"){
            		        
            		        $("#newgroup").modal('hide');
            		        $("#groupname").val("");
            		        self.getGroup();
            		    }
            		    else if(data == "same"){
            		        alert("该组名已存在");
            		    }
            		    else if(data == "count"){
            		        alert("自定义分组不能超过20个");
            		    }
            		    else{
            		        alert("分组创建失败");
            		    }
            		}
                });
            });
            //分组设置拖拽接口，3秒保存
            $("#editgroup").click(function(){
                $(".side").toggleClass("set");
                if($(".side").hasClass("set")){
                    $("#define-group").sortable({
                         sort: function(){
                             self.searchtimer && clearTimeout(self.searchtimer);
                         },
                         stop: function(event, ui) {
                            var group_id_str = "";
                            $("#define-group a").each(function(){
                                group_id_str += $(this).attr("gid")+",";
                            });
                            self.searchtimer && clearTimeout(self.searchtimer);
                            self.searchtimer = setTimeout(function ()
                            {
                                self.setSort(group_id_str);
                            },3000)//1秒延时保存
                         } 
                    });
                    
                }
                else{
                    $("#define-group").sortable( 'destroy' );
                }
            });
            //新建关注
            $('#add_concern').click(function(){
                var module_id = 'concern', 
                to_id = "TO_UID", 
                to_name = "TO_NAME", 
                manage_flag, 
                form_name = "";
                window.org_select_callbacks = window.org_select_callbacks || {};
                window.org_select_callbacks.add = function(item_id, item_name){
                    self.addConcern(item_id, item_name);
                };
                window.org_select_callbacks.remove = function(item_id, item_name){
                    self.removeConcern(item_id, item_name);
                };                
                window.org_select_callbacks.clear = function(){
                };
                SelectUser('11', module_id, to_id, to_name, manage_flag, form_name);
                return false;
            });               
            //右侧选择分组
            $('#group').delegate('a', 'click', function(){
                $('#group a').removeClass('active');
                $(this).addClass('active');
                var group_id = $(this).attr('gid');
                var group_name = $(this).attr('g_name');
                $("#group-title").text(group_name);
                self.getCount(group_id);
                self.params.curpage = 1;
                self.getFeed(group_id);
                self.getAllUser(group_id);
                $('#closemanage').trigger('click');
                $(".concern_layer").hide();
            });
            //右侧删除指定分组
            $('#define-group').delegate('.delhandle', 'click', function(){
                var gid = $(this).parent(".groupitem").attr("gid");
                self.deleteGroup(gid);
                return false;
            });
            //右侧编辑指定分组
            $('#define-group').delegate('.edithandle', 'click', function(){
                var gid = $(this).parent(".groupitem").attr("gid");
                var g_name = $(this).parent(".groupitem").attr("g_name");
                $("#edit-modal").modal('show');
                $("#editgroupname").val(g_name);
                $("#editgroupname").attr("gid",gid);
                return false;
            });
            //保存编辑分组
            $('#save-edit-group').click(function(){
                var groupid = $("#editgroupname").attr("gid");
                var groupname = $("#editgroupname").val();
                $.ajax({
            		type: 'GET',
            		url: 'concern_function.php',
            		data: {
            		    load: 'edit_group',
            		    groupid: groupid,
            		    groupname: groupname
            		},
            		cache: false,
            		success: function(data){
            		    if(data == "ok"){
            		        $("#edit-modal").modal('hide');
            		        $("#editgroupname").val("").attr("gid","");
            		        self.getGroup();
            		    }
            		    else if(data == "same"){
            		        alert("该组名已存在");
            		    }
            		    else{
            		        alert("分组修改失败");
            		    }
            		}
                });
            });
            //feed流点击某人分组弹框
            $('body').delegate('.group_down', 'click', function(){ 
                //显示设置权限框
                if($('#group_layer').is(':hidden')){
                    var self = $(this);
                    var l_left = $(this).offset().left-90;
                    var l_top = $(this).offset().top + 23;
                    $('#group_layer').css({
                        "left": l_left,
                        "top": l_top
                    });
                    $('#group_layer').slideDown(200);
                    //获取所有分组并勾选已关注项
                    $.ajax({
                		type: 'GET',
                		url: 'concern_function.php',
                		data: {
                		    load: 'group'
                		},
                		cache: false,
                		success: function(data){
                		    d = JSON.parse(data);
                		    var html = "";
                		    $.each(d, function(k, v){
                		        var privs = self.parents('.feedlist').find('.groupid').val();
                                var arr = privs.split(',');
                                var flag = "";
                                for(var i in arr){
                                    if(arr[i]!=="" && arr[i]==v.group_id){
                                        flag = 'checked="checked"';
                                    }
                                }
                		        var check = '<label class="checkbox"><input id="'+v.group_id+'" type="checkbox" txt="'+v.group_name+'" class="group_check" title="'+ v.group_name +'" value="'+v.group_id+'" '+flag+'>'+v.group_name+'</label>';
                		        html += check;
                		    });
                		    $('#group_layer_bd').html(html);  
                		}
                    });  
                    //获取对应userid
                    var user_id = $(this).parents('.feedlist').attr('userid');
                    $('#group_user').val(user_id);
                }
                else{
                    $('#group_layer').slideUp(200);
                }
            });
            //弹框勾选分组
            $('#group_layer').delegate('.group_check', 'click', function(){
                if($(this).attr("checked") == "checked"){
                    $(this).attr("checked",false);
                }else{
                    $(this).attr("checked",true); 
                }
            });
            //弹框保存分组
            $('#group_save').click(function(){
                //设置权限的用户id
                var user_id = $('#group_user').val();
                //设置的权限
                var group_id_str="";
                var group_name_str="";
                $("#group_layer input[type='checkbox']").each(function(){
                    if($(this).attr("checked") == "checked"){
                        group_id_str += $(this).val()+",";
                        group_name_str += $(this).attr('txt')+",";
                    }
                });
                self.setGroup(user_id, group_id_str, group_name_str);
            });
            //弹框取消分组
            $('#group_cancel').click(function(){
                $('#group_layer').hide();
            });
            //feed流设置关注项
            $('body').delegate('.setpriv', 'click', function(){
                //显示设置权限框
                if($('#layer').is(':hidden')){
                    var self = $(this);
                    var l_left = $(this).offset().left;
                    var l_top = $(this).offset().top + 25;
                    $('#layer').css({
                        "left": l_left,
                        "top": l_top
                    });
                    $('#layer').slideDown(200);
                    //获取所有权限类型并勾选已关注项
                    $.ajax({
                		type: 'GET',
                		url: 'concern_function.php',
                		data: {
                		    load: 'get_priv'
                		},
                		cache: false,
                		success: function(data){
                		    d = JSON.parse(data);
                		    var html = "";
                		    $.each(d, function(k, v){
                		        var privs = self.parents('.feedlist').find('.concern_content').val();
                                var arr = privs.split(',');
                                var flag = "";
                                for(var i in arr){
                                    if(arr[i]!=="" && arr[i]==k){
                                        flag = 'checked="checked"';
                                    }
                                }
                		        var check = '<label class="checkbox"><input id="'+k+'" type="checkbox" txt="'+v+'" class="concern_check" value="'+k+'" '+flag+'>'+v+'</label>';
                		        html += check;
                		    });
                		    $('#layer_bd').html(html);  
                		}
                    });  
                    //获取对应userid
                    var user_id = $(this).parents('.feedlist').attr('userid');
                    $('#concernuser').val(user_id);
                }
                else{
                    $('#layer').slideUp(200);
                }
            });
            //feed流取消关注
            $('body').delegate('.cancel', 'click', function(){
                var group_id = $('#group a.active').attr('gid');
                var userid = $(this).parents('.feedlist').attr('userid');
                var username = $(this).parents('.feedlist').find(".feed_name").text();
                self.cancel(userid, group_id);
            });
            //弹框勾选关注项
            $('#layer').delegate('.concern_check', 'click', function(){
                if($(this).attr("checked") == "checked"){
                    $(this).attr("checked",false);
                }else{
                    $(this).attr("checked",true); 
                }
            });
            //弹框保存关注项
            $('.l_save').click(function(){
                //设置权限的用户id
                var user_id = $('#concernuser').val();
                //设置的权限
                var concern_content="";
                var concern_txt="";
                $("#layer input[type='checkbox']").each(function(){
                    if($(this).attr("checked") == "checked"){
                        concern_content += $(this).val()+",";
                        concern_txt += $(this).attr('txt')+",";
                    }
                });
                self.setPri(user_id, concern_content, concern_txt);
            });
            //弹框取消权限
            $('.l_cancel').click(function(){
                $('#layer').hide();
            });
            //批量管理
            $('#manage').click(function(){
                $(this).hide();
                $('.manage').show();
                $(".feedline").addClass("nodelete");
                $('.allset').attr('disabled',true);
                //批量管理选择名片
                $('.feedline').delegate('.feedlist', 'click', function(){
                    $(this).toggleClass('active');
                    if($('.feedlist.active').length>0){
                        $('.allset').attr('disabled',false);
                    }
                    else{
                        $('.allset').attr('disabled',true);
                    }
                });
            });
            //退出批量管理
            $('#closemanage').click(function(){
                $('.manage').hide();
                $(".feedline").removeClass("nodelete");
                $('#layer_patch').hide();
                $('#patch_group_layer').hide();
                $('#manage').show();
                $('.feedlist').removeClass('active');
                $('.feedline').unbind("click");
            });
            //批量设置关注项
            $('#managepriv').click(function(){
                if($('#layer_patch').is(':hidden')){
                    var self = $(this);
                    var l_left = $(this).offset().left;
                    var l_top = $(this).offset().top + 30;
                    $('#layer_patch').css({
                        "left": l_left,
                        "top": l_top
                    });
                    $('#layer_patch').slideDown(200);
                    //获取所有权限类型并勾选已关注项
                    $.ajax({
                		type: 'GET',
                		url: 'concern_function.php',
                		data: {
                		    load: 'get_priv'
                		},
                		cache: false,
                		success: function(data){
                		    d = JSON.parse(data);
                		    var html = "";
                		    $.each(d, function(k, v){
                		        var check = '<label class="checkbox"><input type="checkbox" txt="'+v+'" class="concern_check" value="'+k+'">'+v+'</label>';
                		        html += check;
                		    });
                		    $('#layer_bd_patch').html(html);  
                		}
                    });  
                }
                else{
                    $('#layer_patch').slideUp(200);
                }
            });
            //批量设置勾选关注项
            $('#layer_patch').delegate('.concern_check', 'click', function(){
                if($(this).attr("checked") == "checked"){
                    $(this).attr("checked",false);
                }else{
                    $(this).attr("checked",true); 
                }
            });
            //批量设置保存关注项
            $('#pacth_save').click(function(){
                //获取批量选择userid
                var user_id = "";
                $('.feedlist.active').each(function(){
                    user_id += $(this).attr('userid') + ',';
                });
                //设置的权限
                var concern_content="";
                $("#layer_bd_patch input[type='checkbox']").each(function(){
                    if($(this).attr("checked") == "checked"){
                        concern_content += $(this).val()+",";
                    }
                });
                self.setPatchPri(user_id, concern_content);
            });
            //批量设置关注项关闭弹框
            $('#pacth_close').click(function(){
                $('#layer_patch').hide();
                $("#layer_bd_patch input[type='checkbox']").attr("checked",false);
            });
            //批量取消关注
            $('#pacth_cancel').click(function(){
                //获取批量选择userid
                var user_id = "";
                $('.feedlist.active').each(function(){
                    user_id += $(this).attr('userid') + ',';
                });
                var group_id = $('#group a.active').attr('gid');
                self.cancelPatchPri(user_id, group_id);
            });
            //批量设置分组
            $("#pacth_group").click(function(){
                if($('#patch_group_layer').is(':hidden')){
                    var self = $(this);
                    var l_left = $(this).offset().left;
                    var l_top = $(this).offset().top + 30;
                    $('#patch_group_layer').css({
                        "left": l_left,
                        "top": l_top
                    });
                    $('#patch_group_layer').slideDown(200);
                    //获取所有权限类型并勾选已关注项
                    $.ajax({
                		type: 'GET',
                		url: 'concern_function.php',
                		data: {
                		    load: 'group'
                		},
                		cache: false,
                		success: function(data){
                		    d = JSON.parse(data);
                		    var html = "";
                		    $.each(d, function(k, v){
                		        var check = '<label class="checkbox"><input type="checkbox" txt="'+v.group_name+'" class="pacth_group_check" value="'+v.group_id+'">'+v.group_name+'</label>';
                		        html += check;
                		    });
                		    $('#patch_group_bd').html(html);  
                		}
                    });  
                }
                else{
                    $('#patch_group_layer').slideUp(200);
                }
            });
            //批量设置勾选分组
            $('#patch_group_layer').delegate('.pacth_group_check', 'click', function(){
                if($(this).attr("checked") == "checked"){
                    $(this).attr("checked",false);
                }else{
                    $(this).attr("checked",true); 
                }
            });
            //批量设置保存分组
            $('#pacth_save_group').click(function(){
                //获取批量选择userid
                var user_id = "";
                $('.feedlist.active').each(function(){
                    user_id += $(this).attr('userid') + ',';
                });
                //设置的权限
                var group_id_str="";
                $("#patch_group_bd input[type='checkbox']").each(function(){
                    if($(this).attr("checked") == "checked"){
                        group_id_str += $(this).val()+",";
                    }
                });
                self.setPatchGroup(user_id, group_id_str);
            });
            //批量设置分组关闭弹框
            $('#pacth_close_group').click(function(){
                $('#patch_group_layer').hide();
                $("#patch_group_bd input[type='checkbox']").attr("checked",false);
            });
        },
        addConcern: function(userid, username){
            var self = this,
                gid = $("#group .active").attr("gid");
            if(gid == "undefined" || gid == null){
                gid = 0;
            }
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'concern',
        		    concern_content:"COMMUNITY,",
        		    group_id: gid,
        		    user_id:userid
        		},
        		cache: false,
        		success: function(data){
        		    if(data == "ok"){
        		        //添加成功后刷新关注数字和当前组
        		        self.refreshCurrent();
        		    }
        		}
            });
        },
        removeConcern: function(userid, username){
			var gid = $("#group .active").attr("gid");
            var self = this;
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'cancel_concern',
        		    user_id:userid,
					group_id:gid
        		},
        		cache: false,
        		success: function(data){
        		    if(data == "ok"){
        		        //删除成功后刷新关注数字和当前组
        		        self.refreshCurrent();
        		    }
        		}
            });
        },
        //刷新关注数字和当前组
        refreshCurrent: function(){
            var self = this,
                gid = $("#group .active").attr("gid");
            self.getCount(gid);
            self.getFeed(gid);
        },
        //关注数
        getCount: function(group_id){   
            var self = this;
            var time = new Date;
            self.lastCountRequestTime = time;
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'count_concern',
        		    group_id: group_id
        		},
        		cache: false,
        		success: function(data){
        		    if(time < self.lastCountRequestTime){
                        return;
                    }
        		    $('#count').text(data); 
        		}
            });     
        },
        //删除分组
        deleteGroup: function(group_id){
            var self = this;
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'del_group',
        		    group_id: group_id
        		},
        		cache: false,
        		success: function(data){
        		    self.getGroup();
        		    $("#allgroup").trigger("click"); 
        		}
            });     
        },
        //feed流
        getFeed: function(group_id){
            $('#feedline').html("");
            var $loading = $('#loading');
            $loading.show();
            var $emptytip = $("#empty-tips");
            $emptytip.hide();
            var self = this;
            var time = new Date;
            self.lastRequestTime = time;  
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'list',
        		    curPage: self.params.curpage,
        		    pageLimit: self.params.pagelimit,
        		    group_id: group_id
        		},
        		cache: false,
        		success: function(data){
        		    if(time < self.lastRequestTime){
        		        $loading.hide();
                        return;
                    }
        		    d = JSON.parse(data);
        		    if(d.datalist.length > 0){
        		        $emptytip.hide();
        		        $('#feedline').html($('#feedTmpl').tmpl(d.datalist));
        		        self.params.curpage = d.curpage;
        		        self.setPagination(d.curpage, d.totalpage);
        		        $loading.hide();
        		    } 
        		    else{
        		        $loading.hide();
        		        self.setPagination(0, 0);
        		        $emptytip.show();
        		    }
        		},
        		error: function(){
        		    $loading.hide();
                    self.setPagination(0, 0);               
        		}
            });     
        },
        //获取关注的所有人的userid和username
        getAllUser: function(gid){
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'getalluser',
        		    group_id: gid
        		},
        		cache: false,
        		success: function(data){
        		    d = JSON.parse(data);
        		    $("#userid").val(d[0].useridstr);
	                $("#username").val(d[0].usernamestr);
        		}
            });
        },
        //右侧分组
        getGroup: function(){
            var self = this;
            $('#define-group').html("");
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'group'
        		},
        		cache: false,
        		success: function(data){
        		    d = JSON.parse(data);
        		    $('#define-group').append($('#groupTmpl').tmpl(d));   
        		}
            });   
        },
        //拖拽组名保存
        setSort: function(group_id_str){
            var self = this;
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'updatesort',
        		    group_id_str: group_id_str
        		},
        		cache: false,
        		success: function(data){
        		    console.log("拖拽成功");
        		}
            });  
        },
        //feed流取消关注
        cancel: function(userid, group_id){
            //remove dom
            var self = this;
            $("#feed_"+userid).remove();
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'cancel_concern',
        		    group_id: group_id,
        		    user_id: userid
        		},
        		cache: false,
        		success: function(data){
        		    self.getCount(group_id);
        		    self.getFeed(group_id);
        		    //翻页后删除最后一个返回的curpage不对
        		    self.getAllUser(group_id);
        		}
            });  
        },
        //feed流保存分组
        setGroup: function(user_id, group_id_str, group_name_str){
            //如果为选择分组，默认放入未分组
            if(group_id_str == ""){
                group_id_str = 0;
            }
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'select_group',
        		    user_id: user_id,
        		    group_id_str: group_id_str
                },
        		cache: false,
        		success: function(data){
        		    if(data == "ok"){
        		        $('#group a.active').trigger('click');
        		    }
        		    else{
        		        alert('分组设置失败');
        		    }
        		}
            });  
        },
        //feed流保存关注项
        setPri: function(user_id, concern_content, concern_txt){
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'edit_priv',
        		    user_id: user_id,
        		    concern_content: concern_content
                },
        		cache: false,
        		success: function(data){
        		    if(data == "ok"){
        		        //刷新对应权限
        		        var html = "";
        		        var arr = concern_txt.split(',');
        		        for(var i in arr){
        		            if(arr[i] !== ""){
        		                var concern_item = '<span class="">'+arr[i]+'</span>';
            		            html += concern_item;
        		            }
                        }
        		        $('#feed_'+user_id).find('.concern_content').val(concern_content);
        		        $('#layer').hide();
        		    }
        		    else{
        		        alert('设置失败');
        		    }
        		}
            });  
        },
        //feed流批量设置关注项
        setPatchPri: function(user_id, concern_content){
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'batch_priv',
        		    user_str: user_id,
        		    concern_str: concern_content
                },
        		cache: false,
        		success: function(data){
        		    if(data == "ok"){
        		        $('#layer_patch').hide();
        		        $('#group a.active').trigger('click');
        		    }
        		    else{
        		        alert('批量设置失败');
        		        $('#layer_patch').hide();
        		    }
        		}
            });  
        },
        //feed流批量设置分组
        setPatchGroup: function(user_id, group_id_str){
            //如果为选择分组，默认放入未分组
            if(group_id_str == ""){
                group_id_str = 0;
            }
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'batch_group',
        		    user_id_str: encodeURIComponent(user_id),
        		    group_id_str: group_id_str
                },
        		cache: false,
        		success: function(data){
        		    if(data == "ok"){
        		        $('#patch_group_layer').hide();
        		        $('#allgroup').trigger('click');
        		    }
        		    else{
        		        alert('批量设置分组失败');
        		        $('#patch_group_layer').hide();
        		    }
        		}
            });  
        },
        //批量取消关注
        cancelPatchPri: function(user_id, group_id){
            var self = this;
            $.ajax({
        		type: 'GET',
        		url: 'concern_function.php',
        		data: {
        		    load: 'batch_cancel',
        		    user_str: user_id,
        		    group_id: group_id
                },
        		cache: false,
        		success: function(data){
        		    if(data == "ok"){
        		        self.getCount(group_id);
        		        self.getFeed(group_id);
        		        self.getAllUser(group_id);
        		    }
        		    else{
        		        alert('批量取消失败');
        		    }
        		}
            });  
        },
        initPagination: function(){            
            var self = this,
            $pagi = $('#concern-pagination');  
            $pagi.bootstrapPaginator({
                totalPages: 10,
                alignment: 'right',
                pageUrl: "javascript:void(0)",
                onPageChanged: function(e, prev, next){
                    self.params.curpage = next;
                    var groupid = $('#group a.active').attr('gid');
                    self.getFeed(groupid);
                }
            });
            this.pagination = $pagi.data('bootstrapPaginator');
        },
        setPagination: function(curpage, totalpage){
            curpage = parseInt(curpage, 10);
            totalpage = parseInt(totalpage, 10) || 0;
            $('body,html').animate({scrollTop:0},500);
            if(!this.pagination){
                this.initPagination();
            }
            if(totalpage <= 1){                
                this.pagination.$element.hide();
            }else{
                this.pagination.$element.show();
                this.pagination.currentPage = curpage;
                this.pagination.totalPages = totalpage;
                this.pagination.render();
            }
        }
    };
    window.tConcern = tConcern;
    $(function(){
        tConcern.init();
    });
})(jQuery);
</script>
</html>

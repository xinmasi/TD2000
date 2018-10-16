<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("get_diary_data.func.php");
include_once("inc/editor.php");
$login_user_id=$_SESSION["LOGIN_USER_ID"];

$dia_id = intval($dia_id);
$diary_copy_time = preg_match_all("/[^0-9_\/]+/", $diary_copy_time) ? '' : $diary_copy_time;

if(!$dia_id)
{
    Message(_("错误"),_("该日志不存在或者已经归档!"));
    exit;
}
$state = is_read($login_user_id,$dia_id,$diary_copy_time);

$HTML_PAGE_TITLE = _("日志详情");
include_once("inc/header.inc.php");
$para_array  = get_sys_para("LOCK_TIME,LOCK_SHARE,IS_COMMENTS");
$diary_array = get_diary_detaildata($dia_id,$para_array,$IS_MAIN,$diary_copy_time);
if(count($diary_array)>0)
{
    $dia_id           = $diary_array["dia_id"];//日志主ID
    $dia_date         = $diary_array["dia_date"]; //创建日期
    $dia_time         = $diary_array["dia_time"]; //创建时间
    $subject          = $diary_array["subject"];   //主题
    $dia_type_desc    = $diary_array["dia_type_desc"];  //日志类型
    $content          = $diary_array["content"]; //内容
    $attachments      = $diary_array["attachments"]; //附件名称
    $author_user_id   = $diary_array["author_user_id"];  //作者
    $author_user_name = $diary_array["author_user_name"]; //作者名称
    $author_dept_name = $diary_array["author_dept_name"]; //作者部门名称
    $author_priv_name = $diary_array["author_priv_name"]; //作者角色名称
    $author_photo     = $diary_array["author_photo"]; //作者头像
    $edit_flag        = $diary_array["edit_flag"];  //编辑权限
    $del_flag         = $diary_array["del_flag"];  //删除权限
    $share_flag       = $diary_array["share_flag"];  //设置共享范围权限
    $reply_flag       = $diary_array["reply_flag"];  //点评权限
    $comment_count    = $diary_array["comment_count"];
    $readers          = $diary_array["readers"];
    $top_flag         = $diary_array["top_flag"];//置顶状态
}
else
{
    Message(_("错误"),_("该日志不存在或者已经归档!"));
?>
<center><input type="button" class="BigButtonA" value="<?=_("返回")?>" onClick="window.close();"></center>

<?
    exit;
}



$query  = "SELECT USER_ID FROM diary WHERE DIA_ID='$dia_id'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $USER_ID = $ROW['USER_ID'];
}
if($USER_ID == $_SESSION['LOGIN_USER_ID'])
{
 
    $query  = "UPDATE diary_comment SET COMMENT_FLAG='1'  WHERE DIA_ID='$dia_id'";
    exequery(TD::conn(),$query);
}

if($diary_copy_time!="")//是否归档库查询
{
    $DIARY_TABLE_NAME         = TD::$_arr_db_master['db_archive'].".DIARY".$diary_copy_time;
    $DIARY_COMMENT_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY_COMMENT".$diary_copy_time;
}
else
{
    $DIARY_TABLE_NAME         = "DIARY"; 
    $DIARY_COMMENT_TABLE_NAME = "DIARY_COMMENT";
}
if($state==1)
{
    if(!find_id($readers,$_SESSION["LOGIN_USER_ID"]) && $author_user_id!=$_SESSION["LOGIN_USER_ID"])
    {
        //修改事务提醒状态--yc
        update_sms_status('13',$dia_id);

        $readers = $readers.$_SESSION["LOGIN_USER_ID"].",";
        $query2 = "UPDATE DIARY set READERS='$readers' WHERE DIA_ID='$dia_id'";
        exequery(TD::conn(),$query2);
    }
}
//查找该用户的该日志的上一篇下一篇,按时间排序
//上一篇
$query  = "SELECT DIA_ID FROM ".$DIARY_TABLE_NAME." WHERE USER_ID='$author_user_id' and DIA_TIME < '$dia_time' order by DIA_TIME desc limit 0,1 ";
$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $dia_pre_id = $ROW["DIA_ID"];    
}
else
{
    $dia_pre_id = "";
}
//下一篇
$query  = "SELECT DIA_ID FROM ".$DIARY_TABLE_NAME." WHERE USER_ID='$author_user_id' and DIA_TIME > '$dia_time' order by DIA_TIME asc limit 0,1 ";    
$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $dia_next_id = $ROW["DIA_ID"];    
}
else
{
    $dia_next_id = "";
}

?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/diary.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.ux.attachmenu.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<style>
.feed-cmt-list-item{
    border-top: none;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
    $(".diary-detail-viewchange").click(function(){
        if($("#diary-detail-container").width()==720){
            $("#diary-detail-container").css("width","95%");
            $(".cke_reset").css("width","100%");
            $(".diary-detail-viewchange").text("<?=_("切换到窄版")?>");
            
        }
        else{
            $("#diary-detail-container").css("width","720px");
            $(".diary-detail-viewchange").text("<?=_("切换到宽版")?>");
        };
    })
});
</script>
<body>
    <div id="shareModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="shareModal" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="shareModalLabel"><?=_("共享")?></h3>
        </div>
        <div class="modal-body">  
            <form name="shareform">
                <div id="share-group" class="control-group">
                    <div class="share-control pull-right"> 
                        <button type="button" id="share-plus" class="btn btn-mini"><i class="icon-plus"></i></button>
                        <button type="button" id="share-trash" class="btn btn-mini"><i class="icon-trash"></i></button> 
                    </div>
                    <label class="control-label" ><b><?=_("人员")?></b></label>
                    <div class="controls">
                        <div id="share-tags">  </div>
                        <input type="hidden" id="sharename" name="sharename" />
                        <input type="hidden" id="sharetext" name="sharetext" />
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button id="shareSubmit" class="btn btn-primary"><?=_("确定")?></button>
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("关闭")?></button>
        </div>
    </div>
    <div id="diary-detail-bg">    </div>
    <div id="diary-fixed-box" style="display:none">
        <ul>
            <? if($dia_pre_id!="") {?>
            <li class="prev">
                <a href="show_diary.php?dia_id=<?=$dia_pre_id ?>&diary_copy_time=<?=$diary_copy_time ?>" class="icon-chevron-left"></a>
            </li>
        <? }?>
            <? if($dia_next_id!="") {?>
            <li class="next">
                <a href="show_diary.php?dia_id=<?=$dia_next_id ?>&diary_copy_time=<?=$diary_copy_time ?>" class="icon-chevron-right"></a>
            </li>
            <? }?>
        </ul>
    </div>
    <div id="diary-detail-container" class="container" data-diary-id="<?=$dia_id?>">
        <div id="diary-detail-header">
            <a target="_blank" href="../ipanel/user/user_info.php?USER_ID=<?=$author_user_id?>&WINDOW=1" class="diary-detail-title">
                <h1 class="shoutest">
                    <?=$author_user_name?>
                </h1>
            </a>
            <div class="diary-detail-ext-header">
                <span class="diary-detail-dept"> <?=$author_dept_name?> </span>                     
                <span class="diary-detail-priv"> <?=$author_priv_name?> </span>                 
            </div>
            <span class="diary-detail-viewchange" hidefocus="hidefocus"><?=_("切换到宽版")?></span>
        </div>
        
        <div id="diary-detail-content" class="feed">
            <? if($state==1) {?>
            <div class="pop-content clearfix">
                <div class="feed-hd">
                    <div class="feed-time" title="<?=$dia_time?>">
                        <?=get_time($dia_time)?>
                    </div>
                    <div class="feed-basic">
                        <span class="feed-type">
                            <?=$dia_type_desc?>
                        </span>
                    </div>
                </div>
                <div class="feed-bd">
                    <h4 class="feed-title">
                        <?=$subject?>
                    </h4>
                    <div class="feed-ct">
                        <div class="feed-txt-full rich-content">
                            <div class="feed-txt-summary">
                                <div class="jjl_body">
                                    <?=$content?>        
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="feed-attachments">
                        <?=$attachments?>
                    </div>
                    <div class="feed-act">
                        <?
                        if($del_flag==1)
                        {
                        ?>
                            <a href="javascript:void(0)" data-cmd="del" hidefocus="hidefocus">
                                <?=_("删除")?>
                            </a>
                        <?
                        }
                        if($edit_flag==1)
                        {
                        ?>
                            <a href="new/edit.php?dia_id=<?=$dia_id?>&DIARY_FROM=1&diary_copy_time=<?=$diary_copy_time ?>" data-cmd="edit" hidefocus="hidefocus">
                                <?=_("编辑")?>
                            </a>
                        <?
                        }
                        if($top_flag==1)
                        {
                        ?>
                            <a href="javascript:void(0)" data-cmd="deltop" hidefocus="hidefocus">
                                <?=_("取消置顶")?>
                            </a>
                        <?
                        }
                        else
                        {
                        ?>
                            <a href="javascript:void(0)" data-cmd="addtop" hidefocus="hidefocus">
                                <?=_("置顶")?>
                            </a>
                        <?
                        }
                        if($share_flag==1)
                        {
                        ?>
                            <a href="javascript:void(0)" data-cmd="share" hidefocus="hidefocus" ><?=_("共享")?></a> 
                        <?
                        }
                        if($reply_flag==1)
                        {
                        ?>
                        <a data-cmd="reply" hidefocus="hidefocus" data-cmt-count="<?=$comment_count?>">
                            <?=_("评论")?><?="(".$comment_count.")"?>
                        </a>
                        <?
                        }
                        ?>
                    </div>
                </div>
            </div>
            <? }else
            { ?>
            <div style="background: #FFF;padding: 60px;border: 1px solid #DDD;font-weight: bold;color: #555;font-size: 20px;text-align: center;">
                <?=_("您没有权限查看这篇日志!")?>
            </div>
            <? } ?>
        </div>
        
        <div id="footer">
            <?
            if($dia_pre_id != ''){
            ?>
                <div class="previous-page">
                    <a href="show_diary.php?dia_id=<?=$dia_pre_id ?>&diary_copy_time=<?=$diary_copy_time ?>">
                        <?=_("上一篇")?>
                    </a>
                </div>
            <?
            }
            if($dia_next_id != ''){
            ?>
                <div class="next-page">
                    <a href="show_diary.php?dia_id=<?=$dia_next_id ?>&diary_copy_time=<?=$diary_copy_time ?>">
                        <?=_("下一篇")?>
                    </a>
                </div>
            <?
            }
            ?>
        </div>
        <div class="feed-ext-detailreaders"></div>
        <div class="feed-ext-body">
        <?
        if($reply_flag==0)
            $noform="noform";
        else
           $noform="";
        if($reply_flag==1){
        
        ?>
            <ul class="feed-ext-list <?=$noform?>"> 
            </ul> 
            <div class="feed-ext-add-comment">
                <form target="" action="" name="feed-comment-form">
                    <div class="feed-ext-add-comment-to">
                    </div>
                    <textarea name="TD_HTML_EDITOR_feed-submit-cmt-context-<?=$dia_id?>" class="feed-submit-cmt-context"></textarea>
                    <button type="button" data-cmd="replySubmit" class="btn btn-primary feed-submit-cmt-btn">
                        <?=_("提交")?>
                    </button>
                    <input type="hidden" name="comment-to" value="">
                    <input type="hidden" name="comment-id" value="">
                    <input type="hidden" name="comment-type" value="">
                    <input type="hidden" name="diary-id" value="<?=$dia_id?>">
                    <div class="feed-ext-comment-sms-op">
                        <?           
                        echo sms_remind(13)
                        ?>
                    </div>
                    <div class="feed-ext-comment-sms-advcomment">
                       <label>
                           <input type="checkbox" name="advcomment" class="advcomment" />高级评论
                       </label>
                    </div>
                </form>
            </div>
        <?
       }
        ?>        
    </div>
    </div>
    <div style="display:none">
    <?
    $editor = new Editor('') ;
    $editor->Create() ;
    ?>
    </div>
</body>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/template/jquery.tmpl.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script id="cmtTmpl" type="text/x-jquery-tmpl">
<li class="feed-cmt-list-item ${type}" data-cmt-id="${id}" data-comment-to-id="${comment_id}">
    <a href="javascript:void(0);" td-user-id="${from_id}" class="feed-cmt-list-user">${from}</a>
    {{if to}}
        <?=_("回复")?><a href="javascript:void(0);" td-user-id="${to_id}" hidefocus="hidefocus" class="feed-cmt-list-user">${to}</a>
    {{/if}}
    <div class="feed-cmt-list-ext">
        {{if del_flag == "1"}}
        <a class="feed-cmt-del-handle" data-cmd="delReply" 
            data-cmt-id="${id}" data-to-id="${from_id}" 
            data-cmt-type="${type}" data-to-text="${from}" 
            href="javascript:void(0);" hidefocus="hidefocus">
            <?=_(" 删除")?>
        </a>
        {{/if}}
        {{if reply_flag == "1"}}
        <a class="feed-cmt-reply-handle" data-cmd="replyComment" 
            data-cmt-id="${type ? comment_id : id}" data-to-id="${from_id}" 
            data-cmt-type="${type}" data-to-text="${from}" 
            href="javascript:void(0);" hidefocus="hidefocus">
            <?=_("回复")?>
        </a>  
        {{/if}}
        <span class="feed-cmt-list-time" title="${send_time}" data-timestamp="${send_timestamp}">${formatTime(send_timestamp)} </span>
    </div>
    <div class="feed-cmt-content">{{html content}}</div>
    <div class="feed-cmt-attachments">
        ${attachments}
    </div>
</li>
</script>

<script src="<?=MYOA_JS_SERVER?>/static/js/gesture/GM.js"></script>
<script>
(function(win, $){
    var tDiaryDetail = {
        init: function(){
            this.bindEvent();
            this.replyFeed(<?=$dia_id?>);
            this.getReaders(<?=$dia_id?>);
        }, 
        Router: {
            DelFeed: 'op_diary.php',
            ShareFeed: 'op_diary.php',
            GetReply: 'get_diary_comment.php',
            DelReply: 'op_diary_comment.php',
            GetSharelist: 'get_diary_share.php',
            SubmitComment: 'op_diary_comment.php',
            GetReaders: 'get_diary_readers.php',
            GetTOP: 'get_diary_top.php'
        },
        bindEvent: function(){  
            var self = this;
            $('#diary-detail-container').on('click.diarycmd', '[data-cmd]', function(){
                var $this = $(this), 
                $panel = $this.parents('[data-diary-id]').first(),
                diaryId = $panel.attr('data-diary-id'),
                cmd = $this.attr('data-cmd');
                
                switch(cmd){                    
                    case 'replyComment':
                        self.replyComment(diaryId, $this.attr('data-cmt-id'), $this.attr('data-to-id'), $this.attr('data-to-text'), 'sub')
                        break;
                    case 'replySubmit':
                        self.submitReply(diaryId);
                        break;
                    case 'delReply':
                        self.delReply(diaryId, $this.attr('data-cmt-id'), $this.attr('data-cmt-type'));
                        break;
                    case 'share':
                        self.share(diaryId);
                        break;
                    case 'del':
                        self.deleteFeed(diaryId);
                        break;
                    case 'addtop':
                        self.addTop(diaryId);
                        break;
                    case 'deltop':
                        self.delTop(diaryId);
                        break; 
                    default:
                        break;
                }
            });
            
             $('#share-plus').click(function(){
                var module_id = 'diary', 
                to_id = "sharename", 
                to_name = "sharetext", 
                manage_flag, 
                form_name = "shareform";
            
                window.org_select_callbacks = window.org_select_callbacks || {};
                
                window.org_select_callbacks.add = function(item_id, item_name){
                    self.sharetags.add({ value: item_id, text: item_name });                    
                };
                window.org_select_callbacks.remove = function(item_id, item_name){
                    self.sharetags.remove(item_id);                    
                };                
                window.org_select_callbacks.clear = function(){                    
                                  
                };
                
                SelectUser('9',module_id, to_id, to_name, manage_flag, form_name);
                return false;
            });
                   
            $('#share-trash').click(function(){
                self.sharetags.clear();
            });
            
            $('#shareSubmit').click($.proxy(this.shareSubmit, this));
            
            
            $(".advcomment").change(function (){
                var ischecked = $(this).prop('checked');
                if($("#diary-detail-container").width()==720){
                    var areawidth=$(".feed-submit-cmt-context").width();
                }
                else{
                    console.log(0);
                }
                if(ischecked){
                    var $textarea = $(".feed-submit-cmt-context")[0];
                    $textarea.value = $textarea.value.replace(/\n/ig, '</br>');
                    replaceEditor($textarea,{
                        width:areawidth,
                        height:85,
                        toolbar: 'Feedback'
                    });
                    $(this).parents('.feed-ext-comment-sms-advcomment').hide();
                }
            }) 
        },
        
                             
        replyFeed: function(id){
            var $list = $('#diary-detail-container .feed-ext-list');
            this.getReply(id, $list);
        },
        getReply: function(id, $list){
            var self = this;
            $.get(this.Router.GetReply, { DIA_ID: id }, function(d){ 
                $('#diary-detail-container').find('.feed-ext-comment-sms-op').html(d.sms_op);
                if(d && typeof d.comment_data == 'object'){
                    $list.html(self.replyRender(d.comment_data)); 
                    $.attachmenu();
                }
            });
        },
        getReaders: function(id){
            var self = this;
            $('.feed-ext-detailreaders').html("");
            $.get(self.Router.GetReaders, { DIA_ID: id }, function(d){ 
                if(d == ""){
                     $('.feed-ext-detailreaders').html('<span>暂无人员浏览</span>');
                }
                else{
                    $('.feed-ext-detailreaders').html("");
                    $.each(d, function(){
                        $('.feed-ext-detailreaders').append('<span class="readersname">'+this.text+'</span>');
                    });
                    $("<span>已浏览</span>").appendTo($('.feed-ext-detailreaders'));
                }
            });
        },   
        delReply: function(dia_id, cmt_id, type){
            var self = this;
            this.confirm('<?=_("删除后无法恢复，是否删除该评论?")?>', function(){
                $.get(self.Router.DelReply, { id: cmt_id, op: 'del', 'comment-type': type }, function(flag){
                    if(flag == 'ok'){
                        var selector = type != 'sub' 
                            ? '.feed-ext-list .feed-cmt-list-item[data-cmt-id="' + cmt_id + '"]' 
                            : '.feed-ext-list .feed-cmt-list-item.sub[data-cmt-id="' + cmt_id + '"]';
                        self.notify('<?=_("删除成功。")?>');
                        
                        $('#diary-detail-container').find(selector).hide();
                        if( type != 'sub'){
                            self.setDiaryCounter(dia_id, -1);
                            $('#diary-detail-container').find('[data-comment-to-id="' + cmt_id + '"]').hide();
                        }
                    }else{
                        self.notify('<?=_("删除失败。")?>');
                    }
                });
            });
        },
        
        deleteFeed: function(id){
            var self = this;
            this.confirm('<?=_("删除后无法恢复，是否删除该日志?")?>', function(){
                $.get(self.Router.DelFeed, { diary_id: id, op: 'del' }, function(flag){
                    if(flag == 'ok'){
                        self.notify('<?=_("删除成功。")?>');
                        window.close();
                        //$('#diary-feed-' + id).remove();
                    }else{
                        self.notify('<?=_("删除失败。")?>');
                    }
                });
            });
        },
        
        addTop: function(id){
            var self = this;
            $.get(self.Router.GetTOP, { diary_id: id, op: 'add' }, function(flag){
                if(flag == 'ok'){
                    self.notify("置顶成功");
                    window.location.reload();
                }else{
                    self.notify("置顶失败");
                }
            });
            
        },
        
        delTop: function(id){
            var self = this;
            $.get(self.Router.GetTOP, { diary_id: id, op: 'del' }, function(flag){
                if(flag == 'ok'){
                    self.notify("取消置顶成功");
                    window.location.reload();
                }else{
                    self.notify("取消置顶失败");
                }
            });
            
        },
        
        submitReply: function(id){
            var self = this,
            $context = $('#diary-detail-container'),
            $form = $context.find('form[name="feed-comment-form"]'),
            data = $form.serializeArray(),
            $button = $form.find('.feed-submit-cmt-btn');
            isHtml = $context.find("[name='advcomment']").prop('checked'),
            context = isHtml 
                        ? getEditorHtml("feed-submit-cmt-context-"+id) 
                        : jQuery.trim($form.find('textarea.feed-submit-cmt-context').val()).replace(/\n/ig, "</br>");
            data.push({
                name: 'op',
                value: 'add'
            });              
            if( context == ''){
                return;
            }
            data.push({
                name: 'TD_HTML_EDITOR_feed-submit-cmt-context', 
                value: context
            });
            
            $button.button('loading');
            
            $.post(self.Router.SubmitComment, data, function(ret){
                if(ret && ret.flag == 'ok'){
                    self.setDiaryCounter(id, 1);
                    self.replyFeed(id);
                }else{
                    self.notify('<?=_("评论失败。")?>');
                }
                $button.button('reset');
                try{
                    isHtml && setHtml("feed-submit-cmt-context-"+id);
                }catch(e){}
                $form.find('textarea.feed-submit-cmt-context').val('');
            });
                
        },
        replyComment: function(diaryId, cmtId, toId, toText, cmtType){
            var $context = $('#diary-detail-container'),
                $cmtForm = $context.find('form[name="feed-comment-form"]'),
                $cmtTo = $cmtForm.find('.feed-ext-add-comment-to'),
                $inputTo = $cmtForm.find('input[name="comment-to"]'),
                $cmtId = $cmtForm.find('input[name="comment-id"]'),
                $cmtType = $cmtForm.find('input[name="comment-type"]'),
                $diaryId = $cmtForm.find('input[name="diary-id"]'),
                api = $cmtTo.data('tags') || $cmtTo.tags().data('tags');
                
            $inputTo.val(toId);    
            $cmtId.val(cmtId);    
            $cmtType.val(cmtType);    
            $diaryId.val(diaryId);    
            
            api.clear();
            api.options.callbacks.remove = function(){
                $inputTo.val('');
                $cmtId.val('');
                $cmtType.val('');    
            };
            api.add({
                value: toId,
                text: '<?=_("回复：")?>' + toText
            });
        },
        setDiaryCounter: function(dia_id, n){
            var $replyCounter = $('#diary-detail-container').find('[data-cmt-count]'),
            counter = parseInt( $replyCounter.attr('data-cmt-count') ) + n;                            
            $replyCounter.attr('data-cmt-count', counter);
            $replyCounter.html('<?=_("评论(")?>' + counter + '<?=_(")")?>');
        },
        
        share: function(id){
            $('#shareModal').modal('show');
            var self = this;
            self.sharetags = $('#share-tags').data('tags') || $('#share-tags').tags().data('tags');
            $('#share-tags').attr('data-diary-id', id);
            self.sharetags.clear();
            $.get(self.Router.GetSharelist, { diary_id: id }, function(d){ 
                d = d || [];
                var username, usertext;
                $.each(d, function(){
                    self.sharetags.add(this);
                    username += this.value + ',';
                    usertext += this.text + ',';
                });
                $('#sharename').val(username);
                $('#sharetext').val(usertext);
            });        
        },
        shareSubmit: function(){
            var self = this, 
            data = $('#share-tags').data('tags').serialize();
            data['diary_id'] = $('#share-tags').attr('data-diary-id');
            data['op'] = 'share';
            $.get(self.Router.ShareFeed, data, function(flag){
                if(flag == 'ok'){
                    self.notify('<?=_("共享成功。")?>');                    
                }else{
                    self.notify('<?=_("共享失败。")?>');
                }
            });
        },
        replyRender: function(c){
            var tmpl = $('#cmtTmpl');
            return tmpl.tmpl(c);        
        },
        notify: function(c){
            alert(c);
        },
        confirm: function(txt, func){
            confirm(txt) && $.isFunction(func) && func();
        }
    };
    
    $(function(){
        tDiaryDetail.init();
        
        GM.init({ 
            normal_actions: {    
                L: {
                    name : "<?=_('上一篇日志')?>",
                    action : function(){ 
                        var $target = $('#footer .previous-page a');
                        if($target.size()){
                            win.location = $target.attr('href');
                        }
                    }
                },
                R: {
                    name : "<?=_('下一篇日志')?>",
                    action : function(){ 
                        var $target = $('#footer .next-page a');
                        if($target.size()){
                            win.location = $target.attr('href');
                        }
                    }
                }
            }     
        });
        
    });
    
})(window, jQuery)
</script>
</html>

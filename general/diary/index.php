<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_all.php");
include_once("get_diary_data.func.php");
include_once("check_priv.inc.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("工作日志");
include_once("inc/header.inc.php");
if($USER_ID!="")
{
    $query  = "SELECT USER_NAME FROM USER WHERE USER_ID='$USER_ID'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $USER_NAME = $ROW["USER_NAME"];
    }
    else
    {
        $USER_NAME = $USER_ID;
    }
}
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/general/diary/css/diary.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.ux.calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.ux.calendar.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="/general/diary/js/diaryscrolltop.js"></script>
<script>
function export_excel()
{
    document.searchForm.action = "export2.php";
    document.searchForm.submit();
}    
//如果从OA精灵打开，则最大化窗口
if(window.external && typeof window.external.OA_SMS != 'undefined')
{        
    var h = Math.min(800, screen.availHeight - 180),
        w = Math.min(1280, screen.availWidth - 180);
    window.external.OA_SMS(w, h, "SET_SIZE");
}
var G_obj_diaryinfo = {
    serverTimestamp: <?= time() * 1000?>,
    isMain: <?= $IS_MAIN == 1 ? 1 : 0?>,
    fromUser: <? echo "'".$USER_ID."'";?>,
    fromUserName: <? echo "'".$USER_NAME."'";?>    
};
$(document).ready(function(){
    if(jQuery && jQuery.fn.tooltip){
        jQuery('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });
    }
    $(".diary-users-toggle").click(function(){
        if($('.diary-viewpart').is(':visible')){
            $(".diary-viewpart").hide();
            $(".diary-viewall").show();
            $(this).html("收起");            
        }
        else{
            $(".diary-viewpart").show();
            $(".diary-viewall").hide();
            $(this).html("展开");  
        }
    });
    //lijun add the delegation click handler for the imgs of diary module
    $('#diarylist').delegate('.feed-txt-summary img','click',function() {

        var aid = $(this).attr('title');
        var aname;
        var src = $(this).attr('src').replace(/NAME=(.+)/g,function() {
            aname = arguments[1];
        });
        //window.open('/inc/image_view.php?ATTACHMENT_ID='+aid+'&ATTACHMENT_NAME='+aname+'&MODULE=diary');
        window.open('/inc/image_view.php?MEDIA_URL='+encodeURIComponent(this.src));
    });
})
function sms_back()
{
    var to_uid  = document.getElementById("nodiary_user_id").value;
    var to_name = document.getElementById("nodiary_user_name").value;
    var top     = (screen.availHeight-340)/2;
    var left    = (screen.availWidth-500)/2;
    window.open("/general/status_bar/sms_back.php?TO_UID="+ to_uid + "&TO_NAME=" + to_name + "&CONTENT=请注意提交工作日志",'','height=340,width=500,,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,top='+top+',left='+left+',resizable=yes');  
}
</script>
<style>
html,body{
    overflow:auto;
    -webkit-overflow-scrolling: touch;
}
@media only screen 
and (min-device-width : 768px) 
and (max-device-width : 1024px)  {     
    html,body{
        height: 100%;
    }
}
</style>
</head>

<div id="pageloading" style="display: none;"><?=_("正在加载...")?></div>
<div id="submiting" style="display: none;"><?=_("正在提交...")?></div>
<div id="shareModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="shareModal" aria-hidden="true">
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

<div class="wrapper">
    <div class="container">
        <div class="holder-40"></div>
        <div class="row">            
            <div class="span9">
                <div class="row">
                    <div class="span3">
                         <button class="btn btn-info btn-large" type="button" onclick="location='new/?FROM_URL=diary'"><?=_("写日志")?></button>
                    </div>
                    <div id="diary-type-switcher" class="span6">
                        <div class="btn-group pull-right">
                             <button data-diary-type="all" class="btn" type="button"><?=_("看所有人的")?></button> 
                             <button data-diary-type="mine" class="btn active btn-primary" type="button"><?=_("看自己的")?></button> 
                             <button data-diary-type="others" class="btn" type="button"><?=_("看其他人的")?></button> 
                        </div>
                    </div>
                </div>
                <div id="diary-user-tip">
                    <div class="diary-dept-time">
                    </div>
                    <div class="diary-users">
                        <span class="diary-viewpart" ></span>
                        <span class="diary-viewall"></span><span id="count"></span><?=_("人无动态")?>
                    </div>
                    <span class="diary-msg">
                        <a class="diary-users-toggle" href="javascript:void(0)"></a>
                        <a class="diary-send-msg" href="javascript:sms_back();"><?=_("发微讯")?></a>
                    </span>
                    <input type="hidden" id="nodiary_user_id">
                    <input type="hidden" id="nodiary_user_name">
                </div>
                <div id="diary-empty-tip">
                    <?=_("没有符合条件的日志，")?><a href="new/?FROM_URL=diary" ><?=_("新建一篇")?></a>
                </div>                
                <div id="diarylist" class="feedlist">
                </div>                
                <div id="diary-pagination" class="pagination pull-right">                   
                </div>
            </div>
            <div class="span3">
                <div class="diary-main-panel">
                    <h3 class="diary-user-name"><?=$_SESSION["LOGIN_USER_NAME"] ?></h3>
                    <ul class="diary-types-box clearfix">
                        <li data-type="all">
                            <div class="diary-types-counter"><? echo get_diary_count($_SESSION["LOGIN_USER_ID"],1,'','','')+get_diary_count($_SESSION["LOGIN_USER_ID"],2,$WHERE_STRS,1,'')?></div>
                            <div class="diary-types-title"><?=_("全部日志")?></div>
                        </li>
                        <li data-type="mine">
                            <div class="diary-types-counter"><? echo get_diary_count($_SESSION["LOGIN_USER_ID"],1,'',1,'');?></div>
                            <div class="diary-types-title"><?=_("我的日志")?></div>
                        </li>
                        <li data-type="others">
                            <div class="diary-types-counter"><? echo get_diary_count($_SESSION["LOGIN_USER_ID"],2,$WHERE_STRS,1,'');?></div>
                            <div class="diary-types-title"><?=_("他人日志")?></div>
                        </li>
                    </ul>
                    <div class="diary-calendar-wrapper">
                        <div id="diary-calendar" class="dot-loading">                       
                        </div>
                        <div id="diary-date-result"></div>
                    </div>  
                    <div id="diary-search-box">
                        <form class="form-search" name="searchForm">
                            <i class="icon-search"></i>
                            <input id="diary-search-query" class="diary-search-query" type="text" hidefocus="hidefocus"/> 
                            <a href="javascript:void(0)" class="adv-search-handler" data-placement="bottom" data-toggle="tooltip" title="<?=_('高级搜索')?>"><i class="icon-chevron-down"></i></a>
                            <div id="diary-adv-search-box" class="diary-adv-search-box">
                                <div class="control-group">
                                    <div class="controls pull-right">
                                        <a class="date-quicklink" data-cmd="recent3day"  href="javascript:void(0)" ><?=_("三天内")?></a>
                                        <a class="date-quicklink" data-cmd="recent1week" href="javascript:void(0)" ><?=_("本周")?></a>
                                        <a class="date-quicklink" data-cmd="recent1month" href="javascript:void(0)" ><?=_("本月")?></a>
                                    </div>
                                    <label class="control-label" for="input01"><b><?=_("日期")?></b></label>
                                    <div class="controls">
                                        <input type="text" id="startdate" name="startdate" placeholder="<?=_("起始日期")?>" class="input-mini " onclick="WdatePicker({dateFmt:'yyyy-M-d'})" >
                                        <?=_("至")?>
                                        <input type="text" id="enddate" name="enddate" placeholder="<?=_("结束日期")?>" class="input-mini " onclick="WdatePicker({dateFmt:'yyyy-M-d'})" >
                                    </div>
                                </div>
                                    
                                <div class="control-group">
                                    <label class="control-label"><b><?=_("范围")?></b></label>
                                    <div class="controls">
                                        <select id="diarytype" class="input-medium " name="diarytype">
                                            <option value="all"><?=_("所有的")?></option>
                                            <option value="mine"><?=_("我自己的")?></option>
                                            <option value="shared"><?=_("共享给我的")?></option>
                                            <option value="permission"><?=_("有权限查看的")?></option>
                                        </select>
                                    </div>

                                </div> 
                                <div id="dept-group" class="control-group">
                                      <!-- Text input--> 
                                    <div class="dept-control pull-right"> 
                                        <button type="button" id="dept-plus" class="btn btn-mini"><i class="icon-plus"></i></button>
                                        <button type="button" id="dept-trash" class="btn btn-mini"><i class="icon-trash"></i></button> 
                                    </div>
                                    <label class="control-label" ><b><?=_("部门")?></b></label>
                                    <div class="controls">
                                        <div id="dept-tags">  </div>
                                        <input type="hidden" id="deptname" name="deptname" />
                                        <input type="hidden" id="depttext" name="depttext" />
                                    </div>
                                </div>

                                <div id="role-group" class="control-group">
                                      <!-- Text input-->
                                    <div class="role-control pull-right"> 
                                        <button type="button" id="role-plus" class="btn btn-mini"><i class="icon-plus"></i></button>
                                        <button type="button" id="role-trash" class="btn btn-mini"><i class="icon-trash"></i></button> 
                                    </div>
                                    <label class="control-label" ><b><?=_("角色")?></b></label>
                                    <div class="controls">
                                        <div id="role-tags">  </div>
                                        <input type="hidden" id="rolename" name="rolename" />
                                        <input type="hidden" id="roletext" name="roletext" />
                                    </div>
                                </div>
                                <div id="user-group" class="control-group">
                                      <!-- Text input-->
                                    <div class="user-control pull-right"> 
                                        <button type="button" id="user-plus" class="btn btn-mini"><i class="icon-plus"></i></button>
                                        <button type="button" id="user-trash" class="btn btn-mini"><i class="icon-trash"></i></button> 
                                    </div>
                                    <label class="control-label" ><b><?=_("人员")?></b></label>
                                    <div class="controls">
                                        <div id="user-tags">  </div>
                                        <input type="hidden" id="username" name="username" />
                                        <input type="hidden" id="usertext" name="usertext" />
                                    </div>
                                </div>

                                <div class="control-group">
                                      <!-- Select Basic -->
                                    <label class="control-label"><b><?=_("日志表")?></b></label>
                                    <div class="controls">
                                        <select class="input-medium " id="diarydb" name="diarydb">
                                            <option value=""><?=_("当前日志")?></option>
                                            <? 
                                                $query = "show tables from ".TD::$_arr_db_master['db_archive']." like 'DIARY_COMMENT_REPLY_%'";
                                                $cursor= exequery(TD::conn(),$query);
                                                while($ROW=mysql_fetch_array($cursor))
                                                {
                                                    $TABLE_NAME=substr($ROW[0], 20);
                                            ?>                                                  
                                                    <option value="_<?=$TABLE_NAME?>"><?=$TABLE_NAME?></option>                                           
                                            <?  } ?>                                              
                                        </select>
                                    </div>
                                </div>    
                                
                                <div class="control-group">
                                    <button class="btn btn-info" type="button" id="adv-search-btn"><i class="icon-search icon-white"></i><?=_("查询")?></button>&nbsp;&nbsp;                                    
                                    <button class="btn btn-info" type="button" onClick="export_excel();"><?=_("导出")?></button>
                                </div>    
                            </div>
                        </form>
                    </div>
                </div>                                       
                <?
                $diary_array=get_new_comment($_SESSION["LOGIN_USER_ID"]);
                if(count($diary_array)>0){    
                ?>
                <dl id="diary-cmt-list" class="diary-cmt-list">
                    <?
                    foreach($diary_array as $key=> $val)
                    {
                    ?>
                    <dt>
                        <?=$val["commenter_name"]?> <i><?=_("回复了")?></i>
                    </dt>
                    <dd>
                        <a target="_blank" href="<?=$val["url"]?>"><?=_("《").$val["subject"]._("》");?></a>
                    </dd>
                    <?
                     }
                    ?>                
                </dl>
                <?
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div style="display:none">
<?
$editor = new Editor('') ;
$editor->Create() ;
?>
</div>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/template/jquery.tmpl.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="js/index.js"></script>
</body>

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
            <?=_("删除")?>
        </a>
        {{/if}}
        {{if reply_flag == "1"}}
        <a class="feed-cmt-reply-handle" data-cmd="replyComment" 
            data-cmt-id="${ type ? comment_id : id}" data-to-id="${from_id}" 
            data-cmt-type="${type}" data-to-text="${from}" 
            href="javascript:void(0);" hidefocus="hidefocus">
            <?=_("回复")?>
        </a>  
        {{/if}}
        <span class="feed-cmt-list-time" title="${send_time}" data-timestamp="${send_timestamp}">{{html formatTime(send_timestamp)}} </span>
    </div>
    <div class="feed-cmt-content">{{html content}}</div>
    <div class="feed-cmt-attachments">
        ${attachments}
    </div>
</li>
</script>

<script id="feedTmpl" type="text/x-jquery-tmpl"> 
<div class="feed  feed-text" id="diary-feed-${dia_id}" data-diary-id="${dia_id}"> 
    <div class="feed-avatar"> 
        <div class="blog-info"> 
            <a target="_blank" href="../ipanel/user/user_info.php?USER_ID=${author_user_id}&WINDOW=1" data-avatar="${author_photo}"  td-user-id="${author_user_id}" 
                title="${author_user_name}"  class="blog-avatar"  hidefocus="hidefocus"> 
               <img src="${author_photo}" />
               ${author_user_name}
            </a> 
        </div> 
    </div> 
    <div class="feed-content-holder pop"> 
        <div class="ui-poptip-arrow ui-poptip-arrow-10"> 
            <em>◆</em> 
            <span>◆</span> 
        </div>       
        <div class="feed-container-top"> 
        </div> 
        <div class="pop-content clearfix"> 
            <div class="feed-hd">
                <div class="feed-time" title="${dia_date}" data-timestamp="${dia_time}">
                    {{html formatTime(dia_time)}}
                </div>
                <div class="feed-basic"> 
                    <a target="_blank" href="../ipanel/user/user_info.php?USER_ID=${author_user_id}&WINDOW=1" class="feed-user" hidefocus='hidefocus'> 
                       ${author_user_name} 
                    </a>  
                    <span class="feed-dept" title="${author_dept_name}"> 
                       ${author_short_dept_name} 
                    </span>   
                    <span class="feed-priv"> 
                       ${author_priv_name} 
                    </span> 
                    <span class="feed-type">${dia_type}</span>                   
                </div> 
            </div> 
            <div class="feed-bd"> 
                <h4 class="feed-title"> 
                    <a target="_blank" href="${more_url}">${subject}</a>
                </h4>  
                <div class="feed-ct"> 
                    <div class="feed-txt-full rich-content"> 
                        <div class="feed-txt-summary">                                         
                            {{html content_all}} 
                        </div> 
                        <div class="feed-txt-more"> 
                        </div> 
                    </div> 
                </div>
                <div class="feed-attachments">
                    {{html attachments}}
                </div>   
                <div class="feed-act"> 
                    <!--<a href="#"><?=_("赞")?></a>-->
                    <a href="javascript:void(0)" data-cmd="readers" hidefocus="hidefocus"><?=_("浏览")?></a>
                    {{if top_flag == "1"}} 
                    <a href="javascript:void(0)" data-cmd="deltop"  hidefocus="hidefocus"><?=_("取消置顶")?></a> 
                    {{else}}
                    <a href="javascript:void(0)" data-cmd="addtop"  hidefocus="hidefocus"><?=_("置顶")?></a>
                     {{/if}} 
                    {{if del_flag == "1"}} 
                        <a href="javascript:void(0)" data-cmd="del" hidefocus="hidefocus"><?=_("删除")?></a> 
                    {{/if}} 
                    {{if edit_flag == "1"}} 
                        <a href="new/edit.php?dia_id=${dia_id}&IS_MAIN=1" data-cmd="edit" hidefocus="hidefocus"><?=_("编辑")?></a> 
                    {{/if}} 
                    {{if share_flag == "1"}} 
                        <a href="javascript:void(0)" data-cmd="share" hidefocus="hidefocus" ><?=_("共享")?></a> 
                    {{/if}} 
                    {{if reply_flag == "1"}} 
                        <a href="javascript:void(0)" data-cmd="reply" hidefocus="hidefocus" data-cmt-count="${comment_count}" ><?=_("评论")?>(${comment_count})</a> 
                    {{/if}} 
                </div>             
            </div> 
       </div> 
       <div class="feed-ft J_FeedFooter ui-poptip-container" style="display:none"> 
           <div class="ui-poptip-arrow ui-poptip-arrow-11"> 
               <em>◆</em> 
               <span>◆</span> 
           </div> 
       </div> 
       <div class="feed-ext-body"> 
           <div class="feed-ext-add-comment"> 
               <form target="" action="" name="feed-comment-form"> 
                   <div class="feed-ext-add-comment-to"></div> 
                   <textarea name="TD_HTML_EDITOR_feed-submit-cmt-context-${dia_id}" class="feed-submit-cmt-context"></textarea> 
                   <button type="button" data-cmd="replySubmit" data-loading-text="<?=_("提交中...")?>" class="btn btn-primary feed-submit-cmt-btn">提交</button> 
                   <input type="hidden" name="comment-to" value="" /> 
                   <input type="hidden" name="comment-id" value="" /> 
                   <input type="hidden" name="comment-type" value="" /> 
                   <input type="hidden" name="diary-id" value="${dia_id}" /> 
                   <div class="feed-ext-comment-sms-op"></div>
                   <div class="feed-ext-comment-sms-advcomment">
                       <label>
                           <input type="checkbox" name="advcomment" class="advcomment" /><?=_("高级评论")?>
                       </label>
                   </div>
               </form> 
           </div> 
           <ul class="feed-ext-list"> 
           </ul> 
       </div> 
       <div class="feed-ext-body-readers"> 
           <div class="feed-ext-readers">
           </div>
       </div>
       <div class="feed-container-bottom"> 
       </div> 
       <div class="post-flag-panel"> 
       </div> 
   </div> 
</div> 
</script>
</html>
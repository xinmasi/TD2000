<script>
//---------zfc--------------
//项目快讯more
var news = false;
function open_news_more(PROJ_ID)
{
     if(news)
        news.close();
	 URL = "news/index.php?PROJ_ID="+PROJ_ID;
	 //window.open(URL,"_blank","project_detail_"+PROJ_ID,"height="+600+",width="+400+",status=1,toolbar=no,menubar=yes,location=no,scrollbars=yes,top="+100+",left="+100+",resizable=yes");
     //news = window.open (URL, "_blank", "height=650, width=1100, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=50,left=150");
	news = window.open(URL,"","status=0,toolbar=no,menubar=no,width=1100,height=650,location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}

var newsle = false;

function open_news_more_single(PROJ_ID,id)
{   
    if(newsle)
        newsle.close();
	 URL = "news/news.php?PROJ_ID="+PROJ_ID+"&id="+id;
	 //window.open(URL,"_blank","project_detail_"+PROJ_ID,"height="+600+",width="+400+",status=1,toolbar=no,menubar=yes,location=no,scrollbars=yes,top="+100+",left="+100+",resizable=yes");
     //newsle = window.open (URL, "_blank", "height=500, width=700, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=50,left=350");
	 newsle = window.open(URL,"","status=0,toolbar=no,menubar=no,width=700,height=500,location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}

//热门讨论
var comment = false;
function open_comment(PROJ_ID,MSG_ID)
{   
    if(comment)
        comment.close();
	 URL = "../../proj/forum/comment.php?PROJ_ID="+PROJ_ID+"&MSG_ID="+MSG_ID;
	 //window.open(URL,"_blank","project_detail_"+PROJ_ID,"height="+600+",width="+400+",status=1,toolbar=no,menubar=yes,location=no,scrollbars=yes,top="+100+",left="+100+",resizable=yes");
     //comment = window.open (URL, "_blank", "height=550, width=800, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=120,left=100");
	 comment = window.open(URL,"","status=0,toolbar=no,menubar=no,width=800,height=550,location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
//热门讨论more
var comment_more = false;
function open_comment_more(PROJ_ID)
{
    if(comment_more)
        comment_more.close();
	 URL = "../../proj/forum/index.php?PROJ_ID="+PROJ_ID;
	 //window.open(URL,"_blank","project_detail_"+PROJ_ID,"height="+600+",width="+400+",status=1,toolbar=no,menubar=yes,location=no,scrollbars=yes,top="+100+",left="+100+",resizable=yes");
     //comment_more = window.open (URL, "_blank", "height=550, width=800, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=120,left=100");
	 comment_more = window.open(URL,"","status=0,toolbar=no,menubar=no,width=800,height=550,location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
//项目批注
var proj_c = false;
function open_project_comment(PROJ_ID)
{   
    if(proj_c)
        proj_c.close();
	 URL = "../../proj/comment/index.php?PROJ_ID="+PROJ_ID;
	 //window.open(URL,"_blank","project_detail_"+PROJ_ID,"height="+600+",width="+400+",status=1,toolbar=no,menubar=yes,location=no,scrollbars=yes,top="+100+",left="+100+",resizable=yes");
    // proj_c = window.open (URL, "_blank", "height=550, width=800, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=120,left=100");
	proj_c = window.open(URL,"","status=0,toolbar=no,menubar=no,width=800,height=550,location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
//项目批注more
var proj_c_m = false;
function open_project_comment_more(PROJ_ID)
{   
    if(proj_c_m)
        proj_c_m.close();
	 URL = "../../proj/comment/index.php?PROJ_ID="+PROJ_ID;
     //proj_c_m = window.open (URL, "_blank", "height=550, width=800, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=120,left=100");
	 proj_c_m = window.open(URL,"","status=0,toolbar=no,menubar=no,width=800,height=550,location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
//问题追踪
var qu = false;
function open_query(BUG_ID)
{
    if(qu)
        qu.close();
	 URL = "bug_detail.php?BUG_ID="+BUG_ID;
     //qu = window.open (URL, "_blank", "height=550, width=800, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=120,left=100");
	 qu = window.open(URL,"","status=0,toolbar=no,menubar=no,width=800,height=550,location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
//问题追踪more
var qu_m = false;
function open_query_more(PROJ_ID)
{
    if(qu_m)
        qu_m.close();
	 URL = "../../proj/bug/index.php?PROJ_ID="+PROJ_ID;
     //qu_m = window.open (URL, "_blank", "height=550, width=800, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=120,left=100");
	 qu_m = window.open(URL,"","status=0,toolbar=no,menubar=no,width=800,height=550,location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
//分配任务
var new_task = false;
function open_new_task(PROJ_ID)
{
    if(new_task)
        new_task.close();
    URL = "/general/project/proj/new/task/index.php?PROJ_ID="+PROJ_ID;
    //new_task = window.open (URL, "_blank", "height=550, width=950, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no,top=120,left=100");
	new_task = window.open(URL,"","status=0,toolbar=no,menubar=no,width=950,height=550,location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
</script>
<div class="rightbar_bottom_l boder-one"><!--项目快讯-->
    <div>
        <div class="rightbar_bottom_lp"><span class="point-green"></span><?=_("项目快讯")?></div>
        <div class="rightbar_more"><a href="#" onClick="open_news_more(<?=$i_proj_id?>)"><img src="<?=MYOA_STATIC_SERVER?>/static/modules/project/img/xmgl_12.jpg">更多</a>
        </div>
        <div class="clear"></div>
    </div>
    <div class="rightbar_bottom_ld">
        <ul>
<?php
include_once("inc/auth.inc.php");

    $query="select id,uid,content from proj_news where proj_id='$i_proj_id' order by id desc limit 0,4";
    $cursor=exequery(TD::conn(),$query);
    while ($row = mysql_fetch_assoc($cursor))
    {
        $content = $row['content'];
        $name = $row['uid'];
        $id = $row['id'];
        $user_name = GetUserNameByUid($name);
        $user_name = rtrim($user_name,",");
        $con_name=strip_tags($content);
        echo "<li><div><span>·</span><a href='#' title='$con_name' onClick='open_news_more_single($i_proj_id,$id)' \">".$user_name."：".$content."</a></div></li>";
 }
?>
            </ul>
        </div>
    </div>
    <div class="rightbar_bottom_l boder-two"><!--热门讨论-->
        <div>        
            <div class="rightbar_bottom_lp"><span class="point-blue"></span><font color="#1ba19a"><?=_("热门讨论")?></font></div>
            <div class="rightbar_more" ><a href="#" onClick="javascript:open_comment_more('<?=$i_proj_id?>')"><img src="<?=MYOA_STATIC_SERVER?>/static/modules/project/img/xmgl_12.jpg">更多</a></div>
        </div>
        <div class="rightbar_bottom_ld">
            <ul>
            <?php
                $s_query_discussion = "select SUBJECT,MSG_ID from proj_forum where proj_id='{$i_proj_id}' order by SUBMIT_TIME desc limit 4";
                $res_cursor_discussion = exequery(TD::conn(), $s_query_discussion);
                while($a_discussion = mysql_fetch_array($res_cursor_discussion))
                {
                    $MSG_ID = $a_discussion[1];
                    echo "<li><div><span>·</span><a href='#' title='$a_discussion[0]' onclick=\"javascript:open_comment('$i_proj_id', '$MSG_ID')\">".$a_discussion[0]."</a></div></li>";
                }
            ?>
            </ul>       
        </div>
    </div>
    <div class="rightbar_bottom_l boder-three">
        <div>
            <div class="rightbar_bottom_lp"><span class="point-yellow"></span><font color="#cc9b22"><?=_("项目批注")?></font></div>
            <div class="rightbar_more"><a href="#" onClick="javascript:open_project_comment_more('<?=$i_proj_id?>')"><img src="<?=MYOA_STATIC_SERVER?>/static/modules/project/img/xmgl_12.jpg">更多</a></div>
        </div>
        <div class="rightbar_bottom_ld">
            <ul>
            <?php
                $s_query_annotation = "select CONTENT from proj_comment where proj_id='{$i_proj_id}' order by WRITE_TIME desc limit 4 ";
                $res_cursor_annotation = exequery(TD::conn(), $s_query_annotation);
                while($a_annotation = mysql_fetch_array($res_cursor_annotation)) 
                {
                    echo "<li><div><span>·</span><a href='#' title='$a_annotation[0]' onclick=\"javascript:open_project_comment('$i_proj_id')\">".csubstr($a_annotation[0], 0, 30)."</a></div></li>";
      
                }
            ?>
            </ul>              
        </div>
    </div>
    <div class="rightbar_bottom_r">
        <div>
            <div class="rightbar_bottom_lp"><span class="point-red"></span><font color="#db4930"><?=_("问题跟踪")?></font></div>
            <div class="rightbar_more"><a href="#" onClick="javascript:open_query_more('<?=$i_proj_id?>')"><img src="<?=MYOA_STATIC_SERVER?>/static/modules/project/img/xmgl_12.jpg">更多</a></div>
        </div>
        <div class="rightbar_bottom_ld">
            <ul>
<?php
                $s_query_follow = "select BUG_ID,BUG_NAME from proj_bug where proj_id='{$i_proj_id}' order by DEAD_LINE desc limit 4";
                $res_cursor_follow = exequery(TD::conn(), $s_query_follow);
                while($a_follow = mysql_fetch_array($res_cursor_follow)) 
                {
                    $bug_id = $a_follow[0];
                    echo "<li><div><span>·</span><a href='#' title='$a_follow[1]' onclick=\"javascript:open_query('$bug_id')\" >".$a_follow[1]."</a></div></li>";
                }//
?>   
            </ul>
        </div>              
    </div>
    <div class="clear"></div>
<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="147";
$MODULE_DESC=_("图片新闻");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'news';
echo "<style type='text/css'> #flashnvanum {display:none;} </style>";
echo "<style type='text/css'>.module_body{position:relative}</style>";
include_once("inc/utility_file.php");
$MODULE_BODY_IMGS = '';
$swf_right_width=800*(100-$COL_WIDTH_LEFT)/100 *0.68; //右侧图片宽度
$swf_left_width=800*($COL_WIDTH_LEFT)/100 *0.32; //左侧图片宽度
if(find_id($MODULE_LEFT_STR,$MODULE_ID))
   $swf_width=$swf_left_width;
else
   $swf_width=$swf_right_width;
$swf_width=166;   
$swf_height=100;   //2014-8-27 sxm 将图片的高度改成定值
//$swf_height=$SHOW_COUNT*20;
$mylinks="\"../../news/show/read_news.php?NEWS_ID=1148|../../news/show/read_news.php?NEWS_ID=1148|../../news/show/read_news.php?NEWS_ID=1148|../../news/show/read_news.php?NEWS_ID=1148|../../news/show/read_news.php?NEWS_ID=1148|../../news/show/read_news.php?NEWS_ID=1148\"";

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'news_show\',\''._("图片新闻").'\',\'/general/news/show/\');">'._("全部").'&nbsp;</a>';
if($MODULE_TYPE != "")
   $MODULE_TYPE = '<a href="javascript:get_news(\'\');">'._("全部新闻").'</a>  <a href="javascript:get_news(\'no_read0\');">'._("未读新闻").'</a> '.$MODULE_TYPE;


$MODULE_BODY.= "<div style='margin-left: 170px;' ><ul>";
$query = "SELECT ATTACHMENT_ID,ATTACHMENT_NAME,NEWS_ID,SUBJECT,NEWS_TIME,CLICK_COUNT,FORMAT,TYPE_ID,ANONYMITY_YN,READERS from NEWS where 
PUBLISH='1' and TYPE_ID='pic_news' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID)) order by NEWS_TIME desc limit 0,$MAX_COUNT";
if($MODULE_SCROLL=="true")
   $MODULE_BODY.='<marquee   direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>';
$cursor= exequery(TD::conn(),$query);
$COUNT=0;
$img_array = array();
while($ROW=mysql_fetch_assoc($cursor))
{
   $NEWS_ID=$ROW["NEWS_ID"];
   $SUBJECT=strip_tags($ROW["SUBJECT"]);
   $NEWS_TIME=$ROW["NEWS_TIME"];
   $CLICK_COUNT=$ROW["CLICK_COUNT"];
   $FORMAT=$ROW["FORMAT"];
   $TYPE_ID=$ROW["TYPE_ID"];
   $NEWS_TIME=strtok($NEWS_TIME," ");
   $ANONYMITY_YN=$ROW["ANONYMITY_YN"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];   
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
   $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
   
      //........................
    
    foreach($ATTACHMENT_ID_ARRAY as $k => $attachment_id)
    {
        if($attachment_id=="")
            continue;   
      
        if(is_image($ATTACHMENT_NAME_ARRAY[$k]))
        {   
        	$COUNT++;
            $img_file = attach_real_path($ATTACHMENT_ID_ARRAY[$k], $ATTACHMENT_NAME_ARRAY[$k], "news");
            if(!file_exists($img_file))
               continue;
            
            $YM=substr($attachment_id,0,strpos($attachment_id,"_"));
            if($YM)
                $attachment_id=substr($attachment_id,strpos($attachment_id,"_")+1);
            $attachment_id_encoded=attach_id_encode($attachment_id,$ATTACHMENT_NAME_ARRAY[$k]);
     
            $img_array [] = array("NEWS_ID" => $NEWS_ID, "SUBJECT" => $SUBJECT, "YM" => $YM, "ID"=> $attachment_id_encoded, "NAME" => $ATTACHMENT_NAME_ARRAY[$k]);          
        } 
         else
             continue;
        if($COUNT > $SHOW_COUNT)
               break;    
    }
   //...............................................
   $ATTACHMENT_TYPE = substr($ATTACHMENT_NAME_ARRAY[0],strrpos($ATTACHMENT_NAME_ARRAY[0],"."));   
   $ATTACHMENT_ID=substr($ATTACHMENT_ID_ARRAY[0],strpos($ATTACHMENT_ID_ARRAY[0],"_")+1);
   $READERS=$ROW["READERS"];

   $query = "SELECT count(*) from NEWS_COMMENT where NEWS_ID='$NEWS_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $COMMENT_COUNT=$ROW[0];

   $SUBJECT_TITLE="";
   if(strlen($SUBJECT) > 50)
   {
      $SUBJECT_TITLE=$SUBJECT;
      $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
   }
   $SUBJECT=td_htmlspecialchars($SUBJECT);
   $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

   $TYPE_NAME=get_code_name($TYPE_ID,"NEWS");
   if($TYPE_NAME!="")
      $TSUBJECT='<a href="/general/news/show/news.php?TYPE='.$TYPE_ID.'">'._("【").$TYPE_NAME._("】").'</a>';
   else
      $TSUBJECT='';
   $MODULE_BODY.='<li>'.$TSUBJECT.'<a href="javascript:open_news_p('.$NEWS_ID.','.$FORMAT.');" title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a><label title=_("'._("点击次数：").$CLICK_COUNT.'")>('.$CLICK_COUNT.')</label>&nbsp;';

   if($ANONYMITY_YN!="2")
      $MODULE_BODY.='<a href="javascript:re_news_p('.$NEWS_ID.');" style="text-decoration:underline">'._("评论").'</a>('.$COMMENT_COUNT.')&nbsp;';

   $MODULE_BODY.= "(".$NEWS_TIME.")";
   if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
      $MODULE_BODY.='<img src="'.MYOA_STATIC_SERVER.'/static/images/email_new.gif" align="absMiddle">';

   $MODULE_BODY.='</li>';
 
}
if($MODULE_SCROLL=="true")
   $MODULE_BODY.='</marquee>';
$MODULE_BODY.='
<script type="text/javascript" src="'.MYOA_JS_SERVER.'/static/js/jquery-1.5.1/jquery.flashSlider.min.js'.$GZIP_POSTFIX.'"></script>';
if($COUNT==0)
   $MODULE_BODY.= "</div><div style='float:left;'><li>"._("暂无新闻")."</li></ul></div>";
   
else
{
    $MODULE_BODY .= "</div></div>";
    $MODULE_BODY_IMGS .= "<div id='news_slider_wraper' class='flash-slider'><div id='news_slider' class='".( count($img_array)== 1 ? 'single-slider':'multi-slider')."' style='float:left;padding:0px 0px;position:absolute;'><ul style='list-style-type: none;padding:0px 0px; margin: 0px;list-style:none;'>";
    foreach($img_array as $img) :
        $MODULE_BODY_IMGS.=  ' <li style="width:'.$swf_width.'px;height:100px;float:left;margin:0px;padding:0px 0px;list-style:none;background-image:none;">';
        $MODULE_BODY_IMGS.='<img style="width:'.$swf_width.'px;height:'.$swf_height.'px;padding:0px 0px;" src="/inc/attach.php?MODULE=news&YM='.$img["YM"].'&ATTACHMENT_ID='.$img["ID"].'&ATTACHMENT_NAME='.urlencode($img["NAME"]).'" alt='.$img["NAME"].'  />';
        $MODULE_BODY_IMGS.='</li>';
    endforeach ; 
    $MODULE_BODY_IMGS.='</ul></div></div>';  
}
$MODULE_BODY.='
<script type="text/javascript">	
//var $j=jQuery.noConflict();
jQuery("#news_slider.multi-slider").flashSlider(
{
speed:800,
pause:2000
});

function open_news_p(NEWS_ID,FORMAT)
{
    URL="/general/news/show/read_news.php?NEWS_ID="+NEWS_ID;
    myleft=(screen.availWidth-780)/2;
    mytop=100
    mywidth=780;
    myheight=500;
    if(FORMAT=="1")
    {
       myleft=0;
       mytop=0
       mywidth=screen.availWidth-10;
       myheight=screen.availHeight-40;
    }
    window.open(URL,"open_news_p"+NEWS_ID,"height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
   
function re_news_p(NEWS_ID)
{
    URL="/general/news/show/re_news.php?NEWS_ID="+NEWS_ID;
    myleft=(screen.availWidth-500)/2;
    window.open(URL,"re_news_p"+NEWS_ID,"height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
</script>'; 
}
$MODULE_BODY = $MODULE_BODY_IMGS.$MODULE_BODY;
?>





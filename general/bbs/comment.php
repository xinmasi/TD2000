<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
//URLD:\MYOA\webroot\general\bbs\comment.php
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";

$FONT_SIZE = get_font_size("FONT_DISCUSSION", 12);
$REPLAY_COMMENT_ID = $COMMENT_ID;

$HTML_PAGE_TITLE = _("讨论区");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function delete_comment(COMMENT_ID,USER_ID)
{
    msg="<?=_("确定要删除该文章吗?")?>";
    if(window.confirm(msg))
    {
        URL="delete.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID="+COMMENT_ID+"&USER_ID="+USER_ID;
        window.location=URL;
    }
}

function CheckForm()
{
    if(getEditorText('CONTENT').length==0 &&  getEditorHtml('CONTENT')=="")
    {
        alert("<?=_("内容不能为空!")?>");
        return false;
    }
    if(NAME_SELECT==2 && document.form1.NICK_NAME.value=="")
    {
        alert("<?=_("署名不能为空!")?>");
        return false;
    }
    return true;
}

function SubmitForm()
{
    if(CheckForm())
        document.form1.submit();
}
function upload_attach()
{
    if(CheckForm())
    {
        document.form1.OP.value="0";
        document.form1.submit();
    }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{

    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID=<?=$COMMENT_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}

function InsertImage(src)
{
    AddImage2Editor('CONTENT', src);
}
function lock_comment(COMMENT_ID,LOCK_FLAG)
{
    if(LOCK_FLAG == 0)
    {
        msg="<?=_("确定要解锁吗?")?>";
    }
    else
    {
        msg="<?=_("确定要锁定吗?")?>";
    }

    if(window.confirm(msg))
    {
        URL="lock.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID="+COMMENT_ID+"&LOCK_FLAG="+LOCK_FLAG;
        window.location=URL;
    }
}
function show_comment(COMMENT_ID)
{
    msg="<?=_("确定要屏蔽吗?")?>";
    if(window.confirm(msg))
    {
        URL="shielding.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID_ROOT=<?=$REPLAY_COMMENT_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID="+COMMENT_ID+"&SHOW_YN=1";
        window.location=URL;
    }
}
function recover_comment(COMMENT_ID)
{
    msg="<?=_("确定要恢复此被屏蔽文章吗?")?>";
    if(window.confirm(msg))
    {
        URL="shielding.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID_ROOT=<?=$REPLAY_COMMENT_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID="+COMMENT_ID+"&SHOW_YN=0";
        window.location=URL;
    }
}

function quote_comment(the_layer)
{
    var ContentHTML = eval("document.getElementById('span_" + the_layer + "')").innerHTML;
    var ContentHTML2 = eval("document.getElementById('content_" + the_layer + "')").innerHTML;
    ContentHTML = "<style>.BBS_QUOTE{border:#666 1px dashed;padding:8px;margin:8px; text-align:left;background-color:#fff; zoom:1;}.BBS_QUOTE{filter:alpha(opacity=70);opacity:0.7;background-color:none;}.BBS_QUOTE img{position:relative;vertical-align:baseline;}</style><DIV align=center><DIV class=BBS_QUOTE><IMG src='<?=MYOA_STATIC_SERVER?>/static/images/bbs_quote.gif'><B><?=_("引用")?> " + ContentHTML + "</B><BR><DIV>"+ContentHTML2+"</DIV></DIV></DIV><P></P>";
    setEditorHtml('CONTENT',ContentHTML);
}
</script>
<style>
.pagebar{
    text-align: center;
    font-size: 12px;
    word-wrap:break-word;
    word-brak:normal;
}
#cke_TD_HTML_EDITOR_CONTENT
{
    border:none;
    border-left:1px solid #AAA;
    border-right:1px solid #AAA;
}
.fast_replay_td textarea
{
    border:none
}
</style>


<body class="bodycolor" style="text-align: center;">
<?
$query = "SELECT COMMENT_ID from BBS_COMMENT where COMMENT_ID='$COMMENT_ID' AND IS_CHECK!='0' AND IS_CHECK!='2'";

$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
if(mysql_num_rows($cursor) <= 0)
{
    Message(_("提示"),_("帖子已提交，请等待审批"));
    ?>
    <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='board.php?BOARD_ID=<?=$BOARD_ID?>&IS_MAIN=1';">
    <?
    exit;
}

//------- 个人信息 --------
$query = "SELECT a.USER_NAME as USER_NAME,b.NICK_NAME as NICK_NAME from USER a,USER_EXT b where a.UID='".$_SESSION["LOGIN_UID"]."' AND a.UID=b.UID";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $USER_NAME1=$ROW["USER_NAME"];
    $NICK_NAME1=$ROW["NICK_NAME"];
}

//------- 讨论区信息 -------
$query = "SELECT * from BBS_BOARD where BOARD_ID='$BOARD_ID' and (DEPT_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) ".dept_other_sql("DEPT_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) ".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BOARD_HOSTER))";
$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $DEPT_ID            = $ROW["DEPT_ID"];
    $PRIV_ID            = $ROW["PRIV_ID"];
    $USER_ID1           = $ROW["USER_ID"];
    $BOARD_NAME         = $ROW["BOARD_NAME"];
    $ANONYMITY_YN       = $ROW["ANONYMITY_YN"];
    $BOARD_HOSTER       = $ROW["BOARD_HOSTER"];
    $AUTHOR_NAME        = $ROW["AUTHOR_NAME"];
    $LOCK_DAYS_BEFORE   = $ROW["LOCK_DAYS_BEFORE"];
    $NEED_CHECK         = $ROW["NEED_CHECK"];
    $BOARD_NAME         = str_replace("<","&lt",$BOARD_NAME);
    $BOARD_NAME         = str_replace(">","&gt",$BOARD_NAME);
//   $BOARD_NAME=stripslashes($BOARD_NAME);
    ?>

    <script>
        <?
         if($AUTHOR_NAME==$USER_NAME1 || $ANONYMITY_YN=="0")
            $NAME_SELECT=1;
         else
            $NAME_SELECT=2;
        ?>

        NAME_SELECT=<?=$NAME_SELECT?>;

        function set_name(name)
        {
            NAME_SELECT=name;
        }
    </script>
    <?
}
else
{
    //----------讨论区权限控制---------
    exit;
}

//----------讨论区权限控制---------
//if(!($DEPT_ID=="ALL_DEPT" || find_id($DEPT_ID,$_SESSION["LOGIN_DEPT_ID"]) || find_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV"]) || find_id($USER_ID1,$_SESSION["LOGIN_USER_ID"]) || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
//	 exit;
?>
<div>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><a href="index.php"> <?=_("讨论区")?></a>
                &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a> &raquo; <?=_("查看文章")?><br>
            </td>
        </tr>
    </table>
</div>
<div style="width:98%;text-align:center; margin:0 auto;">
    <div class="threadflow" style="width:190px;height:25px;padding-top:3px; margin-bottom:5px;">
        <?
        $WHERE_STR0="";
        if(!($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
            $WHERE_STR0 = "and (SHOW_YN='0'||USER_ID='".$_SESSION["LOGIN_USER_ID"]."')";

        $query = "SELECT COMMENT_ID from BBS_COMMENT where (BOARD_ID='$BOARD_ID' or  BOARD_ID='-1') and PARENT='0' AND IS_CHECK!=0 AND IS_CHECK!=2 ".$WHERE_STR0." order by  BOARD_ID asc,TOP desc,SUBMIT_TIME desc";
        $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
        $ARRAY_COUNT=0;
        while($ROW=mysql_fetch_array($cursor))
        {
            $ARRAY_COUNT++;
            $COMMENT_ID1 = $ROW["COMMENT_ID"];
            $COMMENT_ID_ARRAY1[$ARRAY_COUNT] = $COMMENT_ID1;
            $COMMENT_ID_ARRAY2[$COMMENT_ID1] = $ARRAY_COUNT;
        }

        $LAST_COMMENT_ID = $COMMENT_ID_ARRAY2[$COMMENT_ID]-1;
        $NEXT_COMMENT_ID = $COMMENT_ID_ARRAY2[$COMMENT_ID]+1;

        if($COMMENT_ID_ARRAY1[$LAST_COMMENT_ID]!="")
        {
            ?>
            <a href="comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID_ARRAY1[$LAST_COMMENT_ID]?>&PAGE_START=<?=$PAGE_START?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/previouspage.gif" style="vertical-align :text-bottom;"><?=_("上一主题")?> </a> |
            <?
        }
        else
            echo "<img src=\"".MYOA_STATIC_SERVER."/static/images/previouspage.gif\" style=\"vertical-align :text-bottom;\">"._("上一主题 |");
        if($COMMENT_ID_ARRAY1[$NEXT_COMMENT_ID]!="")
        {
            ?>

            <a href="comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID_ARRAY1[$NEXT_COMMENT_ID]?>&PAGE_START=1"> <?=_("下一主题")?><img src="<?=MYOA_STATIC_SERVER?>/static/images/nextpage.gif" style="vertical-align :text-bottom;"></a>
            <?
        }else
            echo _("下一主题")."<img src=\"".MYOA_STATIC_SERVER."/static/images/nextpage.gif\" style=\"vertical-align :text-bottom;\">";
        ?>
    </div>
</div>
<div id="comment">
    <div id="comment_body" style="margin:0 auto; margin-bottom:5px;">
        <?
        $COMMENT_ID_PEARENT=$COMMENT_ID;
        $WHERE_STR="";
        if(!($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
            $WHERE_STR = "and (SHOW_YN='0'||USER_ID='".$_SESSION["LOGIN_USER_ID"]."')";

        //-------- 阅读数加1 ---------
        if($PAGE==""||$PAGE==1)
        {
            $query = "update BBS_COMMENT set READ_CONT=READ_CONT+1 where COMMENT_ID='$COMMENT_ID'";
            $cursor = exequery(TD::conn(),$query);
        }

        $query = "SELECT BOARD_ID from BBS_COMMENT where (COMMENT_ID='$COMMENT_ID' OR PARENT='$COMMENT_ID') ".$WHERE_STR."";
        $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
        $NUM_ROWS = mysql_num_rows($cursor);

        $NUM_P = 10;
        if($PAGE=="")
            $PAGE=1;
        $START = ($PAGE-1)*$NUM_P;

        $P = ceil(($NUM_ROWS)/$NUM_P);
        $PRE_I = $PAGE - 1;
        if($PAGE != 1)
        {
            $PBAR_STR .= _("<a href=\"comment.php?PAGE=1&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">首页</a>&nbsp;");
            $PBAR_STR .= _("<a href=\"comment.php?PAGE=$PRE_I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">上一页</a>&nbsp;");
        }

        $SHOW_COUNT = 0;
        for($I = 1;$I <= $P;$I++)
        {
            if($I==$PAGE)
            {
                $SHOW_COUNT++;
                $PBAR_STR .= "&nbsp;".$I."&nbsp;&nbsp;";
            }
            else
            {
                if($PAGE > $P - 10 && $I > $P - 10)
                {
                    $SHOW_COUNT++;
                    if($SHOW_COUNT > 10)
                        break;
                    $PBAR_STR .= "<a href=\"comment.php?PAGE=$I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">".$I."</a>&nbsp;";
                }
                else
                {
                    if($I > $PAGE - 5)
                    {
                        $SHOW_COUNT++;
                        if($SHOW_COUNT > 10)
                            break;

                        $PBAR_STR .= "<a href=\"comment.php?PAGE=$I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">".$I."</a>&nbsp;";
                    }
                }
            }
        }

        $NEXT_I = $PAGE + 1;
        if($PAGE < $P)
        {
            $PBAR_STR .= _("<a href=\"comment.php?PAGE=$NEXT_I&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">下一页</a>&nbsp;");
            $PBAR_STR .= _("<a href=\"comment.php?PAGE=$P&BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START\">末页</a>&nbsp;");
        }

        //-------- 文章信息 ----------
        $query = "SELECT * from BBS_COMMENT where (COMMENT_ID='$COMMENT_ID' OR PARENT='$COMMENT_ID') AND IS_CHECK!=0 AND IS_CHECK!=2 ".$WHERE_STR." ORDER BY OLD_SUBMIT_TIME limit $START,$NUM_P";
        $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
        $COUNT_ALL=mysql_num_rows($cursor);
        $COUNT=0;
        while($ROW=mysql_fetch_array($cursor))
        {
            $COUNT++;
            $SAVEBOARD_ID = $ROW["BOARD_ID"];
            $COMMENT_ID = $ROW["COMMENT_ID"];
            $USER_ID = $ROW["USER_ID"];
            $AUTHOR_NAME = $ROW["AUTHOR_NAME"];
            $SUBJECT = $ROW["SUBJECT"];
            if(td_trim($SUBJECT)=="")
            {
                $SUBJECT = "无标题";
            }
            $CONTENT = $ROW["CONTENT"];
            $SIGNED_YN = $ROW["SIGNED_YN"];
            $SUBMIT_TIME = $ROW["SUBMIT_TIME"];
            $READEDER = $ROW["READEDER"];
            $SHOW_YN = $ROW["SHOW_YN"];
            $DELETE_YN = $ROW["DELETE_YN"];
            $UPDATE_PERSON = $ROW["UPDATE_PERSON"];
            $UPDATE_TIME = $ROW["UPDATE_TIME"];
            $OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];
            if($OLD_SUBMIT_TIME=="0000-00-00 00:00:00")
                $OLD_SUBMIT_TIME=$SUBMIT_TIME;
            $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
            $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
            $PARENT = $ROW["PARENT"];
            $LOCK_YN = $ROW["LOCK_YN"];
            $DELETE_YN = $ROW["DELETE_YN"];
            $TYPE= $ROW["TYPE"];
            $AUTHOR_NAME_TMEP=$ROW["AUTHOR_NAME_TMEP"];
            if($SAVEBOARD_ID=="-1")
                $TYPE=_("公告");
            $AUTHOR_NAME=str_replace("<","&lt",$AUTHOR_NAME);
            $AUTHOR_NAME=str_replace(">","&gt",$AUTHOR_NAME);
//  $AUTHOR_NAME=stripslashes($AUTHOR_NAME);
            $SUBJECT=str_replace("<","&lt",$SUBJECT);
            $SUBJECT=str_replace(">","&gt",$SUBJECT);
//  $SUBJECT=stripslashes($SUBJECT);
            $USER_NAME="";
            //---------------修改---------------
            //$query1 = "SELECT * from USER where USER_ID='$USER_ID'";
            $query1 = "SELECT a.USER_NAME as USER_NAME,a.DEPT_ID as DEPT_ID, b.BBS_SIGNATURE as BBS_SIGNATURE from USER a,USER_EXT b where a.USER_ID='$USER_ID' and a.UID=b.UID";
            //---------------结束---------------
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
            {
                $USER_NAME=$ROW["USER_NAME"];
                $DEPT_NAME=td_trim(GetDeptNameById($ROW["DEPT_ID"]));
                $BBS_SIGNATURE = $ROW["BBS_SIGNATURE"];
            }

            if(!find_id($READEDER,$_SESSION["LOGIN_USER_ID"]))
            {
                //修改事务提醒状态--yc
                update_sms_status('18',$BOARD_ID);

                $READEDER.=$_SESSION["LOGIN_USER_ID"].",";
                $query1="update BBS_COMMENT set READEDER='$READEDER' where COMMENT_ID='$COMMENT_ID'";
                exequery(TD::conn(),$query1);
            }

            $COMMENT_ID_ROOT = "";
            if($PARENT == 0)
                $COMMENT_ID_ROOT=$COMMENT_ID;
            else
                $COMMENT_ID_ROOT=$REPLAY_COMMENT_ID;

//  $CONTENT=stripslashes($CONTENT);

            ?>
            <div class="comment_box">
            <?
            if($COUNT==1)
            {
                ?>
                <div class="subject_header1">
                    <table width="100%" height="100%" border="0">
                        <tr>
                            <td style="font-weight: bold;font-size: 13px;">
                                <?
                                if($TYPE!="" && $TYPE!=_("无分类"))
                                    echo "[$TYPE]";
                                echo _("标题：");
                                echo $SUBJECT;
                                ?></td>
                            <td style="" width="110">
                                <?
                                if($LOCK_YN==0)
                                {
                                    $LOCK_TEXT = _("锁贴");
                                    $LOCK_FLAG = 1;
                                }else{
                                    $LOCK_TEXT = _("解锁");
                                    $LOCK_FLAG = 0;
                                }

                                //版主和oa管理员有权锁贴和解锁
                                if(find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]==1)
                                {
                                    ?>
                                    <input type="button" value="<?=$LOCK_TEXT?>" class="SmallButton" onClick="lock_comment('<?=$REPLAY_COMMENT_ID?>','<?=$LOCK_FLAG?>');">
                                    <?
                                }
                                else
                                    echo  "&nbsp;&nbsp;&nbsp;&nbsp;";
                                ?>
                                <input type="button" value="<?=_("返回")?>" class="SmallButton" onClick="location='board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>';">
                            </td>
                        </tr>
                    </table>
                </div>
                <?
            }
            ?>
            <div class="comment_title">
                <table width="99%" height="100%" >
                    <tr>
                        <td>
                            <span class="flower_span"><?=$COUNT+($PAGE-1)*$NUM_P?>#</span>
     				<span class="info_span" id="span_<?=$COUNT+($PAGE-1)*$NUM_P?>">
<?
if($ANONYMITY_YN==0 || ($ANONYMITY_YN==1 && $_SESSION["LOGIN_USER_PRIV"]==1))
{
    if($AUTHOR_NAME_TMEP==2 || $AUTHOR_NAME!=$USER_NAME)
        echo _("作者昵称：").$AUTHOR_NAME;
    //echo _("作者姓名：").$USER_NAME._("（昵称：").$AUTHOR_NAME._("）")."&nbsp;"._("部门：").$DEPT_NAME;
    else
        echo _("作者姓名：").$USER_NAME."&nbsp;"._("部门：").$DEPT_NAME;
}else{
    if($AUTHOR_NAME_TMEP==2 || $AUTHOR_NAME!=$USER_NAME)
        echo _("作者昵称：").$AUTHOR_NAME;
    //echo _("作者昵称：").$AUTHOR_NAME."&nbsp;"._("部门：").$DEPT_NAME;
    else
        echo _("作者姓名：").$USER_NAME."&nbsp;"._("部门：").$DEPT_NAME;
}
echo "&nbsp;&nbsp;".$OLD_SUBMIT_TIME;
if($DELETE_YN=="1")
    echo "&nbsp;<font color=red>"._("已删除")."</font>";
if($SHOW_YN=="1")
    echo "&nbsp;<font color=red>"._("已屏蔽")."</font>";
?>
           </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="main_contment" style="overflow:auto;">
                <?
                if(!(find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]==1)&&$DELETE_YN=="1")
                {
                    $SUBJECT="";
                    $CONTENT=_("------------帖子已删除-----------");
                }
                ?>
                <b><?=$SUBJECT?></b><br><br>

<span id="content_<?=$COUNT+($PAGE-1)*$NUM_P?>" style="font-size:<?=$FONT_SIZE?>pt;">
	<?=$CONTENT?>
</span>
                <?
                if($ATTACHMENT_NAME!="")
                {
                    $IMAGE_COUNT=0;
                    $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
                    $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
                    $IMG_TYPE_STR="gif,jpg,png,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,";

                    $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
                    for($I=0;$I<$ARRAY_COUNT;$I++)
                    {
                        if($ATTACHMENT_ID_ARRAY[$I]=="" || stristr($CONTENT, "ATTACHMENT_ID=".attach_id_encode($ATTACHMENT_ID_ARRAY[$I], $ATTACHMENT_NAME_ARRAY[$I])))
                            continue;

                        $IMAGE_COUNT++;
                        if($IMAGE_COUNT == 1)
                            echo "<br><br><br><b>"._("附件：")."</b>";
                        echo "<br>";
                        echo attach_link($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I],1,1,1);

                        $MODULE=attach_sub_dir();
                        $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
                        $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
                        if($YM)
                            $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
                        $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);

                        $EXT_NAME=substr(strrchr($ATTACHMENT_NAME_ARRAY[$I],"."),1);
                        $EXT_NAME=strtolower($EXT_NAME);

                        if(find_id($IMG_TYPE_STR,$EXT_NAME))
                        {
                            $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
                            if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
                                $WIDTH=$IMG_ATTR[0];
                            if($WIDTH>600)
                                $WIDTH=600;
                            ?>
                            <br><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="0"  width="<?=$WIDTH?>" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>">
                            <?
                        }
                    }//for
                }//if
                ?>
            </div>
            <?
            if($SIGNED_YN==1 && trim($BBS_SIGNATURE)!="")
            {
                ?>
                <div style="text-align:left;margin-left:10px;word-break:break-all;word-wrap:break-word;">
                    <img src="<?=MYOA_STATIC_SERVER?>/static/images/sigline.gif"><br>
                    <?=$BBS_SIGNATURE?>
                </div>
                <?
            }
            if($UPDATE_PERSON!="" && $UPDATE_TIME!="")
            {
                ?>
                <div style="text-align:left;margin-left:10px;">
                    <I><font size="2" color=blue>
                            <?
                            /*$EDIT_NICK_NAME="";
                            //-------------------修改--------------------
                            //$query6 = "SELECT USER_NAME,NICK_NAME from USER where USER_ID='$UPDATE_PERSON'";
                            $query6 = "SELECT a.USER_NAME as USER_NAME,b.NICK_NAME as NICK_NAME from USER a, USER_EXT b where a.USER_ID='$UPDATE_PERSON'";
                            //-------------------结束--------------------
                            $cursor6= exequery(TD::conn(),$query6);
                            if($ROW6=mysql_fetch_array($cursor6))
                            {
                                $EDIT_NICK_NAME = $ROW6["NICK_NAME"];
                                $USER_NAME      = $ROW6["USER_NAME"];
                            }

                            if($EDIT_NICK_NAME=="")
                               echo $USER_NAME;//substr(GetUserNameById($UPDATE_PERSON),0,-1);
                            else
                               echo $EDIT_NICK_NAME;
                            */

                            if($AUTHOR_NAME_TMEP==1)
                            {
                                echo $USER_NAME;
                            }else
                            {
                                echo $AUTHOR_NAME;
                            }
                            ?>
                            &nbsp;<?=sprintf(_("编辑于%s"),"&nbsp;".$UPDATE_TIME)?></font></I>
                </div>
                <?
            }
            if($ANONYMITY_YN!=2)
            {
                ?>
                <div class="op_bar" <?if($LOCK_YN==1) echo "style=\"display:none\"";?>>
                    <a href="edit.php?REPLY=1&BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID_ROOT?>&PAGE_START=<?=$PAGE_START?>" title="<?=_("回复此文章")?>"><?=_("回复")?></a>
                    <a href="javascript:quote_comment('<?=$COUNT+($PAGE-1)*$NUM_P?>');" title="<?=_("引用此文章")?>"><?=_("引用")?></a>
                    <?
                    if($_SESSION["LOGIN_USER_ID"] == $USER_ID || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
                    {
                        if($SHOW_YN==0)
                        {
                            ?>
                            <a href="javascript:show_comment('<?=$COMMENT_ID?>');" title="<?=_("屏蔽此文章")?>"><?=_("屏蔽")?></a>
                            <?
                        }
                        if($SHOW_YN==1)
                        {
                            ?>
                            <a href="javascript:recover_comment('<?=$COMMENT_ID?>');" title="<?=_("恢复此屏蔽文章")?>"><?=_("恢复")?></a>
                            <?
                        }
                        $SHOW_TIME = date("Y-m-d H:i:s");
                        $DIF = strtotime($SHOW_TIME) - strtotime($OLD_SUBMIT_TIME);
                        $DAYS = $DIF/3600/24;
                        if($DAYS > $LOCK_DAYS_BEFORE && $LOCK_DAYS_BEFORE != 0)
                        {
                            ?>
                            <a href="#" style="display:none;" title="<?=_("进入锁定时间，无法编辑")?>"><?=_("编辑")?></a>
                            <a href="#" style="display:none;" title="<?=_("进入锁定时间，无法删除")?>"><?=_("删除")?></a>
                            <?
                        }
                        else
                        {
                            ?>
                            <a href="edit.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID?>&PAGE_START=<?=$PAGE_START?>" title="<?=_("编辑此文章")?>"><?=_("编辑")?></a>
                            <?
                            if($USER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1|| find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
                            {
                                if($COUNT_ALL > 1 && $COUNT != 1)
                                {
                                    ?>
                                    <a href="javascript:delete_comment('<?=$COMMENT_ID?>','<?=$USER_ID?>');" title="<?=_("删除此文章")?>"><?=_("删除")?></a>
                                    <?
                                }
                                if($COUNT_ALL == 1 && $COUNT == 1)
                                {
                                    ?>
                                    <a href="javascript:delete_comment('<?=$COMMENT_ID?>','<?=$USER_ID?>');" title="<?=_("删除此文章")?>"><?=_("删除")?></a>
                                    <?
                                }
                            }
                        }
                    }
                    ?>
                </div>
                </div>
                <?
            }//if($ANONYMITY_YN!=2)
        }//while

        if($NICK_NAME1=="")
            $NICK_NAME1=$USER_NAME1;
        ?>
    </div>
    <div style="width:98%; margin:0 auto; margin-bottom:5px;">
        <div class="threadflow" style="width:192px;height:25px;padding-top:3px;">
            <?
            if($COMMENT_ID_ARRAY1[$LAST_COMMENT_ID]!="")
            {
                ?>
                <a href="comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID_ARRAY1[$LAST_COMMENT_ID]?>&PAGE_START=<?=$PAGE_START?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/previouspage.gif" style="vertical-align :text-bottom;"><?=_("上一主题")?> </a> |
                <?
            }
            else
                echo "<img src=\"".MYOA_STATIC_SERVER."/static/images/previouspage.gif\" style=\"vertical-align :text-bottom;\">"._("上一主题 |");
            if($COMMENT_ID_ARRAY1[$NEXT_COMMENT_ID]!="")
            {
                ?>

                <a href="comment.php?BOARD_ID=<?=$BOARD_ID?>&COMMENT_ID=<?=$COMMENT_ID_ARRAY1[$NEXT_COMMENT_ID]?>&PAGE_START=1"><?=_("下一主题")?> <img src="<?=MYOA_STATIC_SERVER?>/static/images/nextpage.gif" style="vertical-align :text-bottom;"></a>
                <?
            }else
                echo _("下一主题")."<img src=\"".MYOA_STATIC_SERVER."/static/images/nextpage.gif\" style=\"vertical-align :text-bottom;\">";
            ?>
        </div>
    </div>
    <br /><br />
    <?
    if($P > 1)
    {
        ?>
        <div class="pagebar"><?=$PBAR_STR?></div>
        <br />
        <br />
        <?
    }
    ?>

    <form name="form1" enctype="multipart/form-data" style="margin-top:5px;" action="insert.php" method="post" <?if($LOCK_YN==1) echo "style=\"display:none;margin:0 auto;\"";?>>
        <?
        if($ANONYMITY_YN!=2)
        {
            ?>
            <div class="subject_header" style="width:98%; padding-left:0px; margin:0 auto; "><?=_("快速回复主题")?></div>
            <div style="margin-top: 0px;font-size:12px; width:98%; border-left:1px solid #AAA; border-right:1px solid #AAA; margin:0 auto;">
                <TABLE cellSpacing=1 cellPadding=0 align=center class="fast_replay_table" border="0" style="border:0px; background:#fff;width:100%;">
                    <TR>
                        <TD align="middle" width="10%" class="fast_replay_td"><?=_("署名")?></TD>
                        <TD align="left" class="fast_replay_td">
                            <INPUT type="radio" name="AUTHOR_NAME" value="1" <? if(($AUTHOR_NAME==$USER_NAME1 || $ANONYMITY_YN=="0")) echo "checked";?> onClick="set_name(1)">
                            <INPUT type="text"  name="USER_NAME" size="10" maxlength="25" class="BigStatic" value="<?=$USER_NAME1?>" readonly>
                            <?
                            if($ANONYMITY_YN=="1")
                            {
                                ?>
                                <INPUT type="radio" name="AUTHOR_NAME" value="2" <? if($AUTHOR_NAME!=$USER_NAME1) echo "checked";?> onClick="set_name(2)"><?=_("昵称")?>
                                <INPUT type="text" name="NICK_NAME" size="10" maxlength="25" class="BigInput" value="<?=$NICK_NAME1?>">
                                <?
                            }
                            ?>
                        </TD>
                    </TR>
                    <TR>
                        <TD align="middle" width="10%" class="fast_replay_td"><?=_("标题")?></TD>
                        <TD align="left" class="fast_replay_td"><INPUT name="SUBJECT" tabIndex=1 size="40" class="BigInput"></TD>
                    </TR>
                    <TR>
                        <TD vAlign="top" align="middle" width="10%" class="fast_replay_td"><?=_("内容")?></TD>
                        <TD vAlign="top" class="fast_replay_td">
                            <DIV align="left">
                                <?
                                $editor = new Editor('CONTENT');
                                $editor->Height = '200' ;
                                $editor->Config["StartupFocus"]="false";
                                $editor->Config = array("EditorAreaStyles" => "body{font-size:".$FONT_SIZE."pt;}","model_type" => "06");
                                $editor->Value = '' ;
                                $editor->Create() ;
                                ?>
                            </DIV>
                        </TD>
                    </TR>
                    <tr height="25">
                        <td nowrap class="fast_replay_td"><?=_("附件选择：")?></td>
                        <td class="fast_replay_td" style="text-align:left;">
                            <script>ShowAddFile();ShowAddImage();</script>
                            <input type="hidden" name="ATTACHMENT_ID_OLD" value>
                            <input type="hidden" name="ATTACHMENT_NAME_OLD" value>
                        </td>
                    </tr>
                    <tr height="25">
                        <td nowrap class="fast_replay_td"><?=_("签名档：")?></td>
                        <td class="fast_replay_td" style="text-align:left;">
                        <input type="checkbox" name="SIGNED_YN" id="SIGNED_YN" <?if($SIGNED_YN==1 || $COMMENT_ID=="" ||empty($BOARD_ID)) echo "checked";?>><label for="SIGNED_YN"><?=_("附加签名档")?></label>
                        </td>
                    </tr>
                    <?
                    if($BOARD_ID!="-1")
                    {
                        $SMS_SELECT_REMIND = sms_select_remind(18);
                        $SMS2_SELECT_REMIND = sms2_select_remind(18);
                        ?>
                        <tr>
                            <td nowrap class="fast_replay_td"><?=_("事务提醒")?><br><?=_("讨论区人员：")?></td>
                            <td class="fast_replay_td" style="text-align:left;">
                                <?
                                if($SMS_SELECT_REMIND!="")
                                {
                                    ?>
                                    <input type="radio" name="SMS_SELECT_REMIND" id="SMS_SELECT_REMIND2" value="2" onClick="document.getElementById('SMS_SELECT_REMIND_SPAN').style.display='none';"><label for="SMS_SELECT_REMIND2"><?=_("提醒本帖人员")?></label>
                                    <?
                                }
                                echo $SMS_SELECT_REMIND;
                                ?>
                            </td>
                        </tr>
                        <?
                        if($SMS2_SELECT_REMIND!="")
                        {
                            ?>
                            <tr>
                                <td nowrap class="fast_replay_td"><?=_("手机短信提醒")?><br><?=_("讨论区人员：")?></td>
                                <td class="fast_replay_td" style="text-align:left;">
                                    <input type="radio" name="SMS2_SELECT_REMIND" id="SMS2_SELECT_REMIND2" value="2" onClick="document.getElementById('SMS2_SELECT_REMIND_SPAN').style.display='none';"><label for="SMS2_SELECT_REMIND2"><?=_("提醒本帖人员")?></label>
                                    <?=$SMS2_SELECT_REMIND?>
                                </td>
                            </tr>
                            <?
                        }
                    }
                    ?>
                    <TR>
                        <TD colspan="2" align="center">
                            <input type="hidden" name="BOARD_ID" value="<?=$BOARD_ID?>">
                            <input type="hidden" name="COMMENT_ID" value="<?=$COMMENT_ID_PEARENT?>">
                            <input type="hidden" name="PAGE_START" value="<?=$PAGE_START?>">
                            <input type="hidden" name="REPLY" value="1">
                            <input type="hidden" name="OP" value="">
                            <input type="hidden" name="FAST_REPLY" value="1">
                            <input type="hidden" name="NEED_CHECK" value="<?=$NEED_CHECK?>">
                            <input type="button" value="<?=_("发表帖子")?>" class="BigButton" onClick="SubmitForm()">&nbsp;&nbsp;
                            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='board.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>';">
                        </TD>
                        </TD>
                    </TR>
                </TABLE>
            </div>
            <?
        }
        ?>
    </form>
</div>
<script src="/module/ueditor/ueditor.parse.js"></script>
<script type="text/javascript">
    uParse('.main_contment', {
        rootPath: '/module/ueditor'
    })
</script>
</body>
</html>
<script>
    /*
     document.getElementById("content___Frame").style.display="none";
     function pageFocus(){
     document.getElementById("content___Frame").style.display="block";
     }
     setTimeout("pageFocus()",1000);
     */
</script>

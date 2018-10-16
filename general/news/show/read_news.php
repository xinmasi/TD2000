<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_org.php");
if(!isset($start))
    $start=0;
$FONT_SIZE = get_font_size("FONT_NEWS", 12);

$HTML_PAGE_TITLE = _("查看新闻");
include_once("inc/header.inc.php");
?>
<style>
    #ui_notify_show_preveiw{
        background-color: #fff;
        padding: 8px 10px;
    }
    .ui-notify-tips-preview{
        display: inline-block;
        width: 30%;
        vertical-align: top;
    }
    .ui-notify-tips-preview div{
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        -o-text-overflow: ellipsis;
    }
    .ui-notify-tips-preview div p{
        display: inline-block;
    }
</style>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>
<script Language="JavaScript">
    //如果从OA精灵打开，则最大化窗口
    if(window.external && typeof window.external.OA_SMS != 'undefined')
    {
        var h = Math.min(800, screen.availHeight - 180),
            w = Math.min(1280, screen.availWidth - 180);
        window.external.OA_SMS(w, h, "SET_SIZE");
    }
    function CheckForm()
    {
        if(document.form1.CONTENT.value=="")
        { alert("<?=_("评论的内容不能为空！")?>");
            return (false);
        }

        if(document.getElementsByName("AUTHOR_NAME").length >1 && document.getElementsByName("AUTHOR_NAME").item(1).checked && document.form1.NICK_NAME.value=="")
        { alert("<?=_("昵称不能为空！")?>");
            return (false);
        }

        return (true);
    }

    function get_keyword()
    {
        document.getElementById("showKeyword").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("正在搜索本文关键词……")?>";
        var httpReq=getXMLHttpObj();
        httpReq.open("GET","/inc/get_keyword.php?MODULE_ID=NEWS&ID=<?=$NEWS_ID?>");
        httpReq.onreadystatechange=function(){
            if(httpReq.readyState==4){
                if(httpReq.responseText=="")
                {
                    document.getElementById("showKeyword").innerHTML="<?=_("未能找到关键词")?>";
                }
                else
                {
                    document.getElementById("showKeyword").innerHTML=httpReq.responseText;
                }
            }
        };
        httpReq.send(null);
    }

    function open_news(KEYWORD,LIST)
    {
        URL="/general/ipanel/keyword/index.php?KEYWORD="+KEYWORD+"&LIST="+LIST;
        myleft=(screen.availWidth-780)/2;
        mytop=100
        mywidth=780;
        myheight=500;

        window.open(URL,"KEYWORD","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
    }
</script>
<body class="bodycolor" style="margin:10px;">
<?
function GetReleaseName($TO_ID,$PRIV_ID,$USER_ID)
{
    if(!empty($TO_ID))
    {
        if($TO_ID == 'ALL_DEPT')
        {
            $RELEASE_SCOPE = '全体部门';
        }else{
            $TO_IDS= td_trim($TO_ID);
            $query = "SELECT DEPT_NAME from department where DEPT_ID in ($TO_IDS)";
            $cursor= exequery(TD::conn(),$query);
            while($ROW=mysql_fetch_array($cursor))
            {
                $RELEASE_SCOPE.=$ROW['DEPT_NAME'].',';
            }
        }
    }
    if(!empty($PRIV_ID))
    {
        $PRIV_IDS= td_trim($PRIV_ID);
        $query = "SELECT PRIV_NAME from USER_PRIV where USER_PRIV in ($PRIV_IDS)";
        $cursor= exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
            $RELEASE_SCOPE.=$ROW['PRIV_NAME'].',';
        }
    }
    if(!empty($USER_ID))
    {
        $USER_IDS = GetUidByUserID($USER_ID);
        $USER_IDS= td_trim($USER_IDS);
        $query = "SELECT USER_NAME from USER where UID in ($USER_IDS)";
        $cursor= exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
            $RELEASE_SCOPE.=$ROW['USER_NAME'].',';
        }
    }
    return td_trim($RELEASE_SCOPE);
}
$POST_PRIV=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV");
$query = "SELECT * from NEWS where NEWS_ID='$NEWS_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $SUBJECT=$ROW["SUBJECT"];
    $PROVIDER=$ROW["PROVIDER"];
    $NEWS_TIME=$ROW["NEWS_TIME"];
    $CLICK_COUNT=$ROW["CLICK_COUNT"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $FORMAT=$ROW["FORMAT"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $ANONYMITY_YN=$ROW["ANONYMITY_YN"];
    $TO_ID=$ROW["TO_ID"];
    $PRIV_ID=$ROW["PRIV_ID"];
    $USER_ID=$ROW["USER_ID"];
    $READERS=$ROW["READERS"];
    $LAST_EDITOR=$ROW["LAST_EDITOR"];
    $LAST_EDIT_TIME=$ROW["LAST_EDIT_TIME"];
    $KEYWORD=$ROW["KEYWORD"];
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
    $CLICK_COUNT++;
    //$SUBJECT=stripslashes($SUBJECT);

    $RELEASENAME = GetReleaseName($TO_ID,$PRIV_ID,$USER_ID);
    if($SUBJECT_COLOR!="")
        $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";

    $COMPRESS_CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
    if($COMPRESS_CONTENT!=""&&$FORMAT!="2")
        $CONTENT=$COMPRESS_CONTENT;
    else
        $CONTENT=$ROW["CONTENT"];
    if($CONTENT!="")
    {
        if($KEYWORD=="")
            $SHOW_KEYWORD="<span id='showKeyword' class='small1'><a href='javascript:get_keyword();' class='A1'>"._("显示本文关键词")."</a></span>";
        else
        {
            $SHOW_KEYWORD=_("本文关键词：");
            $KEYWORD_ARRAY=explode(",",$KEYWORD);
            foreach($KEYWORD_ARRAY as $KID)
            {
                if($KID!="")
                    $SHOW_KEYWORD.="<a href=javascript:open_news('".$KID."',2)>".$KID."</a> ";
            }

        }
    }
    if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
    {
        //修改事务提醒状态--yc
        update_sms_status('14',$NEWS_ID);

        $READERS.=$_SESSION["LOGIN_USER_ID"].",";
        $NEWS_ID=intval($NEWS_ID);
        $query = "update NEWS set READERS='$READERS',CLICK_COUNT='$CLICK_COUNT' where NEWS_ID='$NEWS_ID'";
    }
    else
    {
        $NEWS_ID=intval($NEWS_ID);
        $query = "update NEWS set CLICK_COUNT='$CLICK_COUNT' where NEWS_ID='$NEWS_ID'";
    }
    exequery(TD::conn(),$query);

    if($FORMAT=="2")
        Header("location: $CONTENT");

    $IMG_TYPE_STR="gif,jpg,png,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,";

    if($_SESSION["LOGIN_USER_PRIV"]!=1 && $PROVIDER!=$_SESSION["LOGIN_USER_ID"] && $TO_ID!='ALL_DEPT' && !find_id($TO_ID,$_SESSION["LOGIN_DEPT_ID"]) && check_id($TO_ID,$_SESSION["LOGIN_DEPT_ID_OTHER"],true)=="" && !find_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV"]) && check_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV_OTHER"],true)=="" && !find_id($USER_ID,$_SESSION["LOGIN_USER_ID"]) && $POST_PRIV!=1)
    {
        Message(_("提示"),_("您无权限查看该新闻。"));
        exit;
    }

    if($CONTENT=="")
        $CONTENT="<br>"._("见附件");
    $TR_HEIGHT=160;
    if($FORMAT=="1")
        $TR_HEIGHT=550;

    $TYPE_NAME=get_code_name($TYPE_ID,"NEWS");
    if($TYPE_NAME!="")
        $SUBJECT=_("【").$TYPE_NAME._("】").$SUBJECT;

    $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$PROVIDER'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
    {
        $PROVIDER_NAME=$ROW1["USER_NAME"];
        $DEPT_ID=$ROW1["DEPT_ID"];
    }

    $DEPT_NAME=dept_long_name($DEPT_ID);

    $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$LAST_EDITOR'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
    {
        $LAST_EDITOR_NAME=$ROW1["USER_NAME"];
        $LAST_EDITOR_DEPTID=$ROW1["DEPT_ID"];
    }
    $LAST_EDITOR_DEPTNAME=dept_long_name($LAST_EDITOR_DEPTID);
}
else
{
    Message(_("提示"),_("该新闻已删除。"));
    exit;
}
?>
<table class="TableTop" width="100%" align="center">
    <tr>
        <td class="left" <?if($SUBJECT_COLOR!="" && $SUBJECT_COLOR!="#000000"){?> style="background:url('<?=MYOA_STATIC_SERVER?>/static/images/table_top_bg_blue.png')  repeat-x;" <?}?>></td>
        <td class="center" align="center" <?if($SUBJECT_COLOR!="" && $SUBJECT_COLOR!="#000000"){?> style="background:url('<?=MYOA_STATIC_SERVER?>/static/images/table_top_bg_blue.jpg')  repeat-x;" <?}?>><?=$SUBJECT?></td>
        <td class="right" <?if($SUBJECT_COLOR!="" && $SUBJECT_COLOR!="#000000"){?> style="background:url('<?=MYOA_STATIC_SERVER?>/static/images/table_top_bg_blue.png')  no-repeat;background-position-y:-64px;" <?}?> ></td>
    </tr>
</table>
<table class="TableBlock" width="100%" align="center">
    <tr>
        <td class="TableContent" align="left" id="ui_notify_show_preveiw">
            <span class="ui-notify-tips-preview">
                <!--新闻类型-->
                <div>
                    <?=_("新闻类型: ")?><p><?=empty($TYPE_NAME) ? '' : $TYPE_NAME?></p>
                </div>
                <!--发布时间-->
                <div>
                    <?=_("发布时间: ")?><p><?=empty($NEWS_TIME) ? '' : $NEWS_TIME?></p>
                </div>
            </span>
            <span class="ui-notify-tips-preview" style="margin-left: 4%">
                <!--发布人-->
                <div>
                     <?=_("发布人: ")?><p><?=empty($PROVIDER_NAME) ? '' : $PROVIDER_NAME?></p>
                </div>
                <!--发布范围-->
                <div>
                    <? empty($RELEASENAME) ? $RELEASENAME='' : $RELEASENAME; ?>
                    <?=_("发布范围: ")?><p title="<?=_("发布范围:$RELEASENAME")?>"><?=empty($RELEASENAME) ? '' : substr($RELEASENAME,0,60)?></p>
                </div>
            </span>
            <span class="ui-notify-tips-preview" style="margin-left: 4%">
                <!--点击量-->
                <div>
                    <?=_("点击量: ")?><p><?=empty($CLICK_COUNT) ? '0' : $CLICK_COUNT?></p>
                </div>
            </span>
        </td>
    </tr>
    <tr>
        <td height="<?=$TR_HEIGHT?>" valign="top" class="rich-content content"  style="font-size:<?=$FONT_SIZE?>pt;">
            <?
            $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
            $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
            $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);

            if($FORMAT!="1")
                echo $CONTENT;
            else
            {
                for($I=0;$I<$ARRAY_COUNT;$I++)
                {
                    $EXT_NAME=strtolower(substr($ATTACHMENT_NAME_ARRAY[$I],-4));
                    if($EXT_NAME==".mht"||$EXT_NAME==".htm"||$EXT_NAME==".html")
                    {
                        $MODULE=attach_sub_dir();
                        $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
                        $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
                        if($YM)
                            $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
                        $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);
                        ?>
                        <iframe id=mhtFrame src="" width="100%" height="800"></iframe>
                        <script>
                            mhtFrame.location="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>&DIRECT_VIEW=1";
                        </script>
                        <?
                        break;
                    }
                }
            }
            ?>
            <br><br> <?=$SHOW_KEYWORD?>
        </td>
    </tr>
    <?
    $ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
    if($ATTACH_ARRAY["NAME"]!="")
    {
        ?>
        <tr>
            <td class="TableData"><?=_("附件文件")?>:<br><?=attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],1,1,1,0,0,1,1,'',false,$FORMAT)?></td>
        </tr>
        <?
    }

    if($ATTACH_ARRAY["IMAGE_COUNT"]>0)
    {
        ?>
        <tr>
            <td class="TableData">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/image.gif" align="absmiddle" border="0">&nbsp;<?=_("附件图片")?>: <br><br>

                <?
                $ATTACHMENT_ID_ARRAY=explode(",",$ATTACH_ARRAY["ID"]);
                $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACH_ARRAY["NAME"]);
                $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);

                for($I=0;$I<$ARRAY_COUNT;$I++)
                {
                    if($ATTACHMENT_ID_ARRAY[$I]=="")
                        continue;

                    $MODULE=attach_sub_dir();
                    $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
                    $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
                    if($YM)
                        $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
                    $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);
                    $URL_ARRAY = attach_url($ATTACHMENT_ID_ARRAY[$I], $ATTACHMENT_NAME_ARRAY[$I], $MODULE, $OTHER);
                    if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
                    {
                        $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
                        if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
                            $WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
                        else
                            $WIDTH=100;
                        ?>
                        <!--<a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="0"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>-->
                        <a href="javascript:;" data-group="mediaImageGallery" data-url="<?=urlencode($URL_ARRAY['down'])?>" onClick="ShowImageGallery(this)"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="1"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a>

                        <?
                    }
                }
                ?>
            </td>
        </tr>
        <?
    }
    ?>
    <tr>
        <td class="TableContent" align="left">
            <?
            if($LAST_EDITOR!="")
            {
                ?>
                &nbsp;&nbsp;<u title="<?=_("部门：")?><?=$LAST_EDITOR_DEPTNAME?>" style="cursor:hand"><?=$LAST_EDITOR_NAME?></u>&nbsp;
                <?=_("最后编辑于：")?><i><?=$LAST_EDIT_TIME?></i>
                <?
            }
            ?>
        </td>
    </tr>
</table>
<?
if($ANONYMITY_YN=="2")
{
    echo "<br><div align=\"center\"><input type=\"button\" value=\""._("关闭")."\" class=\"BigButton\" onClick=\"TJF_window_close();\"></div>";
    exit;
}
?>
<br>
<table class="TableTop" width="100%" align="center">
    <tr>
        <td class="left"></td>
        <td class="center"><?=_("最新5条评论")?></td>
        <td class="right"></td>
    </tr>
</table>
<table class="TableBlock" width="100%" align="center">
    <?
    $COUNT=0;
    $query = "SELECT * from NEWS_COMMENT where NEWS_ID='$NEWS_ID' order by RE_TIME desc limit 0,5";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        $COMMENT_ID=$ROW["COMMENT_ID"];
        $PARENT_ID=$ROW["PARENT_ID"];
        $CONTENT=$ROW["CONTENT"];
        $RE_TIME=$ROW["RE_TIME"];
        $USER_ID=$ROW["USER_ID"];
        $NICK_NAME=$ROW["NICK_NAME"];

        $CONTENT=td_htmlspecialchars($CONTENT);
        $CONTENT=str_replace("\n","<br>",$CONTENT);

        $USER_NAME="";
        if($NICK_NAME=="")
        {
            $query = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$USER_ID'";
            $cursor1= exequery(TD::conn(),$query);
            if($ROW1=mysql_fetch_array($cursor1))
            {
                $DEPT_ID=$ROW1["DEPT_ID"];
                $DEPT_NAME=dept_long_name($DEPT_ID);
                $USER_NAME="<u title=\""._("部门：").$DEPT_NAME."\" style=\"cursor:hand\">".$ROW1["USER_NAME"]."</u>";
            }
        }
        else
        {
            $USER_NAME=$NICK_NAME;
        }

        $query = "SELECT CONTENT from NEWS_COMMENT where COMMENT_ID='$PARENT_ID'";
        $cursor1= exequery(TD::conn(),$query);
        if($ROW1=mysql_fetch_array($cursor1))
        {
            $CONTENT1=$ROW1["CONTENT"];
            $CONTENT1=str_replace("<","&lt",$CONTENT1);
            $CONTENT1=str_replace(">","&gt",$CONTENT1);
            $CONTENT1=stripslashes($CONTENT1);
            $CONTENT1=str_replace("\n","<br>",$CONTENT1);
        }

        $query = "SELECT count(*) from NEWS_COMMENT where PARENT_ID='$COMMENT_ID'";
        $cursor1= exequery(TD::conn(),$query);
        if($ROW1=mysql_fetch_array($cursor1))
            $RELAY_COUNT=$ROW1[0];
        ?>
        <tr>
            <td class="TableContent">
                &nbsp;&nbsp;<?=$USER_NAME?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=_("发表时间：")?><?=$RE_TIME?>
            </td>
        </tr>
        <tr height="40">
            <td class="TableData" >
                <?=$CONTENT?>
                <?
                if($PARENT_ID!=0)
                {
                    ?>
                    <br><hr width="95%">
                    <b>[<?=_("原贴")?>]</b><br>
                    <?=$CONTENT1?>
                    <?
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="TableControl" align="right">
                <a href="relay.php?COMMENT_ID=<?=$COMMENT_ID?>&NEWS_ID=<?=$NEWS_ID?>&start=<?=$start?>&MANAGE=<?=$MANAGE?>" style="text-decoration:underline"><?=_("回复本贴")?></a>&nbsp;&nbsp;&nbsp;
                <?
                //if(find_id($_SESSION["LOGIN_FUNC_STR"],"105") || $USER_ID==$_SESSION["LOGIN_USER_ID"])
                if($_SESSION["LOGIN_USER_PRIV"] == 1 || $PROVIDER == $_SESSION["LOGIN_USER_ID"] || $USER_ID == $_SESSION["LOGIN_USER_ID"])
                {
                    ?>
                    <a href="delete.php?COMMENT_ID=<?=$COMMENT_ID?>&NEWS_ID=<?=$NEWS_ID?>&start=<?=$start?>&MANAGE=<?=$MANAGE?>" style="text-decoration:underline"><?=_("删除")?></a>&nbsp;&nbsp;&nbsp;
                    <?
                }
                ?>
                <?=_("回复数：")?><?=$RELAY_COUNT?>&nbsp;&nbsp;
            </td>
        </tr>
        <?
    }

    if($COUNT==0)
    {
        ?>
        <tr height="40">
            <td class="TableData"><?=_("暂无评论")?></td>
        </tr>
        <?
    }
    ?>

</table>
<br>
<form action="submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
    <table class="TableTop" width="100%" align="center">
        <tr>
            <td class="left"></td>
            <td class="center"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"><?=_("发表评论：")?></td>
            <td class="right"></td>
        </tr>
    </table>
    <table class="TableBlock" width="100%" align="center">
        <tr>
            <td align="center" class="TableData"><?=_("内容：")?></td>
            <td class="TableData">
                <textarea cols="57" name="CONTENT" rows="5" class="BigInput" wrap="on"></textarea>
            </td>
        </tr>
        <tr>
            <td align="center" class="TableData"><?=_("署名：")?></td>
            <td class="TableData">
                <input type="radio" name="AUTHOR_NAME" value="USER_ID" <? if($ANONYMITY_YN=="0")echo "checked";?>>
                <input type="text"  name="USER_NAME" size="10" maxlength="25" class="BigStatic" value="<?=$_SESSION["LOGIN_USER_NAME"]?>" readonly>
                <?
                if($ANONYMITY_YN=="1")
                {
                    ?>
                    <input type="radio" name="AUTHOR_NAME" value="NICK_NAME" checked><?=_("昵称")?>
                    <input type="text" name="NICK_NAME" size="10" maxlength="25" class="BigInput" value="<?=$LOGIN_NICK_NAME?>">
                    <?
                }
                ?>
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td nowrap colspan="2">
                <input type="hidden" value="<?=$NEWS_ID?>" name="NEWS_ID">
                <input type="hidden" value="<?=$MANAGE?>" name="MANAGE">
                <input type="submit" value="<?=_("发表")?>" class="BigButton">&nbsp;&nbsp;
                <?
                if($ANONYMITY_YN!="2")
                {
                    ?>
                    <input type="button" value="<?=_("查看所有评论")?>" class="BigButton" onClick="javascript:window.location='re_news.php?NEWS_ID=<?=$NEWS_ID?>';">&nbsp;&nbsp;
                    <?
                }
                ?>
                <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="TJF_window_close();">
            </td>
        </tr>
    </table>
</form>
<script src="/module/ueditor/ueditor.parse.js"></script>
<script type="text/javascript">
    uParse('.rich-content', {
        rootPath: '/module/ueditor'
    })
</script>
</body>
</html>

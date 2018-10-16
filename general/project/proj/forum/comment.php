<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

if(!$PAGE_SIZE)
    $PAGE_SIZE = get_page_size("PROJECT", 5);
if(!isset($PAGE_START) || $PAGE_START=="")
    $PAGE_START=0;
$query1 = "SELECT  PROJ_NAME from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";

$cursor1= exequery(TD::conn(),$query1);
$row1 =mysql_fetch_array($cursor1);
$PROJ_NAME=$row1[0];

//修改事务提醒状态--yc
update_sms_status('42',$PROJ_ID);

$HTML_PAGE_TITLE =$PROJ_NAME."讨论区";

//$HTML_PAGE_TITLE = _("项目讨论区");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function delete_comment(MSG_ID)
{
    msg="<?=_("确定要删除该文章吗")?>?";
    if(window.confirm(msg))
    {
        URL="delete.php?PROJ_ID=<?=$PROJ_ID?>&PAGE_START=<?=$PAGE_START?>&MSG_ID="+MSG_ID;
        window.location=URL;
    }
}

function CheckForm()
{
    if(document.form1.SUBJECT.value == "" && document.form1.CONTENT.value == "")
    {
        alert("<?=_("标题或内容不能为空！")?>");
        return false;
    }

}
</script>


<body class="bodycolor">
<?
//----------权限控制---------
$query = "select * from PROJ_PROJECT WHERE PROJ_ID = '$PROJ_ID'" ;
$cursor = exequery(TD::conn(),$query);
if($arr = mysql_fetch_array($cursor))
{
    $message = 0;
    $PROJ_VIEWER = $arr['PROJ_VIEWER'];
    $PROJ_USER   = $arr['PROJ_USER'];
    $PROJ_OWNER  = $arr['PROJ_OWNER'];
    if(find_id($PROJ_VIEWER,$_SESSION['LOGIN_USER_ID']))
    {
        $message = 1;
    }
    if($PROJ_OWNER == $_SESSION['LOGIN_USER_ID'])
    {
        $message = 1;
    }
    $PROJ_USER = str_replace("|","",$PROJ_USER);
    if(find_id($PROJ_USER,$_SESSION['LOGIN_USER_ID']))
    {
        $message = 1;
    }
}
else
{
    Message(_("错误"),_("您无权查看！"));
    exit;
}

if($message==0)
{
    Message(_("错误"),_("您无权查看！"));
    exit;
}

if(!isset($PAGE_ITEMS))
{
    $PAGE_ITEMS=0;
    $query = "SELECT count(*) from PROJ_FORUM where PROJ_ID='$PROJ_ID' and (MSG_ID='$MSG_ID' OR PARENT='$MSG_ID')";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $PAGE_ITEMS=$ROW[0];
}
?>
<div>
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><?=$PROJ_NAME?>  &raquo; <a href="index.php?PROJ_ID=<?=$PROJ_ID?>"><?=_("项目讨论区")?> </a>
            </td>
            <td align="right" valign="bottom" class="small1">
                <?=page_bar($PAGE_START,$PAGE_ITEMS,$PAGE_SIZE,"PAGE_START")?>
            </td>
        </tr>
    </table>
</div>
<div id="comment">
    <div id="comment_body">
    <?
    $PARENT=$MSG_ID;

    //-------- 文章信息 ----------
    $query = "SELECT * from PROJ_FORUM where PROJ_ID='$PROJ_ID' AND (MSG_ID='$MSG_ID' OR PARENT='$MSG_ID') ORDER BY OLD_SUBMIT_TIME limit $PAGE_START,$PAGE_SIZE";
    $cursor = exequery(TD::conn(),$query);
    $COUNT_ALL=mysql_num_rows($cursor);
    $COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        $PROJ_ID = $ROW["PROJ_ID"];
        $MSG_ID = $ROW["MSG_ID"];
        $USER_ID = $ROW["USER_ID"];
        $SUBJECT = $ROW["SUBJECT"];
        $CONTENT = $ROW["CONTENT"];
        $SUBMIT_TIME = $ROW["SUBMIT_TIME"];
        $READEDER = $ROW["READEDER"];
        $OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];
        if($OLD_SUBMIT_TIME=="0000-00-00 00:00:00")
            $OLD_SUBMIT_TIME=$SUBMIT_TIME;
        $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
        $PARENT = $ROW["PARENT"];

        $SUBJECT=str_replace("<","&lt",$SUBJECT);
        $SUBJECT=str_replace(">","&gt",$SUBJECT);
        $SUBJECT=stripslashes($SUBJECT);

        $USER_NAME="";
        $query1 = "SELECT * from USER where USER_ID='$USER_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
            $USER_NAME=$ROW["USER_NAME"];

        //----------zfc--------------
        if($PARENT == 0)
            $MSG_ID_ROOT=$MSG_ID;
        else
            $MSG_ID_ROOT=$PARENT;

        $CONTENT=stripslashes($CONTENT);
        ?>
        <div class="comment_box">
            <?
            if($COUNT==1&&$PAGE_START==0)
            {
                ?>
                <div class="subject_header1">
                    <table width="100%" height="100%" border="0">
                        <tr>
                            <td style="font-weight: bold;font-size: 14px;"><?=_("主题：")?><?=$SUBJECT?></td>
                            <td style="padding-top:0px;padding-bottom:5px;" width="60"><input type="button" value="<?=_("返回")?>" class="SmallButton" onClick="location='index.php?PROJ_ID=<?=$PROJ_ID?>&start=<?=$start?>';"></td>
                        </tr>
                    </table>
                </div>
                <?
            }
            ?>
            <div class="comment_title">
                <table width="100%" height="100%">
                    <tr>
                        <td>
                            <!-------zfc-------->
                            <span class="flower_span"><?=($PAGE_START + $COUNT)?>#</span>
                <span class="info_span">
                <?
                echo _("作者：");
                ?>
                    <?=$USER_NAME?>&nbsp;&nbsp;<?=$OLD_SUBMIT_TIME?>
                </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="main_contment">
                <b><?=$SUBJECT?></b><br><br>
                <?=$CONTENT?>
                <?
                if($ATTACHMENT_NAME!="")
                {
                    ?>
                    <br><br><br><b><?=_("附件：")?></b>
                    <?
                    $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
                    $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
                    $IMG_TYPE_STR="gif,jpg,png,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,";

                    $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
                    for($I=0;$I<$ARRAY_COUNT;$I++)
                    {
                        if($ATTACHMENT_ID_ARRAY[$I]=="")
                            break;

                        echo "<br>";
                        echo attach_link($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I],1,1,1);

//           $MODULE=attach_sub_dir();
//           $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
//           $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
//           if($YM)
//              $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
//           $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);

                        $EXT_NAME=substr(strrchr($ATTACHMENT_NAME_ARRAY[$I],"."),1);
                        $EXT_NAME=strtolower($EXT_NAME);

                        if(find_id($IMG_TYPE_STR,$EXT_NAME))
                        {
                            $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
                            if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
                                $WIDTH=$IMG_ATTR[0];
                            if($WIDTH>600)
                                $WIDTH=600;
                            $URL_ARRAY = attach_url($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]);
                            ?>
                            <br>
                            <img src="<?=$URL_ARRAY["view"]?>" width='<?=$WIDTH?>' border=1 alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>">
                            <!--img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="0"  width="<?=$WIDTH?>" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"-->
                            <?
                        }
                    }//for
                }//if
                ?>
            </div>
            <div class="op_bar">
                <a href="edit.php?REPLY=1&PROJ_ID=<?=$PROJ_ID?>&MSG_ID=<?=$MSG_ID_ROOT?>&PAGE_START=<?=$PAGE_START?>" title="<?=_("回复此文章")?>"><?=_("回复")?></a>
                <?
                if($_SESSION["LOGIN_USER_ID"] == $USER_ID)
                {
                    ?>
                    <a href="edit.php?PROJ_ID=<?=$PROJ_ID?>&MSG_ID=<?=$MSG_ID?>&PAGE_START=<?=$PAGE_START?>" title="<?=_('编辑此文章')?>"><?=_("编辑")?></a>
                    <?
                }
                if($USER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1|| find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
                {   //-------------------zfc---------------------
                    if($COUNT_ALL > 1 && $COUNT != $PAGE_START + 1)
                    {
                        ?>
                        <a href="javascript:delete_comment('<?=$MSG_ID?>');" title="<?=_("删除此文章")?>"><?=_("删除")?></a>
                        <?
                    }
                    if($COUNT_ALL == 1 && $COUNT == 1)
                    {
                        ?>
                        <a href="javascript:delete_comment('<?=$MSG_ID?>');" title="<?=_("删除此文章")?>"><?=_("删除")?></a>
                        <?
                    }
                }
                ?>
            </div>
        </div>
        <?
    }//while

        ?>
    </div>
<br>
<div class="big3" align="left"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" align="absmiddle"><?=_("快速回复")?></div>
    <form name="form1" enctype="multipart/form-data" action="insert.php" onsubmit="return CheckForm();" method="post">
        <TABLE cellSpacing=1 cellPadding=0 width="98%" align=center class="TableList" border="0" >
            <TR>
                <TD align="center" width="10%" class="TableContent"><?=_("标题")?></TD>
                <TD align="left" class="TableData"><INPUT name="SUBJECT" tabIndex=1 size="40" class="BigInput"></TD>
            </TR>
            <TR>
                <TD vAlign="top" align="middle" width="10%" class="TableContent"><?=_("内容")?></TD>
                <TD vAlign="top" class="TableData">
                    <DIV align="left">
                        <textarea name="CONTENT" class="BigInput" cols="70" rows="7"></textarea>
                    </DIV>
                </TD>
            </TR>
            <TR>
                <TD align="center" width="10%" class="TableContent"><?=_("提醒项目其他人员")?></TD>
                <TD align="left" class="TableData"><?=sms_remind(42);?><span style="color:red"><?=_("默认会提醒1# 作者")?></span></TD>
            </TR>
            <TR class="TableControl">
                <TD colspan="2" align="center">
                    <input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>">
                    <input type="hidden" name="MSG_ID" value="<?=$MSG_ID_ROOT?>">
                    <input type="hidden" name="PAGE_START" value="<?=$PAGE_START?>">
                    <input type="hidden" name="REPLY" value="1">
                    <input type="submit" value="<?=_("发表帖子")?>" class="BigButton">&nbsp;&nbsp;
                    <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?PROJ_ID=<?=$PROJ_ID?>&start=<?=$start?>';">
                </TD>
                </TD>
            </TR>
        </TABLE>
    </form>
</div>
</body>
</html>
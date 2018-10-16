<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("查看公告通知");
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
</style>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>
<!--<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>-->
<script Language=JavaScript>
//如果从OA精灵打开，则最大化窗口
if(window.external && typeof window.external.OA_SMS != 'undefined')
{
    var h = Math.min(800, screen.availHeight - 180),
        w = Math.min(1280, screen.availWidth - 180);
    window.external.OA_SMS(w, h, "SET_SIZE");
}
function fw_notify(NOTIFY_ID)
{
    if(window.confirm("<?=_("确认要转发该公告通知吗？")?>"))
        window.location="../manage/fw.php?NOTIFY_ID="+NOTIFY_ID;
}

function open_notify1(NOTIFY_ID)
{
    URL="../show/read_notify.php?NOTIFY_ID="+NOTIFY_ID;
    myleft=(screen.availWidth-780)/2;
    window.close();
    window.open(URL,"read_notify"+NOTIFY_ID,"height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}
//多工作关闭
function close_this_new()
{
    TJF_window_close();
}
function get_keyword()
{
    document.getElementById("showKeyword").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("正在搜索本文关键词……")?>";
    var httpReq=getXMLHttpObj();
    var ID = document.getElementById("NOTIFYID").value;
    httpReq.open("GET","/inc/get_keyword.php?MODULE_ID=NOTIFY&ID="+ID);
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
function open_notify(KEYWORD,LIST)
{
    URL="/general/ipanel/keyword/index.php?KEYWORD="+KEYWORD+"&LIST="+LIST;
    myleft=(screen.availWidth-780)/2;
    mytop=100
    mywidth=780;
    myheight=500;

    window.open(URL,"KEYWORD","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function setExternalLinkTarget()
{
    var locationMatch = document.location.href.match(/^(http:\/\/)?([^\/]+)/i);
    var siteUrl = locationMatch[2];
    var allLinks = document.getElementsByTagName('a');
    var len = allLinks.length;
    if(len > 0)
    {
        var externalLink;
        for(i=0; i<len; i++)
        {
            externalLink = allLinks[i];
            if (externalLink.href.indexOf(siteUrl) == -1 && externalLink.href.substring(0,10)!='javascript' )
            {
                externalLink.setAttribute('target', '_blank');
            }
        }
    }
}
window.onload = function()
{
    setExternalLinkTarget();
}
</script>

<body class="bodycolor">

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
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
$CUR_TIME=date("Y-m-d H:i:s",time());
if($IS_SEARCH==1)
    $WHERE_STR="SEND_TIME_MIN=$SEND_TIME_MIN&SEND_TIME_MAX=$SEND_TIME_MAX&FORMAT=$FORMAT&TYPE_ID=$TYPE_ID&CONTENT=".urlencode($CONTENT)."&TO_ID=$TO_ID&SUBJECT=".urlencode($SUBJECT);
else
    $WHERE_STR="FIELD=$FIELD&bySendTime=$bySendTime&ASC_DESC=$ASC_DESC&TYPE=$TYPE&SEND_TIME=$SEND_TIME";
if($DO_ACTION!="")
{
    $WHERE_CLAUSE     = " WHERE PUBLISH='1' ";
    if($TYPE != "0" && $TYPE!=""){
        $WHERE_CLAUSE .= " AND TYPE_ID='$TYPE'";
    }

    $WHERE_CLAUSE  .= " AND BEGIN_DATE<='$CUR_DATE_U' and (END_DATE>='$CUR_DATE_U' or END_DATE='0') ";
    //$WHERE_CLAUSE  .= " AND NOT find_in_set('".$_SESSION["LOGIN_USER_ID"]."',READERS)" ;
    $WHERE_CLAUSE  .= " AND (TO_ID='ALL_DEPT'
                        OR find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)
                        OR find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)
                        OR find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) "
                        . priv_other_sql("PRIV_ID").dept_other_sql("TO_ID")." )";
    $query = "SELECT NOTIFY_ID from NOTIFY ".$WHERE_CLAUSE;
    $NOTIFY_ID_BIG=$NOTIFY_ID+50;
    if($NOTIFY_ID_SMALL>50)
        $NOTIFY_ID_SMALL=$NOTIFY_ID-50;
    else
        $NOTIFY_ID_SMALL=0;
    $query .= " AND NOTIFY_ID < '$NOTIFY_ID_BIG' AND NOTIFY_ID > $NOTIFY_ID_SMALL";
    if($IS_SEARCH==1)//从公告查询那里过来的
    {
        if($SEND_TIME_MIN!="")
            $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
        if($SEND_TIME_MAX!="")
            $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
        //------------------------ 生成条件字符串 ------------------
        $CONDITION_STR="";
        if($SUBJECT!="")
            $CONDITION_STR.=" and SUBJECT like '%".$SUBJECT."%'";
        if($CONTENT!="")
            $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
        if($SEND_TIME_MIN!="")
            $CONDITION_STR.=" and SEND_TIME>='$SEND_TIME_MIN'";
        if($SEND_TIME_MAX!="")
            $CONDITION_STR.=" and SEND_TIME<='$SEND_TIME_MAX'";
        if($FORMAT!="")
            $CONDITION_STR.=" and FORMAT='$FORMAT'";
        if($TYPE_ID!="")
            $CONDITION_STR.=" and TYPE_ID='$TYPE_ID'";
        if($TO_ID!="")
            $CONDITION_STR.=" and find_in_set(FROM_ID,'$TO_ID')";
        $query.=$CONDITION_STR." order by TOP desc, BEGIN_DATE desc, SEND_TIME desc";
    }
    else //从公告查看那里过来的
    {
        if($TYPE!="0" && $TYPE!="")
        {
            $query .= " and TYPE_ID='$TYPE'";
        }
        if($SEND_TIME!="")
        {
            $query .= " and SUBSTRING(SEND_TIME,1,10)='$SEND_TIME'";
        }
        if($FIELD=="")
        {
            if($bySendTime==1)
                $query .= " order by BEGIN_DATE desc,SEND_TIME desc";
            else
                $query .= " order by TOP desc,BEGIN_DATE desc,SEND_TIME desc";
        }
        else
        {
            $query .= " order by ".$FIELD;
            if($ASC_DESC=="1")
                $query .= " desc";
            else
                $query .= " asc";
        }
    }
    $cursor= exequery(TD::conn(),$query);
    $COUNT=0;
    $NOTIFY_ID_ARRAY=array();
    while($ROW=mysql_fetch_array($cursor))
    {
        $COUNT++;
        $NOTIFY_ID1=$ROW["NOTIFY_ID"];
        if($NOTIFY_ID1==$NOTIFY_ID) //找出当前的id的行数
        {
            $COUNT_CUR=$COUNT;
            $COUNT_COUNT=$COUNT+1;
        }
        $NOTIFY_ID_ARRAY[]=$NOTIFY_ID1;
        if($COUNT_COUNT!="" && $COUNT>$COUNT_COUNT)
            break;
    }
    if($DO_ACTION=="next") //下一篇
    {
        $NOTIFY_ID=$NOTIFY_ID_ARRAY[$COUNT_CUR];
        $MESSAGE=_("已没有下一篇公告");
    }
    else if($DO_ACTION=="pre") //上一篇
    {
        $NOTIFY_ID=$NOTIFY_ID_ARRAY[$COUNT_CUR-2];
        $MESSAGE=_("已没有上一篇公告");
    }
    if($NOTIFY_ID=="")
    {
        Message(_("提示"),$MESSAGE);
?>
        <center> <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="close_this_new();"> </center>
<?
        exit;
    }
}
$FONT_SIZE = get_font_size("FONT_NOTIFY", 12);
$POST_PRIV=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV");
$SYS_PARA_ARRAY=get_sys_para("NOTIFY_AUDITING_SINGLE,NOTIFY_AUDITING_SINGLE_NEW");
$NOTIFY_AUDITING_SINGLE=$SYS_PARA_ARRAY["NOTIFY_AUDITING_SINGLE"];//是否需要审批
$NOTIFY_AUDITING_SINGLE_NEW=$SYS_PARA_ARRAY["NOTIFY_AUDITING_SINGLE_NEW"];
if($NOTIFY_AUDITING_SINGLE=="")
    $NOTIFY_AUDITING_SINGLE=0;
$query = "SELECT * from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $FROM_DEPT=$ROW["FROM_DEPT"];
    $FROM_ID=$ROW["FROM_ID"];
    $TO_ID=$ROW["TO_ID"];
    $PRIV_ID=$ROW["PRIV_ID"];
    $USER_ID=$ROW["USER_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $READERS=$ROW["READERS"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $PRINT=$ROW["PRINT"];
    $DOWNLOAD=$ROW["DOWNLOAD"];
    $FORMAT=$ROW["FORMAT"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $AUDITER=$ROW["AUDITER"];
    $PUBLISH=$ROW["PUBLISH"];
    $END_DATE=$ROW["END_DATE"];
    $BEGIN_DATE=date("Y-m-d",$ROW["BEGIN_DATE"]);
    $AUDIT_DATE=$ROW["AUDIT_DATE"];
    $SEND_TIME=$ROW["SEND_TIME"];
    $LAST_EDITOR=$ROW["LAST_EDITOR"];
    $LAST_EDIT_TIME=$ROW["LAST_EDIT_TIME"];
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
    $KEYWORD=$ROW["KEYWORD"];
    $COMPRESS_CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
    if($COMPRESS_CONTENT!=""&&$FORMAT!="2")
        $CONTENT=$COMPRESS_CONTENT;
    else
        $CONTENT=$ROW["CONTENT"];
    $CONTENT = preg_replace("/<\!--.*?-->/si","",$CONTENT);
    if($CONTENT!="")
    {
        if($KEYWORD=="")
            $SHOW_KEYWORD="<span id='showKeyword' class='small1'><a href='javascript:get_keyword();' class='A1' id='B1'>"._("显示本文关键词")."</a></span>";
        else
        {
            $SHOW_KEYWORD=_("本文关键词：");
            $KEYWORD_ARRAY=explode(",",$KEYWORD);
            foreach($KEYWORD_ARRAY as $KID)
            {
                if($KID!="")
                    $SHOW_KEYWORD.="<a href=javascript:open_notify('".$KID."',1) id='B2'>".$KID."</a> ";
            }
        }
    }
    if($END_DATE=="0")
        $END_DATE="";
    if($END_DATE != "" && $END_DATE < $CUR_DATE_U  && $IS_MANAGE!="1" && $IS_SEARCH!="1")
    {
        Message(_("提示"),_("该公告通知已终止"));
        exit;
    }
    if($PRINT!="1"&&$DOWNLOAD!="1")
        $OP_FLAG="00";
    if($PRINT=="1"&&($DOWNLOAD=="1"||$DOWNLOAD==""))
        $OP_FLAG="11";
    if($OP_FLAG=="")
        $OP_FLAG=$DOWNLOAD.$PRINT;
    if($PUBLISH!="1")
    {
        if ($FROM_ID!=$_SESSION["LOGIN_USER_ID"] && $IS_MANAGE!=1)
        {
            Message(_("提示"),_("该公告通知未发布。"));
            exit;
        }
    }
    $READERS_NUMBER_OF = substr_count("$READERS",',');
    if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]) && $IS_MANAGE!=1)
    {
        //修改事务提醒状态--yc
        update_sms_status('1',$NOTIFY_ID);

        $READERS.=$_SESSION["LOGIN_USER_ID"].",";
        $query = "update NOTIFY set READERS='$READERS' where NOTIFY_ID='$NOTIFY_ID'";
        exequery(TD::conn(),$query);

        $query = "insert into APP_LOG(USER_ID,TIME,MODULE,OPP_ID,TYPE) values ('".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','4','$NOTIFY_ID','1')";
        exequery(TD::conn(),$query);
    }
    if($FORMAT=="2")
    {
        if(strpos($CONTENT, '/file_folder/read.php?')>0)
        {
            $CONTENT.="&BTN_CLOSE=1";
        }
        Header("location: $CONTENT");
        exit;
    }
    $IMG_TYPE_STR="gif,jpg,png,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,";
    if($_SESSION["LOGIN_USER_PRIV"]!=1 &&$POST_PRIV!=1 && $FROM_ID!=$_SESSION["LOGIN_USER_ID"] && $TO_ID!='ALL_DEPT' && !find_id($TO_ID,$_SESSION["LOGIN_DEPT_ID"]) && check_id($TO_ID,$_SESSION["LOGIN_DEPT_ID_OTHER"],true)=="" && !find_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV"]) && check_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV_OTHER"],true)=="" && !find_id($USER_ID,$_SESSION["LOGIN_USER_ID"]) && $IS_MANAGE!=1)
    {
        Message(_("提示"),_("您无权限查看该公告。"));
        exit;
    }
    $TR_HEIGHT=300;
    if($FORMAT=="1")
        $TR_HEIGHT=550;

    $SUBJECT=td_htmlspecialchars($SUBJECT);
    if($SUBJECT_COLOR!="")
        $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";

    if($CONTENT=="")
        $CONTENT="<br>"._("见附件");

    $TYPE_NAME=get_code_name($TYPE_ID,"NOTIFY");
    if($TYPE_NAME!="")
        $SUBJECT="<font color='".$SUBJECT_COLOR."'>[".$TYPE_NAME."]</font>".$SUBJECT;
    $RELEASENAME = GetReleaseName($TO_ID,$PRIV_ID,$USER_ID);
    $DEPARTMENT_ARRAY       = TD::get_cache('SYS_DEPARTMENT');
    $FROM_DEPT_NAME         = $DEPARTMENT_ARRAY[$FROM_DEPT]["DEPT_NAME"];
    $FROM_DEPT_LONG_NAME    = $DEPARTMENT_ARRAY[$FROM_DEPT]["DEPT_LONG_NAME"];
    $FROM_UID               = UserId2Uid($FROM_ID);
    $LAST_EDITOR_UID        = UserId2Uid($LAST_EDITOR);
    $USER_STR_UID           = $FROM_UID.",".$LAST_EDITOR_UID;
    $ROW                    = GetUserInfoByUID($USER_STR_UID,"USER_NAME,DEPT_ID");
    $FROM_NAME              = isset($ROW[$FROM_UID]["USER_NAME"])?$ROW[$FROM_UID]["USER_NAME"]:"";
    $DEPT_ID                = isset($ROW[$FROM_UID]["DEPT_ID"])?$ROW[$FROM_UID]["DEPT_ID"]:"";
    $DEPT_NAME              = dept_long_name($DEPT_ID);
    $LAST_EDITOR_NAME       = isset($ROW[$LAST_EDITOR_UID]["USER_NAME"])?$ROW[$LAST_EDITOR_UID]["USER_NAME"]:"";
    $LAST_EDITOR_DEPTID     = isset($ROW[$LAST_EDITOR_UID]["DEPT_ID"])?$ROW[$LAST_EDITOR_UID]["DEPT_ID"]:"";
    $LAST_EDITOR_DEPTNAME   = dept_long_name($LAST_EDITOR_DEPTID);
    if($FROM_NAME=="")
    {
        $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$FROM_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $FROM_NAME  = $ROW["USER_NAME"];
            $DEPT_ID    = $ROW["DEPT_ID"];
            $DEPT_NAME  = dept_long_name($DEPT_ID);
        }
        else
        {
            //$FROM_NAME=$FROM_ID;
            $DEPT_NAME=_("用户已删除");
        }
    }
    if($LAST_EDITOR_NAME=="")
    {
        $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$LAST_EDITOR'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $LAST_EDITOR_NAME=$ROW["USER_NAME"];
            $LAST_EDITOR_DEPTID=$ROW["DEPT_ID"];
            $LAST_EDITOR_DEPTNAME=dept_long_name($LAST_EDITOR_DEPTID);
        }
        else
        {
            //$LAST_EDITOR_NAME=$LAST_EDITOR;
            $LAST_EDITOR_DEPTNAME=_("用户已删除");
        }
    }

    if($AUDITER!="")
    {
        $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$AUDITER'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $AUDITER_NAME=$ROW["USER_NAME"];
            $AUDITER_DEPT_ID=$ROW["DEPT_ID"];
            $AUDITER_DEPT_NAME=dept_long_name($AUDITER_DEPT_ID);
        }
        else
        {
            $AUDITER_NAME=$AUDITER;
            $AUDITER_DEPT_NAME=_("用户已删除");
        }
    }
}
else
{
    Message(_("提示"),_("该公告已删除。"));
    exit;
}
?>

<table class="TableBlock no-top-border " width="90%" align="center" >
    <tr>
        <td  width="100%" style="padding:0px">
            <table class="TableTop" width="100%" cellpadding="0" >
                <tr>
                    <td class="center"  width="100%" <?if($SUBJECT_COLOR!="" && $SUBJECT_COLOR!="#000000"){?> style="background:url('<?=MYOA_STATIC_SERVER?>/static/images/table_top_bg_blue.jpg')  repeat-x;" <?}else{?> style="font-size:20px;"<?}?>><?=$SUBJECT?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="TableContent" align="left" id="ui_notify_show_preveiw">
            <span class="ui-notify-tips-preview">
                <!--公告类型-->
                <div>
                    <?=_("公告类型: ")?><span><?=empty($TYPE_NAME) ? '' : $TYPE_NAME?></span>
                </div>
                <!--发布人-->
                <div>
                    <?=_("发布人: ")?><span><?=empty($FROM_NAME) ? $DEPT_NAME : $FROM_NAME?></span>
                </div>
                <!--审核人-->
                <?if(!empty($AUDITER_NAME)){?>
                <div>
                   
                    <?=_("审核人: ")?><span><?=empty($AUDITER_NAME) ? $AUDITER_DEPT_NAME : $AUDITER_NAME?></span>
                </div>
                <?}?>
            </span>
            <span class="ui-notify-tips-preview" style="margin-left: 4%">
                <!--发布部门-->
                <div>
                    <?=_("发布部门：")?><span><?=empty($FROM_DEPT_NAME) ? $FROM_DEPT_LONG_NAME : $FROM_DEPT_NAME?></span>
                </div>
                <!--发布时间-->
                <div>
                    <?=_("发布时间：")?><span><?=empty($SEND_TIME) ? '' : $SEND_TIME?></span>
                </div>
                <!--审核时间-->
                <?if(!empty($AUDITER_NAME)){?>
                <div class="ui-notify-more-content">
                    <?=_("审核时间: ")?><span><?=empty($AUDIT_DATE) ? '' : $AUDIT_DATE?></span>
                </div>
                <?}?>
            </span>
            <span class="ui-notify-tips-preview" style="margin-left: 4%">
                <!--阅读量-->
                <div>
                    <?=_("阅读量: ")?><span><?=empty($READERS_NUMBER_OF) ? '0' : $READERS_NUMBER_OF?></span>
                </div>
                <!--发布范围-->
                <div>
                    <?empty($RELEASENAME) ? $RELEASENAME='' : $RELEASENAME;?>
                    <?=_("发布范围: ")?><span title="<?=_("发布范围:$RELEASENAME")?>"><?=empty($RELEASENAME) ? '' : substr($RELEASENAME,0,60)?></span>
                </div>
            </span>
        </td>
    </tr>
    <tr>
        <td  colspan="2"  valign="top"  class="rich-content content" style="height:<?=$TR_HEIGHT?>;font-size:<?=$FONT_SIZE?>pt;">
<?
$ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
$ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
$ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
if($FORMAT!="1")
{
    if($HEIGHT_KEYWORD!="")
    {
        $CONTENT=str_replace($HEIGHT_KEYWORD," <span style=\"background-color:yellow;\">".$HEIGHT_KEYWORD."</span>",$CONTENT);
        echo $CONTENT;
    }
    else
        echo $CONTENT;
}
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
            <iframe id=mhtFrame src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>&DIRECT_VIEW=1"   scrolling="auto"   width="100%" height="800"></iframe>
<?
            break;
        }
    }
}
?>
            <br><br><?=$SHOW_KEYWORD?>
        </td>
    </tr>
<?
$ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
if($ATTACH_ARRAY["NAME"]!="")
{
?>
    <tr>
        <td class="TableData"><?=_("附件文件")?>:<br><?=attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],1,1,$OP_FLAG,0,0,1,1)?>
        </td>
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
    //改为播放图片
    $URL_ARRAY = attach_url($ATTACHMENT_ID_ARRAY[$I], $ATTACHMENT_NAME_ARRAY[$I], $MODULE, $OTHER);
    if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
    {
        $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
        if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
            $WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
        else
            $WIDTH=100;
?>
            <!--  <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="0"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>-->
            <a href="javascript:;" data-group="mediaImageGallery" data-url="<?=urlencode($URL_ARRAY['down'])?>" onClick="ShowImageGallery(this)"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="1"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a>
<?
    }
}
?>
        </td>
    </tr>
<?
}
if($LAST_EDITOR!="")
{
?>
    <tr>
        <td class="TableContent" align="left">
            &nbsp;&nbsp;<span title="<?=_("部门：")?><?=$LAST_EDITOR_DEPTNAME?>"><?=$LAST_EDITOR_NAME?></span>&nbsp;
            <?=_("最后编辑于：")?><span><?=$LAST_EDIT_TIME?></span>
        </td>
    </tr>
<?
}

if(!isset($bpm) && !$run_id)
{
?>
    <tr align="center" class="TableControl">
        <td>
<?
if(find_id($_SESSION["LOGIN_FUNC_STR"],"24")&&($IS_MANAGE!="1"))
{
?>
            <input type="button" class="BigButton" value="<?=_("转发")?>" onClick="fw_notify('<?=$NOTIFY_ID?>');">&nbsp;
            <input type="button" value="<?=_("上一篇")?>" class="BigButton" onClick="javascript:window.location='read_notify.php?DO_ACTION=pre&IS_SEARCH=<?=$IS_SEARCH?>&NOTIFY_ID=<?=$NOTIFY_ID?>&<?=$WHERE_STR?>'" href="#" >&nbsp;&nbsp;
            <input type="button" value="<?=_("下一篇")?>" class="BigButton" onClick="javascript:window.location='read_notify.php?DO_ACTION=next&IS_SEARCH=<?=$IS_SEARCH?>&NOTIFY_ID=<?=$NOTIFY_ID?>&<?=$WHERE_STR?>'" href="#" >
<?
}
?>
            &nbsp;
            <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="close_this_new();">
            <input type="hidden" value="<?=$NOTIFY_ID?>" id="NOTIFYID">
        </td>
    </tr>
<?
}
?>
</table>
<script src="/module/ueditor/ueditor.parse.js"></script>
<script type="text/javascript">
uParse('.rich-content', {
    rootPath: '/module/ueditor'
})
</script>
</body>
</html>

<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");

if(!isset($TYPE))
    $TYPE="0";
if(!$PAGE_SIZE)
    $PAGE_SIZE = get_page_size("NOTIFY", 10);
if(!isset($start) || $start=="")
    $start=0;
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";

$SYS_PARA_ARRAY=get_sys_para("NOTIFY_TOP_DAYS");
$NOTIFY_TOP_DAYS=$SYS_PARA_ARRAY["NOTIFY_TOP_DAYS"];

$SEND_TIME = $SEND_TIME!='' ? date('Y-m-d', strtotime($SEND_TIME)) : '';
$TYPE = preg_match_all("/[^a-zA-Z0-9_\/]+/", $TYPE) ? '0' : $TYPE;
$PAGE_SIZE = intval($PAGE_SIZE);
$start = intval($start);

$HTML_PAGE_TITLE = _("按类型显示公告通知");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language=JavaScript>
    window.setTimeout('this.location.reload();',120000);

    function read_all()
    {
        msg='<?=_("确认要标记所有公告通知为已读吗？")?>';
        if(window.confirm(msg))
        {
            url="read_all.php";
            location=url;
        }
    }
</script>

<script>
function open_notify(NOTIFY_ID,WHERE_STR)
{
    URL="../show/read_notify.php?NOTIFY_ID="+NOTIFY_ID+"&"+WHERE_STR;
    myleft=(screen.availWidth-780)/2;
    window.open(URL,"read_notify"+NOTIFY_ID,"height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

function order_by(field,asc_desc)
{
    window.location="notify.php?start=<?=$start?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function selectOrder()
{
    if(document.getElementById("href_txt").innerText=="<?=_("按发布日期排序")?>")
    {
        document.getElementById("href_txt").innerText="<?=_("按是否置顶排序")?>";
        window.location="notify.php?start=<?=$start?>&TYPE=<?=$TYPE?>&bySendTime=1";
    }
    else
    {
        document.getElementById("href_txt").innerText="<?=_("按发布日期排序")?>";
        window.location="notify.php?start=<?=$start?>&TYPE=<?=$TYPE?>";
    }
}

function change_type(type,send_time)
{
    window.location="notify.php?start=<?=$start?>&SEND_TIME="+send_time+"&TYPE="+type+"&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
}

function change_time(send_time,type)
{
    window.location="notify.php?start=<?=$start?>&SEND_TIME="+send_time+"&TYPE="+type+"&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
}
</script>


<body class="bodycolor" bottommargin="4">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
$WHERE_STR="FIELD=$FIELD&bySendTime=$bySendTime&ASC_DESC=$ASC_DESC";
if($ASC_DESC	==	""){
    $ASC_DESC	=	"1";
}
$WHERE_CLAUSE 	= " WHERE PUBLISH='1' ";
if($TYPE != "0")
{
    $WHERE_CLAUSE .= " AND TYPE_ID='$TYPE'";
    $WHERE_STR.="&TYPE=$TYPE";
}
if($SEND_TIME!="")
{
    $WHERE_CLAUSE .= " AND SUBSTRING(SEND_TIME,1,10)='$SEND_TIME'";
    $WHERE_STR.="&SEND_TIME=$SEND_TIME";
}
$WHERE_CLAUSE  .= " AND BEGIN_DATE<='$CUR_DATE_U' and (END_DATE>='$CUR_DATE_U' or END_DATE='0') ";
$WHERE_CLAUSE  .= " AND (TO_ID='ALL_DEPT'
													 OR find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)
													 OR find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)
													 OR find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) "
    . priv_other_sql("PRIV_ID").dept_other_sql("TO_ID")." )";


$ORDER_CLAUSE 	= " ORDER BY ";
if($FIELD != "")
{
    if(stristr($FIELD,"/") || stristr($FIELD,")") || stristr($FIELD,"*") || stristr($FIELD,"(") || stristr($FIELD,"="))
    {
        Message(_("错误"),_("参数含有非法字符。"));
        exit;
    }

    $ORDER_CLAUSE .= $FIELD;
    if($ASC_DESC == "1")
    {
        $ORDER_CLAUSE .= " desc ";

    }
    else
    {
        $ORDER_CLAUSE .= " asc ";
    }
}
else
{
    if($bySendTime == 1)
    {
        $ORDER_CLAUSE .= " BEGIN_DATE desc,SEND_TIME desc ";
    }else
    {
        $ORDER_CLAUSE .= " TOP desc,BEGIN_DATE desc,SEND_TIME desc ";
    }
}
$LIMIT_CLAUSE = " LIMIT $start, $PAGE_SIZE ";
if(!isset($TOTAL_ITEMS))
{
    $query = "SELECT COUNT(NOTIFY_ID) from NOTIFY "
        . $WHERE_CLAUSE;
    $TOTAL_ITEMS =0;
    $cursor= exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
        $TOTAL_ITEMS = $ROW[0];
}
if($TOTAL_ITEMS==0)
{
    ?>
    <form name="form1">
        <table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
            <tr>
                <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("公告通知")?></span>&nbsp;
                    <select name="TYPE" class="BigSelect" onChange="change_type(this.value,SEND_TIME.value);">
                        <option value="0"<?if($TYPE=="0") echo " selected";?>><?=_("所有类型")?></option>
                        <?=code_list("NOTIFY",$TYPE)?>
                        <option value=""<?if($TYPE=="") echo " selected";?>><?=_("无类型")?></option>
                    </select>&nbsp;
                    <?=_("发布日期")?> <input type="text" name="SEND_TIME" size="12" maxlength="10" class="BigInput" value="<?=$SEND_TIME?>" onClick="WdatePicker()"/>&nbsp;<input type="button" class="SmallButton" value="<?=_("确定")?>" onClick="change_time(SEND_TIME.value,TYPE.value)">
                </td>
            </tr>
        </table>
    </form>
    <br>

    <?
    Message("",_("无公告通知"));
    exit;
}
?>
<form name="form1">
    <table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("公告通知")?></span>&nbsp;
                <select name="TYPE" class="BigSelect" onChange="change_type(this.value,SEND_TIME.value);">
                    <option value="0"<?if($TYPE=="0") echo " selected";?>><?=_("所有类型")?></option>
                    <?=code_list("NOTIFY",$TYPE)?>
                    <option value=""<?if($TYPE=="") echo " selected";?>><?=_("无类型")?></option>
                </select>&nbsp;
                <?=_("发布日期")?> <input type="text" name="SEND_TIME" size="12" maxlength="10" class="BigInput" value="<?=$SEND_TIME?>" onClick="WdatePicker()"/>&nbsp;<input type="button" class="SmallButton" value="<?=_("确定")?>" onClick="change_time(SEND_TIME.value,TYPE.value)">
            </td>
            <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
        </tr>
    </table>
</form>
<?
$LIST_CLAUSE = " NOTIFY_ID,FROM_ID,TO_ID,SUBJECT,READERS,FORMAT,TOP,TOP_DAYS,PRIV_ID,USER_ID,TYPE_ID,BEGIN_DATE,SUBJECT_COLOR ";
$query = " SELECT ".$LIST_CLAUSE." FROM NOTIFY "
    . $WHERE_CLAUSE
    . $ORDER_CLAUSE
    . $LIMIT_CLAUSE ;
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
//把查询结果存放到数组里，取一次数据缓存
$ROW_ARRAY=array();
$USER_ID_STR="";
$FROM_ID_STR="";
$TYPE_ID_STR="";
while($ROW1=mysql_fetch_array($cursor))
{
    $USER_ID=$ROW1["USER_ID"];
    $NOTIFY_ID=$ROW1["NOTIFY_ID"];
    $FROM_ID=$ROW1["FROM_ID"];
    $TO_ID=$ROW1["TO_ID"];
    $SUBJECT=$ROW1["SUBJECT"];
    $FORMAT=$ROW1["FORMAT"];
    $READERS=$ROW1["READERS"];
    $TOP=$ROW1["TOP"];
    $TOP_DAYS=$ROW1["TOP_DAYS"];
    $PRIV_ID=$ROW1["PRIV_ID"];
    $TYPE_ID=$ROW1["TYPE_ID"];
    $SUBJECT_COLOR=$ROW1["SUBJECT_COLOR"];
    $BEGIN_DATE=$ROW1["BEGIN_DATE"];
    $USER_ID_STR.=$USER_ID.",";
    $FROM_ID_STR.=$FROM_ID.",";
    if($TYPE_ID!="")
        $TYPE_ID_STR.="'".$TYPE_ID."',";
    $ROW_ARRAY[]= array("FROM_ID"=> $FROM_ID,"USER_ID"=> $USER_ID,"TO_ID"=> $TO_ID,"NOTIFY_ID"=> $NOTIFY_ID,"SUBJECT"=> $SUBJECT,"FORMAT"=> $FORMAT,"TOP"=> $TOP,"PRIV_ID"=> $PRIV_ID,"SUBJECT_COLOR"=> $SUBJECT_COLOR,"TYPE_ID"=> $TYPE_ID,"BEGIN_DATE"=> $BEGIN_DATE,"TOP_DAYS"=> $TOP_DAYS,"READERS"=> $READERS );
}
$USER_ID_STR=$USER_ID_STR.",".$FROM_ID_STR;
if(td_trim($USER_ID_STR)!="")
{
    $USER_UID=UserId2Uid($USER_ID_STR);
    if($USER_UID!="")
    {
        $USER_NAME_ARRAY=GetUserInfoByUID($USER_UID,"USER_NAME,DEPT_ID,AVATAR");
    }
}
//公告类型查询
$TYPE_ID_STR=td_trim($TYPE_ID_STR);
$TYPE_NAME_ARRAY=array();
$TYPE_NAME		=	"";
if($TYPE_ID_STR!="")
{
    $query1=	"select CODE_NAME,CODE_EXT,CODE_NO from SYS_CODE where PARENT_NO='NOTIFY' and CODE_NO in ($TYPE_ID_STR)";
    $cursor1= exequery(TD::conn(),$query1);
    while($ROW_CODE=mysql_fetch_array($cursor1))
    {
        $TYPE_ID_KEY=$ROW_CODE["CODE_NO"];
        $TYPE_NAME=$ROW_CODE["CODE_NAME"];
        $CODE_EXT=unserialize($ROW["CODE_EXT"]);
        if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
            $TYPE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
        $TYPE_NAME_ARRAY[$TYPE_ID_KEY]= $TYPE_NAME;
    }
}
//..............................................
if($ASC_DESC=="0")
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
?>
<table class="TableList" width="95%" align="center">
    <thead class="TableHeader">
    <td nowrap align="center"><?=_("发布人")?></td>
    <td nowrap align="center"><?=_("类型")?></td>
    <td nowrap align="center" onClick="order_by('SUBJECT','<?if($FIELD=="SUBJECT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("标题")?></u><?if($FIELD=="SUBJECT") echo $ORDER_IMG;?></td>
    <td nowrap align="center"><?=_("发布范围")?></td>
    <td nowrap align="center" onClick="order_by('SEND_TIME','<?if($FIELD=="SEND_TIME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("生效日期")?></u><?if($FIELD=="SEND_TIME"||$FIELD=="") echo $ORDER_IMG;?></td>
    </thead>
    <?
    $NOTIFY_COUNT=0;
    foreach ($ROW_ARRAY as $key=> $val){
        $ROW=$val;
        $NOTIFY_COUNT++;
        $NOTIFY_ID=$ROW["NOTIFY_ID"];
        $FROM_ID=$ROW["FROM_ID"];
        $TO_ID=$ROW["TO_ID"];
        $SUBJECT=$ROW["SUBJECT"];
        $READERS=$ROW["READERS"];
        $FORMAT=$ROW["FORMAT"];
        $TOP=$ROW["TOP"];
        $PRIV_ID=$ROW["PRIV_ID"];
        $USER_ID=$ROW["USER_ID"];
        $TYPE_ID=$ROW["TYPE_ID"];
        $TOP_DAYS=$ROW["TOP_DAYS"];
        $BEGIN_DATE=$ROW["BEGIN_DATE"];
        $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
        $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];

        /*$SUBJECT_TITLE="";
       if(strlen($SUBJECT) > 50)
       {
          $SUBJECT_TITLE=$SUBJECT;
          $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
       }
       $SUBJECT=td_htmlspecialchars($SUBJECT);
       $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);*/
        if($TOP==1)
        {
            if($NOTIFY_TOP_DAYS=="")
            {
                if($TOP_DAYS!=0)
                {
                    $DAYS = (strtotime($CUR_DATE) - strtotime($BEGIN_DATE))/ 86400;
                    if($DAYS>=$TOP_DAYS)
                    {
                        $Tquery="update NOTIFY set TOP='0' where NOTIFY_ID='$NOTIFY_ID'";
                        exequery(TD::conn(),$Tquery);
                        $TOP=0;
                    }
                }
            }
            else
            {
                if($TOP_DAYS>$NOTIFY_TOP_DAYS||$TOP_DAYS==0)
                    $TOP_DAYS=$NOTIFY_TOP_DAYS;
                $DAYS = (strtotime($CUR_DATE) - strtotime($BEGIN_DATE))/ 86400;
                if($DAYS>=$TOP_DAYS)
                {
                    $Tquery="update NOTIFY set TOP='0' where NOTIFY_ID='$NOTIFY_ID'";
                    exequery(TD::conn(),$Tquery);
                    $TOP=0;
                }
            }
        }
        $SUBJECT_TITLE="";
        if(strlen($SUBJECT) > 50)
        {
            $SUBJECT_TITLE=$SUBJECT;
            $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
        }
        $SUBJECT=td_htmlspecialchars($SUBJECT);
        $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);
        // 发布用户数据从缓存中获取
        $FROM_UID=UserId2Uid($FROM_ID);
        if($FROM_UID!="")
        {
            $FROM_NAME=$USER_NAME_ARRAY[$FROM_UID]["USER_NAME"];
            $DEPT_ID=$USER_NAME_ARRAY[$FROM_UID]["DEPT_ID"];
            $AVATAR=$USER_NAME_ARRAY[$FROM_UID]["AVATAR"];
            $DEPT_NAME	=	dept_long_name($DEPT_ID);
        }
        else //如果用缓存中取不到 数据库中查
        {
            $query1="select USER_NAME,DEPT_ID,AVATAR from USER where USER_ID='$FROM_ID'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
            {
                $FROM_NAME=$ROW["USER_NAME"];
                $DEPT_ID=$ROW["DEPT_ID"];
                $AVATAR		=	$ROW["AVATAR"];
                $DEPT_NAME	=	dept_long_name($DEPT_ID);
            }
            else
            {
                $FROM_NAME=$FROM_ID;
                $AVATAR		=	"";
                $DEPT_NAME	=	_("用户已删除");
            }
        }
        $TYPE_NAME=$TYPE_NAME_ARRAY[$TYPE_ID];
        $TO_NAME="";
        if($TO_ID=="ALL_DEPT")
            $TO_NAME=_("全体部门");
        else
            $TO_NAME=GetDeptNameById($TO_ID);
        $TO_NAME_TITLE="";
        $TO_NAME_STR="";
        if($TO_NAME!="")
        {
            $TO_NAME_TITLE.=_("部门：").$TO_NAME;
            $TO_NAME_STR.="<font color=#0000FF><b>"._("部门：")."</b></font>".csubstr(strip_tags($TO_NAME),0,20).(strlen($TO_NAME)>20?"...":"")."<br>";
        }
        $PRIV_NAME=GetPrivNameById($PRIV_ID);
        if($PRIV_NAME!="")
        {
            if($TO_NAME_TITLE!="")
                $TO_NAME_TITLE.="\n\n";
            $TO_NAME_TITLE.=_("角色：").$PRIV_NAME;
            $TO_NAME_STR.="<font color=#0000FF><b>"._("角色：")."</b></font>".csubstr(strip_tags($PRIV_NAME),0,20).(strlen($PRIV_NAME)>20?"...":"")."<br>";

        }
        $USER_NAME="";
        if($USER_ID!="")
        {
            $USER_UID=UserId2Uid($USER_ID);
            $UID_ARRAY=explode(',',$USER_UID);
            $USER_ID_ARRAY=explode(',',$USER_ID);
            for($I=0;$I<count($USER_ID_ARRAY);$I++)
            {
                if($USER_ID_ARRAY[$I]=="")
                    continue;
                if($UID_ARRAY[$I]!="" )
                {
                    $USER_NAME1=$USER_NAME_ARRAY[$UID_ARRAY[$I]]["USER_NAME"];
                }
                else  //如果缓存中取不到uid的值那根据user_id从数据库中获取
                {
                    $query1="select USER_NAME from USER where USER_ID='$USER_ID_ARRAY[$I]'";
                    $cursor1= exequery(TD::conn(),$query1);
                    if($ROW=mysql_fetch_array($cursor1))
                        $USER_NAME1=$ROW["USER_NAME"];
                    else
                        $USER_NAME1=$USER_ID_ARRAY[$I];
                }
                $USER_NAME.=$USER_NAME1.",";
            }
        }
        if($USER_NAME!="")
        {
            if(substr($USER_NAME,-1)==",")
                $USER_NAME=substr($USER_NAME,0,-1);
            if($TO_NAME_TITLE!="")
                $TO_NAME_TITLE.="\n\n";
            $TO_NAME_TITLE.=_("人员：").$USER_NAME;
            $TO_NAME_STR.="<font color=#0000FF><b>"._("人员：")."</b></font>".csubstr(strip_tags($USER_NAME),0,20).(strlen($USER_NAME)>20?"...":"")."<br>";

        }
        if($TOP=="1")
            $SUBJECT="<font color=red><b>".$SUBJECT."</b><img src='".MYOA_STATIC_SERVER."/static/images/arrow_up.gif' title='"._("已置顶")."'></font>";
        else
            $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
        if($NOTIFY_COUNT%2==1)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";
        ?>
        <tr class="<?=$TableLine?>">
            <td nowrap width="120"><img src="<?=avatar_path($AVATAR)?>" width="16" height="16"> <u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u></td>
            <td nowrap align="center"><?=$TYPE_NAME?></td>
            <td><a href="javascript:open_notify('<?=$NOTIFY_ID?>','<?=$WHERE_STR?>');" title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a>
                <?
                if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
                    echo "<img src='".MYOA_STATIC_SERVER."/static/images/new.gif' height=11 width=28>";
                ?>
            </td>
            <td style="cursor:hand" title="<?=$TO_NAME_TITLE?>"><?=$TO_NAME_STR?></td>
            <td nowrap align="center"><?=$BEGIN_DATE?></td>
        </tr>
        <?
    }

    ?>
</table>
<br>
<table class="TableBlock" width="95%" align="center">
    <tr>
        <td class="TableContent" nowrap align="center" width="80"><b><?=_("快捷操作：")?></b></td>
        <td class="TableControl" nowrap>&nbsp;
            <a href="javascript:read_all();" title="<?=_("标记所有公告通知为已读")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/email_open.gif" align="absMiddle" border="0"> <?=_("标记所有公告为已读")?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="javascript:selectOrder();" id='href_txt' title="<?=_("按生效日期排序")?>"><? if($bySendTime!=1) echo _("按生效日期排序");else echo _("按置顶排序");?><img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></a>&nbsp;&nbsp;</td>
    </tr>
</table>
</body>
</html>


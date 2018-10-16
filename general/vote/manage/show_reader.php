<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

//2013-04-11 主服务器查询
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";

function ParseItemName($ITEM_NAME,$ITEM_ID,$COUNT=1,$VOTE_ID)
{
    $POS=strpos($ITEM_NAME, "{");
    if($POS===false)
        return $ITEM_NAME;

    if(substr($ITEM_NAME, $POS, 6)=="{text}")
        return substr($ITEM_NAME, 0, $POS)."&nbsp;<a href=\"javascript:vote_data(".$VOTE_ID.",'INPUT_ITEM_".$ITEM_ID."_".$COUNT++."')\" style=\"text-decoration: underline;\">"._("详情")."</a>".ParseItemName(substr($ITEM_NAME, $POS+6),$ITEM_ID,$COUNT,$VOTE_ID);
    if(substr($ITEM_NAME, $POS, 8)=="{number}")
        return substr($ITEM_NAME, 0, $POS)."&nbsp;<a href=\"javascript:vote_data(".$VOTE_ID.",'INPUT_ITEM_".$ITEM_ID."_".$COUNT++."')\" style=\"text-decoration: underline;\">"._("详情")."</a>".ParseItemName(substr($ITEM_NAME, $POS+8),$ITEM_ID,$COUNT,$VOTE_ID);
    if(substr($ITEM_NAME, $POS, 10)=="{textarea}")
        return substr($ITEM_NAME, 0, $POS)."&nbsp;<a href=\"javascript:vote_data(".$VOTE_ID.",'INPUT_ITEM_".$ITEM_ID."_".$COUNT++."')\" style=\"text-decoration: underline;\">"._("详情")."</a>".ParseItemName(substr($ITEM_NAME, $POS+10),$ITEM_ID,$COUNT,$VOTE_ID);

    return substr($ITEM_NAME, 0, $POS+1).ParseItemName(substr($ITEM_NAME, $POS+1), $ITEM_ID,'',$VOTE_ID);
}


$HTML_PAGE_TITLE = _("投票结果");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<style>
    .TableList .TableHeader td, .TableList td.TableHeader{
        text-align:left;
    }
</style>
<script>
function vote_data(vote_id, item_pos)
{
    URL="vote_data.php?VOTE_ID="+vote_id+"#"+item_pos;
    myleft=(screen.availWidth-300)/2;
    window.open(URL,"vote_data","height=300,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=250,left="+myleft+",resizable=yes");
}
function view_all()
{
    var td=document.getElementsByTagName("TD");
    for(var i=0;i<td.length;i++)
    {
        if(td[i].title=="<?=_("点击显示所有投票人")?>")
            td[i].innerHTML=td[i].getAttribute("text");
    }
}

function send_vote_sms(user_id, subject, vote_id)
{
    if(!confirm("是否要提醒所有未投票人？"))
    {
        return false;
    }
    $.post('send_vote_sms.php', {user_id : user_id, subject : subject, vote_id : vote_id},
        function(data, status)
        {
            if("success"==status && "complete"==data)
            {
                alert('提醒成功！');
            }
            else
            {
                alert('提醒失败！');
            }
        });
}

function remind()
{
    var wid_res = $("#result").css("width");
    wid_res = wid_res.substring(0, wid_res.lastIndexOf("px"));
    var wid_rem = $("#remind").css("width");
    wid_rem = wid_rem.substring(0, wid_rem.lastIndexOf("px"));
    if((wid_body - wid_res) > (wid_body/2 + wid_rem/2 + 25))
    {
        $("#remind").css({"float" : "right", "margin-left" : "", "margin-right" : wid_body/2 - wid_rem/2 + "px"});
    }
    else
    {
        $("#remind").css({"float" : "", "margin-left" : "25px", "margin-right" : ""});
    }
}
$(window).resize(function()
{
    if(wid_body != document.body.offsetWidth)
    {
        wid_body = document.body.offsetWidth;
        remind();
    }
});
$(document).ready(function()
{
    wid_body = document.body.offsetWidth;
    remind();
});
</script>
<body class="bodycolor">
<?
$query = "SELECT SUBJECT,FROM_ID,TO_ID,PRIV_ID,USER_ID,READERS,BEGIN_DATE,PARENT_ID from vote_title where VOTE_ID ='$VOTE_ID '";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $SUBJECT    = $ROW["SUBJECT"];
    $FROM_ID    = $ROW["FROM_ID"];
    $TO_ID      = $ROW["TO_ID"];
    $PRIV_ID    = $ROW["PRIV_ID"];
    $USER_ID_TO = $ROW["USER_ID"];
    $READERS    = $ROW["READERS"];
    $BEGIN_DATE = $ROW["BEGIN_DATE"];
    $PARENT_ID  = $ROW["PARENT_ID"];
    $BEGIN_DATE = strtok($BEGIN_DATE," ");

    $query1 = "SELECT * from USER where USER_ID='$FROM_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
        $FROM_NAME=$ROW["USER_NAME"];
        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_NAME=dept_long_name($DEPT_ID);
    }

    $READ_COUNT = 0;

    $READER_ARRAY = explode(',',td_trim($READERS));
    $READER_ARRAY = array_unique($READER_ARRAY);
    foreach($READER_ARRAY as $READER)
    {
        if($READER != "")
            $READ_COUNT++;
    }

    $NOTREADER_ARRAY = array();
    $USER_ID_N = '';
    if($TO_ID!="ALL_DEPT")
    {
        $DEPT_OTHER_SQL = "";
        $TO_ID_ARRAY = explode(",", $TO_ID);
        foreach($TO_ID_ARRAY as $ID)
        {
            if($ID != "")
                $DEPT_OTHER_SQL .= " OR FIND_IN_SET('$ID',DEPT_ID_OTHER)";
        }
        $READER_LIST = '';
        $query ="SELECT USER_ID FROM USER WHERE (FIND_IN_SET(USER_PRIV,'$PRIV_ID') OR FIND_IN_SET(DEPT_ID,'$TO_ID')".$DEPT_OTHER_SQL." OR FIND_IN_SET(USER_ID,'$USER_ID_TO')) AND NOT FIND_IN_SET(USER_ID,'$READERS') AND NOT_LOGIN=0 AND DEPT_ID<>0";
    }
    else
    {
        $query ="SELECT USER_ID FROM USER WHERE NOT FIND_IN_SET(USER_ID,'$READERS') AND NOT_LOGIN=0 AND DEPT_ID<>0";
    }
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $NOTREADER_ARRAY[] = $ROW['USER_ID'];
        $USER_ID_N .= $ROW['USER_ID'].',';
    }
    $UN_READ_COUNT = count($NOTREADER_ARRAY);
}

if($TO_ID!="ALL_DEPT")
{
    $TOK=strtok($PRIV_ID,",");
    while($TOK!="")
    {
        $query1 = "SELECT DEPT_ID from USER where USER_PRIV='$TOK' and NOT_LOGIN=0 AND DEPT_ID<>0";
        $cursor1= exequery(TD::conn(),$query1);
        while($ROW=mysql_fetch_array($cursor1))
        {
            $DEPT_ID=$ROW["DEPT_ID"];
            if(!find_id($TO_ID,$DEPT_ID))
                $TO_ID.=$DEPT_ID.",";
        }

        $TOK=strtok(",");
    }
}

if($TO_ID!="ALL_DEPT")
{
    $TOK=strtok($USER_ID_TO,",");
    while($TOK!="")
    {
        $query1 = "SELECT DEPT_ID from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $DEPT_ID=$ROW["DEPT_ID"];
            if(!find_id($TO_ID,$DEPT_ID))
                $TO_ID.=$DEPT_ID.",";
        }
        $TOK=strtok(",");
    }
}



//------------------统计投票人数  end-------------------------------
$OLD_VOTE_ID=$VOTE_ID;
$query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
    $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $FROM_ID=$ROW["FROM_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $ANONYMITY=$ROW["ANONYMITY"];
    $READERS=$ROW["READERS"];
    $CONTENT=$ROW["CONTENT"];
    $CONTENT=td_htmlspecialchars($CONTENT);
    $CONTENT=nl2br($CONTENT);
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
}
else
    exit;

if($ATTACHMENT_NAME!="")
{
    $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
    $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
    for($I=0;$I<count($ATTACHMENT_ID_ARRAY);$I++)
    {
        if($ATTACHMENT_ID_ARRAY[$I]=="")
            continue;

        if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
            $IMAGE_COUNT++;
    }
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" align="center" class="small">
    <tr>
        <td><span id="result"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"/><span class="big3"> <?=_("投票结果")?> - <?=$SUBJECT?></span>
                <?
                if($PARENT_ID==0)
                {
                    $MSG = sprintf(_("(已投票：%d人，未投票：%d人)"),$READ_COUNT,$UN_READ_COUNT);
                    echo $MSG, '</span>';
                }
                if(0<$UN_READ_COUNT && ($FROM_ID==$_SESSION["LOGIN_USER_ID"] || 1==$_SESSION["LOGIN_SYS_ADMIN"]))
                {
                    if("," == $USER_ID_N[strlen($USER_ID_N) - 1])
                    {
                        $USER_ID_N = substr($USER_ID_N, 0, -1);
                    }
                    ?><span id="remind"><input type="button" class="BigButton" value="提醒" title="提醒未投票人" onClick="send_vote_sms('<?=urlencode($USER_ID_N)?>','<?=urlencode($SUBJECT)?>',<?=$VOTE_ID?>)"/></span><div style="clear:both"></div><br/><?
                }
                ?>
        </td>
        <br/>
    </tr>
    <tr>
        <td class="small1"><?=$CONTENT?></td>
    </tr>
</table>
<table class="TableList" width="100%" align="center">
<?
$query = "SELECT count(*) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
    $ITEM_COUNT=$ROW[0];

$POSTFIX = _("，");
if($ITEM_COUNT>0)
{
    $query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
    $cursor2= exequery(TD::conn(),$query);

    $ITEM_COUNT=0;
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $ITEM_COUNT++;
        $VOTE_ID=$ROW2["VOTE_ID"];
        $TYPE=$ROW2["TYPE"];
        $SUBJECT=$ITEM_COUNT._("、").$ROW2["SUBJECT"];
        ?>
        <tr class="TableHeader">
            <td colspan="4"><?=$SUBJECT?></td>
        </tr>
        <?
        if($TYPE==0 || $TYPE==1)
        {
            $query = "SELECT sum(VOTE_COUNT),max(VOTE_COUNT) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
            $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
            if($ROW=mysql_fetch_array($cursor))
            {
                $SUM_COUNT=$ROW[0];
                $MAX_COUNT=$ROW[1];
            }
            if($SUM_COUNT==0||$SUM_COUNT=="")
                $SUM_COUNT=1;
            if($MAX_COUNT==0||$MAX_COUNT=="")
                $MAX_COUNT=1;

            $query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID' order by ITEM_ID";
            $cursor= exequery(TD::conn(),$query);
            $NO=0;
            while($ROW=mysql_fetch_array($cursor))
            {
                $ITEM_ID=$ROW["ITEM_ID"];
                if($NO>=26)
                    $ITEM_NAME=chr($NO%26+65).floor($NO/26)._("、").$ROW["ITEM_NAME"];
                else
                    $ITEM_NAME=chr($NO%26+65)._("、").$ROW["ITEM_NAME"];
                $VOTE_COUNT=$ROW["VOTE_COUNT"];
                $VOTE_USER=$ROW["VOTE_USER"];
                $NO++;

                $ITEM_NAME=ParseItemName($ITEM_NAME,$ITEM_ID,'',$VOTE_ID);

                $VOTE_USER_NAME="";

                if($ANONYMITY=="0")
                {
                    $query = "SELECT * from USER where find_in_set(USER_ID,'$VOTE_USER')";

                    $cursor1= exequery(TD::conn(),$query);
                    while($ROW1=mysql_fetch_array($cursor1))
                        $VOTE_USER_NAME.=$ROW1["USER_NAME"].$POSTFIX;
                    $VOTE_USER_NAME=substr($VOTE_USER_NAME,0,-strlen($POSTFIX));
                }
                ?>
                <tr class="TableData">
                    <td width="35%">&nbsp;<?=$ITEM_NAME?></td>
                    <td width="239">
                        <table height="10" border="0" cellspacing="0" cellpadding="0" class="small">
                            <tr height="10">
                                <td width="<?=$VOTE_COUNT*200/$MAX_COUNT?>" style="background:url('<?=MYOA_STATIC_SERVER?>/static/images/vote_bg.gif') repeat-x;border:none;"></td>
                                <td width="30" style="border:none;"><?=round($VOTE_COUNT*100/$SUM_COUNT)?>%</td>
                            </tr>
                        </table>
                    </td>
                    <td align="center" width="50"><?=$VOTE_COUNT?><?=_("票")?></td>
                    <?
                    if($_SESSION["LOGIN_USER_PRIV"]==1 || $_SESSION["LOGIN_USER_ID"]==$FROM_ID)
                    {
                        ?>
                        <td style="cursor:pointer" text="<?=$VOTE_USER_NAME?>" title="<?=_("点击显示所有投票人")?>" onClick="view_all();"><?=csubstr($VOTE_USER_NAME,0,30).(strlen($VOTE_USER_NAME)>30?"...":"")?></td>
                        <?
                    }
                    ?>
                </tr>
                <?
            }
        }
        else
        {
            ?>
            <tr class="TableData">
                <td colspan="4">
                    <?
                    $query = "SELECT * from VOTE_DATA where ITEM_ID='$VOTE_ID' and FIELD_NAME='0'";
                    $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                        $USER_ID=$ROW["USER_ID"];
                        $FIELD_DATA=$ROW["FIELD_DATA"];
                        $USER_NAME="";
                        if($ANONYMITY==0)
                        {
                            $query = "SELECT * from USER where USER_ID='$USER_ID'";
                            $cursor1= exequery(TD::conn(),$query);
                            if($ROW1=mysql_fetch_array($cursor1))
                                $USER_NAME=$ROW1["USER_NAME"];
                        }

                        $FIELD_DATA=td_htmlspecialchars($FIELD_DATA);
                        $FIELD_DATA=nl2br($FIELD_DATA);

                        if($USER_NAME!="")
                            echo "<li>"._("【").$USER_NAME._("】");
                        echo $FIELD_DATA."</li><br>";
                    }
                    ?>
                </td>
            </tr>
            <?
        }
    }
}

$query = "SELECT * from VOTE_TITLE where PARENT_ID='$VOTE_ID' order by VOTE_NO,SEND_TIME";
$cursor2= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW2=mysql_fetch_array($cursor2))
{
    $ITEM_COUNT++;
    $VOTE_ID=$ROW2["VOTE_ID"];
    $TYPE=$ROW2["TYPE"];
    $SUBJECT=$ITEM_COUNT._("、").$ROW2["SUBJECT"];
    ?>
    <tr class="TableHeader">
        <td colspan="4"><?=$SUBJECT?></td>
    </tr>
    <?
    if($TYPE==0 || $TYPE==1)
    {
        $query = "SELECT sum(VOTE_COUNT),max(VOTE_COUNT) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
        $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
        if($ROW=mysql_fetch_array($cursor))
        {
            $SUM_COUNT=$ROW[0];
            $MAX_COUNT=$ROW[1];
        }
        if($SUM_COUNT==0||$SUM_COUNT=="")
            $SUM_COUNT=1;
        if($MAX_COUNT==0||$MAX_COUNT=="")
            $MAX_COUNT=1;

        $query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID' order by ITEM_ID";
        $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
        $NO=0;
        while($ROW=mysql_fetch_array($cursor))
        {
            $ITEM_ID=$ROW["ITEM_ID"];
            if($NO>=26)
                $ITEM_NAME=chr($NO%26+65).floor($NO/26)._("、").$ROW["ITEM_NAME"];
            else
                $ITEM_NAME=chr($NO%26+65)._("、").$ROW["ITEM_NAME"];
            $VOTE_COUNT=$ROW["VOTE_COUNT"];
            $VOTE_USER=$ROW["VOTE_USER"];
            $NO++;

            $ITEM_NAME=ParseItemName($ITEM_NAME,$ITEM_ID,'',$VOTE_ID);

            $VOTE_USER_NAME="";
            if($ANONYMITY=="0")
            {
                $query = "SELECT * from USER where find_in_set(USER_ID,'$VOTE_USER')";
                $cursor1= exequery(TD::conn(),$query);
                while($ROW1=mysql_fetch_array($cursor1))
                    $VOTE_USER_NAME.=$ROW1["USER_NAME"].$POSTFIX;
                $VOTE_USER_NAME=substr($VOTE_USER_NAME,0,-strlen($POSTFIX));
            }
            ?>
            <tr class="TableData">
                <td>&nbsp;<?=$ITEM_NAME?></td>
                <td width="240">
                    <table height="10" border="0" cellspacing="0" cellpadding="0" class="small">
                        <tr height="10">
                            <td width="<?=$VOTE_COUNT*200/$MAX_COUNT?>" style="background:url('<?=MYOA_STATIC_SERVER?>/static/images/vote_bg.gif') repeat-x;border:none; "></td>
                            <td width="30" style="border:none;"><?=round($VOTE_COUNT*100/$SUM_COUNT)?>%</td>
                        </tr>
                    </table>
                </td>
                <td align="right"><?=$VOTE_COUNT?><?=_("票")?></td>
                <?
                if($_SESSION["LOGIN_USER_PRIV"]==1 || $_SESSION["LOGIN_USER_ID"]==$FROM_ID)
                {
                    ?>
                    <td style="cursor:pointer" text="<?=$VOTE_USER_NAME?>" title="<?=_("点击显示所有投票人")?>" onClick="view_all();"><?=csubstr($VOTE_USER_NAME,0,30).(strlen($VOTE_USER_NAME)>30?"...":"")?></td>
                    <?
                }
                ?>
            </tr>
            <?
        }
    }
    else
    {
        ?>
        <tr class="TableData">
            <td colspan="4">
                <?
                $query = "SELECT * from VOTE_DATA where ITEM_ID='$VOTE_ID' and FIELD_NAME='0'";
                $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
                while($ROW=mysql_fetch_array($cursor))
                {
                    $USER_ID=$ROW["USER_ID"];
                    $FIELD_DATA=$ROW["FIELD_DATA"];
                    $USER_NAME="";
                    if($ANONYMITY==0)
                    {
                        $query = "SELECT * from USER where USER_ID='$USER_ID'";
                        $cursor1= exequery(TD::conn(),$query);
                        if($ROW1=mysql_fetch_array($cursor1))
                            $USER_NAME=$ROW1["USER_NAME"];
                    }

                    $FIELD_DATA=td_htmlspecialchars($FIELD_DATA);
                    $FIELD_DATA=nl2br($FIELD_DATA);

                    if($USER_NAME!="")
                        echo "<li>"._("【").$USER_NAME._("】");
                    echo $FIELD_DATA."</li><br>";
                }
                ?>
            </td>
        </tr>
        <?
    }
}
?>
<?
if($ATTACHMENT_NAME!="")
{
    ?>
    <tr>
        <td class="TableData" colspan="4"><?=_("附件文件")?>:<br><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1)?></td>
    </tr>
    <?
}

if($IMAGE_COUNT>0)
{
    ?>
    <tr class="TableData">
        <td colspan="4">
            <img src="<?=MYOA_STATIC_SERVER?>/static/images/image.gif" align="absmiddle" border="0">&nbsp;<?=_("附件图片")?>: <br><br>

            <?
            $MODULE=attach_sub_dir();
            for($I=0;$I<count($ATTACHMENT_ID_ARRAY);$I++)
            {
                if($ATTACHMENT_ID_ARRAY[$I]=="" || stristr($CONTENT, $ATTACHMENT_ID_ARRAY[$I]) || stristr($CONTENT, $ATTACHMENT_NAME_ARRAY[$I]))
                    continue;

                $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
                if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
                {
                    //$WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
                    $WIDTH=$IMG_ATTR[0];
                    $HEIGHT=$IMG_ATTR[1];
                }
                else
                {
                    $WIDTH=100;
                    $HEIGHT=100;
                }
                $ATTACHMENT_ID=$ATTACHMENT_ID_ARRAY[$I];
                $YM=substr($ATTACHMENT_ID,0,strpos($ATTACHMENT_ID,"_"));
                if($YM)
                    $ATTACHMENT_ID=substr($ATTACHMENT_ID,strpos($ATTACHMENT_ID,"_")+1);
                $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID,$ATTACHMENT_NAME_ARRAY[$I]);

                if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
                {
                    ?>
                <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="1"  width="<?=$WIDTH?>" height="<?=$HEIGHT?>" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>
                    <?
                }
            }
            ?>
        </td>
    </tr>
    <?
}
?>

</table>
<br>
<div style="text-align:center">
    <br>
    <input type="button" value="<?=_("打印")?>" class="BigButton" onClick="document.execCommand('Print');" title="<?=_("打印文件内容")?>">&nbsp;&nbsp;
    <input type="button" class="BigButton" value = "<?=_("导出")?>"  onClick="location.href='export/export.php?VOTE_ID=<?=$_GET[VOTE_ID]?>'" />&nbsp;&nbsp;
    <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();">
</div>
</body>
</html>
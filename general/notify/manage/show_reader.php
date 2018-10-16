<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("公告通知查阅情况");
include_once("inc/header.inc.php");
?>

<script>
function delete_reader(NOTIFY_ID)
{
    msg='<?=_("确认要清空查阅情况吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete_reader.php?NOTIFY_ID=" + NOTIFY_ID;
        window.location=URL;
    }
}

function SetNums()
{
    document.form1.action="noread_remind.php";
    document.form1.submit();
}
</script>

<?
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";   
$CUR_DATE=date("Y-m-d",time());

$query = "SELECT SUBJECT,FROM_ID,TO_ID,PRIV_ID,USER_ID,READERS,BEGIN_DATE,END_DATE from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $SUBJECT        = $ROW["SUBJECT"];
    $FROM_ID        = $ROW["FROM_ID"];
    $TO_ID          = $ROW["TO_ID"];
    $TO_ID_REAL     = $ROW["TO_ID"];
    $PRIV_ID        = $ROW["PRIV_ID"];
    $USER_ID_TO     = $ROW["USER_ID"];
    $READERS        = $ROW["READERS"];
    $BEGIN_DATE     = $ROW["BEGIN_DATE"];
    $END_DATE       = $ROW['END_DATE'];

    $TO_IDS         = $TO_ID;
    /*$END_DATE=strtok($END_DATE," ");
    if($END_DATE=="0000-00-00")
    $END_DATE="";*/
    if ($END_DATE=="0")
        $END_DATE="";
    else 
        $END_DATE=date("Y-m-d",$END_DATE);  
    
    if($END_DATE!="")
    {
        if(compare_date($CUR_DATE,$END_DATE)>0)
        {
            $NOTIFY_STATUS=3;
        }
    }         
    
    // $BEGIN_DATE=strtok($BEGIN_DATE," ");
    $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
    $FROM_UID=UserId2Uid($FROM_ID);
    if($FROM_UID!="")
    {
        $ROW=GetUserInfoByUID($FROM_UID,"USER_NAME,DEPT_ID");
        $FROM_NAME=$ROW["USER_NAME"];
        $DEPT_ID=$ROW["DEPT_ID"];
        $DEPT_NAME0=dept_long_name($DEPT_ID);
    }
    
    $WHERE_STR = "";
    
    if($TO_ID!="ALL_DEPT" && $TO_ID!="")
    {
        $WHERE_STR = $WHERE_STR ? $WHERE_STR." or " : "";
        $WHERE_STR .= " (find_in_set(DEPT_ID, '$TO_ID') ";
        
        $DEPT_ARR = explode(',', $TO_ID);
        foreach($DEPT_ARR as $k => $v)
        {
            if($v)
            {
                $WHERE_STR .= " or find_in_set('$v', DEPT_ID_OTHER) ";
            }
        }
        $WHERE_STR .= ") ";
    }
    
    if($TO_ID!="ALL_DEPT" && $PRIV_ID!="")
    {
        $WHERE_STR = $WHERE_STR ? $WHERE_STR." or " : "";
        $WHERE_STR .= " (find_in_set(USER_PRIV, '$PRIV_ID') ";
        
        $PRIV_ARR = explode(',', $PRIV_ID);
        foreach($PRIV_ARR as $k => $v)
        {
            if($v)
            {
                $WHERE_STR .= " or find_in_set('$v', USER_PRIV_OTHER) ";
            }
        }
        $WHERE_STR .= ") ";
    }
    
    if($TO_ID!="ALL_DEPT" && $USER_ID_TO!="")
    {
        $WHERE_STR = $WHERE_STR ? $WHERE_STR." or " : "";
        $WHERE_STR .= " find_in_set(USER_ID, '$USER_ID_TO') ";
    }
}

if(td_trim($READERS))
{
    $log_arr = array();
    $query = "select USER_ID,TIME from APP_LOG where MODULE ='4' and OPP_ID='$NOTIFY_ID' and find_in_set(USER_ID, '$READERS') and TYPE='1'";
    $cursor = exequery(TD::conn(), $query);
    while($row = mysql_fetch_array($cursor))
    {
        $USER_ID1 = $row['USER_ID'];
        $TIME1    = $row['TIME'];
        
        $log_arr[$USER_ID1] = $TIME1;
    }
}

if($TO_ID!="ALL_DEPT" && $WHERE_STR!="")
{
    $query = "select USER_ID,DEPT_ID,USER_NAME,DEPT_ID_OTHER from user where DEPT_ID!='0' and (NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0') and (".$WHERE_STR.") order by USER_PRIV_NO,USER_NO,USER_NAME";
}
else if($TO_ID=="ALL_DEPT")
{
    $query = "select USER_ID,DEPT_ID,USER_NAME,DEPT_ID_OTHER from user where DEPT_ID!='0' and  (NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0') order by USER_PRIV_NO,USER_NO,USER_NAME";
}

$DEPT_LIST_ARR = array();
$READ_COUNT = $UN_READ_COUNT = 0;
$un_userid_str = "";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor))
{
    $USER_ID0       = $row['USER_ID'];
    $DEPT_ID0       = $row['DEPT_ID'];
    $USER_NAME0     = $row['USER_NAME'];
    $DEPT_ID_OTHER0 = $row['DEPT_ID_OTHER'];
    $TO_ID .= $DEPT_ID0.",";
    
    if(find_id($READERS, $USER_ID0))
    {
        $READ_COUNT++;
        $USER_NAME0 = $log_arr[$USER_ID0] ? $USER_NAME0."(".$log_arr[$USER_ID0].")" : $USER_NAME0;
        
        $TMP_ARR = array();
        if($DEPT_ID_OTHER0)
        {
            $TMP_ARR = explode(',', $DEPT_ID0.",".$DEPT_ID_OTHER0);
            $TMP_ARR = array_unique($TMP_ARR);
            $TMP_ARR = array_filter($TMP_ARR);
            
            foreach($TMP_ARR as $k1 => $v1)
            {
                if($v1)
                {
                    $DEPT_LIST_ARR[$v1]['read'] .= $USER_NAME0.",";
                }
            }
        }
        else
        {
            $DEPT_LIST_ARR[$DEPT_ID0]['read'] .= $USER_NAME0.",";
        }
    }
    else
    {
        $un_userid_str .= $USER_ID0.",";
        $UN_READ_COUNT++;
        
        $TMP_ARR = array();
        if($DEPT_ID_OTHER0)
        {
            $TMP_ARR = explode(',', $DEPT_ID0.",".$DEPT_ID_OTHER0);
            $TMP_ARR = array_unique($TMP_ARR);
            $TMP_ARR = array_filter($TMP_ARR);
            
            foreach($TMP_ARR as $k1 => $v1)
            {
                if($v1)
                {
                    $DEPT_LIST_ARR[$v1]['unread'] .= $USER_NAME0.",";
                }
            }
        }
        else
        {
            $DEPT_LIST_ARR[$DEPT_ID0]['unread'] .= $USER_NAME0.",";
        }
    }
}

$DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');

$OPTION_TEXT2 = "";
foreach($DEPARTMENT_ARRAY as $k2 => $v2)
{
    if($TO_IDS=="ALL_DEPT" || find_id($TO_ID, $k2) || child_in_toid($TO_ID, $k2))
    {
        $DEPT_NAME = $v2["DEPT_NAME"];
        $DEPT_LINE = $v2["DEPT_LINE"];
        
        $UN_USER = $DEPT_LIST_ARR[$k2]['unread'];
        $USER_NAME_STR = $DEPT_LIST_ARR[$k2]['read'];
        
        $UN_USER = str_replace(",","，",td_trim($UN_USER));
        $USER_NAME_STR = str_replace(",","，",td_trim($USER_NAME_STR));
        
        $OPTION_TEXT2 .= "
      <tr class=TableData>
        <td class=\"TableContent\">".str_replace("│", "　", $DEPT_LINE).$DEPT_NAME."</td>
        <td style=\"\">".td_trim($USER_NAME_STR)."</td>
        <td style=\"\">".td_trim($UN_USER)."</td>
        
      </tr>";
    }
}
?>
<body class="bodycolor">
<form name="form1" method="post" >
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/notify.gif" align="absmiddle"><span class="big3"> <?=_("查阅情况")?></span>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
            <input type="button" value="<?=_("清空查阅情况")?>" class="SmallButton" onClick="delete_reader(<?=$NOTIFY_ID?>);">
<?
}
?>
        </td>
    </tr>
</table>

<?
if($OPTION_TEXT2=="")
    Message(_("提示"),_("无人查阅"));
else
{
?>
    <table class="TableTop" width="100%" align="center">
        <tr>
            <td class="left"></td>
            <td class="center"><?=$SUBJECT?></td>
            <td class="right"></td>
        </tr>
    </table>
    <table class="TableBlock" width="100%" align="center">
        <tr>
            <td class="TableContent" align="right" colspan="3">
                <u title="<?=_("部门：")?><?=$DEPT_NAME0?>" style="cursor:hand"><?=$FROM_NAME?></u>&nbsp;&nbsp;
                <?=_("发布于：")?><i><?=$BEGIN_DATE?></i>
            </td>
        </tr>
        <tr class="TableHeader">
            <td nowrap align="center"><?=_("部门/成员单位")?></td>
            <td nowrap align="center"><?=_("已读人员")?></td>
            <td nowrap align="center"><?=_("未读人员")?></td>
        </tr>
        <?=$OPTION_TEXT2?>
        <tfoot class="TableControl">
            <td nowrap align="center"><b><?=_("合计：")?></b></td>
            <td nowrap align="center"><b><?=$READ_COUNT?></b></td>
            <td nowrap align="center"><b><?=$UN_READ_COUNT?></b></td>
        </tfoot>
        
    </table>
<?
if($NOTIFY_STATUS!=3)
{
?>
    <table class="TableBlock"" width="100%" >
        <tr>
            <td><?=_("提醒未查阅人员:")?></td>
                <input type="hidden"  name="NOTIFY_ID" value="<?=$NOTIFY_ID?>">
                <input type="hidden"  name="un_userid_str" value="<?=$un_userid_str?>">
            <td align="left">
                <?=sms_remind(1);?>
            </td>
        </tr>
        <tr align="center">
            <td colspan="2" nowrap><input class="BigButton" onClick="SetNums()" type="button" value="<?=_("确定")?>"/>&nbsp;&nbsp;
                <input class="BigButton" onClick="window.close();" type="button" value="<?=_("关闭")?>"/>
            </td>
        </tr>
    </table>
<?
}
?>
</form>
  
<?
}
?>

</body>
</html>
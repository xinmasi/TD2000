<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("新闻查询");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<?
$ROW        = GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV,POST_DEPT,DEPT_ID");
$POST_PRIV  = $ROW["POST_PRIV"];
$DEPT_ID1   = td_trim($ROW["POST_DEPT"]);
$DEPT_ID2   = $ROW["DEPT_ID"];
//只查询主部门的成员
function GetMembersFromDept($DEPT_ID1,$QUERY_MASTER=""){
    $query  = "SELECT * from USER where find_in_set(DEPT_ID,'".$DEPT_ID1."')";
    $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
    while($ROW = mysql_fetch_array($cursor))
    {
        $USER_ID_STR .= $ROW["USER_ID"].",";
    }
    return $USER_ID_STR;
}
//$DEPT_MEMBERS ---> $DEPT_ID1 部门下所有成员
$DEPT_MEMBERS = GetMembersFromDept($DEPT_ID1,$QUERY_MASTER="");
 $CUR_DATE=date("Y-m-d",time());
  //----------- 合法性校验 ---------
  if($SEND_TIME_MIN!="")
  {
    $TIME_OK=is_date($SEND_TIME_MIN);

    if(!$TIME_OK)
    { Message(_("错误"),_("发布日期的格式不对，应形如 ").$CUR_DATE);
      Button_Back();
      exit;
    }
    $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
  }

  if($SEND_TIME_MAX!="")
  {
    $TIME_OK=is_date($SEND_TIME_MAX);

    if(!$TIME_OK)
    { Message(_("错误"),_("发布日期的格式不对，应形如 ").$CUR_DATE);
      Button_Back();
      exit;
    }
    $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
  }
	$time = date("Y-m-d H:i:s",time());
 //------------------------ 生成条件字符串 ------------------
 $CONDITION_STR="";
 if($SUBJECT!="")
    $CONDITION_STR.=" and SUBJECT like '%".$SUBJECT."%'";
 if($CONTENT!="")
    $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
 if($SEND_TIME_MIN!="")
    $CONDITION_STR.=" and NEWS_TIME>='$SEND_TIME_MIN'";
 if($SEND_TIME_MAX!="")
    $CONDITION_STR.=" and NEWS_TIME<='$SEND_TIME_MAX'";
 if($CLICK_COUNT_MIN!="")
    $CONDITION_STR.=" and CLICK_COUNT>=$CLICK_COUNT_MIN";
 if($CLICK_COUNT_MAX!="")
    $CONDITION_STR.=" and CLICK_COUNT<=$CLICK_COUNT_MAX";
 if($FORMAT!="")
    $CONDITION_STR.=" and FORMAT='$FORMAT'";
 if($TYPE_ID!="")
    $CONDITION_STR.=" and TYPE_ID='$TYPE_ID'";
 if($PUBLISH == '0')
    $CONDITION_STR.=" and ( PUBLISH='$PUBLISH' or '$time' < NEWS_TIME )";
 if($PUBLISH == '1')
    $CONDITION_STR.=" and ( PUBLISH='$PUBLISH' and '$time' > NEWS_TIME )";
 if($TOP!="")
 	$CONDITION_STR.=" and TOP='$TOP'";

//删除附件
if($_SESSION["LOGIN_USER_PRIV_TYPE"]=="1" || $POST_PRIV=="1")
{
    $query = "select ATTACHMENT_ID,ATTACHMENT_NAME from NEWS where ATTACHMENT_NAME!=''";
}else if ($POST_PRIV=="0" || $POST_PRIV=='6')
{
    $query="select ATTACHMENT_ID,ATTACHMENT_NAME from NEWS left join USER on NEWS.PROVIDER=USER.USER_ID where USER.DEPT_ID='$DEPT_ID2' and NEWS.ATTACHMENT_NAME!=''";
}
else if ($POST_PRIV=="2")
{
    $query="select ATTACHMENT_ID,ATTACHMENT_NAME from NEWS  WHERE (find_in_set(PROVIDER,'$DEPT_MEMBERS') or PROVIDER='".$_SESSION["LOGIN_USER_ID"]."') and ATTACHMENT_NAME!=''";
}
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}
if($_SESSION["LOGIN_USER_PRIV_TYPE"]=="1" || $POST_PRIV=="1")
{
    $query = "delete from NEWS where 1=1".$CONDITION_STR;
}else if ($POST_PRIV=="0" || $POST_PRIV=='6')
{
    $query_select  = "select NEWS_ID from NEWS left join USER on NEWS.PROVIDER=USER.USER_ID where USER.DEPT_ID='$DEPT_ID2'";
    $cursor_select = exequery(TD::conn(),$query_select);
    while($ROW = mysql_fetch_array($cursor_select)){
        $NEWS_ID_STR.= $ROW["NEWS_ID"].",";
    }
    if($NEWS_ID_STR != ""){
        $query = "delete from NEWS WHERE find_in_set(NEWS_ID,'$NEWS_ID_STR')".$CONDITION_STR;
    }else{
        $NEWS_COUNT = "0";
        Message("",sprintf(_("共删除%d条新闻"),$NEWS_COUNT));
        Button_Back();
        add_log(14,sprintf(_("查询删除%d新闻"),$NEWS_COUNT),$_SESSION["LOGIN_USER_ID"]);
        exit;
    }
}
else if ($POST_PRIV=="2")
{
    $query="delete from NEWS  WHERE (find_in_set(PROVIDER,'$DEPT_MEMBERS') or PROVIDER='".$_SESSION["LOGIN_USER_ID"]."')".$CONDITION_STR;
}
exequery(TD::conn(),$query);
$NEWS_COUNT=mysql_affected_rows();
Message("",sprintf(_("共删除%d条新闻"),$NEWS_COUNT));
Button_Back();

add_log(14,sprintf(_("查询删除%d新闻"),$NEWS_COUNT),$_SESSION["LOGIN_USER_ID"]);
?>
</body>

</html>

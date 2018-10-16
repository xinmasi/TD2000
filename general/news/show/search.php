<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("新闻查询");
include_once("inc/header.inc.php");
?>


<script>
function open_notify(NEWS_ID)
{
 URL="read_news.php?NEWS_ID="+NEWS_ID;
 myleft=(screen.availWidth-780)/2;
 window.open(URL,"read_news"+NEWS_ID,"height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

</script>

<body class="bodycolor">

<?
 $CUR_DATE=date("Y-m-d",time());
 $time = date("Y-m-d H:i:s",time());
  //----------- 合法性校验 ---------
  if($NEWS_TIME_MIN!="")
  {
    $TIME_OK=is_date($NEWS_TIME_MIN);

    if(!$TIME_OK)
    { Message(_("错误"),_("\"发布日期\"的格式不对，应形如 ").$CUR_DATE);
      Button_Back();
      exit;
    }
    $NEWS_TIME_MIN=$NEWS_TIME_MIN." 00:00:00";
  }

  if($NEWS_TIME_MAX!="")
  {
    $TIME_OK=is_date($NEWS_TIME_MAX);

    if(!$TIME_OK)
    { Message(_("错误"),_("\"发布日期\"的格式不对，应形如 ").$CUR_DATE);
      Button_Back();
      exit;
    }
    $NEWS_TIME_MAX=$NEWS_TIME_MAX." 23:59:59";
  }

 //------------------------ 生成条件字符串 ------------------
 $CONDITION_STR="";
 if($SUBJECT!="")
    $CONDITION_STR.=" and SUBJECT like '%".$SUBJECT."%'";
 if($CONTENT!="")
    $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
 if($NEWS_TIME_MIN!="")
    $CONDITION_STR.=" and NEWS_TIME>='$NEWS_TIME_MIN'";
 if($NEWS_TIME_MAX!=""){
	 $CONDITION_STR.=" and NEWS_TIME<='$NEWS_TIME_MAX'";
 }else{
	 $CONDITION_STR.=" and NEWS_TIME<='$time'";
 }
 if($FORMAT!="")
    $CONDITION_STR.=" and FORMAT='$FORMAT'";
 if($TYPE_ID!="")
    $CONDITION_STR.=" and TYPE_ID='$TYPE_ID'";
 if($TO_ID!="")
    $CONDITION_STR.=" and find_in_set(PROVIDER,'$TO_ID')";
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("新闻查询结果")?></span><br>
    </td>
  </tr>
</table>

<?
$query = "SELECT NEWS_ID,SUBJECT,NEWS_TIME,CLICK_COUNT,TYPE_ID,READERS,FORMAT,SUBJECT_COLOR from NEWS where PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID))";
$query.=$CONDITION_STR." order by TOP desc, NEWS_TIME desc";
$NEWS_COUNT=0;
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $NEWS_COUNT++;
    $NEWS_ID=$ROW["NEWS_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $NEWS_TIME=$ROW["NEWS_TIME"];
    $CLICK_COUNT=$ROW["CLICK_COUNT"];
    $READERS=$ROW["READERS"];
    $FORMAT=$ROW["FORMAT"];
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
	 
	 $SUBJECT_TITLE="";
    if(strlen($SUBJECT) > 50)
    {
       $SUBJECT_TITLE=$SUBJECT;
       $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
    }
    $SUBJECT=td_htmlspecialchars($SUBJECT);
    $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);
    $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
    $TYPE_NAME=get_code_name($TYPE_ID,"NEWS");

    $query1 = "SELECT count(*) from NEWS_COMMENT where NEWS_ID='$NEWS_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    $COMMENT_COUNT=0;
    if($ROW1=mysql_fetch_array($cursor1))
       $COMMENT_COUNT=$ROW1[0];

   if($NEWS_COUNT==1)
   {
?>
<table class="TableList" width="95%" align="center">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("标题")?></td>
      <td nowrap align="center"><?=_("类型")?></td>
      <td nowrap align="center"><?=_("发布时间")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("点击次数")?></td>
      <td nowrap align="center"><?=_("评论(条)")?></td>
   </thead>
<?
   }
   if($NEWS_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td><a href=javascript:open_notify('<?=$NEWS_ID?>'); title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a>
<?
      if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
         echo "<img src='".MYOA_STATIC_SERVER."/static/images/new.gif' height=11 width=28>";
?>
      </td>
      <td align="center"><?=$TYPE_NAME?></td>
      <td nowrap align="center"><?=$NEWS_TIME?></td>
      <td nowrap align="center"><?=$CLICK_COUNT?></td>
      <td nowrap align="center"><?=$COMMENT_COUNT?></td>
    </tr>
<?
}

if($NEWS_COUNT==0)
{
   Message("",_("无符合条件的新闻"));
   Button_Back();
   exit;
}
else
{
?>
</table>
<?
Button_Back();
}

?>
</body>

</html>

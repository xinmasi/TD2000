<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("新闻查询");
include_once("inc/header.inc.php");
ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/msword");
$filename = _("新闻");
Header("Content-Disposition: attachment; ".get_attachment_filename($filename.".doc"));
?>
<body topmargin="5">
  <table border="1" cellspacing="1" width="95%" class="small" cellpadding="3">
      <tr style="BACKGROUND: #D3E5FA; color: #000000; font-weight: bold;">
        <td nowrap align="center"><?=_("发布日期")?></td>
        <td nowrap align="center"><?=_("新闻类型")?></td>
        <td nowrap align="center"><?=_("新闻标题")?></td>
        <td nowrap align="center"><?=_("新闻内容")?></td>
        <td nowrap align="center"><?=_("评论")?></td>
      </tr>
<?
$WHERE_STR="";
$time = date("Y-m-d H:i:s",time());
if($NEWS_TIME_MIN!="")
{
	 $NEWS_TIME_MIN.=" 00:00:00";
   $WHERE_STR = " and NEWS_TIME >= '$NEWS_TIME_MIN'";
}
if($NEWS_TIME_MAX!="")
{
   $NEWS_TIME_MAX.=" 23:59:59";
   $WHERE_STR .= " and NEWS_TIME <= '$NEWS_TIME_MAX'";
}else{
   $WHERE_STR .= " and NEWS_TIME <= '$time'"; 
}
if($SUBJECT!="")
   $WHERE_STR .= " and SUBJECT like '%".$SUBJECT."%'";
if($CONTENT!="")
    $WHERE_STR.=" and CONTENT like '%".$CONTENT."%'";
if($FORMAT!="")
    $WHERE_STR.=" and FORMAT='$FORMAT'";
 if($TYPE_ID!="")
    $WHERE_STR.=" and TYPE_ID='$TYPE_ID'";
 if($TO_ID!="")
    $WHERE_STR.=" and find_in_set(PROVIDER,'$TO_ID')";    

$query = "SELECT NEWS_ID,SUBJECT,NEWS_TIME,CLICK_COUNT,TYPE_ID,READERS,FORMAT,SUBJECT_COLOR,COMPRESS_CONTENT,CONTENT from NEWS where PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID))";
$query.=$WHERE_STR." order by NEWS_TIME desc";

$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $NEWS_ID=$ROW["NEWS_ID"];
   $NEWS_TIME=$ROW["NEWS_TIME"];
   $NEWS_TIME=strtok($NEWS_TIME," ");
   $TYPE_ID=$ROW["TYPE_ID"];
   $SUBJECT=$ROW["SUBJECT"];
   $NOTAGS_CONTENT=$ROW["CONTENT"];    
   $CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]); 
   if($CONTENT=="")   
      $CONTENT=$NOTAGS_CONTENT;    
   
   $CONTENT_COMENT="";
   $query = "SELECT USER.USER_NAME,NEWS_COMMENT.RE_TIME,NEWS_COMMENT.CONTENT from NEWS_COMMENT,USER where NEWS_COMMENT.NEWS_ID='$NEWS_ID' and NEWS_COMMENT.USER_ID = USER.USER_ID order by NEWS_COMMENT.RE_TIME desc";
   $cursor1= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor1))
   {
      $USER_NAME=$ROW["USER_NAME"];     
      $RE_TIME=$ROW["RE_TIME"];
      $CONTENT1=$ROW["CONTENT"];
      $CONTENT1=str_replace("\"","'",$CONTENT1);
      
      $CONTENT_COMENT.="<font color=\"#0000FF\">".$USER_NAME."&nbsp;&nbsp;".$RE_TIME."</font><br>".$CONTENT1."<br><br>";
   }

   $NEWS_TYPE_DESC=get_code_name($TYPE_ID,"NEWS");
?>
   <tr style="BACKGROUND: #FFFFFF;">
     <td nowrap align="center" width="100"><?=$NEWS_TIME?></td>
     <td nowrap align="center" width="100"><?=$NEWS_TYPE_DESC?></td>
     <td><?=$SUBJECT?></td>
     <td><?=$CONTENT?></td>
     <td><?=$CONTENT_COMENT?></td>
   </tr>
<?
}
?>
  </table>

</body>
</html>
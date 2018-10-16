<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

 ob_end_clean();
 Header("Cache-control: private");
 Header("Content-type: application/msword");
 Header("Content-Disposition: attachment; ".get_attachment_filename($_SESSION["LOGIN_USER_ID"].".doc"));

$HTML_PAGE_TITLE = _("工作日志查询");
include_once("inc/header.inc.php");
?>

<body topmargin="5">
  <table border="1" cellspacing="1" width="95%" class="small" cellpadding="3">
      <tr style="BACKGROUND: #D3E5FA; color: #000000; font-weight: bold;">
        <td nowrap align="center"><?=_("日期")?></td>
        <td nowrap align="center"><?=_("日志类型")?></td>
        <td nowrap align="center"><?=_("日志标题")?></td>
        <td nowrap align="center"><?=_("日志内容")?></td>
        <td nowrap align="center"><?=_("点评")?></td>
        <td nowrap align="center"><?=_("附件名称")?></td>
      </tr>
<?
if($DIARY_COPY_TIME!="")
{
   $DIARY_TABLE_NAME=TD::$_arr_db_master['db_archive'].".DIARY". $DIARY_COPY_TIME;
   $DIARY_COMMENT_TABLE_NAME=TD::$_arr_db_master['db_archive'].".DIARY_COMMENT". $DIARY_COPY_TIME;  
}
else
{
   $DIARY_TABLE_NAME="DIARY";
   $DIARY_COMMENT_TABLE_NAME="DIARY_COMMENT";   
}

$WHERE_STR="";
if($BEGIN_DATE!="")
{
	 $BEGIN_DATE.=" 00:00:00";
   $WHERE_STR = " and DIA_DATE >= '$BEGIN_DATE'";
}
if($END_DATE!="")
{
   $END_DATE.=" 23:59:59";
   $WHERE_STR .= " and DIA_DATE <= '$END_DATE'";
}
if($SUBJECT!="")
   $WHERE_STR .= " and SUBJECT like '%$SUBJECT%'";
if($ATTACHMENT_NAME!="")
   $WHERE_STR .= " and ATTACHMENT_NAME like '%$ATTACHMENT_NAME%'";
if($KEY1!="" || $KEY2!="" || $KEY3!="")
{
	 if($KEY1=="")
	    $KEY1="!@#$%^&*()__)(*&^%$#@";
	 if($KEY2=="")
	    $KEY2="!@#$%^&*()__)(*&^%$#@";
	 if($KEY3=="")
	    $KEY3="!@#$%^&*()__)(*&^%$#@";		    	    
	 $WHERE_STR .= " and (CONTENT like '%$KEY1%' or CONTENT like '%$KEY2%' or CONTENT like '%$KEY3%')";
}    

$query = "SELECT DIA_ID,DIA_DATE,SUBJECT,DIA_TYPE,ATTACHMENT_ID,ATTACHMENT_NAME,COMPRESS_CONTENT,CONTENT from ".$DIARY_TABLE_NAME." where USER_ID='$USER_ID' and DIA_TYPE!='2' ".$WHERE_STR." order by DIA_DATE desc,DIA_ID desc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $DIA_ID=$ROW["DIA_ID"];
   $DIA_DATE=$ROW["DIA_DATE"];
   $DIA_DATE=strtok($DIA_DATE," ");
   $DIA_TYPE=$ROW["DIA_TYPE"];
   $SUBJECT=$ROW["SUBJECT"];
   $NOTAGS_CONTENT=$ROW["CONTENT"];   
   $CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
   if($CONTENT=="")   
      $CONTENT=$NOTAGS_CONTENT;    
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   
   $CONTENT_COMENT="";
   $query = "SELECT USER.USER_NAME,".$DIARY_COMMENT_TABLE_NAME.".SEND_TIME,".$DIARY_COMMENT_TABLE_NAME.".CONTENT from ".$DIARY_COMMENT_TABLE_NAME.",USER where ".$DIARY_COMMENT_TABLE_NAME.".DIA_ID='$DIA_ID' and ".$DIARY_COMMENT_TABLE_NAME.".USER_ID = USER.USER_ID order by ".$DIARY_COMMENT_TABLE_NAME.".SEND_TIME desc";
   $cursor1= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor1))
   {
      $USER_NAME=$ROW["USER_NAME"];     
      $SEND_TIME=$ROW["SEND_TIME"];
      $CONTENT1=$ROW["CONTENT"];
      $CONTENT1=str_replace("\"","'",$CONTENT1);
      
      $CONTENT_COMENT.="<font color=\"#0000FF\">".$USER_NAME."&nbsp;&nbsp;".$SEND_TIME."</font><br>".$CONTENT1."<br><br>";
   }

   $DIA_TYPE_DESC=get_code_name($DIA_TYPE,"DIARY_TYPE");
?>
   <tr style="BACKGROUND: #FFFFFF;">
     <td nowrap align="center" width="100"><?=$DIA_DATE?></td>
     <td nowrap align="center" width="100"><?=$DIA_TYPE_DESC?></td>
     <td><?=$SUBJECT?></td>
     <td><?=$CONTENT?></td>
     <td><?=$CONTENT_COMENT?></td>
     <td>
<?
  $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
  $ARRAY_COUNT=sizeof($ATTACHMENT_NAME_ARRAY);
  for($I=0;$I<$ARRAY_COUNT;$I++)
  {
     if($ATTACHMENT_NAME_ARRAY[$I]=="")
        continue;
     echo $ATTACHMENT_NAME_ARRAY[$I]."<br>";
  }
?>
     </td>
   </tr>
<?
}
?>
  </table>

</body>
</html>
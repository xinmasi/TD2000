<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("check_priv.inc.php");

 ob_end_clean();
 Header("Cache-control: private");
 Header("Content-type: application/msword");
 Header("Content-Disposition: attachment; ".get_attachment_filename(_("工作日志").".doc"));

$HTML_PAGE_TITLE = _("工作日志查询");
include_once("inc/header.inc.php");
?>

<body topmargin="5">
  <table border="1" cellspacing="1" width="95%" class="small" cellpadding="3"> 
      <tr style="BACKGROUND: #D3E5FA; color: #000000; font-weight: bold;">
        <td nowrap align="center"><?=_("日期")?></td>
        <td nowrap align="center"><?=_("写日志时间")?></td>        
        <td nowrap align="center"><?=_("日志类型")?></td>
        <td nowrap align="center"><?=_("日志标题")?></td>
        <td nowrap align="center"><?=_("日志内容")?></td>
        <td nowrap align="center"><?=_("点评")?></td>
        <td nowrap align="center"><?=_("附件名称")?></td>
      </tr>
<?
//$WHERE_STR="";

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
if($BEGIN_DATE!="")
{
   $URL_BEGIN_DATE = $BEGIN_DATE;
   $BEGIN_DATE.=" 00:00:00";
   $WHERE_STR .= " and DIA_DATE >= '$BEGIN_DATE'";
}
if($END_DATE!="")
{
   $URL_END_DATE = $END_DATE;  
   $END_DATE.=" 23:59:59";
   $WHERE_STR .= " and DIA_DATE <= '$END_DATE'";
}
if($SUBJECT!="")
{
   $URL_SUBJECT = $SUBJECT;
   $WHERE_STR .= " and SUBJECT like '%$SUBJECT%'";
}
if($TO_ID1!="")
{
   $WHERE_STR .= " and find_in_set(".$DIARY_TABLE_NAME.".USER_ID,'$TO_ID1')";
}
if($TO_ID!="" && $TO_ID!="ALL_DEPT")
{
   $TO_ID .= "," . GetChildDeptId($TO_ID);
   if(substr($TO_ID,-1,1)==",")
      $TO_ID=substr($TO_ID,0,-1);
   $TO_ID = str_replace(",,", ",", $TO_ID);

   $WHERE_STR .= " and b.DEPT_ID in ($TO_ID)";
}

if($PRIV_ID!="")
{
   if(substr($PRIV_ID,-1,1)==",")
      $PRIV_ID=substr($PRIV_ID,0,-1);   
   $WHERE_STR .= " and b.USER_PRIV in ($PRIV_ID)";
}

if($COPYS_TO_ID!="")
{
   if(substr($COPYS_TO_ID,-1,1)==",")
      $COPYS_TO_ID=substr($COPYS_TO_ID,0,-1);  
   $WHERE_STR .= " and find_in_set(".$DIARY_TABLE_NAME.".USER_ID ,'$COPYS_TO_ID')";
}

if($DIA_TYPE=="")
   $WHERE_STR .= " and DIA_TYPE!='2'";
else
   $WHERE_STR .= " and DIA_TYPE='$DIA_TYPE'";

$query = "SELECT ".$DIARY_TABLE_NAME.".DIA_ID,".$DIARY_TABLE_NAME.".DIA_DATE,".$DIARY_TABLE_NAME.".DIA_TYPE,".$DIARY_TABLE_NAME.".SUBJECT,".$DIARY_TABLE_NAME.".COMPRESS_CONTENT,".$DIARY_TABLE_NAME.".CONTENT,".$DIARY_TABLE_NAME.".ATTACHMENT_ID,".$DIARY_TABLE_NAME.".ATTACHMENT_NAME,".$DIARY_TABLE_NAME.".LAST_COMMENT_TIME,".$DIARY_TABLE_NAME.".USER_ID,".$DIARY_TABLE_NAME.".DIA_TIME from ".$DIARY_TABLE_NAME." left join USER b on b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1 ".$WHERE_STR;
$query .= " order by DIA_DATE desc,DIA_ID desc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $DIA_ID=$ROW["DIA_ID"];
   $DIA_DATE=$ROW["DIA_DATE"];
   $DIA_TIME=$ROW["DIA_TIME"]; 
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
   $query = "SELECT USER.USER_NAME,DIARY_COMMENT.SEND_TIME,DIARY_COMMENT.CONTENT from DIARY_COMMENT,USER where DIARY_COMMENT.DIA_ID='$DIA_ID' and DIARY_COMMENT.USER_ID = USER.USER_ID order by DIARY_COMMENT.SEND_TIME desc";
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
     <td nowrap align="center" width="100"><?=$DIA_TIME?></td>     
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
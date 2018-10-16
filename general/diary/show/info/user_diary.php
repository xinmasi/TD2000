<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
$PRIV_NO_FLAG=2;
$MODULE_ID="4";

$PER_PAGE=10;
if(!isset($start) || $start=="")
   $start=0;  
include_once("inc/my_priv.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("工作日志查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<style>
  .ol{margin-left:20px;}
  .hr1{border-top:1px dotted gray;HEIGHT:0;}
</style>
<script>
function delete_comment(COMMENT_ID)
{
  var msg='<?=_("删除该点评会连带日志回复一并删除，确认删除吗？")?>';
  if(window.confirm(msg))
  {
  	var URL="delete.php?COMMENT_ID="+COMMENT_ID+"&FROMUD=1&USER_ID=<?=$USER_ID?>&DEPT_ID=<?=$DEPT_ID?>";
  	location=URL;
  } 
}

</script>


<body class="bodycolor">
<?
$query = "SELECT count(DIA_ID) from diary where USER_ID='$USER_ID' and DIA_TYPE!='2'";
$cursor= exequery(TD::conn(),$query);
$DIA_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
  $DIA_COUNT=$ROW[0];

if($DIA_COUNT>0)
{
	$BEGIN_DATE=date("Y-m-01",time());
	$CUR_DATE=date("Y-m-d",time());

$USER_NAME=td_trim(GetUserNameById($USER_ID));

$MSG2 = sprintf(_("共%s条"), '<span class="big4">&nbsp;'.$DIA_COUNT.'</span>&nbsp;');
$MSG3 = sprintf(_("%s - 最新日志"), $USER_NAME);
}
?>
<div class="PageHeader">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
  	<td class="title"><?=$MSG3?>
  	<!--<a class="ToolBtn2"  href="user_query2.php?USER_ID=<?=$USER_ID?>&USER_NAME=<?=$USER_NAME?>" title="<?=_("查询日志")?>"><span><?=_("查询")?></span></a>--></td>
    <td align="right" valign="bottom" class="small1"><?=$MSG2?>
    </td>
    <?
    if($DIA_COUNT>0){
    ?>
    	<td align="right" valign="bottom" class="small1"><?=page_bar($start,$DIA_COUNT,$PER_PAGE)?>
    <?
    }
    ?>
    </tr>
</table>
</div>
<?
if($DIA_COUNT==0)
 {
?>
<div class="PageHeader"></div>
<?

   Message("",_("无日志记录"));
   exit;
 }
if(!is_user_priv($USER_ID, $MY_PRIV))
{
   Message("",_("您无查看该用户工作日志的权限。"));
   exit;
}

//============================ 显示日志 =======================================
$query = "SELECT DIA_ID,COMPRESS_CONTENT,READERS,DIA_TIME,DIA_DATE,SUBJECT,ATTACHMENT_ID,ATTACHMENT_NAME,CONTENT from DIARY where USER_ID='$USER_ID' and DIA_TYPE!='2' order by DIA_DATE desc,DIA_ID desc limit ".$start.",".$PER_PAGE;
$DIA_ID_STR="";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	 $DIA_ID_STR=$DIA_ID.",";

   $DIA_ID=$ROW["DIA_ID"];
   $DIA_DATE=$ROW["DIA_DATE"];
   $WRITE_USER_ID=$ROW["USER_ID"];
   $DIA_DATE=strtok($DIA_DATE," ");
   $SUBJECT=$ROW["SUBJECT"];
   $DIA_TIME=$ROW["DIA_TIME"];
   $NOTAGS_CONTENT=$ROW["CONTENT"];   
   $CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
   if($CONTENT=="")   
      $CONTENT=$NOTAGS_CONTENT;     
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];

   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $READERS=$ROW["READERS"]; 
   if($SUBJECT=="")
      $SUBJECT=csubstr(strip_tags($CONTENT),0,50).(strlen($CONTENT)>50?"...":"");
      
   $weeknames=Array(_("星期日"),_("星期一"),_("星期二"),_("星期三"),_("星期四"),_("星期五"),_("星期六"));
   $dateArr = explode("-", $DIA_DATE);
   $week=date("w",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
   $weekname=$weeknames[$week];
?>
<table class="TableTop" width="100%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <A href="read.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>&USER_NAME=<?=$USER_NAME?>&FROM_FLAG=1&start=<?=$start?>&PER_PAGE=<?=$PER_PAGE?>"><?=$SUBJECT?></A>
      </td>
      <td class="center subject" align="right">
        <DIV class="operate">
        	<!--<a align=right title="<?=_("指定共享范围")?>" onClick="javascript:location.href('share.php?DIA_ID=<?=$DIA_ID?>&start=<?=$start?>&PER_PAGE=<?=$PER_PAGE?>&USER_ID=<?=$USER_ID?>&SUBJECT=<?=$SUBJECT?>&USER_NAME=<?=$USER_NAME?>&FROM_FLAG=1')" href="#"><?=_("指定共享范围")?></a>-->
	        &nbsp;&nbsp;<a title="<?=_("点评")?>"  href="comment.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>&start=<?=$start?>&PER_PAGE=<?=$PER_PAGE?>"><?=_("点评")?></a>&nbsp; 
	      </DIV>
      </td>
      <td class="right"></td>
   </tr>
</table>
<div class="one_diary">
       <DIV class="diary_type"><?=_("工作日志")?> | <?=_("日志日期：")?><?=$DIA_DATE." ".$weekname?> | <?=_("最后修改：")?><?=$DIA_TIME?></DIV>
       <DIV class="content" style="overflow-y:auto;overflow-x:auto;width=100%;height=100%">
<?
echo $CONTENT;
?>
       </DIV>
       <DIV class="content">
<?
$ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);

if($ATTACH_ARRAY["NAME"]!="")
{
	 echo "<br><br>"._("附件：")."<br>";
	 echo attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],0,1,1); //attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1);
	      
}
?>
      </DIV>
  <div class="content">
<?
  
$COUNT=0;
$query2 = "SELECT * from DIARY_COMMENT where DIA_ID='$DIA_ID' order by SEND_TIME asc";
$cursor2= exequery(TD::conn(),$query2,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor2))
{
   $COUNT++;
   $USER_ID1=$ROW["USER_ID"];
   $SEND_TIME=$ROW["SEND_TIME"];
   $CONTENT=$ROW["CONTENT"];
   $COMMENT_ID=$ROW["COMMENT_ID"];
   $COMMENT_FLAG=$ROW["COMMENT_FLAG"];  

   $CONTENT=str_replace("\"","'",$CONTENT);

	$USER_NAME1=td_trim(GetUserNameById($USER_ID1));

  /*if($COMMENT_FLAG==0 && $DIA_TYPE!=2)
     $COMMENT_FLAG_DESC="<font color=red>"._("未读")."</font>";
  else if($DIA_TYPE!=2)
     $COMMENT_FLAG_DESC="<font color=green>"._("已读")."</font>";*/

$MSG6 = sprintf(_("%d楼%s点评%s%s"), $COUNT,"&nbsp;<b>".$USER_NAME1."</b>&nbsp;","&nbsp;".$SEND_TIME."&nbsp;",$COMMENT_FLAG_DESC);
?><hr class="hr1">
     <div class="diary_comment"><?=$MSG6?>&nbsp;
<?
if($USER_ID1 == $_SESSION["LOGIN_USER_ID"])
{
?>
     <a href="javascript:delete_comment('<?=$COMMENT_ID?>');"><?=_("删除")?></a>
<?
}
?>
     </div><div class="replycontent">
<?
   echo "<p style='margin:5px 5px 0px 5px;padding:5px;'>".$CONTENT."</p>";
   $query3 = "SELECT * from DIARY_COMMENT_REPLY where COMMENT_ID='$COMMENT_ID' order by REPLY_TIME asc";
   $cursor3= exequery(TD::conn(),$query3,$QUERY_MASTER);
   $REPLYER="";
   $REPLY_ID="";  
   $COMMENT_ID="";   
   $REPLY_TIME="";   	    	      	   	  
   $REPLY_COMMENT="";      
   while($ROW3=mysql_fetch_array($cursor3))
   {
   	  $REPLYER=$ROW3["REPLYER"];
   	  $REPLY_ID=$ROW3["REPLY_ID"];  
   	  $COMMENT_ID=$ROW3["COMMENT_ID"];   
   	  $REPLY_TIME=$ROW3["REPLY_TIME"];   	    	      	   	  
   	  $REPLY_COMMENT=$ROW3["REPLY_COMMENT"]; 
   	  //$REPLY_COMMENT=str_replace("\n","<br>",$REPLY_COMMENT);
   	  
      $query4 = "SELECT USER_NAME from USER where USER_ID='$REPLYER'";
      $cursor4= exequery(TD::conn(),$query4);  	  
   	  if($ROW4=mysql_fetch_array($cursor4))
   	     $USER_NAME4=$ROW4["USER_NAME"];
      echo "<div class='diary_comment_replay'>
      "._("回复：").$REPLY_COMMENT."<br><br>".
      "<i>"._("回复时间：").$REPLY_TIME."&nbsp;&nbsp;"._("回复人：").$USER_NAME4."</i></div>";   	    	  
   }
?>
     </div>
<?
}
?>
</div>
    </div>
<?
   if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]) and $WRITE_USER_ID!=$_SESSION["LOGIN_USER_ID"])
      $READERS = $READERS.$_SESSION["LOGIN_USER_ID"].",";
       $DIA_ID=intval($DIA_ID);
   $query2="update DIARY set READERS='$READERS' where DIA_ID='$DIA_ID'";
   exequery(TD::conn(),$query2);
}//while
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
<td align="right" valign="bottom" class="small1"><?=page_bar($start,$DIA_COUNT,$PER_PAGE)?></td>
</tr>
</table>
</body>
</html>

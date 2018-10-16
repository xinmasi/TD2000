<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("查看日志");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">
<style>
  .hr1{border-top:1px dotted gray;HEIGHT:0;}
</style>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function delete_comment(COMMENT_ID,USER_NAME)
{
  var msg='<?=_("删除该点评会连带日志回复一并删除，确认删除吗？")?>';
  if(window.confirm(msg))
  {
  	var URL="delete.php?COMMENT_ID="+COMMENT_ID+"&DIA_ID=<?=$DIA_ID?>&USER_NAME="+USER_NAME+"&FROM=<?=$FROM?>&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=<?=$END_DATE?>&SUBJECT=<?=$SUBJECT?>";
  	location=URL;
  }
}
</script>

<?
$DIA_ID=intval($DIA_ID);
if($DIARY_COPY_TIME!="")
{
   $DIARY_TABLE_NAME         = TD::$_arr_db_master['db_archive'].".DIARY". $DIARY_COPY_TIME;
   $DIARY_COMMENT_TABLE_NAME = TD::$_arr_db_master['db_archive'].".DIARY_COMMENT". $DIARY_COPY_TIME; 
   $DIARY_COMMENT_REPLY      = TD::$_arr_db_master['db_archive'].".DIARY_COMMENT_REPLY". $DIARY_COPY_TIME;  
}
else
{
   $DIARY_TABLE_NAME         = "DIARY";
   $DIARY_COMMENT_TABLE_NAME = "DIARY_COMMENT";
   $DIARY_COMMENT_REPLY      = "DIARY_COMMENT_REPLY";   
}
$URL_DIA_TYPE=$DIA_TYPE;
$query  = "SELECT * from ".$DIARY_TABLE_NAME." where DIA_ID='$DIA_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
   $DIA_TIME       = $ROW["DIA_TIME"];
   $DIA_DATE       = $ROW["DIA_DATE"];
   $WRITE_USER_ID  = $ROW["USER_ID"];
   $DIA_DATE       = strtok($DIA_DATE," ");
   $DIA_TYPE       = $ROW["DIA_TYPE"];
   $READERS        = $ROW["READERS"];
   $NOTAGS_CONTENT = $ROW["CONTENT"];
   if($ROW["COMPRESS_CONTENT"] == "")
   {
      $CONTENT = $NOTAGS_CONTENT;
   }
   else
   {
      $CONTENT = @gzuncompress($ROW["COMPRESS_CONTENT"]);
      if($CONTENT===FALSE)
	  {
		 $CONTENT = $NOTAGS_CONTENT; 
	  }     
   }
   $ATTACHMENT_ID   = $ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];

   if($DIA_TIME=="0000-00-00 00:00:00")
      $DIA_TIME="";
      
   $weeknames = Array(_("星期日"),_("星期一"),_("星期二"),_("星期三"),_("星期四"),_("星期五"),_("星期六"));
   $dateArr   = explode("-", $DIA_DATE);
   $week      = date("w",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
   $weekname  = $weeknames[$week];
}
else
{
   Message(_("错误"),_("该日志不存在或者已经归档!"));
   Button_Back();
   exit; 
}



$PRIV_NO_FLAG = 2;
$MODULE_ID    = "4";
include_once("inc/my_priv.php");
/*if(!is_user_priv($_SESSION["LOGIN_USER_ID"], $MY_PRIV))
{
   Message(_("提示"),_("无查看该日志权限"));
   Button_Back();
   exit;
}*/

if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]) && $WRITE_USER_ID!=$_SESSION["LOGIN_USER_ID"])
{
    //修改事务提醒状态--yc
    update_sms_status('13',$DIA_ID);

    $READERS = $READERS.$_SESSION["LOGIN_USER_ID"].",";
}
$DIA_ID = intval($DIA_ID);
$query2 = "update ".$DIARY_TABLE_NAME." set READERS='$READERS' where DIA_ID='$DIA_ID'";
exequery(TD::conn(),$query2);
?>

<body class="bodycolor">
<?
if($FROM_FLAG2=="1")
{
?>
<div class="PageHeader" style="height=0; padding-top=10;">
   <div class="title"><?=$USER_NAME?> - <?=_("工作日志")?></div>
</div>
<?
}else{
?>
<div class="PageHeader">
   <div class="title"><?=$USER_NAME?> - <?=_("工作日志")?></div>
</div>
<?
}
?>
<table class="TableTop" width="100%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("日志类型：")?><?=get_code_name($DIA_TYPE,"DIARY_TYPE");?>
      </td>
      <td class="right"></td>
   </tr>
</table>

<div class="one_diary">
<div class="diary_type"><?=_("日志日期：")?><?=$DIA_DATE." ".$weekname?> &nbsp;&nbsp;<?=_("最后修改：")?><?=$DIA_TIME?> &nbsp;&nbsp;<?=_("所有人：")?><?=td_trim(GetUserNameById($WRITE_USER_ID))?></div>
<div class="content" style="overflow-y:auto;overflow-x:auto;width=100%;height=100%"><?=$CONTENT?></div>
<div class="content">
<?
$ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
if($ATTACH_ARRAY["NAME"]!="")
{
?>
	<div><b><?=_("附件文件")?>:</b><br><?=attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],1,1,1)?></div><br />
  <div>
<?
}

if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";   

$COUNT  = 0;
$query  = "SELECT * from ".$DIARY_COMMENT_TABLE_NAME." where DIA_ID='$DIA_ID' order by SEND_TIME asc";
$cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
  $COUNT++;
  $COMMENT_ID = $ROW["COMMENT_ID"];
  $USER_ID    = $ROW["USER_ID"];
  $SEND_TIME  = $ROW["SEND_TIME"];
  $CONTENT    = $ROW["CONTENT"];
  
  $USER_NAME1 = td_trim(GetUserNameById($USER_ID));

$MSG2 = sprintf(_("%d楼%s点评%s"), $COUNT,"<b>".$USER_NAME1."</b>&nbsp;","&nbsp;".$SEND_TIME);
?>
    <hr class="hr1">
     <div class="diary_comment"><?=$MSG2?>&nbsp;&nbsp;
<?
if($USER_ID == $_SESSION["LOGIN_USER_ID"] && $DIARY_COPY_TIME=="" )
{
?>
    <a href="javascript:delete_comment('<?=$COMMENT_ID?>','<?=$USER_NAME?>');"><?=_("删除")?></a> 
<?
}
?>
    </div><div class="replycontent">
<?
   echo "<p style='margin:5px 5px 0px 5px;padding:5px;'>".$CONTENT."</p>";
   $query3  = "SELECT * from ".$DIARY_COMMENT_REPLY." where COMMENT_ID='$COMMENT_ID' order by REPLY_TIME asc";
   $cursor3 = exequery(TD::conn(),$query3,$QUERY_MASTER);
   $REPLYER       = "";
   $REPLY_ID      = "";
   $COMMENT_ID    = "";
   $REPLY_TIME    = "";
   $REPLY_COMMENT = "";
   while($ROW3=mysql_fetch_array($cursor3))
   {
   	  $REPLYER       = $ROW3["REPLYER"];
   	  $REPLY_ID      = $ROW3["REPLY_ID"];
   	  $COMMENT_ID    = $ROW3["COMMENT_ID"];
   	  $REPLY_TIME    = $ROW3["REPLY_TIME"];
   	  $REPLY_COMMENT = $ROW3["REPLY_COMMENT"];
   	  $REPLY_COMMENT = str_replace("\n","<br>",$REPLY_COMMENT);
   	  $USER_NAME4    = td_trim(GetUserNameById($REPLYER));

      echo "<div class='diary_comment_replay'>
      "._("回复：").$REPLY_COMMENT."<br><br>".
      "<i>"._("回复时间：").$REPLY_TIME."&nbsp;&nbsp;"._("回复人：").$USER_NAME4."</i></div>";
   }
?>
    </div>
<?
}
?></div></div>
 <br>
  <center>
    	<!--<input type="button" value="<?=_("打印")?>" class="BigButton" onClick="document.execCommand('Print');">&nbsp;&nbsp;-->
<?
if($FROM==1)
{
   if($FROMTYPE=="WORK_STAT")
   {
?>   	
      <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='user_search.php?BEGIN_DATE=<?=$BEGIN_DATE?>&TO_ID1=<?=$WRITE_USER_ID?>&FROMTYPE=<?=$FROMTYPE?>&END_DATE=<?=$END_DATE?>'">	
<? 
   }
   else
   {
      if($FROM_WORKSTAT==1)
      {
 ?>
         <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
 <?     	
      }
      else
      {
?>
         <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='user_search.php?DIARY_COPY_TIME=<?=$DIARY_COPY_TIME?>&BEGIN_DATE=<?=$BEGIN_DATE?>&DIA_TYPE=<?=$URL_DIA_TYPE?>&END_DATE=<?=$END_DATE?>&SUBJECT=<?=urlencode($SUBJECT)?>'">
<?
      }
   }
}
else
{
	   if($FROM_TABLE=="1")
	   {
?>
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();">
<?	   	
	   }
	   else if($FROM_FLAG2=="1")
	   {
?>
      <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();">
<?
    }
    else
    {
?>
			<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
<?
		}
}
?>
 </center>

</body>
</html>
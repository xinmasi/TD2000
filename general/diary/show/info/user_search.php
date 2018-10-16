<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("check_priv.inc.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";


$HTML_PAGE_TITLE = _("工作日志查询");
include_once("inc/header.inc.php");


$PER_PAGE = 10;

if(!isset($start) || $start=="")
{
	$start=0;
} 

?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<script>
function check_all()
{
 if(!document.all("email_select"))
    return;
 for (i=0;i<document.all("email_select").length;i++)
 {
   if(document.all("allbox").checked)
      document.all("email_select").item(i).checked=true;
   else
      document.all("email_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.all("allbox").checked)
      document.all("email_select").checked=true;
   else
      document.all("email_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      document.all("allbox").checked=false;
}
function delete_mail(DIARY_COPY_TIME,BEGIN_DATE,END_DATE,SUBJECT,TO_ID1,TO_ID,PRIV_ID,COPYS_TO_ID,DIA_TYPE)
{
  delete_str="";
  for(i=0;i<document.all("email_select").length;i++)
  {

      el=document.all("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.all("email_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要删除日志，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除所选日志吗？")?>';
  if(window.confirm(msg))
  {
    url="delete_query.php?DELETE_STR="+ delete_str +"&DIARY_COPY_TIME="+ DIARY_COPY_TIME +"&BEGIN_DATE="+ BEGIN_DATE +"&END_DATE="+ END_DATE +"&SUBJECT="+ SUBJECT +"&TO_ID1="+ TO_ID1 +"&TO_ID="+ TO_ID +"&PRIV_ID="+ PRIV_ID +"&COPYS_TO_ID="+ COPYS_TO_ID +"&DIA_TYPE="+ DIA_TYPE;
    location=url;
  }
}

</script>



<body class="bodycolor">
<?
  //----------- 合法性校验 ---------
if($BEGIN_DATE!="")
{
  $TIME_OK=is_date($BEGIN_DATE);

  if(!$TIME_OK)
  { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($END_DATE!="")
{
  $TIME_OK=is_date($END_DATE);

  if(!$TIME_OK)
  { Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}
$MSG2 = '<div class="title">查询结果</div>';

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

//============================ 显示日志 =======================================
$COUNT=0;//总数

$query_count="SELECT count(*) from ".$DIARY_TABLE_NAME." left join USER b on b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1 ".$WHERE_STR;
$cursor_count = exequery(TD::conn(),$query_count,$QUERY_MASTER);   
if($ROW_COUNT=mysql_fetch_array($cursor_count))
{
	$COUNT = $ROW_COUNT[0];
}
?>
<div class="PageHeader">
   <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
		<tr>
			<td class="title"><?=$MSG2?></td>
			<td align="right" valign="bottom" class="small1"><?=page_bar($start,$COUNT,$PER_PAGE)?>
			</td>
		</tr>
	</table>
</div>
<?
$DIA_COUNT=0;
$query = "SELECT ".$DIARY_TABLE_NAME.".DIA_ID,".$DIARY_TABLE_NAME.".DIA_DATE,".$DIARY_TABLE_NAME.".DIA_TYPE,".$DIARY_TABLE_NAME.".SUBJECT,".$DIARY_TABLE_NAME.".COMPRESS_CONTENT,".$DIARY_TABLE_NAME.".CONTENT,".$DIARY_TABLE_NAME.".ATTACHMENT_ID,".$DIARY_TABLE_NAME.".ATTACHMENT_NAME,".$DIARY_TABLE_NAME.".LAST_COMMENT_TIME,".$DIARY_TABLE_NAME.".USER_ID from ".$DIARY_TABLE_NAME." left join USER b on b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1 ".$WHERE_STR;
$query .= " order by DIA_DATE desc,DIA_ID desc limit ".$start.",".$PER_PAGE;
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);

while($ROW=mysql_fetch_array($cursor))
{
   $DIA_ID         = $ROW["DIA_ID"];
   $DIA_DATE       = $ROW["DIA_DATE"];
   $DIA_DATE       = strtok($DIA_DATE," ");
   $DIA_TYPES      = $ROW["DIA_TYPE"];
   $SUBJECT        = $ROW["SUBJECT"];
   $NOTAGS_CONTENT = $ROW["CONTENT"];    
   $CONTENT        = @gzuncompress($ROW["COMPRESS_CONTENT"]);
   if($CONTENT=="")
   {
	   $CONTENT    = $NOTAGS_CONTENT; 
   }
   $USER_ID        = $ROW["USER_ID"];
   
   if(!is_user_priv($USER_ID, $MY_PRIV))
      continue;
   $DIA_COUNT++;   
   
   $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$USER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
   {
      $USER_NAME=$ROW1["USER_NAME"];
      $DEPT_ID=$ROW1["DEPT_ID"];      
   }
   
   $DEPT_NAME= dept_long_name($DEPT_ID);
       
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   if($SUBJECT=="")
      $SUBJECT=csubstr(strip_tags($CONTENT),0,30).(strlen($CONTENT)>30?"...":"");

   $query1 = "SELECT count(*) from ".$DIARY_COMMENT_TABLE_NAME." where DIA_ID='$DIA_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $COMMENT_COUNT=$ROW1[0];

   if($DIA_COUNT==1)
   {
?>
   <table class="TableList" width="100%">
<?
   }
   
   if($DIA_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
   <tr class="<?=$TableLine?>">
<?
if($_SESSION["LOGIN_USER_ID"]=="admin")
{
?>
      <td nowrap align="center">
     <input type="checkbox" name="email_select" value="<?=$DIA_ID?>" onClick="check_one(self);">
      </td>
<?
}
?>
     <td nowrap align="center"><?=$DIA_DATE?></td>
     <td nowrap align="center" width="60"><?=$USER_NAME?></td> 
     <td nowrap align="center" width="60"><?=$DEPT_NAME?></td>     
     <td><a href="read.php?DIARY_COPY_TIME=<?=$DIARY_COPY_TIME?>&DIA_ID=<?=$DIA_ID?>&DIA_TYPE=<?=$DIA_TYPE?>&TO_ID=<?=$TO_ID?>&TO_ID1=<?=$TO_ID1?>&PRIV_ID=<?=urlencode($PRIV_ID)?>&COPYS_TO_ID=<?=$COPYS_TO_ID?>&USER_ID=<?=urlencode($USER_ID)?>&FROM=1&USER_NAME=<?=urlencode($USER_NAME)?>&BEGIN_DATE=<?=$URL_BEGIN_DATE?>&END_DATE=<?=$URL_END_DATE?>&SUBJECT=<?=urlencode($URL_SUBJECT)?>"><?=$SUBJECT?></a></td>
     <td><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1)?></td>
 <? if($FROMTYPE!="WORK_STAT" && $DIARY_COPY_TIME=="")
    {
 ?>  	  
     <td width="70"><center>
     	<!--<a href="share.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>&SUBJECT=<?=$SUBJECT?>&USER_NAME=<?=$USER_NAME?>&FROM_FLAG=2"><?=_("指定共享范围")?></a>-->
     	<a href="comment.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>&FROM=1&USER_NAME=<?=urlencode($USER_NAME)?>&BEGIN_DATE=<?=$URL_BEGIN_DATE?>&END_DATE=<?=$URL_END_DATE?>&SUBJECT=<?=urlencode($URL_SUBJECT)?>&DIA_TYPE=<?=$DIA_TYPE?>&TO_ID=<?=$TO_ID?>&TO_ID1=<?=$TO_ID1?>&PRIV_ID=<?=urlencode($PRIV_ID)?>&COPYS_TO_ID=<?=$COPYS_TO_ID?>"><?=_("点评")?></a>  </center>
     </td>
  <?
    }
  ?> 
   </tr>
<?
}//while

if($DIA_COUNT==0)
{
  Message("",_("无符合条件的日志记录"));
}
else
{
?>
  <thead class="TableHeader">
<?
if($_SESSION["LOGIN_USER_ID"]=="admin")
{
?>
     <td nowrap align="center" width="40"><?=_("选择")?></td>
<?
}
?>
     <td nowrap align="center" width="80"><?=_("日期")?> <img border=0 src="<?=MYOA_IMG_SERVER?>/images/arrow_down.gif" width="11" height="10"></td>
     <td nowrap align="center" width="50"><?=_("作者")?></td>
     <td nowrap align="center" width="50"><?=_("部门")?></td>
     <td nowrap align="center"><?=_("日志标题")?></td>
     <td nowrap align="center"><?=_("附件")?></td>
    <? 
    if($FROMTYPE!="WORK_STAT" && $DIARY_COPY_TIME=="")
    {
    ?> 
     <td nowrap align="center" width="30"><?=_("操作")?></td>     
    <?
    }
    ?>
  </thead>
   <tr class="TableControl">
     <td colspan="7">
<?
if($_SESSION["LOGIN_USER_ID"]=='admin' && $DIARY_COPY_TIME=="")
{
?>     	
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
       <label for="allbox_for"><?=_("全选")?></label> &nbsp;
       <input type="button"  value="<?=_("删除")?>" class="SmallButton" onClick="delete_mail('<?=$DIARY_COPY_TIME?>','<?=$BEGIN_DATE?>','<?=$END_DATE?>','<?=$URL_SUBJECT?>','<?=$TO_ID1?>','<?=$TO_ID?>','<?=$PRIV_ID?>','<?=$COPYS_TO_ID?>','<?=$DIA_TYPE?>');" title="<?=_("删除所选日志")?>"> &nbsp;
<?
}
?>
     </td>
   </tr>
  </table>
<?
}

if($COUNT > 0)
{
	 $MSG2 = sprintf(_("共 %d 篇日志"), $COUNT);
   Message("",$MSG2);
}

if($FROMTYPE=="WORK_STAT")
{
?>
   <div align="center">
      <input type="button"  value="<?=_("确定")?>" class="BigButton" onClick="window.close();">
   </div>
<?
}	
else
{
?>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='user_query.php';">
</div>
<?
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr>
		<td align="right" valign="bottom" class="small1"><?=page_bar($start,$COUNT,$PER_PAGE)?></td>
	</tr>
</table>
</body>
</html>

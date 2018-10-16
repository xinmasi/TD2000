<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");

$PRIV_NO_FLAG = 2;
$MODULE_ID    = "4";
$PER_PAGE     = 10;

if(!isset($start) || $start=="")
{
	$start=0;
} 
include_once("inc/my_priv.php");

$HTML_PAGE_TITLE = _("工作日志");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/diary.css">
<style>
   .hr1{border-top:1px dotted gray;HEIGHT:0;}
</style>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function delete_comment(COMMENT_ID)
{
  var msg='<?=_("删除该点评会连带日志回复一并删除，确认删除吗？")?>';
  if(window.confirm(msg))
  {
  	var URL="delete.php?COMMENT_ID="+COMMENT_ID+"&FROMUD=10&USER_ID=<?=$USER_ID?>&DEPT_ID=<?=$DEPT_ID?>";
  	location=URL;
  }
}
function open_share_set(TO_ID,SUBJECT,USER_NAME,ID)
{
  URL="set_share.php?TO_ID="+TO_ID+"&SUBJECT="+SUBJECT+"&USER_NAME="+USER_NAME+"&ID="+ID;
  location=URL;
}
function more()
{
	URL="diary_body.php?STARTDIARY=<?=$STARTDIARY+1?>#<?=$DIA_ID?>";
	location=URL;
}
function read_diary(USER_NAME,USER_ID,DIA_ID,DIA_ID_STR)
{
  URL="read.php?FROM_FLAG=1&FROM_FLAG2=1&DIA_ID="+DIA_ID+"&USER_NAME="+USER_NAME+"&USER_ID="+USER_ID+"&DIA_ID_STR="+DIA_ID_STR;
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"read","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
</script>

<body class="bodycolor">
<?
$login_user_id    = $_SESSION["LOGIN_USER_ID"];
$DIARY_TABLE_NAME = "DIARY";
//获取权限判断
include_once("general/diary/check_priv.inc.php");
$WHERE_STRS = substr($WHERE_STRS, 4);

//获得本周日期
$startdate = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))); 
$enddate   = date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));

$WHERE_STR="";
$WHERE_STR = " and DIA_DATE >= '$startdate' ";
$WHERE_STR .= " and DIA_DATE <= '$enddate' ";
if($WHERE_STRS=="")
{
	$WHERE_STR.=  " and DIARY.USER_ID!='$login_user_id' and (DIA_TYPE!=2 or find_in_set('".$login_user_id."',TO_ID) || TO_ALL= '1')";
}
else 
{
	$WHERE_STR.= " and DIARY.USER_ID!='$login_user_id' and (".$WHERE_STRS." and DIA_TYPE!=2 or (find_in_set('".$login_user_id."',TO_ID) || TO_ALL= '1'))";
}
	
if($MY_PRIV["DEPT_PRIV"]!="4")
{
	$query="SELECT ".$DIARY_TABLE_NAME.".USER_ID FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1".$WHERE_STR." ORDER BY DIA_TIME DESC";
	//$query = "SELECT USER_ID from DIARY where USER_ID<>'".$_SESSION["LOGIN_USER_ID"]."' and DIA_TYPE!='2'";
} 
else
{
	$query = "SELECT USER_ID from DIARY where DIA_TYPE!='2'";
}
$cursor= exequery(TD::conn(),$query);
$DIA_COUNT      = 0;
$USER_ID_STR    = "";
$USER_ID_NO_STR = "";
while($ROW=mysql_fetch_array($cursor))
{
  	$USER_ID2=$ROW['USER_ID'];
  	
  	/*if(find_id($USER_ID_STR,$USER_ID2))
  	{
    	$DIA_COUNT++;
  		continue;
  	}
	else if(find_id($USER_ID_NO_STR,$USER_ID2))
	{
		continue;
    }
	else if(!is_user_priv($USER_ID2, $MY_PRIV))
	{
		$USER_ID_NO_STR.=$USER_ID2.",";
        continue;
    }
      
    $USER_ID_STR.=$USER_ID2.",";*/
    $DIA_COUNT++;
}

 if($DIA_COUNT==0)
 {
?>
<div class="PageHeader"></div>
<?
   Message("",_("无日志记录"));
   exit;
 }

$MSG2 = sprintf(_("共%s条日志"), '<span class="big4">&nbsp;'.$DIA_COUNT.'</span>&nbsp;');
?>

<div class="PageHeader">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
		<tr>
			<td class="title"><?=$MSG2?></td>
			<td align="right" valign="bottom" class="small1"><?=page_bar($start,$DIA_COUNT,$PER_PAGE)?>
			</td>
		</tr>
	</table>
</div>
<?
$DIA_STR="";
$DIA_STR_ARRAY=array();
//============================ 显示日志 =======================================
if($MY_PRIV["DEPT_PRIV"]!="4")
{
	$query = "SELECT DIARY.DIA_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,COMPRESS_CONTENT,CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,".$DIARY_TABLE_NAME.".USER_ID AS USER_ID,t.TOP_ID as TOP_ID
                 FROM ".$DIARY_TABLE_NAME." LEFT JOIN USER b ON b.USER_ID = ".$DIARY_TABLE_NAME.".USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV LEFT JOIN diary_top as t on t.DIA_ID = DIARY.DIA_ID AND FIND_IN_SET('".$USER_ID."',t.USER_ID) AND t.DIA_CATE = 1 where 1=1".$WHERE_STR." ORDER BY TOP_ID DESC,DIA_TIME DESC LIMIT ".$start.",".$PER_PAGE;
	 //$query = "SELECT DIA_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,COMPRESS_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,TO_ID,USER_ID,CONTENT from DIARY where USER_ID<>'".$_SESSION["LOGIN_USER_ID"]."' and DIA_TYPE!='2' and find_in_set(USER_ID,'$USER_ID_STR') order by DIA_TIME desc limit ".$start.",".$PER_PAGE;
} 
else
{
	$query = "SELECT DIA_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,COMPRESS_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,TO_ID,USER_ID,CONTENT from DIARY where DIA_TYPE!='2' and find_in_set(USER_ID,'$USER_ID_STR') order by DIA_TIME desc limit ".$start.",".$PER_PAGE;
}  
$COUNT_DIA_ID = 0;
$DIA_ID_STR   = "";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$DIA_ARRAY[$COUNT_DIA_ID] = $ROW;
	$DIA_ID_STR              .= $ROW['DIA_ID'].",";
	$COUNT_DIA_ID++;
}
if($IS_MAIN==1)
{
	$QUERY_MASTER = true;
} 
else
{
	$QUERY_MASTER = ""; 
}   
$query = "SELECT * from DIARY_COMMENT where find_in_set(DIA_ID,'$DIA_ID_STR') order by SEND_TIME asc";
$COUNT_DIARY_COMMENT = 0;
$COMMENT_ID_STR      = "";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
	$COMMENT_ARRAY[$COUNT_DIARY_COMMENT] = $ROW;
	$COMMENT_ID_STR                     .= $ROW['COMMENT_ID'].",";
	$COUNT_DIARY_COMMENT++;
}

$query = "SELECT * from DIARY_COMMENT_REPLY where find_in_set(COMMENT_ID,'$COMMENT_ID_STR') order by REPLY_TIME asc";
$COUNT_DIARY_COMMENT_REPLY=0;
$REPLY_ID_STR="";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
	$COMMENT_REPLY_ARRAY[$COUNT_DIARY_COMMENT_REPLY] = $ROW;
	$REPLY_ID_STR                                   .= $ROW['REPLY_ID'].",";
	$COUNT_DIARY_COMMENT_REPLY++;
}

for($i=0;$i < $COUNT_DIA_ID;$i++)
{
   $TO_ID     = $DIA_ARRAY[$i]['TO_ID'];
   $USER_ID   = $DIA_ARRAY[$i]['USER_ID'];
   $USER_NAME = td_trim(GetUserNameById($USER_ID));
   
   $DIA_ID         = $DIA_ARRAY[$i]['DIA_ID'];
   $DIA_DATE       = $DIA_ARRAY[$i]['DIA_DATE'];
   $DIA_TIME       = $DIA_ARRAY[$i]['DIA_TIME'];
   $DIA_TYPE       = $DIA_ARRAY[$i]['DIA_TYPE'];
   $SUBJECT        = $DIA_ARRAY[$i]['SUBJECT'];
   $NOTAGS_CONTENT = $DIA_ARRAY[$i]['CONTENT'];
   
   if($DIA_ARRAY[$i]['COMPRESS_CONTENT'] == "")
   {
      $CONTENT = $NOTAGS_CONTENT;
   }
   else
   {
      $CONTENT = @gzuncompress($DIA_ARRAY[$i]['COMPRESS_CONTENT']);
      if($CONTENT===FALSE)
	  {
		  $CONTENT = $NOTAGS_CONTENT;  
      }       
   }
   $ATTACHMENT_ID   = $DIA_ARRAY[$i]['ATTACHMENT_ID'];
   $ATTACHMENT_NAME = $DIA_ARRAY[$i]['ATTACHMENT_NAME'];

   if(find_id($DIA_STR,$DIA_TYPE))
   {
	  $DIA_TYPE_DESC=$DIA_STR_ARRAY[$DIA_TYPE]; 
   }	
   else
   {
   		$DIA_TYPE_DESC            = get_code_name($DIA_TYPE,"DIARY_TYPE");
   		$DIA_STR                 .= $DIA_TYPE.",";
   		$DIA_STR_ARRAY[$DIA_TYPE] = $DIA_TYPE_DESC;
   }
   
   if($SUBJECT!="")
   {
	   $SUBJECT=csubstr(strip_tags($SUBJECT),0,50).(strlen($SUBJECT)>50?"...":"");
   } 
   $weeknames = Array(_("星期日"),_("星期一"),_("星期二"),_("星期三"),_("星期四"),_("星期五"),_("星期六"));
   $dateArr   = explode("-", $DIA_DATE);
   $week      = date("w",mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
   $weekname  = $weeknames[$week];
   
?>
<table class="TableTop" width="100%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <A href="javascript:void(0)" NAME="<?=$DIA_ID?>" onClick="read_diary('<?=$USER_NAME?>','<?=$USER_ID?>','<?=$DIA_ID?>','<?=$DIA_ID_STR?>')"><?=$SUBJECT?>(<?=$USER_NAME?>)</A>
      </td>
      <td class="right"></td>
   </tr>
</table>
<div class="one_diary">
       <DIV class="diary_type"><?=$DIA_TYPE_DESC?> | <?=_("日志日期：")?><?=$DIA_DATE." ".$weekname?> | <?=_("最后修改：")?><?=$DIA_TIME?> </DIV>
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
	 echo attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],0,1,1);
}
?>
       </DIV>
       <div class="content">
<?
$COMMENT_COUNT=0;
for($j=0;$j < $COUNT_DIARY_COMMENT;$j++)
{
	if($COMMENT_ARRAY[$j]['DIA_ID']==$DIA_ID)
	{
		$COMMENT_COUNT++;
		$USER_ID1     = $COMMENT_ARRAY[$j]['USER_ID'];
		$SEND_TIME    = $COMMENT_ARRAY[$j]['SEND_TIME'];
		$CONTENT      = $COMMENT_ARRAY[$j]['CONTENT'];
		$COMMENT_ID   = $COMMENT_ARRAY[$j]['COMMENT_ID'];
		$COMMENT_FLAG = $COMMENT_ARRAY[$j]['COMMENT_FLAG'];
        $CONTENT      = str_replace("\"","'",$CONTENT);
		$USER_NAME1   = td_trim(GetUserNameById($USER_ID1));
		
//		$USER_NAME1=GetUserInfoByUID(UserId2Uid($USER_ID1),"USER_NAME");
		
		/*if($COMMENT_FLAG==0 && $DIA_TYPE!=2)
			$COMMENT_FLAG_DESC="<font color=red>"._("未读")."</font>";
		else if($DIA_TYPE!=2)
			$COMMENT_FLAG_DESC="<font color=green>"._("已读")."</font>";*/
     
		$MSG2 = sprintf(_("%d楼%s点评%s%s"), $COMMENT_COUNT,"&nbsp;<b>".$USER_NAME1."</b>&nbsp;","&nbsp;".$SEND_TIME."&nbsp;",$COMMENT_FLAG_DESC);
?>
<hr class="hr1">
    <div class="diary_comment"><?=$MSG2?>&nbsp;
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
		for($k=0;$k < $COUNT_DIARY_COMMENT_REPLY;$k++)
		{
			if($COMMENT_REPLY_ARRAY[$k]['COMMENT_ID']==$COMMENT_ID)
			{
				$REPLYER       = $COMMENT_REPLY_ARRAY[$k]['REPLYER'];
				$REPLY_ID      = $COMMENT_REPLY_ARRAY[$k]['REPLY_ID'];
				$COMMENT_ID    = $COMMENT_REPLY_ARRAY[$k]['COMMENT_ID'];
				$REPLY_TIME    = $COMMENT_REPLY_ARRAY[$k]['REPLY_TIME'];
				$REPLY_COMMENT = $COMMENT_REPLY_ARRAY[$k]['REPLY_COMMENT'];
				$REPLY_COMMENT = str_replace("\n","<br>",$REPLY_COMMENT);
				$USER_NAME4    = td_trim(GetUserNameById($REPLYER));
//				$USER_NAME4=GetUserInfoByUID(UserId2Uid($REPLYER),"USER_NAME");	
				
				echo "<div class='diary_comment_replay'>
				"._("回复：").$REPLY_COMMENT."<br><br>".
				"<i>"._("回复时间：").$REPLY_TIME."&nbsp;&nbsp;"._("回复人：").$USER_NAME4."</i></div>";
			}
		}
?>
     </div>
<?
	}
}
?>
       <DIV class="operate">
       	<input type="button" value="<?=_("点评")?>" onClick="javascript:location.href('comment.php?FROM=10&DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>&start=<?=$start?>&PER_PAGE=<?=$PER_PAGE?>')"  class="SmallButton" name="button">
       	<!--<input type="button" value="<?=_("指定共享范围")?>" onClick="location.href('share.php?DIA_ID=<?=$DIA_ID?>&start=<?=$start?>&PER_PAGE=<?=$PER_PAGE?>&USER_ID=<?=$USER_ID?>&SUBJECT=<?=$SUBJECT?>&USER_NAME=<?=$USER_NAME?>&FROM_FLAG=3')"  class="SmallButton" name="button">-->
       </DIV>
</div>
</div>
<?
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr>
		<td align="right" valign="bottom" class="small1"><?=page_bar($start,$DIA_COUNT,$PER_PAGE)?></td>
	</tr>
</table>
</body>
</html>

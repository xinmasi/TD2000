<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("网络会议");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
	window.setTimeout('this.location.reload();',30000);
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/netmeeting.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("网络会议列表")?></span><br>
    </td>
    </tr>
</table>

<br>

<?
 //============================ 显示已创建会议 =======================================
 $CUR_TIME=date("Y-m-d H:i:s",time());

 $query = "SELECT * from NETMEETING where begin_time<='$CUR_TIME' and stop='0' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_ID) or FROM_ID='".$_SESSION["LOGIN_USER_ID"]."') order by BEGIN_TIME desc";
 $cursor= exequery(TD::conn(),$query);

 $MEET_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $MEET_ID=$ROW["MEET_ID"];
    $TO_ID=$ROW["TO_ID"];
    $SUBJECT=$ROW["SUBJECT"];

    $MEET_COUNT++;
    $SUBJECT=str_replace("<","&lt",$SUBJECT);
    $SUBJECT=str_replace(">","&gt",$SUBJECT);
    $SUBJECT=stripslashes($SUBJECT);

    $BEGIN_TIME=$ROW["BEGIN_TIME"];
    $STOP=$ROW["STOP"];

    if($TO_ID!="")
    {
      $TO_NAME="";
      $TOK=strtok($TO_ID,",");
      while($TOK!="")
      {
        if($TO_NAME!="")
           $TO_NAME.=",";
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
           $TO_NAME.=$ROW["USER_NAME"];

        $TOK=strtok(",");
      }
    }

    if($MEET_COUNT==1)
    {
?>

<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("会议主题")?></td>
      <td nowrap align="center"><?=_("参会人员")?></td>
      <td nowrap align="center"><?=_("开始时间")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("会议记录")?></td>
    </tr>

<?
    }

//-------------- 统计在线人数 ----------------
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
$CUR_HOUR=date("H",time());
$CUR_MIN=date("i",time());

$USR_FILE="meeting/msg/".$MEET_ID.".usr";

$USER_FOUND=0;
if(file_exists($USR_FILE))
{

   $LINES=file($USR_FILE);
   $LINES_COUNT=count($LINES);

   for($I=0;$I<$LINES_COUNT;$I++)
   {
       if($I%3==0)
       {
            $POS=strpos($LINES[$I+2],chr(10));
            $REFRESH_TIME=substr($LINES[$I+2],0,$POS);

            $STR=strtok($REFRESH_TIME," ");
            $DATE=$STR;

            if(compare_date($CUR_DATE,$DATE)==0)
            {
               $STR=strstr($REFRESH_TIME," ");
               $STR=strtok($STR,":");
               $HOUR=$STR;
               $STR=strtok(":");
               $MIN=$STR;

               if((($CUR_HOUR*60+$CUR_MIN)-($HOUR*60+$MIN))<2)
                  $USER_FOUND++;
            }
       }
   }
}
?>
    <tr class="TableData">
      <td>
      	<?=$SUBJECT?>
      	<a href="meeting/?MEET_ID=<?=$MEET_ID?>"><?=sprintf(_("进入文本会议(%d人)"),$USER_FOUND)?></a>
<?
    if(find_id($USER_FUNC_ID_STR,"181"))
    {
?>
 | <a href="/general/vmeet/vmeet.php?VMEET=<?=$MEET_ID?>&TYPE=PUBLIC" target="_blank"><?=_("进入视频会议")?></a>
<?
    }
?>
      </td>
      <td><?=$TO_NAME?></td>
      <td nowrap align="center"><?=$BEGIN_TIME?></td>
      <td nowrap align="center">
<?
 if(find_id($MEETER,$_SESSION["LOGIN_USER_ID"]))
{
?>
   <a href="vmeet.php?MEET_ID=<?=$MEET_ID?>"> <?=_("会议记录")?></a>
<?
}
?>
      </td>
    </tr>
<?
 }

 if($MEET_COUNT>0)
 {
?>
  </table>
<?
 }
 else
    Message("","<br>"._("暂无进行中的网络会议"));
?>

</body>
</html>

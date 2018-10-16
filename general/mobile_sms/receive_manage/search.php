<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
if(!$PAGE_SIZE)
   $PAGE_SIZE = 20;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("接收短信查询");
include_once("inc/header.inc.php");
?>


<script>
function delete_sms(SMS_ID)
{
 msg='<?=_("确认要删除该手机短信吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete_sms.php?SMS_ID=" + SMS_ID;
  window.location=URL;
 }
}
</script>


<body class="bodycolor">

<?
  //----------- 合法性校验 ---------
  if($BEGIN_DATE!="")
  {
    $TIME_OK=is_date_time($BEGIN_DATE);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始时间格式不对，应形如 1999-1-2 14:55:20"));
      Button_Back();
      exit;
    }
  }

  if($END_DATE!="")
  {
    $TIME_OK=is_date_time($END_DATE);

    if(!$TIME_OK)
    { Message(_("错误"),_("截止时间格式不对，应形如 1999-1-2 14:55:20"));
      Button_Back();
      exit;
    }
  }
 if(!isset($TOTAL_ITEMS))
 {
   $query = "SELECT count(*) from SMS2,USER where SMS2.FROM_ID=USER.USER_ID ";
  if($BEGIN_DATE!="")
    $query.= " and SEND_TIME>='$BEGIN_DATE'";
 if($END_DATE!="")
    $query.= " and SEND_TIME<='$END_DATE'";

 if($TO_ID!="")
 {
    $TO_ID1=substr($TO_ID,0,strlen($TO_ID)-1);
    $TO_ID1=str_replace(",","','",$TO_ID1);
    $TO_ID1="'".$TO_ID1."'";
    $query.= " and FROM_ID in($TO_ID1)";
 }

 if($CONTENT!="")
    $query.= " and CONTENT like '%$CONTENT%'";
 $query.= " and PHONE='$PHONE' and SEND_FLAG='1'"; 
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
 }
 $query = "SELECT CONTENT,SEND_TIME,USER_NAME from SMS2,USER where SMS2.FROM_ID=USER.USER_ID";
 if($BEGIN_DATE!="")
    $query.= " and SEND_TIME>='$BEGIN_DATE'";
 if($END_DATE!="")
    $query.= " and SEND_TIME<='$END_DATE'";

 if($TO_ID!="")
 {
    $TO_ID=substr($TO_ID,0,strlen($TO_ID)-1);
    $TO_ID=str_replace(",","','",$TO_ID);
    $TO_ID="'".$TO_ID."'";
    $query.= " and FROM_ID in($TO_ID)";
 }

 if($CONTENT!="")
    $query.= " and CONTENT like '%$CONTENT%'";

 $query.= " and PHONE='$PHONE' and SEND_FLAG='1'";
 
 
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/mobile_sms.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("接收短信查询结果")?></span>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
    </td>
  </tr>
</table>

<?
 $query.= " order by SEND_TIME desc,SMS_ID desc";
 $query .= " limit $start,$PAGE_SIZE";

 $cursor= exequery(TD::conn(),$query);
 $SMS_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $SMS_COUNT++;
    if($SMS_COUNT>500)
       break;

    $CONTENT=$ROW["CONTENT"];
    $SEND_TIME=$ROW["SEND_TIME"];
    $USER_NAME=$ROW["USER_NAME"];

    if($SMS_COUNT==1)
    {
?>
     <table class="TableList" width="95%" align="center">
      <tr class="TableHeader">
       <td nowrap align="center"><?=_("发信人")?></td>
       <td nowrap align="center"><?=_("内容")?></td>
       <td nowrap align="center"><?=_("发送时间")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      </tr>
<?
    }

    if($SMS_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$USER_NAME?>
      <td><?=td_htmlspecialchars($CONTENT)?></td>
      <td nowrap align="center"><?=$SEND_TIME?></td>
    </tr>
<?
 }//while
?>
</table>
<?
 if($SMS_COUNT==0)
   Message("",_("无符合条件的手机短信记录"));
 
 Button_Back();
?>

</body>
</html>

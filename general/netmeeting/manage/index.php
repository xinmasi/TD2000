<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("网络会议");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
window.setTimeout('this.location.reload();',60000);
</script>
<script>
function delete_netmeeting(MEET_ID)
{
 msg='<?=_("确认要删除该项网络会议吗？会议记录将被删除！")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?MEET_ID=" + MEET_ID;
  window.location=URL;
 }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("创建新的网络会议")?></span><br>
    </td>
  </tr>
</table>
<div align="center">
  <input type="button"  value="<?=_("新建网络会议")?>" class="BigButton" onClick="location='new/';" title="<?=_("创建网络会议，并指定参会范围和会议时间")?>">
</div>
<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<?
 $CUR_TIME=date("Y-m-d H:i:s",time());

 if($_SESSION["LOGIN_USER_PRIV"]!="1")
     $query = "SELECT count(*) from NETMEETING where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
 else
    $query = "SELECT count(*) from NETMEETING";

 $cursor= exequery(TD::conn(),$query);
 $NETMEETING_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $NETMEETING_COUNT=$ROW[0];

 if($NETMEETING_COUNT==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理已创建的网络会议")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
   Message("",_("无已创建的网络会议"));
   exit;
 }

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理已创建的网络会议")?></span><br>
    </td>

    <td valign="bottom" class="small1"><?=sprintf(_("共%s项网络会议"),"<span class='big4'>&nbsp;".$NETMEETING_COUNT."</span>&nbsp;")?>
    </td>
    </tr>
</table>


<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("召集人")?></td>
      <td nowrap align="center"><?=_("参会人员")?></td>
      <td nowrap align="center"><?=_("会议主题")?></td>
      <td nowrap align="center"><?=_("开始时间")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("当前状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>

<?
 //============================ 显示已创建会议 =======================================
 $CUR_TIME=date("Y-m-d H:i:s",time());

 if($_SESSION["LOGIN_USER_PRIV"]!="1")
     $query = "SELECT * from NETMEETING where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' order by BEGIN_TIME desc";
 else
    $query = "SELECT * from NETMEETING order by BEGIN_TIME desc";

 $cursor= exequery(TD::conn(),$query);

 while($ROW=mysql_fetch_array($cursor))
 {
    $MEET_ID=$ROW["MEET_ID"];
    $FROM_ID=$ROW["FROM_ID"];
    $TO_ID=$ROW["TO_ID"];
    $SUBJECT=$ROW["SUBJECT"];

    $MEETER=$TO_ID.$FROM_ID;

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

    $query1="select * from USER where USER_ID='$FROM_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
       $FROM_NAME=$ROW["USER_NAME"];

    if(compare_date($CUR_TIME,$BEGIN_TIME)<0)
    {
       $NETMEETING_STATUS=1;
       $NETMEETING_STATUS_STR=_("尚未召开");
    }
    else
    {
       if($STOP=="0")
       {
          $NETMEETING_STATUS=2;
          $NETMEETING_STATUS_STR="<font color='#00AA00'><b>"._("会议进行中")."</span>";
       }
       else
       {
          $NETMEETING_STATUS=3;
          $NETMEETING_STATUS_STR="<font color='#FF0000'><b>"._("已结束")."</span>";
       }
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$FROM_NAME?></td>
      <td><?=$TO_NAME?></td>
      <td><?=$SUBJECT?></td>
      <td nowrap align="center"><?=$BEGIN_TIME?></td>
      <td nowrap align="center"><?=$NETMEETING_STATUS_STR?></td>
      <td nowrap align="center">
      <a href="javascript:delete_netmeeting('<?=$MEET_ID?>');"> <?=_("删除")?></a>
      <?
      if($NETMEETING_STATUS==1)
      {
      ?>
      <a href="manage.php?MEET_ID=<?=$MEET_ID?>&OPERATION=1"> <?=_("立即召开")?></a>
      <?
      }
      else if($NETMEETING_STATUS==2)
      {
      ?>
      <a href="manage.php?MEET_ID=<?=$MEET_ID?>&OPERATION=2"> <?=_("立即结束")?></a>
      <?
      }
      else if($NETMEETING_STATUS==3)
      {
      ?>
      <a href="manage.php?MEET_ID=<?=$MEET_ID?>&OPERATION=3"> <?=_("继续开会")?></a>
      <?
      }
      if($STOP=="0")
      {
      ?>
      <a href="add.php?MEET_ID=<?=$MEET_ID?>"> <?=_("加人")?></a>
      <?
      }
	  
      if(find_id($MEETER,$_SESSION["LOGIN_USER_ID"]))
      {
      ?>
      <a href="record.php?MEET_ID=<?=$MEET_ID?>"> <?=_("会议记录")?></a>
      <?
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

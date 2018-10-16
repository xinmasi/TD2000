<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
window.setTimeout('this.location.reload();',30000);
</script>


<?
//----- 更新最后访问时间 -----
$CUR_TIME=date("Y-m-d H:i:s",time());
$TIME=date("H:i:s",time());
$USER_IP=get_client_ip();

$query = "SELECT * from NETCHAT where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $query = "update NETCHAT set IP='$USER_IP',LAST_VISIT_TIME='$CUR_TIME' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
  exequery(TD::conn(),$query);
}
else
{
  $query = "insert into NETCHAT(USER_ID,IP,LAST_VISIT_TIME) values ('".$_SESSION["LOGIN_USER_ID"]."','$USER_IP','$CUR_TIME')";
  exequery(TD::conn(),$query);
}
?>

<body class="bodycolor">

<table class="TableBlock" width="100%" align="center">
    <tr class="TableHeader">
      <td nowrap align="center" colspan="3"><b><?=_("在线人员列表")?></b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:location.reload()"><?=_("手动刷新")?></a></td>
    </tr>

<?
 $query = "SELECT * from NETCHAT where UNIX_TIMESTAMP('$CUR_TIME')-UNIX_TIMESTAMP(LAST_VISIT_TIME)<60";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $USER_ID=$ROW["USER_ID"];
    $IP=$ROW["IP"];

    $query1 = "SELECT USER_NAME from USER where USER_ID='$USER_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
       $USER_NAME=$ROW["USER_NAME"];
?>
    <tr class="TableData">
      <td align="center"><?=$USER_NAME?></td>
      <td align="center"><?=$IP?></td>
      <td align="center"><a href="javascript:mycall('<?=$IP?>')"><?=_("呼叫")?></a></td>
    </tr>
<?
 }
?>

</table>
<br>
<center><span class="big1"><?=_("最后刷新于：")?><?=$TIME?></span></center>

<script language=javascript>
function mycall(IP)
{
  try
  {
     parent.NetMeeting.callto(IP);
  
  }
  catch(ex)
  {
     alert("<?=_("无法呼叫，您可能未正常安装NetMeeting组件。")?>");	
  }
}
</script>

</body>
</html>

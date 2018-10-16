<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("车辆预定信息");
include_once("inc/header.inc.php");
?>


<script>
function my_manage_open(V_URL, VU_ID, V_WINDTH, V_HEIGHT)
{
  myleft=(screen.availWidth-V_WINDTH)/2;
  mytop=(screen.availHeight-V_HEIGHT)/2;
  window.open(V_URL+"?VU_ID="+VU_ID,"note_win"+VU_ID,"height="+V_HEIGHT+",width="+V_WINDTH+",status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}
</script>

<body class="bodycolor">
<?
 $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
 $cursor1= exequery(TD::conn(),$query);
 if($ROW1=mysql_fetch_array($cursor1))
    $V_NUM=$ROW1["V_NUM"];
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("车辆预定情况")?> - <?=$V_NUM?></span>
    </td>
  </tr>
</table>

<?
//============================ 显示已发布公告 =======================================
$query = "SELECT * from VEHICLE_USAGE where V_ID='$V_ID' and VU_STATUS!='4' order by VU_STATUS,VU_START";

$cursor= exequery(TD::conn(),$query);
$VU_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $VU_COUNT++;

   $VU_ID=$ROW["VU_ID"];
   $V_ID=$ROW["V_ID"];
   $VU_PROPOSER=$ROW["VU_PROPOSER"];
   $VU_REQUEST_DATE=$ROW["VU_REQUEST_DATE"];
   $VU_USER=$ROW["VU_USER"];
   $VU_REASON=$ROW["VU_REASON"];
   $VU_START =$ROW["VU_START"];
   $VU_END=$ROW["VU_END"];
   $VU_MILEAGE=$ROW["VU_MILEAGE"];
   $VU_DEPT=$ROW["VU_DEPT"];
   $VU_STATUS=$ROW["VU_STATUS"];
   $VU_REMARK=$ROW["VU_REMARK"];

   $query_name = "SELECT USER_NAME from USER where USER_ID = '$VU_USER'";
   $cursor_name= exequery(TD::conn(),$query_name);
   		if($ROW_NAME=mysql_fetch_array($cursor_name)){
      		$VU_USER = $ROW_NAME["USER_NAME"] != "" ? $ROW_NAME["USER_NAME"]:$VU_USER;
   		}
   if($VU_START=="0000-00-00 00:00:00")
      $VU_START="";
   if($VU_END=="0000-00-00 00:00:00")
      $VU_END="";

   if($VU_STATUS==0)
      $VU_STATUS_DESC=_("待批");
   elseif($VU_STATUS==1)
      $VU_STATUS_DESC=_("已准");
   elseif($VU_STATUS==2)
      $VU_STATUS_DESC=_("使用中");
   elseif($VU_STATUS==3)
     $VU_STATUS_DESC=_("未准");
   elseif($VU_STATUS==4)

   if($VU_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";

   if($VU_COUNT==1)
   {
?>
<table class="TableList" width="95%"  align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("用车人")?></td>
      <td align="center"><?=_("事由")?></td>
      <td nowrap align="center"><?=_("开始时间")?></td>
      <td nowrap align="center"><?=_("结束时间")?></td>
      <td align="center"><?=_("备注")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
   }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$VU_STATUS_DESC?></td>
      <td nowrap align="center"><?=$VU_USER?></td>
      <td align="center"><?=$VU_REASON?></td>
      <td nowrap align="center"><?=$VU_START?></a></td>
      <td nowrap align="center"><?=$VU_END?></a></td>
      <td align="center"><?=$VU_REMARK?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="my_manage_open('usage_detail.php', '<?=$VU_ID?>', '500', '500');"><?=_("详细信息")?></a>
      </td>
    </tr>
<?
}

if($VU_COUNT>0)
   echo "</table>";
else
   Message("",_("无预定信息"));
?>
</body>

</html>

<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���г���Ԥ����Ϣ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("���г���Ԥ����Ϣ")?></span>
    </td>
  </tr>
</table>

<?
//============================ ��ʾ����Ԥ����� =======================================
$query = "SELECT * from VEHICLE_USAGE where VU_STATUS!='4' order by VU_STATUS,VU_START";
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

   if($VU_START=="0000-00-00 00:00:00")
      $VU_START="";
   if($VU_END=="0000-00-00 00:00:00")
      $VU_END="";
  
    $query_name = "SELECT USER_NAME from USER where USER_ID = '$VU_USER'";
   	$cursor_name= exequery(TD::conn(),$query_name);
   		if($ROW_NAME=mysql_fetch_array($cursor_name)){
      		//$VU_USER_ID = $ROW_NAME["USER_NAME"] != ""?$VU_USER:"";
      		$VU_USER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$VU_USER;
   		}

   if($VU_STATUS==0)
      $VU_STATUS_DESC=_("����");
   elseif($VU_STATUS==1)
      $VU_STATUS_DESC=_("��׼");
   elseif($VU_STATUS==2)
      $VU_STATUS_DESC=_("ʹ����");
   elseif($VU_STATUS==3)
      $VU_STATUS_DESC=_("δ׼");

   $query1 = "SELECT * from VEHICLE where V_ID='$V_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $V_NUM=$ROW1["V_NUM"];

   if($VU_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";

   if($VU_COUNT==1)
   {
?>
<table class="TableList" width="95%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("���ƺ�")?></td>
      <td nowrap align="center"><?=_("״̬")?></td>
      <td nowrap align="center"><?=_("�ó���")?></td>
      <td align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("��ʼʱ��")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?></td>
      <td align="center"><?=_("��ע")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>
<?
   }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$V_NUM?></td>
      <td nowrap align="center"><?=$VU_STATUS_DESC?></td>
      <td nowrap align="center"><?=$VU_USER?></td>
      <td align="center"><?=$VU_REASON?></td>
      <td nowrap align="center"><?=$VU_START?></a></td>
      <td nowrap align="center"><?=$VU_END?></a></td>
      <td align="center"><?=$VU_REMARK?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('usage_detail.php?VU_ID=<?=$VU_ID?>','','height=390,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=280,top=150,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>
      </td>
    </tr>
<?
}

if($VU_COUNT>0)
   echo "</table>";
else
   Message("",_("��Ԥ����Ϣ"));
?>
</body>

</html>

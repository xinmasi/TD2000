<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("预约冲突信息");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif"  width="17" height="17"><span class="big3"> <?=_("与下列会议冲突")?></span><br>
    </td>
  </tr>
</table>
<br>
<table class="TableList" width="95%">
<tr class="TableHeader">
  <td nowrap align="center"><?=_("会议名称")?></td>
  <td nowrap align="center"><?=_("开始时间")?></td>
  <td nowrap align="center"><?=_("结束时间")?></td>
</tr>

<?
$PIECES = explode(",", $M_ID);
$PIECES_COUNT=sizeof($PIECES);
if($PIECES[$PIECES_COUNT-1]=="")$PIECES_COUNT--;
for($I=0;$I<$PIECES_COUNT;$I++)
{
$MEETING_COUNT++;
$query = "SELECT * from MEETING where M_ID='$PIECES[$I]'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
 {
	 $M_ID=$ROW["M_ID"];
	 $M_NAME=$ROW["M_NAME"];
	 $M_START=$ROW["M_START"];
   $M_END=$ROW["M_END"];
   if($MEETING_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>

<tr class="<?=$TableLine?>">
	<td nowrap align="center"><a href="javascript:;" onClick="window.open('../query/meeting_detail.php?M_ID=<?=$M_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=$M_NAME?></a>&nbsp;
  <td align="center"><?=$M_START?></td>
  <td align="center"><?=$M_END?></td>
</tr>
<?
 }
}//for
?>
</table><br>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>"></center>
</body>

</html>

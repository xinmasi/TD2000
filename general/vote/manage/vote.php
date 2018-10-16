<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";


$HTML_PAGE_TITLE = _("投票");
include_once("inc/header.inc.php");
?>



<script>
function show_reader(VOTE_ID)
{
 URL="show_reader.php?VOTE_ID="+VOTE_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_vote","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function delete_vote(VOTE_ID,start)
{
 msg='<?=_("确认要删除该投票吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?DELETE_STR=" + VOTE_ID + "&start=" + start + "&parent_id=<?=$PARENT_ID?>";
  window.location=URL;
 }
}


function delete_all()
{
 msg='<?=_("确认要删除所有投票吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete_all.php";
  window.location=URL;
 }
}

</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("新建子投票")?></span><br></td>
  </tr>
</table>

<div align="center">
   <input type="button" class="BigButton" value="<?=_("新建子投票")?>" onClick="location='new.php?PARENT_ID=<?=$PARENT_ID?>&start=<?=$start?>'">
</div>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("管理子投票")?></span><br></td>
  </tr>
</table>
<?
 $query = "SELECT * from VOTE_TITLE where PARENT_ID='$PARENT_ID' order by VOTE_NO,SEND_TIME";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 $VOTE_COUNT=mysql_num_rows($cursor);

 if($VOTE_COUNT==0)
 {
?>
<br>

<?
   Message("",_("无已发布的投票"));
?>
<br>
<div align="center">
   <input type="button" class="BigButton" value="<?=_("返回")?>" onClick="location='index1.php?start=<?=$start?>'">
</div>
<?
   exit;
 }
?>

<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("标题")?></td>
      <td nowrap align="center" width="80"><?=_("类型")?></td>
      <td nowrap align="center" width="120"><?=_("操作")?></td>
    </tr>

<?
 //============================ 显示已发布公告 =======================================
 $CUR_DATE=date("Y-m-d",time());

 while($ROW=mysql_fetch_array($cursor))
 {
    $VOTE_ID=$ROW["VOTE_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $TYPE=$ROW["TYPE"];

    $SUBJECT=td_htmlspecialchars($SUBJECT);

    if($TYPE=="0")
       $TYPE_DESC=_("单选");
    else if($TYPE=="1")
       $TYPE_DESC=_("多选");
    else
       $TYPE_DESC=_("文本输入");
?>
    <tr class="TableData">
      <td>
      <a href="javascript:show_reader('<?=$VOTE_ID?>');" title="<?=_("点击查看投票情况")?>"><?=$SUBJECT?></a>
      </td>
      <td nowrap align="center"><?=$TYPE_DESC?></td>
      <td nowrap align="right">
<?
if($TYPE!="2")
{
?>
      <a href="item/?VOTE_ID=<?=$VOTE_ID?>&start=<?=$start?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("投票项目")?></a>
<?
}
?>
      <a href="new.php?VOTE_ID=<?=$VOTE_ID?>&start=<?=$start?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
      <a href="javascript:delete_vote('<?=$VOTE_ID?>','<?=$start?>');"> <?=_("删除")?></a>
      <?
      if($VOTE_STATUS==1)
      {
      ?>
      <a href="manage.php?VOTE_ID=<?=$VOTE_ID?>&OPERATION=1&start=<?=$start?>"> <?=_("立即生效")?></a>
      <?
      }
      else if($VOTE_STATUS==2)
      {
      ?>
      <a href="manage.php?VOTE_ID=<?=$VOTE_ID?>&OPERATION=2&start=<?=$start?>"> <?=_("立即终止")?></a>
      <?
      }
      else if($VOTE_STATUS==3)
      {
      ?>
      <a href="manage.php?VOTE_ID=<?=$VOTE_ID?>&OPERATION=3&start=<?=$start?>"> <?=_("恢复生效")?></a>
      <?
      }
      ?>&nbsp;
      </td>
    </tr>
<?
 }
?>

<tr class="TableControl">
<td colspan="9" align="center">
    <input type="button" class="SmallButton" value="<?=_("返回")?>" onClick="location='index1.php?start=<?=$start?>'">
</td>
</tr>

</table>
</body>

</html>

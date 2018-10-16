<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目文档");
include_once("inc/header.inc.php");
?>


<script>
function delete_sort(SORT_ID)
{
 msg='<?=_("确认要删除该文件夹吗？这将删除该文件夹中的所有文件且不可恢复！")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?SORT_ID="+SORT_ID+"&PROJ_ID=<?=$PROJ_ID?>";
  window.location=URL;
 }
}
</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建文档目录")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<input type="button"  value="<?=_("新建文档目录")?>" class="BigButton" onClick="location='new/?PROJ_ID=<?=$PROJ_ID?>';">
</div>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("文档目录列表")?></span>
    </td>
  </tr>
</table>

<br>

<?
 //============================ 管理文件夹 =======================================
 $query = "SELECT * from PROJ_FILE_SORT WHERE PROJ_ID='$PROJ_ID' order by SORT_NO,SORT_NAME";
 $cursor= exequery(TD::conn(),$query);
 $SORT_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $SORT_COUNT++;

    $SORT_ID=$ROW["SORT_ID"];
    $SORT_NAME=$ROW["SORT_NAME"];

    $SORT_NAME=td_htmlspecialchars($SORT_NAME);

    if($SORT_COUNT==1)
    {
?>

    <table class="TableList" width="80%" align="center">

<?
    }

    if($SORT_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$SORT_NAME?></td>
      <td nowrap align="center">
          <a href="edit.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>"> 编辑</a>&nbsp;
          <a href="javascript:delete_sort(<?=$SORT_ID?>);"> <?=_("删除")?></a>&nbsp;
          <a href="set_priv/?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>"> <?=_("权限设置")?></a>&nbsp;
      </td>
    </tr>
<?
 }

 if($SORT_COUNT==0)
 {
   Message("",_("尚未建立项目文档目录！"));
   exit;
 }
 else
 {
?>
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("目录名称")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
   </thead>
   </table>
<?
 }
?>

</body>
</html>

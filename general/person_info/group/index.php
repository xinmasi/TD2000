<?
include_once("inc/auth.inc.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("用户组管理");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />
<script>
function delete_group(GROUP_ID)
{
  msg='<?=_("确认要删除该用户组吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?GROUP_ID=" + GROUP_ID;
     window.location=URL;
  }
}
</script>

<body class="bodycolor">
<div class="pageheader" style="padding-top:20px;padding-left:20px;">
    <span class="big3"> <?=_("管理用户组")?></span> &nbsp;
    <input type="button" value="<?=_("新建用户组")?>" class="btn btn-primary" onClick="location='new.php';" title="<?=_("创建新的用户组")?>">
</div>
<?
 //============================ 管理分组 =======================================
 $query = "SELECT * from USER_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by ORDER_NO";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if(mysql_num_rows($cursor)==0)
 {
    Message("",_("无定义的用户组"));
    exit;
 }
?>
<table class="table table-bordered" style="width: 800px;margin-top: 0px">
    <thead style="background-color:#ebebeb;">
        <tr>
            <th nowrap style="text-align: center;width: 60px;"><?=_("排序号")?></th>
            <th nowrap style="text-align: center;width: 300px;"><?=_("用户组名称")?></th>
            <th nowrap style="text-align: center;width: 150px;"><?=_("操作")?></th>
        </tr>
    </thead>
<?
 //============================ 管理分组 =======================================
 while($ROW=mysql_fetch_array($cursor))
 {
    $GROUP_ID=$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    $ORDER_NO=$ROW["ORDER_NO"];
?>
    <tr class="TableData">
      <td nowrap style="text-align: center;"><?=$ORDER_NO?></td>
      <td nowrap style="text-align: center;"><?=$GROUP_NAME?></td>
      <td nowrap style="text-align: center;">
          <a href="edit.php?GROUP_ID=<?=$GROUP_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("编辑")?></a>
          <a href="javascript:delete_group(<?=$GROUP_ID?>);"> <?=_("删除")?></a>
          <a href="set_user.php?GROUP_ID=<?=$GROUP_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("设置用户")?></a>
      </td>
    </tr>
<?
 }
?>
</table>
</body>
</html>

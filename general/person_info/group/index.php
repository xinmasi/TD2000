<?
include_once("inc/auth.inc.php");
//2013-04-11 ����������ѯ
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("�û������");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />
<script>
function delete_group(GROUP_ID)
{
  msg='<?=_("ȷ��Ҫɾ�����û�����")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?GROUP_ID=" + GROUP_ID;
     window.location=URL;
  }
}
</script>

<body class="bodycolor">
<div class="pageheader" style="padding-top:20px;padding-left:20px;">
    <span class="big3"> <?=_("�����û���")?></span> &nbsp;
    <input type="button" value="<?=_("�½��û���")?>" class="btn btn-primary" onClick="location='new.php';" title="<?=_("�����µ��û���")?>">
</div>
<?
 //============================ ������� =======================================
 $query = "SELECT * from USER_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by ORDER_NO";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if(mysql_num_rows($cursor)==0)
 {
    Message("",_("�޶�����û���"));
    exit;
 }
?>
<table class="table table-bordered" style="width: 800px;margin-top: 0px">
    <thead style="background-color:#ebebeb;">
        <tr>
            <th nowrap style="text-align: center;width: 60px;"><?=_("�����")?></th>
            <th nowrap style="text-align: center;width: 300px;"><?=_("�û�������")?></th>
            <th nowrap style="text-align: center;width: 150px;"><?=_("����")?></th>
        </tr>
    </thead>
<?
 //============================ ������� =======================================
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
          <a href="edit.php?GROUP_ID=<?=$GROUP_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("�༭")?></a>
          <a href="javascript:delete_group(<?=$GROUP_ID?>);"> <?=_("ɾ��")?></a>
          <a href="set_user.php?GROUP_ID=<?=$GROUP_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("�����û�")?></a>
      </td>
    </tr>
<?
 }
?>
</table>
</body>
</html>

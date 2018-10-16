<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("投票项目");
include_once("inc/header.inc.php");
?>

<script>
function delete_item(ITEM_ID, VOTE_ID)
{
   if (window.confirm("<?=_("您真的要删除该条选项吗？")?>\n\n<?=_("注意：该操作不可恢复！")?>"))
      window.location="delete.php?ITEM_ID="+ITEM_ID+"&VOTE_ID="+VOTE_ID;
}
function checkForm()
{
    var item_name = document.form0.ITEM_NAME.value;
    var reg=/^\s*$/;
    if(reg.test(item_name))
    {
        var regs = /\s+/g;
        item_name = item_name.replace(regs,"");
    }
    if(item_name=="")
    {
        alert("<?=_("请输入内容")?>");
        return (false);
    }
    document.form0.submit();
}
</script>
<body class="bodycolor">
<?
 $query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
 if($_SESSION["LOGIN_USER_PRIV"]!="1")
    $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if($ROW=mysql_fetch_array($cursor))
 {
    $SUBJECT=$ROW["SUBJECT"];
    $PARENT_ID=$ROW["PARENT_ID"];
    $ANONYMITY=$ROW["ANONYMITY"];
 }
 else
    exit;
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("投票项目管理")?> - <?=$SUBJECT?></span>
    </td>
  </tr>
</table>

<table class="TableList" width="95%" align="center">
  <tr class="TableHeader" align="center">
    <td><?=_("选项")?></td>
    <td width="60"><?=_("票数")?></td>
    <td width="280"><?=_("投票人员")?></td>
    <td width="80"><?=_("操作")?></td>
  </tr>
<?
$POSTFIX = _("，");
$query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID' order by ITEM_ID";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
   $ITEM_ID=$ROW["ITEM_ID"];
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $VOTE_COUNT=$ROW["VOTE_COUNT"];
   $VOTE_USER=$ROW["VOTE_USER"];

   if($ANONYMITY=="0")
   {
      $VOTE_USER_NAME="";
      $query = "SELECT * from USER where find_in_set(USER_ID,'$VOTE_USER')";
      $cursor1= exequery(TD::conn(),$query);
      while($ROW1=mysql_fetch_array($cursor1))
         $VOTE_USER_NAME.=$ROW1["USER_NAME"].$POSTFIX;
      $VOTE_USER_NAME=substr($VOTE_USER_NAME,0,-strlen($POSTFIX));
   }
?>
  <tr class="TableData">
    <form name="form<?=$ITEM_ID?>" method="post" action="update.php">
    <td>&nbsp;<input type="text" name="ITEM_NAME" class="SmallInput" value="<?=$ITEM_NAME?>" size="50"></td>
    <td align="right"><?=$VOTE_COUNT?><?=_("票")?></td>
    <td style="cursor:hand" title="<?=$VOTE_USER_NAME?>"><?=csubstr($VOTE_USER_NAME,0,30).(strlen($VOTE_USER_NAME)>30?"...":"")?></td>
    <td align="center">
    <input type="hidden" name="ITEM_ID" value="<?=$ITEM_ID?>">
    <input type="hidden" name="VOTE_ID" value="<?=$VOTE_ID?>">
    <input type="submit" name="submit" value="<?=_("修改")?>" class="SmallButton">
    <input type="button" value="<?=_("删除")?>" class="SmallButton" name="button" onclick="delete_item('<?=$ITEM_ID?>','<?=$VOTE_ID?>')">
    </td>
    </form>
  </tr>
<?
}
?>
  <tr class="TableControl">
    <form name="form0" method="post" action="add.php">
    <td colspan="4"><?=_("添加项目：")?>
    <input type="text" name="ITEM_NAME" class="SmallInput" size="40">
    <input type="hidden" name="VOTE_ID" value="<?=$VOTE_ID?>">&nbsp;&nbsp;
    <input type="button"  class="SmallButton" value="<?=_("添加")?>" onClick="checkForm();"></td>
    </form>
  </tr>
</table>

<div align="center">
   <br>
<?
if($PARENT_ID==0)
{
?>
   <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='../index1.php?start=<?=$start?>'">
<?
}
else
{
?>
   <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='../vote.php?PARENT_ID=<?=$PARENT_ID?>&start=<?=$start?>'">
<?
}
?>
</div>
</body>
</html>
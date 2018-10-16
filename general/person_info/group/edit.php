<?
include_once("inc/auth.inc.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />
<script>
function CheckForm()
{
   if(document.form1.GROUP_NAME.value=="")
      alert("<?=_("组名不能为空！")?>");
   else
      document.form1.submit();

}
</script>

<?
$query="select * from USER_GROUP where GROUP_ID='$GROUP_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
   $GROUP_NAME=$ROW["GROUP_NAME"];
   $ORDER_NO=$ROW["ORDER_NO"];
}
?>

<body class="bodycolor"  onload="form1.ORDER_NO.focus()">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("编辑用户组")?></span>
    </td>
  </tr>
</table>

<br>

<table class="table table-bordered" width="400" align="center">
  <form action="update.php" name="form1">
    <tr>
      <td nowrap class="TableData"><i class="iconfont">&#xe649;</i><?=_("排序号：")?></td>
      <td class="TableData"><input type="text" name="ORDER_NO" size="20" class="BigInput" value="<?=$ORDER_NO?>"></td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="80"><i class="iconfont">&#xe64b;</i><?=_("用户组名称：")?></td>
      <td class="TableData"><input type="text" name="GROUP_NAME" size="30" class="BigInput" value="<?=$GROUP_NAME?>"></td>
    </tr>
    <tr>
      <td nowrap class="TableData" colspan="2" align="center" style="text-align:center">
          <input type="hidden" name="GROUP_ID" size="10" class="BigInput" value="<?=$GROUP_ID?>">
          <input type="button" value="<?=_("提交")?>" class="btn btn-primary" title="<?=_("提交数据")?>" name="button1" OnClick="CheckForm()">&nbsp&nbsp&nbsp&nbsp
          <input type="button" value="<?=_("返回")?>" class="btn" title="<?=_("返回")?>" name="button2" OnClick="location='index.php'">
      </td>
    </tr>
    </form>
</table>

</body>
</html>
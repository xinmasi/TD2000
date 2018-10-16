<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

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
$query="select * from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	 $GROUP_NAME=$ROW["GROUP_NAME"];
	 $PRIV_DEPT=$ROW["PRIV_DEPT"];
	 $PRIV_ROLE=$ROW["PRIV_ROLE"];
	 $PRIV_USER=$ROW["PRIV_USER"];
	 $ORDER_NO=$ROW["ORDER_NO"];	 
}

$query="select * from DEPARTMENT";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $DEPT_ID=$ROW["DEPT_ID"];
   $DEPT_NAME=$ROW["DEPT_NAME"];
   if(find_id($PRIV_DEPT,$DEPT_ID))
      $TO_NAME.=$DEPT_NAME.",";
}

if($PRIV_DEPT=="ALL_DEPT")
   $TO_NAME=_("全体部门");
   
$TOK=strtok($PRIV_USER,",");
while($TOK!="")
{
   $query1 = "SELECT * from USER where USER_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_NAME.=$ROW["USER_NAME"].",";
   $TOK=strtok(",");
}

$TOK=strtok($PRIV_ROLE,",");
while($TOK!="")
{
   $query1 = "SELECT * from USER_PRIV where USER_PRIV='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $PRIV_NAME.=$ROW["PRIV_NAME"].",";
   $TOK=strtok(",");
}
?>

<body class="bodycolor"  onload="form1.GROUP_NAME.focus()">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("编辑分组")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="470" align="center">
  <form action="update.php" name="form1" method="post" enctype="multipart/form-data">
    <tr>
      <td nowrap class="TableData"><?=_("排序号：")?></td>
      <td class="TableData"><Input type="text" name="ORDER_NO" size="8" class="BigInput" value="<?=$ORDER_NO?>"></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("分组名称：")?></td>
      <td class="TableData"><Input type="text" name="GROUP_NAME" size="25" class="BigInput" value="<?=$GROUP_NAME?>"></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("公布范围（部门）")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$PRIV_DEPT?>">
        <textarea cols=30 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("公布范围（角色）")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ROLE?>">
        <textarea cols=30 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("公布范围（人员）")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="<?=$PRIV_USER?>">
        <textarea cols=30 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('107','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
    <tr>
      <td nowrap class="TableControl" colspan="2" align="center">
          <input type="hidden" name="GROUP_ID" size="10" class="BigInput" value="<?=$GROUP_ID?>">
          <input type="button" value="<?=_("提交")?>" class="BigButton" title="<?=_("提交数据")?>" name="button1" OnClick="CheckForm()">&nbsp&nbsp&nbsp&nbsp
          <input type="button" value="<?=_("返回")?>" class="BigButton" title="<?=_("返回")?>" name="button2" OnClick="location='index.php'">
      </td>
    </tr>
    </form>
</table>
    
</body>
</html>
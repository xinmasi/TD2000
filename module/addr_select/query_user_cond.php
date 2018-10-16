<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

if($TO_ID=="" || $TO_ID=="undefined")
{
   $TO_ID="TO_ID";
}
if($FORM_NAME=="" || $FORM_NAME=="undefined")
   $FORM_NAME="form1";

include_once("inc/header.inc.php");
?>

<style>
.menulines{}
</style>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script Language="JavaScript">

function init() {
  parent.resizeTo(700, 400);
}

function CheckForm(form_action)
{
   document.form1.action = form_action;
   document.form1.submit();
}
</script>

<body class="bodycolor" onload="init();">
<br>
<table class="TableBlock" width="90%" align="center">
  <form action="query_user.php" method="post" name="form1">
   <tr>
    <td nowrap class="TableData" width="80"><?=_("用户名：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="USER_ID" class="SmallInput" size="20" maxlength="20">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("真实姓名：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="USER_NAME" class="SmallInput" size="10" maxlength="10">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("别名：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="BYNAME" class="SmallInput" size="10" maxlength="10">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("部门：")?></td>
    <td nowrap class="TableData" width="80">
        <select name="DEPT_ID" class="SmallSelect" style="width:140px;">
        <option value=""></option>
<?
      echo my_dept_tree(0,$DEPT_ID,1);
      if($DEPT_ID==0)
      {
?>
          <option value="0"><?=_("离职人员/外部人员")?></option>
<?
      }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("角色：")?></td>
    <td nowrap class="TableData" width="80">
        <select name="USER_PRIV" class="SmallSelect" style="width:140px;">
        <option value=""></option>

<?
      $query = "SELECT * from USER_PRIV order by PRIV_NO desc";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $USER_PRIV1=$ROW["USER_PRIV"];
         $PRIV_NAME=$ROW["PRIV_NAME"];

?>
          <option value="<?=$USER_PRIV1?>"><?=$PRIV_NAME?></option>
<?
      }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="button" value="<?=_("查询")?>" class="SmallButton" onclick="CheckForm('query_user.php');" title="<?=_("查询用户")?>" name="button">
    </td>
   </tr>
   
     <input type="hidden" name="FIELD" value="<?=$FIELD?>">
     <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
     <input type="hidden" name="FORM_NAME" value="<?=$FORM_NAME?>">
  </form>
</table>

</body>
</html>

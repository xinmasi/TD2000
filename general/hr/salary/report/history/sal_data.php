<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("工资数据查阅");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
 $query = "SELECT * from USER where USER_ID='$USER_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $USER_NAME=$ROW["USER_NAME"];
 }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=sprintf(_("工资数据查阅（%s）"), $USER_NAME)?></span>
    </td>
  </tr>
</table>

<div align="center">

<?
 //-- 首先查询是否已查阅过数据 --
 if($RECALL=="")
 {
	 $FLOW_ID = intval($FLOW_ID);
   $query="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
     for($I=1;$I<=50;$I++)
     {
       $STR="S".$I;
       $$STR=format_money($ROW["$STR"]);
     }
     $MEMO=$ROW["MEMO"];
     $OPERATION=2; //-- 将执行数据更新 --
   }
   else
     $OPERATION=1; //-- 将执行数据插入 --
 }

 //-- 生成查阅项目 --
 $query="select * from SAL_ITEM";
 $cursor= exequery(TD::conn(),$query);
 $ITEM_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_COUNT++;

    $ITEM_ID=$ROW["ITEM_ID"];
    $ITEM_NAME=$ROW["ITEM_NAME"];

    $S_ID="S".$ITEM_ID;
    $S_NAME=$S_ID."_NAME";

    if($ITEM_COUNT==1)
    {
?>

    <table width="450" class="TableBlock">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center" width="110"><?=$ITEM_NAME?></td>
      <td nowrap align="center"><?=$$S_ID?>
      </td>
    </tr>
<?
 }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=_("备注")?></td>
      <td nowrap align="left"><?=$MEMO?></td>
    </tr>
<?
 if($ITEM_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("工资项目")?></td>
      <td nowrap align="center"><?=_("金额")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("尚未定义工资项目，请与财务主管联系！"));
?>

</div>
<?
Button_Back();
?>
</body>
</html>
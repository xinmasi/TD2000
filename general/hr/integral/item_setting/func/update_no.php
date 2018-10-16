<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("修改积分项");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$NAME_ARRAY = array();
foreach($_POST as $KEY => $VALUE)
{
   if(substr($KEY, 0, 10) == "ITEM_NAME_")
   {
      $BIN_CODE = substr($KEY, 10);
      $NAME_ARRAY[$BIN_CODE] = $VALUE;
   }
}

$ITEM_NAME = $NAME_ARRAY[bin2hex(MYOA_DEFAULT_LANG)];
if($ITEM_NAME == "")
{
   Message(_("提示"),_("积分项名称不能为空！"));
   Button_Back();
   exit;
}

$ITEM_EXT = array();
$LANG_ARRAY = get_lang_array();
foreach($LANG_ARRAY as $LANG => $LANG_DESC)
{
   if($LANG == MYOA_DEFAULT_LANG)
      continue;
   
   $BIN_CODE = bin2hex($LANG);
   if($NAME_ARRAY[$BIN_CODE] != "")
      $ITEM_EXT[$LANG] = $NAME_ARRAY[$BIN_CODE];
}

$ITEM_EXT = count($ITEM_EXT) > 0 ? addslashes(serialize($ITEM_EXT)) : '';

$query = "SELECT * from integral_item where ITEM_NO='$ITEM_NO' and PARENT_ITEM='$PARENT_ITEM' and ITEM_ID!='$ITEM_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   Message(_("提示"),sprintf(_("积分项编号%s已存在！"),$ITEM_NO));
   Button_Back();
   exit;
}

$query="update integral_item set ITEM_NO='$ITEM_NO',ITEM_NAME='$ITEM_NAME',ITEM_ORDER='$ITEM_ORDER',ITEM_BRIEF='$ITEM_BRIEF',ITEM_VALUE='$ITEM_VALUE',USED='$USED',PARENT_ITEM='$PARENT_ITEM',ITEM_EXT='$ITEM_EXT' where ITEM_ID='$ITEM_ID'";
exequery(TD::conn(),$query);

header("location:no_link.php");
?>
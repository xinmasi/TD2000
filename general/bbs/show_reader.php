<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("帖子已阅人员");
include_once("inc/header.inc.php");
?>



<?
$query = "SELECT USER_ID,READEDER from BBS_COMMENT where COMMENT_ID='$COMMENT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW["USER_ID"];
   $READEDER=$ROW["READEDER"];

   $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$USER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
   {
      $FROM_NAME=$ROW["USER_NAME"];
      $DEPT_ID=$ROW["DEPT_ID"];
      $DEPT_NAME=dept_long_name($DEPT_ID);
   }
}

?>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/notify.gif" align="absmiddle"><span class="big3"> <?=_("帖子已阅人员")?></span>
    </td>
    </tr>
</table>

<?
//------ 递归显示部门列表，支持按管理范围显示 --------
function dept_tree_list($DEPT_ID,$PRIV_OP,$READEDER)
{
  static $DEEP_COUNT;
  
  $query = "SELECT * from DEPARTMENT where DEPT_PARENT='$DEPT_ID' order by DEPT_NO";
  $cursor= exequery(TD::conn(),$query);
  $OPTION_TEXT="";
  $DEEP_COUNT1=$DEEP_COUNT;
  $DEEP_COUNT.=_("　");
  while($ROW=mysql_fetch_array($cursor))
  {
      $COUNT++;
      $DEPT_ID=$ROW["DEPT_ID"];
      $DEPT_NAME=$ROW["DEPT_NAME"];
      $DEPT_PARENT=$ROW["DEPT_PARENT"];

      $DEPT_NAME=td_htmlspecialchars($DEPT_NAME);

      $DEPT_PRIV=1;

      $OPTION_TEXT_CHILD=dept_tree_list($DEPT_ID,$PRIV_OP,$READEDER);

      $USER_NAME_STR="";
      
      $POSTFIX = _("，");

      $query="select USER_ID,USER_PRIV,USER_NAME,USER_PRIV_OTHER from USER where DEPT_ID='$DEPT_ID' and NOT_LOGIN='0' order by USER_NO,USER_NAME";
      $cursor1= exequery(TD::conn(),$query);
      while($ROW1=mysql_fetch_array($cursor1))
      {
         $USER_ID=$ROW1["USER_ID"];
         $USER_PRIV=$ROW1["USER_PRIV"];
         $USER_PRIV_OTHER=$ROW1["USER_PRIV_OTHER"];
         $USER_NAME=$ROW1["USER_NAME"];
         if(find_id($READEDER,$USER_ID))
         {
            $USER_NAME_STR.=$USER_NAME.$POSTFIX;
         }
      }
      $USER_NAME_STR=substr($USER_NAME_STR,0,-strlen($POSTFIX));

      $READ_LEN=strlen($USER_NAME_STR);

      if($DEPT_PRIV==1)
      {
      	 $OPTION_TEXT.="
  <tr class=TableData>
    <td class=\"TableContent\">".$DEEP_COUNT1._("├").$DEPT_NAME."</td>
    <td style=\"cursor:hand\">".csubstr(strip_tags($USER_NAME_STR),0,$READ_LEN).(strlen($USER_NAME_STR) > $READ_LEN ? "...":"")."</td>

  </tr>";
      }

      if($OPTION_TEXT_CHILD!="")
         $OPTION_TEXT.=$OPTION_TEXT_CHILD;
  }//while

  $DEEP_COUNT=$DEEP_COUNT1;
  
  return $OPTION_TEXT;
}

$OPTION_TEXT=dept_tree_list(0,0,$READEDER);

if($OPTION_TEXT=="")
   Message(_("提示"),_("无人查阅"));
else
{
?>
  <table class="TableBlock" width="100%" align="center">

    <tr class="TableHeader">
      <td nowrap align="center" width="30%"><?=_("部门/成员单位")?></td>
      <td nowrap align="center"><?=_("已读人员")?></td>

    </tr>
    <?=$OPTION_TEXT?>
    <tfoot class="TableControl">
      <td nowrap align="center"><b><?=_("合计：")?></b></td>
      <?
	  	$query = "SELECT READEDER FROM bbs_comment WHERE COMMENT_ID='$COMMENT_ID'";
		$cursor= exequery(TD::conn(),$query);
		if($ROW=mysql_fetch_array($cursor))
		{
			$READEDER = $ROW[0];
		}
		$newstr = substr($READEDER,0,strlen($READEDER)-1); 
		$READEDER = explode(",",$newstr);
      ?>
      <td nowrap align="center"><b><?=count($READEDER)?></b></td>
    </tfoot>
  </table>
<?
}
?>
</body>
</html>
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("部门/成员单位信息");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("部门/成员单位信息")?></span>
    </td>
  </tr>
</table>

<br>

<?
//------ 递归显示部门列表，支持按管理范围显示 --------
function dept_tree_list($DEPT_ID,$PRIV_OP)
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
      $TEL_NO=$ROW["TEL_NO"];
      $FAX_NO=$ROW["FAX_NO"];
      $TO_ID=$ROW["MANAGER"];
	  $ASSISTANT_ID=$ROW["ASSISTANT_ID"];
      $TO_ID3=$ROW["LEADER1"];
      $TO_ID4=$ROW["LEADER2"];
      $DEPT_FUNC=$ROW["DEPT_FUNC"];
       $ADDRESS=$ROW["DEPT_ADDRESS"];
      $DEPT_NAME=td_htmlspecialchars($DEPT_NAME);
      $DEPT_FUNC=td_htmlspecialchars($DEPT_FUNC);

      if($PRIV_OP==1)
         $DEPT_PRIV=is_dept_priv($DEPT_ID);
      else
         $DEPT_PRIV=1;

      $OPTION_TEXT_CHILD=dept_tree_list($DEPT_ID,$PRIV_OP);
      
      $TO_ARRAY=array();
      $query1 = "SELECT USER_ID,USER_NAME from USER where find_in_set(USER_ID,'$TO_ID')";
      $cursor1= exequery(TD::conn(),$query1);
      while($ROW=mysql_fetch_array($cursor1))
         $TO_ARRAY[$ROW["USER_ID"]]["USER_NAME"]=$ROW["USER_NAME"];
      
      $MANAGER_NAME="";
      $TOK=strtok($TO_ID,",");
      while($TOK!="")
      {
         $MANAGER_NAME.=$TO_ARRAY[$TOK]["USER_NAME"].",";
         $TOK=strtok(",");
      }
      $MANAGER_NAME=trim($MANAGER_NAME,",");
      
	  
	$query1 = "SELECT USER_ID,USER_NAME from USER where find_in_set(USER_ID,'$ASSISTANT_ID')";
	$cursor1= exequery(TD::conn(),$query1);
	while($ROW=mysql_fetch_array($cursor1))
	   $TO_ARRAY[$ROW["USER_ID"]]["USER_NAME"]=$ROW["USER_NAME"];
	$ASSISTANT_NAME="";
	$TOK=strtok($ASSISTANT_ID,",");
	while($TOK!="")
	{
	   $ASSISTANT_NAME.=$TO_ARRAY[$TOK]["USER_NAME"].",";
	   $TOK=strtok(",");
	}
	$ASSISTANT_NAME=trim($ASSISTANT_NAME,",");
	  
      $query1 = "SELECT USER_ID,USER_NAME from USER where find_in_set(USER_ID,'$TO_ID3')";
      $cursor1= exequery(TD::conn(),$query1);
      while($ROW=mysql_fetch_array($cursor1))
         $TO_ARRAY[$ROW["USER_ID"]]["USER_NAME"]=$ROW["USER_NAME"];
       
      $LEADER1_NAME="";
      $TOK=strtok($TO_ID3,",");
      while($TOK!="")
      {
         $LEADER1_NAME.=$TO_ARRAY[$TOK]["USER_NAME"].",";
         $TOK=strtok(",");
      }
      $LEADER1_NAME=trim($LEADER1_NAME,",");
      
      $query1 = "SELECT USER_ID,USER_NAME from USER where find_in_set(USER_ID,'$TO_ID4')";
      $cursor1= exequery(TD::conn(),$query1);
      while($ROW=mysql_fetch_array($cursor1))
         $TO_ARRAY[$ROW["USER_ID"]]["USER_NAME"]=$ROW["USER_NAME"];
      
      $LEADER2_NAME="";
      $TOK=strtok($TO_ID4,",");
      while($TOK!="")
      {
         $LEADER2_NAME.=$TO_ARRAY[$TOK]["USER_NAME"].",";
         $TOK=strtok(",");
      }
      $LEADER2_NAME=trim($LEADER2_NAME,",");

      if($DEPT_PRIV==1)
      {
      	 $OPTION_TEXT.="
  <tr class=TableData>
    <td width=\"16%\">".$DEEP_COUNT1._("├").$DEPT_NAME."</td>
    <td width=\"8%\">".$MANAGER_NAME."</td>
	<td width=\"8%\">".$ASSISTANT_NAME."</td>
    <td width=\"8%\">".$LEADER1_NAME."</td>
    <td width=\"8%\">".$LEADER2_NAME."</td>
    <td width=\"8%\">".$TEL_NO."</td>
    <td width=\"8%\">".$FAX_NO."</td>
    <td width=\"15%\">".$ADDRESS."</td>
    <td width=\"21%\" style=\"padding:5px\">".$DEPT_FUNC."</td>
  </tr>";
      }

      if($OPTION_TEXT_CHILD!="")
         $OPTION_TEXT.=$OPTION_TEXT_CHILD;

  }//while

  $DEEP_COUNT=$DEEP_COUNT1;
  return $OPTION_TEXT;
}

$OPTION_TEXT=dept_tree_list(0,0);

if($OPTION_TEXT=="")
   Message(_("提示"),_("尚未定义部门"));
else
 {
?>
  <table class="TableList" width="90%" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("部门/成员单位")?></td>
      <td nowrap align="center"><?=_("部门主管")?></td>
      <td nowrap align="center"><?=_("部门助理")?></td>
      <td nowrap align="center"><?=_("主管领导")?></td>
      <td nowrap align="center"><?=_("分管领导")?></td>
      <td nowrap align="center"><?=_("电话")?></td>
      <td nowrap align="center"><?=_("传真")?></td>
      <td nowrap align="center"><?=_("地址")?></td>
      <td align="center"><?=_("职能")?></td>
    </tr>
    <?=$OPTION_TEXT?>
  </table>
<?
 }
?>

</body>
</html>
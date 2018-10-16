<?
include_once("inc/auth.inc.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("人力资源管理员设置");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script> 
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<body class="bodycolor">
<br>
<?
$query = "select count(*) from DEPARTMENT";
$cursor= exequery(TD::conn(),$query);
$DEPT_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $DEPT_COUNT=$ROW["0"];
if($DEPT_COUNT>0)
  echo "<table class=\"table table-bordered table-hover\" style=\"width:90%\" align=\"center\">";
echo dept_tree_list(0);
if($DEPT_COUNT>0)
{
?>
  
   <thead style="background-color:#EBEBEB;">
     <td nowrap style="text-align: center;width:25%;"><?=_("部门名称")?></td>
     <td nowrap style="text-align: center;width:35%;"><?=_("人力资源管理员")?></td>
     <td nowrap style="text-align: center;width:35%;"><?=_("人事专员")?></td>
     <td nowrap style="text-align: center;width:5%;"><?=_("操作")?></td>
   </thead>
   </table>
<?
}
else
   Message("",_("尚未定义部门"));
?>
<br>
</body>
</html>

<?
//------ 递归显示部门列表，支持按管理范围显示 --------
function dept_tree_list($DEPT_ID)
{
  static $DEEP_COUNT;

  $query = "SELECT DEPT_ID,DEPT_NAME from DEPARTMENT where DEPT_PARENT='$DEPT_ID' order by DEPT_NO";
  $cursor= exequery(TD::conn(),$query, $connstatus);
  $OPTION_TEXT="";
  $DEEP_COUNT1=$DEEP_COUNT;
  $DEEP_COUNT.=_("　");
  while($ROW=mysql_fetch_array($cursor))
  {
      $COUNT++;
      $DEPT_ID=$ROW["DEPT_ID"];
      $DEPT_NAME=$ROW["DEPT_NAME"];
      $query1 = "select DEPT_HR_MANAGER,DEPT_HR_SPECIALIST from HR_MANAGER where DEPT_ID='$DEPT_ID'";
      $cursor1= exequery(TD::conn(),$query1, $connstatus);
      if($ROW1=mysql_fetch_array($cursor1))
	  {
         $DEPT_HR_MANAGER    = $ROW1["DEPT_HR_MANAGER"];
		 $DEPT_HR_SPECIALIST = $ROW1["DEPT_HR_SPECIALIST"];
	  }
      else
      {      
         $query1 = "insert into HR_MANAGER (DEPT_ID) values ('$DEPT_ID')";
         exequery(TD::conn(),$query1);
      }        
      $USER_NAME="";
      $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'$DEPT_HR_MANAGER')";
      $cursor1= exequery(TD::conn(),$query1, $connstatus);
      while($ROW1=mysql_fetch_array($cursor1))
         $USER_NAME.=$ROW1["USER_NAME"].",";
		 
	  $USER_NAME2="";	  
	  $query2 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'$DEPT_HR_SPECIALIST')";
      $cursor2= exequery(TD::conn(),$query2, $connstatus);
      while($ROW2=mysql_fetch_array($cursor2))
         $USER_NAME2.=$ROW2["USER_NAME"].","; 
	

      $OPTION_TEXT_CHILD=dept_tree_list($DEPT_ID);

      $OPTION_TEXT.="<tr class=TableData>
                       <td class=\"td-head\">".$DEEP_COUNT1._("├").$DEPT_NAME."</td>
                       <td align=\"left\">".substr($USER_NAME,0,-1)."</td>
					   <td align=\"left\">".substr($USER_NAME2,0,-1)."</td>
                       <td nowrap align=\"center\"><a href=\"edit.php?DEPT_ID=".$DEPT_ID."\"> "._("编辑")."</a></td>
                     </tr>";

      if($OPTION_TEXT_CHILD!="")
         $OPTION_TEXT.=$OPTION_TEXT_CHILD;
  }//while

  $DEEP_COUNT=$DEEP_COUNT1;
  return $OPTION_TEXT;
}
?>
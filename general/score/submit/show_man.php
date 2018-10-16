<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//0,考核人员；1，评分人员

$HTML_PAGE_TITLE = _("考核项目");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
<?
  	  if ($type=="0")
  	  {
?>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" align="absmiddle"><span class="big3"><?=_("考核人员")?></span><br>
    </td>
<?
    }
    else
    {
?>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" align="absmiddle"><span class="big3"><?=_("评分人员")?></span><br>
    </td>
<?
     }
?>
    </tr>
</table>

<table border="0" width="60%" cellpadding="3" cellspacing="1" align="center" bgcolor="#000000" class="small">
  <tr class="TableHeader" align="center">
<?
      if ($type=="0")
  	  {
?>
    <td><?=_("考核人员")?></td>
<?
    }
    else
    {
?>
    <td><?=_("评分人员")?></td>
<?
     }
?>
    <td><?=_("部门")?></td>
    <td><?=_("角色")?></td>
  </tr>
<?
 if ($type=="0")
  {$query = "SELECT PARTICIPANT as MAN  from SCORE_FLOW where FLOW_ID='$FLOW_ID'";}
  else
  {$query = "SELECT RANKMAN as MAN from SCORE_FLOW where FLOW_ID='$FLOW_ID'";}
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $MAN=$ROW["MAN"];

$TOK=strtok($MAN,",");
 while($TOK!="")
   {
        $query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
           $USER_NAME=$ROW["USER_NAME"];
           $DEPT_NAME=$ROW["DEPT_NAME"];
           $PRIV_NAME=$ROW["PRIV_NAME"];

?>
  <tr class="TableData">
    <td align="center">
     <?=$USER_NAME?>
    </td>
    <td align="center">
     <?=$DEPT_NAME?>
   </td>
   <td align="center">
     <?=$PRIV_NAME?>
   </td>
  </tr>
<?
  $TOK=strtok(",");
}
?>

</table>
<div align="center">
   <br>
   <input type="button" class="BigButton" value="<?=_("关闭")?>" onclick="window.close();">
</div>
</body>
</html>
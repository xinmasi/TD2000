<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
//0,考核人员；1，评分人员
if($type == "0")
{
    $HTML_PAGE_TITLE = _("被考核人员列表");
}
else
{
    $HTML_PAGE_TITLE = _("考核人员列表");
}

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
<?
  	  if ($type=="0")
  	  {
?>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" align="absmiddle"><span class="big3">&nbsp;<?=_("被考核人员")?></span><br>
    </td>
<?
    }
    else
    {
?>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" align="absmiddle"><span class="big3">&nbsp;<?=_("考核人员")?></span><br>
    </td>	
<?
     }
?>
    </tr>
</table>
<br>
<table width="70%" align="center" class="TableList">
  <tr class="TableHeader" align="center">
<?
      if ($type=="0")
  	  {
?>
    <td><?=_("被考核人员")?></td>
<?
    }
    else
    {
?>
    <td><?=_("考核人员")?></td>
<?
     }
?>
    <td><?=_("部门")?></td>
    <td><?=_("角色")?></td>
<?
if ($type!="0") 
  {
?> 
   <td align="center">
     <?=_("打分状态")?>
   </td>
<?
   }
?>     
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
           $USER_ID=$ROW["USER_ID"];

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
<?
if ($type!="0") 
  {
    $FLOW_ID=intval($FLOW_ID);
    $query1 = "select * from SCORE_FLOW  where FLOW_ID='$FLOW_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
     {
     	$PARTICIPANT=$ROW["PARTICIPANT"];
      $FLOW_FLAG=$ROW["FLOW_FLAG"];
     }
     
$tempcount=0;
if($FLOW_FLAG==0)
 {
 	 $MY_ARRAY=explode(",",$PARTICIPANT);
 }
else
{
	 $TEMP_ARRAY=explode(",",$PARTICIPANT);
   $ARRAY_COUNT=sizeof($TEMP_ARRAY);
   if($TEMP_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
   	 $query1 = "select DEPT_ID from USER where USER_ID='$TEMP_ARRAY[$I]'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
        $DEPT_ID=$ROW["DEPT_ID"];

     if (is_dept_priv($DEPT_ID)==1)

      {
      	$MY_ARRAY[$tempcount]=$TEMP_ARRAY[$I];
      	$tempcount=$tempcount+1;

      }
   }
 }
 $ARRAY_COUNT=sizeof($MY_ARRAY);
 if($MY_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
 //echo $USER_ID;
 if(find_id($PARTICIPANT,$USER_ID)){$ARRAY_COUNT--;}
 $query1 = "select count(*) from SCORE_DATE  where FLOW_ID='$FLOW_ID' and RANKMAN='$USER_ID'";
 $cursor1= exequery(TD::conn(),$query1);
 if($ROW=mysql_fetch_array($cursor1))
 $SCROE_COUNT=$ROW[0];
 if($ARRAY_COUNT==$SCROE_COUNT)
 $SCORE_STATUS="<font color='#00AA00'><b>"._("完成")."</font>";
 else
 $SCORE_STATUS="<font color='#FF0000'><b>"._("未完成")."</font>";
?> 
   <td align="center">
     <?=$SCORE_STATUS?>
   </td>
<?
   }
?>  
  </tr>
<?
  $TOK=strtok(",");
}
?>

</table>
<div align="center">
   <br>
   <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();">
</div>
</body>
</html>
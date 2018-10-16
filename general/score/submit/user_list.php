<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

include_once("inc/header.inc.php");
?>


<style>
.menulines{}
</style>



<body class="bodycolor">

<?
//------- 取出考核人员,GroupId,考核范围---------
$query1 = "select * from SCORE_FLOW  where FLOW_ID='$FLOW_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
{
   $PARTICIPANT=$ROW["PARTICIPANT"];
   $GROUP_ID=$ROW["GROUP_ID"];
   $FLOW_FLAG=$ROW["FLOW_FLAG"];
   $IS_SELF_ASSESSMENT=$ROW["IS_SELF_ASSESSMENT"];
}
//----------------------------

//-----过滤考核人员--------
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
//------------------------

//--------取出考核人名称、角色、考核记录-------

  $ARRAY_COUNT=sizeof($MY_ARRAY);
  if($MY_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
  for($I=0;$I<$ARRAY_COUNT;$I++)
   {
     $query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$MY_ARRAY[$I]'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        $MY_ARRAY_NAME[$I]=$ROW["USER_NAME"];
   	    $MY_ARRAY_PRIVE[$I]=$ROW["PRIV_NAME"];
   	    $MY_ARRAY_DEPT[$I]=$ROW["DEPT_NAME"];
     }
     //添加评分标示  20161205  spz
     $sql="SELECT * FROM score_self_data where PARTICIPANT='$MY_ARRAY[$I]' AND FLOW_ID ='$FLOW_ID'";
     $cursor1= exequery(TD::conn(),$sql);
     if($ROW=mysql_fetch_array($cursor1))
     {
        $MY_SCORE_SELF[$I]=$ROW["SCORE"];
   	 }
     
   	 //----------------------
   	 $query1="SELECT count(*) from SCORE_DATE where FLOW_ID='$FLOW_ID' and RANKMAN='".$_SESSION["LOGIN_USER_ID"]."' and PARTICIPANT='$MY_ARRAY[$I]' ";
     $cursor1= exequery(TD::conn(),$query1, $connstatus);
     if($ROW=mysql_fetch_array($cursor1))
        $MY_ARRAY_FLAG[$I]=$ROW[0];

   }

//-----------------------------
?>

<table border="0" cellspacing="1" width="100%" class="small" cellpadding="3" bgcolor="#000000">
<tr class="TableHeader">
  <td colspan="2" align="center"><b><?=_("被考核人员列表")?></b></td>
</tr>

<?
$USER_COUNT=0;
$ARRAY_COUNT=sizeof($MY_ARRAY);
if($MY_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
for($I=0;$I<$ARRAY_COUNT;$I++)
{
   if($MY_ARRAY[$I]==$_SESSION["LOGIN_USER_ID"])continue;
      $USER_COUNT=$USER_COUNT+1;

?>
<tr class="TableContent">
  <td nowrap class="menulines" BgColor="#FFFFFF" style="cursor:hand" align="center" onClick="do_Score('<?=$MY_ARRAY[$I]?>','<?=$FLOW_ID?>','<?=$GROUP_ID?>',this,'<?=$I?>');" onMouseOver="setPointer(this, '#B3D1FF','<?=$I?>');" onMouseOut="setPointer(this, '#FFFFFF','<?=$I?>');" title="<?=$MY_ARRAY_DEPT[$I]?>  <?=$MY_ARRAY_PRIVE[$I]?>">
<?
  if ($MY_ARRAY_FLAG[$I]>0)
  {
?>
     <img src="<?=MYOA_STATIC_SERVER?>/static/images/score_flag.gif" align="absmiddle">
<?
  }
?>
     <?  //添加评分标示  20161205  spz
       if(!empty($MY_SCORE_SELF[$I])){
           echo $MY_ARRAY_NAME[$I]."(已自评)";
       }else{
           echo $MY_ARRAY_NAME[$I];
       }
     ?>
  </td>
</tr>

<?

 }

 if($USER_COUNT==0)
 {
?>
<tr class="TableControl">
  <td align="center"><?=_("未定义用户")?></td>
</tr>
<?
 }
?>

</table>
</body>
</html>
<script>
var ControlId=-1;
var menu_id=-1;
function do_Score(USER_ID,FLOW_ID,GROUP_ID,theRow,DoId)
{
 menu_id=DoId;
 URL="score_data.php?GROUP_ID="+GROUP_ID+"&USER_ID="+USER_ID+"&FLOW_ID="+FLOW_ID;
 parent.frames["hrms"].location=URL;
 theRow.bgColor='#D9E8FF';
 if (ControlId!=-1 & ControlId!=theRow)ControlId.bgColor='#FFFFFF';
 ControlId=theRow;
}

function setPointer(theRow, thePointerColor,menu_id_over)
{
  if((menu_id!=menu_id_over))
     theRow.bgColor = thePointerColor;
}
</script>
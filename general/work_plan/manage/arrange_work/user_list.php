<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("工作安排查询");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
  <table class="TableList" align="center">
    <tr align="center" class="TableHeader">
      <td width="80" class="Small"><?=_("姓名")?></td>
<?
//---------------- 表头 ---------------
for($DAY=$BEGIN_DAY;$DAY<=$END_DAY;$DAY++)
{
  $WEEK=date("w",mktime(0,0,0,$MONTH,$DAY,$YEAR));

  switch($WEEK)
  {
    case 0:$WEEK_DESC=_("日");
           break;
    case 1:$WEEK_DESC=_("一");
           break;
    case 2:$WEEK_DESC=_("二");
           break;
    case 3:$WEEK_DESC=_("三");
           break;
    case 4:$WEEK_DESC=_("四");
           break;
    case 5:$WEEK_DESC=_("五");
           break;
    case 6:$WEEK_DESC=_("六");
           break;
  }

?>

      <td <?if($WEEK==0)echo "bgcolor=#FFCCFF";else if($WEEK==6)echo "bgcolor=#CCFFCC";?> class="Small">
          <?=$YEAR?>-<?=$MONTH?>-<?=$DAY?><br>
          (<?=$WEEK_DESC?>)
      </td>
<?
   if($BEGIN_DAY==$END_DAY)
   {
?>
      <td width="80"><?=_("操作")?></td>
<?
   }
}
?>
    </tr>

<?
$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');

//============================ 逐日显示工作安排 =======================================

$query = "SELECT * from USER where USER_ID='$USER_ID' and NOT_LOGIN='0'";
$cursor1= exequery(TD::conn(),$query);

if($ROW=mysql_fetch_array($cursor1))
{
  $USER_ID=$ROW["USER_ID"];
  $USER_NAME=$ROW["USER_NAME"];
?>

<tr class="TableData">
<td width="80" align="center"><?=$USER_NAME?></td>

<?
for($DAY=$BEGIN_DAY;$DAY<=$END_DAY;$DAY++)
{
  if($DAY == $CUR_DAY && $YEAR == $CUR_YEAR && $MONTH == $CUR_MON)
     $DAY_COLOR = "TableContent";
  else
     $DAY_COLOR = "TableData";
?>
     <td class="<?=$DAY_COLOR?>">
<?

 $query = "SELECT * from CALENDAR where USER_ID='$USER_ID' and CAL_TYPE!='2' and to_days(CAL_TIME)=to_days('$YEAR-$MONTH-$DAY') order by CAL_TIME";
 $cursor= exequery(TD::conn(),$query);

 while($ROW=mysql_fetch_array($cursor))
 {
    $CAL_ID=$ROW["CAL_ID"];
    $CAL_TIME=$ROW["CAL_TIME"];
    $END_TIME=$ROW["END_TIME"];
    $CAL_TYPE=$ROW["CAL_TYPE"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $OVER_STATUS=$ROW["OVER_STATUS"];
    $MANAGER_NAME="";
    
    if($MANAGER_ID!="")
    {
       $query = "SELECT * from USER where USER_ID='$MANAGER_ID'";
       $cursor2= exequery(TD::conn(),$query);
       if($ROW1=mysql_fetch_array($cursor2))
          $MANAGER_NAME=_("安排人：").$ROW1["USER_NAME"]."<br>";
    }

    if($OVER_STATUS=="" || $OVER_STATUS=="1")
       $OVER_STATUS1= "<font color='#00AA00'><b>"._("已结束")."</b></font>";
    elseif($OVER_STATUS=="0")
       $OVER_STATUS1="";
       
    $CONTENT=str_replace("<","&lt",$CONTENT);
    $CONTENT=str_replace(">","&gt",$CONTENT);
    $CONTENT=stripslashes($CONTENT);

    $CAL_DAY=strtok($CAL_TIME,"-");
    $CAL_DAY=strtok("-");
    $CAL_DAY=strtok(" ");

    if(substr($CAL_DAY,0,1)=="0")
       $CAL_DAY=substr($CAL_DAY,-1);

    $CAL_TIME=strtok($CAL_TIME," ");
    $CAL_TIME=strtok(" ");
    $CAL_TIME=substr($CAL_TIME,0,5);

    $END_TIME=strtok($END_TIME," ");
    $END_TIME=strtok(" ");
    $END_TIME=substr($END_TIME,0,5);

?>
     <?=$CAL_TIME?>-<?=$END_TIME?><br>
<?
   if($MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
   {
?>
     <a href="javascript:;" onClick="window.open('new.php?CAL_ID=<?=$CAL_ID?>','oa_sub_window','height=300,width=500,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes');"><?=$CONTENT?></a>  <?=$OVER_STATUS1?>
     <a href="delete.php?CAL_ID=<?=$CAL_ID?>&YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>&BEGIN_DAY=<?=$BEGIN_DAY?>&END_DAY=<?=$END_DAY?>&USER_ID=<?=$USER_ID?>"><?=_("删除")?></a>
<?
   }
   else
   {
?>
     <?=$CONTENT?> <?=$OVER_STATUS1?>
<?
   }
?>
     &nbsp;<br><?=$MANAGER_NAME?>
<?
 }//while 工作循环
?>
    </td>
<?
   if($BEGIN_DAY==$END_DAY)
   {
?>
    <td width="80" align="center"><a href="javascript:;" onClick="window.open('new.php?YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>&DAY=<?=$DAY?>&USER_ID=<?=$USER_ID?>','oa_sub_window','height=300,width=500,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes');"><?=_("安排工作")?></a></td>
<?
   }
}//for 日循环
?>
   </tr>
<?
}//while 用户循环
?>
</table>

</body>
</html>


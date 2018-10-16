<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";
$query="select * from CALENDAR where CAL_ID='$CAL_ID'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
   $CAL_TIME=$ROW["CAL_TIME"];
   $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
   $END_TIME=$ROW["END_TIME"];
   $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $CAL_LEVEL=$ROW["CAL_LEVEL"];
   $CONTENT=$ROW["CONTENT"];
   $OVER_STATUS=$ROW["OVER_STATUS"];
   $CONTENT=td_htmlspecialchars($CONTENT);
   $MANAGER_ID=$ROW["MANAGER_ID"];
   $OWNER=$ROW["OWNER"];
   $TAKER=$ROW["TAKER"];
   $CREATOR=$ROW["USER_ID"];
   $FROM_MODULE=$ROW["FROM_MODULE"];
   $URL=$ROW["URL"];
   $MANAGER_NAME="";
   if($MANAGER_ID!="")
   {
      $query = "SELECT * from USER where USER_ID='$MANAGER_ID'";
      $cursor1= exequery(TD::conn(),$query);
      if($ROW1=mysql_fetch_array($cursor1))
         $MANAGER_NAME=_("安排人：").$ROW1["USER_NAME"];
   }
   if($TAKER!="")
      $TAKER_NAME=_("参与者：").td_trim(GetUserNameById($TAKER));
   if($OWNER!="")
      $OWNER_NAME=_("所属者：").td_trim(GetUserNameById($OWNER));
   $CREATOR_NAME=_("创建者：").td_trim(GetUserNameById($CREATOR));
   if($OVER_STATUS=="0")
   {
      if(compare_time($CUR_TIME,$END_TIME)>0)
         $OVER_STATUS1="<font color='#FF0000'><b>"._("已超时")."</b></font>";
      else if(compare_time($CUR_TIME,$CAL_TIME)<0)
         $OVER_STATUS1="<font color='#0000FF'><b>"._("未开始")."</b></font>";
      else
         $OVER_STATUS1="<font color='#0000FF'><b>"._("进行中")."</b></font>";
   }
   else
   {
      $OVER_STATUS1="<font color='#00AA00'><b>"._("已完成")."</b></font>";
   }
             
   $TITLE=csubstr($CONTENT,0,10);
   if(substr($CAL_TIME,0,10) == $CUR_DATE && substr($END_TIME,0,10) == $CUR_DATE)
   {
      $CAL_TIME=substr($CAL_TIME,11,5);
      $END_TIME=substr($END_TIME,11,5);
   }
   else
   {
      $CAL_TIME=substr($CAL_TIME,0,16);
      $END_TIME=substr($END_TIME,0,16);
   }
}
$HTML = '<div class="small" style="text-align:left;height:250px;overflow:auto">';
$HTML.='<div style=float:right;margin-right:30px;><img src="'.MYOA_STATIC_SERVER.'/static/images/cal.png" style="width:64px; height:64px"></div>';

$HTML.= $CAL_TIME.' - '.$END_TIME.'<br>';
if($TAKER_NAME!="" || $OWNER_NAME!="")
   $HTML.= $CREATOR_NAME.'<br>';
if($MANAGER_NAME!="")
   $HTML.= $MANAGER_NAME.'<br>';
if($TAKER_NAME!="")
   $HTML.= $TAKER_NAME.'<br>';
if($OWNER_NAME!="")
   $HTML.= $OWNER_NAME.'<br>';
//if($CAL_LEVEL!="")
//   $HTML.= '<span class="CalLevel'.$CAL_LEVEL.'">'.cal_level_desc($CAL_LEVEL).'</span>';
$HTML.= $OVER_STATUS1.'<hr style="margin: 0px;"><p style="word-break: break-all;">';
if($URL!="")
{
    $HTML.='<a href="'.$URL.'" target="_blank">'.nl2br($CONTENT).'</a>';
}
else
{
    $HTML.= nl2br($CONTENT);
}
$HTML.= '</p></div>';
//自己创建者、自己是安排者、OA管理员、所属者 有修改和删除的权限
if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($OWNER,$_SESSION["LOGIN_USER_ID"]))
{
   $FLAG=1;
   $FALG_STATU='<button type="button" value="" class="btn" onclick=window.open("new.php?CAL_ID='.$CAL_ID.'&IS_MAIN='.$IS_MAIN.'","oa_sub_window","height=520,width=700,status=0,toolbar=no,menubar=no,location=no,left=300,top=100,scrollbars=yes,resizable=yes");>'._("修改").'</button>&nbsp;&nbsp;<input type="button" value="'._("删除").'" class="btn btn-danger" onclick="del_cal_pi(\''."$CAL_ID".'\',1,\''.$IS_MAIN.'\')">&nbsp;&nbsp;';
}
else
{
   $FLAG=0;
   $FALG_STATU='';
}


if($AJAX == "1")
{
   ob_end_clean();
   echo $HTML;
   echo '<div class="noteButtons"><input type="button" value="'._("详情").'" title="'._("生成便签窗口").'" class="btn btn-info" onclick="cal_note(\''.$CAL_ID.'\',\''.$IS_MAIN.'\')">&nbsp;&nbsp;'.$FALG_STATU.'<input type="button" value="'._("关闭").'" class="btn" onclick="HideDialog(\'form_div\');"></div>';
   exit;
}
if($FROM==1)
{
	 ob_end_clean();
   echo $HTML;
   echo '</div>';
   exit;
	
}

$HTML_PAGE_TITLE = $TITLE;
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">


<body bgcolor="#FFFFCC" topmargin="5" style="background:none;">

<div class="small">
<?=$CAL_TIME?> - <?=$END_TIME?><br>
<?=$MANAGER_NAME?> <br>
<?
if($CAL_LEVEL!="")
{
?>
<span class="CalLevel<?=$CAL_LEVEL?>"><?=cal_level_desc($CAL_LEVEL)?></span>
<?
}
?>
<?=$OVER_STATUS1?>
<hr>

<p style="word-break: break-all;"><?=$CONTENT?></p>
</div>
</body>
</html>

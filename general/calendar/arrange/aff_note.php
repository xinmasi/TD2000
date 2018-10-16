<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

  $query="select * from AFFAIR where AFF_ID='$AFF_ID'";
  $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
  if($ROW=mysql_fetch_array($cursor))
  {
    $USER_ID=$ROW["USER_ID"];
    $BEGIN_TIME=$ROW["BEGIN_TIME"];
    $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
    $END_TIME=$ROW["END_TIME"];
    if($END_TIME!=0)
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $TYPE=$ROW["TYPE"];
    $REMIND_DATE=$ROW["REMIND_DATE"];
    $REMIND_TIME=$ROW["REMIND_TIME"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $TAKER=$ROW["TAKER"];
    $CREATOR=$ROW["USER_ID"];
    $MANAGER_NAME="";
    if($MANAGER_ID!="")
       $MANAGER_NAME=_("安排者：").td_trim(GetUserNameById($MANAGER_ID));
    if($TAKER!="")
      $TAKER_NAME=_("参与者：").td_trim(GetUserNameById($TAKER));
    if($TAKER!="" && $MANAGER_ID=="")
      $CREATOR_NAME=_("创建者：").td_trim(GetUserNameById($CREATOR));
          
    $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
    if($TYPE=="2")
       $AFF_TIME=_("每日 ").$REMIND_TIME;
    elseif($TYPE=="3")
    {
       if($REMIND_DATE=="1")
          $REMIND_DATE=_("一");
       elseif($REMIND_DATE=="2")
          $REMIND_DATE=_("二");
       elseif($REMIND_DATE=="3")
          $REMIND_DATE=_("三");
       elseif($REMIND_DATE=="4")
          $REMIND_DATE=_("四");
       elseif($REMIND_DATE=="5")
          $REMIND_DATE=_("五");
       elseif($REMIND_DATE=="6")
          $REMIND_DATE=_("六");
       elseif($REMIND_DATE=="0")
          $REMIND_DATE=_("日");
       $AFF_TIME=_("每周").$REMIND_DATE." ".$REMIND_TIME;
    }
    elseif($TYPE=="4")
       $AFF_TIME=_("每月").$REMIND_DATE._("日 ").$REMIND_TIME;
    elseif($TYPE=="5")
       $AFF_TIME=_("每年").str_replace("-",_("月"),$REMIND_DATE)._("日 ").$REMIND_TIME;
    
    $CONTENT=td_htmlspecialchars($CONTENT);

    $TITLE=csubstr($CONTENT,0,10);
    
    $BEGIN_TIME=csubstr($BEGIN_TIME,0,10);
    $END_TIME=csubstr($END_TIME,0,10);
  }
$HTML = '<div class="small" style="text-align:left;">';
$HTML.='<div style=float:right;margin-right:30px;><img src="'.MYOA_STATIC_SERVER.'/static/images/cal.png" style="width:64px; height:64px;"></div>';
$HTML.= $BEGIN_TIME._("至").$END_TIME.'<br>';
$HTML.= $AFF_TIME.'<br>';
if($TAKER_NAME!="" && $MANAGER_NAME=="")
   $HTML.= $CREATOR_NAME.'<br>';
if($MANAGER_NAME!="")
   $HTML.= $MANAGER_NAME.'<br>';
if($TAKER_NAME!="")
   $HTML.= $TAKER_NAME.'<br>'; 
$HTML.='<hr>';
$HTML.= nl2br($CONTENT);
$HTML.= '</div>';
if($FROM==1 &&($MANAGER_ID==$_SESSION["LOGIN_USER_ID"]||($MANAGER_ID==""&&$USER_ID==$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]==1))
{
   $FLAG=1;
   $FALG_STATU='<input type="button" value="'._("修改").'" class="btn" onclick=window.open("../info/new_affair.php?AFF_ID='.$AFF_ID.'&FROM='.$FROM.'&IS_MAIN='.$IS_MAIN.'","oa_sub_window","height=300,width=500,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes");>&nbsp;&nbsp;<input type="button" value="'._("删除").'" class="btn btn-danger" onclick="del_aff_new_arr('."$AFF_ID".',1,'.$IS_MAIN.')">&nbsp;&nbsp;';
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
   echo '<br><br><br><br><br><br><div align="center" style="float:bottom"><input type="button" value="'._("详情").'"  title="'._("生成便签窗口").'"  class="btn btn-info" onclick="aff_note('."$AFF_ID".','.$IS_MAIN.')">&nbsp;&nbsp;'."$FALG_STATU".'<input type="button" value="'._("关闭").'" class="btn" onclick="HideDialog(\'form_div\');"></div>';
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
<script>
function del_aff_new_old(AFF_ID,from)
{
   var url='delete.php?AFF_ID='+AFF_ID+'&FROM='+from;
   if(window.confirm("<?=_("删除后将不可恢复，如果是批量安排的事务将会批量删除，确定删除吗？")?>"))
      location=url;
}
</script>

<body bgcolor="#FFFFCC" topmargin="5" style="background:none;">
<div class="small" style="height:200px;">
	
<?=$BEGIN_TIME?> <?=_("至")?> <?=$END_TIME?><br>
<?=$AFF_TIME?>
<?if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]) echo "<br>"._("安排人：").$MANAGER_ID_NAME."<br>"; ?>
<hr>

<?=nl2br($CONTENT)?>

<?
if($FROM==1 &&($MANAGER_ID==$_SESSION["LOGIN_USER_ID"]||($MANAGER_ID==""&&$USER_ID==$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]==1))
{
?>
<center>
<input type="button" value="<?=_("修改")?>"  onclick="window.open('../info/new_affair.php?AFF_ID=<?=$AFF_ID?>&FROM=<?=$FROM?>','oa_sub_window','height=355,width=500,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes')" class="BigButtonA">&nbsp;&nbsp;	
<input type="button" value="<?=_("删除")?>"  onclick="del_aff_new(<?=$AFF_ID?>,1)" class="BigButtonA">	
</center>	
<?	
}
?>
</div>
</body>
</html>

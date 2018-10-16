<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("考核任务");
include_once("inc/header.inc.php");
?>



<script>

function show_reader(GROUP_ID)
{
 URL="show_reader.php?GROUP_ID="+GROUP_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_vote","height=500,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function show_desc(FLOW_ID)
{
 URL="show_desc.php?FLOW_ID="+FLOW_ID;
 myleft=(screen.availWidth-50)/2;
 window.open(URL,"read_vote","height=300,width=400,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function show_man(FLOW_ID,FLAG)
{
 URL="show_man.php?FLOW_ID="+FLOW_ID+"&type="+FLAG;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_vote","height=500,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function delete_vote(FLOW_ID,CUR_PAGE)
{
 msg='<?=_("确认要删除该考核任务？考核数据将被删除且不可恢复！！")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?FLOW_ID=" + FLOW_ID + "&CUR_PAGE=" + CUR_PAGE;
  window.location=URL;
 }
}
function show_labor(FLOW_ID)
{
  URL="show_labor.php?FLOW_ID="+FLOW_ID;
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"read_score","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

</script>


<body class="bodycolor">

<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
 	 $query="select count(*) from SCORE_FLOW";
else
	 $query = "SELECT count(*) from SCORE_FLOW where CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or CREATE_USER_ID=''";
$cursor= exequery(TD::conn(),$query, $connstatus);
$VOTE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $VOTE_COUNT=$ROW[0];

if($VOTE_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3">&nbsp;&nbsp;<?=_("管理已发布的考核任务")?></span><br>
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("无已发布的考核任务"));
   exit;
}

$PER_PAGE=15;
$PAGES=10;
$PAGE_COUNT=ceil($VOTE_COUNT/$PER_PAGE);

if($CUR_PAGE<=0 || $CUR_PAGE=="")
  $CUR_PAGE=1;
if($CUR_PAGE>$PAGE_COUNT)
  $CUR_PAGE=$PAGE_COUNT;
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" align="absmiddle" WIDTH="18" HEIGHT="18"><span class="big3">&nbsp;&nbsp;<?=_("管理已发布的考核任务")?></span><br>
    </td>
<?
   $COUNT_MSG = sprintf(_("共%s条"),"<span class='big4'>&nbsp;".$VOTE_COUNT."</span>&nbsp;");
?>
    <td align="right" valign="bottom" class="small1"><?=$COUNT_MSG?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index1.php?CUR_PAGE=1"><?=_("首页")?></a>&nbsp;
       <a class="A1" href="index1.php?CUR_PAGE=<?=$PAGE_COUNT?>"><?=_("末页")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
   $PAGE_UP = sprintf(_("上%d页"),$PAGES);
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>"><?=$PAGE_UP?></a>&nbsp;&nbsp;
<?
}

for($I=$CUR_PAGE-$J+1;$I<=$CUR_PAGE-$J+$PAGES;$I++)
{
   if($I>$PAGE_COUNT)
      break;

   if($I==$CUR_PAGE)
   {
?>
       [<?=$I?>]&nbsp;
<?
   }
   else
   {
?>
       [<a class="A1" href="index1.php?CUR_PAGE=<?=$I?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
   $PAGE_DOWN = sprintf(_("下%d页"),$PAGES);
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$I?>"><?=$PAGE_DOWN?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-1?>"><?=_("上一页")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("上一页")?>&nbsp;
<?
}

if($CUR_PAGE+1<= $PAGE_COUNT)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE+1?>"><?=_("下一页")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("下一页")?>&nbsp;
<?
}
?>
       &nbsp;
    </td>
    </tr>
</table>

<br>
<table width="98%" class="TableList">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("考核任务名称")?></td>
      <td nowrap align="center"><?=_("考核人")?></td>
      <td nowrap align="center"><?=_("被考核人")?></td>
      <td nowrap align="center"><?=_("考核指标集")?></td>
      <td nowrap align="center"><?=_("匿名")?></td>
      <td nowrap align="center"><?=_("生效日期")?></td>
      <td nowrap align="center"><?=_("终止日期")?></td>

      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>

<?
 //============================ 显示已发布考核任务 =======================================
$CUR_DATE=date("Y-m-d",time());
if($_SESSION["LOGIN_USER_PRIV"]==1)
 		$query = "SELECT * from SCORE_FLOW order by SEND_TIME desc";
else
 		$query = "SELECT * from SCORE_FLOW where CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or CREATE_USER_ID='' order by SEND_TIME desc";

 $cursor= exequery(TD::conn(),$query, $connstatus);
 $VOTE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $VOTE_COUNT++;

    if($VOTE_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
       continue;
    if($VOTE_COUNT>$CUR_PAGE*$PER_PAGE)
       break;
    $FLOW_ID=$ROW["FLOW_ID"];
    $FLOW_TITLE=$ROW["FLOW_TITLE"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $RANKMAN=$ROW["RANKMAN"];
    $PARTICIPANT =$ROW["PARTICIPANT"];
    $GROUP_ID=$ROW["GROUP_ID"];
    $ANONYMITY=$ROW["ANONYMITY"]; 
    $CREATE_USER_ID =$ROW["CREATE_USER_ID"];

    if($ANONYMITY=="0")
       $ANONYMITY_DESC=_("不允许");
    else
       $ANONYMITY_DESC=_("允许");

    if($END_DATE=="0000-00-00")
       $END_DATE="";
      $RAN_NAME="";
      $TOK=strtok($RANKMAN,",");
      while($TOK!="")
      {
        if($RAN_NAME!="")
           $RAN_NAME.=_("，");
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
           $RAN_NAME.=$ROW["USER_NAME"];
           $RAN_NAME.="....";
        break;
        $TOK=strtok(",");
      }
      $PARTI_NAME="";
      $TOK=strtok($PARTICIPANT,",");
      while($TOK!="")
      {
        if($PARTI_NAME!="")
           $PARTI_NAME.=_("，");
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
           $PARTI_NAME.=$ROW["USER_NAME"];
           $PARTI_NAME.="....";
           break;
        $TOK=strtok(",");
      }

       $query1="select * from SCORE_GROUP where GROUP_ID='$GROUP_ID'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
           $GROUP_NAME=$ROW["GROUP_NAME"];
       else
       		$GROUP_NAME="<font color='red'>"._("指标集已删除或不存在")."</font>";
    if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
    {
       $VOTE_STATUS=1;
       $VOTE_STATUS_STR=_("待生效");
    }
    else
    {
       $VOTE_STATUS=2;
       $VOTE_STATUS_STR="<font color='#00AA00'><b>"._("生效")."</font>";
    }


    if($END_DATE!="")
    {
      if(compare_date($CUR_DATE,$END_DATE)>0)
      {
         $VOTE_STATUS=3;
         $VOTE_STATUS_STR="<font color='#FF0000'><b>"._("终止")."</font>";
      }
    }

    if($VOTE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$FLOW_TITLE?></td>
      <td align="center"><a href="javascript:show_man('<?=$FLOW_ID?>','1');" title="<?=_("点击查看所有的考核人员")?>"><?=$RAN_NAME?></a></td>
      <td align="center"><a href="javascript:show_man('<?=$FLOW_ID?>','0');" title="<?=_("点击查看所有的被考核人员")?>"><?=$PARTI_NAME?></a></td>
      <td align="center"><a href="javascript:show_reader('<?=$GROUP_ID?>');" title="<?=_("点击查看考核项目")?>"><?=$GROUP_NAME?></a></td>
      <td align="center"><?=$ANONYMITY_DESC?></td>
      <td nowrap align="center"><?=$BEGIN_DATE?></td>
      <td nowrap align="center"><?=$END_DATE?></td>

      <td nowrap align="center"><?=$VOTE_STATUS_STR?></td>
<?
			if($_SESSION["LOGIN_UID"]==1||$CREATE_USER_ID==$_SESSION["LOGIN_USER_ID"])
			{
?>
	      <td nowrap align="center">
	      	  <a href="copy.php?FLOW_ID=<?=$FLOW_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("克隆")?></a>
	      	  <a  href="javascript:show_labor('<?=$FLOW_ID?>');" title="<?=_("查阅情况")?>"> <?=_("考核情况")?></a>
	      	  <a href="new.php?FLOW_ID=<?=$FLOW_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("修改")?></a>
			  <a href="javascript:delete_vote('<?=$FLOW_ID?>','<?=$CUR_PAGE?>');"> <?=_("删除")?></a>
	<?
			      if($VOTE_STATUS==1)
			      {
	?>
	      			<a href="manage.php?FLOW_ID=<?=$FLOW_ID?>&OPERATION=1&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("立即生效")?></a>
	<?
			      }
			      else if($VOTE_STATUS==2)
			      {
	?>
	      			<a href="manage.php?FLOW_ID=<?=$FLOW_ID?>&OPERATION=2&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("立即终止")?></a>
	<?
			      }
			      else if($VOTE_STATUS==3)
			      {
	?>
	      			<a href="manage.php?FLOW_ID=<?=$FLOW_ID?>&OPERATION=3&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("恢复生效")?></a>
	<?
	      		}
	?>
	      </td>
<?
		  }
		  else
		  {
?>
	      <td nowrap align="center">
	      	  <a href="show.php?FLOW_ID=<?=$FLOW_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("查看")?></a>
	     	</td>
<?
			}
?>
    </tr>
<?
 }
?>



</table>
</body>

</html>

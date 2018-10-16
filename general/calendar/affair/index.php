<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-4-11 主服务查询
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER="";  

$HTML_PAGE_TITLE = _("周期性事务");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function delete_affair(AFF_ID)
{
 msg='<?=_("确认要删除该事务吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?AFF_ID=" + AFF_ID;
  window.location=URL;
 }
}

function my_note(AFF_ID)
{
  my_left=document.body.scrollLeft+event.clientX-event.offsetX-250;
  my_top=document.body.scrollTop+event.clientY-event.offsetY+150;

  window.open("note.php?AFF_ID="+AFF_ID,"note_win"+AFF_ID,"height=400,width=550,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+ my_top +",left="+ my_left +",resizable=yes");
}

</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("CALENDAR", 10);
$PAGE_SIZE=intval($PAGE_SIZE);
$PAGE_START=intval($PAGE_START);
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER))";
   $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>

<table border="0" cellspacing="0" cellpadding="3" class="small newAffairTitle">
  <tr>
    <td class="Big" style="width:140px"><span class="big3"> <?=_("周期性事务管理")?></span></td>
    <td style="text-align:left"><button type="button" class="btn" onClick="location='new/';" title="<?=_("新建周期性事务")?>"><?=_("新建周期性事务")?></button></td>
    <td align="right" ><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
  </tr>
</table>

<?
$CUR_DATE=date("Y-m-d",time());
 //============================ 显示事务 =======================================
 $query = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) order by BEGIN_TIME desc limit $PAGE_START, $PAGE_SIZE";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 $AFF_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $AFF_COUNT++;

    $AFF_ID=$ROW["AFF_ID"];
    $USER_ID=$ROW["USER_ID"];
    $BEGIN_TIME=$ROW["BEGIN_TIME"];
    $BEGIN_TIME=date("Y-m-d",$BEGIN_TIME);
    $END_TIME=$ROW["END_TIME"];
    if($END_TIME!=0)
    {
    $END_TIME=date("Y-m-d",$END_TIME);
    }
    $BEGIN_TIME_TIME=$ROW["BEGIN_TIME_TIME"];
    $END_TIME_TIME=$ROW["END_TIME_TIME"];
    $TYPE=$ROW["TYPE"];
    $REMIND_DATE=$ROW["REMIND_DATE"];
    $REMIND_TIME=$ROW["REMIND_TIME"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $TAKER=$ROW["TAKER"];
	
    $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
    $CONTENT=td_htmlspecialchars($CONTENT);

    switch($TYPE)
    {
     case "2":
         $TYPE_DESC=_("按日提醒");
         break;
     case "3":
         $TYPE_DESC=_("按周提醒");
         if($REMIND_DATE=="1")
            $REMIND_DATE=_("周一");
         elseif($REMIND_DATE=="2")
            $REMIND_DATE=_("周二");
         elseif($REMIND_DATE=="3")
            $REMIND_DATE=_("周三");
         elseif($REMIND_DATE=="4")
            $REMIND_DATE=_("周四");
         elseif($REMIND_DATE=="5")
            $REMIND_DATE=_("周五");
         elseif($REMIND_DATE=="6")
            $REMIND_DATE=_("周六");
         elseif($REMIND_DATE=="0")
            $REMIND_DATE=_("周日");
         break;
     case "4":
         $TYPE_DESC=_("按月提醒");
         $REMIND_DATE.=_("日");
         break;
     case "5":
         $TYPE_DESC=_("按年提醒");
         $REMIND_DATE=str_replace("-",_("月"),$REMIND_DATE)._("日");
         break;
    case "6":
        $TYPE_DESC=_("按工作日提醒");
        break;
    }

    if($AFF_COUNT==1)
    {
?>

    <table id="newAffairTable" class="table table-bordered table-hover" style="background-color:#fff" width="95%" align="center">
    <colgroup>
      <col width="120"></col><col width="120"></col><col width="120"></col><col width="120"></col><col width="120"></col><col width="120"></col><col width="120"></col><col width="auto"></col><col width="120"></col>
    </colgroup>
    <thead id="newAffairThead">
      <th nowrap align="center"><?=_("起始日期")?> <i class="icon-arrow-down"></i></th>
      <th nowrap align="center"><?=_("结束日期")?></th>
	  <th nowrap align="center"><?=_("开始时间")?></th>
      <th nowrap align="center"><?=_("结束时间")?></th>
      <th nowrap align="center" ><?=_("提醒类型")?></th>
      <th nowrap align="center" width="70"><?=_("提醒日期")?></th>
      <th nowrap align="center" width="70"><?=_("提醒时间")?></th>
      <th nowrap align="center"><?=_("事务内容")?></th>
      <th nowrap align="center" width="70"><?=_("操作")?></th>
   </thead>
<?
    }
    if($AFF_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr>
      <td align="center" width=120><?=$BEGIN_TIME?></td>
      <td align="center" width=120><?=$END_TIME?></td>
      <td align="center" width=120><?=$BEGIN_TIME_TIME?></td>
      <td align="center" width=120><?=$END_TIME_TIME?></td>
      <td nowrap align="center" width=120><?=$TYPE_DESC?></td>
      <td nowrap align="center"><?=$REMIND_DATE?></td>
      <td nowrap align="center"><?=$REMIND_TIME?></td>
      <td><div style="width:300px;display: inline-block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><a href="#" onClick="my_note(<?=$AFF_ID?>);"><?=csubstr(strip_tags($CONTENT),0,100);?> <? if(strlen($CONTENT)>200)echo "...";?></a></div><span></span></td>
      <td nowrap align="center">
      <?
         if(($MANAGER_ID=="" && $USER_ID==$_SESSION["LOGIN_USER_ID"])||$MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1)
         {
      ?>
          <a href="modify.php?AFF_ID=<?=$AFF_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>&nbsp;
          <a href="javascript:delete_affair(<?=$AFF_ID?>);"> <?=_("删除")?></a>
      <?
         }
         else
         {
       ?>
          <a href="#" onClick="my_note(<?=$AFF_ID?>);"><?=_("查看")?></a>
      <?      
         }
      ?>    
      </td>
    </tr>
<?
 }

 if($AFF_COUNT==0)
 {
   Message("",_("无事务记录"));
 }
 else
 {
?>
<!--   <thead id="newAffairThead">
      <th nowrap align="center"><?=_("起始日期")?> <i class="icon-arrow-down"></i></th>
      <th nowrap align="center"><?=_("结束日期")?></th>
	  <th nowrap align="center"><?=_("开始时间")?></th>
      <th nowrap align="center"><?=_("结束时间")?></th>
      <th nowrap align="center" ><?=_("提醒类型")?></th>
      <th nowrap align="center" width="70"><?=_("提醒日期")?></th>
      <th nowrap align="center" width="70"><?=_("提醒时间")?></th>
      <th nowrap align="center"><?=_("事务内容")?></th>
      <th nowrap align="center" width="70"><?=_("操作")?></th>
   </thead>-->
   </table>
<?
 }
?>

<table border="0" cellspacing="0" height="60" cellpadding="3" class="small newAffairTitle">
  <tr>
    <td class="Big"><span class="big3"> <?=_("周期性事务查询")?></span>
    </td>
  </tr>
</table>
	
<table id="searchTable" width="450" align="center">
  <form action="search.php"  method="post" name="form1">
    <tr>
      <td nowrap class="" width="100"> <?=_("日期：")?></td>
      <td class="">
        <input type="text" name="SEND_TIME_MIN" maxlength="10" value="" id="start_time" class="input-small" onClick="WdatePicker()" >
        <?=_("至")?>&nbsp;
        <input type="text" name="SEND_TIME_MAX" maxlength="10" value="" class="input-small" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})">
   
      </td>
    </tr>
    <tr>
      <td nowrap> <?=_("事务内容：")?></td>
      <td class="">
        <input id="affairContent" name="CONTENT" size="33" type="text">
      </td>
    </tr>
    <tr align="center" class="">
      <td colspan="2" nowrap>
        <button type="submit" class="btn btn-primary"><?=_("查询")?></button>
      </td>
    </tr>
  </table>
</form>
</body>
</html>

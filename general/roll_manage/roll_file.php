<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("查看文件");
include_once("inc/header.inc.php");
?>



<script>
function open_file(FILE_ID)
{
 URL="read_file.php?FILE_ID="+FILE_ID;
 myleft=(screen.availWidth-500)/2;
 mytop=150
 mywidth=550;
 myheight=300;
 window.open(URL,"read_file","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function delete_file(FILE_ID,CUR_PAGE)
{
 msg='<?=_("确认要销毁该项文件吗？")?>';
 if(window.confirm(msg))
 {
  URL="./delete_file.php?OP=1&ROLL_ID0=<?=$ROLL_ID?>&FILE_ID=" + FILE_ID + "&CUR_PAGE=" + CUR_PAGE;
  window.location=URL;
 }
}

function delete_all()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("file_select").length;i++)
  {

      el=document.getElementsByName("file_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("file_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要销毁文件，请至少选择其中一个。")?>");
     document.form1.reset();
     return;
  }


 msg='<?=_("确认要销毁已选中的文件吗？")?>';
 if(window.confirm(msg))
 {
  url="./delete_file_all.php?OP=1&DELETE_STR="+ delete_str +"&PAGE_START=<?=$PAGE_START?>&ROLL_ID0=<?=$ROLL_ID?>";
  window.location=url;
 }
}

function order_by(field,asc_desc)
{
 window.location="roll_file.php?CUR_PAGE=<?=$CUR_PAGE?>&ROLL_ID=<?=$ROLL_ID?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function check_all()
{
 for (i=0;i<document.getElementsByName("file_select").length;i++)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("file_select").item(i).checked=true;
   else
      document.getElementsByName("file_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("file_select").checked=true;
   else
      document.getElementsByName("file_select").checked=false;
 }
}

function change_roll()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("file_select").length;i++)
  {

      el=document.getElementsByName("file_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("file_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要移卷文件，请至少选择其中一个。")?>");
     document.form1.reset();
     return;
  }
  msg='<?=_("确认要移动该项文件吗？")?>';
  if(window.confirm(msg)){
    roll_id=document.getElementById("ROLL").value;
    url="change_roll.php?OP=1&DELETE_STR="+ delete_str +"&ROLL_ID="+roll_id+"&PAGE_START=<?=$PAGE_START?>&ROLL_ID0=<?=$ROLL_ID?>";
    location=url;
  }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox")[0].checked=false;
}
function export_all(ROLL_ID)
{
  url="export_all.php?ROLL_ID="+ ROLL_ID;
  window.open(url);
}
</script>


<body class="bodycolor">

<?
$query = "SELECT count(*) from RMS_FILE where ROLL_ID='$ROLL_ID' and DEL_USER=''";
   
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $RMS_FILE_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $RMS_FILE_COUNT=$ROW[0];

 if($RMS_FILE_COUNT==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("查看文件")?></span>&nbsp;
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("无文件"));
   exit;
 }
 
 $PER_PAGE=15;
 $PAGES=10;
 $PAGE_COUNT=ceil($RMS_FILE_COUNT/$PER_PAGE);

 if($CUR_PAGE<=0 || $CUR_PAGE=="")
    $CUR_PAGE=1;
 if($CUR_PAGE>$PAGE_COUNT)
    $CUR_PAGE=$PAGE_COUNT;

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("查看文件")?></span>&nbsp;
    </td>
    <td align="right" valign="bottom" class="small1"><?=sprintf(_("共%s条"),"<span class='big4'>&nbsp;".$RMS_FILE_COUNT."</span>&nbsp;")?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="roll_file.php?CUR_PAGE=1&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ROLL_ID=<?=$ROLL_ID?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("首页")?></a>&nbsp;
       <a class="A1" href="roll_file.php?CUR_PAGE=<?=$PAGE_COUNT?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROLL_ID=<?=$ROLL_ID?>"><?=_("末页")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
?>
       <a class="A1" href="roll_file.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROLL_ID=<?=$ROLL_ID?>"><?=sprintf(_("上%d页"),$PAGES)?></a>&nbsp;&nbsp;
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
       [<a class="A1" href="roll_file.php?CUR_PAGE=<?=$I?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROLL_ID=<?=$ROLL_ID?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
?>
       <a class="A1" href="roll_file.php?CUR_PAGE=<?=$I?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROLL_ID=<?=$ROLL_ID?>"><?=sprintf(_("下%d页"),$PAGES)?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="roll_file.php?CUR_PAGE=<?=$CUR_PAGE-1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROLL_ID=<?=$ROLL_ID?>"><?=_("上一页")?></a>&nbsp;
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
       <a class="A1" href="roll_file.php?CUR_PAGE=<?=$CUR_PAGE+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROLL_ID=<?=$ROLL_ID?>"><?=_("下一页")?></a>&nbsp;
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
<?
if($ASC_DESC=="")
   $ASC_DESC="1";
if($FIELD=="")
   $FIELD="FILE_CODE";
 //============================ 显示已发布文件 =======================================
$query = "SELECT * from RMS_FILE where ROLL_ID='$ROLL_ID' and DEL_USER=''";

$query .= " order by $FIELD";
if($ASC_DESC=="1")
   $query .= " desc";
else
   $query .= " asc";

if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
   
?>
<br>
<table class="TableBlock" width="100%">
  <form action="?"  method="post" name="form1">
  <tr class="TableHeader">
	  <td nowrap align="center"><input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"></td>
      <td nowrap align="center" onClick="order_by('FILE_CODE','<?if($FIELD=="FILE_CODE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("文件号")?></u><?if($FIELD=="FILE_CODE") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('FILE_TITLE','<?if($FIELD=="FILE_TITLE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("文件标题")?></u><?if($FIELD=="FILE_TITLE") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("密级")?></td>
      <td nowrap align="center" onClick="order_by('SEND_UNIT','<?if($FIELD=="SEND_UNIT"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发文单位")?></u><?if($FIELD=="SEND_UNIT"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('SEND_DATE','<?if($FIELD=="SEND_DATE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发文时间")?></u><?if($FIELD=="SEND_DATE") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("紧急等级")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>

<?
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $RMS_FILE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $RMS_FILE_COUNT++;
    
    if($RMS_FILE_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
       continue;
    if($RMS_FILE_COUNT>$CUR_PAGE*$PER_PAGE)
       break;

    $FILE_ID=$ROW["FILE_ID"];
    $FILE_CODE=$ROW["FILE_CODE"];
    $FILE_TITLE=$ROW["FILE_TITLE"];
    $SECRET=$ROW["SECRET"];
    $SEND_UNIT=$ROW["SEND_UNIT"];
    $SEND_DATE=$ROW["SEND_DATE"];
    $URGENCY=$ROW["URGENCY"];

    $FILE_TITLE=td_htmlspecialchars($FILE_TITLE);
	if($SEND_DATE=='0000-00-00') $SEND_DATE='';

    $SECRET=get_code_name($SECRET,"RMS_SECRET");
    $URGENCY=get_code_name($URGENCY,"RMS_URGENCY");
       
    if($RMS_FILE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
	  <td nowrap align="center"><input type="checkbox" name="file_select" value="<?=$FILE_ID?>" onClick="check_one(self);"></td>
      <td align="center">
      <a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_CODE?></a>
      </td>
      <td nowrap align="center"><?=$FILE_TITLE?></td>
      <td nowrap align="center"><?=$SECRET?></td>
      <td nowrap align="center"><?=$SEND_UNIT?></td>
      <td nowrap align="center"><?=$SEND_DATE?></td>
      <td nowrap align="center"><?=$URGENCY?></td>
      <td nowrap align="center">
      <a href="javascript:delete_file('<?=$FILE_ID?>','<?=$CUR_PAGE?>');"> <?=_("销毁")?></a>
      </td>
    </tr>
<?
 }
?>

<tr class="TableControl">
<td colspan="9" align="center">
	  <input type="button"  value="<?=_("导出")?>" class="SmallButton" onClick="export_all('<?=$ROLL_ID?>')" title="<?=_("导出")?>">
    <input type="button"  value="<?=_("批量销毁")?>" class="SmallButton" onClick="delete_all()" title="<?=_("销毁已选中文件")?>">

	
	<?=_("移卷至：")?><select id="ROLL" name=ROLL_ID onChange="change_roll();" class="SmallSelect">
		<option value=""><?=_("请选择案卷")?></option>
		<option value=0><?=_("移出本卷")?></option>
	<?	
	if($_SESSION["LOGIN_USER_PRIV"]=="1")
	{
		$query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where RMS_ROLL.STATUS=0";
    }
	else
	{
		$query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where (RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL.MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."') AND RMS_ROLL.STATUS=0";
	}
	$cursor= exequery(TD::conn(),$query, $connstatus);
	 while($ROW=mysql_fetch_array($cursor))
	 {	
		$ROLL_ID=$ROW["ROLL_ID"];
		$ROLL_CODE=$ROW["ROLL_CODE"];
		$ROLL_NAME=$ROW["ROLL_NAME"];
	?>
		<option value=<?=$ROLL_ID?>><?=$ROLL_CODE?> - <?=$ROLL_NAME?></option>
	<?
	 }	
	?>
	</select>
</td>
</tr>
</form>

</table>
</body>
</html>

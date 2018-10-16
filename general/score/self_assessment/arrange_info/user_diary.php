<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("工作日志查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
   if(document.form1.BEGIN_DATE.value=="")
   { alert("<?=_("起始日期不能为空！")?>");
     return (false);
   }

   if(document.form1.END_DATE.value=="")
   { alert("<?=_("截止日期不能为空！")?>");
     return (false);
   }

   return true;
}

function SaveFile(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  URL="/module/save_file?ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+ATTACHMENT_NAME+"&A=1";
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  window.open(URL,null,"height=180,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes");
}
</script>

<body class="bodycolor">

<?
$BEGIN_DATE=date("Y-m-01",time());
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from USER where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);

if($ROW=mysql_fetch_array($cursor))
   $USER_NAME=$ROW["USER_NAME"];
?>

<div align="center" class="Big1">
<b>[<?=$USER_NAME?> - <?=_("工作日志查询")?>]</b>
</div>

<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3"> <?=_("最近的10篇日志")?></span>
    </td>
  </tr>
</table>

<?
//============================ 显示日志 =======================================
$query = "SELECT * from DIARY where USER_ID='$USER_ID' and DIA_TYPE!='2' order by DIA_DATE desc";
$cursor= exequery(TD::conn(),$query, $connstatus);
$DIA_COUNT=0;

while($ROW=mysql_fetch_array($cursor))
{
   $DIA_COUNT++;

   if($DIA_COUNT>10)
      break;

   $DIA_ID=$ROW["DIA_ID"];
   $DIA_DATE=$ROW["DIA_DATE"];
   $DIA_DATE=strtok($DIA_DATE," ");
   $SUBJECT=$ROW["SUBJECT"];
   $CONTENT=$ROW["CONTENT"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   if($SUBJECT=="")
      $SUBJECT=csubstr(strip_tags($CONTENT),0,50).(strlen($CONTENT)>50?"...":"");

   if($DIA_COUNT==1)
   {
?>

   <table width="95%" class="TableList">

<?
   }
   if($DIA_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
   <tr class="<?=$TableLine?>">
     <td nowrap align="center" width="100"><?=$DIA_DATE?></td>
     <td><a href="read.php?DIA_ID=<?=$DIA_ID?>&USER_NAME=<?=$USER_NAME?>"><?=$SUBJECT?></a></td>
      <td>
<?
$ATTACH_ARRAY = trim_inserted_image("", $ATTACHMENT_ID, $ATTACHMENT_NAME);
if($ATTACH_ARRAY["NAME"]!="")
{
	 echo attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],0,1,1,0,0,1,1,0,"diary");
}
?>
      </td>
     <td nowrap align="center" width="60"><a href="comment.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>"><?=_("点评")?></a></td>
   </tr>
<?
}//while

if($DIA_COUNT==0)
{
  Message("",_("无日志记录"));
  exit;
}
else
{
?>
  <thead class="TableHeader">
     <td nowrap align="center"><?=_("日期")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
     <td nowrap align="center"><?=_("日志标题")?></td>
      <td nowrap align="center"><?=_("附件")?></td>
     <td nowrap align="center"><?=_("操作")?></td>
  </thead>
  </table>
<?
}
?>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<form action="search.php" name="form1" onsubmit="return CheckForm();">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3"> <?=_("日志查询")?></span>
    </td>
  </tr>
</table>

<table width="400" align="center" class="TableBlock">
  <tr>
    <td nowrap class="TableData"><?=_("起始日期：")?></td>
    <td class="TableData"><input type="text" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()"/></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("截止日期：")?></td>
    <td class="TableData"><input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$CUR_DATE?>" onClick="WdatePicker()"/></td>
  </tr>
    <tr>
      <td nowrap class="TableData"><?=_("标题：")?></td>
      <td class="TableData"><input type="text" name="SUBJECT" class="BigInput" size="20"></td>
    </tr>
  <tr>
    <td nowrap class="TableData"><?=_("关键词1：")?></td>
    <td class="TableData"><input type="text" name="KEY1" class="BigInput" size="20"></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("关键词2：")?></td>
    <td class="TableData"><input type="text" name="KEY2" class="BigInput" size="20"></td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("关键词3：")?></td>
    <td class="TableData"><input type="text" name="KEY3" class="BigInput" size="20"></td>
  </tr>
  <tfoot align="center" class="TableFooter">
    <td nowrap colspan="2">
        <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
        <input type="hidden" name="USER_NAME" value="<?=$USER_NAME?>">
        <input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("进行查询")?>" name="button">
    </td>
  </tfoot>
  </form>
</table>

</body>
</html>

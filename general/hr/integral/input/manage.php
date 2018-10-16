<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../func.func.php");
$connstatus = ($connstatus) ? true : false;
function div_str($str)
{
	if(strlen($str) <= 20 )
		return $str;

	$str_tem=csubstr($str,0,20);
	return $str_tem."...";
}

$HTML_PAGE_TITLE = _("积分录入管理");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
function delete_care(ID)
{
  msg='<?=_("确认要删除该已录入积分吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?ID=" + ID+"&PAGE_START=<?=$PAGE_START?>&INTEGRAL_TYPE1=<?=$INTEGRAL_TYPE1?>";
     window.location=URL;
  }
}

function check_all()
{
   for(i=0;i<document.getElementsByName("email_select").length;i++)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").item(i).checked=true;
      else
         document.getElementsByName("email_select").item(i).checked=false;
   }

   if(i==0)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").checked=true;
      else
         document.getElementsByName("email_select").checked=false;
   }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}

function get_checked()
{
   checked_str="";
   for(i=0;i<document.getElementsByName("email_select").length;i++)
   {

      el=document.getElementsByName("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
   }

  if(i==0)
  {
      el=document.getElementsByName("email_select");
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
}

function delete_mail()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("要删除已录入积分，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该项已录入积分吗？")?>';
  if(window.confirm(msg))
  {
    var INTEGRAL_TYPE = document.getElementById("INTEGRAL_TYPE1").value;
    url="delete.php?ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>&INTEGRAL_TYPE1="+INTEGRAL_TYPE;
    window.location=url;
  }
}

function change_type(type)
{
   window.location="manage.php?start=<?=$start?>&INTEGRAL_TYPE1="+type;
}
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);
if($INTEGRAL_TYPE1=="")
	$INTEGRAL_TYPE1=3;
/*
$WHERE_STR = hr_priv("BY_STAFFS");
if($TYPE!="")
   $WHERE_STR .= " and TYPE='$TYPE'";
   */
if($INTEGRAL_TYPE1!="")
	$WHERE_STR.=" and INTEGRAL_TYPE='$INTEGRAL_TYPE1' ";
if($_SESSION["LOGIN_USER_PRIV"]!=1)
	$WHERE_STR.=" and (CREATE_PERSON='".$_SESSION["LOGIN_USER_ID"]."' or CREATE_USER='".$_SESSION["LOGIN_USER_ID"]."')  ";
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_INTEGRAL_DATA where 1=1 ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理已录入积分")?></span>&nbsp;
        <select name="INTEGRAL_TYPE1" onChange="change_type(this.value)" id="INTEGRAL_TYPE1">
          <option <?=$INTEGRAL_TYPE1=="3"?"selected":""?> value="3"><?=_("自定义项积分录入")?></option>
          <option <?=$INTEGRAL_TYPE1=="0"?"selected":""?> value="0"><?=_("未定义项积分录入")?></option>
        </select>
    </td>
<?
if($TOTAL_ITEMS>0)
{
?>
    <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
<?
}
?>
    </tr>
</table>
<div align="center">
<?
if($TOTAL_ITEMS>0)
{
?>
<table class="TableList" width="95%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("项目类型")?></td>
      <td nowrap align="center"><?=_("得分人")?></td>
      <td nowrap align="center"><?=_("分值")?></td>
      <td nowrap align="center"><?=_("打分人")?></td>
      <td nowrap align="center"><?=_("打分日期")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}

$query = "SELECT * from HR_INTEGRAL_DATA where 1=1 ".$WHERE_STR." order by CREATE_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
	//print_r($ROW);
   $ID=$ROW["ID"];
   $ITEM_ID=$ROW["ITEM_ID"];
   $INTEGRAL_REASON=$ROW["INTEGRAL_REASON"];
   $INTEGRAL_TYPE=$ROW["INTEGRAL_TYPE"];
   $USER_ID=$ROW["USER_ID"];
   $INTEGRAL_DATA=$ROW["INTEGRAL_DATA"];
   $CREATE_PERSON=$ROW["CREATE_PERSON"];
   $CREATE_TIME=$ROW["CREATE_TIME"];
   $INTEGRAL_TIME=$ROW["INTEGRAL_TIME"];
   $CREATE_USER=$ROW["CREATE_USER"];
   $ITEM_NAME=$ITEM_ID==0?_("未定义项"):getItemName($ITEM_ID);
   $STATUS=$ROW["STATUS"];
   $USER_NAME = substr(GetUserNameById($USER_ID),0,-1);
   $CREATE_PERSON_NAME = substr(GetUserNameById($CREATE_PERSON),0,-1);
?>
   <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$ID?>" onClick="check_one(self);">
      <td align="center" nowrap><?=$ITEM_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td align="center"><?=$INTEGRAL_DATA?></td>
      <td nowrap align="center"><?=$CREATE_PERSON_NAME?></td>
      <td nowrap align="center"><?=$CREATE_TIME?></td>
      <td align="center" nowrap>
      	<a href="detail.php?ID=<?=$ID?>"> <?=_("查看")?></a>&nbsp;
<?
	if($INTEGRAL_TYPE==0||$INTEGRAL_TYPE==3)
	{
?>
      <a href="modify.php?ID=<?=$ID?>&INTEGRAL_TYPE1=<?=$INTEGRAL_TYPE?>"> <?=_("修改")?></a>&nbsp;
<? } ?>
	  <a href="javascript:delete_care(<?=$ID?>);"> <?=_("删除")?></a>&nbsp;
      </td>
   </tr>
<?
}

if($TOTAL_ITEMS>0)
{
?>
   <tr class="TableControl" style="text-align: left;">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选已录入积分")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp;
     </td>
   </tr>
<?
}else{
   Message("",_("无积分记录"));
}
?>
</div>
</table>
</body>

</html>

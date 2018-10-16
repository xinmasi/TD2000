<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
if(!isset($TYPE))
   $TYPE="0";
if(!isset($PAGE_SIZE))
   $PAGE_SIZE =15;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("资源申请");
include_once("inc/header.inc.php");
?>

<Script language="JavaScript">
function order_by(field,asc_desc,NAME)
{
  window.location="index.php?PAGE_START=<?=$PAGE_START?>&FIELD="+field+"&ASC_DESC="+asc_desc+"&NAME="+NAME;
}
function delete_source(SOURCEID)
{
	msg='<?=_("确认要删除该资源吗？")?>';
	if(window.confirm(msg))
	{
		URL="delete.php?SOURCEID="+SOURCEID;
		window.location=URL;
	}
}
function empty_source(SOURCEID)
{
	msg='<?=_("确认要清空该资源申请的历史数据吗？")?>';
	if(window.confirm(msg))
	{
		URL="delete.php?EMPTY=1&SOURCEID="+SOURCEID;
		window.location=URL;
	}
}
function check_all()
{
	for (i=0;i<document.getElementsByName("source_select").length;i++)
	{
		if(document.getElementById("allbox_for").checked)
			document.getElementsByName("source_select").item(i).checked=true;
		else
			document.getElementsByName("source_select").item(i).checked=false;
	}
	if(i==0)
	{
		if(document.getElementById("allbox_for").checked)
			document.getElementsByName("source_select").checked=true;
		else
			document.getElementsByName("source_select").checked=false;
	}
}
function check_one(el)
{
	if(!el.checked)
		document.getElementById("allbox_for").checked=false;
}
function get_checked()
{
	delete_str="";
	for(i=0;i<document.getElementsByName("source_select").length;i++)
	{
		el=document.getElementsByName("source_select").item(i);
		if(el.checked)
		{ 
			val=el.value;
			delete_str+=val + ",";
		}
	}
	if(i==0)
	{
		el=document.getElementsByName("source_select");
		if(el.checked)
		{  
			val=el.value;
			delete_str+=val + ",";
		}
	}
	return delete_str;
}
function delete_mail()
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("要删除资源，请至少选择其中一条。")?>");
		return;
	}
	msg='<?=_("确认要删除所选资源吗？")?>';
	if(window.confirm(msg))
	{
		url="delete.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
		location=url;
	}
}
function newwin(url)
{
	myleft=(screen.availWidth-500)/2;
	var newwin=window.open(url,"newwin","toolbar=no,location=no,left="+myleft+",top=100,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=400");
	newwin.focus();
}
function newwin2(url)
{
	myleft=(screen.availWidth-820)/2;
	var newwin=window.open(url,"newwin","toolbar=no,location=no,left="+myleft+",top=20,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=820,height=550");
	newwin.focus();
}
function change_name(name)
{
	window.location="index.php?start=<?=$start?>&NAME="+name+"";
}
</script>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr>
		<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/source.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("可申请资源列表")?> </span>
<?
		if($_SESSION["LOGIN_USER_PRIV"]=="1")
		{
?>
	   &nbsp;<input type="button" value="<?=_("添加资源")?>" class="SmallButton" onClick="newwin('add.php')">
<?
    	}
?>
		</td>
	</tr>
</table>
<?
//----- 资源列表 ------
if(!isset($TOTAL_ITEMS))
{
	$query = "SELECT count(*) from OA_SOURCE where (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VISIT_USER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGE_USER) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',VISIT_PRIV) or '".$_SESSION["LOGIN_USER_PRIV"]."'='1') ";
	if($NAME!="")
		$query.=" and SOURCENAME like '%".$NAME."%'  ";
	$cursor= exequery(TD::conn(),$query);
	$TOTAL_ITEMS=0;
	if($ROW=mysql_fetch_array($cursor))
		$TOTAL_ITEMS=$ROW[0];
}
if($TOTAL_ITEMS==0)
{
	Message("",_("没有可供申请的系统资源"));
	
?>
<!--<center><input type="button" class="smallButton" value="<?=_("返回")?>" onClick="window.location='index.php'"></center>-->
<?
	exit;
}
?>
<form name="form1">
<table border="0" width="80%" class="small"  align="center">
	<tr>
		<td>
      <b><?=_("检索名称：")?></b><input type="text" name="NAME" size="20" maxlength="10" class="BigInput" value="<?=$NAME?>">&nbsp; <input type="button" class="SmallButton" value="<?=_("查询")?>" onClick="change_name(NAME.value)">
		</td>
    	<td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
	</tr>
</table>
</form>
<?
if($ASC_DESC=="")
   $ASC_DESC="0";
if($FIELD=="")
   $FIELD="SOURCENO";
$query = "select * from OA_SOURCE  where 1=1 ";
if($NAME!="")
	$query.=" and SOURCENAME like '%".$NAME."%' ";


$query .= " order by $FIELD";
if($ASC_DESC=="1")
   $query .= " desc";
else
   $query .= " asc";
$query .= " limit $start,$PAGE_SIZE";

if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";

$cursor = exequery(TD::conn(),$query);
$COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{  
	$SOURCENO=$ROW["SOURCENO"];
	$SOURCEID = $ROW["SOURCEID"];
	$SOURCENAME = $ROW["SOURCENAME"];
	$VISIT_USER = $ROW["VISIT_USER"];
	$VISIT_PRIV = $ROW["VISIT_PRIV"];
	$MANAGE_USER = $ROW["MANAGE_USER"];
   if(!find_id($VISIT_USER,$_SESSION["LOGIN_USER_ID"])&&!find_id($MANAGE_USER,$_SESSION["LOGIN_USER_ID"])&&!find_id($VISIT_PRIV,$_SESSION["LOGIN_USER_PRIV"])&&$_SESSION["LOGIN_USER_PRIV"]!="1")
		continue;
   $COUNT++;
   if($COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
   if($COUNT==1)
   {
?>
	<table width=80% class="TableList"  align="center">
		<tr class="TableHeader">
	<?
	  	if($_SESSION["LOGIN_USER_PRIV"]=="1")
	  	{
	?>
	   	<td  nowrap align="center" width="30px"><?=_("选择")?></td>
	<?
	   }
	?>
			<td nowrap align="center"  style="cursor:hand;" onClick="order_by('SOURCENO','<?if($FIELD=="SOURCENO") echo 1-$ASC_DESC;else echo "1";?>','<?=$NAME?>');"><u><?=_("序号")?></u><?if($FIELD=="SOURCENO") echo $ORDER_IMG;?> </td>
			<td  align="center"> <?=_("资源名称")?> </td>
	<?
		if($_SESSION["LOGIN_USER_PRIV"]=="1" || ($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]=="2"))
	  	{
	?>
			<td nowrap align="center" width="400px"><?=_("操作")?></td>
	<?
		}
	?>
		</tr>
	<?
	}
   ?>
		<tr class=<?=$TableLine?>>
		<?
		if($_SESSION["LOGIN_USER_PRIV"]=="1")
		{
		?>
			<td nowrap ><input type="checkbox" name="source_select" value="<?=$SOURCEID?>" onClick="check_one(self);"></td>
		<?
		}
		?>
			<td nowrap align="center"><?=$SOURCENO?></td>
         <td  align="center" ><a href="javascript:newwin2('apply.php?SOURCEID=<?=$SOURCEID?>')"><?=$SOURCENAME?></a></td>
<?
  if($_SESSION["LOGIN_USER_PRIV"]=="1")
  {
?>
			<td nowrap align="center" >
				<a href="javascript:newwin('add.php?SOURCEID=<?=$SOURCEID?>')"><?=_("编辑")?></a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:newwin('set_visit_user.php?SOURCEID=<?=$SOURCEID?>')"><?=_("用户权限")?></a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:newwin('set_visit_priv.php?SOURCEID=<?=$SOURCEID?>')"><?=_("角色权限")?></a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:newwin('set_manage_user.php?SOURCEID=<?=$SOURCEID?>')"><?=_("设置管理员")?></a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:empty_source(<?=$SOURCEID?>)"><?=_("清空")?></a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:delete_source(<?=$SOURCEID?>)"><?=_("删除")?></a>
			</td>
<?
    }
    if($_SESSION["LOGIN_USER_PRIV"]!="1" && $_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]=="2")
    {
?>
        <td nowrap align="center" >
				<a href="javascript:newwin('set_visit_user.php?SOURCEID=<?=$SOURCEID?>')"><?=_("用户权限")?></a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:newwin('set_visit_priv.php?SOURCEID=<?=$SOURCEID?>')"><?=_("角色权限")?></a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:newwin('set_manage_user.php?SOURCEID=<?=$SOURCEID?>')"><?=_("设置管理员")?></a>&nbsp;&nbsp;&nbsp;
        </td>
<?
    }
?>

		</tr>
<?
}
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
		<tr class="TableControl">
			<td colspan="4">
	  			<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
    			<label for="allbox_for"><?=_("全选")?></label> &nbsp;
    			<a href="javascript:delete_mail();" title="<?=_("删除所选短消息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp;
    
			</td>
		</tr>
<?
}
?>
</table>
</BODY>
</HTML>

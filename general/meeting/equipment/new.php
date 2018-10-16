<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");

if($EQUIPMENT_ID!="")
   $TITLE_DESC = _("编辑设备");
else
   $TITLE_DESC = _("新建设备");

$HTML_PAGE_TITLE = $TITLE_DESC;
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">
function CheckForm()
{
  if(document.form1.EQUIPMENT_NO.value=="")
  {  alert("<?=_("设备编号不能为空！")?>");
     return (false);
  }
  if(document.form1.EQUIPMENT_NAME.value=="")
  {  alert("<?=_("设备名称/型号不能为空！")?>");
     return (false);
  }
  return (true);
}

function show()
{
	var obj = document.getElementById("show");
	if(obj.style.display=='none') 
	   obj.style.display=''; 
	else 
	   obj.style.display='none';	
}

function sendForm()
{
  if(CheckForm())
     document.form1.submit();
}
</script>

<body class="bodycolor" onLoad="form1.EQUIPMENT_NO.focus();">
<?

if($EQUIPMENT_ID!="")
{
  $query = "SELECT * from MEETING_EQUIPMENT where EQUIPMENT_ID='$EQUIPMENT_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
     $EQUIPMENT_ID=$ROW["EQUIPMENT_ID"];
     $EQUIPMENT_NO=$ROW["EQUIPMENT_NO"];
     $EQUIPMENT_NAME=$ROW["EQUIPMENT_NAME"];
     $EQUIPMENT_STATUS=$ROW["EQUIPMENT_STATUS"];
     $REMARK=$ROW["REMARK"];
     $GROUP_YN =$ROW["GROUP_YN"];
     $GROUP_NO=$ROW["GROUP_NO"];
     $M_ROOM=$ROW["MR_ID"];     
  }
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" HEIGHT="20"><span class="big3"> <?=$TITLE_DESC?></span>
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="add.php" method="post" name="form1">
<table align="center" width="660" class="TableBlock">
   <tr>
     <td nowrap class="TableData"><?=_("设备编号：")?></td>
     <td class="TableData">
       <input type="text" name="EQUIPMENT_NO" maxlength="100" class="BigInput" value="<?=$EQUIPMENT_NO?>">
     </td>
     <td nowrap class="TableData"><?=_("设备状态：")?></td>
     <td class="TableData">
       <select name="EQUIPMENT_STATUS">
       	 <option value="1" <?if($EQUIPMENT_STATUS=="1" || $EQUIPMENT_STATUS=="") echo "selected";?>><?=_("可用")?></option>
       	 <option value="0" <?if($EQUIPMENT_STATUS=="0") echo "selected";?>><?=_("不可用")?></option>       	 
       </select>
     </td>     
   </tr>
   <tr>
     <td nowrap class="TableData"><?=_("设备名称/型号：")?></td>
     <td class="TableData">
       <input type="text" name="EQUIPMENT_NAME" size="30" maxlength="100" class="BigInput" value="<?=$EQUIPMENT_NAME?>">
     </td>
     <td nowrap class="TableData"><?=_("同类设备：")?></td>
     <td class="TableData">
       <select name="GROUP_YN" onChange="show();">
       	 <option value="0" <?if($GROUP_YN==0 || $GROUP_YN=="") echo "selected";?>><?=_("没有")?></option>
       	 <option value="1" <?if($GROUP_YN==1) echo "selected";?>><?=_("有")?></option>       	 
       </select>
     </td>        
   </tr>
   <tr id="show" style="<?if($GROUP_YN==0 || $GROUP_YN=="") echo "display:none";?>">
     <td nowrap class="TableData"><?=_("同类设备名称：")?></td>
     <td class="TableData" colspan="3">
     	 <select name="GROUP_NO" class="BigSelect">
         <?=code_list("MEETING_EQUIPMENT","")?>
       </select>&nbsp;       
       <?=_("同类设备名称可在“系统管理”->“系统代码设置”模块设置。")?>
     </td>      
   </tr>      
   <tr>
     <td nowrap class="TableData"><?=_("所属会议室：")?></td>
     <td class="TableData" colspan="3">
    	<select name="MR_ID" class="BigSelect">
<?
$query = "SELECT MR_ID,MR_NAME from MEETING_ROOM";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $MR_ID=$ROW["MR_ID"];
  $MR_NAME=$ROW["MR_NAME"];
?>
      <option value="<?=$MR_ID?>" <? if($M_ROOM==$MR_ID) echo "selected";?>><?=$MR_NAME?></option>
<?
}
?>
     </td>      
   </tr>
   <tr>
     <td nowrap class="TableData" colspan="4"><?=_("设备描述：")?></td>
   </tr>   
   <tr id="EDITOR">
    <td class="TableData" colspan="4">
<?
$editor = new Editor('REMARK') ;
$editor->Height = '200';
$editor->Config = array('model_type' => '08');
$editor->Value = $REMARK ;
$editor->Create() ;
?>
    </td>
  </tr>
  <tr class="TableControl">
    <td nowrap colspan="4" align="center">
    	<input type="hidden" name="EQUIPMENT_ID" Value="<?=$EQUIPMENT_ID?>">
    	<input type="hidden" name="ZHEFROM" Value="<?=$ZHEFROM?>">
      <input type="button" value="<?=_("确定")?>" class="BigButton" onclick="sendForm();">&nbsp;&nbsp;&nbsp;
<?
if($EQUIPMENT_ID!="" && $ZHEFROM!=1)
{
?>
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
<?
}
if($ZHEFROM==1)
{
?>
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php'">

<?
}
?>
    </td>
  </tr>
  </table>
</form>
</body>
</html>
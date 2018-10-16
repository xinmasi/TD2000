<?
//-----zfc-------
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query = "select * from PROJ_FILE_SORT where PROJ_ID='$PROJ_ID' AND SORT_ID='$SORT_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	 $SORT_NAME=$ROW["SORT_NAME"];
   $VIEW_USER=$ROW["VIEW_USER"];
   $MANAGE_USER=$ROW["MANAGE_USER"];
   $NEW_USER=$ROW["NEW_USER"];
   $MODIFY_USER=$ROW["MODIFY_USER"];
}

$HTML_PAGE_TITLE = _("指定可访问人员");
include_once("inc/header.inc.php");
?>


<script>
var $ = function(id) {return document.getElementById(id);};
function priv_submit()
{
	var tab=$("priv_tab");
	for(i=1;i<tab.rows.length-1;i++)
	{
		var user_id=tab.rows[i].id;
		if(tab.rows[i].cells[2].firstChild.checked==true)
		  $("new_user").value += user_id+",";
		if(tab.rows[i].cells[3].firstChild.checked==true)
		  $("manage_user").value += user_id+",";
		if(tab.rows[i].cells[4].firstChild.checked==true)
		  $("modify_user").value += user_id+",";
		if(tab.rows[i].cells[5].firstChild.checked==true)
		  $("view_user").value += user_id+",";
	}
	document.form1.submit();
}
function check_one(obj)
{
	var tr=obj.parentNode.parentNode;
	if(obj.checked==true)
	{
	  for(var i=1;i<tr.cells.length-1;i++)
	    tr.cells[i].firstChild.checked=true;
	}
	else
	{
		for(var i=1;i<tr.cells.length-1;i++)
	    tr.cells[i].firstChild.checked=false;
	}
}
function check_all(obj)
{
	var tab=$("priv_tab");
	if(obj.checked==true)
	{
  	for(var i=1;i<tab.rows.length-1;i++)
  	{
  		tab.rows[i].cells[5].firstChild.checked=true;
  		check_one(tab.rows[i].cells[5].firstChild); 
  	}
  }
  else
  {
  	for(var i=1;i<tab.rows.length-1;i++)
  	{
  		tab.rows[i].cells[5].firstChild.checked=false;
  		check_one(tab.rows[i].cells[5].firstChild); 
  	}  	
  }	
  		
}
</script>


<body class="bodycolor">

<?

$query = "select PROJ_USER,PROJ_VIEWER,PROJ_PRIV from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	 $PROJ_USER=$ROW["PROJ_USER"];
	 $PROJ_PRIV=$ROW["PROJ_PRIV"];
	 $PROJ_VIEWER=$ROW["PROJ_VIEWER"];
}

$PROJ_USER_STR=str_replace("|","",$PROJ_USER);
$PROJ_VIEWER_STR=str_replace("|","",$PROJ_VIEWER);
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="small">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
        <span class="big3"> <?=_("指定权限")?></span>
        <span style="background:#FfCf5f; margin-left:79px; padding:5px;">项目成员</span>
        <span style="background:#Fcacac;  padding:5px;">项目查看者</span>
    </td>
  </tr>
</table>
<?
if($PROJ_USER_STR==""&&$PROJ_VIEWER_STR=="")
{
   Message(_("错误"),_("尚未定义项目成员或项目查看者!"));
   Button_back();
   exit;
}
?>
<form method="post" action="submit.php" name="form1">
<table class="TableList" width="70%" align="center" id="priv_tab">
  <tr class="TableHeader">
		<td nowrap align="center" width="120"><?=_("人员名称")?></td>
        <td  nowrap align="center">身份</td>
    <td nowrap align="center"><img src="<?=MYOA_STATIC_SERVER?>/static/images/project/add.gif" align="absmiddle"><?=_("新建")?></td>
    <td nowrap align="center"><img src="<?=MYOA_STATIC_SERVER?>/static/images/project/del.gif" align="absmiddle"><?=_("删除")?></td>
    <td nowrap align="center"><img src="<?=MYOA_STATIC_SERVER?>/static/images/project/modify.gif" align="absmiddle"><?=_("修改")?></td>
    <td nowrap align="center"><img src="<?=MYOA_STATIC_SERVER?>/static/images/project/view.gif" align="absmiddle"><?=_("查看")?></td>
    <td nowrap align="center"><input type="checkbox" name="checkAll" onclick="check_all(this);"><?=_("全选")?></td>
  </tr>
<?
$COUNT=0;
$query = "select USER_ID,USER_NAME FROM USER WHERE (FIND_IN_SET(USER_ID,'$PROJ_USER_STR') || FIND_IN_SET(USER_ID,'$PROJ_VIEWER_STR')) ";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	if($COUNT%2==0)
  	 $TableLine='TableLine1';
  else
  	 $TableLine='TableLine2';
  	 
	 $USER_ID=$ROW["USER_ID"];
	 $USER_NAME=$ROW["USER_NAME"];
	 $COUNT++;
?>
  <tr class="<?=$TableLine?>" id="<?=$USER_ID?>">
		<td nowrap align="center" width="120"><?=$USER_NAME?></td>
        <td  nowrap align="center" valign="middle" >
            <ul style="width:20px; margin:0px auto;">
            <?
            if(find_id($PROJ_USER_STR,$USER_ID)){
            ?>
                <li style="width:10px; height:10px; background:#FfCf5f; float:left;"></li>
            <?
            }
            if(find_id($PROJ_VIEWER_STR,$USER_ID)){
            ?>
                 <li style="width:10px; height:10px; background:#Fcacac;  float:left;"></li>
            <?
            }
            ?>
            </ul>
         </td>
    <td nowrap align="center" valign="middle"><input type="checkbox" <?if(find_id($NEW_USER,$USER_ID)) echo "checked";?>></td>
    <td nowrap align="center" valign="middle"><input type="checkbox" <?if(find_id($MANAGE_USER,$USER_ID)) echo "checked";?>></td>
    <td nowrap align="center" valign="middle"><input type="checkbox" <?if(find_id($MODIFY_USER,$USER_ID)) echo "checked";?>></td>
    <td nowrap align="center" valign="middle"><input type="checkbox" <?if(find_id($VIEW_USER,$USER_ID)) echo "checked";?>></td>
    <td nowrap align="center"><input type="checkbox" name="checkAll" onclick="check_one(this);"></td>
  </tr>
<?
}
?>
   <tr>
    <td nowrap  class="TableControl" colspan="7" align="center">
    	<input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>">
      <input type="hidden" name="SORT_ID" value="<?=$SORT_ID?>">
      <input type="hidden" name="new_user" id="new_user" value="">
      <input type="hidden" name="manage_user" id="manage_user" value="">
      <input type="hidden" name="modify_user" id="modify_user" value="">
      <input type="hidden" name="view_user" id="view_user" value="">
        <input type="button" value="<?=_("确定")?>" class="BigButton" onclick="priv_submit();">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='../index.php?PROJ_ID=<?=$PROJ_ID?>'">
    </td>
  </tr>
</form>
</table>

</body>
</html>

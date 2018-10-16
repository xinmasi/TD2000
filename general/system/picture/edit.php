<?
include_once("inc/auth.inc.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("编辑图片目录");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TO_ID.value==""&&document.form1.PRIV_ID.value==""&&document.form1.COPY_TO_ID.value=="")
   {
   	 alert("<?=_("请至少指定一种发布范围！")?>");
     return (false);
   }

   if(document.form1.PIC_NAME.value=="")
   {
   	 alert("<?=_("图片目录名称不能为空！")?>");
     return (false);
   }

   if(document.form1.PIC_PATH.value=="")
   {
   	 alert("<?=_("图片目录路径不能为空！")?>");
     return (false);
   }

   return (true);
}

</script>


<body class="bodycolor" onload="document.form1.PIC_NAME.focus();">

<?
$query = "SELECT * from PICTURE where PIC_ID='$PIC_ID'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
   $PIC_ID=$ROW["PIC_ID"];
   $PIC_NAME=$ROW["PIC_NAME"];
   $PIC_PATH=$ROW["PIC_PATH"];
   $TO_DEPT_ID=$ROW["TO_DEPT_ID"];
   $TO_PRIV_ID=$ROW["TO_PRIV_ID"];
   $TO_USER_ID=$ROW["TO_USER_ID"];
   $ROW_PIC = $ROW["ROW_PIC"];
   $ROW_PIC_NUM = $ROW["ROW_PIC_NUM"];

   $query="select * from DEPARTMENT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
       $DEPT_ID=$ROW["DEPT_ID"];
       $DEPT_NAME=$ROW["DEPT_NAME"];
       if(find_id($TO_DEPT_ID,$DEPT_ID))
          $TO_DEPT_NAME.=$DEPT_NAME.",";
   }

   if($TO_DEPT_ID=="ALL_DEPT")
      $TO_DEPT_NAME=_("全体部门");

   $TOK=strtok($TO_PRIV_ID,",");
   while($TOK!="")
   {
      $query1 = "SELECT * from USER_PRIV where USER_PRIV='$TOK'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
         $PRIV_NAME.=$ROW["PRIV_NAME"].",";
      $TOK=strtok(",");
   }

   $TOK=strtok($TO_USER_ID,",");
   while($TOK!="")
   {
      $query1 = "SELECT * from USER where USER_ID='$TOK'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
         $USER_NAME.=$ROW["USER_NAME"].",";
      $TOK=strtok(",");
   }
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑图片目录")?></span>
    </td>
  </tr>
</table>

<br>
 <table class="TableBlock" width="85%" align="center">
  <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><?=_("发布范围（部门）：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_DEPT_ID?>">
        <textarea cols=40 name=TO_NAME rows=3 class="BigStatic" wrap="yes" readonly><?=$TO_DEPT_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("发布范围（角色）：")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="<?=$TO_PRIV_ID?>">
        <textarea cols=40 name="PRIV_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("发布范围（人员）")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="<?=$TO_USER_ID?>">
        <textarea cols=40 name="COPY_TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('116','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("图片目录名称：")?></td>
      <td class="TableData">
        <input type="text" name="PIC_NAME" size="36" class="BigInput" value="<?=$PIC_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("图片目录路径：")?></td>
      <td class="TableData">
        <input type="text" name="PIC_PATH" size="36" class="BigInput" value="<?=$PIC_PATH?>"> <?=_("说明：OA服务器的本地路径(如D:\MYOA)")?>
      </td>
    </tr>
    <tr>	
    	<td nowrap class="TableData"><?=_("图片显示行/列：")?></td>
    	<td class="TableData"><?=sprintf(_("%s行，"),"<input type='text' name='ROW_PIC' size='10' class='BigInput' value='".$ROW_PIC."' />")?>
    		<?=sprintf(_("%s列"),"<input type='text' name='ROW_PIC_NUM' size='10' class='BigInput' value='".$ROW_PIC_NUM."' />")?>
        </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="hidden" value="<?=$PIC_ID?>" name="PIC_ID">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='./'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>
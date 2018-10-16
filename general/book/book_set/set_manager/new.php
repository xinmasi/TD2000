<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("设置管理员");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.COPY_TO_ID.value=="")
   { alert("<?=_("管理员不能为空！")?>");
     return (false);
   }

   if(document.form1.TO_ID.value=="")
   { alert("<?=_("所管部门不能为空！")?>");
     return (false);
   }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("设置管理员")?></span>
    </td>
  </tr>
</table>
<?
if($AUTO_ID!="")
{
   $query = "SELECT * from BOOK_MANAGER where AUTO_ID='$AUTO_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
     $MANAGER_ID=$ROW["MANAGER_ID"];
     $MANAGE_DEPT_ID=$ROW["MANAGE_DEPT_ID"]; 
       
     $query1="select USER_ID,USER_NAME from USER";
     $cursor1= exequery(TD::conn(),$query1);
     $USER_NAME_STR="";
     while($ROW=mysql_fetch_array($cursor1))
     {
       $USER_ID=$ROW["USER_ID"];
       $USER_NAME=$ROW["USER_NAME"];
       if(find_id($MANAGER_ID,$USER_ID))
          $USER_NAME_STR.=$USER_NAME.",";
     }
   
   	$query1="select DEPT_ID,DEPT_NAME from DEPARTMENT";
     $cursor1= exequery(TD::conn(),$query1);
     $TO_NAME="";
     while($ROW=mysql_fetch_array($cursor1))
     {
       $DEPT_ID=$ROW["DEPT_ID"];
       $DEPT_NAME=$ROW["DEPT_NAME"];
       if(find_id($MANAGE_DEPT_ID,$DEPT_ID))
          $TO_NAME.=$DEPT_NAME.",";
     }
     if($MANAGE_DEPT_ID=="ALL_DEPT")
        $TO_NAME=_("全体部门");	
   }
}
?>
<table class="TableBlock"  width="500" align="center" >
  <form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
      <td nowrap class="TableData"><?=_("管理员：")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="<?=$MANAGER_ID?>">
        <textarea cols=30 name="COPY_TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$USER_NAME_STR?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('217','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("选择")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
   <tr>
     <td nowrap class="TableData"><?=_("所管部门：")?></td>
     <td class="TableData">
       <input type="hidden" name="TO_ID" value="<?=$MANAGE_DEPT_ID?>">
       <textarea cols=30 name=TO_NAME rows=4 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
       <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
       <br><?=_("注：所管部门指图书所属部门。")?>
     </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
    	  <input type="hidden" name="AUTO_ID" value="<?=$AUTO_ID?>">
        <input type="submit" value="<?=_("确定")?>" class="BigButton" name="button">&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='index.php'">
    </td>
   </tr>
  </form>
</table>

</body>
</html>

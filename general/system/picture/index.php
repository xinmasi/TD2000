<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = _("图片管理设置");
include_once("inc/header.inc.php");
?>


<script>
function delete_disk(PIC_ID)
{
 msg='<?=_("确认要取消该图片目录吗？这不会删除该路径下的文件。")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?PIC_ID="+PIC_ID;
  window.location=URL;
 }
}
function menu_code(TYPE,ID)
{
    //加入宽度和高度
    top=(screen.availHeight-640)/2;
    left=(screen.availWidth-960)/2;
    window.open("/module/menu_code/?IS_MAIN=<?=$IS_MAIN?>&TYPE="+TYPE+"&ID="+ID,"MENU_CODE","height=640,width=960,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=" + top +",left="+ left +",resizable=yes");
}
</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建图片目录")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<input type="button"  value="<?=_("新建图片目录")?>" class="BigButton" onClick="location='new/';" title="<?=_("新建图片目录")?>">
</div>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理图片目录")?></span>
    </td>
  </tr>
</table>

<br>

<?
//============================ 管理目录 =======================================
$query = "SELECT * from PICTURE";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
$POSTFIX = _("，");
$PIC_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $PIC_COUNT++;

   $PIC_ID = $ROW["PIC_ID"];
   $PIC_NAME = $ROW["PIC_NAME"];
   $PIC_PATH = $ROW["PIC_PATH"];
   $TO_DEPT_ID=$ROW["TO_DEPT_ID"];
   $TO_PRIV_ID=$ROW["TO_PRIV_ID"];
   $TO_USER_ID=$ROW["TO_USER_ID"];

   $PIC_NAME=str_replace("<","&lt",$PIC_NAME);
   $PIC_NAME=str_replace(">","&gt",$PIC_NAME);
   $PIC_NAME=stripslashes($PIC_NAME);

   $TO_NAME="";
   if($TO_DEPT_ID=="ALL_DEPT")
      $TO_NAME=_("全体部门");
   else
   {
       $TO_DEPT_ID=td_trim($TO_DEPT_ID);
	   if($TO_DEPT_ID!="")
	   {
	   $query1="select * from DEPARTMENT where DEPT_ID in ($TO_DEPT_ID)";
       $cursor1= exequery(TD::conn(),$query1);
       while($ROW=mysql_fetch_array($cursor1))
          $TO_NAME.=$ROW["DEPT_NAME"].$POSTFIX;
	   }
   }

   $TO_NAME_TITLE="";
   $TO_NAME_STR="";
   if($TO_NAME!="")
   {
      if(substr($TO_NAME,-strlen($POSTFIX))==$POSTFIX)
         $TO_NAME=substr($TO_NAME,0,-strlen($POSTFIX));
      $TO_NAME_TITLE.=_("部门：").$TO_NAME;
      $TO_NAME_STR.="<font color=#0000FF><b>"._("部门：")."</b></font>".csubstr(strip_tags($TO_NAME),0,20).(strlen($TO_NAME)>20?"...":"")."<br>";
   }

   $PRIV_NAME="";
   $TO_PRIV_ID=td_trim($TO_PRIV_ID);
  if($TO_PRIV_ID!="")
  {
   $query1 = "SELECT * from USER_PRIV where USER_PRIV in ($TO_PRIV_ID)";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW=mysql_fetch_array($cursor1))
      $PRIV_NAME.=$ROW["PRIV_NAME"].$POSTFIX;
   }
   if($PRIV_NAME!="")
   {
      $PRIV_NAME=substr($PRIV_NAME,0,-strlen($POSTFIX));
      if($TO_NAME_TITLE!="")
         $TO_NAME_TITLE.="\n\n";
      $TO_NAME_TITLE.=_("角色：").$PRIV_NAME;
      $TO_NAME_STR.="<font color=#0000FF><b>"._("角色：")."</b></font>".csubstr(strip_tags($PRIV_NAME),0,20).(strlen($PRIV_NAME)>20?"...":"")."<br>";
   }

   $USER_NAME="";
   $query1 = "SELECT * from USER where find_in_set(USER_ID,'$TO_USER_ID')";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW=mysql_fetch_array($cursor1))
      $USER_NAME.=$ROW["USER_NAME"].$POSTFIX;
   if($USER_NAME!="")
   {
      $USER_NAME=substr($USER_NAME,0,-strlen($POSTFIX));
      if($TO_NAME_TITLE!="")
         $TO_NAME_TITLE.="\n\n";
      $TO_NAME_TITLE.=_("人员：").$USER_NAME;
      $TO_NAME_STR.="<font color=#0000FF><b>"._("人员：")."</b></font>".csubstr(strip_tags($USER_NAME),0,20).(strlen($USER_NAME)>20?"...":"")."<br>";
   }

   if($PIC_COUNT==1)
   {
?>

   <table class="TableList" width="90%" align="center">
     <tr class="TableHeader">
       <td nowrap align="center"><?=_("目录名称")?></td>
       <td nowrap align="center"><?=_("目录路径")?></td>
       <td nowrap align="center"><?=_("发布范围")?></td>
       <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
   }

   if($PIC_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
   <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$PIC_NAME?></td>
     <td nowrap><?=$PIC_PATH?></td>
     <td style="cursor:hand" title="<?=$TO_NAME_TITLE?>"><?=$TO_NAME_STR?></td>
     <td nowrap align="center">
         <a href="edit.php?PIC_ID=<?=$PIC_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("编辑")?></a>&nbsp;
         <a href="javascript:delete_disk(<?=$PIC_ID?>);"> <?=_("删除")?></a>&nbsp;
         <a href="set_priv/?PIC_ID=<?=$PIC_ID?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("权限设置")?></a>&nbsp;
         <a href="#" onClick="javascript:menu_code('PICTURE','<?=$PIC_ID?>');return false;"><?=_("菜单定义指南")?></a>
     </td>
   </tr>
<?
}

if($PIC_COUNT==0)
{
  Message("",_("尚无目录"));
  exit;
}
else
{
?>
  </table>
<?
}
?>

</body>
</html>

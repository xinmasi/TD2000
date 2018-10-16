<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("分组管理");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script>
function delete_group(GROUP_ID)
{
 msg='<?=_("确认要删除该分组吗？")?>\n<?=_("注意：该分组下的联系人将全部转入到默认分组中！")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?GROUP_ID=" + GROUP_ID;
  window.location=URL;
 }
}

function clear_group(GROUP_ID,GROUP_NAME)
{
  var msg = sprintf("<?=_("确认要清空 '%s' 分组的联系人吗？")?>", GROUP_NAME);
  if(window.confirm(msg))
  {
     URL="clear.php?GROUP_ID=" + GROUP_ID;
     window.location=URL;
  }
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建分组")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<input type="button" value="<?=_("新建分组")?>" class="BigButton" onClick="location='new.php';" title="<?=_("创建新的分组，录入相关信息")?>">
</div>

<br>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理分组")?></span>
    </td>
  </tr>
</table>

<br>

<table class="TableList" width="95%" align="center">
  <tr class="TableData">
    <td nowrap align="center"><?=_("默认")?></td>
    <td nowrap align="center"><?=_("全体部门")?></td>
    <td nowrap align="center"><?=_("全体角色")?></td>
    <td nowrap align="center"><?=_("全体人员")?></td>
    <td>
      <a href="javascript:clear_group('0','<?=_("默认")?>');"> <?=_("清空")?></a>
      <a href="import.php?GROUP_ID=0"> <?=_("导入")?></a>
            <a href="print.php?GROUP_ID=0" target="_blank"> <?=_("打印")?></a><br>
      <a href="export.php?GROUP_ID=0&TYPE=0" target="_blank"> <?=_("导出Foxmail格式")?></a>
      <a href="export.php?GROUP_ID=0&TYPE=1" target="_blank"> <?=_("导出OutLook格式")?></a>
    </td>
  </tr>

<?
 //============================ 管理分组 =======================================
 $query = "select * from ADDRESS_GROUP where USER_ID='' order by ORDER_NO asc,GROUP_NAME asc";
 $cursor= exequery(TD::conn(),$query);

 while($ROW=mysql_fetch_array($cursor))
 {
    $GROUP_ID=$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    $PRIV_DEPT=$ROW["PRIV_DEPT"];
    $PRIV_ROLE=$ROW["PRIV_ROLE"];
    $PRIV_USER=$ROW["PRIV_USER"];

    if($PRIV_DEPT=="ALL_DEPT")
       $PRIV_DEPT_STR=_("全体部门");
    else
    {
       $PRIV_DEPT_STR="";
       $TOK=strtok($PRIV_DEPT,",");
       while($TOK!="")
       {
         if($PRIV_DEPT_STR!="")
            $PRIV_DEPT_STR.=",";
         $query1="select * from DEPARTMENT where DEPT_ID='$TOK'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW=mysql_fetch_array($cursor1))
            $PRIV_DEPT_STR.=$ROW["DEPT_NAME"];
         $TOK=strtok(",");
        }
     }

     $PRIV_ROLE_STR="";
     $TOK=strtok($PRIV_ROLE,",");
     while($TOK!="")
     {
       if($PRIV_ROLE_STR!="")
          $PRIV_ROLE_STR.=",";
       $query1="select * from USER_PRIV where USER_PRIV='$TOK'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
          $PRIV_ROLE_STR.=$ROW["PRIV_NAME"];
       $TOK=strtok(",");
      }

     $PRIV_USER_STR="";
     $TOK=strtok($PRIV_USER,",");
     while($TOK!="")
     {
       if($PRIV_USER_STR!="")
          $PRIV_USER_STR.=",";
       $query1="select * from USER where USER_ID='$TOK'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
          $PRIV_USER_STR.=$ROW["USER_NAME"];
       $TOK=strtok(",");
      }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$GROUP_NAME?></td>
      <td align="center"><?=$PRIV_DEPT_STR?></td>
      <td align="center"><?=$PRIV_ROLE_STR?></td>
      <td align="center"><?=$PRIV_USER_STR?></td>
      <td>
          <a href="edit.php?GROUP_ID=<?=$GROUP_ID?>"> <?=_("编辑")?></a>
          <a href="javascript:delete_group(<?=$GROUP_ID?>);"> <?=_("删除")?></a>
          <a href="javascript:clear_group('<?=$GROUP_ID?>','<?=$GROUP_NAME?>');"> <?=_("清空")?></a>
          <a href="import.php?GROUP_ID=<?=$GROUP_ID?>"> <?=_("导入")?></a>     
          <a href="javascript:;" onClick="window.open('support.php?GROUP_ID=<?=$GROUP_ID?>&GROUP_NAME=<?=urlencode($GROUP_NAME)?>','','height=600,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=350,top=300,resizable=yes');"><?=_("维护权限")?></a>
          <br>
          <a href="print.php?GROUP_ID=<?=$GROUP_ID?>" target="_blank"> <?=_("打印")?></a>
          <a href="export.php?GROUP_ID=<?=$GROUP_ID?>&TYPE=0" target="_blank"> <?=_("导出Foxmail格式")?></a>
          <a href="export.php?GROUP_ID=<?=$GROUP_ID?>&TYPE=1" target="_blank"> <?=_("导出OutLook格式")?></a>

      </td>
    </tr>
<?
 }
?>
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("分组名称")?></td>
      <td nowrap align="center"><?=_("开放部门")?></td>
      <td nowrap align="center"><?=_("开放角色")?></td>
      <td nowrap align="center"><?=_("开放人员")?></td>
      <td nowrap align="center" width="300"><?=_("操作")?></td>
   </thead>
   </table>

</body>
</html>

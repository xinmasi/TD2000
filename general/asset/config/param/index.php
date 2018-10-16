<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("参数设置");
include_once("inc/header.inc.php");
?>


<script>
function CheckForm()
{
  msg='<?=_("添加后只有OA管理员可以修改，确定添加吗？")?>';
  if(window.confirm(msg))
  {
    form1.submit();
  }
}

function cleardata()
{
  msg='<?=_("确认要初始化固定资产参数配置吗？")?>';
  if(window.confirm(msg))
     location="clear.php";
}

</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" WIDTH="24" HEIGHT="24"><span class="big3"> <?=_("参数配置")?></span><br>
    </td>
  </tr>
</table>

<?
function check_cfg()
{
   $query="select * from CP_ASSETCFG";
   $cursor = exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
   	   $DPCT_SORT=$ROW["DPCT_SORT"];
   	   $BAL_SORT=$ROW["BAL_SORT"];
   }
   if($DPCT_SORT!="M"&&$DPCT_SORT!="S"&&$DPCT_SORT!="Y" || $BAL_SORT!="01"&&$BAL_SORT!="02")
      return false;
   else
      return true;
}

if(check_cfg())
{
   $query="select * from CP_ASSETCFG";
   $cursor = exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
   	   $DPCT_SORT=$ROW["DPCT_SORT"];
   	   $BAL_SORT=$ROW["BAL_SORT"];
   }
   
   if($DPCT_SORT=="M")
      $DPCT_SORT_DESC=_("月");
   else if($DPCT_SORT=="S")
      $DPCT_SORT_DESC=_("季");
   else if($DPCT_SORT=="Y")
      $DPCT_SORT_DESC=_("年");
      
   if($BAL_SORT=="01")
      $BAL_SORT_DESC=_("残值");
   else if($BAL_SORT=="02")
      $BAL_SORT_DESC=_("残值率");
?>
<table class="TableList" width="460" align="center">
  <tr class="TableHeader">
      <td nowrap width="150" align="center"><?=_("计提折旧方式")?></td>
      <td nowrap width="150" align="center"><?=_("残值处理方式")?></td>
  </tr>
  <tr class="TableLine1">
      <td nowrap width="150" align="center"><?=$DPCT_SORT_DESC?></td>
      <td nowrap width="150" align="center"><?=$BAL_SORT_DESC?></td>
  </tr>
</table>
<?
}
else
{
?>
<table class="TableBlock"  width="460" align="center">
 <form action="add.php" method="post" name="form1">
  <tr>
      <td nowrap align="center" width="80" class="TableContent"><?=_("计提折旧方式")?></td>
      <td nowrap align="left" class="TableData">
      <select name="DPCT_SORT" class="BigSelect">
        <option value="M" selected><?=_("月")?></option>
        <option value="S"><?=_("季")?></option>
        <option value="Y"><?=_("年")?></option>
      </select>
      </td>
  </tr>
  <tr>
      <td nowrap align="center" width="80" class="TableContent"><?=_("残值处理方式")?></td>
      <td nowrap align="left" class="TableData">
      <select name="BAL_SORT" class="BigSelect">
        <option value="01" selected><?=_("残值")?></option>
        <option value="02"><?=_("残值率")?></option>
      </select>
      </td>
  </tr>
  <tr class="TableControl">
      <td nowrap colspan="2" align="center" >
      <input type="button" value="<?=_("添加")?>" class="BigButton" onclick="CheckForm();">
      </td>
  </tr>
 </form>
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
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" WIDTH="24" HEIGHT="24"><span class="big3"> <?=_("增减处理类型")?></span>
    </td>
  </tr>
</table>

<table class="TableList" width="460" align="center">
  <tr class="TableHeader">
      <td nowrap width="80" align="center"><?=_("类别")?></td>
      <td nowrap width="80" align="center"><?=_("名称")?></td>
      <td width="300" align="center"><?=_("描述")?></td>
  </tr>
<?
   $query="select * from CP_PRCS_PROP order by PRCS_CLASS";
   $cursor = exequery(TD::conn(),$query);
   $ROW_COUNT=0;
   while($ROW=mysql_fetch_array($cursor))
   {
   	   $ROW_COUNT++;
   	   $PRCS_CLASS=$ROW["PRCS_CLASS"];
   	   $PRCS_DESC=$ROW["PRCS_DESC"];
   	   $PRCS_LONG_DESC=$ROW["PRCS_LONG_DESC"];
   
       if(substr($PRCS_CLASS,0,1)=="A")
          $PRCS_CLASS_DESC=_("增加");
       else if(substr($PRCS_CLASS,0,1)=="D")
          $PRCS_CLASS_DESC=_("减少");
          
       if($ROW_COUNT%2)
          $TableLine="TableLine1";
       else
          $TableLine="TableLine2";
?>
  <tr class="<?=$TableLine?>">
      <td nowrap width="80" align="center"><?=$PRCS_CLASS_DESC?></td>
      <td nowrap width="80" align="center"><?=$PRCS_DESC?></td>
      <td width="300" align="left"><?=$PRCS_LONG_DESC?></td>
  </tr>
<?
   }
?>
</table>
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" WIDTH="24" HEIGHT="24"><span class="big3"><?=_("参数配置初始化")?></span>
    </td>
  </tr>
</table>
<div align="center">
  <input type="button" value="<?=_("初始化参数配置")?>" class="BigButton" onClick="cleardata();" title="<?=_("初始化固定资产参数配置")?>">
</div>
<?
}
?>
</body>
</html>

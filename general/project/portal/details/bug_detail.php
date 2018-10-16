<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/header.inc.php");

function level_desc($level)
{
   switch($level)
   {
      case "1": return _("非常高");
      case "2": return _("高");
      case "3": return _("普通");
      case "4": return _("低");
   }
}

if($BUG_ID)
{
	$query = "select * from PROJ_BUG WHERE BUG_ID='$BUG_ID'";
	$cursor = exequery(TD::conn(), $query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$BUG_NAME = $ROW["BUG_NAME"];
		$BUG_DESC = $ROW["BUG_DESC"];
		$LEVEL = $ROW["LEVEL"];
		$BEGIN_USER = $ROW["BEGIN_USER"];
		$DEAD_LINE = $ROW["DEAD_LINE"];
		$STATUS = $ROW["STATUS"];
		$ATTACHMENT_ID  = $ROW["ATTACHMENT_ID"];
		$ATTACHMENT_NAME  = $ROW["ATTACHMENT_NAME"];
		$RESULT = $ROW["RESULT"];
		$RESULT_ARR=explode("|*|",$RESULT);
	}

  if($STATUS==1)
  {
  	$query = "update PROJ_BUG SET STATUS=2 WHERE BUG_ID='$BUG_ID'";
  	exequery(TD::conn(),$query);
  }
	if($BEGIN_USER)
	{
    $query = "select USER_NAME from USER WHERE USER_ID='$BEGIN_USER'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
       $BEGIN_USER_NAME = $ROW["USER_NAME"];
  }
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<script>
function window_close()
{
window.close();
}
</script>
<bord>
<br>
<br>
<table width="80%" border="1" bordercolor="#CCCCCC" align="center" cellpadding="0" cellspacing="0" class="TableList">
   <tr>
  		<td width="39%" nowrap class="TableContent"><?=_("问题名称：")?></td>
  	  <td width="61%" class="TableData"><?=$BUG_NAME?></td>  	  	
  </tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("优先级：")?></td>
      <td class="TableData"><span class="CalLevel<?=$LEVEL?>" title="<?=level_desc($LEVEL)?>"></span></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("问题描述：")?></td>
  	  <td class="TableData"><?=$BUG_DESC?></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("提交人：")?></td>
  	  <td class="TableData"><?=$BEGIN_USER_NAME?></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("最后处理期限：")?></td>
  	  <td class="TableData"> <?=$DEAD_LINE?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent"><?=_("附件文档：")?></td>
      <td nowrap class="TableData"><?if($ATTACHMENT_ID) echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);else echo _("无附件");?></td>
    </tr>
    <tr>
      <td nowrap class="TableContent"><?=_("处理记录：")?></td>
      <td class="TableData">
      <?
      foreach($RESULT_ARR as $content)
      {
      	 echo $content."<br>";
      } 
      ?>      
      </td>
    </tr>
</table>
<br>
<div align="center">
<button class="btn" onClick="javascript:window_close()" ><?=_("关闭")?></button>
</div>
<?
}
?>
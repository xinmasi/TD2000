<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

ob_end_clean();
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
		$DEAL_USER = $ROW["DEAL_USER"];
		$DEAD_LINE = $ROW["DEAD_LINE"];
		$ATTACHMENT_ID  = $ROW["ATTACHMENT_ID"];
		$ATTACHMENT_NAME  = $ROW["ATTACHMENT_NAME"];
	  $RESULT = $ROW["RESULT"];
	  $RESULT_ARR=explode("|*|",$RESULT);
	}

	if($DEAL_USER)
	{
    $query = "select USER_NAME from USER WHERE USER_ID='$DEAL_USER'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
       $DEAL_USER_NAME = $ROW["USER_NAME"];
  }
?>
<table class="TableList" border="0" width="80%" align="center" width=500px>
   <tr>
  		<td nowrap class="TableContent"><?=_("问题名称：")?></td>
  	  <td class="TableData"><?=$BUG_NAME?></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("优先级：")?></td>
      <td class="TableData"><span class="CalLevel<?=$LEVEL?>"><?=level_desc($LEVEL)?></span></td>
  	  </td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("问题描述：")?></td>
  	  <td class="TableData"><?=$BUG_DESC?></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("处理人：")?></td>
  	  <td class="TableData"><?=$DEAL_USER_NAME?></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("最后处理期限：")?></td>
  	  <td class="TableData"> <?=$DEAD_LINE?></td>
    </tr>
    <tr>
      <td nowrap class="TableContent"><?=_("附件文档：")?></td>
      <td nowrap class="TableData"><?if($ATTACHMENT_ID) echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,0,0,0,0,0,0);else echo _("无附件");?></td>
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
<?
}
?>
<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

ob_end_clean();
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
		$RESULT_ARR=explode("|*|",$ROW['RESULT']);
	}

	switch ($LEVEL)
	{
		case 0:
		  $LEVEL_DESC=_("��");
		  break;
		case 1:
		  $LEVEL_DESC=_("��ͨ");
		  break;
		case 2:
		  $LEVEL_DESC=_("��");
		  break;
		default:
		  break;	
	}

	if($DEAL_USER)
	{
    $query = "select USER_NAME from USER WHERE USER_ID='$DEAL_USER'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
       $DEAL_USER_NAME = $ROW["USER_NAME"];
  }
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<table class="TableList" border="0" width="80%" align="center" width=500px>
   <tr>
  		<td nowrap class="TableContent"><?=_("�������ƣ�")?></td>
  	  <td class="TableData"><?=$BUG_NAME?></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("���ȼ���")?></td>
  	  <td class="TableData"><?=$LEVEL_DESC?>
  	  </td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("����������")?></td>
  	  <td class="TableData"><?=$BUG_DESC?></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("�����ˣ�")?></td>
  	  <td class="TableData"><?=$DEAL_USER_NAME?></td>  	  	
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("��������ޣ�")?></td>
  	  <td class="TableData"> <?=$DEAD_LINE?>
      </td>
    </tr>
    <tr>
      <td class="TableContent" nowrap><?=_("�����ĵ���")?></td>
      <td nowrap class="TableData"><?if($ATTACHMENT_ID) echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);else echo _("�޸���");?></td>
    </tr>
    <tr>
      <td nowrap class="TableContent"><?=_("������¼��")?></td>
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
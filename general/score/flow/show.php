<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("新建考核任务");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
</script>


<body class="bodycolor">
<?
$FLOW_ID=intval($FLOW_ID);
if($FLOW_ID!="")
{
 $query = "SELECT * from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $GROUP_ID=$ROW["GROUP_ID"];
    $FLOW_TITLE=$ROW["FLOW_TITLE"];
    $FLOW_DESC=$ROW["FLOW_DESC"];
    $FLOW_FLAG=$ROW["FLOW_FLAG"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $RANKMAN=$ROW["RANKMAN"];
    $PARTICIPANT=$ROW["PARTICIPANT"];
    $ANONYMITY=$ROW["ANONYMITY"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";
    if($END_DATE=="0000-00-00")
       $END_DATE="";

      $RAN_NAME="";
      $TOK=strtok($RANKMAN,",");
      while($TOK!="")
      {
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
           $RAN_NAME.=$ROW["USER_NAME"].",";

        $TOK=strtok(",");
      }

      $PARTI_NAME="";
      $TOK=strtok($PARTICIPANT,",");
      while($TOK!="")
      {
        $query1="select * from USER where USER_ID='$TOK'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
           $PARTI_NAME.=$ROW["USER_NAME"].",";

        $TOK=strtok(",");
      }
   }
 }

?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?if($FLOW_ID=="")echo _("新建");else echo _("编辑");?><?=_("考核任务")?></span>
    </td>
  </tr>
</table>

<table width="70%" align="center" class="TableBlock">
  	<tr>
      <td nowrap class="TableData"><?=_("考核任务标题：")?></td>
      <td class="TableData"><?=$FLOW_TITLE?></td>
    </tr>

     <tr>
      <td nowrap class="TableData"><?=_("考核人：")?></td>
      <td class="TableData"><?=$RAN_NAME?></td>
    </tr>

    <tr>
      <td nowrap class="TableData"><?=_("被考核人：")?></td>
      <td class="TableData"><?=$PARTI_NAME?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("考核指标集：")?></td>
<?
				$query = "SELECT * from SCORE_GROUP where GROUP_ID='$GROUP_ID'";
				$GROUPNAME="";
        $cursor= exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
        		$GROUPNAME=$ROW["GROUP_NAME"].",";
        }
?>
      <td class="TableData"><?=$GROUPNAME?></td>
    </tr>
    <tr>

      <td nowrap class="TableData"> <?=_("有效期：")?></td>
      <td class="TableData"><?=$BEGIN_DATE?><?=_("～")?><?=$END_DATE?></td>
    </tr>
     <tr>
      <td nowrap class="TableData"><?=_("描述：")?></td>
      <td class="TableData"><?=$FLOW_DESC?></td>
    </tr>
    <tfoot align="center" class="TableFooter">
      <td colspan="2" nowrap>
        <input type="hidden" name="FLOW_ID" value="<?=$FLOW_ID?>">
<?
				if($FLOW_ID!="")
				{
?>
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?CUR_PAGE=$CUR_PAGE'">
<?
				}
?>
      </td>
    </tfoot>
  </table>

</body>
</html>
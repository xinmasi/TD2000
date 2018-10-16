<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("��Ŀ����");
include_once("inc/header.inc.php");

include_once("../proj_priv.php");
include_once("inc/utility_all.php");

function level_desc($level)
{
   switch($level)
   {
      case "1": return _("�ǳ���");
      case "2": return _("��");
      case "3": return _("��ͨ");
      case "4": return _("��");
   }
}

?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?= MYOA_STATIC_SERVER ?>/static/theme/<?= $_SESSION["LOGIN_THEME"] ?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript">
jQuery.noConflict();
function showDetail(bug_id)
{
	jQuery.get("detail.php?BUG_ID="+bug_id,function(data){jQuery("#detail_body").html(data);ShowDialog('detail');});
}
</script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />

<body>

<table class="table table-bordered table-striped" >

<?
$query = "select * FROM PROJ_BUG where PROJ_ID='$PROJ_ID' ORDER BY CREAT_TIME,LEVEL DESC";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_Fetch_array($cursor))
{
	$COUNT++;
	
	$RESULT = csubstr($ROW['RESULT'], 0, 20);
	
	$query1 = "SELECT USER_NAME FROM USER WHERE USER_ID='$ROW[BEGIN_USER]'";
	$cursor1 = exequery(TD::conn(),$query1);
	if($ROW1=mysql_fetch_array($cursor1))
	   $BEGIN_USER = $ROW1["USER_NAME"];
	   
	$query1 = "SELECT USER_NAME FROM USER WHERE USER_ID='$ROW[DEAL_USER]'";
	$cursor1 = exequery(TD::conn(),$query1);
	if($ROW1=mysql_fetch_array($cursor1))
	   $DEAL_USER = $ROW1["USER_NAME"];

	if($COUNT==1)
    {
	  echo '
	 <tr class="info">
		<td colspan="7"><strong>'._("��Ŀ�����¼").'</strong></td>
	</tr>
	<tr class="info">
        <td >'._("��������").'</td>
        <td >'._("�ύ��").'</td>
        <td >'._("������").'</td>
        <td >'._("������").'</td>
        <td >'._("�������").'</td>
        <td >'._("���ȼ�").'</td>
        <td >'._("״̬").'</td>
    </tr>	  
	  ';
    }
	
	switch ($ROW["STATUS"])
	{
		case 0:
		  $STATUS_DESC=_("δ�ύ");
		  break;
		case 1:
		  $STATUS_DESC=_("δ����");
		  break;
		case 2:
		  $STATUS_DESC=_("������");
		  break;
		case 3:
		  $STATUS_DESC=_("�ѷ���");
		  break;
		default:
		  break;		  
	}
	
	echo '<tr class="'.$TableLine.'">
	  <td ><a href="#" onclick=showDetail("'.$ROW["BUG_ID"].'")>'.$ROW["BUG_NAME"].'</a></td>
	  <td >'.$BEGIN_USER.'</td>
	  <td >'.$DEAL_USER.'</td>
	  <td  title="'.strip_tags($ROW['RESULT']).'">'.$RESULT.'</td>
	  <td >'.$ROW["DEAD_LINE"].'</td>
	  <td ><span class="CalLevel'.$ROW["LEVEL"].'" title="'.level_desc($ROW["LEVEL"]).'">'.level_desc($ROW["LEVEL"]).'</span></td>
	  <td >'.$STATUS_DESC.'</td></tr>';
}
if($COUNT>0)
   echo '</table>';
else
   Message("",_("������Ŀ���⣡"))
?>	
	
</table>

<div id="overlay"></div>
<div id="detail" class="ModalDialog" style="width:550px;">
  <div class="header"><span id="title" class="title"><?=_("��Ŀ��������")?></span><a class="operation" href="javascript:HideDialog('detail');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
  <div id="detail_body" class="body">
  </div>
  <div id="footer" class="footer">
    <input class="btn" onclick="HideDialog('detail')" type="button" value="<?=_("�ر�")?>"/>
  </div>
</div>
</body>
</html>
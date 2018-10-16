<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目审批");
include_once("inc/header.inc.php");
?>


<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>

<script type="text/javascript">
    var showProj1 = false;
    function showProj(PROJ_ID)
    {
        if(showProj1)
            showProj1.close();
        myleft = (screen.availWidth - 1000) / 2;
        showProj1 = window.open("../portal/details/?PROJ_ID=" + PROJ_ID, "", "status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=1000,height=600,left=" + myleft + ",top=50");
    }
</script>


<body class="bodycolor" style="padding:10px;" >

<table class="table table-bordered table-hover" width="100%" align="center" >

  <?php
  $query = "select a.*,b.USER_NAME FROM PROJ_PROJECT AS a LEFT JOIN USER AS b ON (a.PROJ_OWNER=b.USER_ID) where PROJ_STATUS in(2,3,4) AND PROJ_MANAGER='" . $_SESSION["LOGIN_USER_ID"] . "' ORDER BY PROJ_UPDATE_TIME DESC";
  $cursor = exequery(TD::conn(), $query);
  $count = 0;
  while ($ROW = mysql_Fetch_array($cursor)) 
  {
	$count++;
	if($count == 1){
		echo '
			  <tr class="info" style="color:#2a70e9; text-align:center;">
				<td style="text-align:center;"><span><strong>项目编号</strong></span></td>
				<td style="text-align:center;"><span><strong>项目名称</strong></span></td>
				<td style="text-align:center;"><span><strong>申请人</strong></span></td>
				<td style="text-align:center;"><span><strong>开始日期</strong></span></td>
				<td style="text-align:center;"><span><strong>工期</strong></span></td>
				<td style="text-align:center;"><span><strong>结束日期</strong></span></td>
				<td style="text-align:center;"><span><strong>实际结束日期</strong></span></td>
			  </tr>		
		'; 	
	}
	$DIFF_DAY = floor((strtotime($ROW["PROJ_END_TIME"]) - strtotime($ROW["PROJ_START_TIME"])) / (3600 * 24) ) + 1;
	$END_TIME = $ROW["PROJ_ACT_END_TIME"];
    if ($END_TIME == "" || $END_TIME == "0000-00-00")
    {
        $END_TIME = "<font color=green>" . _("尚未结束") . "</font>";
    }	
	?>
		<tr>
			<td style="text-align:center;"><?= $ROW["PROJ_NUM"]?></td>
			<td style="text-align:center;"><a href="#" onclick="showProj('<?= $ROW["PROJ_ID"]?>')"><?= $ROW["PROJ_NAME"]?></a></td>
			<td style="text-align:center;"><?= $ROW["USER_NAME"]?></td>
			<td style="text-align:center;"><?= $ROW["PROJ_START_TIME"]?></td>
			<td style="text-align:center;"><?= $DIFF_DAY._('天');?></td>
			<td style="text-align:center;"><?= $ROW["PROJ_END_TIME"]?></td>
			<td style="text-align:center;"><?= $END_TIME;?></td>
		</tr>
	<?		
  }
  if($count == 0){
	 Message(_("提示"), _("没有审批记录！"));
  }
  ?>
</table>


</body>
</html>
<?
include_once("inc/auth.inc.php");
include("inc/FusionCharts/FusionCharts.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("学历统计");
include_once("inc/header.inc.php");
?>
<script language="JavaScript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>

<body class="bodycolor">
<table width="98%" border="0" cellspacing="0" cellpadding="3" align="center">
  <tr> 
    <td valign="top" class="text" align="center"> 
    	<div id="chartdiv" align="center"><?=_("学历统计")?> </div>
    	<script type="text/javascript">
		   var chart = new FusionCharts("<?=MYOA_JS_SERVER?>/inc/FusionCharts/Line.swf", "ChartId", "800", "450", "0", "0");
		   chart.setDataURL("./line_data.php?SUMFIELD=<?=$SUMFIELD?>&TO_ID=<?=$TO_ID?>");
			 chart.render("chartdiv");
		  </script> 
		</td>
  </tr>
</table>
</body>
</html>


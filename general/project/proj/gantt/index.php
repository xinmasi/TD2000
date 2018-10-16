<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目甘特图");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="/general/workflow/assets/tabs.css">
<script language="javascript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/Fusioncharts.js"></script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr><td>
	<img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle" />
	<span class="big3"><?=_("项目甘特图")?></span>
	</td>
	<td align="right"><input type="button" class="SmallButton" onClick="window.location='../task/?PROJ_ID=<?=$PROJ_ID?>'" value="<?=_("列表视图")?>"></td>
</tr>
</table>
<?
$query = "SELECT count(*) as count from PROJ_TASK where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $COUNT = $ROW['count'];
    if($COUNT == 0)
    {
    	 Message("",_("尚未建立项目任务！"));
    	 exit;
	}
	$HEIGHT = 35 * $COUNT + 150;
}
?>
<!--
<img src="gantt.php?PROJ_ID=<?=$PROJ_ID?>">
-->

<div class="module listColor" id="content">
        <div class="head">
		  <h4 class="moduleHeader" id="moduleHeader">
            <div id="navMenu">
            	<a id="title1" href="javascript:;" class="active" target="" onClick="ShowLayers(1,2);"><span> flash</span></a>
                <!--<a id="title2" href="javascript:;" target="" onClick="ShowLayers(2,2);"><span> <?=_("图片")?></span></a>-->
			</div>
          </h4>
        </div>
        <div class="module_body" id="module_body">
          <div id="content1" class="module_div">
            <div id="ganttDiv"><?=_("甘特图")?></div>
            <script type="text/javascript">
                var swf_file = '<?=MYOA_JS_SERVER?>/inc/FusionCharts/Gantt.swf';
            	var myChart = new FusionCharts('<?=MYOA_JS_SERVER?>/inc/FusionCharts/Gantt.swf', "ganttDiv", "1040", '<?=$HEIGHT?>', "0", "0");
            	myChart.setDataURL("data.php?PROJ_ID=<?=$PROJ_ID?>");
            	myChart.render("ganttDiv");
            </script>
          </div>
          <div id="content2" class="module_div" style="display:none;">
          	<img src="gantt.php?PROJ_ID=<?=$PROJ_ID?>">
          </div>
        </div>
      </div>
<script>
function ShowLayers(n,m)
{
    for(i=1;i<=m;i++)
    {
        eval("content" + i).style.display="none";
        eval("title"+i+".className='';");
    }
    eval("title"+n+".className='active';");
    eval("content" + n).style.display="";
    //动态设定标题栏的宽度
    document.getElementById("moduleHeader").style.width = document.getElementById("content" + n).scrollWidth + 8;
}
</script>
</body>
</html>
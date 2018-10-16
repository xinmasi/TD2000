<html>
<head>
<script>
$(function () 
{
	Highcharts.setOptions({
		lang: {
			printChart: "<?=_('打印图表')?>",
			downloadJPEG: "<?=_('下载JGEG')?>",
			downloadPDF: "<?=_('下载PDF')?>",
			downloadPNG: "<?=_('下载PNG')?>",
			downloadSVG: "<?=_('下载SVG')?>"
		}
        });
    $('#histogram').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: "<?=_('项目预算资金')?>"
        },
        subtitle: {
            text: "<?=_('柱状图')?>"
        },
        xAxis: {
            categories: <?php echo array_to_json($a_type_name_array);?>
        },
        yAxis: {
            min: 0,
            title: {
                text: "<?=_('预算资金（元）')?>"
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b><?=_("￥")?>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.05,
                borderWidth: 0
            }
        },
        series: [{
            name: "<?=_('预算')?>",
            data: <?php echo array_to_json($a_budget_amount_array);?>
        }, {
            name: "<?=_('实际（已审核）')?>",
            data: <?php echo array_to_json($a_real_amount_array);?>
        }]
    });
});
</script>
</head>

<body>

<div style="overflow:scroll;width:97%;">
    <div id="histogram" style="width: <?=$i_width?>px; height: 400px; margin: 0 auto"></div>
</div>

</body>
</html>

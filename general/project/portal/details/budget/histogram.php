<html>
<head>
<script>
$(function () 
{
	Highcharts.setOptions({
		lang: {
			printChart: "<?=_('��ӡͼ��')?>",
			downloadJPEG: "<?=_('����JGEG')?>",
			downloadPDF: "<?=_('����PDF')?>",
			downloadPNG: "<?=_('����PNG')?>",
			downloadSVG: "<?=_('����SVG')?>"
		}
        });
    $('#histogram').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: "<?=_('��ĿԤ���ʽ�')?>"
        },
        subtitle: {
            text: "<?=_('��״ͼ')?>"
        },
        xAxis: {
            categories: <?php echo array_to_json($a_type_name_array);?>
        },
        yAxis: {
            min: 0,
            title: {
                text: "<?=_('Ԥ���ʽ�Ԫ��')?>"
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b><?=_("��")?>{point.y:.1f}</b></td></tr>',
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
            name: "<?=_('Ԥ��')?>",
            data: <?php echo array_to_json($a_budget_amount_array);?>
        }, {
            name: "<?=_('ʵ�ʣ�����ˣ�')?>",
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

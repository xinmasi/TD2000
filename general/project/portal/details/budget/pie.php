<html>
<head>
<script>
$(function () 
{
// Radialize��ɫ
    Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
        return {
            radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(0).get('rgb')] // �䰵
            ]
        };
    });

// ����ͼ��
    $('#container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
        text: "<?=_('��ĿԤ���ʽ�')?>"
        },
        subtitle: {
        text: "<?=_('��״ͼ')?>"
        },
        tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    formatter: function() {
                        return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: "<?=_('Ԥ���ʽ���ռ����')?>",
            data:<?php echo array_to_json($a_arr);?>
        }]
    });
    
// ����ͼ��2.0
    $('#container_real').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
        text: "<?=_('��Ŀʵ���ʽ�')?>"
        },
        subtitle: {
        text: "<?=_('��״ͼ')?>"
        },
        tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    formatter: function() {
                        return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: "<?=_('ʵ���ʽ���ռ����')?>",
            data:<?php echo array_to_json($a_arr_real);?>
        }]
    });    
    
    
    
});
</script>

</head>

<body>
<style>
.highcharts-container{margin:0 auto;}
</style>
<div style="overflow:hidden;width:50%;float:left">
    <div id="container" style="min-width:400px;margin:0 "></div>
</div>
<div style="overflow:hidden;width:50%;float:right">
    <div id="container_real" style="min-width:400px;margin:0"></div>
</div>

</body>
</html>

<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("员工考勤动态一览表");
include_once("inc/header.inc.php");
?>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script language="javascript">
var s_timer = "";
$(function ()
{
    $.ajax({
        url: "./get_status.php",
        data: {},
        type: "post",
        dataType: "json",
        success: function (json)
        {
            var i_sz = 0, s_class = "";
            $.each(json, function (idx, obj)
            {
               var s_html = "";
                if(i_sz++ % 2 == 1)
                {
                    s_class = "TableLine1";
                }else
                {
                    s_class = "TableLine2";
                }
                s_html += "<tr class=\"TableData\" id='" + idx + "'>";
                s_html += "<td class='black' width=150>" + obj.dept + "</td>";
                s_html += "<td class='black'width=80>" + obj.name + "</td>";
                if(obj.status == "0")
                {
                    s_html += "<td class='green' width=40>当班</td>";
                }else if(obj.status == "1")
                {
                    s_html += "<td class='blue'width=40>外出</td>";
                }else if(obj.status == "2")
                {
                    s_html += "<td class='blue'width=40>出差</td>";
                }else if(obj.status == "3")
                {
                    s_html += "<td class='red'width=40>加班</td>";
                }else if(obj.status == "4")
                {
                    s_html += "<td class='gray'width=40>请假</td>";
                }else if(obj.status == "5")
                {
                    s_html += "<td class='gray'width=40>休息</td>";
                }
                s_html += "<td class='gray'width=200>" + obj.desc + "</td>";
                if(obj.priv=="1")
                {
                    s_html += '<td><span><a hidefocus="hidefocus" href="/general/status_bar/sms_back.php?TO_UID=' + obj.uid
                    + '&TO_NAME=' + obj.name + '" target="_blank"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/sms.gif" /><?=_("发微讯")?></a></span></td>';
                }else
                {
                    s_html += '<td></td>'
                }
                s_html += "</tr>";
                $("#status").append(s_html);
            });

            s_timer = window.setTimeout("update_status()", 60 * 1000);
        },
        error: function (request, strError)
        {
            alert(strError);
        },
        async: true
    });
});

function update_status()
{
    $.ajax({
        url: "./get_status.php",
        data: {},
        type: "post",
        dataType: "json",
        success: function (json)
        {
            var i_sz = 0, s_class = "";
            $.each(json, function (idx, obj)
            {
                if(obj.changed == "1")
                {
                    var s_html = "<td class='black' width=150>" + obj.dept + "</td>";
                    s_html += "<td class='black'width=80>" + obj.name + "</td>";
                    if(obj.status == "0")
                    {
                        s_html += "<td class='green'width=40>当班</td>";
                    }else if(obj.status == "1")
                    {
                        s_html += "<td class='blue'width=40>外出</td>";
                    }else if(obj.status == "2")
                    {
                        s_html += "<td class='blue'width=40>出差</td>";
                    }else if(obj.status == "3")
                    {
                        s_html += "<td class='red'width=40>加班</td>";
                    }else if(obj.status == "4")
                    {
                        s_html += "<td class='gray'width=40>请假</td>";
                    }
                    s_html += "<td class='gray'width=200>" + obj.desc + "</td>";
                    s_html += '<td><span><a hidefocus="hidefocus" href="/general/status_bar/sms_back.php?TO_UID=' + idx
                        + '&TO_NAME=' + obj.name + '" target="_blank"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/sms.gif" /><?=_("发微讯")?></a></span></td>';
                    $("#" + idx).html(s_html);
                }
            });
            s_timer = window.setTimeout("update_status()", 60 * 1000);
        },
        error: function (request, strError)
        {
            //alert(strError);
        },
        async: true
    });
}
</script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/reportshop/workshop/report/report.css"/>
</head>
<body class="bodycolor" onUnload="javascript:clearTimeout(s_timer)"  scroll="auto" style="margin: 0 auto;">
<center>
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big" align="left"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("员工考勤动态一览表")?></span>&nbsp;
    </td>  
    <td align="right" valign="bottom" class="small1"><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="window.history.go(-1)"></td>
    </tr>
</table>

<table class="TableList" width="80%" id="status">
  <tr class="TableHeader TableContent">
      <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("详情")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
</table>    
</center>

</body>
</html>
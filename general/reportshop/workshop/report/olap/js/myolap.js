$(document).ready(function ()
{
    collapse();
return;
    $('.repd_vcell').mousedown(function ()
    {
        var clone = $("<div>" + $(this).html() + " (" + $(this).attr('repd_fname') + ") " + "</div>");
        clone.addClass('repd_vcell');
        clone.css('position', 'absolute');
        clone.attr('repd_move', 'true');
        clone.attr('repd_fname', $(this).attr('repd_fname'));
        $(this).append(clone);
        event.preventDefault();
    });

    $('.repd_hcell').mousedown(function ()
    {
        var clone = $("<div>" + $(this).html() + " (" + $(this).attr('repd_fname') + ") " + "</div>");
        clone.addClass('repd_hcell');
        clone.css('position', 'absolute');
        clone.attr('repd_move', 'true');
        clone.attr('repd_fname', $(this).attr('repd_fname'));
        $(this).append(clone);
        event.preventDefault();

    });

    $('.repd_vcell').mousemove(function (event)
    {
        var mv = $('div[repd_move="true"]');
        if (mv.length)
        {
            $(this).css('border-style', 'dashed');
            $(this).css('border-bottom', 'red thick dashed');
            $(this).fadeTo(0, 0.5);
            mv.attr('repd_exfield', $(this).attr('repd_fname'));
        }
    });

    $('.repd_hcell').mousemove(function (event)
    {
        var mv = $('div[repd_move="true"]');
        if (mv.length)
        {
            $(this).css('border-style', 'dashed');
            $(this).css('border-right', 'red thick dashed');
            $(this).fadeTo(0, 0.5);
            mv.attr('repd_exfield', $(this).attr('repd_fname'));
        }
    });

    $('.repd_vcell').mouseout(function (event)
    {
        $(this).css('border-style', 'solid');
        $(this).css('border-bottom', 'none');
        $(this).fadeTo(0, 1);
        $('div[repd_move="true"]').attr('repd_exfield', '');
    });

    $('.repd_hcell').mouseout(function (event)
    {
        $(this).css('border-style', 'solid');
        $(this).fadeTo(0, 1);
        $('div[repd_move="true"]').attr('repd_exfield', '');
        $(this).css('border-right', 'none');
    });

    $('.repd_div').mousemove(function (event)
    {
        var elem = $('div[repd_move="true"]');
        if (elem.length)
        {
            elem.css({top: event.pageY + 20, left: event.pageX + 20});
            event.preventDefault();
        }
    });
    $('.repd_div').mouseup(function (event)
    {
        var elem = $('div[repd_move="true"]');
        if (elem.length)
        {
            elem.attr('repd_move', 'false');
            event.preventDefault();

            $('#repd_action').val('settings');
            $('#repd_field_from').val('');
            $('#repd_field_to').val(elem.attr('repd_fname'));
            $('#repd_form').submit();

        }
    });

    $('.repd_vcell').mouseup(function ()
    {

        var elem = $('div[repd_move="true"]');
        event.preventDefault();

        $('#repd_action').val('settings');
        $('#repd_field_from').val(elem.attr('repd_exfield'));
        $('#repd_field_to').val(elem.attr('repd_fname'));
        elem.attr('repd_move', 'false');
        $('#repd_form').submit();
    });

    $('.repd_hcell').mouseup(function ()
    {
        var elem = $('div[repd_move="true"]');

        event.preventDefault();

        $('#repd_action').val('settings');
        $('#repd_field_from').val(elem.attr('repd_exfield'));
        $('#repd_field_to').val(elem.attr('repd_fname'));
        elem.attr('repd_move', 'false');
        $('#repd_form').submit();
    });

    $('#repd_form').on('submit', function (e)
    {
        $('.repd_div').fadeOut("slow", function ()
        {
            $('.repd_div').fadeIn("slow");
        });
    });

    var repd_progress = $('.repd_progress');
    if (repd_progress.length)
    {
        repd_progress.each(function ()
        {

            var per = Math.round($(this).parent().width() / 100 * $(this).attr("repd_percent")) - 10;
            $(this).animate({width: per + "px"}, 500);
            //$(this).height($(this).parent().height());

        });
    }


    var repd_graph = $('div[repd_graph="true"]');

    repd_graph.mouseout(function (event)
    {
        $(this).css('border-style', 'solid');
        $(this).fadeTo(0, 1);
        $(this).css('border-top', '');
    });

    repd_graph.mousemove(function (event)
    {
        var mv = $('div[repd_move="true"]');
        if (mv.length)
        {
            $(this).css('border-style', 'dashed');
            $(this).css('border-top', 'red thick dashed');
            $(this).fadeTo(0, 0.5);
            mv.attr('repd_exfield', $(this).attr('repd_fname'));
        }
    });
    $('div[repd_graph="true"]').mouseup(function ()
    {
        var elem = $('div[repd_move="true"]');

        event.preventDefault();

        $('#repd_action').val('settings');
        $('#repd_field_from').val(elem.attr('repd_fname'));
        $('#repd_field_to').val(elem.attr('repd_fname'));
        elem.attr('repd_move', 'false');
        $('#repd_form').submit();
    });

    if (repd_graph.length)
    {

        repd_graph.each(function ()
        {
            var items = $.parseJSON($(this).attr('repd_gr_data'));
            var sums = $.parseJSON($(this).attr('repd_gr_max'));

            for (key in items)
            {
                var lines = '';
                for (key2 in sums)
                {
                    var per = Math.round(items[key][key2] / sums[key2] * 100);
                    var w = per * 3;
                    lines = lines + '<br><span style="width:100px;display:inline-block">' + key2 + '</span><span style="text-align:center;padding:4px;color:white;margin:4px;background: #feccb1;background: -moz-linear-gradient(top,  #feccb1 0%, #f17432 50%, #ea5507 51%, #fb955e 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#feccb1), color-stop(50%,#f17432), color-stop(51%,#ea5507), color-stop(100%,#fb955e)); background: -webkit-linear-gradient(top,  #feccb1 0%,#f17432 50%,#ea5507 51%,#fb955e 100%);background: -o-linear-gradient(top,  #feccb1 0%,#f17432 50%,#ea5507 51%,#fb955e 100%);background: -ms-linear-gradient(top,  #feccb1 0%,#f17432 50%,#ea5507 51%,#fb955e 100%);background: linear-gradient(to bottom,  #feccb1 0%,#f17432 50%,#ea5507 51%,#fb955e 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#feccb1\', endColorstr=\'#fb955e\',GradientType=0 );display:inline-block;height:20px;width:0%" repd_percent="' + (per - 10) + '" repd_bars="true">&nbsp;' + per + '%</span>';
                }
                $(this).append('<div><span style="width:200px;display:inline-block"><h3>' + key + '</h3></span>' + lines + '</div>');
            }


        });
        $('span[repd_bars="true"]').each(function ()
        {
            $(this).animate({width: $(this).attr("repd_percent") + "%"}, 500);
        });
    }

});

/**
 * 简单假设：任何一个单元格最多只被一次收缩
 * 简单之美！
 * @param _obj
 * @param _i_nth
 * @param _i_datafield
 */
function toggle_vertical(_obj, _i_nth, _i_datafield)
{
    if($(_obj).children("i:eq(0)").hasClass("icon-minus"))//执行收缩
    {
        var obj_td = $(_obj).parent("td");
        var i_cz = 0, i_shift = parseInt($(obj_td).attr("colspan")) - _i_datafield;//实际收缩大小
        var i_x = 0;
        $(obj_td).prevAll().each(function()
        {
            i_x++;
            if($(this).css("display") == "none")
            {
                return true;
            }
            i_cz += $(this).attr("colspan") == undefined ? 1 : parseInt($(this).attr("colspan"));
        });
        var s_id = "x" + i_x + "y" + _i_nth;
        var i_begin = i_cz + _i_datafield + 1, i_end = i_cz + parseInt($(obj_td).attr("colspan"));
        var arr_row_span = [];
        $("table tr:gt(" + _i_nth + ")").each(function()
        {
            i_cz = 0;
            for(var i = arr_row_span.length - 1; i >= 0; i--)
            {
                if(--arr_row_span[i] > 0)
                {
                    i_cz++;
                }else
                {
                    arr_row_span.pop();
                }
            }

            $(this).children("td").each(function()
            {
                if($(this).css("display") == "none")
                {
                    return true;
                }
                i_cz += $(this).attr("colspan") == undefined ? 1 : parseInt($(this).attr("colspan"));
                if(i_cz >= i_begin && i_cz <= i_end)
                {
                    $(this).attr("c_id", s_id);
                    $(this).hide();
                }

                if($(this).attr("r_rowspan") != undefined)
                {
                    arr_row_span.push(parseInt($(this).attr("r_rowspan")));
                }else
                if($(this).attr("o_rowspan") != undefined)
                {
                    arr_row_span.push(parseInt($(this).attr("o_rowspan")));
                }else
                if($(this).attr("rowspan") != undefined)
                {
                    arr_row_span.push(parseInt($(this).attr("rowspan")));
                }
            });
        });
        var i_row_cz = 0;
        $("table tr:lt(" + _i_nth + "),table tr:eq(" + _i_nth + ")").each(function()
        {
            i_cz = 0;
            $(this).children("td").each(function()
            {
                if($(this).css("display") == "none")
                {
                    return true;
                }
                var i_span = $(this).attr("colspan") == undefined ? 1 : parseInt($(this).attr("colspan"));
                if(i_cz + 1 <= i_begin && i_cz + i_span >= i_end)
                {
                    if(i_row_cz == _i_nth)
                    {
                        $(this).attr("o_colspan", $(this).attr("colspan"));
                    }
                    $(this).attr("colspan", parseInt($(this).attr("colspan")) - i_shift);
                    return false;
                }
                i_cz += i_span;
            });
            i_row_cz++;
        });

        $(_obj).children("i:eq(0)").removeClass("icon-minus").addClass("icon-plus");
    }else//执行扩张
    {
        var obj_td = $(_obj).parent("td");
        var i_cz = 0, i_shift = parseInt($(obj_td).attr("o_colspan")) - parseInt($(obj_td).attr("colspan"));//实际扩张大小
        var i_x = 0;
        $(obj_td).prevAll().each(function()
        {
            i_x++;
            if($(this).css("display") == "none")
            {
                return true;
            }
            i_cz += $(this).attr("colspan") == undefined ? 1 : parseInt($(this).attr("colspan"));
        });
        var i_begin = i_cz + _i_datafield + 1, i_end = i_cz + parseInt($(obj_td).attr("o_colspan")),
            i_r_begin = i_cz + 1, i_r_end = i_cz + parseInt($(obj_td).attr("colspan"));
        var s_id = "x" + i_x + "y" + _i_nth;

        $("table td[c_id='" + s_id + "']").each(function(){
            $(this).css("display", "");
            $(this).removeAttr("c_id");
        });

        $("table tr:lt(" + _i_nth + "),table tr:eq(" + _i_nth + ")").each(function()
        {
            i_cz = 0;
            $(this).children("td").each(function()
            {
                if($(this).css("display") == "none")
                {
                    return true;
                }
                var i_span = $(this).attr("colspan") == undefined ? 1 : parseInt($(this).attr("colspan"));
                if(i_cz + 1 <= i_r_begin && i_cz + i_span >= i_r_end)// || i_cz + i_span + 1 == i_begin)
                {
                    $(this).attr("colspan", parseInt($(this).attr("colspan")) + i_shift);
                    return false;
                }
                i_cz += i_span;
            });
        });
        $(_obj).children("i:eq(0)").removeClass("icon-plus").addClass("icon-minus");
    }
}

function toggle_horizontal(_obj, _i_nth)
{
    if($(_obj).children("i:eq(0)").hasClass("icon-minus"))//执行收缩
    {
        var obj_td = $(_obj).parent("td");
        var i_cz = 0, i_shift = parseInt($(obj_td).attr("rowspan")) - 1;//实际收缩大小
        var obj_tr = $(obj_td).parent("tr");
        var i_y = 0;
        $(obj_tr).prevAll().each(function(){
            i_y++;
            if($(this).css("display") == "none")
            {
                return true;
            }
            var me = this;
            $(this).children("td[rowspan]").each(function(){
                var i_rowspan = parseInt($(this).attr("rowspan"));
                var me_td = this;
                $(me).nextAll().each(function(){
                    if($(this).css("display") == "none")
                    {
                        return true;
                    }
                    i_rowspan--;
                    if(i_rowspan <= 0)
                    {
                        return false;
                    }else
                    if(this == obj_tr[0])
                    {
                        if($(me_td).attr("r_rowspan") == undefined)
                        {
                            $(me_td).attr("r_rowspan", $(me_td).attr("rowspan"));
                        }
                        $(me_td).attr("rowspan", parseInt($(me_td).attr("rowspan")) - i_shift);
                    }
                });
            });
        });
        var s_id = "x" + _i_nth + "y" + i_y;

        $(obj_tr).nextAll().each(function(){
            if($(this).css("display") == "none")
            {
                return true;
            }
            $(this).css("display", "none");
            $(this).attr("c_id", s_id);
            i_cz++;
            if(i_cz >= i_shift)
            {
                return false;
            }
        });

        $(obj_td).attr("o_rowspan", $(obj_td).attr("rowspan")).removeAttr("rowspan");

        $(_obj).children("i:eq(0)").removeClass("icon-minus").addClass("icon-plus");
    }else//执行扩张
    {
        var obj_td = $(_obj).parent("td");
        var i_cz = 0, i_shift = parseInt($(obj_td).attr("o_rowspan")) - 1;//实际扩张大小
        var obj_tr = $(obj_td).parent("tr");
        var i_y = 0;
        $(obj_tr).prevAll().each(function()
        {
            i_y++;
            if($(this).css("display") == "none")
            {
                return true;
            }
            var me = this;
            $(this).children("td[rowspan]").each(function(){
                var i_rowspan = parseInt($(this).attr("rowspan"));
                var me_td = this;
                $(me).nextAll().each(function(){
                    if($(this).css("display") == "none")
                    {
                        return true;
                    }
                    i_rowspan--;
                    if(i_rowspan <= 0)
                    {
                        return false;
                    }else
                    if(this == obj_tr[0])
                    {
                        $(me_td).attr("rowspan", parseInt($(me_td).attr("rowspan")) + i_shift);
                    }
                });
            });
        });
        var s_id = "x" + _i_nth + "y" + i_y;
        $("table tr[c_id='" + s_id + "']").each(function(){
            $(this).css("display", "");
            $(this).removeAttr("c_id");
        });
        $(obj_td).attr("rowspan", $(obj_td).attr("o_rowspan")).removeAttr("o_rowspan");

        $(_obj).children("i:eq(0)").removeClass("icon-plus").addClass("icon-minus");
    }
}

function collapse()
{
    var i = 0;
    for(i = $("table tr").length - 1; i >= 0; i--)
    {
        var obj_td = $("table tr:eq(" + i + ")").children("td:eq(0)");
        if(obj_td.length > 0)
        {
            var obj_a = $(obj_td).children("a");
            if(obj_a.length > 0)
            {
                obj_a.click();
            }else
            if($(obj_td).text() == "合计")
            {
                break;
            }
        }
    }
    for(var j = i - 1; j >= 0; j--)
    {
        $("table tr:eq(" + j + ")").find("td > a").each(function ()
        {
            $(this).click();
        });
    }
}

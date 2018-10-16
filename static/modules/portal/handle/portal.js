
function handle_work(menu_flag, run_id, prcs_key_id, flow_id, prcs_id, flow_prcs){
	if(window.parent && typeof window.parent.createTab == 'function'){
		var url = "approve_center/list/input_form/?actionType=handle";
	}else{
		var url = "/general/approve_center/list/input_form/?actionType=handle";
	}
    url += "&MENU_FLAG=" + menu_flag;
    url += "&RUN_ID=" + run_id;
    url += "&PRCS_KEY_ID=" + prcs_key_id;
    url += "&FLOW_ID=" + flow_id;
    url += "&PRCS_ID=" + prcs_id;
    url += "&FLOW_PRCS=" + flow_prcs;
	url += "&MODULE_TYPE=" + 'portal';
	if(window.parent && typeof window.parent.createTab == 'function'){
		window.parent.createTab(301,'我的工作',url,'')
	}else{
		window.open(url);
	}
}

function flow_view(RUN_ID, FLOW_ID){
    var myleft = (screen.availWidth - 800) / 2;
    window.open("/general/approve_center/list/flow_view/?RUN_ID=" + RUN_ID + "&FLOW_ID=" + FLOW_ID, RUN_ID, "status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=400,left=" + myleft + ",top=100");
}

function user_view(USER_ID){
    var mytop = (jQuery(document).height() - 500) / 2 - 30;
    var myleft = (jQuery(document).width() - 780) / 2;
    window.open("/general/ipanel/user/user_info.php?WINDOW=1&USER_ID=" + USER_ID, "user_view", "status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=780,height=500,left=" + myleft + ",top=" + mytop + "\"");
}


function view_work(menu_flag, run_id, prcs_key_id, flow_id, prcs_id, flow_prcs){
    var url = "/general/approve_center/list/print/?";
    url += "RUN_ID=" + run_id;
    url += "&MENU_FLAG=" + menu_flag;
    url += "&PRCS_KEY_ID=" + prcs_key_id;
    url += "&FLOW_ID=" + flow_id;
    if (prcs_id)
    {
        url += "&PRCS_ID=" + prcs_id;
    }
    if (flow_prcs)
    {
        url += "&FLOW_PRCS=" + flow_prcs;
    }
    var tmp_height = jQuery(window.parent.parent) ? jQuery(window.parent.parent.document).height() : jQuery(document).height()
    var configStr = "status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=" + (jQuery(document).width() - 20) + ",height=" + tmp_height + ",left=-4,top=0";
    window.open(url, "view_work_" + run_id, configStr);
}

function view_graph(FLOW_ID){
    var myleft = (screen.availWidth - 800) / 2;
    window.open("/general/approve_center/flow_view.php?FLOW_ID=" + FLOW_ID, "", "status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=500,left=" + myleft + ",top=50");
}

function reminders(run_id,flow_id,run_name,prcs_id,n,prcs_flag,type){
    if(!jQuery(".mobile-check").hasClass("mobile-bg-static")){
        jQuery(".mobile-check").addClass("mobile-bg-static");
    }
	jQuery('#reminder_run_id').val(run_id);
	jQuery('#reminder_flow_id').val(flow_id);
	jQuery('#reminder_run_name').val(run_name);
	jQuery('#reminder_prcs_id').val(prcs_id);
	jQuery('#reminder_prcs_flag').val(prcs_flag);
	jQuery('#reminder_type').val(type);
    n==1 ? jQuery('#reminder_content').val("麓脽掳矛脢脗脦帽脤谩脨脩拢潞脟毛脛煤戮隆驴矛掳矛脌铆麓脣鹿陇脳梅") : jQuery('#reminder_content').val(sprintf(td_lang.general.workflow.msg_292, run_id, run_name));
	open_bootcss_modal('reminderModal', '500');
}

function open_bootcss_modal(modal_id, widths, tops){
    jQuery('#' + modal_id).modal
    ({keyboard: false, backdrop: "static"})
    if (typeof(widths) == "undefined" || widths == ""){
        widths = 560;
    }
    if (typeof(tops) == "undefined" || tops == ""){
        tops = 10;
    }
    jQuery('#' + modal_id).css('width', widths + 'px');
    jQuery('#' + modal_id).css('margin-left', -(widths / 2) + 'px');
    jQuery('#' + modal_id).css('top', '15px');
    jQuery('.modal-body').css('max-height', jQuery(window).height() - 150);
    jQuery('.modal-footer').css('padding', '8px 15px 8px');
}


//兼容旧信息中心
//全部
function view_more(name,label,url){
   openURL(url, name, label);
}

function openURL(url, name, label){
   if(window.top.bTabStyle)
      window.top.openURL(name, label, url);
   else if(window.top != self && typeof(window.top.openURL) == 'function')
      window.top.openURL(url);
   else
      window.open(url,'name','height=600,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=0,left=(screen.availWidth-500)/2,resizable=yes');
}
//翻页
function NextPage(module_id, page){
    var o = jQuery('#module_' + module_id + '_ul ul');
    if(!o){
         return;
    }
    var height = o.height(),
        top = parseInt(o.css("margin-top")),
        newtop = top +  page * 100;
    if( height > Math.abs(newtop) && height >= 100 && height <= 300){
        if( height >= 100 && height < 200 ){
            if( newtop == -100){
                jQuery('#module_'+module_id+'_link_pre').removeClass("PageLinkDisable");
                jQuery('#module_'+module_id+'_link_next').addClass("PageLinkDisable");
            }else if(newtop == 0){
                jQuery('#module_'+module_id+'_link_pre').addClass("PageLinkDisable");
                jQuery('#module_'+module_id+'_link_next').removeClass("PageLinkDisable");
            }else{
                page == -1 ? alert("已是最后一页") : alert("已是第一页");
                return;
            }
        }else if( height >= 200 && height <= 300){
            if(newtop == 0){
                jQuery('#module_'+module_id+'_link_pre').addClass("PageLinkDisable");
                jQuery('#module_'+module_id+'_link_next').removeClass("PageLinkDisable");
            }else if(newtop == -100){
                jQuery('#module_'+module_id+'_link_pre').removeClass("PageLinkDisable");
                jQuery('#module_'+module_id+'_link_next').removeClass("PageLinkDisable");
            }else if(newtop == -200){
                jQuery('#module_'+module_id+'_link_pre').removeClass("PageLinkDisable");
                jQuery('#module_'+module_id+'_link_next').addClass("PageLinkDisable");
            }else{
                page == -1 ? alert("已是最后一页") : alert("已是第一页");
                return;
            }
        }
        jQuery('#module_'+module_id+'_ul ul').css("margin-top",newtop)
    }else{
        page == -1 ? alert("已是最后一页") : alert("已是第一页");
        return;
    }
}
// 类型切换
function new_req() {
	if (window.XMLHttpRequest) return new XMLHttpRequest;
	else if (window.ActiveXObject) {
		var req;
		try { req = new ActiveXObject("Msxml2.XMLHTTP"); }
		catch (e) { try { req = new ActiveXObject("Microsoft.XMLHTTP"); }
		catch (e) { return null; }}
		return req;
	} else return null;
}
function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}
function _get(url, args, fn, sync){
	sync = isUndefined(sync) ? true : sync;
	var req = new_req();
    if (args != "") args = "?" + args;
    url = "/general/mytable/intel_view/" + url
	try{
	   req.open("GET", url + args, sync);
	}
	catch(ex){
	   alert(ex.description);
	   return;
	}
	if(false == isUndefined(fn))
	   req.onreadystatechange = function() { 
           if (req.readyState == 4) fn(req);
        };
	req.send('');
}
//恢复挂起
function work_run_restore_single_work(run_id, prcs_key_id)
{
    var url="/module/approve_center/engine/re_pause_work.php?run_id_str="+run_id+"&prcs_key_id_str="+prcs_key_id;
    jQuery.get(url,{},function(data){
        alert(td_lang.general.workflow.msg_314);//工作已恢复
        jQuery("#gridTable").jqGrid('setGridParam', { serializeGridData : function(e){ e.connstatus = 1;  return e;} }).trigger('reloadGrid');
    });
}

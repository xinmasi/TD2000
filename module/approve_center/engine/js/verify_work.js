//����
function comment(RUN_ID,FLOW_ID,BEGIN_DEPT,ARCHIVE_ID,MODULE_TYPE){
    if(ARCHIVE_ID != '' && typeof(ARCHIVE_ID) != 'undefined'){
        var ARCHIVE_ID_STR = "&ARCHIVE_ID="+ARCHIVE_ID;
    }else{
        var ARCHIVE_ID_STR = '';
    }
    var myleft=(screen.availWidth-550)/2;
    var mytop=(screen.availHeight-200)/2;
	var url = MODULE_TYPE == 'workflow' ? "/module/workflow/engine/verify_work.php?RUN_ID="+RUN_ID+"&FLOW_ID="+FLOW_ID+"&BEGIN_DEPT="+BEGIN_DEPT+ARCHIVE_ID_STR : "/module/approve_center/engine/verify_work.php?RUN_ID="+RUN_ID+"&FLOW_ID="+FLOW_ID+"&BEGIN_DEPT="+BEGIN_DEPT+ARCHIVE_ID_STR;
    window.open(url,"comment","status=0,toolbar=no,menubar=no,width=550,height=200,location=no,scrollbars=yes,resizable=no,left="+myleft+",top="+mytop);
}

//��ע
function focus_run(RUN_ID,FLOW_ID,FLOW_NAME,OP)
{   
    var OP_DESC=OP==1?td_lang.general.workflow.msg_4:td_lang.general.workflow.msg_5;//"��ע":"ȡ����ע"
    var msg2 = sprintf(td_lang.inc.msg_126,OP_DESC);
    var msg=msg2;
    if(window.confirm(msg))
    {
        jQuery.get("/module/approve_center/engine/focus_work.php",{"action":"concern_work","run_id":RUN_ID,"op":OP,"flow_id":FLOW_ID,"flow_name":FLOW_NAME},function(data)
        {
            //jQuery.showTip(data);
            //jQuery("#gridTable").trigger('reloadGrid');
            jQuery("#gridTable").jqGrid('setGridParam', { serializeGridData : function(e){ e.connstatus = 1;  return e;} }).trigger('reloadGrid');
        });
    }
}
//ǿ�ƽ���
function end_run(run_id_one,flow_id,begin_dept)
{
    var msg=td_lang.general.workflow.msg_2;//"ȷ��Ҫǿ�ƽ�����ѡ������"
    if(window.confirm(msg))
    {
        if(typeof run_id_one == "undefined")
            var run_str=get_run_str();
        else
            var run_str=run_id_one;
        if(run_str=="")
        {
            alert(td_lang.general.workflow.msg_3);//"Ҫ����������������ѡ������һ�"
            return;
        }
        jQuery.get("/module/approve_center/engine/verify_end_work.php",{"action":"end_work","run_id_str":run_str,"flow_id":flow_id,"begin_dept":begin_dept},function(data)
        {
              //jQuery.showTip(data);
                jQuery("#gridTable").jqGrid('setGridParam', { serializeGridData : function(e){ e.connstatus = 1;  return e;} }).trigger('reloadGrid');
          });
    }
}
//�ָ�ִ��
function restore_run(RUN_ID,flow_id,begin_dept)
{
    var msg=td_lang.general.workflow.msg_1;//"ȷ��Ҫ���˹����ָ���ִ������"
    if(window.confirm(msg))
    {
        var url="/module/approve_center/engine/restore_work.php?action=restore_work&run_id="+RUN_ID+"&flow_id="+flow_id+"&begin_dept="+begin_dept;
        jQuery.get(url,{},function(data){
            //jQuery.showTip(data);
            jQuery("#gridTable").jqGrid('setGridParam', { serializeGridData : function(e){ e.connstatus = 1;  return e;} }).trigger('reloadGrid');
        });
    }
}

//�鿴������
function view_work(menu_flag, run_id, prcs_key_id, flow_id, prcs_id, flow_prcs, archive_id){
    var url = "/general/approve_center/list/print/index.php?actionType=view";
    url += "&MENU_FLAG="+menu_flag;
    url += "&RUN_ID="+run_id;
    url += "&PRCS_KEY_ID="+prcs_key_id;
    url += "&FLOW_ID="+flow_id;
    //url += "&PRCS_ID="+prcs_id;
    //url += "&FLOW_PRCS="+flow_prcs;
    if(archive_id != '' && typeof archive_id != 'undefined')
    {
        url += "&ARCHIVE_TIME="+archive_id;
    }
    var tmp_height = jQuery(window.parent.parent) ? jQuery(window.parent.parent.document).height() : jQuery(document).height()
    var configStr = "status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width="+(jQuery(document).width()-20)+",height="+tmp_height+",left=-4,top=0";
    window.open(url, "view_work_"+run_id, configStr);
}

//����ɾ��
function delete_run(RUN_ID,FLOW_ID,BEGIN_DEPT,MODULE_TYPE)
{
    var msg=td_lang.general.workflow.msg_6;//"ȷ��Ҫɾ����ѡ������"
    if(window.confirm(msg))
    {
		var url = MODULE_TYPE == 'workflow' ? "/module/workflow/engine/delete_work.php" : "/module/approve_center/engine/delete_work.php";
        jQuery.get(url,{"action":"delete_work","run_id_str":RUN_ID,"flow_id":FLOW_ID,"begin_dept":BEGIN_DEPT},function(data)
        {
            
            //if(data== 1)
           // {
                //jQuery.showTip(td_lang.general.workflow.msg_7);//�ù�����ɾ��
                jQuery("#gridTable").jqGrid('setGridParam', { serializeGridData : function(e){ e.connstatus = 1;  return e;} }).trigger('reloadGrid');
           // }
           // else
           // {
                //alert(td_lang.general.workflow.msg_8);
           // }
                
        });
    }
}

Array.prototype.unique = function()
{
    var n = {},r=[]; //nΪhash����rΪ��ʱ����
    for(var i = 0; i < this.length; i++) //������ǰ����
    {
        if (!n[this[i]]) //���hash����û�е�ǰ��
        {
            n[this[i]] = true; //����hash��
            r.push(this[i]); //�ѵ�ǰ����ĵ�ǰ��push����ʱ��������
        }
    }
    return r;
}
//ί��
function work_run_intrust(key_prcs_id)
{
    var run_id =jQuery("#RUN_ID_"+key_prcs_id).val();
    var flow_prcs = jQuery("#FLOW_PRCS_"+key_prcs_id).val();
    var flow_id = jQuery("#FLOW_ID_"+run_id).val();
    var prcs_id = jQuery("#PRCS_ID_"+key_prcs_id).val();
    loadModal();
    jQuery('#myModal_intrust').load('/module/approve_center/engine/data/work_run_intrust.php?run_id='+run_id+'&flow_id='+flow_id+'&flow_prcs='+flow_prcs+'&prcs_id='+prcs_id+'&prcs_key_id='+key_prcs_id, function() {

        jQuery('#myModalLabel').html(td_lang.general.workflow.msg_59);
        
        var key ;
        jQuery("#gridTable").find('tr').each(function(){
        
            key = jQuery(this).find('input[prcs_key_id='+key_prcs_id+']').length;
            if(key == 1){    
                var run_id =jQuery("#RUN_ID_"+key_prcs_id).val();
                var flow_name = jQuery(this).find("td[aria-describedby='gridTable_flow_name']").find('a').html();
                //var run_name = jQuery(this).find("td[aria-describedby='gridTable_RUN_NAME']").find('a').html();
                //var prcs_name = jQuery(this).find("td[aria-describedby='gridTable_TIME']").find('a').html();
                var delegate_type = jQuery("#FREE_OTHER_"+run_id).val();
                var flow_prcs = jQuery("#FLOW_PRCS_"+key_prcs_id).val();
                var prcs_id = jQuery("#PRCS_ID_"+key_prcs_id).val();
                var flow_id = jQuery("#FLOW_ID_"+run_id).val();
                var from_user_name = jQuery("#FROM_USER_NAME_"+key_prcs_id).val();
                var run_name = jQuery("#RUN_NAME_"+key_prcs_id).val();
                var prcs_name = jQuery("#PRCS_NAME_"+key_prcs_id).val();
                var op_flag = jQuery("#OP_FLAG_"+key_prcs_id).val();
                
                var selectData = {
                    'run_id':run_id, 
                    'run_name':run_name, 
                    'prcs_name':prcs_name, 
                    'prcs_key_id':key_prcs_id,
                    'flow_id':flow_id, 
                    'prcs_id':prcs_id, 
                    'delegate_type':delegate_type, 
                    'flow_prcs':flow_prcs,
                    'from_user_name':from_user_name,
                    'op_flag':op_flag
                };
                var SMS_CONTENT_SIZE = td_lang.general.workflow.msg_258 + run_name;
                jQuery("#SMS_CONTENT").val(SMS_CONTENT_SIZE);
                var resultData = selectData;
                var Data = {"runData":resultData};
                var template = jQuery.templates("#intrustDataTmpl");
                var htmlOutput = template.render(Data);
                jQuery("#intrustData").html(htmlOutput);
                jQuery('#PLUGIN_INTRUST_BEFORE_POSITION').html(jQuery.templates('#PLUGIN_INTRUST_BEFORE_POSITION_Tmpl').render({end_script:'</script>'}));
                jQuery('#PLUGIN_INTRUST_AFTER_POSITION').html(jQuery.templates('#PLUGIN_INTRUST_AFTER_POSITION_Tmpl').render({end_script:'</script>'}));
                jQuery("button[btntype='work_run_intrust']").click(function(){//��ͨ����

                    var attrTrObj = jQuery(this).parent().parent();
                    
                    var delegate_type = attrTrObj.attr('delegate_type');
                    if(delegate_type === '2'){ //����ί��  btn-success
                        var module_id = '', 
                        to_id = attrTrObj.find('input[type="hidden"]').attr('id'), 
                        to_name = attrTrObj.find('input[type="text"]').attr('id'),  
                        manage_flag = '0',
                        form_name = "form1"; 
                        window.org_select_callbacks = window.org_select_callbacks || {}; 
                        window.org_select_callbacks.add = function(item_id, item_name){}; 
                        window.org_select_callbacks.remove = function(item_id, item_name){};
                        window.org_select_callbacks.clear = function(){}; 
                        SelectUserSingle('5', module_id, to_id, to_name, manage_flag, form_name); 
                    }else if(delegate_type === '1'){//������ί�е�ǰ���辭���� btn-info �����Ͳ�����ָ��������Ϊ��¼��RUN_ID��ͬ����������Ȩ�����õ���RUN_ID�ʵ�������
                        var run_id = jQuery(this).attr('run_id');
                        var flow_id = jQuery(this).attr('flow_id');
                        var prcs_id = jQuery(this).attr('prcs_id');
                        var flow_prcs = jQuery(this).attr('flow_prcs');
                        var to_id = attrTrObj.find('input[type="hidden"]').attr('id'); 
                        var to_name = attrTrObj.find('input[type="text"]').attr('id');
                        LoadWindow(delegate_type,flow_id, run_id, prcs_id, flow_prcs, to_id, to_name);
                    }else if(delegate_type === '3'){ //���������õľ���Ȩ��ί��  btn-warning
                        var run_id = jQuery(this).attr('run_id');
                        var flow_id = jQuery(this).attr('flow_id');
                        var prcs_id = jQuery(this).attr('prcs_id');
                        var flow_prcs = jQuery(this).attr('flow_prcs');
                        var to_id = attrTrObj.find('input[type="hidden"]').attr('id'); 
                        var to_name = attrTrObj.find('input[type="text"]').attr('id');
                        LoadWindow(delegate_type,flow_id, run_id, prcs_id, flow_prcs, to_id, to_name);
                    }
                });
            }
        });
    });
}
function work_to_back(RUN_ID, PRCS_KEY_ID, FLOW_ID, PRCS_ID, FLOW_PRCS, ALLOW_BACK, RUN_NAME)
{
    jQuery.ajax({
        url: '/general/approve_center/list/input_form/data/getflowprcsdata.php',
        data: {flow_id: FLOW_ID,allow_back: ALLOW_BACK,run_id: RUN_ID,flow_prcs: FLOW_PRCS,prcs_id: PRCS_ID,prcs_key_id:PRCS_KEY_ID},
        async: false,
        dataType: 'json',
        success: function(jsonData){
            var back_data = {allow_back: ALLOW_BACK, run_id: RUN_ID, flow_id: FLOW_ID, run_name: RUN_NAME, flow_prcs: FLOW_PRCS, prcs_id: PRCS_ID, prcs_key_id: PRCS_KEY_ID, allow_data: jsonData};
            var Data = {"backData": back_data};
            var template = jQuery.templates("#backDataTmpl");
            var htmlOutput = template.render(Data);
            var retWork = jsonData[0]['return_work'];
            var retWorkAll = jsonData[0]['return_work_all'];
            jQuery("#back_ok").text( retWork);
            jQuery("#allow_back_myModalLabel").text( retWorkAll);

            jQuery("#allow_back_div").html(htmlOutput);
            if(jQuery("#is_child").length != 0)
            {
                jQuery("#back_ok").hide();
            }
        }
    });
    
    jQuery.ajax({
        url: '/general/approve_center/list/input_form/data/getflowprcsview.php',
        data: {flow_id: FLOW_ID, run_id: RUN_ID, flow_prcs: FLOW_PRCS, prcs_id: PRCS_ID, prcs_key_id: PRCS_KEY_ID},
        async: false,
        success: function(data){
            jQuery('#back_plugin').html(data); 
        }
    });
    
    jQuery('#PLUGIN_BACK_BEFORE_POSITION').html(jQuery.templates('#PLUGIN_BACK_BEFORE_POSITION_Tmpl').render({end_script:'</script>'}));
    jQuery('#PLUGIN_BACK_AFTER_POSITION').html(jQuery.templates('#PLUGIN_BACK_AFTER_POSITION_Tmpl').render({end_script:'</script>'}));
    open_bootcss_modal('myModal_allow_back', "760", "5");
}
function LoadWindow(FREE_OTHER,FLOW_ID, RUN_ID, PRCS_ID, FLOW_PRCS, TO_ID, TO_NAME)
{
    if(FREE_OTHER==1)
    {
        var URL="/general/approve_center/list/others/user_select_prcs/?FLOW_ID="+FLOW_ID+"&RUN_ID="+RUN_ID+"&PRCS_ID="+PRCS_ID+"&FLOW_PRCS="+FLOW_PRCS+"&TO_ID="+TO_ID+"&TO_NAME="+TO_NAME;
        var w=250;
        var h=300;
    }
    else if(FREE_OTHER==3)
    {
        var URL="/general/approve_center/list/others/user_select_all/?FLOW_ID="+FLOW_ID+"&RUN_ID="+RUN_ID+"&PRCS_ID="+PRCS_ID+"&FLOW_PRCS="+FLOW_PRCS+"&TO_ID="+TO_ID+"&TO_NAME="+TO_NAME;
        var w=400;
        var h=350;
    }
    var loc_y=loc_x=200;
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-event.offsetX;
        loc_y=document.body.scrollTop+event.clientY-event.offsetY+210;
    }
    LoadDialogWindow(URL,self,loc_x, loc_y, w, h);
}
//��������
function loadModal()
{
    jQuery('#myModal').modal({
        keyboard: true,
        backdrop:"static"
    });
    jQuery('#myModal').css('top','15px');
    jQuery('.modal-body').css('max-height', jQuery(window).height()-150);
    jQuery('.modal-footer').css('padding', '8px 15px 8px');
}
function check_form()
{
    try{
        // �û��Զ���js�ű�ִ�г���
        var beforeCustomScript = jQuery("#intrustBeforeCustomScript").val();
        if(typeof beforeCustomScript !== 'undefined')
        {
            var customScriptArr = beforeCustomScript.split(",");
            for(var customScriptCount = 0; customScriptCount < customScriptArr.length; customScriptCount++)
            {
                if (typeof window[customScriptArr[customScriptCount]] == 'function')
                {
                    var ret = window[customScriptArr[customScriptCount]]();
                    if(typeof ret !== 'undefined')
                    {
                        return;
                    } 
                } 
            }
        }
        
        jQuery("input[type='text'][name^='intrust_run_']").each(function(){ //��֤
            if(jQuery(this).val() == ""){
                var run_id = jQuery(this).parent().parent().find("button:first").attr("run_id");
                throw sprintf(td_lang.general.workflow.msg_58, run_id, td_lang.general.workflow.msg_59);
                return false;
            }
            var dataTrObj = jQuery(this).parent().parent();
            var delegate_type = dataTrObj.attr('delegate_type');
            if(!delegate_type){
                return true;
            }
            var run_name = dataTrObj.find('td:eq(1)').html().replace(/<span(.*)<\/span>/ig, '');
            var btnObj = dataTrObj.find("button:first");
            var textObj = dataTrObj.find("input[type='hidden']:first");
            var run_id = btnObj.attr("run_id");
            var flow_id = btnObj.attr("flow_id");
            var prcs_key_id = btnObj.attr("prcs_key_id");
            var prcs_id = btnObj.attr("prcs_id");
            var flow_prcs = btnObj.attr("flow_prcs");
            var from_user_name = btnObj.attr("from_user_name");
            var op_flag = btnObj.attr("op_flag");
            var serializeVal = jQuery('#instrust_plugin').serializeArray();
            
            var to_user = textObj.val();
            var datasrc = {"run_id": run_id,
                           "prcs_key_id": prcs_key_id,
                           "prcs_id": prcs_id,
                           "flow_prcs": flow_prcs,
                           "to_user": to_user,
                           "from_user_name": from_user_name,
                           "op_flag": op_flag
                           };
                           
            for(var thisCount = 0; thisCount < serializeVal.length; thisCount++)
            {
                datasrc[serializeVal[thisCount].name] = serializeVal[thisCount].value;
            }
            
            jQuery.ajax({
                type: "POST",
                url: "/module/approve_center/engine/data/work_to_intrust.php",
                cache: false,
                async: false,
                data: datasrc,
                error: function(msg){
                    alert(msg);
                },
                success: function(msg){
                    if(msg == ""){
                        var error_html = "<a href='javascript:;' data-toggle='tooltip' data-placement='top' title='' class='result_ok'></a>";
                        dataTrObj.find("td:eq(3)").find('.result-block').html(error_html);
                    }else if(msg.substring(0, 5) == "ERROR"){
                        var msg_arr = msg.split("|");
                        var msg_info = msg_arr[1];
                        var error_html = "<a href='javascript:;' data-toggle='tooltip' data-placement='top' title='"+msg_info+"' class='result_error'></a>";
                        dataTrObj.find("td:eq(3)").find('.result-block').html(error_html);
                        jQuery('.result_error').tooltip('show');
                    }else{
                        alert(msg);
                    }
                }
            });
        });    
    
        if(jQuery('#intrustData').find('.result_error').length > 0){//�д�����ʾ���ύ
            return false;
                        
    
        }else{    //�ύ
            var detaile_count = 0;
            jQuery(".ajax-result-block").html(td_lang.general.workflow.msg_118);
            jQuery(".ajax-result-block").css("display", "block");
            jQuery('#work_run_submit').attr("disabled", true);
            var msg_check = jQuery("#intrust_sms").is(":checked") ? "checked" : "";
            var mobile_check = jQuery("#intrust_mobile").is(":checked") ? "checked" : "";
            var sms_content = jQuery('#SMS_CONTENT').val();
                
    
            jQuery("input[type='text'][name^='intrust_run_']").each(function(){
                if(jQuery(this).val() == ""){
                    var run_id = jQuery(this).parent().parent().find("button:first").attr("run_id");
                    throw sprintf(td_lang.general.workflow.msg_58, run_id, td_lang.general.workflow.msg_59);
                    return false;
                }
                var dataTrObj = jQuery(this).parent().parent();
                var delegate_type = dataTrObj.attr('delegate_type');
                if(!delegate_type){
                    return true;
                }
    
    
                var run_name = dataTrObj.find('td:eq(1)').html().replace(/<span(.*)<\/span>/ig, '');
                var btnObj = dataTrObj.find("button:first");
                var textObj = dataTrObj.find("input[type='hidden']:first");
                var run_id = btnObj.attr("run_id");
                var flow_id = btnObj.attr("flow_id");
                var prcs_key_id = btnObj.attr("prcs_key_id");
                var prcs_id = btnObj.attr("prcs_id");
                var flow_prcs = btnObj.attr("flow_prcs");
                var from_user_name = btnObj.attr("from_user_name");
                var op_flag = btnObj.attr("op_flag");
                var to_user = textObj.val();
                var datasrc = { "run_id":run_id,
                                "prcs_key_id":prcs_key_id,
                                "prcs_id":prcs_id,
                                "flow_prcs":flow_prcs,
                                "to_user":to_user,
                                "mobile_check":mobile_check,
                                "msg_check":msg_check,
                                "sms_content":sms_content,
                                "run_name":run_name,
                                "flow_id":flow_id,
                                "from_user_name":from_user_name,
                                "op_flag":op_flag
                            };
                
                var serializeVal = jQuery('#instrust_plugin').serializeArray();
                for(var thisCount = 0; thisCount < serializeVal.length; thisCount++)
                {
                    datasrc[serializeVal[thisCount].name] = serializeVal[thisCount].value;
                }
    
                jQuery.ajax({
                    type: "POST",
                    url: "/module/approve_center/engine/data/work_to_intrust.php?action=submit",
                    cache: false,
                    async: false,
                    data: datasrc,
                    error: function(msg){
                        alert(msg);
                    },
                    success: function(msg){
                        if(msg == ""){
                            var error_html = "<a href='javascript:;' data-toggle='tooltip' data-placement='top' title='' class='result_ok'></a>";
                            dataTrObj.find("td:eq(3)").find('.result-block').html(error_html);
                            detaile_count++;
                        }else if(msg.substring(0, 5) == "ERROR"){
                            var msg_arr = msg.split("|");
                            var msg_info = msg_arr[1];
                            var error_html = "<a href='javascript:;' data-toggle='tooltip' data-placement='top' title='"+msg_info+"' class='result_error'></a>";
                            dataTrObj.find("td:eq(3)").find('.result-block').html(error_html);
                            jQuery('.result_error').tooltip('show');
                            detaile_count++;
                        }else{
                            jQuery('#work_run_submit').attr("disabled", false);
                        }
                    }
                });
            });    
            if(parseInt(detaile_count) > 0){
                jQuery(".ajax-result-block").html(td_lang.general.workflow.msg_119);
                jQuery(".ajax-result-block").css("color", "red");
            }else{
                jQuery(".ajax-result-block").html(td_lang.general.workflow.msg_120);
            }    
         
            setTimeout(function(){
                jQuery('#myModal').modal('hide');
                jQuery('#work_run_submit').attr("disabled", false);
                //window.refreshGrid();
                jQuery("#gridTable").jqGrid('setGridParam', { serializeGridData : function(e){ e.connstatus = 1;  return e;} }).trigger('reloadGrid');
            }, 2000);
            
            //�û��Զ���js�ű�ִ�г���
            var afterCustomScript = jQuery("#intrustAfterCustomScript").val();
            if(typeof afterCustomScript !== 'undefined')
            {
                var customScriptArr = afterCustomScript.split(",");
                for(var customScriptCount = 0; customScriptCount < customScriptArr.length; customScriptCount++)
                {
                    if (typeof window[customScriptArr[customScriptCount]] == 'function')
                    {
                        var ret = window[customScriptArr[customScriptCount]]();
                        if(typeof ret !== 'undefined')
                        {
                            return;
                        } 
                    } 
                }
            }  
        }
    }catch(e){
        alert(e);
        return false;
    }
}
function back_ok()
{
    var back_run_id = jQuery("#back_run_id").val();
    var back_flow_id = jQuery("#back_flow_id").val();
    var back_run_name = jQuery("#back_run_name").val();
    var back_flow_prcs = jQuery("#back_flow_prcs").val();
    var back_prcs_id = jQuery("#back_prcs_id").val();
    var back_prcs_key_id = jQuery("#back_prcs_key_id").val();
    
    //�û��Զ���js�ű�ִ�г���
    var beforeCustomScript = jQuery("#backBeforeCustomScript").val();
    if(typeof beforeCustomScript !== 'undefined')
    {
        var customScriptArr = beforeCustomScript.split(",");
        for(var customScriptCount = 0; customScriptCount < customScriptArr.length; customScriptCount++)
        {
            if (typeof window[customScriptArr[customScriptCount]] == 'function')
            {
                var ret = window[customScriptArr[customScriptCount]]();
                if(typeof ret !== 'undefined')
                {
                    return;
                } 
            } 
        }
    }
    
    //# ��ǩ����Ϊ��
    if (jQuery.trim(jQuery('#back_counter_singn').val()) === '') {
        alert(td_lang.general.workflow.msg_285);
        return false;
    }
    var serializeVal = jQuery('#back_plugin').serialize();
    if(serializeVal != "")
    {
        serializeVal = "&" + serializeVal;
    }
    jQuery('#back_ok').attr('disabled', 'disabled');
    var allow_back = jQuery('#allow_back').val();
    jQuery('#back_to_prcs').val('');
    if (allow_back == '1') {
        var checked_prcs = jQuery('input[type="radio"][name="back_prcs"]:checked');
        if (checked_prcs.length == 0) {
            alert(td_lang.general.workflow.msg_255);
            jQuery(this).removeAttr('disabled');
            return false;
        }
        checked_prcs.each(function(i, v) {
            jQuery('#back_to_prcs').val(jQuery('#back_to_prcs').val() + jQuery(v).attr('id') + ',');
        });
        var sms_check_obj=jQuery('#back-sms span.sms-check');
        var sms_check_val=sms_check_obj.hasClass('sms-bg-static')?'':'checked';
        var mobile_check_obj=jQuery('#back-mobile span.mobile-check');
        var mobile_check_val=mobile_check_obj.hasClass('mobile-bg-static')?'':'checked';
        var email_check_obj=jQuery('#back-email span.email-check');
        var email_check_val=email_check_obj.hasClass('email-bg-static')?'':'checked';
    } else if (allow_back == '2') {
        if (jQuery('input[type="radio"][name="back_prcs"]:checked').length == 0) {
            alert(td_lang.general.workflow.msg_255);
            jQuery(this).removeAttr('disabled');
            return false;
        }
        jQuery('#back_to_prcs').val(jQuery('input[type="radio"][name="back_prcs"]:checked').attr('id'));
        var sms_check_obj=jQuery('#back-sms span.sms-check');
        var sms_check_val=sms_check_obj.hasClass('sms-bg-static')?'':'checked';
        var mobile_check_obj=jQuery('#back-mobile span.mobile-check');
        var mobile_check_val=mobile_check_obj.hasClass('mobile-bg-static')?'':'checked';
        var email_check_obj=jQuery('#back-email span.email-check');
        var email_check_val=email_check_obj.hasClass('email-bg-static')?'':'checked';
    }
    var back_data ='email_check='+email_check_val+'&mobile_check='+mobile_check_val+'&msg_check='+sms_check_val+'&flow_id=' + back_flow_id + '&run_name=' + back_run_name + '&flow_prcs=' + back_flow_prcs + '&prcs_id=' + back_prcs_id + '&prcs_key_id=' + back_prcs_key_id + '&run_id=' + back_run_id + '&back_counter_singn=' + jQuery('#back_counter_singn').val() + '&allow_back=' + jQuery('#allow_back').val()
    + '&back_to_prcs=' + jQuery('#back_to_prcs').val() + serializeVal;
    jQuery.ajax({
        type: 'POST',
        url: '/general/approve_center/list/input_form/data/backhandle.php',
        data: back_data,
        success: function(msg) {
            var msg_arr = msg.split('|');
            if (msg === 'SUCCESS|') {
                alert(td_lang.general.workflow.msg_256);
                setTimeout(function(){
                    jQuery('#myModal_allow_back').modal('hide');
                    jQuery('#back_ok').attr("disabled", false);
                    //window.refreshGrid();
                    jQuery("#gridTable").jqGrid('setGridParam', { serializeGridData : function(e){ e.connstatus = 1;  return e;} }).trigger('reloadGrid');
                }, 1000);
                
                //�û��Զ���js�ű�ִ�г���
                var afterCustomScript = jQuery("#backAfterCustomScript").val();
                if(typeof afterCustomScript !== 'undefined')
                {
                    var customScriptArr = afterCustomScript.split(",");
                    for(var customScriptCount = 0; customScriptCount < customScriptArr.length; customScriptCount++)
                    {
                        if (typeof window[customScriptArr[customScriptCount]] == 'function')
                        {
                            var ret = window[customScriptArr[customScriptCount]]();
                            if(typeof ret !== 'undefined')
                            {
                                return;
                            } 
                        } 
                    }
                }
            } else if (msg_arr[0] == 'ERROR') {
                alert(msg_arr[1]);
                jQuery('#back_ok').removeAttr('disabled');
                return false;
            } else {
                alert(td_lang.module.msg_49);
                alert(msg);
                jQuery('#back_ok').removeAttr('disabled');
                return false;
            }
        }
    });
}
//һ���߰�
function onekey_reminders(mark)
{
    var rowData = jQuery('#gridTable').jqGrid('getGridParam','selarrrow');
    if(rowData.length)
    {
        var key_prcs_id = "";
        var run_id = "";
        var flow_id = "";
        for(var i=0;i<rowData.length;i++)
        {
            var OPERATION_S = jQuery('#gridTable').jqGrid('getCell',rowData[i],'OPERATION');
            var OPERATION_STR = OPERATION_S.substring(OPERATION_S.indexOf('('),OPERATION_S.indexOf(')'));
            var operation_str = OPERATION_STR.split(",");
            if(typeof(mark) != 'undefined' && mark=='query')
            {
                if(OPERATION_S.indexOf("�߰�") == -1)//�����޴߰�Ȩ��
                    continue;
                run_id += operation_str[0].replace(/[^0-9]/ig,"")+",";
                flow_id += operation_str[1].replace(/[^0-9]/ig,"")+",";
            }else
            {
                run_id += operation_str[0].replace(/[^0-9]/ig,"")+",";
                key_prcs_id +=operation_str[1].replace(/[^0-9]/ig,"")+",";
                flow_id += operation_str[2].replace(/[^0-9]/ig,"")+",";
            }
        }
        reminders(run_id,flow_id,"","",key_prcs_id,1);
    }else
    {
        alert("������ѡ��һ����¼");
    }
}
//�����߰�
function reminders(run_id,flow_id,run_name,user_id,key_prcs_id,n)
{
    jQuery('#reminder_run_id').val(run_id);
    jQuery('#reminder_flow_id').val(flow_id);
    jQuery('#reminder_run_name').val(run_name);
    jQuery('#reminder_user_id').val(user_id);
    jQuery('#reminder_key_prcs_id').val(key_prcs_id);
    //jQuery('#reminder_content').val(sprintf(td_lang.general.workflow.msg_292, run_id, run_name));
    n==1 ? jQuery('#reminder_content').val("�߰��������ѣ�������������˹���") : jQuery('#reminder_content').val(sprintf(td_lang.general.workflow.msg_292, run_id, run_name));
    open_bootcss_modal('reminderModal', '500');
}
//�߰�
function reminders_do(){
    var run_id = jQuery('#reminder_run_id').val();
    var flow_id = jQuery('#reminder_flow_id').val();
    var user_id = jQuery('#reminder_user_id').val();
    var key_prcs_id = jQuery('#reminder_key_prcs_id').val();
    var content = jQuery('#reminder_content').val();
    var mobile_check_obj=jQuery('#back-mobile span.mobile-check');
    var mobile_check =mobile_check_obj.hasClass('mobile-bg-static')?'':'checked';

    if(jQuery.trim(content) == ''){
        alert(td_lang.general.workflow.msg_291);
        return;
    }
    jQuery.get("/module/approve_center/engine/flow_reminders.php",{"RUN_ID":run_id,"FLOW_ID":flow_id,"USER_ID":user_id,"KEY_PRCS_ID":key_prcs_id,"CONTENT":content,"mobile_check":mobile_check,},function(data)
    {
        jQuery('#reminderModal').modal('hide');
        alert(td_lang.general.workflow.msg_246);
    });
}
//�鿴ʵ������ͼ
function flow_view(RUN_ID,FLOW_ID,ARCHIVE_ID)
{
    if(ARCHIVE_ID == "" || typeof(ARCHIVE_ID)=="undefined")
    {
        var myleft=(screen.availWidth-800)/2;
        window.open("/general/approve_center/list/flow_view/?RUN_ID="+RUN_ID+"&FLOW_ID="+FLOW_ID,RUN_ID,"status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=400,left="+myleft+",top=100");
    }
    else
    {
        var myleft=(screen.availWidth-800)/2;
        window.open("/general/approve_center/list/flow_view/?RUN_ID="+RUN_ID+"&FLOW_ID="+FLOW_ID+"&ARCHIVE_ID="+ARCHIVE_ID,RUN_ID,"status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=400,left="+myleft+",top=100");
    }
}

function guide_flow(flow_id, flow_name)
{
    new_work(flow_name, '/general/approve_center/new/edit.php?FLOW_ID=' + flow_id);
}
function new_work(title, url)
{
    if(window.top.bTabStyle)
    {
        top.openURL('', title, url); 
    }
    else
    {
        window.location = url;
    }
}

//ת��
function transmission(RUN_ID,PRCS_KEY_ID,FLOW_ID,PRCS_ID,FLOW_PRCS,RUN_NAME,PRCS_NAME)
{
    jQuery('#work_run_form_submit').attr("disabled", false);
    open_bootcss_modal('myModalForm', "960", "5");
    loadWorkHandleNext(RUN_ID,PRCS_KEY_ID,FLOW_ID,PRCS_ID,FLOW_PRCS,RUN_NAME,PRCS_NAME);
}

function loadWorkHandleNext(run_id,prcs_key_id,flow_id,prcs_id,flow_prcs,RUN_NAME,PRCS_NAME){// ���̰���ʱת����һ����������
    jQuery('#myModalForm').find('#myModalForm_intrust').html(td_lang.inc.msg_30);
    jQuery('#myModalForm').find('#myModalForm_intrust').load('work_next.php?flow_id='+flow_id+'&prcs_key_id='+prcs_key_id+'&run_id='+run_id+'&prcs_id='+prcs_id+'&prcs_name='+encodeURIComponent(PRCS_NAME)+'&flow_prcs='+flow_prcs,function(msg){
        
//        if(RUN_NAME.length>50){
//            RUN_NAME = RUN_NAME.substr(0, 50)+"...";
//        }
        jQuery('#myModalFormLabel').html('NO. '+run_id+' '+RUN_NAME);
        jQuery('#PLUGIN_TURN_BEFORE_POSITION').html(jQuery.templates('#PLUGIN_TURN_BEFORE_POSITION_Tmpl').render({end_script:'</script>'}));
        jQuery('#PLUGIN_TURN_AFTER_POSITION').html(jQuery.templates('#PLUGIN_TURN_AFTER_POSITION_Tmpl').render({end_script:'</script>'}));
        return false;
        
    });
}

function bindCheckBtnEvent(){
	if (jQuery('#workPrcsData li.workflow-node').size() > 1) {
    jQuery('#all_check').show().on('refreshCheckStatus', function(){
        var status = true; //Ĭ��ȫѡactive
        jQuery("li.workflow-node[id^='next_prcs_'][condition!='false']").each(function(i, v){
            if(jQuery(v).attr('id') != 'next_prcs_0' && sync_deal == 1 && !this.className.match(/active/)){
                status = false; //�����ⲽ��δ��ѡ�� 
            }
        });
        jQuery(this)[ status ? 'addClass' : 'removeClass' ]('active');
    });
	}
}

function addPrcsJsonDatas(data){
    var Data = {"prcsData":data.prcsData};
    var template = jQuery.templates("#workPrcsTmpl");
    var htmlOutput = template.render(Data);
    jQuery("#workPrcsData").html(htmlOutput);
    jQuery(data.prcsData).each(function(i, v){
        var prcs_name_obj = jQuery("#next_prcs_"+v.prcs_num).find('a:first');
        var prcs_back = v.prcs_back;
        if(prcs_name_obj.attr('title') === '0'){
            prcs_name_obj.attr('title', '<strong>'+td_lang.general.workflow.msg_241+'</strong>');
        }else{
            prcs_name_obj.attr('title', prcs_name_obj.attr('title') + "��" + prcs_name_obj.text());
        }
        var prcs_obj_li = jQuery("#next_prcs_"+v.prcs_num);
        if (prcs_back != undefined) 
        {
            prcs_obj_li.attr("prcs_back", prcs_back); //�Ƿ������̷��ظ�����
        }
        if(typeof v.prcs_in_condition != 'undefined' && v.prcs_in_condition != ''){
            var condition_desc = '';
            var condition_desc_title = '';
            var prcs
            if(v.prcs_in_condition.substring(0, 5) == 'SETOK'){
                condition_desc = td_lang.general.workflow.msg_236;
                condition_desc_title = td_lang.general.workflow.msg_238;
                prcs_obj_li.attr('condition', 'true');
            }else{
                condition_desc = td_lang.general.workflow.msg_237;
                condition_desc_title = td_lang.general.workflow.msg_239;
                prcs_obj_li.attr('condition', 'false');
            }
            var a = jQuery('<a>'+condition_desc+'</a>').attr('title', '<strong>'+condition_desc_title+'��<br />'+v.prcs_in_condition.replace('SETOK', '')+'</strong>');
            prcs_obj_li.find('ol').append(a);
        }
    });
    jQuery(".workflow-node").find('a').tooltip();
    sync_deal = data.sync_deal; //����
    turn_priv = data.turn_priv;// ǿ��ת��
    gather_node = data.gather_node;// ǿ�ƺϲ�
    if(sync_deal == 2){
        jQuery('#prcs_title').append('&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">'+td_lang.general.workflow.msg_242+'</font>');
    }
    var sms_content = jQuery('#myModalForm').find('#SMS_CONTENT').val();
    jQuery(".workflow-node").click(function(){
        var prcs_type = jQuery("#PRCS_TYPE").val();
        var next_prcs_id =jQuery("#NEXT_PRCS_TYPE").val();
        var prcs_info_arr = jQuery(this).attr('id').split('_');
        if(prcs_info_arr.length != 3){
            return false;
        }
        var prcs_num = prcs_info_arr[2];
        var $this = jQuery(this);
        if($this.attr('condition') == 'false'){
            return false;
        }
        // ��ֹ����
        if(sync_deal == 0){
            if(prcs_type != 3)
            {
                $this.siblings().removeClass('active');
                $this.addClass('active');
            }else //����ת��
            {
                //$this.siblings().removeClass('active');
                if(flow_prcs == prcs_num)
                {
                    jQuery(".workflow-node[id='next_prcs_"+prcs_num+"']").addClass('active');
                    jQuery(".workflow-node[id='next_prcs_"+next_prcs_id+"']").removeClass('active');
                    return false;
                   
                }else
                {
                    jQuery(".workflow-node[id='next_prcs_"+prcs_num+"']").addClass('active');
                    jQuery(".workflow-node[id='next_prcs_"+flow_prcs+"']").removeClass('active');
                }
            }
            if(prcs_info_arr[2] === "0" && $this.attr("prcs_back") == undefined){
                jQuery("#work-next-prcs-block").html('');
                jQuery('div[data_type="next"]').hide();
                if(jQuery('#myModalForm').find('#SMS_CONTENT').length != 0)
                {
                    jQuery('#myModalForm').find('#SMS_CONTENT').val(sms_content.replace(td_lang.general.workflow.msg_259, td_lang.general.workflow.msg_260));
                }
                return false;
            }
            jQuery('#myModalForm').find('#SMS_CONTENT').val(sms_content);
            jQuery('div[data_type="next"]').show();
            var prcsBlockData = new Array();
            var prcs_uname = new Array();
            var prcs_uname_arr = $this.attr('prcs_uname').split(',');
            if(prcs_type == 3) //����ת��
            {
                if(flow_prcs == prcs_num) //���Բ���
                {
                    var prcs_uname_str = jQuery("#PRCS_USER"+flow_prcs).val();
                    var prcs_uid_arr = prcs_uname_str.split(',');
                }else
                {
                    var prcs_uid_arr = $this.attr('prcs_uid').split(',');
                }

            }else
            {
                var prcs_uid_arr = $this.attr('prcs_uid').split(',');
            }
            jQuery(prcs_uname_arr).each(function(i, v){
                if(v != ""){
                    prcs_uname.push({"user_id":prcs_uid_arr[i], "uname": v, 'gather_node_have_flag':$this.attr('gather_node_have_flag')});
                }
            });
            var prcsBlockData = [{
                'prcs_num':prcs_num, 
                'prcs_back': $this.attr('prcs_back'),
                'top_flag':$this.attr('top_flag'), 
                'prcs_op_uid':$this.attr('prcs_op_uid'), 
                'prcs_op_uname':$this.attr('prcs_op_uname'), 
                'prcs_uid':$this.attr('prcs_uid'), 
                'time_out':$this.attr('time_out'),
                'time_out_modify':$this.attr('time_out_modify'),
                'prcs_uname': prcs_uname,
                'gather_node_have_flag':$this.attr('gather_node_have_flag'), 
                'next_prcs_name':$this.attr('next_prcs_name'),
                'is_child_node':$this.attr('is_child_node')
            }];
            var template = jQuery.templates("#workNextPrcsUserTmpl");
            var htmlOutput = template.render(prcsBlockData);
            jQuery("#work-next-prcs-block").html(htmlOutput);
            jQuery('.workflow-node-title-text[title]').tooltip();
            if( $this.attr('user_lock') !== '0'){
                jQuery("#TOP_FLAG_SHOW"+prcs_num).attachmenu();
            }else{
                if($this.attr('auto_type')!==""&&jQuery(".user-tags").length!==0)
                {
                    jQuery('#chose_user' + prcs_num).click(function() {//yzx�������޸�Ĭ����Ա
                        alert(td_lang.general.workflow.msg_247);
                        return false;
                    });
                    jQuery('.close').hide();//yzx ������Աɾ��
                }
            	jQuery('#TOP_FLAG_SHOW'+prcs_num).click(function(){
            	    
            		alert(td_lang.general.workflow.msg_248);
            		return false;
            	});
            }
            
        }else if( sync_deal == 1){ // ��������
            bindCheckBtnEvent();
            if($this.hasClass('active')){
                $this.removeClass('active');
                jQuery("#work-next-prcs-block [prcs_id_next='"+prcs_num+"']").remove();
                jQuery('#all_check').removeClass('active');
            }else{
                $this.addClass('active');
                jQuery('#all_check').trigger('refreshCheckStatus');
                if(prcs_info_arr[2] === "0"  && typeof $this.attr("prcs_back") == 'undefined'){
                    jQuery("#work-next-prcs-block").html('');
                    jQuery(".workflow-node[id!='next_prcs_0']").removeClass('active');
                    jQuery('#all_check').removeClass('active');
                    if(jQuery('#myModalForm').find('#SMS_CONTENT').length != 0)
                    {
                        jQuery('#myModalForm').find('#SMS_CONTENT').val(sms_content.replace(td_lang.general.workflow.msg_259, td_lang.general.workflow.msg_260));
                    }
                    jQuery('div[data_type="next"]').hide();
                    return false;
                } else if (prcs_info_arr[2] === "0" && typeof $this.attr("prcs_back") != 'undefined') {  // ��ǰ������ǽ����ڵ㣬����û�з��ز���
                    jQuery("#work-next-prcs-block [prcs_id_next!='0']").remove();  // ����Աѡ���������
                    jQuery(".workflow-node[id!='next_prcs_0']").removeClass('active');  // ȡ�����в��ǽ����ڵ��ѡ��״̬
                    jQuery('#all_check').removeClass('active');  // ȡ��ȫѡѡ��״̬
                } else {
                    jQuery("#work-next-prcs-block [prcs_id_next='0']").remove();  // ɾ����Աѡ�����Ľ�������
                    jQuery(".workflow-node[id='next_prcs_0']").removeClass('active');  // ȡ�������ڵ��ѡ��״̬
                }
                jQuery('#myModalForm').find('#SMS_CONTENT').val(sms_content);
                prcs_info_arr[2] === "0" && typeof $this.attr("prcs_back") == 'undefined' && jQuery(".workflow-node[id='next_prcs_0']").removeClass('active');
                jQuery('div[data_type="next"]').show();
                var prcsBlockData = new Array();
                var prcs_uname = new Array();
                var prcs_uname_arr = $this.attr('prcs_uname').split(',');
                var prcs_uid_arr = $this.attr('prcs_uid').split(',');
                jQuery(prcs_uname_arr).each(function(i, v){
                    if(v != ""){
                        prcs_uname.push({"user_id":prcs_uid_arr[i], "uname": v, 'gather_node_have_flag':$this.attr('gather_node_have_flag')});
                    }
                });
                var prcsBlockData = [{
                    'prcs_num':prcs_num, 
                    'prcs_back': $this.attr('prcs_back'),
                    'top_flag':$this.attr('top_flag'), 
                    'prcs_op_uid':$this.attr('prcs_op_uid'), 
                    'prcs_op_uname':$this.attr('prcs_op_uname'), 
                    'prcs_uid':$this.attr('prcs_uid'),  
                    'time_out':$this.attr('time_out'),
                    'time_out_modify':$this.attr('time_out_modify'),
                    'prcs_uname': prcs_uname, 
                    'gather_node_have_flag':$this.attr('gather_node_have_flag'),
                    'next_prcs_name':$this.attr('next_prcs_name'),
                    'is_child_node':$this.attr('is_child_node')
                }];
                var template = jQuery.templates("#workNextPrcsUserTmpl");
                var htmlOutput = template.render(prcsBlockData);
                var current_index = jQuery("li[id^='next_prcs_'][class*='active']").index($this);
                var before_insert_id = '';
                jQuery("li[id^='next_prcs_'][class*='active']").each(function(i, v){
                    var unit_index = jQuery("li[id^='next_prcs_'][class*='active']").index(jQuery(v));
                    if(unit_index > current_index){
                        before_insert_id = jQuery(v).attr('id');
                        return false;
                    }
                })
                if(before_insert_id != ''){
                    var prcs_num_arr = before_insert_id.split('_');
                    if(prcs_num_arr.length == 3){
                        var search_num = prcs_num_arr[2];
                        jQuery('li[prcs_id_next="'+search_num+'"]').before(jQuery(htmlOutput));
                    }
                }else{
                    jQuery("#work-next-prcs-block").append(htmlOutput);
                }
                jQuery('.workflow-node-title-text[title]').tooltip();
                if( $this.attr('user_lock') !== '0'){
                    jQuery("#TOP_FLAG_SHOW"+prcs_num).attachmenu();
                }else{
                   if($this.attr('auto_type')!==""&&jQuery(".user-tags").length!==0)
                    {
                        jQuery('#chose_user' + prcs_num).click(function() {//yzx�������޸�Ĭ����Ա
                            alert(td_lang.general.workflow.msg_247);
                            return false;
                        });
                        jQuery('.close').hide();//yzx ������Աɾ��
                    }
                	jQuery('#TOP_FLAG_SHOW'+prcs_num).click(function(){
                	    
                		alert(td_lang.general.workflow.msg_248);
                		return false;
                	});
                }
            }
        }else if( sync_deal == 2){   //ǿ�Ʋ���
            if(prcs_info_arr[2] === "0"  && typeof $this.attr("prcs_back") == 'undefined'){//����
                 if($this.hasClass('active')){
                     
                 }else{
                     $this.addClass('active'); 
                     jQuery(".workflow-node[id!='next_prcs_0']").removeClass('active');
                     jQuery('div[data_type="next"]').hide();
                 }
                if(jQuery('#myModalForm').find('#SMS_CONTENT').length != 0)
                {
                    jQuery('#myModalForm').find('#SMS_CONTENT').val(sms_content.replace(td_lang.general.workflow.msg_259, td_lang.general.workflow.msg_260));
                }
                jQuery("#work-next-prcs-block").html('');
                return false;
            }else{//�ǽ���
                jQuery('#myModalForm').find('#SMS_CONTENT').val(sms_content);
                jQuery('#all_check').hide().addClass('disable');
                if($this.hasClass('active')){//ȡ��
                    
                }else{//ѡ��
                    if(prcs_info_arr[2] === "0" && typeof $this.attr("prcs_back") != 'undefined'){
                        jQuery(".workflow-node[id='next_prcs_0']").addClass('active');  // ѡ�н�������ڵ�
                        jQuery("#work-next-prcs-block [prcs_id_next!='0']").remove();  // ����Աѡ���������
                        jQuery(".workflow-node[id!='next_prcs_0']").removeClass('active');  // ȡ�����в��ǽ����ڵ��ѡ��״̬
                        var $li_obj = jQuery(".workflow-node[id='next_prcs_0']");
                    }else{
                        jQuery(".workflow-node[id!='next_prcs_0'][condition!='false']").addClass('active');  // ѡ��������Ϊfalse�Ĳ���ڵ�
                        jQuery("#work-next-prcs-block [prcs_id_next='0']").remove();  // ����Աѡ���������
                        jQuery(".workflow-node[id='next_prcs_0']").removeClass('active');  // ȡ��ѡ�еĽ�������ڵ�
                        var $li_obj = jQuery(".workflow-node[id!='next_prcs_0'][condition!='false']");
                    }
                    jQuery('div[data_type="next"]').show();
                    $li_obj.each(function(i, v){
                        $v = jQuery(v);
                         var prcsBlockData = new Array();
                            var prcs_uname = new Array();
                            var prcs_uname_arr = $v.attr('prcs_uname').split(',');
                            var prcs_uid_arr = $v.attr('prcs_uid').split(',');
                            jQuery(prcs_uname_arr).each(function(i, v){
                                if(v != ""){
                                    prcs_uname.push({"user_id":prcs_uid_arr[i], "uname": v, 'gather_node_have_flag':$v.attr('gather_node_have_flag')});
                                }
                            });
                            var prcs_num_arr = $v.attr('id').split('_');
                            var prcs_num = prcs_num_arr[2];
                            var prcsBlockData = [{
                                'prcs_num':prcs_num, 
                                'prcs_back': $this.attr('prcs_back'),
                                'top_flag':$v.attr('top_flag'), 
                                'prcs_op_uid':$v.attr('prcs_op_uid'), 
                                'prcs_op_uname':$v.attr('prcs_op_uname'), 
                                'prcs_uid':$v.attr('prcs_uid'),  
                                'time_out':$v.attr('time_out'),
                                'time_out_modify':$v.attr('time_out_modify'),
                                'prcs_uname': prcs_uname, 
                                'gather_node_have_flag':$v.attr('gather_node_have_flag'),
                                'next_prcs_name':$v.attr('next_prcs_name'),
                                'is_child_node':$v.attr('is_child_node')
                            }];
                            var template = jQuery.templates("#workNextPrcsUserTmpl");
                            var htmlOutput = template.render(prcsBlockData)
                            jQuery("#work-next-prcs-block").append(htmlOutput);
                            jQuery('.workflow-node-title-text[title]').tooltip();
                            if( $v.attr('user_lock') !== '0'){
                                jQuery("#TOP_FLAG_SHOW"+prcs_num).attachmenu();
                            }else{
                                if($v.attr('auto_type')!==""&&jQuery(".user-tags").length!==0)
                                {
                                    jQuery('#chose_user' + prcs_num).click(function() {//yzx�������޸�Ĭ����Ա
                                        alert(td_lang.general.workflow.msg_247);
                                        return false;
                                    });
                                    jQuery('.close').hide();//yzx ������Աɾ��
                                }
                            	jQuery('#TOP_FLAG_SHOW'+prcs_num).click(function(){
                            	    
                            		alert(td_lang.general.workflow.msg_248);
                            		return false;
                            	});
                            }
                    });
                }
            }
        }
        jQuery('.modal-body').trigger('scroll');
    });

    if(data.is_next_flag == 1)//�м������
    {
        var eachStep = data.prcsData;
        for(var i=0;i<eachStep.length;i++)
        {
            if(eachStep[i].notcheckprcs == 0)
            {
                jQuery('#next_prcs_' + eachStep[i].prcs_num).click();
                break;
            }
        }

    }else
    {
        var prcs_data_arr = data.prcsData;
        for(var i=0;i<prcs_data_arr.length;i++)
        {
            var prcs_num_str = prcs_data_arr[i].prcs_num;
            var prcs_num = parseInt(prcs_num_str);
            if ((sync_deal == 1 || sync_deal == 2) && prcs_num != 0) {
                if(sync_deal == 1 && prcs_data_arr[i].hasOwnProperty('allow_choose_prcs') && prcs_data_arr[i].allow_choose_prcs == 1)
                {
                    if( prcs_data_arr.length > 1)
                    {
                        jQuery("#all_check").css('display','inline');
                    }
                    continue;
                }
                jQuery('[id^="next_prcs_'+prcs_num+'"]').trigger('click');
            } else if (data.defaultNextPrcs && sync_deal == 0) {
                if(prcs_data_arr.length > 1)
                {
                    jQuery('#next_prcs_' + prcs_data_arr[prcs_data_arr.length-1-i].prcs_num).click();

                }else
                {
                    jQuery('#next_prcs_' + data.defaultNextPrcs).click();
                }
            }else if(prcs_num == 0 && prcs_data_arr.length == 1){
                jQuery('[id^="next_prcs_'+prcs_num+'"]').trigger('click');
            }
        }
    }

    if(sync_deal == 1 || sync_deal == 2)
    {
        var data_hidden_arr = data.prcsData;
        var numHidden = 0;
        var hiddenArr = new Array();
        jQuery("li.workflow-node[id^='next_prcs_'][condition='false']").each(function(i, v)
        {
            if(jQuery(v).attr('id') != 'next_prcs_0')
            {
                hiddenArr[i] = jQuery(v).attr('id');
                numHidden++;
                jQuery(v).css('display','none');
            }

        });
        if(numHidden > 0)
        {
            jQuery('#con_hidden').css('display', 'inline');
            jQuery('#con_hidden').css('cursor', 'pointer');
        }
        jQuery('#con_hidden').click(function()
        {

            var hiddenFlag  =  jQuery(".trian").attr('flag');
            if(hiddenFlag == 0)
            {
                jQuery(".trian").css({'transform':'rotate(360deg)'});
                jQuery(".trian").attr({'flag':'1'});
                for (var i = 0; i < hiddenArr.length; i++)
                {

                    jQuery("#"+hiddenArr[i]).animate({height: 'hide'});
                   // jQuery("#"+hiddenArr[i]).css('display','none');

                }
            }else if(hiddenFlag == 1)
            {
                jQuery(".trian").css({'transform':'rotate(180deg)'});
                jQuery(".trian").attr({'flag':'0'});
                for (var i = 0; i < hiddenArr.length; i++)
                {

                    jQuery("#" + hiddenArr[i]).animate({height: 'show'});
                    //jQuery("#"+hiddenArr[i]).css('display','block');


                }
            }

        });




    }

}

function set_top(flag, line_count){
    jQuery("#TOP_FLAG"+line_count).val(flag);
    if(flag==0)
    {
        document.getElementById('TOP_FLAG_SHOW'+line_count).innerHTML = td_lang.general.workflow.msg_251; //"�����ˣ�"
    }     
    else if(flag=="1")
    {
        document.getElementById('TOP_FLAG_SHOW'+line_count).innerHTML= td_lang.general.workflow.msg_252; //"�Ƚ��������죺";
    }
    else if(flag=="2")
    {
        document.getElementById('TOP_FLAG_SHOW'+line_count).innerHTML= td_lang.general.workflow.msg_253; //"�������˻�ǩ��";
    }
	else if(flag=="3")
	{
		document.getElementById('TOP_FLAG_SHOW'+line_count).innerHTML= td_lang.general.workflow.msg_316; //"�ɾ����˷��������̣�";
	}
      
    if(flag!="0")
    {
       jQuery("#PRCS_OP_USER"+line_count).val("");
       jQuery("#host_op_block_div"+line_count).find(".user-tags").remove();
    }
   
   document.getElementById("TOP_FLAG_SHOW"+line_count+'_menu').style.display = 'none';
}

function setEditableField(bid) {

    var prcsid = bid.replace("freePrcsEditableField", "");
    var urlStr = "set_item.php?FLOW_ID=" + window.flow_id + "&RUN_ID=" + window.run_id + "&PRCS_ID=" + prcsid + "&PRCS_ID_NEXT=" + window.prcs_next_id + "&LINE_COUNT=" + window.prcs_next;
    loc_x = 220;
    loc_y = 200;
    if (is_ie) {
        loc_x = document.body.scrollLeft + event.clientX - event.offsetX - 220;
        loc_y = document.body.scrollTop + event.clientY - event.offsetY;
    }
    LoadDialogWindow(urlStr, self, loc_x, loc_y, 973, 460);
}
function setEditFormField(bid) 
{    
    var urlStr = "set_form.php?FLOW_ID=" + window.flow_id + "&RUN_ID=" + window.run_id + "&PRCS_ID=" + bid;
    loc_x = 220;
    loc_y = 200;
    if (is_ie) {
        loc_x = document.body.scrollLeft + event.clientX - event.offsetX - 220;
        loc_y = document.body.scrollTop + event.clientY - event.offsetY;
    }
    LoadDialogWindow(urlStr, self, loc_x, loc_y, 973, 460);
}
(function($) {
    //��������ѡ�� by jx
    function FreePrcsSelector() {
        this.init.apply(this, arguments);
    }
    var opts = {
        id: 'workFreePrcs', //����
        index: 3, //Ĭ����С�ӵ�������ʼ�����������
        _index: 3,
        data: null //�ѱ����������������
    };
    FreePrcsSelector.prototype = {
        constructor: FreePrcsSelector,
        init: function(opts) {
            this.$el = $('#' + opts.id);
            this.index = parseInt(opts.index || 3, 10);
            this._index = parseInt(opts._index || 3, 10);
            this.data = opts.data || {};
            this.readonly = opts.readonly || false;
            this.free_preset = opts.free_preset; //�����Ƿ�����Ԥ�貽�� --YZX

            this.data.length && this.load(this.data);
            if (this.free_preset != 0) {
                !this.readonly && this.renderCtrl();
            }
            this.bindEvent();
        },
        bindEvent: function() {
            var self = this;
            this.$el.delegate('[data-cmd="del"]', 'click', function() {
                var id = $(this).parents('.work-free-prcs-block').first().attr('data-id');
                self.remove(id);
            });
            this.$el.delegate('[data-cmd="setEditableField"]', 'click', function() {
                var id = $(this).parents('.work-free-prcs-block').first().attr('data-id');
                self.setField(id);
            });
        },
        render: function(data) {
            var template = $.templates("#workFreePrcsBlockTmpl");
            return template.render(data);
        },
        renderCtrl: function() {
            var $btns = $('<div style="text-align:right;"></div>').insertAfter(this.$el).css('margin-bottom', 10);
            this.$add = $('<button class="btn btn-small btn-info style="display:inline-block"">�������ɲ���</button>').appendTo($btns);
            this.$clear = $('<button class="btn btn-small btn-danger pull-right">�������ɲ���</button>').appendTo($btns).css('margin-left', 10);
            this.$add.click($.proxy(this.add, this));
            this.$clear.click($.proxy(this.clear, this));
        },
        add: function() {
            var $el = this.$el,
                data = {id: this.guid(),index: this.index++},
                $html = $(this.render(data));
            $el.append($html);

            var prcs_data = {
                prcs_num: data.id,
                next_prcs_name: 'Ԥ�貽��',
                top_flag: 0,
                prcs_type: 'free'
            };

            this.renderItem(prcs_data);
        },
        remove: function(id) {
            $('#work-free-prcs-block-' + id).remove();
            this.index--;
            this.resetIndex();
        },
        load: function(data) {
            if (!data) {
                return;
            }
            var self = this;
            $.each(data, function(i, n) {
                self.renderItem(n.prcsData[0]);
            });
        },
        renderItem: function(data) {
            if (!data) {
                return;
            }
            var prcs_data = data;
            var $html = $('#work-free-prcs-block-' + data.prcs_num);
            var nodeHtml = $.templates("#workPrcsTmpl").render({prcsData: prcs_data});
            var resultHtml = $.templates("#workNextPrcsUserTmpl").render(prcs_data);
            //console.log("����", $html);
            //console.log("����", data);
            $html.find('.workflow-procs-nodes').html(nodeHtml);
            $html.find('.workflow-procs-nodes-result').html(resultHtml);
            $html.find('.workflow-node').addClass('active');
            jQuery("#TOP_FLAG_SHOW" + prcs_data.prcs_num).attachmenu();
        },
        clear: function() {
            this.index = this._index;
            this.$el.find('.default-free').remove();
        },
        guid: function() {
            var i = 8, store = [];
            while (i) {
                store.push(Math.floor(Math.random() * 16.0).toString(16));
                i--;
            }
            return store.join('');
        },
        resetIndex: function() {
            var index = parseInt(this._index, 10);
            //console.log(index);
            this.$el.find('.work-free-prcs-block').each(function(i, n) {
                n.setAttribute('data-index', index + i);
                $(n).find('.work-prcs-title-text b').text(index + i);
            });
        },
        setField: function(i) {
            setEditableField('freePrcsEditableField' + i);
        },
        /*
         * ��ȡ��������ѡ���� return [object | false]
         */
        serialize: function() {
            var check_flag = true, json = [];
            /*
             *    .work-free-prcs-block-next    ��һ���� php����
             *    .work-free-prcs-block         Ԥ�貽�� js����
             */
            this.$el.find('.work-free-prcs-block-next,.work-free-prcs-block').each(function(i, n) {
                var index = n.getAttribute('data-index');
                var flow_prcs = n.getAttribute('data-id');
                if (jQuery('#PRCS_USER' + flow_prcs).val() == '') {
                    alert(td_lang.general.workflow.msg_245);
                    check_flag = false;
                    return false;
                }
                if (jQuery('#TOP_FLAG' + flow_prcs).val() === '0' && jQuery('#PRCS_OP_USER' + flow_prcs).val() == '') {
                    alert(td_lang.general.workflow.msg_244);
                    check_flag = false;
                    return false;
                }
                json.push({
                    index: index,
                    top_flag: jQuery('#TOP_FLAG' + flow_prcs).val(),
                    prcs_op_user: jQuery('#PRCS_OP_USER' + flow_prcs).val(),
                    prcs_user: jQuery('#PRCS_USER' + flow_prcs).val(),
                    editable_field: jQuery('#freePrcsEditableField' + flow_prcs).val(),
					editform_field: jQuery('#freePrcsEditFormField' + flow_prcs).val()
                });
            });
            //  console.log(jQuery('#freePrcsEditableField8').val());
            return check_flag && json;
        }
    };

    window.FreePrcsSelector = FreePrcsSelector;
})(jQuery);
define('TListViewCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var TListView = require('TListView');
    var TListViewCtrl = Base.extend({
        initialize: function(config) {
            TListViewCtrl.superclass.initialize.call(this, config);
            this.$wrapper = $("."+config.parentid).find('.group-fields');
            this.$config = config;
            this.$config.unCalc = true;//不参与计算控件的标识
            this.title = config.title;
            this.id = config.id;
            this.d_id = config.d_id;
            this.required = config.required;
            this.desc = config.desc = '请填写内容！';
            this.initField(config);
            this.$editobj = $('#listviewediticon'+this.id);
            this.$listcontent = $('#list-wrapper'+this.id).find('.list-content');
            this.newlistview(this.d_id,this.id);
        },
        initField: function(config){
        	var self = this;
        	var html = self.parseHtml(config);
        	this.$wrapper.append(html);
        },
		parseHtml: function(d){
			d.d_id = d.id.substr(5,d.id.length);
			var tplHTML = '<% if(secret){ %>'+
						  '<% } else{ %>'+
							'<div class="read_detail '+
							'<% if(hidden!=undefined && hidden==true){ %>'+
								' hidden '+
							'<% } %>'+
							'<% if(writable){ %>'+
								'WriteDiv'+
							'<% } %>'+
							' tag-listview">'+
							'<% if(required){ %>'+
								'<span class="isrequired">*</span>'+
							'<% } %>'+
							'<em><%=title%>：</em>'+
						  	'<input type="hidden" name="<%=id%>" value="<%=value%>" class="LIST_VIEW" />'+

						  			'<div class="field">'+
						  			    '<a class="report-list-icon-create" href="javascript:void(0);"></a>'+
						  			'</div>'+
						  			'<div id="list-wrapper<%=id%>" class="list-wrapper" data-title="<%=title%>">'+
						  			    '<div class="list-content"></div>'+
						  			    '<div class="list-sum"></div>'+
						  			'</div>'+

						  	'</div>'+
						   '<% } %>'+
						   '<% if(required){ %>'+
							'<div class="div_alert div_alert_hidden" id="div_alert_<%=id%>"><%=desc%></div>'+
						   '<% } %>';
			return $.tpl(tplHTML,d);
		},
		newlistview: function(listitem, listname){
			var self = this;
    		$.ajax({
	            url: 'getListDataJson.php',
	            type: 'POST',
	            data: {'RUN_ID': q_run_id,'FLOW_ID': q_flow_id,'PRCS_ID': q_prcs_id,'FLOW_PRCS': q_flow_prcs,'PRCS_KEY_ID':q_prcs_key_id,'LIST_NAME':self.id,'actionType':q_action_type},
	            cache: false,
	            async: false,
	            success: function(d){
	                d = JSON.parse(d);
	                var ListViewFieldManager = TListView.TListViewManager;

	                self.list = new ListViewFieldManager({
	                    wrapper: self.$listcontent,
	                    fieldmanager: self.$config.fieldmanager
	                }, d);
	                saveFlag = 0;
	            },
	            error: function(){
	            }
	        });

            //测试数据
           // var d = {
				// "main": [ ], 
				// "detail": [
					// {
						// "label": "列表控件测试", 
						// "lower_name": "data_m1", 
						// "required": true, 
						// "secret": false, 
						// "writable": true, 
						// "hidden": null, 
						// "url": "listview('1','DATA_1')", 
						// "add_op": 1, 
						// "edit_op": 1, 
						// "delete_op": 1, 
						// "op": "1", 
						// "id": "data_m1", 
						// "title": "列表控件测试", 
						// "schema": [
							// {
								// "colname": "单行", 
								// "colvalue": "", 
								// "id": "list_data_m1_col_1", 
								// "displaystyle": "", 
								// "fieldtype": "RTextField", 
								// "editable": 1, 
								// "secret": false, 
								// "required": true,
								// "code_select_value": ""
							// }, 
							// {
								// "colname": "多行", 
								// "colvalue": "", 
								// "id": "list_data_m1_col_2", 
								// "displaystyle": "", 
								// "fieldtype": "RTextAreaField", 
								// "editable": 1, 
								// "secret": false, 
								// "required": true,
								// "code_select_value": ""
							// }, 
							// {
								// "colname": "下拉", 
								// "colvalue": "b", 
								// "id": "list_data_m1_col_3", 
								// "displaystyle": "", 
								// "fieldtype": "RSelectField", 
								// "editable": 1, 
								// "secret": false, 
								// "required": true,
								// "code_select_value": "a"
							// }, 
							// {
								// "colname": "单选", 
								// "colvalue": "b", 
								// "id": "list_data_m1_col_4", 
								// "displaystyle": "", 
								// "fieldtype": "RRadioField", 
								// "editable": 1, 
								// "secret": false,
								// "required": true,								
								// "code_select_value": "a"
							// }, 
							// {
								// "colname": "复选", 
								// "colvalue": "b", 
								// "id": "list_data_m1_col_5", 
								// "displaystyle": "", 
								// "fieldtype": "RCheckBoxField", 
								// "editable": 1, 
								// "secret": false,
								// "required": true,								
								// "code_select_value": "a"
							// }, 
							// {
								// "colname": "日期", 
								// "colvalue": "", 
								// "id": "list_data_m1_col_6", 
								// "displaystyle": "yyyy-MM-dd", 
								// "fieldtype": "RDateField", 
								// "editable": 1, 
								// "secret": false, 
								// "required": true,
								// "code_select_value": ""
							// }, 
							// {
								// "colname": "日期时间", 
								// "colvalue": "", 
								// "id": "list_data_m1_col_7", 
								// "displaystyle": "yyyy-MM-dd HH:mm:ss", 
								// "fieldtype": "RDateField", 
								// "editable": 1, 
								// "secret": false,
								// "required": true,								
								// "code_select_value": ""
							// }
						// ], 
						// "item": [
							// {
								// "id": 1, 
								// "index": 1, 
								// "order": 1, 
								// "title": "列表控件测试 - 编辑", 
								// "item": [
									// {
										// "colname": "单行", 
										// "colvalue": "", 
										// "id": "list_data_m1_col_1", 
										// "displaystyle": "", 
										// "fieldtype": "RTextField", 
										// "editable": 1, 
										// "secret": false, 
										// "required": true,
										// "code_select_value": "", 
										// "src": "", 
										// "filename": ""
									// }, 
									// {
										// "colname": "多行", 
										// "colvalue": "", 
										// "id": "list_data_m1_col_2", 
										// "displaystyle": "", 
										// "fieldtype": "RTextAreaField", 
										// "editable": 1, 
										// "secret": false,
										// "required": true,										
										// "code_select_value": "", 
										// "src": "", 
										// "filename": ""
									// }, 
									// {
										// "colname": "下拉", 
										// "colvalue": "b", 
										// "id": "list_data_m1_col_3", 
										// "displaystyle": "", 
										// "fieldtype": "RSelectField", 
										// "editable": 1, 
										// "secret": false, 
										// "required": true,	
										// "code_select_value": "a", 
										// "src": "", 
										// "filename": ""
									// }, 
									// {
										// "colname": "单选", 
										// "colvalue": "b", 
										// "id": "list_data_m1_col_4", 
										// "displaystyle": "", 
										// "fieldtype": "RRadioField", 
										// "editable": 1, 
										// "secret": false, 
										// "required": true,	
										// "code_select_value": "a", 
										// "src": "", 
										// "filename": ""
									// }, 
									// {
										// "colname": "复选", 
										// "colvalue": "b", 
										// "id": "list_data_m1_col_5", 
										// "displaystyle": "", 
										// "fieldtype": "RCheckBoxField", 
										// "editable": 1, 
										// "secret": false, 
										// "required": true,	
										// "code_select_value": "a", 
										// "src": "", 
										// "filename": ""
									// }, 
									// {
										// "colname": "日期", 
										// "colvalue": "", 
										// "id": "list_data_m1_col_6", 
										// "displaystyle": "yyyy-MM-dd", 
										// "fieldtype": "RDateField", 
										// "editable": 1, 
										// "secret": false, 
										// "required": true,	
										// "code_select_value": "", 
										// "src": "", 
										// "filename": ""
									// }, 
									// {
										// "colname": "日期时间", 
										// "colvalue": "", 
										// "id": "list_data_m1_col_7", 
										// "displaystyle": "yyyy-MM-dd HH:mm:ss", 
										// "fieldtype": "RDateField", 
										// "editable": 1, 
										// "secret": false, 
										// "required": true,	
										// "code_select_value": "", 
										// "src": "", 
										// "filename": ""
									// }
								// ]
							// }
						// ]
					// }
				// ], 
				// "basic": [ ]
			// };
           // var ListViewFieldManager = TListView.TListViewManager;
           // self.list = new ListViewFieldManager({
               // wrapper: self.$listcontent,
               // fieldmanager: self.$config.fieldmanager
           // }, d);
           // saveFlag = 0;

		},
		bindCalc: function(){
		},
		getValue: function(){
		   return (this.list && this.list.serializeArray());
		},
		getData: function(){
            var ret = {};
            ret.name = this.$config.id;
            ret.value = this.getValue();
            ret.sum = this.list.serializeSumArray();
            return ret;
        },
        onSubmit: function() {
        	var self = this;
			var d_id = self.$config.d_id;
            var obj_val = self.getValue();
			var temp_name = 'DATA_data_m'+d_id;
			var temp_val = obj_val[temp_name];
			var temp_arr = temp_val.split('\n');
			var check_flag = true;
			for(var i=0;i<temp_arr.length-1;i++)
			{
				if(temp_arr[i] == '')
					continue;
				var temp_field_arr = temp_arr[i].split('`');
				for(var j=0;j<temp_field_arr.length-1;j++)
				{
					if(temp_field_arr[j] != '')
					{
						check_flag = false;
						break;
					}
				}
				if(check_flag == false)
					break;
			}
            var required_val = this.required;
            var low_id = this.low_id;
            if(check_flag == true && required_val)
            {
                this.validation(this.desc);
                setTimeout(function(){
                    $("#div_alert_"+self.$config.id+"").removeClass("div_alert_show");
                }, 5000);
                return false;
            }
        },
        validation: function(s) {
            $("#div_alert_"+this.id+"").addClass("div_alert_show");
        }
    });
    exports.TListViewCtrl = window.TListViewCtrl = TListViewCtrl
});

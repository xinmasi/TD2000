define('TDataSelectionCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var TDataSelectionCtrl = Base.extend({
        initialize: function(config) {
            TTextareaCtrl.superclass.initialize.call(this, config);
            this.$wrapper = $("."+config.parentid).find('.group-fields');
            this.initField(config);
        },
		initField: function(config) {
            var self = this;
            var html = self.parseHtml(config);
            this.$wrapper.append(html);
            //this.$obj = config.rich == 1 ? $('span[name="' + config.id + '"]').eq(0) : $('textarea[name="' + config.id + '"]').eq(0);
            this.$config = config;
			this.$template = $('#dataselect-modal-tmpl');
            this.id = config.id;
            this.title = config.title;
            this.low_id = config.id.toLowerCase();
            this.required = config.required;
            this.desc = config.desc;
			this.bindEvent();
        },
		parseHtml: function(d){
			var tplHTML = '<% if(secret){ %>'+
						  '<% } else{ %>'+
						  	 '<div class="read_detail '+
						  	 '<% if(writable){ %>'+
						  	 'WriteDiv'+
						  	 '<% } %>'+
						  	 ' tag-dataselection">'+
						  	 '<% if(required){ %>'+
						  	 '<span class="isrequired">*</span>'+
						  	 '<% } %>'+
						  	 '<em><%=title%></em><div class="field">'+
						  	 '<% if(writable){ %>'+
						  	 '<button class="ui-btn" type="button" data-id="<%=id%>">Ñ¡Ôñ</button>'+
						  	 '<% } else{ %>'+
						  	 '<button class="ui-btn" type="button" disabled data-id="<%=id%>">Ñ¡Ôñ</button>'+
						  	 '<% } %>'+
						  	 '</div></div>'+
						  '<% } %>'+
						  '<% if(required){ %>'+
						  	 '<div class="div_alert div_alert_hidden" id="div_alert_<%=lower_name%>"><%=desc%></div>'+
						  '<% } %>';
			return $.tpl(tplHTML,d);
		},
		bindEvent: function(){
			var self = this
			/*
				data_table:"OFFICE_PRODUCTS",
				data_type:"0",
				data_field:"PRO_NAME`PRO_PRICE`",
				data_fld_name:"??????`??`", 
				data_control:"??`??`",
				data_query:"0`0`",
				uid:"4004F17A2A013A1407F3DD5A645FAD3C"
			*/
			var params = {
				data_table: this.$config.data_table,
				data_type:this.$config.data_type,
				data_field:this.$config.data_field,
				data_fld_name:this.$config.data_fld_name,
				data_control:this.$config.data_control,
				data_query:this.$config.data_query,
				uid:this.$config.uid
			}

			$("[data-id='"+ this.id +"']").click(function(){
				//params???
				// var tableconfig = {
					// columns: ["??????","??"],
					// datasource: [
						// ["???","1190.00"],
						// ["??A84","4500.00"]
					// ]
				// }
				$("#dataselection_container").html(self.setModalHtml(tableconfig))
				var dia=$(".ui-dialog").dialog("show");
				dia.on("dialog:action",function(e){
					console.log(e.index)
				});
			})
			
			$('body').delegate('.data_select_add', 'click', function(){
				var $this = $(this)
				var index = $this.attr('data-index')
				console.log(index)
				return false
			})
		},
		setModalHtml: function(d){
			var tplHTML = '<table>'+
								'<tr>'+
								'<% for(var m=0;m<columns.length;m++){ %>'+
									'<td><%=columns[m]%></td>'+
							  	'<% } %>'+
								'<td><%=data_select_handle%></td>'+
								'</tr>'+
								'<% for(var i=0;i<datasource.length;i++){ %>'+
									'<tr>'+
										'<% for(var j=0;j<datasource[i].length;j++){ %>'+
											'<td><%=datasource[i][j]%></td>'+
										'<% } %>'+
										'<td class="data_select_add" data-index="<%=i%>"><%=data_select_add%></td>'+
		                        	'</tr>'+
							  	'<% } %>'+
						  '</table>';
			return $.tpl(tplHTML,d);
		}
    });
    exports.TDataSelectionCtrl = window.TDataSelectionCtrl = TDataSelectionCtrl;
});

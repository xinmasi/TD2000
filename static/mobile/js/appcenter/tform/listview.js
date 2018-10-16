define('ListView', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var Fields = require('ReportFieldLoder');
    //var Fields = require('FieldLoader');
    var getFieldClass = function(c) {
        return Fields[c];
    };
    var ListViewManager = Base.extend({
        attrs: {
            wrapper: ''
        },
        initialize: function(config, store) {
            ListViewManager.superclass.initialize.call(this, config);
            this.$wrapper = $(this.get('wrapper'));
            //this.$sum = this.$wrapper.parent('.list-wrapper').find('.list-sum');
            this.$wrapper.html("");
            //this.$sum.html("");

            this.fieldManager = config.fieldManager;
            this.register = store || [];
            this.instances = {};
            this.lists = [];
            this.initMainBlocks(store.main);
            this.initDetailBlocks(store.detail);
        },

		// trigger后重新渲染
		triggerRerender: function(store) {
			var self = this;
			var handleIndex = window.listHandleIndex;

			$.each(store.value,function(i,line){ //line是返回列表控件的每行数据
				if(handleIndex == line.index && line.flag == "new"){ //新建时触发triggerTri
					var cfg = self.lists[0].instances[handleIndex].cfg;
					for ( id in line.data){
						$.each(cfg.data,function(i,field){
							if(field.field_id == id){
								field.value = line.data[id].value;
								if(line.data[id].options != undefined){
                                    field.options = line.data[id].options;
                                }else{
								    if(field.options != undefined){
                                        //field.options = undefined;
                                    }
                                }
							}
						})
					}
					self.rerenderMailBlocks(cfg.data);
				}else if( handleIndex == line.index ){ //编辑时触发triggerTri
					var guid = self.register.detail[0].guid
					var config = self.fieldManager.fieldsMap[guid].value;
					var schema = self.fieldManager.fieldsMap[guid].schema;
					$.each(config,function(i,cfg){
						if( handleIndex == cfg.index ){
							for ( id in line.data){
								$.each(cfg.data,function(j,field){
									$.each(schema,function(k,attr){
										if(field.field_id == attr.field_id && field.field_id == id){
											field = $.extend(true,field, attr);
											field.value = line.data[id].value;
										}
									})
								})
							}
							self.rerenderMailBlocks(cfg.data);
							return false;
						}
					})
				}
			})
        },
		rerenderDetailBlocks: function(cfg){
			console.log("rerenderDetailBlocks:"+cfg)
		},
		rerenderMailBlocks: function(cfg){
			$.each(cfg,function(i,field){
				var field_id = field.field_id;
				var pure_id = field_id.substring(1,field_id.length-1);
				if(field.writable == false){
					$('#'+ pure_id).text(field.value);
					$('.field [name="'+field_id+'"]').val(field.value);
				}else{
                    if(field.options != undefined){
                        if($('.field [name="'+field_id+'"]').length > 0 && $('.field [name="'+field_id+'"]').prop("nodeName").toUpperCase() == "SELECT") {
                            var s_option = "";
                            $.each(field.options, function (i, option) {
                                s_option += "<option value='" + option.code + "'>" + option.name + "</option>";
                            });
                            $('.field [name="' + field_id + '"]').html(s_option);
                        }else if($('.field [id="'+field_id+'_check"]').length > 0){
                            var default_items;
                            if (typeof(field.value) == 'object') {
                                default_items = field.value.join(',');
                            } else {
                                default_items = field.value;
                            }
                            var options = field.options,
                                opData = options ? options : [],

                                defaultData = default_items ? default_items.split(',') : [],
                                html = [];
                            $.each(opData, function(k, v) {
                                if (this == '') {
                                    return;
                                }
                                var checked = $.inArray(v.code, defaultData) == -1 ? '' : 'checked';
                                html.push('<label for="' + field_id + '_' + v.code + '" class="ui-checkbox"><input value="' + this.code + '"  type="checkbox" name="' + field_id + '" id="' + field_id + '_' + v.code + '" ' + checked + ' /><span>' + this.name + '</span></label>');
                            });
                            $('.field [id="'+field_id+'_check"]').html(html.join(''));
                        }else if($('.field [id="'+field_id+'_radio"]').length > 0){
                            var options = field.options,
                                opData = options ? options : [],
                                html = [];
                            $.each(opData, function(k, v) {
                                if (this == '') {
                                    return
                                }
                                var checked = this.code == field.value ? ' checked ' : '';
                                html.push('<label for="' + field_id + '_' + v.code + '" class="ui-radio"><input type="radio" name="' + field_id + '" id="' + field_id + '_' + v.code + '" ' + checked + ' /><span>' + this.name + '</span></label>');
                            });
                            $('.field [id="'+field_id+'_radio"]').html(html.join(''));
                        }
                    }
                    $('.field [name="'+field_id+'"]').val(field.value);
				}
			})
		},
        getColSum: function(col) { //返回指定列的合计值
            var res = this.lists[0].calcColSum(col);
            //console.log(res+"res");
            return res;
        },
        regist: function(cfg) {
            this.register.push(cfg);
        },
        getFieldClassName: function(type) {
            type = type.replace(/-/g, "")
            type = type.firstUpperCase()
                //return ''+type+'Ctrl'
            return 'R' + type + 'Field'
        },
        factory: function(cfg, $wrapper) {
            var self = this;
            var field_type = self.getFieldClassName(cfg.type);
            var klass = getFieldClass(field_type),
                field;
            if (klass) {
                cfg.listManager = this; //对每一条记录过滤每一种类型的控件过程中传入这一条记录对象
                cfg.container = $wrapper;
				cfg.template = $('#f-' + cfg.type + '-tmpl').html();
                cfg.pure_id = cfg.field_id.substring(1,cfg.field_id.length-1)
				cfg.tip = '';
				cfg.color = '';
                field = new klass(cfg, $wrapper, self.fieldManager);
            }
            return field;
        },
        initMainBlocks: function(blocks) {
            var self = this;
            if (!(blocks)) {
                return;
            }
            //var store = self.decorateStoreData(blocks[0]);
            $.each(blocks, function(i, block) {
                var $block = self.parseBlock(block);
                self.initFields(block.data, $block);
            });
            // if (self.register.flag == 0) {
            //     self.get('wrapper').trigger('calc');
            // }
            //$('.list-wrapper .read_detail').last().addClass("last");
        },
        initDetailBlocks: function(detail) {
            if (!(detail && detail[0].value)) {
                return;
            }
            var store = this.decorateStoreData(detail[0]);
            this.initListManager(store, this.$wrapper);
        },
        decorateStoreData: function(data) {
            if (!data) {
                return;
            }
            var store = data;
            var schema = {};         //列数据
            var items = [];          //行数据
            $.each(store.subFields, function(i, fields) {
                store.subFields[i].value = '';
                schema[fields.field_id] = fields;
            });
            store.schema = store.subFields;
            $.each(store.value, function(i, lines) {
                var rows = [];
                $.each(lines.data, function(i, data) {
                    //item[ data.field_id ].value = data.value;
                    rows.push(
                        $.extend(true, {},
                            schema[data.field_id], { value: data.value })
                    );
                });
                store.value[i].data = rows;
            });
            return store;
        },
        initListManager: function(cfg, $wrapper) {

            var self = this;
            if (!cfg) {
                return;
            }
            this.lists.push(
                new ReportListFieldManager({
                    wrapper: $wrapper,
                    fieldManager: self.fieldManager
                }, cfg)
            );
        },
        initFields: function(fields, $block) {
            var self = this,
                register = this.register,
                instances = this.instances;
            $.each(fields, function(i, cfg) {
                try {
                    var instance = self.factory(this, $block);
                    if (instance && instance.get('field_id')) {
                        instances[instance.get('field_id')] = instance;
                    }
                } catch (e) {
                    console.log("发生了错误！！！name: " + e.name + "message: " + e.message);
                }
            });
        },
        bindEvent: function() {
            var self = this,
                runId = this.get('runId'),
                flowId = this.get('flowId');
        },
        parseBlock: function(d) {
            var tmpl = '<div class="reportBlock report-block-header tform clearfix">\
							<h3><%=name%></h3>\
						</div>';
            var $block = $($.parseTpl(tmpl, d));
            this.$wrapper.append($block);

            return $block;
        },
        createId: (function(i) {
            return function() {
                return i++;
            }
        })(0),
        save: function() {
            return this.$el.serializeArray();
        },
        appendFieldElement: function(el) {
            this.$wrapper.append(el);
        },
        destroy: function() {
            this.$el.remove();
        },
        updateFields: function() {

        },
        updateDataFromFields: function() {
            $.each(this.instances, function(i, field) {
                field.updateDataFromField && field.updateDataFromField();
            });
        },
        serializeArray: function() {
            var self = this,
                ret = [],
                list = {};
            $.each(this.instances, function(i, field) {
                field.getData && ret.push(field.getData());
            });
            $.each(this.lists || [], function(i, l) {
                list['DATA_' + l.getId()] = (l.serializeArray());
            });
            return list;
        },
        //        serializeArray: function(){
        //            var self = this, ret = [], list = [];
        //            //lists => [list];
        //            $.each(this.lists || [], function(i, l){
        //                list = l.serializeArray();
        //                //list['DATA_'+l.getId()] = (l.serializeArray());
        //            });
        //            return list;
        //        },
        serializeSumArray: function() {
            var ret = null;
            $.each(this.lists || [], function(i, l) {
                if (this.sumobj) {
                    ret = this.sumobj.getData();
                }
            });
            return ret;
        }
    });
    var ReportListFieldManager = ListViewManager.extend({
        attrs: {
            wrapper: '',
            sumArea: '',
            savebtn: ''
        },
        initialize: function(config, store) {
            ListViewManager.superclass.initialize.call(this, config);
            this.$wrapper = $(this.get('wrapper'));
            this.$sumArea = this.$wrapper.parent('.list-wrapper').find('.list-sum');
            this.$save = $(this.get('savebtn'));
            this.register = store || [];
            this.instances = {};
            this.$el = this.parseBlock(store);
            this.initFields(store.value, this.$el);
            //(store.op == 'do') && this.buildCreateHelper();
            this.fieldManager = config.fieldManager;
            //console.log("2",this.fieldmanager);
            this.bindEvent();
            this.sumobj = null;
            //store.sumArr && this.createSum();
            store.sumArr && this.createSum();
            store.sumArr && this.$el.trigger('_calcSum');
        },
        calcColSum: function(col) {
            var self = this;
            var sum = 0;
            $.each(self.instances, function(k, v) {
                if (v.cfg != undefined) {
                    var cur_index = parseInt(col) - 1;
                    var instance = v.cfg.item[cur_index];
                    if (instance.value == "") { instance.value = 0; }
                    if (self.validate_num(instance.value)) {
                        sum += parseFloat(instance.value);
                    }
                }
            });
            return sum;
        },
        getId: function() {
            return this.register.id || this.register.field_id;
        },
        bindEvent: function() {
            var self = this;
            this.$wrapper.parents('.tag-listview').on('tap', '.report-list-icon-create', function() {

                self.createItem();
            });
            this.$el.bind('_calcSum', function() {
                self.calcSum();
                self.fieldManager.trigger('calc');
            });
            if (this.register.delete_op == true) {
                var _id = self.$wrapper.attr('id');
                Swiped.init({
                    query: ('#' + _id + ' .read_detail'),
                    list: true,
                    right: 100
                });
            };
        },
        calcSum: function() { //合计计算
            var self = this;
            $.each(self.register.sumArr, function(i, obj) {
                if (obj.sumflag == true) {
                    var reminder = 4;
                    var rule = obj.options;
                    if (rule.indexOf('|') != -1) {
                        reminder = rule.substr(rule.indexOf('|') + 1, 1); //余数
                    }

                    var sum = 0;
                    var flag = true;
                    $.each(self.instances, function(k, v) {
                        if (v.cfg != undefined) {
                            var isright = true;
                            $.each(v.cfg.item, function(k1, v1) {
                                if (v1.id == obj.id) {
                                    if (v1.value == "") { v1.value = 0; }
                                    if (self.validate_num(v1.value)) {
                                        sum += parseFloat(v1.value);
                                    } else {
                                        isright = false;
                                        return false;
                                    }
                                };
                            });
                            if (isright == false) {
                                flag = false;
                                return false;
                            }
                        }
                    });
                    if (flag == true) {
                        obj.value = sum.toFixed(reminder);
                    }
                }
            });

            self.sumobj.cfg.item = self.register.sumArr;
            self.sumobj.render();
        },

        validate_num: function(val) {
            if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(val) && val.length != 0) {
                return false;
            } else {
                return true;
            }
        },

        createSum: function() { //生成合计区域
            var self = this;
            var id = (new Date).getTime();
            var sumArr = {
                id: id,
                index: 'sum',
                title: (this.register.title ? this.register.title + ' - ' : this.register.title) + sumTitle,
                item: []
            };
            sumArr.item = $.extend(true, [], this.register.sumArr);
            var $el = self.$sumArea;
            $.each([sumArr], function(i, cfg) {
                var instance = self.sumfactory(this, $el);
                self.sumobj = instance;
            });
        },
        sumfactory: function(cfg, $block) {
            var klass = getFieldClass('RListField'),
                field;
            if (klass) {
                field = new klass(cfg, $block);
            }
            return field;
        },
        initFields: function(fields, $block) {
            var self = this,
                register = self.register,
                instances = self.instances;
            //列表无数据时，显示空提示，隐藏合计区域
            if (fields.length == 0) {
                this.$sumArea.hide();
                return;
            }
            this.$sumArea.show();
            //有删除权限，显示删除叉号
            if (register.delete_op == 1) {
                self.$el.addClass('deletePriv');
            }
            $.each(fields, function(i, cfg) {
				cfg.num = i +1 ;
                var instance = self.factory(cfg, $block);
                if (instance && instance.get('field_id')) {
                    instances[instance.get('field_id')] = instance;
                }

                instance.on('click', $.proxy(self.itemClickHandle, self));
                instance.on('delete', $.proxy(self.itemDeleteHandle, self));
            });
        },
        factory: function(cfg, $block) {
            var self = this;
            var klass = getFieldClass('RListField'),
                field;
            if (klass) {
                cfg.delete_op = this.register.delete_op;
				cfg.edit_op = this.register.edit_op;
				console.log('123',klass)
                field = new klass(cfg, $block, self.fieldManager);
            }
            return field;
        },
        parseBlock: function(d) {
            var tmpl = '<div class="reportBlock report-block-header clearfix"></div>';

            var $block = $($.parseTpl(tmpl, d));
            this.$wrapper.append($block);

            //if (d.op == '1' && d.add_op == 1) {
                this.$wrapper.parents('.tag-listview').find('.report-list-icon-create').addClass('active');
            //}
            return $block;
        },
        buildCreateHelper: function() {
            this.$el.append('<div class="read_detail clearfix report-create-helper"><label>' + createItemBtn + '</label></div>');
        },
        createItem: function() {
            var self = this,
                id = Math.round((new Date).getTime()/1000 + Math.random()*1000),
                createText = '新建';
            var schema = {
                field_id: id,
                index: id,
				flag: 'new',
                editable: true,
                name: (this.register.name ? this.register.name + ' - ' : this.register.name) + createText,
                subFields: this.register.subFields,
                value: []
            };
            schema.value = $.extend(true, [], this.register.schema);
            //新建字段赋予编辑权限
            $.each(schema.value, function(i, v) {
                schema.value[i].editable = true;
            });
            schema.data = schema.value;
            this.initFields([schema], this.$el);
			window.listHandleIndex = schema.index;
		    this.itemClickHandle(this.instances[id], {
                close: function() {
                    this.$el.remove();
                    this.destroy();
                },
                save: function() {
                    this.state = 'add';
                    self.register.sumArr && self.$el.trigger('_calcSum');
                }
            });

        },
        itemDeleteHandle: function(item, callbacks) {
            var self = this;
            if (self.instances[item.cfg.id]) {
                delete this.instances[item.cfg.id];
            }
			item.$el.remove();
            self.fieldManager.instances[self.register.field_id].synchListValue(item)
            //self.fieldManager && self.fieldManager.trigger('calc');
            //self.register.sumArr && self.$el.trigger('_calcSum');
        },
        itemClickHandle: function(item, callbacks) {
            var self = this;
            var saveItemBtn = "保存",
                closeItemBtn = '关闭';
            //console.log(this.register)
            //this.register.edit_op = 1 //假设有编辑权限
            //无编辑权限有新建权限，不能编辑已有的数据和新建保存的数据，只能在新建时修改对应数据
            if (item.get('flag') == 'new' || this.register.edit_op == 1) {
                var flag = "";
                if (item.get('flag') == 'new' || item.get('flag') == 'sum') {
                    flag = "new";
                };
				var type = flag === 'new'  || flag === 1 ? '新建':'编辑';
                item.cfg.name = (self.register.name ? self.register.name + ' - ' : self.register.name) + type,
                    $.each(item.cfg.data, function(i, data) {
                        data.editable = self.register.edit_op;
                    })

                var $panel = $('#scroller_list_detail');
                $panel.empty();
                pages.to('list_detail');
                var $inner = $('<div>').appendTo($panel);
                var $btnwrapper = $("<div style='text-align: center;margin-top:15px;'>").appendTo($panel);
                var $save = $('<button>').text(saveItemBtn).addClass("reportbtn").appendTo($btnwrapper); //保存
                var $close = $('<button>').text(closeItemBtn).addClass("reportclose").appendTo($btnwrapper);
                $panel.append('<div class="holder-40"></div>');
				//修正本无删除权限，但是编辑点进去再返回会出现滑动删除的情况
				if(this.register.delete_op == true){
					Swiped.init({
						query: ('.read_detail'),
						list: true,
						right: 100
					});
				}
                $close.click(function() {
                    delete window.listHandleIndex;
					$panel.empty();
                    history.back();
                    callbacks && callbacks.close && callbacks.close.call(item);
                    if (self.$wrapper.find('.tag-RListField').length == 0) {
                        self.$sumArea.hide();
                    }
                });
                $save.click(function() {
                    delete window.listHandleIndex;
					if($('#nocontent').size()){
						$('#nocontent').remove();
					}

                    l.updateDataFromFields();　　
                    item.render();　//向略缩图增加一行数据
                    // self.fieldManager.trigger('calc');
                    // self.register.sumArr && self.$el.trigger('_calcSum');
                    $panel.empty();
                    history.back();
                    saveFlag = 0;
                    callbacks && callbacks.save && callbacks.save.call(item);
                    l.fieldManager.instances[self.register.field_id].synchListValue(item)

                });
                //console.log('3',self.fieldmanager);
                //点击条目打开编辑详情
                l = new ListViewManager({
                    wrapper: $inner,
                    fieldManager: self.fieldManager
                }, {
                    // subFields: self.register.subFields,
                    // name: self.register.name,
                    main: [item.cfg],
                    field_id: this.getId(),
                    flag: flag
                });
                setTimeout(function() {
                    $panel.show();
                }, 1)
            }
        },
        serializeArray: function() {
            var str = "";
            $.each(this.instances, function(i, field) {
                var data = field.getData()
                    //data && ret.push(data);
                data && (str += field.getData());
            });
            return str;
        }

    });
    exports.ListViewManager = window.ListViewManager = ListViewManager;
});
define('ReportFieldLoder', ["RTextField", "RListField", "RNumberField", "RCurrencyField","RDateField","RSelectField","RLinkField",  "RCheckboxField", "RRadioField", "RCalcCtrlField", "RMultitextField",  "RDeptselectField", "RUserselectField","RAttachmentField","RImageField","RQrcodeField","RBarcodeField"], function(require, exports, module) {
    var depends = module.dependencies;
    if([].forEach){
        depends.forEach(function(v, i){
            var mod = require(depends[i]);
            exports[depends[i]] = mod ? mod[depends[i]] : null;
        });
    }else{
        for (var i in depends) {
            var mod = require(depends[i]);
            exports[depends[i]] = mod ? mod[depends[i]] : null;
        }
    }

});
/*文本组件*/
define('RTextField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var RTextField = Base.extend({
        attrs: {
            id: null
        },
        initialize: function(cfg, $wrapper) {
            cfg.fieldtype = cfg.type;
            cfg.value = cfg.value || '';
            cfg.id = cfg.id || cfg.field_id;
            RTextField.superclass.initialize.call(this, cfg);
            this.cfg = cfg;
            this.$el = $(this.parseTpl(cfg));
            this.$field = this.getField();
            this.$wrapper = $wrapper;
            if (!cfg.secret) {
                this.appendFieldElement(this.$el);
            }
            this.bindEvent();
			this.initialized();
        },

        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
			var pure_id = cfg.id.substring(1,cfg.id.length-1);
            var tmpl = '<div class="read_detail clearfix tag-<%=fieldtype%> '+ hidden +'">\
							<label>\
								' + (cfg.required ?'<span class="ui-form-badge-dot">* </span>' : '') +
								'<%=name%>：\
							</label>\
							<div class="field">\
								' + (cfg.editable == true && cfg.writable == true ?
								'<input value="<%=value%>" name="<%=id%>" type="text" />' :
								'<span id="'+pure_id+'"><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
							'</div>\
						</div>';
            return $.parseTpl(tmpl, cfg)
        },
        appendFieldElement: function(el) {
            this.$wrapper.append(el);
        },
        getField: function() {
            return this.$el.find('input')
        },
        getValue: function() { return this.getField().val() },
        getText: function() { return this.getValue() },
        updateDataFromField: function() {
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        getData: function() {
            var ret = {};
            if (this.cfg.editable) {
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;
            }
            return ret;
        },
		initialized: function() {},
        validate_num: function() {
            var str = this.getValue();
            if (str.length == 1 && str == "-") {
                return true;
            };
            if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length != 0) {
                alert(numverTips);
                return false;
            } else {
                return true;
            }
        },
        bindEvent: function() {
			var self = this,
				$input = self.getField();
               
			$input.bind("keyup", function() {
				var value =  self.getValue().trim();
				var field_id = self.cfg.field_id;
				var list_field_id = self.cfg.listManager.register.field_id;
				var list_data = self.cfg.listManager.register.main[0];
				$.each(list_data.data,function(i,data){
					if(data.field_id === field_id){
						data.value = value;
					}
				}) 
                if(self.cfg.trigger === true && value !== ""){
					list_data.trig_field_id = field_id;
					list_data.field_id  = list_field_id;
					fieldManager.triggerTrig(list_data);
				}
            });
		},
        bindCalc: function(instances, rule, result, reminder) {
            var $input = this.getField(),
                self = this;
            $input.bind("keyup paste _calced", function() {
                var ret = [];
                ret.push(instances);
                ret.push(rule);
                ret.push(result);
                ret.push(reminder);
                var flag = self.validate_num();
                flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
            });
        }
    });
    exports.RTextField = window.RTextField = RTextField;
});

/*列表组件*/
define('RListField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RListField = RTextField.extend({
        attrs: {
            id: null
        },
        initialize: function(cfg, $wrapper) {
            RTextField.superclass.initialize.call(this, cfg);
            var self = this;
            this.cfg = cfg;
            this.$el = $(this.parseTpl(cfg));
            this.$wrapper = $wrapper;
            this.appendFieldElement(this.$el);
            this.bindEvent();
        },
        bindEvent: function() {
            var self = this;
            self.$el.on('tap', '.list-item', function(e) {
				if(!window.stopDelete){
					e.stopPropagation();
					window.listHandleIndex = self.cfg.index;
					self.trigger('click', self);
				}
				window.stopDelete = false;
            })
			//略缩图的删除
            self.$el.delegate('.read_detail_delete span', 'touchend', function(e) {
                e.stopPropagation();
                var delflag = confirm(confirmDelete);
                if (delflag == true) {
					self.cfg.flag = 'delete'
                    self.trigger('delete', self);
                    saveFlag = 0;
                    if ($('.reportBlock.report-block-header').find('.read_detail').length == 0) {
                        $('.reportBlock.report-block-header').append("<div id='nocontent'>暂无可显示数据</div>");
                    }
                    return true;
                } else {
					window.stopDelete = true;
                    return false;
                }
            });
        },
        parseTpl: function(cfg) {
            var id = this.get('id'),
                sum_text = "合计",
                add_text = "新增",
				className ='no';
            var tmpl = (function() {
                var fields = [];

                var title = "";
                if (cfg.flag == "sum") {
                    title = sum_text;
                } else if (cfg.flag == "new") {
					className = 'no-new'
                    title = add_text;
                } else {
                    title = cfg.num;
                }

                $.each(cfg.data, function(i, v) {
                    //console.log(v);
                    if (v.hidden) {
                        if (i == 0) {
                            fields.push('<b class="'+className+'">' + title + '</b>');
                        }
                    } else {
                        if (i == 0) {
                            fields.push('<b class="'+className+'">' + title + '</b><label>' + v.name + ': ' + emptyHelper(v.value, v) + '</label>');
                        } else {
                            fields.push('<label>' + v.name + ': ' + emptyHelper(v.value, v) + '</label>');
                        }
                    }
                });
                var newClass = (cfg.flag == 'new') ? 'list-item-new' : '';
                var html = '';
                var deletehtml = '';
                var read_detail_delete = "删除";
                if (cfg.flag == 'new') {
                    deletehtml = '<div class="read_detail_delete"><div class="read_detail_delete_wrapper"><span>' + read_detail_delete + '</span></div></div>';
                }
                if (cfg.delete_op != undefined && cfg.delete_op != false) {
                    deletehtml = '<div class="read_detail_delete"><div class="read_detail_delete_wrapper"><span >' + read_detail_delete + '</span></div></div>';
                }

                html = '<div class="read_detail clearfix tag-RListField" ><div class="clearfix list-item ' + newClass + '">' + fields.join('\n\r') + '</div>' + deletehtml + '</div>';
                return html;
            })();
            return $.parseTpl(tmpl, cfg)
        },
        render: function() {
            this.$el.html($(this.parseTpl(this.cfg)).html());
            if (this.cfg.delete_op === true) {
                Swiped.init({
                    query: '.list-wrapper .read_detail ',
                    list: true,
                    right: 100
                });
            }else {
                if (this.cfg.flag == 'new') {
                    $('body').addClass('deletePriv');
                    Swiped.init({
                        query: '.list-wrapper .read_detail ',
                        list: true,
                        right: 100
                    });
                }
            }
        },
        appendFieldElement: function(el) {
            this.$wrapper.append(el);
        },
        getData: function() {
            if (this.cfg == undefined) {
                return;
            }
            //var ret = [];
            var data_str = "";
            $.each(this.cfg.item, function() {
                if (this.fieldtype == "RRadioField" || this.fieldtype == "RCheckboxField") {
                    var the_value = this.value;
                    the_value = the_value.replace(/\r\n/g, "&lt;br&gt;");
                    data_str += this.value + "`";
                } else if (this.fieldtype == "RTextareaField") {
                    var the_value = this.value;
                    the_value = the_value.toString();
                    the_value = the_value.replace(/`/g, '[0x60]');
                    var textarea_html = the_value + "`";
                    textarea_html = textarea_html.replace(/\r\n/g, "&lt;br&gt;");
                    textarea_html = textarea_html.replace(/\n/g, "&lt;br&gt;");
                    data_str += textarea_html;
				}else {
                    var the_value = this.value;
                    the_value = the_value.toString();
                    the_value = the_value.replace(/`/g, '[0x60]');
                    data_str += the_value + "`";
                }
            });
            data_str = data_str.replace(/\r\n/g, "&lt;br&gt;");
            data_str += "\n";
            return data_str;
        }
    });

    function emptyHelper(c, v) {
		//alert(typeof c)
		console.log(v.type)
        if (v && v.fieldtype == "RCalcCtrlField") {
            return c == '' ? 0 : c;
        } else if (c instanceof Array && v && v.type == "user-select") {
            var opts = '';
            $.each(c, function(i, value) {
                opts = opts.length === 0 ? value.username : opts + "," + value.username;
            })
            return opts
        } else if (c instanceof Array && v && v.type == "dept-select") {
            var opts = '';
            $.each(c, function(i, value) {
                opts = opts.length === 0 ? value.dept_name : opts + "," + value.dept_name;
            })
            return c = opts
        } else if (c instanceof Array && v && v.type == "attachment") {
			var opts = '';
            $.each(c, function(i, value) {
                opts = opts.length === 0 ? value.attach_name : opts + "," + value.attach_name;
            })
            return c = opts
		} else if (c instanceof Array && v && v.type == "image") {
			var opts = '';
            $.each(c, function(i, value) {
                opts = opts.length === 0 ? value.attach_name : opts + "," + value.attach_name;
            })
            return c = opts
		} else if (c instanceof Array && c.length > 0 && v && v.type == "select") {
            var opts = [];
            if(v.options && v.options.length){
                $.each(v.options, function(i, item) {
                    if(c.includes(item.code.toString())){
                        opts.push(item.name);
                    }
                })
            }else{
                return c;//修复选项没有情况下，不显示值的问题
            }
            return c = opts.join(',');
        }else if (typeof c === "string" && v && v.type == "select") {
            var opts = "";
            if(v.options && v.options.length){
                $.each(v.options, function(i, item) {
                    if(c.includes(item.code.toString())){
                        opts = item.name;
                    }
                })
            }else{
                return c;//修复选项没有情况下，不显示值的问题
            }
            return c = opts;
        } else if (c instanceof Array && c.length > 0 && v && v.type == "checkbox") {
            var opts = [];
            if(v.options && v.options.length){
                $.each(v.options, function(i, item) {
                    if(c.includes(item.code.toString())){
                        opts.push(item.name);
                    }
                })
            }else{
                return c;//修复选项没有情况下，不显示值的问题
            }
            return c = opts.join(',')
        } else if (typeof c === "string" && v && v.type == "QRcode") {
			return (c == '' || c == null) ? ' - ' : '<img src="'+ c +'" style="height:30px;width:auto;vertical-align: middle;" />';
		} else if (typeof c === "string" && v && v.type == "barcode") {
			return (c == '' || c == null) ? ' - ' : '<img src="'+ c +'" style="height: 30px; width:auto;vertical-align: middle;" />';
		}else {
            return (c == '' || c == null) ? ' - ' : c;
        }
    }
    exports.RListField = window.RListField = RListField;
});
/*数字组件*/
define('RNumberField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RNumberField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
			var pure_id = cfg.id.substring(1,cfg.id.length-1);
            var tmpl = '<div class="read_detail clearfix tag-<%=fieldtype%> '+hidden+'" >\
							<label>\
								' + (cfg.required ?'<span class="ui-form-badge-dot">* </span>' : '') +
								'<%=name%>：\
							</label>\
							<div class="field">\
								' + (cfg.editable == true && cfg.writable == true ?
								'<input value="<%=value%>" name="<%=id%>" type="text" />' :
								'<span id="'+pure_id+'"><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
							'</div>\
						</div>';
            return $.parseTpl(tmpl, cfg)
        },
        getField: function() {
            return this.$el.find('input')
        },
        getValue: function() { return this.getField().val() },
        updateDataFromField: function() {
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        getData: function() {
            var ret = {};
            if (this.cfg.editable) {
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;
            }
            return ret;
        },
		initialized: function() {},
        validate_num: function() {
            var str = this.getValue();
            if (str.length == 1 && str == "-") {
                return true;
            };
            if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length != 0) {
                alert('请输入数字');
                this.$el.find('input').val('');
                return false;
            } else {
                return true;
            }
        },
        bindEvent: function() {
            var $input = this.getField(),
                self = this;
            $input.bind("keyup paste _calced", function() {
                self.validate_num();
            });
			$input.bind("keyup", function() {
				var value =  self.getValue().trim()
				var field_id = self.cfg.field_id;
				var list_field_id = self.cfg.listManager.register.field_id;
				var list_data = self.cfg.listManager.register.main[0];
				$.each(list_data.data,function(i,data){
					if(data.field_id === field_id){
						data.value = value;
					}
				}) 
                if(self.cfg.trigger === true && value !== ""){
					list_data.trig_field_id = field_id;
					list_data.field_id  = list_field_id;
					fieldManager.triggerTrig(list_data);
				}
            });
        },
        bindCalc: function(instances, rule, result, reminder) {
            var $input = this.getField(),
                self = this;
            $input.bind("keyup paste _calced", function() {
                var ret = [];
                ret.push(instances);
                ret.push(rule);
                ret.push(result);
                ret.push(reminder);
                var flag = self.validate_num();
                flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
            });
        }
    });
    exports.RNumberField = window.RNumberField = RNumberField;
});

/*货币组件*/
define('RCurrencyField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RCurrencyField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
			var pure_id = cfg.id.substring(1,cfg.id.length-1);
            var tmpl = '<div  class="read_detail clearfix tag-<%=fieldtype%>'+hidden+'">\
						    <label>\
								' + (cfg.required ?'<span class="ui-form-badge-dot">* </span>' : '') +
								'<%=name%>：\
							</label>\
						    <div class="field">\
								' + (cfg.editable == true && cfg.writable == true?
								'<input value="<%=value%>" name="<%=id%>"  type="text" />' :
								'<span id="'+pure_id+'"><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
							'</div>\
						</div>';
            return $.parseTpl(tmpl, cfg)
        },
        appendFieldElement: function(el) {
            this.$wrapper.append(el);
        },
        getField: function() {
            return this.$el.find('input')
        },
        getValue: function() { return this.getField().val() },
        updateDataFromField: function() {
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        getData: function() {
            var ret = {};
            if (this.cfg.editable) {
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;
            }
            return ret;
        },
		initialized: function() {},
        validate_num: function() {
            var str = this.getValue();
            if (str.length == 1 && str == "-") {
                return true;
            };
            if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length != 0) {
                alert('请输入数字');
                this.$el.find('input').val('');
                return false;
            } else {
                return true;
            }
        },
        bindEvent: function() {
            var $input = this.getField(),
                self = this;
            $input.bind("keyup paste _calced", function() {
                self.validate_num();
            });
			$input.bind("keyup", function() {
				var value =  self.getValue().trim()
				var field_id = self.cfg.field_id;
				var list_field_id = self.cfg.listManager.register.field_id;
				var list_data = self.cfg.listManager.register.main[0];
				$.each(list_data.data,function(i,data){
					if(data.field_id === field_id){
						data.value = value;
					}
				}) 
                if(self.cfg.trigger === true && value !== ""){
					list_data.trig_field_id = field_id;
					list_data.field_id  = list_field_id;
					fieldManager.triggerTrig(list_data);
				}
            });
        },
        bindCalc: function(instances, rule, result, reminder) {
            var $input = this.getField(),
                self = this;
            $input.bind("keyup paste _calced", function() {
                var ret = [];
                ret.push(instances);
                ret.push(rule);
                ret.push(result);
                ret.push(reminder);
                var flag = self.validate_num();
                flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
            });
        }
    });
    exports.RCurrencyField = window.RCurrencyField = RCurrencyField;
});

/*日期组件*/
define('RDateField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RDateField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
			var pure_id = cfg.id.substring(1,cfg.id.length-1);
            var tmpl = '<div class="read_detail clearfix tag-<%=fieldtype%> '+hidden+'">\
							<label>\
								' + (cfg.required ?'<span class="ui-form-badge-dot">* </span>' : '') +
								'<%=name%>：\
							</label>\
							<div class="field">\
								' + (cfg.editable == true && cfg.writable == true?
								'<input value="<%=value%>" name="<%=id%>"  type="text" /><i class="ui-icon-commonflow ui-form-icon-time"></i>' :
								'<span id="'+pure_id+'"><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
							'</div>\
						</div>';
            return $.parseTpl(tmpl, cfg)
        },
        initialized: function() {
			var format = this.cfg.format;
            if (this.cfg.editable && this.cfg.writable == true) {
                var $input = this.getField();
				$input.attr("readonly", "true");
                if (format == "yyyy/MM/dd"  ) {
                    $input.attr({ "data-type": "date" });
				} else if(format == "yyyy-MM-dd"){
                    $input.attr({ "data-type": "date1" })
				} else if(format == "yyyyMMdd" ){
                    $input.attr({ "data-type": "date2" })
                } else if(format == "HH:mm:ss" ){
                    $input.attr({ "data-type": "time" });
				} else if(format == "HH:mm"){
                    $input.attr({ "data-type": "time1" });
                } else{
					$input.attr({ "data-type": "datetime" });
				}
                $input.focus(function() {
                    if ($input.attr("data-type") == "date" ) {
                        $('[data-type="date"]').mobiscroll().date({
                            theme: 'ios7',
                            lang: 'zh',
                            dateFormat: 'yyyy/mm/dd',
                            display: 'bottom',
                            mode: 'scroller'
                        });
					}else if ($input.attr("data-type") == "date1" ) {
                        $('[data-type="date1"]').mobiscroll().date({
                            theme: 'ios7',
                            lang: 'zh',
                            dateFormat: 'yyyy-mm-dd',
                            display: 'bottom',
                            mode: 'scroller'
                        });
					}else if ($input.attr("data-type") == "date2" ) {
                        $('[data-type="date2"]').mobiscroll().date({
                            theme: 'ios7',
                            lang: 'zh',
                            dateFormat: 'yyyymmdd',
                            display: 'bottom',
                            mode: 'scroller'
                        });
                    } else if ($input.attr("data-type") == "datetime") {
                        $('[data-type="datetime"]').mobiscroll().datetime({
                            theme: 'ios7',
                            lang: 'zh',
							dateFormat: 'yy-mm-dd',
							timeFormat: 'HH:ii:ss',
							timeWheels:'HHiiss',
                            display: 'bottom',
                            mode: 'scroller'
                        });
                    } else if ($input.attr("data-type") == "time") {
                        $('[data-type="time"]').mobiscroll().time({
                            theme: 'ios7',
                            lang: 'zh',
                            display: 'bottom',
                            timeFormat: 'HH:ii:ss',
							timeWheels:'HHiiss',
                            mode: 'scroller'
                        });
                    } else if ($input.attr("data-type") == "time1") {
                        $('[data-type="time1"]').mobiscroll().time({
                            theme: 'ios7',
                            lang: 'zh',
                            display: 'bottom',
                            mode: 'scroller',
							timeFormat: 'HH:ii'
                        });
                    }
                });
            }
        },

		bindEvent: function() {
            var $input = this.getField(),
                self = this;
			$input.bind("keyup", function() {
				var value =  self.getValue().trim()
				var field_id = self.cfg.field_id;
				var list_field_id = self.cfg.listManager.register.field_id;
				var list_data = self.cfg.listManager.register.main[0];
				$.each(list_data.data,function(i,data){
					if(data.field_id === field_id){
						data.value = value;
					}
				}) 
                if(self.cfg.trigger === true && value !== ""){
					list_data.trig_field_id = field_id;
					list_data.field_id  = list_field_id;
					fieldManager.triggerTrig(list_data);
				}
            });
        },
    });
    exports.RDateField = window.RDateField = RDateField;
});

/*下拉框*/
define('RSelectField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RSelectField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
			var pure_id = cfg.id.substring(1,cfg.id.length-1);
            var tmpl = '<div class="read_detail clearfix tag-<%=fieldtype%> '+hidden+'">\
							<label>\
								' + (cfg.required ?'<span class="ui-form-badge-dot">* </span>' : '') +
								'<%=name%>：\
							</label>\
							<div class="field">\
								' + (cfg.editable == true && cfg.writable == true ?
								'<select  value="<%=value%>" <%=multi ? "multiple" : ""%> name="<%=id%>" title="<%=name%>"></select>' :
								'<span id="'+pure_id+'"><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
							'</div>\
						</div>';
            return $.parseTpl(tmpl, cfg)
        },
        renderOption: function(cfg) {
            var options = cfg.options,
                opData = options ? options : [],
                html = [];
            $.each(opData, function() {
                if (this == '') {
                    return
                }
                var selected = cfg.value.includes(this.code) ? ' selected ' : '';
                if(html.length == 0 && this.code != ""){
                    html.push('<option value=""></option>');
                }
                html.push('<option value="' + this.code + '" ' + selected + '>' + this.name + '</option>');
            });
            if(opData.length == 0){
                html.push('<option value="' + cfg.value + '" selected>' + cfg.value + '</option>');
            }
            this.$field.append(html.join(''));
        },


        getField: function() {
            if (this.cfg.editable) {
                return this.$el.find('select')
            } else {
                return this.$el.find('input')
            }
        },
        getValue: function() {
            var list_value = [];
            if (this.cfg.editable) {
                var this_obj = this.getField().find('option');
                for (var this_count = 0; this_count < this_obj.length; this_count++) {
                    if ($(this_obj[this_count]).prop("selected") === true) {
                       list_value.push($(this_obj[this_count]).val());

                    }
                }
                return this_obj.length > 0 ? list_value.join(',') : this.getField().val();
                // return this.getField().find('option:selected').val()
            } else {
                return this.getField().val()
            }
        },
		bindEvent: function() {
            var $input = this.getField(),
                self = this;
			$input.bind("change", function() {
				var value =  self.getValue().trim()
				var field_id = self.cfg.field_id;
				var list_field_id = self.cfg.listManager.register.field_id;
				var list_data = self.cfg.listManager.register.main[0];
				$.each(list_data.data,function(i,data){
					if(data.field_id === field_id){
						data.value = value;
					}
				}) 
                if(self.cfg.trigger === true && value !== ""){
					list_data.trig_field_id = field_id;
					list_data.field_id  = list_field_id;
					fieldManager.triggerTrig(list_data);
				}
            });
        },
        getText: function() {
            var list_value = [];
            if (this.cfg.editable) {
                var this_obj = this.getField().find('option');
                for (var this_count = 0; this_count < this_obj.length; this_count++) {
                    if ($(this_obj[this_count]).prop("selected") === true) {
                       list_value.push($(this_obj[this_count]).text());
                    }
                }
                return this_obj.length > 0 ? list_value : this.getField().text();
                // return this.getField().find('option:selected').text()
            } else {
                return this.getField().text()
            }
        },
        initialized: function() {
            if (this.cfg.editable && this.cfg.editable == "1") {
                this.renderOption(this.cfg);
            }
        },
        bindCalc: function(instances, rule, result, reminder) {
            var self = this,
                $select = self.getField(),
                ret = [];
            ret.push(instances);
            ret.push(rule);
            ret.push(result);
            ret.push(reminder);
            if (self.cfg.editable) {
                $select.bind("change _calced", function() {
                    var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            } else {
                $select.bind("_calced", function() {
                    var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            }
        }
    });
    exports.RSelectField = window.RSelectField = RSelectField;
});
/*链接组件*/
define('RLinkField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RLinkField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
            var tmpl = '<div class="read_detail clearfix tag-<%=fieldtype%> '+hidden+'">\
							<label>\
								' + (cfg.required ?'<span class="ui-form-badge-dot">* </span>' : '') +
								'<%=name%>：\
							</label>\
							<div class="field">\
								' + (cfg.editable == true && cfg.writable == true?
								'<a href="javascript:;"  target="_blank">查看</a>' :
								'<span>查看<span>') +
							'</div>\
						</div>';
            return $.parseTpl(tmpl, cfg)
        },
		getValue: function() {
			return this.cfg.value || ''
		},
        bindEvent: function() {
            var self = this;
			self.$el.find('a').tap(function() {
                var data =  $.isFunction(self.cfg.listManager.fieldManager.getData) && self.cfg.listManager.fieldManager.getData();
                $.ajax({
                    type: "POST",
                    url: "/general/appbuilder/web/appcenter/appdata/link",
                    data: {
                        formId: store.formInfo.formId, //表单id
                        data: data, //所有数据 数组
                        field_id: self.cfg.field_id, //点击的链接字段id
                        index: 1 //可选 列表子字段需要传行号，主表传1
                    },
                    success: function(response) {
                        if (response.status == 'ok') {
                            var data = response.data;
                            var formId = data.formId;
                            var userid = data.userid;
                            var time = data.time;
                            var $link = self.$el.find('a');
                            $link.attr('href','/pda/appcenter/handle.php?type=read&formId='+formId+'&userid='+userid+'&time='+time+'#handle');
                            //$link.attr('href','https://www.baidu.com')
                            //$link.trigger('click')
							// console.log(self.cfg.listManager)
							self.cfg.value = '/pda/appcenter/handle.php?type=read&formId='+formId+'&userid='+userid+'&time='+time+'#handle'
							var value = self.cfg.value;
							var field_id = self.cfg.field_id;
							var list_field_id = self.cfg.listManager.register.field_id;
							var list_data = self.cfg.listManager.register.main[0];
							$.each(list_data.data,function(i,data){
								if(data.field_id === field_id){
									data.value = value;
								}
							}) 
							if(self.cfg.trigger === true && value !== ""){
								list_data.trig_field_id = field_id;
								list_data.field_id  = list_field_id;
								self.cfg.listManager.fieldManager.triggerTrig(list_data);
							}
                        }
                    }
                })
            })

        }
    });
    exports.RLinkField = window.RLinkField = RLinkField;
});
/*复选组件*/
define('RCheckboxField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RCheckboxField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
            var tmpl = '\
<div class="ui-form-item  ui-border-b f-checkbox tag-<%=fieldtype%> '+hidden+'">\
  <label class="ui-form-item-title"><%=name%>:</label>\
  <div class="ui-form-control">\
    ' + (cfg.editable == true ?
                    '<div class="checkWrapper" id="<%=id%>_check"></div>' :
                    '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
                '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        getField: function() {
            if (this.cfg.editable) {
                return this.$el.find('.checkWrapper')
            } else {
                return this.$el.find('input')
            }
        },
        getValue: function() {
            var cvalue = 0;
            this.getField().find('input[type="checkbox"]:checked').each(function() {
                var $this = $(this);
                var val = $this.parent('label').find('span').text();
                cvalue += parseFloat(val);
            });
            cvalue = cvalue.toString();
            return cvalue;
        },
        getValueString: function() {
            if (this.cfg.editable) {
                var str = [];
                this.getField().find('input[type="checkbox"]:checked').each(function() {
                    var $this = $(this);
                    var val = $this.parent('label').find('input').val();
                    str.push(val);
                });
                //str = str.substr(0, str.length - 1);
                return str;
            } else {
                return this.getField().val()
            }
        },
        updateDataFromField: function() {
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValueString();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        renderItem: function(cfg) {
            this.$field = this.getField();
            var default_items;
            if (typeof(cfg.value) == 'object') {
                default_items = cfg.value.join(',');
            } else {
                default_items = cfg.value;
            }
            var options = cfg.options,
                opData = options ? options : [],

                defaultData = default_items ? default_items.split(',') : [],
                html = [];
            $.each(opData, function(k, v) {
                if (this == '') {
                    return;
                }
                var checked = $.inArray(v.code, defaultData) == -1 ? '' : 'checked';
                html.push('<label for="' + cfg.id + '_' + v.code + '" class="ui-checkbox"><input value="' + this.code + '"  type="checkbox" name="' + cfg.id + '" id="' + cfg.id + '_' + v.code + '" ' + checked + ' /><span>' + this.name + '</span></label>');
            });
            this.$field.append(html.join(''));
        },
        initialized: function() {
            if (this.cfg.editable ) {
                this.renderItem(this.cfg);
                this.$el.addClass('field-editable');

            }
        },
        bindCalc: function(instances, rule, result, reminder) {
            var self = this,
                $field = self.getField(),
                ret = [];
            ret.push(instances);
            ret.push(rule);
            ret.push(result);
            ret.push(reminder);
            if (self.cfg.editable) {
                $field.find('input').each(function() {
                    var $this = $(this);
                    $this.on('tap click _calced', function(event) {
                        setTimeout(function() {
                            var flag = self.validate_num();
                            flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                        }, 1000);
                    });
                });
            } else {
                $field.on('_calced', function(event) {
                    var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            }
        }
    });
    exports.RCheckboxField = window.RCheckboxField = RCheckboxField;
});
/*单选组件*/
define('RRadioField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RRadioField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
            var tmpl = '\
<div class="ui-form-item ui-border-b f-radio tag-<%=fieldtype%> '+hidden+'">\
  <label class="ui-form-item-title"><%=name%>:</label>\
  <div class="ui-form-control">\
    ' + (cfg.editable == true?
                    '<div class="radioWrapper" id="<%=id%>_radio"></div>' :
                    '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
                '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        getField: function() {
            if (this.cfg.editable) {
                return this.$el.find('.radioWrapper')
            } else {
                return this.$el.find('input')
            }
        },
        getValue: function() {
            if (this.cfg.editable) {
                var str = '';
                if ($("input[name='" + this.cfg.id + "']:checked").length > 0) {
                    str = $("input[name='" + this.cfg.id + "']:checked").parent('label').find('span').text();
                }
                return str;
            } else {
                return this.getField().val();
            }
        },
        updateDataFromField: function() {
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        renderItem: function(cfg) {
            var options = cfg.options,
                opData = options ? options : [],
                html = [];
            this.$field = this.getField();
            $.each(opData, function(k, v) {
                if (this == '') {
                    return
                }
                var checked = this.code == cfg.value ? ' checked ' : '';
                html.push('<label for="' + cfg.id + '_' + v.code + '" class="ui-radio"><input type="radio" name="' + cfg.id + '" id="' + cfg.id + '_' + v.code + '" ' + checked + ' /><span>' + this.name + '</span></label>');
            });
            this.$field.append(html.join(''));
        },
        initialized: function() {
            if (this.cfg.editable ) {
                this.renderItem(this.cfg);
                this.$el.addClass('field-editable');
            }
        },
        bindCalc: function(instances, rule, result, reminder) {
            var self = this,
                $field = self.getField(),
                ret = [];
            ret.push(instances);
            ret.push(rule);
            ret.push(result);
            ret.push(reminder);
            if (self.cfg.editable) {
                $field.find('input').each(function() {
                    var $this = $(this);
                    $this.on('tap click _calced', function(event) {
                        setTimeout(function() {
                            var flag = self.validate_num();
                            flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                        }, 1000);
                    });
                });
            } else {
                $field.on('_calced', function(event) {
                    var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            }
        }
    });
    exports.RRadioField = window.RRadioField = RRadioField;
});
/*多行组件*/
define('RMultitextField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RMultitextField = RTextField.extend({
        parseTpl: function(cfg) {
            if (cfg.editable) {
                cfg.value = this.lfDecode(cfg.value);
            } else {
                cfg.value = cfg.value.replace(/&lt;br&gt;/g, "<br>");
            }
            var hidden = cfg.hidden ? " hide" : "";
            var readonly = cfg.writable ? "" : "readonly";
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%> '+hidden+'">\
  <label style="font-size:16px;"><%=name%>:</label>\
  <div class="field">\
    ' + (cfg.editable == true ?
                    '<textarea name="<%=id%>" value="<%=value%>" style="font-size:15px; width:100%; height:200px; line-height:20px;"><%=value%></textarea>' :
                    '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
                '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        initialized: function() {
            if (this.cfg.editable) {
                this.cfg.editable == true && this.$el.addClass('field-editable');
            }
        },
        lfEncode: function(s) {
            s = s.replace(/\r\n/g, '<br>');
            s = s.replace(/\n/g, '<br>');
            return s;
        },
        lfDecode: function(s) {
            s = s.replace(/&lt;br&gt;/ig, "\r\n");
            s = s.replace(/<br>/ig, "\r\n");
            return s;
        },
        getField: function() {
            if (this.cfg.editable ) {
                return this.$el.find('textarea')
            } else {
                return this.$el.find('input')
            }
        },
        getValue: function() {
            if (this.cfg.editable) {
                return this.lfEncode(this.getField().val());
            } else {
                return this.$el.find('input').val();
            }
        },
        bindCalc: function(instances, rule, result, reminder) {
            var self = this,
                $input = this.getField(),
                ret = [];
            ret.push(instances);
            ret.push(rule);
            ret.push(result);
            ret.push(reminder);
            if (self.cfg.editable) {
                $input.bind("keyup paste _calced", function() {
                    var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            } else {
                $input.bind("_calced", function() {
                    var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            }
        }
    });
    exports.RMultitextField = window.RMultitextField = RMultitextField;
});
/*选部门组件*/
define('RDeptselectField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RDeptselectField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%> '+hidden+'">\
  <label style="font-size:16px;"><%=name%>:</label>\
  <div class="field">\
    ' + (cfg.editable == true ?
                    '<button type="button" class="ui-btn">选择</button><div class="ui-deptselect-list">\
                <% for(var i=0; i<value.length;i++) { %>\
                    <a href="javascript:;" class="ui-form-tag" style="display:inline-block" data-deptid="<%=value[i].dept_id%>"><%=value[i].dept_name%></a>\
                <% } %>\
            </div>\
    ' :
                    '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
                '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        appendFieldElement: function(el) {
            this.$wrapper.append(el);
        },
        getField: function() {
            return this.$el.find('input')
        },
        getValue: function() { 
			//return this.getField().val()
			return this.cfg.value ? this.cfg.value : []		
		},
        updateDataFromField: function() {
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        getData: function() {
            var ret = {};
            if (this.cfg.editable) {
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;
            }
            return ret;
        },
        initialized: function() {
            this.cfg.editable == true && this.$el.addClass('field-editable');
        },
        validate_num: function() {
            var str = this.getValue();
            if (str.length == 1 && str == "-") {
                return true;
            };
            if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length != 0) {
                alert(numverTips);
                return false;
            } else {
                return true;
            }
        },
        bindEvent: function() {
            var self = this;
            this.$el.find('button').on('tap', function() {
                var depts = [];
                var $select_ids = $(this).parent('div.field').find('a');
                $.each($select_ids, function(i) {
                    var deptId = $(this).attr('data-deptid')
                    depts.push(deptId);
                })
				var _value = [];
				self.cfg.value = [];
                tMobileSDK.selectDept({
                    depts: depts,
                    onSuccess: function(result) {
                        self.$el.find('.ui-deptselect-list').empty();
                        for (var i = 0; i < result.length; i++) {
                            var dept_html = '<a href="javascript:;" class="ui-form-tag" data-deptid="' + result[i].deptId + '">' + result[i].deptName + '</a>';
                            self.$el.find('.ui-deptselect-list').append(dept_html);
							_value.push({
								dept_id: result[i].deptId,
								dept_name: result[i].deptName
							})
                        }
						self.cfg.value = _value
                    },
                    onFail: function(data) {
                        alert("部门选人失败：" + data);
                    }
                });
				
            });
        },
        bindCalc: function(instances, rule, result, reminder) {
            var $input = this.getField(),
                self = this;
            $input.bind("keyup paste _calced", function() {
                var ret = [];
                ret.push(instances);
                ret.push(rule);
                ret.push(result);
                ret.push(reminder);
                var flag = self.validate_num();
                flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
            });
        }
    });
    exports.RDeptselectField = window.RDeptselectField = RDeptselectField;
});
/*选人组件*/
define('RUserselectField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RUserselectField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%> '+hidden+'">\
  <label style="font-size:16px;"><%=name%>:</label>\
  <div class="field">\
    ' + (cfg.editable == true?

                    '<button type="button" class="ui-btn">选择</button>\
      <div class="ui-userselect-list">\
                <% for(var i=0; i<value.length;i++) { %>\
                    <a href="javascript:;" class="ui-form-tag" style="display:inline-block"  data-uid="<%=value[i].uid%>"><%=value[i].username%></a>\
                <% } %>\
            </div>' :
                    '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
                '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        appendFieldElement: function(el) {
            this.$wrapper.append(el);
        },
        getField: function() {
            return this.$el.find('input')
        },
        getValue: function() { 
			return this.cfg.value ? this.cfg.value : []		
			//return this.getField().val() 
		},
        updateDataFromField: function() {
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        getData: function() {
            var ret = {};
            if (this.cfg.editable) {
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;
            }
            return ret;
        },
        initialized: function() {
            this.cfg.editable == true && this.$el.addClass('field-editable');
        },
        validate_num: function() {
            var str = this.getValue();
            if (str.length == 1 && str == "-") {
                return true;
            };
            if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length != 0) {
                alert(numverTips);
                return false;
            } else {
                return true;
            }
        },
        bindEvent: function() {
            var self = this;
            this.$el.find('button').on('tap', function() {
                var ids = [];
                var $select_ids = $(this).parent('div.field').find('a');
                $.each($select_ids, function(i) {
                    var uId = $(this).attr('data-uid');
                    ids.push(uId);
                })
				var _value = [];
				self.cfg.value = [];
                tMobileSDK.selectUser({
                    users: ids,
                    onSuccess: function(result) {
                        self.$el.find('.ui-userselect-list').empty();
                        for (var i = 0; i < result.length; i++) {
                            var user_html = '<a href="javascript:;" class="ui-form-tag" data-uid="' + result[i].uid + '">' + result[i].userName + '</a>';
                            self.$el.find('.ui-userselect-list').append(user_html);
							_value.push({
								uid: result[i].uid,
								username: result[i].userName
							})
                        }
						self.cfg.value = _value
                    },
                    onFail: function(data) {
                        alert("用户选人失败：" + data);
                    }
                });
            });
        },
        bindCalc: function(instances, rule, result, reminder) {
            var $input = this.getField(),
                self = this;
            $input.bind("keyup paste _calced", function() {
                var ret = [];
                ret.push(instances);
                ret.push(rule);
                ret.push(result);
                ret.push(reminder);
                var flag = self.validate_num();
                flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
            });
        }
    });
    exports.RUserselectField = window.RUserselectField = RUserselectField;
});
/*计算组件*/
define('RCalcCtrlField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var Math = require('math');
    var RCalcCtrlField = RTextField.extend({
        parseTpl: function(cfg) {
            var hidden = cfg.hidden ? " hide" : "";
            var readonly = cfg.writable ? "" : "readonly";
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%> '+hidden+'">\
  <label style="font-size:16px;"><%=name%>:</label>\
  <div class="field"><span><%=value%></span><input value="" name="<%=id%>" '+readonly+' type="hidden" /></div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        getField: function() {
            return this.$el.find('span')
        },
        setValue: function(str, reminder) {
            var res = math.eval(str);
            if (reminder !== "") {
                res = res.toFixed(reminder);
            } else {
                if (res != undefined) {
                    res = res.toFixed(2);
                }
            }
            this.$el.find('span').text(res);
        },
        updateDataFromField: function() {
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        getValue: function() { return this.getField().text() },
        initialized: function() {
            //在每条记录中过滤每个计算控件的cfg里新增对应的上级记录对象listManager，调用这个在listManager中实现获取对应的dom对象，并对dom绑事件
            //this.cfg.listManager && this.cfg.listManager.initCalc(this.cfg);
            this.cfg.listManager && this.initCalc(this.cfg);
        },
        initCalc: function(cfg) {
            var self = this,
                instances = this.cfg.listManager.instances,
                rule = this.cfg.options,
                reminder = 4;
            if (rule.indexOf('|') != -1) {
                reminder = rule.substr(rule.indexOf('|') + 1, 1); //余数
                rule = rule.substr(0, rule.indexOf('|')); //提纯公式
            }
            // var arr = rule.match(/(\d+)/g);
            var arr = rule.match(/\[(\d+)\]/g);
            var list_id = this.cfg.listManager.register.id;
            $.each(arr, function(k, v) {
                var v_reg = /\[(\/?\d*)\]/;
                v = v.replace(v_reg, "$1");
                var obj = instances['list_' + list_id + '_col_' + v];
                obj.bindCalc(instances, cfg.options, cfg.id, reminder);
            });
            self.cfg.listManager.get('wrapper').bind('calc', function() {
                self.calc(arguments[1], arguments[2], arguments[3], arguments[4]);
            });
        },
        calc: function(instances, rule, result, reminder) {
            var self = this,
                instances = instances ? instances : this.cfg.listManager.instances,
                rule = rule ? rule : this.cfg.options,
                result = result ? result : this.cfg.id,
                reminder = reminder ? reminder : 4;
            if (rule.indexOf('|') != -1) {
                reminder = rule.substr(rule.indexOf('|') + 1, 1); //余数
                rule = rule.substr(0, rule.indexOf('|')); //提纯公式
            }
            var arr = rule.match(/(\d+)/g);
            //var list_id = this.cfg.listManager.register.main[0].id;
            var list_id = this.cfg.listManager.register.id;
            $.each(instances, function(k, v) {
                $.each(arr, function(m, n) {
                    if (v.cfg.id == ('list_' + list_id + '_col_' + n)) {
                        var re = new RegExp("\\[" + n + "\\]", "ig");
                        var calv = 0;
                        calv = (v.getValue() == '' ? 0 : v.getValue());
                        //对小数点最后一位进行处理
                        if (calv && calv.indexOf(".") != -1 && calv.indexOf(".") == calv.length - 1) {
                            calv = calv.replace(".", "");
                        }
                        //对百分号的处理
                        if (calv && calv.indexOf("%") != -1) {
                            calv.replace("%", "");
                            calv = parseFloat(calv) / 100;
                        }
                        rule = rule.replace(re, calv);
                    }
                });
            });
            instances[result].setValue(rule, reminder);
            instances[result].getField().trigger('_calced');
        },
        bindCalc: function(instances, rule, result, reminder) {
            var self = this,
                $input = this.getField();
            $input.bind("_calced", function() {
                self.cfg.listManager.get('wrapper').trigger('calc', [instances, rule, result, reminder]);
            });
        }
    });
    exports.RCalcCtrlField = window.RCalcCtrlField = RCalcCtrlField;
});



/*附件组件*/
define('RAttachmentField', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var RAttachmentField = Base.extend({
        initialize: function(config) {
            RAttachmentField.superclass.initialize.call(this, config);
            this._config = config;
			this.cfg = config;
            this._render();
            this.$el = $('#f-field-' + this._config.pure_id);
            this.bindEvent();
        },

        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },
		
        reRender: function(new_config) {
            //生成新dom，替换旧dom
            var html = $($.parseTpl(this._config.template, new_config)).html();
            this.$el.html(html);
			this._config.value = new_config.value;
        },
		
        validate: function() {
            var value = this._config.value;
            var name  = this._config.name;
            //验证必填
            var required = this._config.required;
            if(required && value.length <= 0){
                alert('字段'+name+"为必填字段");
                return false;
            }else{
                return true;
            }
        },

        getValue: function() {
            //后台无法解析attach_url,先置空
            var values =  this._config.value;
            for (var i=0; i<values.length;i++){
                values[i].attach_url ={};
            }
            return values;
        },
        triggerCalc: function() {
            if(this._config.effect){
                this._config.fieldManager.calc(this._config.field_id);
            }
        },
        triggerValidate: function() {
            if(this._config.trigger){
                this._config.fieldManager.triggerTrig(this._config);
            }
        },
        bindEvent: function() {
            var self = this;
            var multi = self._config.multi;
            self.$el.find('button').on('click', function() {
                // var loader = $.loading({ content:'加载中...',});
                tMobileSDK.selectFile({
                    multiple: multi ? true : false,
                    onSuccess: function(data) {
                        //alert(JSON.stringify(result))
                        if(multi){
                            for (var i = 0; i < data.length; i++) {
                                var attach_html = '<a class="ui-attch-file-wrap pda_attach" href="javascript:;" _href="'+ data[i].url +'" data-url="' + data[i].url + '" data-id="' + data[i].id + '"><span>' + data[i].name.replace("*", "") + '</span><i class="ui-icon-close-progress icon-delete"></i></a>';
                                self.$el.find('.ui-attach-file').append(attach_html);
								var _value = self._config.value instanceof Array ? self._config.value : []
                                _value.push({
                                    "attach_id": data[i].id,
                                    "attach_name": data[i].name.replace("*",""),
									"attach_url": {
                                        "down": data[i].url,
                                        "view": data[i].url,
                                        "office_read": data[i].url
                                    }
                                })
								self.cfg.value = _value
								self._config.value = _value
                            }
                        }else {
                            var attach_html = '<a class="ui-attch-file-wrap pda_attach" href="javascript:;" _href="'+ data[0].url +'" data-url="' + data[0].url + '" data-id="' + data[0].id + '"><span>' + data[0].name.replace("*", "") + '</span><i class="ui-icon-close-progress icon-delete"></i></a>';
                            self.$el.find('.ui-attach-file').html(attach_html);
							var _value = self._config.value instanceof Array ? self._config.value : []
                            _value= [{
                                "attach_id": result[i].id,
                                "attach_name": result[i].name.replace("*",""),
								"attach_url": {
                                        "down": data[i].url,
                                        "view": data[i].url,
                                        "office_read": data[i].url
                                    }
                            }]
							self.cfg.value = _value
							self._config.value = _value

                        }
                        self.triggerCalc();
                        self.triggerValidate();
                        // loader.loading("hide");
                    },
                    onFail: function(data) {
                        alert("上传文件失败：" + data);
                        //loader.loading("hide");
                    }
                });

            })

            this.$el.delegate('i.ui-icon-close-progress', 'tap', function(e) {
                var attach_id = $(this).parent('.ui-attch-file-wrap').attr('data-id');
                $(this).parent('a.ui-attch-file-wrap').remove();
                self._config.value = self._config.value.filter(function(item){
                    if(item.attach_id != attach_id){
                        return item;
                    }
                })
				self.cfg.value = self._config.value
                self.triggerCalc();
                self.triggerValidate();
                e.stopPropagation();
            })
        }
    });
    exports.RAttachmentField = window.RAttachmentField = RAttachmentField;
});

//图片组件
define('RImageField', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var RImageField = Base.extend({
        initialize: function(config) {
            RImageField.superclass.initialize.call(this, config);
            this._config = config;
			this.cfg = config;
            this._render();
            this.$el = $('#f-field-'+this._config.pure_id);
            this.bindEvent();
        },
        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },
        reRender: function(new_config) {
            //生成新dom，替换旧dom
            var html = $($.parseTpl(this._config.template, new_config)).html();
            this.$el.html(html);
			this._config.value = new_config.value;
        },
        validate: function() {
            var value = this._config.value;
            var name  = this._config.name;
            //验证必填
            var required = this._config.required;
            if(required && value.length <= 0){
                alert('字段'+name+"为必填字段");
                return false;
            }else{
                return true;
            }
        },
        getValue: function() {
            //后台无法解析attach_url,先置空
            var values =  this._config.value;
            for (var i=0; i<values.length;i++){
                values[i].attach_url ={};
            }
            return values;
        },
        triggerCalc: function() {
            if(this._config.effect){
                this._config.fieldManager.calc(this._config.field_id);
            }
        },
        triggerValidate: function() {
            if(this._config.trigger){
                this._config.fieldManager.triggerTrig(this._config);
            }
        },
        bindEvent: function() {
            var self = this;
            var multi = self._config.multi
            this.$el.find('button').on('click', function(){
                tMobileSDK.chooseImage({
                    multiple: multi ? true : false,
                    max: 9,
                    fromCamera: false,
                    onSuccess: function(result){
                        if(multi){
                            for(var i=0;i<result.length; i++ ){
                                var img_html ='<a class="ui-attach-img-wrap pda_attach" data-id="'+ result[i].id +'" is_image="1" href="'+ result[i].url +'" _href="'+ result[i].url +'"><i class="ui-icon-close-progress icon-delete"></i><img class="ui-attach-img" src="'+result[i].url +'" /><p class="ui-attch-img-name" data-url="'+ result[i].href +'data-id="' + result[i].id + '">'+ result[i].name.replace("*","")+'</p></a>';
                                self.$el.find('.ui-images-list').append(img_html);
								var _value = self._config.value instanceof Array ? self._config.value : []
                                _value.push({
                                    "attach_id": result[i].id,
                                    "attach_name": result[i].name.replace("*",""),
                                    "attach_url": {
                                        "down": result[i].url,
                                        "view": result[i].url,
                                        "office_read": result[i].url
                                    }
                                })
								self.cfg.value = _value
								self._config.value = _value
                            }
                        }else{
                             var img_html ='<a class="ui-attach-img-wrap pda_attach" data-id="'+ result[0].id +'" is_image="1" href="'+ result[0].url +'" _href="'+ result[0].url +'"><i class="ui-icon-close-progress icon-delete"></i><img class="ui-attach-img" src="'+result[0].url +'" /><p class="ui-attch-img-name" data-url="'+ result[0].href +'data-id="' + result[0].id + '">'+ result[0].name.replace("*","")+'</p></a>';
                            self.$el.find('.ui-images-list').html(img_html);
							var _value = self._config.value instanceof Array ? self._config.value : []
                            _value = [{
                                "attach_id": result[i].id,
                                "attach_name": result[i].name.replace("*",""),
                                "attach_url": {
                                    "down": result[i].url,
                                    "view": result[i].url,
                                    "office_read": result[i].url
                                }
                            }]
							self.cfg.value = _value
							self._config.value = _value
                        }
                        self.triggerCalc()
                    },
                    onFail: function(result){
                        alert("上传图片失败：" + result);
                    }
                });
            })

            this.$el.delegate('i.ui-icon-close-progress', 'tap', function(e) {
                var attach_id = $(this).parent('.ui-attach-img-wrap').attr('data-id');
                $(this).parent('a.ui-attach-img-wrap').remove();
                self._config.value = self._config.value.filter(function(item){
                    if(item.attach_id != attach_id){
                        return item
                    }
                })
				self.cfg.value = self._config.value
                //alert(JSON.stringify(self._config.value))
                e.stopPropagation();
				return false;
            })
        }
    });
    exports.RImageField = window.RImageField = RImageField;
});

//二维码
define('RQrcodeField', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var RQrcodeField = Base.extend({
        initialize: function(config) {
            RQrcodeField.superclass.initialize.call(this, config);
            this._config = config;
            this._render();
            this.$el = $('#f-field-'+this._config.pure_id);
        },
        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },
        reRender: function(new_config) {
            var html = $($.parseTpl(this._config.template, new_config)).html();
            this.$el.html(html);
			this._config.value = new_config.value;
        }
    });
    exports.RQrcodeField = window.RQrcodeField = RQrcodeField;
});

//条形码
define('RBarcodeField', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var RBarcodeField = Base.extend({
        initialize: function(config) {
            RBarcodeField.superclass.initialize.call(this, config);
            this._config = config;
            this._render();
            this.$el = $('#f-field-'+this._config.pure_id);
        },
        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },
        reRender: function(new_config) {
            var html = $($.parseTpl(this._config.template, new_config)).html();
            this.$el.html(html);
			this._config.value = new_config.value;
        }
    });
    exports.RBarcodeField = window.RBarcodeField = RBarcodeField;
});



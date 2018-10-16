define('ListView', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var Fields = require('ReportFieldLoder');
    //var Fields = require('FieldLoader');
    var getFieldClass = function (c) {
        return Fields[c];
    };
    var ListViewManager = Base.extend({
        attrs: {
            wrapper: ''
        },
        initialize: function (config, store) {
            ListViewManager.superclass.initialize.call(this, config);
            this.$wrapper = $(this.get('wrapper'));
            this.$sum = this.$wrapper.parent('.list-wrapper').find('.list-sum');
            this.$wrapper.html("");
            this.$sum.html("");
            
            this.fieldmanager = config.fieldmanager;
            this.register = store || [];
            this.instances = {};
            this.lists = [];
            this.initMainBlocks(store.main);
            this.initDetailBlocks(store.detail);
        },
        getColSum: function(col){//返回指定列的合计值
            var res = this.lists[0].calcColSum(col);
            //console.log(res+"res");
            return res;
        },
        regist: function (cfg) {
            this.register.push(cfg);
        },
        getFieldClassName: function(type){
            type = type.replace(/-/g, "")
            type = type.firstUpperCase()
            //return ''+type+'Ctrl'
            return 'R'+type+'Field'
        },
        factory: function (cfg, $wrapper) {
            var self = this;
            var field_type = self.getFieldClassName(cfg.type);
            var klass = getFieldClass(field_type),
                field;
            if (klass) {
                cfg.listManager = this;//对每一条记录过滤每一种类型的控件过程中传入这一条记录对象
                field = new klass(cfg, $wrapper, self.fieldmanager);
            }
            return field;
        },
        initMainBlocks: function (blocks){
            var self = this;
            if(!(blocks)){
                return;
            }
            //var store = self.decorateStoreData(blocks[0]);
            $.each(blocks, function (i, block) {
                var $block = self.parseBlock(block);
                self.initFields(block.data, $block);
            });
            if(self.register.flag == 0){
                self.get('wrapper').trigger('calc');
            }
            //$('.list-wrapper .read_detail').last().addClass("last");
        },
        initDetailBlocks: function (detail){
            if(!(detail && detail[0].value)){
                return;
            }
            var store = this.decorateStoreData(detail[0]);
            this.initListManager(store, this.$wrapper);
        },
        decorateStoreData: function(data){
            if(!data){
                return ;
            }
            var store = data;
            var schema = {}; //列数据
            var items = [];   //行数据
            $.each(store.subFields, function (i, fields) {
                store.subFields[i].value = '';
                schema[fields.field_id] = fields;  
            }); 
            store.schema = store.subFields;
            $.each(store.value, function (i, lines) {
                var rows = [];
                $.each(lines.data, function (i, data) {
                    //item[ data.field_id ].value = data.value;
                    rows.push(
                        $.extend(true, {}, 
                            schema[data.field_id], 
                            { value: data.value})
                    );
                });
                store.value[i].data = rows;
            });
            return store;
        },
        initListManager: function(cfg, $wrapper){
            
            var self = this;
            if(!cfg){
                return;
            }
            this.lists.push(
                new ReportListFieldManager({ 
                    wrapper: $wrapper,
                    fieldmanager: self.fieldmanager
                }, cfg)
            );
        },
        initFields: function (fields, $block) {
            var self = this,
                register = this.register,
                instances = this.instances;
            $.each(fields, function (i, cfg) {
                var instance = self.factory(this, $block);
                if (instance && instance.get('field_id')) {
                    instances[instance.get('field_id')] = instance;
                }
            });
        },
        bindEvent: function () {
            var self = this,
                runId = this.get('runId'),
                flowId = this.get('flowId');
        },
        parseBlock: function(d){
            var tmpl = '\
<div class="reportBlock report-block-header tform clearfix">\
  <h3><%=name%></h3>\
</div>\
';
            var $block = $($.parseTpl(tmpl, d));
            this.$wrapper.append( $block );

            return $block;
        },
        createId: (function (i) {
            return function () {
                return i++;
            }
        })(0),
        save: function () {
            return this.$el.serializeArray();
        },
        appendFieldElement: function (el) {
            this.$wrapper.append(el);
        },
        destroy: function(){
            this.$el.remove();
        },
        updateFields: function(){
            
        },
        updateDataFromFields: function(){
            $.each(this.instances, function(i, field){
                field.updateDataFromField && field.updateDataFromField();
            });
        },
        serializeArray: function(){
            var self = this, ret = [], list = {};
            $.each(this.instances, function(i, field){
                field.getData && ret.push(field.getData());
            });
            $.each(this.lists || [], function(i, l){
                list['DATA_'+l.getId()] = (l.serializeArray());
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
        serializeSumArray: function(){
            var ret = null;
            $.each(this.lists || [], function(i, l){
                if(this.sumobj){
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
        initialize: function (config, store) {
            ListViewManager.superclass.initialize.call(this, config);
            this.$wrapper = $(this.get('wrapper'));
            this.$sumArea = this.$wrapper.parent('.list-wrapper').find('.list-sum');
            this.$save = $(this.get('savebtn'));
            this.register = store || [];
            this.instances = {};
            this.$el = this.parseBlock(store);
            this.initFields(store.value, this.$el);
            //(store.op == 'do') && this.buildCreateHelper();
            this.fieldmanager = config.fieldmanager;
            //console.log("2",this.fieldmanager);
            this.bindEvent();
            this.sumobj = null;
            //store.sumArr && this.createSum();
            store.sumArr && this.createSum();
            store.sumArr && this.$el.trigger('_calcSum');
        },
        calcColSum: function(col){
            var self = this;
            var sum = 0;
            $.each(self.instances, function(k, v){
            	if(v.cfg != undefined){
            	    var cur_index = parseInt(col)-1;
            	    var instance = v.cfg.item[cur_index];
            	    if(instance.value == ""){instance.value = 0;}
            	    if(self.validate_num(instance.value)){
                        sum += parseFloat(instance.value); 
                    }
            	}
            });
            return sum;
        },
        getId: function(){
            return this.register.id || this.register.field_id ;	
        },
        bindEvent: function(){
            var self = this;
            this.$wrapper.parents('.tag-listview').on('tab click', '.report-list-icon-create', function(){
                self.createItem();
            });
            this.$el.bind('_calcSum', function(){
                self.calcSum();
                self.fieldmanager.trigger('calc');
            });
            if(this.register.delete_op == 1){
               var _id = self.$wrapper.parent('.list-wrapper').attr('id');
            	Swiped.init({
			        query: ('#'+_id+' .list-item'),
			        list: true,
			        right: 100
			    });
            }; 
        },
        calcSum: function(){//合计计算
            var self = this;
            $.each(self.register.sumArr, function(i, obj){
                if(obj.sumflag == true){
                    var reminder = 4;
                    var rule = obj.options;
                    if(rule.indexOf('|') != -1){
                        reminder  = rule.substr(rule.indexOf('|')+1,1);//余数
                    }
                    
                    var sum = 0;
                    var flag = true;
                    $.each(self.instances, function(k, v){
                    	if(v.cfg != undefined){
                    	    var isright = true;
	                        $.each(v.cfg.item, function(k1, v1){
	                            if(v1.id == obj.id){
	                                if(v1.value == ""){v1.value = 0;}
	                                if(self.validate_num(v1.value)){
	                                    sum += parseFloat(v1.value); 
	                                }else{
	                                    isright = false;
	                                    return false;
	                                }
	                            };
	                        });
	                        if(isright == false){
	                            flag = false;
	                            return false;
	                        }
                    	}
                    });
                    if(flag == true){
                        obj.value = sum.toFixed(reminder);    
                    }
                }
            });
            
            self.sumobj.cfg.item = self.register.sumArr;
            self.sumobj.render();
        },
        
        validate_num: function(val){
			if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(val) && val.length!=0) {
				return false;
			}else{
				return true;
			}
       	},
       	
        createSum: function(){//生成合计区域
            var self = this;
            var id = (new Date).getTime();
            var sumArr = {
                id: id,
                index: 'sum',
                title: ( this.register.title ?  this.register.title + ' - ' : this.register.title ) + sumTitle,
                item: []
            };
            sumArr.item = $.extend(true, [], this.register.sumArr);
            var $el = self.$sumArea;
            $.each([sumArr], function (i, cfg) {
                var instance = self.sumfactory(this, $el);
                self.sumobj = instance;
            });
        },
        sumfactory: function (cfg, $block) {
            var klass = getFieldClass('RListField'),
                field;
            if (klass) {
                field = new klass(cfg, $block);
            }
            return field;
        },
        initFields: function (fields, $block) {
            var self = this,
                register = this.register,
                instances = this.instances;
            //列表无数据时，显示空提示，隐藏合计区域
            if(fields.length == 0){
                this.$sumArea.hide();
                return;
            }
            this.$sumArea.show();
            //有删除权限，显示删除叉号
            if(register.delete_op == 1){
            	self.$el.addClass('deletePriv');
           	}
            $.each(fields, function (i, cfg) {
                var instance = self.factory(this, $block);
                if (instance && instance.get('field_id')) {
                    instances[instance.get('field_id')] = instance;
                }
                instance.on('tap click', $.proxy(self.itemClickHandle, self) );
                instance.on('delete', $.proxy(self.itemDeleteHandle, self) );
            });
        },
        factory: function (cfg, $block) {
            var self = this;
            var klass = getFieldClass('RListField'),
                field;
            if (klass) {
            	cfg.delete_op = this.register.delete_op;
                field = new klass(cfg, $block, self.fieldmanager);
            }
            return field;
        },
        parseBlock: function(d){
/*             var tmpl = '\
<div class="reportBlock report-block-header clearfix">\
  <div class="report-block-header-op">'
    +((d.op == '1' && d.add_op == 1) ? '<a class="report-list-icon-create" href="javascript:void(0);"></a>' : '')+        
'\
</div></div>\
'; */
           var tmpl = '\
<div class="reportBlock report-block-header clearfix">\
</div></div>\
';

            var $block = $($.parseTpl(tmpl, d));
            this.$wrapper.append( $block );

            if(d.op == '1' && d.add_op == 1){
                this.$wrapper.parents('.tag-listview').find('.report-list-icon-create').addClass('active');
            }
            return $block;
        },
        buildCreateHelper: function(){
            this.$el.append('<div class="read_detail clearfix report-create-helper"><em>'+createItemBtn+'</em></div>');  
        },
        createItem: function(){
            var self = this,
                id   = (new Date).getTime(),
                createText = '新建';
            var schema = {
                field_id: id,
                index: 'new',
                editable: 1,
                name: ( this.register.name ?  this.register.name + ' - ' : this.register.name ) + createText,
                subFields: this.register.subFields,
                value: []
            };
            schema.value = $.extend(true, [], this.register.schema);
            //新建字段赋予编辑权限
            $.each(schema.value, function(i, v){
                schema.value[i].editable = 1;
            });
            schema.data = schema.value;
            this.initFields([schema], this.$el);
            this.itemClickHandle(this.instances[id], {
                close: function(){
                    this.$el.remove();
                    this.destroy();
                },
                save: function(){
                    this.state = 'add';
                    self.register.sumArr && self.$el.trigger('_calcSum');
                }
            });
            
        },
        itemDeleteHandle: function(item, callbacks){
            var self = this;
            if(this.instances[item.cfg.id]){
                delete this.instances[item.cfg.id];
            }
            item.$el.remove();
            self.fieldmanager && self.fieldmanager.trigger('calc');
            self.register.sumArr && self.$el.trigger('_calcSum');
        },
        itemClickHandle: function(item, callbacks){
        	var self = this;
            var saveItemBtn = "保存",
                closeItemBtn  = '关闭';
        	//无编辑权限有新建权限，不能编辑已有的数据和新建保存的数据，只能在新建时修改对应数据
        	if(item.get('index') == 'new' || this.register.edit_op == 1){
	            var flag = 0;
	            if(item.get('index') == 'new' || item.get('index') == 'sum'){
	                flag = 1;
	            };
                item.cfg.name =  ( self.register.name ?  self.register.name + ' - ' : self.register.name ) + '编辑',
                $.each(item.cfg.data,function(i,data){
                    data.editable = self.register.edit_op;
                })
                
                var $panel = $('#scroller_list_detail');
                $panel.empty();
                pageTo('list_detail');
	            var $inner = $('<div>').appendTo($panel);
	            var $btnwrapper = $("<div style='text-align: center;margin-top:15px;'>").appendTo($panel);
	            var $save = $('<button>').text(saveItemBtn).addClass("reportbtn").appendTo($btnwrapper);//保存
	            var $close = $('<button>').text(closeItemBtn).addClass("reportclose").appendTo($btnwrapper);
	            $panel.append('<div class="holder-40"></div>');
	            $close.click(function(){
                    $panel.empty();
                    history.back();
	                callbacks && callbacks.close && callbacks.close.call(item);
	                if(self.$wrapper.find('.tag-RListField').length == 0){
            			self.$sumArea.hide();
	                }
	            });
	            $save.click(function(){
	                l.updateDataFromFields();
	                item.render();
	                self.fieldmanager.trigger('calc');
	                self.register.sumArr && self.$el.trigger('_calcSum');
	                $panel.empty();
                    history.back();
                    saveFlag = 0;
	                callbacks && callbacks.save && callbacks.save.call(item);
	            });
	            //console.log('3',self.fieldmanager);
	            //点击条目打开编辑详情
	            l = new ListViewManager({
	                wrapper: $inner,
	                fieldmanager: self.fieldmanager
	            }, {
                    // subFields: self.register.subFields,
                    // name: self.register.name,
                    main: [item.cfg],
	                field_id: this.getId(),
	                flag: flag
	            });
	            setTimeout(function(){
	                $panel.show();
	            }, 1)
        	}else{
        		return false;
        	}
        },
        serializeArray: function(){
    	    var str = "";
            $.each(this.instances, function(i, field){
            	var data = field.getData() 
                //data && ret.push(data);
		        data && (str+=field.getData());
            });
             return str;
        }

    });
    exports.ListViewManager = window.ListViewManager = ListViewManager;
});
define('ReportFieldLoder', ["RTextField", "RDateField", "RCheckboxField", "RRadioField", "RCalcCtrlField", "RRawField", "RTextareaField", "RSelectField", "RListField", "RNumberField","RCurrencyField","RDeptselectField","RUserselectField"], function (require, exports, module) {
    var depends = module.dependencies;
    for (var i in depends) {
        var mod = require(depends[i]);
        exports[depends[i]] = mod ? mod[depends[i]] : null;
    }
});
define('RNumberField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RNumberField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    ' + (cfg.editable == 1 ?
      '<input value="<%=value%>" name="<%=id%>" type="text" style="font-size:15px; width:65%;"/><p></p>'
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        getField: function(){ 
            return this.$el.find('input')
        },
        getValue: function(){ return this.getField().val() },
        updateDataFromField: function(){
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);            
        },
        getData: function(){
            var ret = {};
            if(this.cfg.editable){
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;   
            }
            return ret;
        },
        initialized: function(){
            this.cfg.editable == "1" && this.$el.addClass('field-editable');
        },
        validate_num: function(){
			var str = this.getValue();
			if(str.length == 1 && str == "-"){
				return true;
			};
			if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length!=0) {
				alert('请输入数字'); 
                this.$el.find('input').val('');
				return false;
			}else{
				return true;
			}
       	},
        bindEvent: function(){
            var $input = this.getField(),
                self   = this;
            $input.bind("keyup paste _calced", function(){
                self.validate_num();
            });
        },
        bindCalc: function(instances, rule, result, reminder){
            var $input = this.getField(),
                self   = this;
            $input.bind("keyup paste _calced", function(){
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
define('RCurrencyField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RCurrencyField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    ' + (cfg.editable == 1 ?
      '<input value="<%=value%>" name="<%=id%>" type="text" style="font-size:15px; width:65%;"/><p></p>'
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
       appendFieldElement: function(el){
            this.$wrapper.append(el);
        },
        getField: function(){ 
            return this.$el.find('input')
        },
        getValue: function(){ return this.getField().val() },
        updateDataFromField: function(){
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);            
        },
        getData: function(){
            var ret = {};
            if(this.cfg.editable){
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;   
            }
            return ret;
        },
        initialized: function(){
            this.cfg.editable == "1" && this.$el.addClass('field-editable');
        },
        validate_num: function(){
			var str = this.getValue();
			if(str.length == 1 && str == "-"){
				return true;
			};
			if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length!=0) {
				alert('请输入数字'); 
                this.$el.find('input').val('');
				return false;
			}else{
				return true;
			}
       	},
        bindEvent: function(){
            var $input = this.getField(),
                self   = this;
            $input.bind("keyup paste _calced", function(){
                self.validate_num();
            });
        },
        bindCalc: function(instances, rule, result, reminder){
            var $input = this.getField(),
                self   = this;
            $input.bind("keyup paste _calced", function(){
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
define('RDeptselectField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RDeptselectField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    ' + (cfg.editable == 1 ?
       '<button type="button" class="ui-btn">选择</button><div class="ui-deptselect-list">\
                <% for(var i=0; i<value.length;i++) { %>\
                    <a href="javascript:;" class="ui-form-tag" style="display:inline-block" data-deptid="<%=value[i].dept_id%>"><%=value[i].dept_name%></a>\
                <% } %>\
            </div>\
    '
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        appendFieldElement: function(el){
            this.$wrapper.append(el);
        },
        getField: function(){ 
            return this.$el.find('input')
        },
        getValue: function(){ return this.getField().val() },
        updateDataFromField: function(){
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);            
        },
        getData: function(){
            var ret = {};
            if(this.cfg.editable){
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;   
            }
            return ret;
        },
        initialized: function(){
            this.cfg.editable == "1" && this.$el.addClass('field-editable');
        },
        validate_num: function(){
			var str = this.getValue();
			if(str.length == 1 && str == "-"){
				return true;
			};
			if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length!=0) {
				alert(numverTips); 
				return false;
			}else{
				return true;
			}
       	},
        bindEvent: function(){
            var self = this;
            this.$el.find('button').on('click', function(){
                var depts =[]; 
                var $select_ids = $(this).parent('div.field').find('a');
                $.each($select_ids,function(i){
                    var deptId = $(this).attr('data-deptid')
                    depts.push(deptId);
                })                
                tMobileSDK.selectDept({ 
                    depts:depts,
                    onSuccess: function(result){
                        self.$el.find('.ui-deptselect-list').empty();
                        for(var i=0; i<result.length; i++ ){
                            var dept_html = '<a href="javascript:;" class="ui-form-tag" data-deptid="'+ result[i].deptId+'">'+ result[i].deptName+'</a>';   
                            self.$el.find('.ui-deptselect-list').append(dept_html);
                        }
                    },
                    onFail: function(data){
                        alert("部门选人失败：" + data);
                    }
                }); 
            }); 
        },
        bindCalc: function(instances, rule, result, reminder){
            var $input = this.getField(),
                self   = this;
            $input.bind("keyup paste _calced", function(){
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
define('RUserselectField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RUserselectField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    ' + (cfg.editable == 1 ?
      
    '<button type="button" class="ui-btn">选择</button>\
      <div class="ui-userselect-list">\
                <% for(var i=0; i<value.length;i++) { %>\
                    <a href="javascript:;" class="ui-form-tag" style="display:inline-block"  data-uid="<%=value[i].uid%>"><%=value[i].username%></a>\
                <% } %>\
            </div>'
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
       appendFieldElement: function(el){
            this.$wrapper.append(el);
        },
        getField: function(){ 
            return this.$el.find('input')
        },
        getValue: function(){ return this.getField().val() },
        updateDataFromField: function(){
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);            
        },
        getData: function(){
            var ret = {};
            if(this.cfg.editable){
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;   
            }
            return ret;
        },
        initialized: function(){
            this.cfg.editable == "1" && this.$el.addClass('field-editable');
        },
        validate_num: function(){
			var str = this.getValue();
			if(str.length == 1 && str == "-"){
				return true;
			};
			if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length!=0) {
				alert(numverTips); 
				return false;
			}else{
				return true;
			}
       	},
        bindEvent: function(){
            var self = this;
            this.$el.find('button').on('click', function(){
                var ids = [];
                var $select_ids = $(this).parent('div.field').find('a');
                $.each($select_ids,function(i){
                    var uId = $(this).attr('data-uid');
                    ids.push(uId);
                })
                tMobileSDK.selectUser({    
                    users: ids,
                    names:names,
                    onSuccess: function(result){
                        self.$el.find('.ui-userselect-list').empty();
                        for(var i=0; i<result.length; i++ ){
                            var user_html = '<a href="javascript:;" class="ui-form-tag" data-uid="'+ result[i].uid+'">'+ result[i].userName+'</a>';   
                            self.$el.find('.ui-userselect-list').append(user_html);
                        }
                    },
                    onFail: function(data){
                        alert("用户选人失败：" + data);
                    }
                }); 
            }); 
        },
        bindCalc: function(instances, rule, result, reminder){
            var $input = this.getField(),
                self   = this;
            $input.bind("keyup paste _calced", function(){
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
define('RTextField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var RTextField = Base.extend({
        attrs: {
            id: null
        },
        initialize: function (cfg, $wrapper) {
            cfg.fieldtype = cfg.type;
            cfg.value = cfg.value || '';
            cfg.id = cfg.id || cfg.field_id;
            RTextField.superclass.initialize.call(this, cfg);
            this.cfg = cfg;
            this.$el = $(this.parseTpl(cfg));
            this.$field = this.getField();
            this.$wrapper = $wrapper;
            if(!cfg.secret){
            	this.appendFieldElement(this.$el);
            }
            this.bindEvent();
            this.initialized();
        },

        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    ' + (cfg.editable == 1 ?
      '<input value="<%=value%>" name="<%=id%>" type="text" style="font-size:15px; width:65%;"/>'
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        appendFieldElement: function(el){
            this.$wrapper.append(el);
        },
        getField: function(){ 
            return this.$el.find('input')
        },
        getValue: function(){ return this.getField().val() },
        updateDataFromField: function(){
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);            
        },
        getData: function(){
            var ret = {};
            if(this.cfg.editable){
                ret.name = this.cfg.id;
                ret.value = this.getValue();
                ret.displaystyle = this.cfg.displaystyle;   
            }
            return ret;
        },
        initialized: function(){
            this.cfg.editable == "1" && this.$el.addClass('field-editable');
        },
        validate_num: function(){
			var str = this.getValue();
			if(str.length == 1 && str == "-"){
				return true;
			};
			if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(\.)?(?:\d+)?%?$/.test(str) && str.length!=0) {
				alert(numverTips); 
				return false;
			}else{
				return true;
			}
       	},
        bindEvent: function(){
        },
        bindCalc: function(instances, rule, result, reminder){
            var $input = this.getField(),
                self   = this;
            $input.bind("keyup paste _calced", function(){
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
define('RDateField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RDateField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    ' + (cfg.editable == 1 ?
      '<input value="<%=value%>" name="<%=id%>" type="text" />'
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        initialized: function(){
        	var self = this;
            if(this.cfg.editable){
                var $input = this.getField();
                $input.attr("readonly","true");
                if(this.cfg.format == "yyyy-MM-dd"){
                    $input.attr({"data-type":"date"});
                }else{
                    $input.attr({"data-type":"datetime"});
                }
                $input.focus(function(){
                    if($input.attr("data-type") == "date"){
                        $('[data-type="date"]').mobiscroll().date({
                            theme: 'ios7',
                            lang: 'zh',
                            dateFormat: 'yy-mm-dd',
                            display: 'bottom',
                            dateOrder: 'ddMMyy',
                            mode: 'scroller'
                        });
                    }
                    else if($input.attr("data-type") == "datetime"){
                        $('[data-type="datetime"]').mobiscroll().datetime({
                            theme: 'ios7',
                            lang: 'zh',
                            dateFormat: 'yy-mm-dd',
                            display: 'bottom',
                            mode: 'scroller'
                        });
                    }
                    else if($input.attr("data-type") == "time"){
                        $('[data-type="time"]').mobiscroll().time({
                            theme: 'ios7',
                            lang: 'zh',
                            display: 'bottom',
                            dateFormat: 'hh:mm',
                            mode: 'scroller'
                        });
                    }
                });  
                this.cfg.editable == "1" && this.$el.addClass('field-editable');
            }
        }

    });
    exports.RDateField = window.RDateField = RDateField;
});
define('RCalcCtrlField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var Math = require('math');
    var RCalcCtrlField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field"><span><%=value%></span><input value="" name="<%=id%>" type="hidden" /></div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        getField: function(){ 
            return this.$el.find('span')
        },
        setValue: function(str, reminder){
            var res = math.eval(str);
            if(reminder !== ""){
            	res = res.toFixed(reminder);
            }else{
                if(res!=undefined){
                    res = res.toFixed(2);    
                }
            }
            this.$el.find('span').text(res);
        },
        updateDataFromField: function(){
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        getValue: function(){ return this.getField().text() },
        initialized: function(){
            //在每条记录中过滤每个计算控件的cfg里新增对应的上级记录对象listManager，调用这个在listManager中实现获取对应的dom对象，并对dom绑事件
            //this.cfg.listManager && this.cfg.listManager.initCalc(this.cfg);
            this.cfg.listManager && this.initCalc(this.cfg);
        },
        initCalc: function(cfg){
            var self = this,
                instances = this.cfg.listManager.instances,
                rule      = this.cfg.options,
                reminder  = 4;
            if(rule.indexOf('|')!=-1){
                reminder  = rule.substr(rule.indexOf('|')+1,1);//余数
                rule = rule.substr(0,rule.indexOf('|'));//提纯公式
            }
            // var arr = rule.match(/(\d+)/g);
            var arr = rule.match(/\[(\d+)\]/g);
            var list_id = this.cfg.listManager.register.id;
            $.each(arr, function(k, v){
                var v_reg =/\[(\/?\d*)\]/;
                v = v.replace(v_reg,"$1");
                var obj = instances['list_'+list_id+'_col_'+v];
                obj.bindCalc(instances, cfg.options, cfg.id, reminder);
            });
            self.cfg.listManager.get('wrapper').bind('calc', function(){
                self.calc(arguments[1],arguments[2],arguments[3],arguments[4]);
            });
        },
        calc: function(instances, rule, result, reminder){
            var self      = this,
                instances = instances? instances: this.cfg.listManager.instances,
                rule      = rule? rule: this.cfg.options,
                result    = result? result: this.cfg.id,
                reminder  = reminder? reminder: 4;
            if(rule.indexOf('|')!=-1){
                reminder  = rule.substr(rule.indexOf('|')+1,1);//余数
                rule = rule.substr(0,rule.indexOf('|'));//提纯公式
            }
            var arr  = rule.match(/(\d+)/g);
            //var list_id = this.cfg.listManager.register.main[0].id;
            var list_id = this.cfg.listManager.register.id;
            $.each(instances, function(k, v){
                $.each(arr, function(m, n){
                    if(v.cfg.id == ('list_'+list_id+'_col_'+n)){
                        var re = new RegExp("\\["+n+"\\]","ig");
                        var calv = 0;
                        calv = (v.getValue()=='' ? 0: v.getValue());
                        //对小数点最后一位进行处理
                        if(calv && calv.indexOf(".") != -1 && calv.indexOf(".") == calv.length-1)
                        {
                            calv = calv.replace(".", "");
                        }
                        //对百分号的处理
                        if(calv && calv.indexOf("%") != -1)
                        {
                            calv.replace("%", "");
                            calv = parseFloat(calv)/100;
                        }
                        rule = rule.replace(re,calv);
                    }
                });
            });
            instances[result].setValue(rule, reminder);
            instances[result].getField().trigger('_calced');
        },
        bindCalc: function(instances, rule, result, reminder){
            var self   = this,
                $input = this.getField();
            $input.bind("_calced", function(){
                self.cfg.listManager.get('wrapper').trigger('calc', [instances, rule, result, reminder]);
            });
        }
    });
    exports.RCalcCtrlField = window.RCalcCtrlField = RCalcCtrlField;
});
define('RSelectField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RSelectField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
  ' + (cfg.editable == 1 ?
      '<select value="<%=value%>" name="<%=id%>" title="<%=name%>"></select>'
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        renderOption: function (cfg) {
            var options = cfg.options,
                opData = options ? options : [],
                html = [];
            $.each(opData, function () {
                if (this == '') {
                    return
                }
                var selected = this == cfg.value ? ' selected ' : '';
                html.push('<option value="' + this + '" ' + selected + '>' + this + '</option>');
            });
            this.$field.append(html.join(''));
        }, 
        getField: function(){ 
            if(this.cfg.editable == "1"){
                return this.$el.find('select')
            }else{
                return this.$el.find('input')
            }
        },
        getValue: function(){
            if(this.cfg.editable == "1"){
                var this_obj = this.getField().find('option');
                for(var this_count = 0; this_count < this_obj.length; this_count++)
                {
                    if($(this_obj[this_count]).prop("selected") === true){
                        return $(this_obj[this_count]).val();
                    }
                }
                // return this.getField().find('option:selected').val()
            }else{
                return this.getField().val()
            }
        },
        initialized: function(){
            if(this.cfg.editable && this.cfg.editable == "1"){
                this.renderOption(this.cfg);
                this.$el.addClass('field-editable');
            }
        },
        bindCalc: function(instances, rule, result, reminder){
            var self    = this,
                $select = self.getField(),
                ret     = [];
            ret.push(instances);
            ret.push(rule);
            ret.push(result);
            ret.push(reminder);
            if(self.cfg.editable == "1"){
                $select.bind("change _calced", function(){
                	var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            }
            else{
                $select.bind("_calced", function(){
                	var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            }
        }
    });
    exports.RSelectField = window.RSelectField = RSelectField;
});
define('RListField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RListField = RTextField.extend({
        attrs: {
            id: null
        },
        initialize: function (cfg, $wrapper) {
            RTextField.superclass.initialize.call(this, cfg);
            var self = this;
            this.cfg = cfg;
            this.$el = $(this.parseTpl(cfg));
            this.$wrapper = $wrapper;
            this.appendFieldElement(this.$el);
            this.bindEvent();
            this.$el.on('tab click', function(){
                if(!window.stopDelete){
                    self.trigger('tab click', self); 
                }
                window.stopDelete = false;
            });
        },
        bindEvent: function(){
            var self = this;
            this.$el.delegate('.read_detail_delete', 'tab click', function(e){
                e.stopPropagation();
                var delflag = confirm(confirmDelete); 
                if (delflag == true){ 
                    self.trigger('delete', self);
                    saveFlag = 0;
                    window.stopDelete = true;
                    if($('#scroller_list_edit').find('.read_detail').length == 0){
                        $('#scroller_list_edit').find('#emptyTips').css('display', 'block');
                    }
                    return true; 
                } 
                else{
                    window.stopDelete = true;
                    return false; 
                } 
            });
        },
        parseTpl: function (cfg) {
//console.log(cfg,cfg.index)
            var id = this.get('id'),
                sum_text = "合计",
                add_text = "新增";
            var tmpl = (function(){
                var fields = [];
                
                var title = "";
                if(cfg.index == "sum"){
                    title = sum_text;
                }else if(cfg.index == "new"){
                    title = add_text;
                }else{
                    title = cfg.index;
                }
                
                
                $.each(cfg.data, function(i, v){
                    //console.log(v);
                    if(v.secret){
                        if(i == 0){
                            fields.push('<b class="no">'+title+'</b>');
                        }
                    }else{
                        if(i == 0){
                            fields.push('<b class="no">'+title+'</b><em>' + v.name + ': ' + emptyHelper(v.value) + '</em>');
                            /*fields.push('<b class="no">'+(cfg.index)+'</b><em>' + v.name + ': ' + emptyHelper(v.value) + '</em>');*/
                        }else{
                            fields.push('<em>' + v.name + ': ' + emptyHelper(v.value,v) + '</em>');
                        }
                    }
                });
                var newClass = (cfg.index == 'new')? 'list-item-new':'';
                var html = '';
                var deletehtml = '';
                var read_detail_delete = "删除";
                if(cfg.index == 'new'){
                    deletehtml = '<div class="read_detail_delete"><span>'+ read_detail_delete +'</span></div>';
                }
                if(cfg.delete_op !=undefined && cfg.delete_op != 0){
                    deletehtml = '<div class="read_detail_delete"><span>'+ read_detail_delete +'</span></div>';
                }
                
                html = '<div class="read_detail clearfix tag-RListField"><div class="clearfix list-item '+ newClass +'">'+ fields.join('\n\r') +'</div>'+deletehtml+'</div>';
                return html;
            })();
            return $.parseTpl(tmpl, cfg)
        },
        render: function(){
            this.$el.html( $(this.parseTpl(this.cfg)).html() );
            if(this.cfg.delete_op == 1){
            	Swiped.init({
			        query: '.list-content .list-item',
			        list: true,
			        right: 100
			    });
			}else{
				if(this.cfg.index == 'new'){
					$('body').addClass('deletePriv');
					Swiped.init({
				        query: '.list-content .list-item-new',
				        list: true,
				        right: 100
				    });
				}
			}
        },
        appendFieldElement: function(el){
            this.$wrapper.append(el);
        },
        getData: function(){
        	if(this.cfg == undefined){
        		return ;
        	}
            //var ret = [];
            var data_str = "";
            $.each(this.cfg.item, function(){
                if(this.fieldtype == "RRadioField" || this.fieldtype == "RCheckboxField")
                {
                    var the_value = this.value;
                    the_value = the_value.replace(/\r\n/g, "&lt;br&gt;");
                    data_str += this.value + "`";
                }
                else if(this.fieldtype == "RTextareaField")
                {
                    var the_value = this.value;
                    the_value = the_value.toString();
		    the_value = the_value.replace(/`/g, '[0x60]');
                    var textarea_html = the_value + "`";
                    textarea_html = textarea_html.replace(/\r\n/g, "&lt;br&gt;");
                    textarea_html = textarea_html.replace(/\n/g, "&lt;br&gt;");
                     data_str += textarea_html;
                }
                else
                {
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
    function emptyHelper(c,v){
    	if(v && v.fieldtype == "RCalcCtrlField"){
    		return c == '' ? 0 : c;
    	}else if( c instanceof Array && v && v.type == "user-select"){
            var opts = '';
            $.each(c,function(i,value){
                opts = opts.length === 0 ? value.username  : opts + "," + value.username ;
            })
            return c =opts
        }else if( c instanceof Array && v && v.type == "dept-select"){
            var opts = '';
            $.each(c,function(i,value){
                opts = opts.length === 0 ?  value.dept_name : opts + "," + value.dept_name;
            })
            return c =opts   
        }else{
        	return (c == '' || c == null) ? ' - ' : c;
    	}
    }
    exports.RListField = window.RListField = RListField;
});
define('RCheckboxField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RCheckboxField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    ' + (cfg.editable == 1 ?      
      '<div class="checkWrapper"></div>'
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        getField: function(){ 
            if(this.cfg.editable == "1"){
                return this.$el.find('.checkWrapper') 
            }else{
                return this.$el.find('input') 
            }
        },
        getValue: function(){
            var cvalue = 0;
            this.getField().find('input[type="checkbox"]:checked').each(function(){
            	var $this = $(this);
                var val = $this.parent('label').find('span').text();
                cvalue += parseFloat(val);
            });
            cvalue = cvalue.toString();
            return cvalue;
        },
        getValueString: function(){
            if(this.cfg.editable == "1"){
                var str = "";
                this.getField().find('input[type="checkbox"]:checked').each(function(){
                	var $this = $(this);
                    var val = $this.parent('label').find('span').text();
                    str += val + ',';
                });
                str = str.substr(0, str.length-1);
                return str;
            }
            else{
                return this.getField().val()
            }
        },
        updateDataFromField: function(){
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValueString();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        renderItem: function(cfg){
            this.$field = this.getField();
            var options = cfg.options,
                opData = options ? options : [],
                default_items = cfg.value,
                defaultData = default_items ? default_items.split(',') : [],
                html = [];
            $.each(opData, function (k, v) {
                if (this == '') {
                    return;
                }
                var checked = $.inArray(v, defaultData)==-1 ? '' : 'checked';
                html.push('<label for="'+ cfg.id+'_'+k +'" class="ui-checkbox"><input type="checkbox" name="'+cfg.id+'" id="'+ cfg.id+'_'+k +'" ' + checked + ' /><span>'+ this +'</span></label>');
            });
            this.$field.append(html.join(''));
        },
        initialized: function(){ 
            if(this.cfg.editable && this.cfg.editable == "1"){ 
                this.renderItem(this.cfg);
                this.$el.addClass('field-editable');

            }
        },
        bindCalc: function(instances, rule, result, reminder){
            var self   = this,
                $field = self.getField(),
                ret = [];
            ret.push(instances);
            ret.push(rule);
            ret.push(result);
            ret.push(reminder);
            if(self.cfg.editable == "1"){
                $field.find('input').each(function(){
                    var $this = $(this);
                    $this.on('tap click _calced', function(event){
                        setTimeout(function(){ 
                        	var flag = self.validate_num();
                            flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                        },1000);
                    });
                }); 
            }else{
                $field.on('_calced', function(event){ 
                	 var flag = self.validate_num();
                     flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            } 
        }
    });
    exports.RCheckboxField = window.RCheckboxField = RCheckboxField;
});
define('RRadioField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RRadioField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    ' + (cfg.editable == 1 ?      
      '<div class="radioWrapper"></div>'
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        getField: function(){ 
            if(this.cfg.editable == "1"){
                return this.$el.find('.radioWrapper') 
            }else{
                return this.$el.find('input') 
            }
        },
        getValue: function(){
            if(this.cfg.editable == "1"){
            	var str = '';
            	if($("input[name='"+this.cfg.id+"']:checked").length > 0){
            		str = $("input[name='"+this.cfg.id+"']:checked").parent('label').find('span').text();
            	}
                return str;
            }else{
                return this.getField().val();
            }
        },
        updateDataFromField: function(){
            var oldvalue = this.cfg.value;
            this.cfg.value = this.getValue();
            this.cfg.isChanged = !(oldvalue == this.cfg.value);
        },
        renderItem: function(cfg){
            var options = cfg.options,
                opData = options ? options : [],
                html = [];
            this.$field = this.getField();
            $.each(opData, function (k, v) {
                if (this == '') {
                    return
                }
                var checked = this == cfg.value ? ' checked ' : '';
                html.push('<label for="'+ cfg.id+'_'+k +'" class="ui-radio"><input type="radio" name="'+cfg.id+'" id="'+ cfg.id+'_'+k +'" ' + checked + ' /><span>'+ this +'</span></label>');
            });
            this.$field.append(html.join(''));
        },
        initialized: function(){
            if(this.cfg.editable && this.cfg.editable == "1"){ 
                this.renderItem(this.cfg);
                this.$el.addClass('field-editable');
            }
        },
        bindCalc: function(instances, rule, result, reminder){
            var self   = this,
                $field = self.getField(),
                ret = [];
            ret.push(instances);
            ret.push(rule);
            ret.push(result);
            ret.push(reminder);
            if(self.cfg.editable == "1"){  
                $field.find('input').each(function(){
                    var $this = $(this);
                    $this.on('tap click _calced', function(event){
                        setTimeout(function(){
                        	var flag = self.validate_num();
                            flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                        },1000);
                    });
                });  
            }else{
                $field.on('_calced', function(event){
                	var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            }
        }
    });
    exports.RRadioField = window.RRadioField = RRadioField;
});
define('RTextareaField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RTextareaField = RTextField.extend({
        parseTpl: function (cfg) {
            if(cfg.editable == 1){
                cfg.value = this.lfDecode(cfg.value);
            }else{
                cfg.value = cfg.value.replace(/&lt;br&gt;/g, "<br>");
            }
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    ' + (cfg.editable == 1 ?      
      '<textarea name="<%=id%>" value="<%=value%>" style="font-size:15px; width:100%; height:200px; line-height:20px;"><%=value%></textarea>'
    : '<span><%=value%></span><input value="<%=value%>" name="<%=id%>" type="hidden" />') +
  '</div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        initialized: function(){
            if(this.cfg.editable){ 
                this.cfg.editable == "1" && this.$el.addClass('field-editable');
            }
        },
        lfEncode: function(s){
            s = s.replace(/\r\n/g, '<br>');
            s = s.replace(/\n/g, '<br>');
            return s;
        },
        lfDecode: function(s){
            s = s.replace(/&lt;br&gt;/ig, "\r\n");
            s = s.replace(/<br>/ig, "\r\n");
            return s;
        },
        getField: function(){ 
            if(this.cfg.editable == "1"){
                return this.$el.find('textarea')
            }else{
                return this.$el.find('input')
            }   
        },
        getValue: function(){ 
            if(this.cfg.editable == "1"){
                return this.lfEncode(this.getField().val());
            }else{
                return this.$el.find('input').val();
            }   
        },
        bindCalc: function(instances, rule, result, reminder){
            var self   = this,
                $input = this.getField(),
                ret = [];
            ret.push(instances);
            ret.push(rule);
            ret.push(result);
            ret.push(reminder);
            if(self.cfg.editable == "1"){
                $input.bind("keyup paste _calced", function(){
                	var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            }else{
                $input.bind("_calced", function(){
                	var flag = self.validate_num();
                    flag && self.cfg.listManager.get('wrapper').trigger('calc', ret);
                });
            }
        }
    });
    exports.RTextareaField = window.RTextareaField = RTextareaField;
});
define('RRawField', function (require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var RTextField = require('RTextField').RTextField;
    var RRawField = RTextField.extend({
        parseTpl: function (cfg) {
            var tmpl = '\
<div class="read_detail clearfix tag-<%=fieldtype%>">\
  <em style="font-size:16px;"><%=name%>:</em>\
  <div class="field">\
    <input value="<%=value%>" name="<%=id%>" type="hidden" />\
  </div>\
</div>\
';
            return $.parseTpl(tmpl, cfg)
        },
        initialized: function(){
        }
    });
    exports.RRawField = window.RRawField = RRawField;
});


define('ListCtrl', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var ListView = require('ListView');
    var ListCtrl = Base.extend({
        initialize: function(config) {
            ListCtrl.superclass.initialize.call(this, config);
            this._config = config;
            this._config.required = false;
            //this._config.hidden = false;
            this._render();
            this.$el = $('#f-field-' + this._config.pure_id);
            this.$listcontent = $('#f-field-list-' + this._config.pure_id);
            this.bindEvent();
            //self.listViewManager = null;
            this.newListView();
        },

        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },
        validate: function() {
            return true;
        },
        reRender: function(data) {
			var self = this;
            var ListViewManager = ListView.ListViewManager;

            if(window.listHandleIndex){
                self.listViewManager.triggerRerender(data);
				this.bindEvent();
            }else{
                self._config = data;
                self.listViewManager = new ListViewManager({
                    wrapper: self.$listcontent,
                    fieldManager: self._config.fieldManager
                }, { detail: [self._config] });
            }
            return;
            //var self = this;
            //var ListViewManager = ListView.ListViewManager;
			//self.listViewManager.triggerRerender(data)
            //this.bindEvent();
        },

        getValue: function() {
            var value = $.extend([],this._config.value,true);    
            value.forEach(function(line){
                if(line.data instanceof Array === true){
                    var hash = {};
                    line.data.forEach(function(item){
                        var item_data = {};
                        item_data.field_id = item.field_id;
                        item_data.value = item.value;
                        hash[item.field_id] = item_data;
                    })
                    line.data = hash;
                }
				delete line.num;
				delete line.edit_op;
				delete line.delete_op;
				delete line.name;
            })
            return value;
        },
        synchListValue: function(item){
            var value = this._config.value;
            if(item.cfg.flag == 'new'){
                var findit = false;
                this._config.value.forEach(function(line){
                    if(line.field_id == item.cfg.field_id || line.index == item.cfg.index){
                        findit = true;
                        line.data = item.cfg.data;
                        return false;
                    }
                })
                if(findit === false){
                    var line = {
                        index: item.cfg.index,
                        flag: item.cfg.flag,
                        data: item.cfg.data
                    }
                    this._config.value = this._config.value.concat([line]);
                }
			}else if(item.cfg.flag == 'delete'){
				var self = this;
				this._config.value.forEach(function(line, i){
                    if(line.index == item.cfg.index){
                        self._config.value.splice(i,1);
                        return false;
                    }
                })
			
            }else{	
                this._config.value.forEach(function(line){
                    if(line.index == item.cfg.index){
                        line.data = item.cfg.data;
                        return false;
                    }
                })

            }
			//尝试在删除列表控件行或修改列表控件值的时候执行触发器，代码实在太乱了，这样写不保证没有问题 tianlin 20180305
			if(this._config.trigger){
				this._config.fieldManager && this._config.fieldManager.triggerTrig(this._config);
			}
        },
        newListView: function() {
            var self = this;
            var ListViewManager = ListView.ListViewManager;
            self.listViewManager = new ListViewManager({
                wrapper: self.$listcontent,
                fieldManager: self._config.fieldManager
            }, { detail: [self._config] }); //self._config.value[0].data只有field_id和value两个字段
        },

        configReset: function() {

        },

        bindEvent: function() {
		
        }
    });
    exports.ListCtrl = window.ListCtrl = ListCtrl;
});

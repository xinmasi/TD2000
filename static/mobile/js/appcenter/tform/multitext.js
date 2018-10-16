define('MultitextCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var MultitextCtrl = Base.extend({
        initialize: function(config) {
            MultitextCtrl.superclass.initialize.call(this, config);
            this._config = config;
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
            this.bindEvent();
        },
        getValue: function() {
            return this.$el.find('textarea').val();
        },
        validate: function() {
            var value = this._config.value;
            var name  = this._config.name;
            //验证必填
            var required = this._config.required;
            if(required && $.trim(value) == ""){
                alert('字段'+name+"为必填字段");
                return false;
            }else{
                return true;
            }
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
            this.$el.find('textarea').on('keyup', function(){
				if(self._config.value != $(this).val()){
					self._config.value = $(this).val();
					self.triggerValidate();
					self.triggerCalc();
				}
            })
        }
    });
    exports.MultitextCtrl = window.MultitextCtrl = MultitextCtrl;
});

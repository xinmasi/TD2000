define('RadioCtrl', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var RadioCtrl = Base.extend({
        initialize: function(config) {
            RadioCtrl.superclass.initialize.call(this, config);
            this._config = config;
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
            this.bindEvent();
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

        getValue: function() {
            return this._config.value ? this._config.value : '';
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
            this.$el.find('input').on('change', function() {
                self._config.value = $(this).val();
                self.triggerCalc();
                self.triggerValidate();
            })
        }
    });
    exports.RadioCtrl = window.RadioCtrl = RadioCtrl;
});

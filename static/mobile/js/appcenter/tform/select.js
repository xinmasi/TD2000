define('SelectCtrl', function(require, exports, module) {
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var SelectCtrl = Base.extend({
        initialize: function(config) {
            SelectCtrl.superclass.initialize.call(this, config);
            this._config = config;
            this._render();
            this.$el = $('#f-field-' + this._config.pure_id);
            this.bindEvent();
        },

        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },
		
        reRender: function(new_config) {
            //������dom���滻��dom
            var html = $($.parseTpl(this._config.template, new_config)).html();
            this.$el.html(html);
            this.bindEvent();
			this._config.value = new_config.value;
        },

        validate: function() {
            var value = this._config.value;
            var name  = this._config.name;
            var multi  = this._config.multi;
            //��֤����
            var required = this._config.required;
            if(required){
                if(multi){
                    if(value.length <= 0){
                        alert('�ֶ�'+name+"Ϊ�����ֶ�");
                        return false;
                    }
                }else{
                    if($.trim(value) == ""){
                        alert('�ֶ�'+name+"Ϊ��ѡ�ֶ�");
                        return false;
                    }
                }
            }else{
                return true;
            }
        },

        getValue: function() {
            var self = this;
            if (this._config.multi) {
                return (self._config.value && self._config.value.length) ? self._config.value : [];
            } else {
                return self._config.value ? self._config.value : '';
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
            this.$el.find('select').on('change', function() {
                self._config.value = $(this).val();
                self.triggerCalc();
                self.triggerValidate();
            })
        }
    });
    exports.SelectCtrl = window.SelectCtrl = SelectCtrl;
});

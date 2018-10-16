define('NumberCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var NumberCtrl = Base.extend({
        initialize: function(config) {
            NumberCtrl.superclass.initialize.call(this, config);
            this._config = config;
            this._config.value = this._config.value.toFixed(this._config.fixTo || 0);
            this._config.value_ths = this._config.thousandth ? toThs(this._config.value) : '';
            this._render();
            this.$el = $('#f-field-'+this._config.field_id);
            this.bindEvent();
        },

        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },

        getValue: function() {
            if(this._config.writable){
                return this.$el.find('input').val();
            }else{
                return this._config.value;
            }  
        },

        bindEvent: function() {
            var self = this;
            this.$el.find('input').on('keyup', function(){
                var num_value = $(this).val();
                var num_test = /^(-?\d+)(\.\d+)?$/;
                if (!num_test.test(num_value) ) {
                    alert("请输入数字");
                    $(this).val('');
                    $(this).siblings('p').text('请输入数字');
                    return false;
                }else{
                    num_value = parseInt(num_value).toFixed(self._config.fixTo || 0);
                    $(this).siblings('p').text(toThs(num_value));
                }
            })
            
            this.$el.find('input').on('focus', function(){
                $(this).val('');
                $(this).siblings('p').text('请输入数字');
            })
            
            this.$el.find('input').on('change', function(){
                var num_value = $(this).val();
                num_value = parseInt(num_value).toFixed(self._config.fixTo || 0);
                $(this).siblings('p').text(toThs(num_value));
            })
            
            this.$el.find('input').on('blur', function(){
                var num_value = $(this).val();
                if( num_value === '' ){
                    num_value = self._config.value;
                }else{
                    num_value = parseInt(num_value).toFixed(self._config.fixTo || 0);
                }
                $(this).val(num_value);
                $(this).siblings('p').text(toThs(num_value));
            })
        }
    });
    exports.NumberCtrl = window.NumberCtrl = NumberCtrl;
});

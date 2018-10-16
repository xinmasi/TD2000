define('CurrencyCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var CurrencyCtrl = Base.extend({
        initialize: function(config) {
            CurrencyCtrl.superclass.initialize.call(this, config);
            this._config = config
            this._config.value = parseInt(this._config.value).toFixed(this._config.fixTo || 0)
            this._config.value_upcase = numtoCL.toMoney(this._config.value)
            this._render()
            this.$el = $('#f-field-'+this._config.field_id)
            this.bindEvent()
        },

        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config))
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
                var currency_value = $(this).val();
                var num_test = /^(-?\d+)(\.\d+)?$/;
                if (!num_test.test(currency_value) ) {
                    alert('请输入金额');
                    $(this).val('');
                    $(this).siblings('p').find('span').text('请输入金额');
                    return false;
                }else{
                    $(this).siblings('p').find('span').text(numtoCL.toMoney(currency_value));
                }
            })
            
            this.$el.find('input').on('change', function(){
                var currency_value = $(this).val();
                $(this).siblings('p').find('span').text(numtoCL.toMoney(currency_value));
            })
            
            this.$el.find('input').on('focus', function(){
                $(this).val('');
                $(this).siblings('p').find('span').text('请输入金额');
            })
            this.$el.find('input').on('blur', function(){
                var currency_value = $(this).val();
                if( currency_value === ''){
                    currency_value = self._config.value;
                }else{
                    currency_value = parseInt(currency_value).toFixed(self._config.fixTo || 0);
                }   
                $(this).val(currency_value);
                $(this).siblings('p').find('span').text(numtoCL.toMoney(currency_value));
            })
        }
    });
    exports.CurrencyCtrl = window.CurrencyCtrl = CurrencyCtrl;
});

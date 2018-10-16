define('TextCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var TextCtrl = Base.extend({
        initialize: function(config) {
            TextCtrl.superclass.initialize.call(this, config);
            this._config = config;
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
            this.$el.find('input').on('keyup', function(){
                console.log("单行输入框的值为：" + $(this).val());
            })
        }
    });
    exports.TextCtrl = window.TextCtrl = TextCtrl;
});

define('MultitextCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var MultitextCtrl = Base.extend({
        initialize: function(config) {
            MultitextCtrl.superclass.initialize.call(this, config);
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
                return this.$el.find('textarea').val();
            }else{
                return this._config.value;
            }
        },

        bindEvent: function() {
            this.$el.find('textarea').on('keyup', function(){
                console.log('多行文本的内容为：'+$(this).val());
            })
        }
    });
    exports.MultitextCtrl = window.MultitextCtrl = MultitextCtrl;
});

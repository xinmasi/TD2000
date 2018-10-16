define('SelectCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var SelectCtrl = Base.extend({
        initialize: function(config) {
            SelectCtrl.superclass.initialize.call(this, config);
            this._config = config
            this._render()
            this.$el = $('#f-field-'+this._config.field_id)
            this.bindEvent()
        },

        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config))
        },

        getValue: function() {
            var self = this
            if(this._config.writable){
                // console.log(this._config.writable,"下拉框的值为："+this.$el.find('select').val())
                return this.$el.find('select').val(); 
            }else{
                return this._config.value
            } 
        },

        bindEvent: function() {
            var self = this
            this.$el.find('select').on('change', function(){
                console.log("下拉框的值为：" + $(this).val())
            })
        }
    });
    exports.SelectCtrl = window.SelectCtrl = SelectCtrl;
});

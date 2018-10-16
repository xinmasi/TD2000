define('CheckboxCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var CheckboxCtrl = Base.extend({
        initialize: function(config) {
            CheckboxCtrl.superclass.initialize.call(this, config);
            this._config = config
            this._render()
            this.$el = $('#f-field-'+this._config.field_id)
            this.bindEvent()
        },

        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config))
        },

        getValue: function() {
            if(this._config.writable){
                var chk_value =[]; 
                this.$el.find('input[type="checkbox"]:checked').each(function(){ 
                     chk_value.push($(this).val()); 
                })
                // console.log("多选框的值为：" +chk_value)
                return chk_value
            }else{
                return this._config.value
            }  
        },

        bindEvent: function() {
            var self = this
            this.$el.find('input').on('click', function(){
                var chk_value =[]; 
                $('#f-field-'+ self._config.field_id + ' input[type="checkbox"]:checked').each(function(){ 
                     chk_value.push($(this).val()); 
                })
                console.log("多选框的值为：" + chk_value)
            })
        }
    });
    exports.CheckboxCtrl = window.CheckboxCtrl = CheckboxCtrl;
});

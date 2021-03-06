define('TBaseCtrl', function(require, exports, module){
    var $ = jQuery;
    var Base = require('base');
    var TBaseCtrl = Base.extend({

        initialize: function(config) {
            TBaseCtrl.superclass.initialize.call(this, config);
            this.required = config.required;
            this.desc = config.desc;
        },
        
        setOffsetTop: function() {
            var obj_val = this.$obj.val(),
                $field = this.$obj;
            var required_val = this.required;
            if((obj_val == '' || typeof obj_val == 'undefined') && required_val)
            {
                var objTop = $field.offset().top;
                $("body,html").animate({ 
                    scrollTop: objTop - 150
                },50); 
                return false;
            }            
        },
        
        onSubmit: function() {
            var obj_val = this.$obj.val(),
                $field = this.$obj;
            var required_val = this.required;
            if((obj_val == '' || typeof obj_val == 'undefined') && required_val)
            {
                this.validation(this.desc);
                setTimeout(function(){
                    var api = $field.data('tipsy');
                    api && api.hide();
                }, 5000);
                return false;
            }
        },
        
        validation: function(s) {
            this.$obj.tipsy({
                title: function () {
                    this.title = this.getAttribute('original-title');
                    this.removeAttribute('original-title');
                    return '<b style="color:#E25C5C;">' + s + '</b>';
                },
                html: true,
                fade: true,
                gravity: $.fn.tipsy.autoNS,
                opacity: 1,
                trigger: 'manual',
                container: '#content-main',
                fixPos: function(tp){ 
                // tp.left -= 50;  
                    return tp; 
                }
            }).tipsy('show');
        }
        
    });
    exports.TBaseCtrl = window.TBaseCtrl = TBaseCtrl;
});


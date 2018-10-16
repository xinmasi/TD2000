define('TRadioCtrl', function(require, exports, module){
    var $ = jQuery;
    var Base = require('base');
    var TRadioCtrl = Base.extend({

        initialize: function(config) {
            TRadioCtrl.superclass.initialize.call(this, config);
            this.id = config.id;
            this.low_id = config.id.toLowerCase();
            this.required = config.required;
            this.desc = config.desc;
        },
        
        onSubmit: function() {
            var obj_val = $("div.checked > input[name='"+this.id+"']").val();
            var required_val = this.required;
            var low_id = this.low_id;
            if((obj_val == '' || typeof obj_val == 'undefined') && required_val)
            {
                this.validation(this.desc);
                setTimeout(function(){
                    jQuery("#div_alert_"+low_id+"").removeClass("div_alert_show");
                }, 5000);
                return false;
            }
        },
        
        validation: function(s) {
            jQuery("#div_alert_" + this.low_id + "").addClass("div_alert_show");
        }
    });
    exports.TRadioCtrl = window.TRadioCtrl = TRadioCtrl;
});


define('NumberCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var NumberCtrl = Base.extend({
        initialize: function(config) {
            NumberCtrl.superclass.initialize.call(this, config);
            this._config = config;
            if(this._config.value){
                this._config.value = parseFloat(this._config.value).toFixed(this._config.fixTo || 0);
            }
            this._config.value_ths = this._config.thousandth ? toThs(this._config.value) : '';
            var step = 1;
            var fixTo = this._config.fixTo;
            if(fixTo != undefined && fixTo > 0 && fixTo <20){
				for(var i=0; i<fixTo; i++){
					step = step/10;
				}
			}
            this._config.step = step;
            this._render();
            this.$el = $('#f-field-'+this._config.pure_id);
            this.bindEvent();
        },
        _render: function() {
			
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },
        reRender: function(new_config) {
            //������dom���滻��dom
			new_config.value_ths = this._config.value_ths ;
			new_config.step = this._config.step;
            var html = $($.parseTpl(this._config.template, new_config)).html();
            this.$el.html(html);
			this._config.value = new_config.value;
			this.synchThs();
            this.bindEvent();
        },
        getValue: function() {
            return this._config.value ? this._config.value : '';
        },
        validate: function() {
            var value = this._config.value;
            var name  = this._config.name;
            
            var required = this._config.required;
			/* var reg = /^(-?\d+)(\.\d+)?$/;   */
			//��֤����
            if(required && $.trim(value) == ""){
                alert('�ֶ�'+name+"Ϊ�����ֶ�");
                return false;
            }
/* 			if($.trim(value) != "" && !/^(-?\d+)(\.\d+)?$/.test(value)){ 
				alert('�ֶ�'+name+"����������");
				return false;				
			}  */
        },
        synchThs: function(){
            var me = this;
            me.$el.find('p.ths span').text(toThs(me._config.value));
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
            var me = this;
			
            me.$el.find('input').on('keyup', function(){
                var value =  $(this).val();
				
                me._config.value = parseFloat(value).toFixed(me._config.fixTo || 0);
                me.synchThs();
                me.triggerCalc();
                me.triggerValidate();
            })
        }
    });
    exports.NumberCtrl = window.NumberCtrl = NumberCtrl;
});

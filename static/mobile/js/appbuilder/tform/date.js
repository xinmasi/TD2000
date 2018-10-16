define('DateCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var DateCtrl = Base.extend({
        initialize: function(config) {
            DateCtrl.superclass.initialize.call(this, config);
            this._config = config
            this._render()
            this.$el = $('#f-field-'+this._config.field_id)
            this.bindEvent()
            this.initMobiscroll(this._config.format)
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

        initMobiscroll: function(format){
            var self = this;
            var now = new Date();
            var $target = this.$el.find('input');
            // 格式为 HH:mm HH:mm:ss
            if(format.lastIndexOf(":") === 2 || format.lastIndexOf(":") === 5){
                $target.mobiscroll().time({
                    theme: 'ios7',
                    display: 'bottom',
                    lang: 'zh',
                    mode: 'scroller'
                })
                return false
            }

            // 格式为 yyyy-MM-dd HH:mm:ss
            if(format.length > 10){
                $target.mobiscroll().datetime({
                    theme: 'ios7',
                    display: 'bottom',
                    lang: 'zh',
                    mode: 'scroller',
                    dateFormat: 'yyyy-MM-dd'
                })
                return false
            }

            // 格式为 yyyy-MM-dd 
            if(format.indexOf("-") > 0 && format.length === 10){
                $target.mobiscroll().date({
                    theme: 'ios7',
                    display: 'bottom',
                    lang: 'zh',
                    mode: 'scroller',                                         
                    dateFormat: format
                })
                return false
            }

            // 格式为 yyyy/MM/dd 
            if(format.indexOf("/") > 0 && format.length === 10){
                $target.mobiscroll().date({
                    theme: 'ios7',
                    display: 'bottom',
                    lang: 'zh',
                    mode: 'scroller',                                         
                    dateFormat: format
                })
                return false
            }
            
            // 格式为 yyyyMMdd 
            if(format.length === 8){
                $target.mobiscroll().date({
                    theme: 'ios7',
                    display: 'bottom',
                    lang: 'zh',
                    mode: 'scroller',
                    dateFormat: format
                })
                return false
            }
        },

        bindEvent: function() {
            var self = this;
            var format = self._config.format;
            this.$el.find('input').on('change', function(){
                var date_value = $(this).val();
                
                // 格式为 HH:mm:ss
                if(format.lastIndexOf(":") === 5){
                    date_value = date_value + ":00";
                    $(this).val(date_value);
                    return false;
                }
                
                // 格式为 yyyyMMdd 
                if(format.length === 8){
                    date_value = date_value.replace("月", "");
                    date_value = date_value.length === 7 ? date_value.slice(0,4) + '0' + date_value.slice(4,7) : date_value
                    $(this).val(date_value);
                    return false
                }
                
                // 格式为 yyyy/MM/dd 
                if(format.indexOf("/") > 0 && format.length === 10){
                    date_value = date_value.replace("月", "");
                    var date_arr = date_value.split("/");
                    date_arr[1] = date_arr[1] >= 10 ? date_arr[1] : '0' + date_arr[1];
                    date_value = date_arr.join("/"); 
                    $(this).val(date_value);
                    return false
                }
                
                // 格式为 yyyy-MM-dd 
                if(format.indexOf("-") > 0 && format.length === 10){
                    date_value = date_value.replace("月", "");
                    var date_arr = date_value.split("-");
                    date_arr[1] = date_arr[1] >= 10 ? date_arr[1] : '0' + date_arr[1];
                    date_value = date_arr.join("-"); 
                    $(this).val(date_value);
                    return false
                }
                
                // 格式为 yyyy-MM-dd HH:mm:ss
                if(format.length > 10){
                    date_value = date_value.replace("月", "");
                    var datetime_arr = date_value.split(" ");
                    var date_arr = datetime_arr[0].split("-");
                    date_arr[1] = date_arr[1] >= 10 ? date_arr[1] : '0' + date_arr[1];
                    datetime_arr[1] = datetime_arr[1] + ":00";
                    date_value = date_arr.join("-") + " " + datetime_arr[1] ; 
                    $(this).val(date_value);
                }
                console.log("日期的值是：" + $(this).val())
            })
        }
    });
    exports.DateCtrl = window.DateCtrl = DateCtrl;
});

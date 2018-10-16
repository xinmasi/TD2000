define('LocationCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var LocationCtrl = Base.extend({
        initialize: function(config) {
            LocationCtrl.superclass.initialize.call(this, config);
            this._config = config
            this._render()
            this.$el = $('#f-field-'+this._config.field_id)
            this.bindEvent();
            //this.getMap(this._config.value.lat,this._config.value.lng);
        },

        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config))
        },
        
        getValue: function() {

        },
        getMap: function(lat,lng){
            tMobileSDK.getLocationByMap({
                onSuccess: function(response){
                    alert(JSON.stringify(response));
                }
            })
        },   
        bindEvent: function() {
            this.$el.find('button').on('click', function(){
                var $target = $(this).siblings('p');
                var local_lat = $target.attr('data-lat');
                var local_lng = $target.attr('data-lng');
                alert("定位的值为："+local_lat+","+local_lng);
                tMobileSDK.getLocationByMap({
                    onSuccess: function(response){
                        alert(JSON.stringify(response));
                        $target.text(response.nowaddress).attr('data-lat',response.nowlatitude).attr('data-lng',response.nowlontitude);
                    },
                    onFail: function(err){
                        alert("地图定位失败");
                    },
                })
            })
        }
    });
    exports.LocationCtrl = window.LocationCtrl = LocationCtrl;
});

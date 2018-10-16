define('AddressCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var AddressCtrl = Base.extend({
        initialize: function(config) {
            AddressCtrl.superclass.initialize.call(this, config);
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
                return this._config.value;
            }else{
                return this._config.value;
            }  
        },

        bindEvent: function() {
            var self = this;
            this.$el.find('select.address_prov').on('change', function(){
                var prov_id = $(this).val();
                var prov_name = '';
                $(this).find('option').each(function(){
                    if( prov_id === $(this).val() ){
                        prov_name =  $(this).text();
                        return false;
                    }                   
                })

                $(this).parents('.ui-select-group').find('select.address_city').empty();
                $(this).parents('.ui-select-group').find('select.address_country').empty();
                var citys = areaData.provinces[prov_id].citys;
                for(var city in citys){
                    var city_opts = '<option value="'+ city +'">'+citys[city].name +'</option>'
                    $(this).parents('.ui-select-group').find('select.address_city').append(city_opts);
                }

                var city_id = $(this).parents('.ui-select-group').find('select.address_city').val();
                var countrys = areaData.provinces[prov_id].citys[city_id].countrys;
                $(this).parents('.ui-select-group').find('select.address_country').empty();
                for(var country in countrys){
                    var country_html = '<option value="'+ country+'" >'+countrys[country].name +'</option>'
                    $(this).parents('.ui-select-group').find('select.address_country').append(country_html);
                }
                console.log("省的名称是：" + prov_name+ "," + prov_id );
                self._config.value.prov.name = prov_name;
                self._config.value.prov.id = prov_id;
            })

            this.$el.find('select.address_city').on('change', function(){
                var city_id = $(this).val();
                var city_name = ''
                $(this).find('option').each(function(){
                    if( city_id === $(this).val() ){
                        city_name =  $(this).text();
                        return false;
                    }                   
                })
                $(this).parents('.ui-select-group').find('select.address_country').empty();
                var countrys = areaData.provinces[self._config.value.prov.id].citys[city_id].countrys;
                for(var country in countrys){
                    var country_html = '<option value="'+ country +'" >'+countrys[country].name +'</option>'
                    $(this).parents('.ui-select-group').find('select.address_country').append(country_html);
                }
                self._config.value.city.name = city_name;
                self._config.value.city.id = city_id;
                console.log("市的名称是：" + city_name + "," + city_id);
            })

            this.$el.find('select.address_country').on('change', function(){
                var country_id= $(this).val();
                var country_name = '';
                $(this).find('option').each(function(){
                    if( country_id === $(this).val() ){
                        country_name =  $(this).text();
                        return false;
                    }                   
                })
                self._config.value.country.name = country_name
                self._config.value.country.id = country_id
                console.log("县的名称是：" + country_name + "," + country_id)
            })

            this.$el.find('input').on('blur', function(){
                var street_value = $(this).val();
                self._config.value.street.name = street_value;
                console.log("街道的地址是：" + street_value);
            })
        }

    });
    exports.AddressCtrl = window.AddressCtrl = AddressCtrl;
});

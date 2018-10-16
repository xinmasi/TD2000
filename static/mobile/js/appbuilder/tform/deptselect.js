define('DeptselectCtrl', function(require, exports, module){
    var $ = window.jQuery || window.Zepto;
    var Base = require('base');
    var DeptselectCtrl = Base.extend({
        initialize: function(config) {
            DeptselectCtrl.superclass.initialize.call(this, config);
            this._config = config;
            this._render();
            this.$el = $('#f-field-'+this._config.field_id);
            this.bindEvent();
        },
        
        _render: function() {
            this._config.container.append($.parseTpl(this._config.template, this._config));
        },
        
        getValue: function() {

        },
        
        bindEvent: function() {
            var self = this;
            this.$el.find('button').on('click', function(){
                var depts = [];
                $('.ui-deptselect-list a').each(function(){
                    var deptid = $(this).attr('data-deptid');
                    depts.push(deptid);
                })
                tMobileSDK.selectDept({ 
                    depts:depts,
                    onSuccess: function(result){
                        self.$el.find('.ui-deptselect-list').empty();
                        for(var i=0; i<result.length; i++ ){
                            var dept_html = '<a href="javascript:;" class="ui-form-tag" data-deptid="'+ result[i].deptId+'">'+ result[i].deptName+'</a>';   
                            self.$el.find('.ui-deptselect-list').append(dept_html);
                        }
                    },
                    onFail: function(data){
                        alert("部门选人失败：" + data);
                    }
                }); 
            })
        }
    });
    exports.DeptselectCtrl = window.DeptselectCtrl = DeptselectCtrl;
});

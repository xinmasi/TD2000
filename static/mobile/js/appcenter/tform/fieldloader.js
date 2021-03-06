define('FieldLoader', ["GroupCtrl","TextCtrl","MultitextCtrl","NumberCtrl","CurrencyCtrl","DateCtrl","RadioCtrl","CheckboxCtrl","SelectCtrl","AddressCtrl","LocationCtrl","AttachmentCtrl","ImageCtrl","DeptselectCtrl","UserselectCtrl","LinkCtrl","ListCtrl","QrcodeCtrl","ProgressbarCtrl","AutonumberCtrl","BarcodeCtrl"], function(require, exports, module){
    var depends = module.dependencies;
    if([].forEach){
        depends.forEach(function(v, i){
            var mod = require(depends[i]);
            exports[depends[i]] = mod ? mod[depends[i]] : null;
        });
    }else{
       for(var i in depends){
           var mod = require(depends[i]);
           exports[depends[i]] = mod ? mod[depends[i]] : null;
       }
    }
});

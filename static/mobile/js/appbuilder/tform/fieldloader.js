define('FieldLoader', ["GroupCtrl","TextCtrl","MultitextCtrl","NumberCtrl","CurrencyCtrl","DateCtrl","RadioCtrl","CheckboxCtrl","SelectCtrl","AddressCtrl","LocationCtrl","AttachmentCtrl","ImageCtrl","DeptselectCtrl","UserselectCtrl","LinkCtrl","ListCtrl"], function(require, exports, module){
   var depends = module.dependencies;
   for(var i in depends){
       var mod = require(depends[i]);
       exports[depends[i]] = mod ? mod[depends[i]] : null;
   }
});

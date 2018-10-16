<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("基本信息"), "href" => "base/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/project.gif"),
   array("text" => _("项目成员"), "href" => "user/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/user_group.gif"),
   array("text" => _("项目任务"), "href" => "task/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/diary.gif"),
   array("text" => _("项目文档"), "href" => "file/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/file_folder.gif"),
   //array("text" => _("项目经费"), "href" => "cost/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/project/cost.gif")
);
$SCRIPT='
<script>
var $ = function(id) {return document.getElementById(id);};
var userAgent = navigator.userAgent.toLowerCase();
var isSafari = userAgent.indexOf("Safari")>=0;
var is_opera = userAgent.indexOf("opera") != -1 && opera.version();
var is_moz = (navigator.product == "Gecko") && userAgent.substr(userAgent.indexOf("firefox") + 8, 3);
var is_ie = (userAgent.indexOf("msie") != -1 && !is_opera) && userAgent.substr(userAgent.indexOf("msie") + 5, 3);;
if(is_ie)
   window.attachEvent("onload", forbiden);
else
   window.addEventListener("load", forbiden,false);
';
if(!$PROJ_ID)
  $SCRIPT.='
  function forbiden()
  {
     var menu=document.getElementById("navMenu");
     var menu_id=0;
     if(!menu) return;  
     for(var i=0; i<menu.childNodes.length;i++)
     {
        if(menu.childNodes[i].tagName!="A")
           continue;
        if(menu_id!=0)
        {
          menu.childNodes[i].href="#";
          menu.childNodes[i].target="_self";     
          menu.childNodes[i].onclick=function(){alert("'._("请先保存项目信息！").'");};
        }
        menu_id++;
     }
  }
  ';
else
  $SCRIPT.='function forbiden(){return;}';
$SCRIPT.='</script>';
include_once("inc/menu_top.php");
?>

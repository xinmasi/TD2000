<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("������Ϣ"), "href" => "base/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/project.gif"),
   array("text" => _("��Ŀ��Ա"), "href" => "user/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/user_group.gif"),
   array("text" => _("��Ŀ����"), "href" => "task/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/diary.gif"),
   array("text" => _("��Ŀ�ĵ�"), "href" => "file/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/file_folder.gif"),
   //array("text" => _("��Ŀ����"), "href" => "cost/?PROJ_ID=$PROJ_ID&EDIT_FLAG=$EDIT_FLAG", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/project/cost.gif")
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
          menu.childNodes[i].onclick=function(){alert("'._("���ȱ�����Ŀ��Ϣ��").'");};
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

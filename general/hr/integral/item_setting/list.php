<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
if($FROM==1)
	Message("",_("OA使用积分项，可以设置一些预设好的OA使用动作所产生的积分项是否被使用、分值是多少。请点击左侧某项的下一级按钮进行配置。"));
if($FROM==2)
	Message("",_("人事档案积分项，可以设置人事档案中字段所产生的积分项是否被使用、分值是多少。请点击左侧某项的下一级按钮进行配置。"));
if($FROM==3)
	Message("",_("自定义积分项，可以由用户自行设置积分项的分类和项目。点击左侧")."<a href=\"new.php\" target=\"code_edit\" >"._("增加自定义积分项分类")."</a>"._("增加积分分类。点击相应分类的下一级按钮对项目进行设置。"));
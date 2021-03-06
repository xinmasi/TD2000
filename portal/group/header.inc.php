<?
include_once("inc/utility.php");
include_once("inc/session.php");

session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?=$page_title?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?=$page_charset?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <?
    $path = pathinfo($_SERVER['SCRIPT_NAME']);
    $basename = strtolower($path['basename']);
    ?>
    <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/portal/group/style/css/css.css">
    <?
    if($basename=='column.php')
    {
    ?>
        <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/portal/group/style/css/list.css">
    <?
    }
    elseif($basename!='index.php')
    {
    ?>
    <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/portal/group/style/css/content.css">
    <?
    }
    ?>
    <script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
    <script type="text/javascript">
        function addFavorite(URL, title)
        {
            try{
                window.external.addFavorite(URL, title);
                window.sidebar.addPanel(title, URL, "");
            }catch(e){
                var type = navigator.userAgent.indexOf("Chrome") + navigator.userAgent.indexOf("Firefox");
                if(type > -1){
                    alert("加入收藏失败，请使用 Ctrl+D 进行添加");
                }
            }
        }
        function setHome()
        {
            try{
                document.body.style.behavior = 'url(#default#homepage)';
                document.body.setHomePage(document.URL);
                var setType = navigator.userAgent.toLowerCase();
                if(setType.indexOf("msie 8.0") > -1 || setType.indexOf("msie 6.0") > -1){
                    var tip = "设置首页成功";
                    alert(tip);
                }else{
                    alert("您的浏览器不支持此操作\n请使用浏览器的“选项”或“设置”等功能设置首页");
                }
            }catch(e){
                if(typeof tip == 'undefined'){
                    alert("您的浏览器不支持此操作\n请使用浏览器的“选项”或“设置”等功能设置首页");
                }
            }
        }
    </script>
</head>
<body>
<div id="group">
    <div class="main">
        <!----- 头部 ----->
        <div id="header">
            <div class="logo">
                <a href="<?=$arr_portal_info['logo_link']?>" target="_blank">
                    <?if($arr_portal_info['logo_img'] != '') {?><span class="img"><img style="height:66px;width:190px;" src="<?=$arr_portal_info['logo_img']?>" /></span><?}?>
                    <span class="text"><?=$arr_portal_info['logo_text']?></span>
                </a>
            </div>
                    <div class="top">  <!---- 登录 ---->
        <div><a href="javascript:;" onclick="setHome()"><?=_("设为首页")?></a> | <a href="###" onclick="addFavorite(window.location,document.title)"><?=_("加入收藏")?></a></div>
        <div class="loginbar">
            <?
            if(empty($_SESSION['LOGIN_USER_ID']))
            {
            ?>
                <a href="/?from=portal" target="_top"><?=_("登录OA")?></a>
            <?
            }
            else
            {
                $h = date('G');
                $user_name = '<strong>'.$_SESSION['LOGIN_USER_NAME'].'</strong>';
                if ($h < 8)
                {
                    echo $user_name.', '._("早上好！");
                }
                else if($h < 12)
                {
                     echo $user_name.', '._("上午好！");
                }
                else if($h < 13)
                {
                     echo $user_name.', '._("中午好！");
                }
                else if ($h < 18)
                {
                     echo $user_name.', '._("下午好！");
                }
                else
                {
                    echo $user_name.', '._("晚上好！");
                }
            ?>
                <a href="/general/" target="_top"><?=_("进入OA")?></a>
            <?
            }
            ?>
        </div>
        </div>
            <!---- 导航 ---->
            <div class="nav">
                <ul>
                        <li><a href="index.php?portal_id=<?=$portal_id?>"><?=_("首 页")?></a></li>
                    <?
                    foreach($arr_nav_list as $nav_id => $nav)
                    {
                        if($nav['column_type'] != '1' && $nav['column_link'] == '')
                        {
                            $nav['column_link'] = 'column.php?portal_id='.$portal_id.'&column_id='.$nav_id;
                        }

                        echo '<li><a target="'.get_link_target_desc($nav['link_target']).'" href="'.$nav['column_link'].'">'.td_htmlspecialchars($nav['column_name']).'</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <!---- 公告 ---->
            <div class="notice">
                <? $s_column_id = '20';?><!--这里不要用$column_id变量-->
                <strong><?=$arr_columns_info[$s_column_id]['column_name']?>：</strong>
                <ul>
                    <?
                    $contents = $obj_portal_data->get_contents_list($s_column_id,0,3);
                    foreach($contents as $content)
                    {
                        $content_link = get_content_link($content, $portal_id, $s_column_id);
                        echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'">'.td_htmlspecialchars($content['subject']).'</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <!-------内容区域 content ------->
    <div id="content">
<?
session_write_close();
?>

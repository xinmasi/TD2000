<?
include_once('../include/common.inc.php');

//获取页面导航栏信息，在../inc/header.inc.php中使用
$arr_nav_list = $obj_portal_data->get_nav_list();

//获取首页主体部分各栏目的基本信息，可根据column_id一次获取多个栏目的新写
$arr_columns_info = $obj_portal_data->get_columns_info('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,51');

$page_title = $arr_portal_info['portal_name'].' - '._("首页");
include_once('./header.inc.php');

?>
    <script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/portal/group/js/jquery.SuperSlide.js"></script>
    <!-------内容区域一 ------->
    <div class="content_1">
        <!---- 集团新闻 ---->
        <div class="news">
                <? 
                $column_id = '3';
                $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);        
                ?>
                <div class="title"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span><strong><?=$arr_columns_info[$column_id]['column_name']?></strong></div>
                <ul>
                    <?
                    $contents = $obj_portal_data->get_contents_list($column_id,0,10);
                    foreach($contents as $content)
                    {   
                        $content_link = get_content_link($content, $portal_id, $column_id);
						$temp_subject = csubstr($content['subject'],0,36);
                        $temp_time = date("Y-m-d",$content['time']);
                        echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'">'.$temp_subject.'</a>'."&nbsp;[$temp_time]".'</li>';    
                    }                        
                    ?>
                </ul>
        </div>
        <!---- 幻灯片 ---->
        <div class="news_tu">
            <div id="focusBox" class="focusBox">
                    <? $column_id = '51'?>
                        <div class="hd">
                            <ul>
                                <?
                                $contents = $obj_portal_data->get_contents_list($column_id);
                                $s_i = 0;
                                foreach($contents as $content)
                                {
                                    echo '<li>'.++$s_i.'</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="bd">
                            <ul>
                                <?
                                foreach($contents as $content)
                                {
                                    $content_link = get_content_link($content, $portal_id, $column_id);
                                    echo '<li><div class="pic"><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'"><img src="'.$content['link_img'].'" /></a>';
                                    echo '<div class="con"><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'">'.$content['subject'].'</a></li>';        
                                }                        
                                ?>
                            </ul>
                        </div>
                    </div>
            <script type="text/javascript">jQuery(".focusBox").slide( { mainCell:".bd ul",autoPlay:true, delayTime:0, trigger:"click"} );</script>
        </div>
        <!---- 视频 ---->
        <div class="mv" >
                <?
                $column_id = '28';
                $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);
                $contents = $obj_portal_data->get_contents_list($column_id,0,1);
                $content_link = get_content_link($contents['0'], $portal_id, $column_id);
                ?>
                <div class="mv_content"><a target="<?=$content_link['link_target']?>" href="<?=$content_link['content_link']?>"><?=$contents['0']['subject']?></a></div>
                <div class="mv_1">
                     <div class="title"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span><strong><?=$arr_columns_info[$column_id]['column_name']?></strong></div>
                     <ul>
                         <?
                         $contents = $obj_portal_data->get_contents_list($column_id,0,3);
                         foreach($contents as $content)
                         {
                             $content_link = get_content_link($content, $portal_id, $column_id);
                             echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'">'.$content['subject'].'</a></li>';    
                         }                        
                         ?>
                     </ul>
                </div>
        </div>
    </div>
   
   <!-------内容区域二 ------->
   <div class="content_2">
       <!---- 滚动图片 ---->
       <div class="scroll_pictures" id="leftMarquee">
            <?
            $column_id = '18';
            $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);                
            ?>
           <div class="title"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span><strong><?=$arr_columns_info[$column_id]['column_name']?></strong></div>
           <div class="bd">
               <ul class="picList">
                   <?
                    $contents = $obj_portal_data->get_contents_list($column_id);
                    foreach($contents as $content)
                    {
                         $content_link = get_content_link($content, $portal_id, $column_id);
                         echo '<li><div class="pic"><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" ><img src="'.$content['link_img'].'" /><span>'.$content['subject'].'</span></div></a></li>';    
                    }                        
                    ?>
               </ul>
           </div>
           <script type="text/javascript">jQuery("#leftMarquee").slide({ mainCell:".bd ul",effect:"leftMarquee",vis:6,interTime:40,autoPlay:true });</script>
       </div>
   </div>
   
   <!-------内容区域三 ------->
   <div class="content_3">
       <!---- 栏目1 ---->
       <div class="column" style="margin-left:0px;">
                <? 
                $column_id = '15';
                $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);    
                ?>
               <div class="title"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span><strong><?=$arr_columns_info[$column_id]['column_name']?></strong></div>
               <ul>
                    <?
                    $contents = $obj_portal_data->get_contents_list($column_id,0,8);
                    foreach($contents as $content)
                    {
                        $content_link = get_content_link($content, $portal_id, $column_id);
                        $temp_subject = csubstr($content['subject'],0,36);
                        $temp_time = date("Y-m-d",$content['time']);
                        echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" >'.$temp_subject.'</a>'."&nbsp;[$temp_time]".'</li>';
                         
                    }                        
                    ?>
               </ul>
       </div>
       <!---- 栏目2 ---->
       <div class="column">
               <? 
               $column_id = '16';
               $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);
               ?>
               <div class="title"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span><strong><?=$arr_columns_info[$column_id]['column_name']?></strong></div>
               <ul>
                    <?
                    $contents = $obj_portal_data->get_contents_list($column_id,0,8);
                    foreach($contents as $content)
                    {
                          $content_link = get_content_link($content, $portal_id, $column_id);
                          $temp_subject = csubstr($content['subject'],0,36);
                       	  $temp_time = date("Y-m-d",$content['time']);
                          echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" >'.$temp_subject.'</a>'."&nbsp;[$temp_time]".'</li>';    
                    }                        
                    ?>
               </ul>
       </div>
       <!---- 栏目3 ---->
       <div class="column">
               <?
               $column_id = '19';
               $column_link = get_column_link($arr_columns_info, $portal_id, $column_id); 
               ?>
               <div class="title"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span><strong><?=$arr_columns_info[$column_id]['column_name']?></strong></div>
               <ul>
                    <?
                    $contents = $obj_portal_data->get_contents_list($column_id,0,8);
                    foreach($contents as $content)
                    {
                        $content_link = get_content_link($content, $portal_id, $column_id);
                        $temp_subject = csubstr($content['subject'],0,36);
                        $temp_time = date("Y-m-d",$content['time']);
                        echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" >'.$temp_subject.'</a>'."&nbsp;[$temp_time]".'</li>';
                         
                    }                        
                    ?>
               </ul>
       </div>
       <!---- 栏目4 ---->
       <div class="column" style="margin-left:0px;">
               <?
               $column_id = '20';
               $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);
               ?>
               <div class="title"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span><strong><?=$arr_columns_info[$column_id]['column_name']?></strong></div>
               <ul>
                    <?
                    $contents = $obj_portal_data->get_contents_list($column_id,0,8);
                    foreach($contents as $content)
                    {
                         $content_link = get_content_link($content, $portal_id, $column_id);
                        $temp_subject = csubstr($content['subject'],0,36);
                        $temp_time = date("Y-m-d",$content['time']);
                        echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" >'.$temp_subject.'</a>'."&nbsp;[$temp_time]".'</li>';
                         
                    }                        
                    ?>
               </ul>
       </div>
       <!---- 栏目5 ---->
       <div class="column">
               <?
               $column_id = '26';
               $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);     
               ?>
               <div class="title"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span><strong><?=$arr_columns_info[$column_id]['column_name']?></strong></div>
               <ul>
                    <?
                    $contents = $obj_portal_data->get_contents_list($column_id,0,8);
                    foreach($contents as $content)
                    {
                         $content_link = get_content_link($content, $portal_id, $column_id);
                        $temp_subject = csubstr($content['subject'],0,36);
                        $temp_time = date("Y-m-d",$content['time']);
                        echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" >'.$temp_subject.'</a>'."&nbsp;[$temp_time]".'</li>';
                         
                    }                        
                    ?>
               </ul>
       </div>
       <!---- 栏目6 ---->
       <div class="column">
               <?
               $column_id = '8';
               $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);
               ?>
               <div class="title"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span><strong><?=$arr_columns_info[$column_id]['column_name']?></strong></div>
               <ul>
                    <?
                    $contents = $obj_portal_data->get_contents_list($column_id,0,8);
                    foreach($contents as $content)
                    {    
                         $content_link = get_content_link($content, $portal_id, $column_id);
                        $temp_subject = csubstr($content['subject'],0,36);
                        $temp_time = date("Y-m-d",$content['time']);
                        echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" >'.$temp_subject.'</a>'."&nbsp;[$temp_time]".'</li>';
                         
                    }                        
                    ?>
               </ul>
       </div>
   </div>
   
   <!--- 友情链接 --->
   <div class="link">
            <? $column_id = '30'?>
            <strong><?=$arr_columns_info[$column_id]['column_name']?>：</strong>
            <?
            $contents = $obj_portal_data->get_contents_list($column_id);
            foreach($contents as $content)
            {
                $content_link = get_content_link($content, $portal_id, $column_id);
                echo '<a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" >'.$content['subject'].'</a>';    
            }                        
            ?>
    </div>
   
  </div>
<?
include_once('./footer.inc.php');
?>
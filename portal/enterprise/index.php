<?
include_once('../include/common.inc.php');

//��ȡҳ�浼������Ϣ����../inc/header.inc.php��ʹ��
$arr_nav_list = $obj_portal_data->get_nav_list();

//��ȡ��ҳ���岿�ָ���Ŀ�Ļ�����Ϣ���ɸ���column_idһ�λ�ȡ�����Ŀ����д
$arr_columns_info = $obj_portal_data->get_columns_info('44,49,48,47,63,64,61,54,60');

$page_title = $arr_portal_info['portal_name'].' - '._("��ҳ");
include_once('./header.inc.php');
?>
    <script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/portal/group/js/jquery.SuperSlide.js"></script>

            <!--- ��������һ --->
            <div class="content_1">
                     <!-- �õ�Ƭ -->
            <div class="news_tu">
                <div id="focusBox" class="focusBox">
                        <? $column_id = '60'?>
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

                     <!-- ����֪ͨ -->
                     <div class="notice">
                        <? 
                        $column_id = '47';
                        $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);        
                        ?>
                        <div class="title"><?=$arr_columns_info[$column_id]['column_name']?></div>
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
                         <div class="more"><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></div>	
                      </div>
            </div>
            
             <!--- ��������� --->
            <div class="content_2">
                    <!-- ��˾��� -->
                    <div class="company_profile">
                    <? 
                    $column_id = '48';
                    $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);        
                    ?>
                    <div class="title"><?=$arr_columns_info[$column_id]['column_name']?></div>
                    <ul>
                        <?
                        $contents = $obj_portal_data->get_contents_list($column_id,0,7);
                        foreach($contents as $content)
                        {   
                            $content_link = get_content_link($content, $portal_id, $column_id);
                        	$temp_subject = csubstr($content['subject'],0,36);
                        	$temp_time = date("Y-m-d",$content['time']);
                        	echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" >'.$temp_subject.'</a>'."&nbsp;[$temp_time]".'</li>';
                        }                        
                        ?>
                     </ul>
                    <div class="more"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span></div>	
                    </div>
                    <!-- ͼ���б� -->
                    <div class="photo_list">
                        <? 
                        $column_id = '61';        
                        ?>
                        <div class="title"><?=$arr_columns_info[$column_id]['column_name']?></div>
                        <?
                        $contents = $obj_portal_data->get_contents_list($column_id,0,4);
                        foreach($contents as $content)
                        {   
                            $content_link = get_content_link($content, $portal_id, $column_id);             
                            echo '<dl>';
                            echo '<dt><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'"><img src="'.$content['link_img'].'" width="130" height="90" />';                    
                            echo '<dd><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'">'.$content['subject'].'</a></dd>';
                            echo '</dl>';  
                        }                        
                        ?>                   
                    </div>
                    
                    <!-- ���Ŷ�̬ -->
                    <div class="news">
                        <? 
                        $column_id = '49';
                        $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);        
                        ?>
                        <div class="title"><?=$arr_columns_info[$column_id]['column_name']?></div>
                        <ul>
                            <?
                            $contents = $obj_portal_data->get_contents_list($column_id,0,7);
                            foreach($contents as $content)
                            {   
                                $content_link = get_content_link($content, $portal_id, $column_id);
                        		$temp_subject = csubstr($content['subject'],0,36);
                        		$temp_time = date("Y-m-d",$content['time']);
                        		echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'" >'.$temp_subject.'</a>'."&nbsp;[$temp_time]".'</li>';
                            }                        
                            ?>
                         </ul>
                        <div class="more"><span><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></span></div>	
                    </div> 
            </div>
            
            <!--- ���������� --->
            <div class="content_3">
                    <!-- ҵ����� -->
                    <div class="news">
                        <? 
                        $column_id = '54';
                        $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);        
                        ?>
                        <div class="title_a"><?=$arr_columns_info[$column_id]['column_name']?></div>
                        <ul>
                            <?
                            $contents = $obj_portal_data->get_contents_list($column_id,0,5);
                            foreach($contents as $content)
                            {   
                                $content_link = get_content_link($content, $portal_id, $column_id);             
                                echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'">'.$content['subject'].'</a></li>';    
                            }                        
                            ?>
                        </ul>
                        <div class="more"><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></div>	
                    </div>
                    
                    <!-- ������Ŀ -->
                    <div class="news">
                        <? 
                        $column_id = '57';
                        $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);        
                        ?>
                        <div class="title_a">
                        	<?=($column_id == '57') ?  $arr_columns_info['44']['column_name']  : $arr_columns_info[$column_id]['column_name'];?>
                        </div>
                        <ul>
                            <?
                            $contents = $obj_portal_data->get_contents_list($column_id,0,5);
                            foreach($contents as $content)
                            {   
                                $content_link = get_content_link($content, $portal_id, $column_id);             
                                echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'">'.$content['subject'].'</a></li>';    
                            }                        
                            ?>
                        </ul>
                        <div class="more"><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></div>	
                    </div> 
                    
                    <!-- ������Ŀ -->
                    <div class="news">
                        <? 
                        $column_id = '64';
                        $column_link = get_column_link($arr_columns_info, $portal_id, $column_id);        
                        ?>
                        <div class="title_a"><?=$arr_columns_info[$column_id]['column_name']?></div>
                        <ul>
                            <?
                            $contents = $obj_portal_data->get_contents_list($column_id,0,5);
                            foreach($contents as $content)
                            {   
                                $content_link = get_content_link($content, $portal_id, $column_id);             
                                echo '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'">'.$content['subject'].'</a></li>';    
                            }                        
                            ?>
                        </ul>
                        <div class="more"><a href="<?=$column_link['column_link']?>" target="<?=$column_link['link_target']?>">more</a></div>	
                    </div>  
            
            </div>
            
            <!--- �������� --->
            <div class="link">
            <? $column_id = '63'?>
            <strong><?=$arr_columns_info[$column_id]['column_name']?>��</strong>
            <?
            $contents = $obj_portal_data->get_contents_list($column_id,0,5);
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

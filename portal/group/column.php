<?
include_once('../include/common.inc.php');

$parent_columns = isset($_GET['parent_columns']) ? intval($_GET['parent_columns']) : intval($column_id);
$portal_id = intval($portal_id);

$columns_id = $column_id.",20,".$parent_columns;
$s_url = 'column.php?portal_id='.$portal_id.'&column_id='.$column_id;

//��ȡҳ�浼������Ϣ����../inc/header.inc.php��ʹ��.
$arr_nav_list = $obj_portal_data->get_nav_list();

//��ȡ��ҳ���岿�ָ���Ŀ�Ļ�����Ϣ���ɸ���column_idһ�λ�ȡ�����Ŀ����д
$arr_columns_info = $obj_portal_data->get_columns_info($columns_id);

//��ȡ����Ŀ
$arr_child_columns = $obj_portal_data->get_child_columns($parent_columns);

//�����Ŀҳ�������ǵ�һ������Ŀ������ת����һ������Ŀ�ĵ�ַ
if($arr_columns_info[$column_id]['column_type'] == '5' && count($arr_child_columns) > 0)
{
    $column_link = get_column_link($arr_child_columns, $portal_id, key($arr_child_columns));
    header('location: '.$column_link['column_link'].'&parent_columns='.$parent_columns);
}

$s_list_right_css = 'list_right_2';
if(!empty($arr_child_columns))
{
    $s_list_right_css = '';
    $s_child_list = '<div class="list_left"><h3>'.$arr_columns_info[$parent_columns]['column_name'].'</h3><ul>';
    foreach($arr_child_columns as $child_column_id => $child_column)
    {
        $column_link = get_column_link($arr_child_columns, $portal_id, $child_column_id);
        $column_link['column_link'] .= '&parent_columns='.$parent_columns;
        if($child_column_id == $column_id)
        {
            $css = 'current';   
        }
        else
        {
            $css = '';
        }
        $s_child_list .= '<li ><a class="'.$css.'" href="'.$column_link['column_link'].'" target="'.$column_link['link_target'].'">'.$child_column['column_name'].'</a></li>';
    }
    $s_child_list .= '</ul></div>';
}

$page_title = $arr_portal_info['portal_name'].' - '.$arr_columns_info[$column_id]['column_name'];
include_once('./header.inc.php');

?>
            <!---- �����б� ---->
            <div id="list">
                <!-- �б��� -->
                <?=$s_child_list?>
                <!-- �б��� -->
                <div class="list_right <?=$s_list_right_css?>">
<!--                    <div class="lisht_title"><strong><a href="index.php?portal_id=<?=$portal_id?>"><?=_("��ҳ")?></a> / <a href="<?=$s_url?>"><?=$arr_columns_info[$column_id]['column_name']?></a></strong></div>-->
                    <?
                    if($arr_columns_info[$column_id]['column_type']=='0' || $arr_columns_info[$column_id]['column_type']=='2' || $arr_columns_info[$column_id]['column_type']=='3')
                    {                     
                        $a_contents = $obj_portal_data->get_contents_list($column_id,0,100);

                        $s_contents_length = sizeof($a_contents);
                        //ҳ��Ļ�������
                        if(empty($_GET['pages']))
                        {
                            $pages = 1;
                        }
                        else
                        {
                            $pages = $_GET['pages'];
                        }
                        
                        $page_size = 10;
                        $start_list = ($pages-1) * $page_size;
                        $end_list = $start_list + $page_size;

                        $pages_count = ceil($s_contents_length/$page_size)==0?1: ceil($s_contents_length/$page_size);
                        $show_page = $pages.'/'.$pages_count;
                        
                        $prev = $pages-1;
                        if($prev<=0)
                        {
                            $prev = 1;
                        }
                        
                        $next = $pages+1;
                        if($pages>=$pages_count)
                        {
                            $next = $pages_count;
                        }
                        $home_page = $s_url;
                        $prev_page = $s_url."&pages=$prev";
                        $next_page = $s_url."&pages=$next";
                        $last_page = $s_url."&pages=$pages_count";

                        $list_html = '';
						
                        for($i = $start_list; $i< $end_list; $i++)
                        {
							$content_id = $a_contents[$i]['content_id'];
                            if($content_id!='0'&&$content_id=='')
                            {
                                continue;
                            }
                            
                            $count++;
                            $content_link = get_content_link($a_contents[$i], $portal_id, $column_id, $content_id); 
                        	$temp_time = date("Y-m-d",$a_contents[$i]['time']);
                            $list_html .= '<li><a href="'.$content_link['content_link'].'" target="'.$content_link['link_target'].'">'.$a_contents[$i]['subject'].'</a>'."&nbsp;<span style='float:right'>[$temp_time]</span>".'</li>';
                        }
                        
                        if($list_html != '')
                        {
                        ?>
                            <ul>
							     <?=$list_html?>
                               
                            </ul>
                            <h5><?=_("�ܼ�")?> <?=$s_contents_length?> <?=_("�� ҳ��")?> <?=$show_page?><?=_("ҳ")?> <a href="<?=$home_page ?>"><?=_("��ҳ")?></a> | <a href="<?=$prev_page ?>"><?=_("��һҳ")?></a> | <a href="<?=$next_page ?>"><?=_("��һҳ")?></a> | <a href="<?=$last_page ?>"><?=_("ĩҳ")?></a></h5>
                        <?
                        }
                        else
                        {
                            echo '<div class="no-data">'._("��������").'</div>';
                        }
                    }
                    else if($arr_columns_info[$column_id]['column_type']=='4')
                    {
                        $page_info = $obj_portal_data->get_column_page($column_id);
                        $page_content = (is_array($page_info) && $page_info['content'] != '') ? $page_info['content'] : ('<div class="no-data">'._("��������").'</div>');
                    ?>
                        <?=$page_content?>
                    
                    <?/*
                          $files = $page_info['files'];
                          $s_num = 0;
                          if(!empty($files))
                          {
                              //echo '<h5><strong>'._("����:").'</strong>';
                              foreach($files as $file)
                              {
                                  $s_num ++;
                                  $files_html .= '<span><a href="'.$file['file_url_down'].'">'.$file['file_name'].'</a></span>';
                              }
                              //echo '</h5>';
                          }
                          if($s_num > 0)
                          {
                              echo '<h5><strong>'._("����:").'</strong>'.$files_html.'</h5>';
                          }*/
                    }
                    else
                    {
                        echo '<div class="no-data">'._("��������").'</div>';
                    }
                    ?>
                </div>
            </div>
<?
include_once('./footer.inc.php');
?>


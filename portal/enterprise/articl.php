<?
include_once('../include/common.inc.php');

$columns_id = $column_id.",10";
$content_id = intval($content_id);

//��ȡҳ�浼������Ϣ����../inc/header.inc.php��ʹ��
$arr_nav_list = $obj_portal_data->get_nav_list();

//��ȡ��ҳ���岿�ָ���Ŀ�Ļ�����Ϣ���ɸ���column_idһ�λ�ȡ�����Ŀ����д
$arr_columns_info = $obj_portal_data->get_columns_info($columns_id);

$arr_contents_list = $obj_portal_data->get_contents_list($column_id);
//print_r($arr_contents_list);
$arr_content_info = $obj_portal_data -> get_contents_info($content_id);
//print_r($arr_content_info);

$page_title = $arr_content_info[$content_id]['subject'];
include_once('./header.inc.php');

?>
          <!---- ����ҳ ���� ---->
          <div class="content_text">
<!--              <div class="content_title"><strong><a href="index.php?portal_id=<?=$portal_id?>"><?=_("��ҳ")?></a> / <a href="column.php?portal_id=<?=$portal_id?>&column_id=<?=$column_id?>"><?=$arr_columns_info[$column_id]['column_name']?></a></strong></div>-->
                  <h2><?=$arr_content_info[$content_id]['subject']?></h2>
                  <h3><span><?=_("����ʱ�䣺")?><?=date('Y-m-d',$arr_content_info[$content_id]['time'])?></span>&nbsp;&nbsp;<!--<span>����xx�����</span>&nbsp;&nbsp;<span>�����ˣ�xx</span>--></h3>
                  <div style="margin:10px 15px 10px 5px;min-height:75px;font-size:12pt;"><?=$arr_content_info[$content_id]['content_text']?></div>
                  <?
                  $content_files = $arr_content_info[$content_id]['files'];
                  if(!empty($content_files))
                  {
                      //echo '<h5><strong>'._("����:").'</strong>';
                      foreach($content_files as $file)
                      {
                          if($file['download']=='0')
                          {
                              continue;
                          }
                          $s_num ++;
                          $files_html .= '<span><a href="'.$file['file_url_down'].'">'.$file['file_name'].'</a></span>';
                      }
                      //echo '</h5>';
                  }
                  if($s_num > 0)
                  {
                      echo '<h5><strong>'._("����:").'</strong>'.$files_html.'</h5>';
                  }
                  ?>
                  <!--<h6><span>��һƪ��<a href="#">����ҵ���ֻ�����������ѧ����̳�������ٰ�</a></span>��һƪ��<a href="#">���Ź�˾���й�500ǿ��19λ </a></h6>-->
          </div>
        </div>
<?
include_once('./footer.inc.php');
?>


<?
include_once("inc/utility.php");
include_once('../include/common.inc.php');

$columns_id = $column_id.",10";
//获取页面导航栏信息，在../inc/header.inc.php中使用
$arr_nav_list = $obj_portal_data->get_nav_list();

//获取首页主体部分各栏目的基本信息，可根据column_id一次获取多个栏目的新写
$arr_columns_info = $obj_portal_data->get_columns_info($columns_id);
$arr_content_info = $obj_portal_data -> get_contents_info($content_id);

$content_files = $arr_content_info[$content_id]['files'];

$page_title = $arr_content_info[$content_id]['subject'];
include_once('./header.inc.php');
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/portal/group/js/lrtk.js"></script>
          <!---- 三级页 文章 ---->
          <div class="content_text content_img">
<!--              <div class="content_title"><strong><a href="index.php?portal_id=<?=$portal_id?>"><?=_("首页")?></a> / <a href="column.php?portal_id=<?=$portal_id?>&column_id=<?=$column_id?>"><?=$arr_columns_info[$column_id]['column_name']?></a></strong></div>-->
                <div id="slideshow">
                 <h2><?=$arr_content_info[$content_id]['subject']?>（<span id="xz">x</span>/<span id="imgdata">x</span>）</h2>
                 <h3><span><?=_("发布时间：")?><?=date('Y-m-d',$arr_content_info[$content_id]['time'])?></span>&nbsp;&nbsp;<!--<span>共有xx人浏览</span>&nbsp;&nbsp;<span>发布人：xx</span>--></h3>
                <!-- 幻灯片 开始 -->
                    <ul class="slideshow">
                        <span><img src="<?=MYOA_STATIC_SERVER?>/static/portal/group/style/images/slideshow/load.gif"></span>
                        <div class="left" title="<?=_("上一张")?>" style="cursor:url(<?=MYOA_STATIC_SERVER?>/static/portal/group/style/images/slideshow/cur-left.cur.ico), auto; left:0;"></div>
                        <div class="right" title="<?=_("下一张")?>" style="cursor:url(<?=MYOA_STATIC_SERVER?>/static/portal/group/style/images/slideshow/cur-right.cur.ico), auto; right:0;"></div>
                        <?
                        if(!empty($content_files))
                        {
                            foreach($content_files as $file)
                            {
                                echo '<li>';
                                echo '<div class="img" style="background:url('.$file['file_url_view'].') center center no-repeat; width:855px; height:482px;"></div>';
                                echo '<div class="text">'.td_htmlspecialchars($file['description']).'</div>';
                                echo '</li>';
                            }
                        }
                       ?>

                  </ul>
                      <?
                      if(!empty($content_files))
                      {
                          //echo '<h5><strong>'._("附件:").'</strong>';
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
                          echo '<h5><strong>'._("附件:").'</strong>'.$files_html.'</h5>';
                      }
                      ?>
                <!-- 幻灯片 结束 -->
                </div>
  </div>
        </div>
<?
include_once('./footer.inc.php');
?>

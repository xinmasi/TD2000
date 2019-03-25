


    <div class="navbar">
      <div class="navbar-inner">
        <a class="brand" href="#">项目中心</a>
        <ul class="nav"> 
          <li class="<?php if($_GET['pageIndex'] == '1' || $_GET['pageIndex'] == null ) echo 'active'; ?>"><a href="/general/project/portal?pageIndex=1">项目列表</a></li>
          <li class="<?php if($_GET['pageIndex'] == '2') echo 'active'; ?>"><a href="/general/project/portal/new_page/monthPlan.php?pageIndex=2&dateType=month">月计划</a></li>
          <li class="<?php if($_GET['pageIndex'] == '3') echo 'active'; ?>"><a href="/general/project/portal/new_page/weekPlan.php?pageIndex=3&dateType=week">周计划</a></li>
          <li class="<?php if($_GET['pageIndex'] == '4') echo 'active'; ?>"><a href="/general/project/portal/new_page/dayPlan.php?pageIndex=4&dateType=day">日计划</a></li>
          <li class="<?php if($_GET['pageIndex'] == '5') echo 'active'; ?>"><a href="/general/project/portal/new_page/dayPlan.php?pageIndex=4&dateType=day">报表统计</a></li>
        </ul>
      </div>
    </div>
   
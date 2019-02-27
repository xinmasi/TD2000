


    <div class="navbar">
      <div class="navbar-inner">
        <a class="brand" href="#">项目中心</a>
        <ul class="nav"> 
          <li class="<?php if($_GET['pageIndex'] == '1' || $_GET['pageIndex'] == null ) echo 'active'; ?>"><a href="/general/project/portal?pageIndex=1">项目列表</a></li>
          <li class="<?php if($_GET['pageIndex'] == '2') echo 'active'; ?>"><a href="/general/project/portal/new_page/monthPlan.php?pageIndex=2">月计划</a></li>
          <li class="<?php if($_GET['pageIndex'] == '3') echo 'active'; ?>"><a href="/general/project/portal/new_page/weekPlan.php?pageIndex=3">周计划</a></li>
          <li class="<?php if($_GET['pageIndex'] == '4') echo 'active'; ?>"><a href="/general/project/approve?pageIndex=4">日计划</a></li>
        </ul>
      </div>
    </div>
   
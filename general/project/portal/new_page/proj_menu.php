


    <div class="navbar">
      <div class="navbar-inner">
        <a class="brand" href="#">��Ŀ����</a>
        <ul class="nav"> 
          <li class="<?php if($_GET['pageIndex'] == '1' || $_GET['pageIndex'] == null ) echo 'active'; ?>"><a href="/general/project/portal?pageIndex=1">��Ŀ�б�</a></li>
          <li class="<?php if($_GET['pageIndex'] == '2') echo 'active'; ?>"><a href="/general/project/portal/new_page/monthPlan.php?pageIndex=2&dateType=month">�¼ƻ�</a></li>
          <li class="<?php if($_GET['pageIndex'] == '3') echo 'active'; ?>"><a href="/general/project/portal/new_page/weekPlan.php?pageIndex=3&dateType=week">�ܼƻ�</a></li>
          <li class="<?php if($_GET['pageIndex'] == '4') echo 'active'; ?>"><a href="/general/project/portal/new_page/dayPlan.php?pageIndex=4&dateType=day">�ռƻ�</a></li>
          <li class="<?php if($_GET['pageIndex'] == '5') echo 'active'; ?>"><a href="/general/project/portal/new_page/dayPlan.php?pageIndex=4&dateType=day">����ͳ��</a></li>
        </ul>
      </div>
    </div>
   
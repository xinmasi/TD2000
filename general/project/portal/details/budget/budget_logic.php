<html>
<body>
<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#Pzone" data-toggle="tab"><?=_("��״ͼ")?></a>
        </li>
        <li>
            <a href="#Hzone" data-toggle="tab"><?=_("��״ͼ")?></a>
        </li>
        
         <li>
            <a href="#Tzone" data-toggle="tab"><?=_("��    ��")?></a>
        </li>
        

        <li class="pull-right" >

            <a href="budget_new.php?VALUE=4&PROJ_ID=<?=$i_proj_id?>"><?=_("�����ʽ�")?></a>
                <!--<a href="../notice.php?type=back"><?=_("�����ʽ�")?></a>-->
  
                
        <li>
        

        
    </ul>
    <div class="tab-content">
        <!-- ��ͼ -->
        <div class="tab-pane " id="Tzone" style="overflow-y:auto;height:90%">
            <? include_once("budget/table.php");?>
        </div>
        <!-- ��ͼ -->
        <div class="tab-pane " id="Hzone" style="height:90%" >
            <? include_once("budget/histogram.php");?>
        </div>
        <!-- ��ͼ -->
        <div class="tab-pane active" id="Pzone" style="height:90%; overflow:hidden">
            <? include_once("budget/pie.php");?>
        </div>
    </div>
</div>
</body>
</html>

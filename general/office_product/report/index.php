<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�칫��Ʒ����");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/reportform.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
    
<style>
    html,body{
        overflow:hidden;
        height:100%;        
    }  
</style>    

<body class="reportform">
<div class="reportform-menu">
    <div class="bs-docs-sidebar" style=" margin: 10px; ">
        <ul class="nav nav-list bs-docs-sidenav">
          <li><a href="main.php?url=product_info.php" target="main"><i class="icon-chevron-right" ></i><?=_("��Ʒ�ܱ�")?></a></li>
          <li><a href="main.php?url=purchase.php&TRANS_FLAG=0" target="main"><i class="icon-chevron-right" ></i><?=_("�ɹ���Ʒ����")?></a></li>
          <li><a href="main.php?url=dept_Sum.php" target="main"><i class="icon-chevron-right" ></i><?=_("���š���Ա������Ʒ����")?></a></li>
          <li><a href="main.php?url=borrow_sum.php&TRANS_FLAG=2"  target="main"><i class="icon-chevron-right" ></i><?=_("������Ʒ����")?></a></li>
          <li><a href="main.php?url=borrow_sum.php&TRANS_FLAG=3"  target="main"><i class="icon-chevron-right" ></i><?=_("�黹��Ʒ����")?></a></li>
          <li><a href="main.php?url=noreturn.php" target="main"><i class="icon-chevron-right" ></i><?=_("δ�黹��Ʒ����")?></a></li>
          <li><a href="main.php?url=report.php&TRANS_FLAG=4" target="main"><i class="icon-chevron-right" ></i><?=_("������Ʒ����")?></a></li>
          <li><a href="main.php?url=repair.php" target="main"><i class="icon-chevron-right" ></i><?=_("ά����¼����")?></a></li>
          <li><a href="main.php?url=ledger.php" target="main"><i class="icon-chevron-right" ></i><?=_("̨�˱���")?></a></li>
        </ul>
    </div>
</div>
<div class="repotform-right">
    <iframe class="mainframe repotform-iframe" name="main" src="main_down.php" 
          border="0" frameborder="0" framespacing="0" marginheight="0" marginwidth="0" ></iframe>
</div>
</body>  
</html>
 
<script type="text/javascript">
    $('.nav li').click(function(e){
        $('.nav li').removeClass('active');
        $(this).addClass('active');   
    });
</script>


 
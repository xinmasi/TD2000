<?
include_once ("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����칫��Ʒ��Ϣ" );
include_once ("inc/header.inc.php");
?>
<script Language="JavaScript">
function CheckForm2()
{
    if(document.form2.EXCEL_FILE.value=="")
    {
        alert("<?=_("��ѡ��Ҫ������ļ���")?>");
        return false;
    }
    if(document.form2.EXCEL_FILE.value!="")
    {
        var file_temp=document.form2.EXCEL_FILE.value,file_name;
        var Pos;
        Pos=file_temp.lastIndexOf("\\");
        file_name=file_temp.substring(Pos+1,file_temp.length);
        document.form2.FILE_NAME.value=file_name;
    }
    return true;
}

</script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/product.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>

<body class="bodycolor" style="background: white;">
<div class="product_main_div">
    <div class="wrap_top_info">
        <h3><?=_("�칫��Ʒ��Ϣ����")?></h3>
    </div>
    <div align="center">
        <div style="margin: 0 auto; width: 300px;">
            <span style="color: green; font-size: 14px; font-weight: bold; padding: 0px;"><?=_("��ָ�����ڵ����Excel�ļ�")?></span>
        </div>
        <div>
            <form name="form2" method="post" action="import.php" enctype="multipart/form-data" onSubmit="return CheckForm2();">
                <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
                <input type="hidden" name="FILE_NAME">
                <input type="hidden" name="GROUP_ID" value="<?=$GROUP_ID?>">
                <input type="submit" value="<?=_("����")?>" class="btn btn-info">
            </form>
        </div>
        <div>
            <span style="color: green;"><?=_("��ʹ�ð칫��Ʒ��Ϣģ�嵼�����ݣ�")?></span>
            <a href="#" onClick="window.location='templet_export.php'">
                <?=_("�칫��Ʒ��Ϣģ������")?>
            </a>
        </div>
    </div>
    <div class="alert clear" style="margin: 0 auto; width: 320px; margin-top: 15px;">
        <button type="button" class="close" data-dismiss="alert" title='<?=_('�ر�')?>'>&times;</button>
        <strong style="color: rgb(52, 52, 87); font-size: 16px; font-weight: bold;"><?=_("˵��:")?></strong><br />
        <p style="color: green;">
            <span>
                <?=_("1��ģ���еĵǼ�Ȩ��(�û�)�������ˡ��������������Ϊ�գ�Ӧ���������ʵ������ע�Ᵽ֤����û���ظ���")?><br>
                <?=_("2��ģ���а칫��Ʒ�⡢�칫��Ʒ����������ڰ칫��Ʒ����д��ڣ�")?><br>
                <?=_("3��ģ���а칫��Ʒ���ơ����/�ͺš���Ʒ�⡢��Ʒ��𡢼۸�����ڰ칫��Ʒ�ظ�ʱ������¿��ڰ칫��Ʒ��Ϣ���������д��")?>
            </span>
        </p>
    </div>
</div>
</body>
</html>
function depositoryOfType(id)
{
    if(id != '-1')
    {
        var url_data = "id="+id;
        $.ajax({
            type: "POST",
            url: "type_ajax.php",
            data: url_data,
            success: function(data){
                $('#OFFICE_TYPE').html(data);
            }
        });

        $('#DEPOSITORY_ID').val(id);
    }
    else
    {
        $('#OFFICE_TYPE').html('<select name="OFFICE_PROTYPE" id = "OFFICE_PROTYPE"  onchange = "depositoryOfProducts(this.value);"><option value="-1">'+td_lang.general.msg_5+'</option></select>');
    }
}
function depositoryOfProducts(id)
{
    if(id != '-1')
    {
        var url_data = "id="+id;
        $.ajax({
            type: "POST",
            url: "products_ajax.php",
            data: url_data,
            success: function(data){
                $('#OFFICE_PRODUCTS').html(data);
            }
        });
    }
    else
    {
        $('#OFFICE_PRODUCTS').html('<select name="PRO_ID" class="BigSelect"><option value="-1">'+td_lang.general.msg_5+'</option></select> ');
    }
}
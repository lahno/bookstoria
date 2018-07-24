$(document).ready(function() {

    /*
    * CREATE ORDER
     */
    $('#order_create').on('click', function (e) {
        e.preventDefault();
        var modal = $('#modalCreateOrder');

        $.ajax({
            type: 'GET',
            url: '/order/create/'+$(this).attr('book-id'),
            data: '',
            processData: true,
            contentType: false,
            beforeSend: function(){

            },
            success: function(data) {
                console.log(data);
                if(data.status){
                    modal.find('#myModalLabel').text(data.order.description);
                    modal.find('.payment_btn').find('span').text('(' + data.data.ext1 + ')');

                    modal.find("input[name='payment']").val(data.data.payment);
                    modal.find("input[name='key']").val(data.data.key);
                    modal.find("input[name='url']").val(data.data.url);
                    modal.find("input[name='data']").val(data.data.data);
                    modal.find("input[name='sign']").val(data.data.sign);
                    modal.find("input[name='ext1']").val(data.data.ext1);
                    modal.find("input[name='ext2']").val(data.data.ext2);
                    modal.modal('show');
                }else{
                    modal_succes.find('.modal_success__body p').text('');
                    modal_succes.find('.modal_success__bt p').text('');
                }
            },
            error:function (xhr){

            },
            complete: function(){

            }
        });

    });

    $('#register_form').on('submit', function(e){
        e.preventDefault();
        var modal = $('#modal_reg');
        var modal_succes = $('#modal_success');
        var form = $(this);
        var f = form[0];
        var fd = new FormData(f);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: fd,
            processData: false,
            contentType: false,
            beforeSend: function(){
                form.find("input[type='submit']").addClass('disabled');
            },
            success: function(data) {
                console.log(data);
                if(data.status){
                    modal_succes.find('.modal_success__body p').text(data.body);
                    modal_succes.find('.modal_success__bt p').text(data.bt);
                    clearInput(form);
                }else{
                    modal_succes.find('.modal_success__body p').text('');
                    modal_succes.find('.modal_success__bt p').text('');
                }
                modal.modal('hide');
                modal_succes.find('.modal_success__title h3').text(data.title);
                modal_succes.modal('show');
            },
            error:function (xhr){
                if( xhr.status === 422 ) {
                    var errors = $.parseJSON(xhr.responseText);
                    $.each(errors, function (key, val) { // val[0]
                        form.find("input[name='"+ key +"']").parents('.inp-group').addClass('inp-error');
                        form.find("select[name='"+ key +"']").parents('.inp-group').addClass('inp-error');
                    });
                }
            },
            complete: function(){
                form.find("input[type='submit']").removeClass('disabled');
            }
        });
    });

    $('#select_glav').on('change', function () {
        let selected = $(this).find('option:selected');
        if (selected.attr('link-url') !== null){
            location.href = selected.attr('link-url');
        }
    })

});
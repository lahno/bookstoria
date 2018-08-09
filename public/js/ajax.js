$(document).ready(function() {

    /*
    * CREATE ORDER
     */
    $('#order_create').on('click', function (e) {
        e.preventDefault();
        let modal = $('#modalCreateOrder');

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
    $('#order_user_create').on('click', function (e) {
        e.preventDefault();
        let modal = $('#modalCreateUserOrder');
        modal.modal('show');
    });
    $('#formCreateUserOrder').on('submit', function(e){
        e.preventDefault();
        let modal = $(this).parents('.modal'),
            book_id = $('#order_user_create').attr('book-id'),
            form = $(this),
            f = form[0],
            fd = new FormData(f);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: fd,
            processData: false,
            contentType: false,
            beforeSend: function(){
                form.find("input[type='submit']").addClass('disabled');
                form.find('.form-group p').text();
            },
            success: function(data) {
                console.log(data);
                if(data.auth){
                    modal.modal('hide');
                    let modal_next = $('#modalCreateOrder');
                    $.ajax({
                        type: 'GET',
                        url: '/order/create/'+book_id,
                        data: {user:data.user},
                        processData: true,
                        contentType: false,
                        success: function(data) {
                            console.log(data);
                            if(data.status){
                                modal_next.find('#myModalLabel').text(data.order.description);
                                modal_next.find('.payment_btn').find('span').text('(' + data.data.ext1 + ')');

                                modal_next.find("input[name='payment']").val(data.data.payment);
                                modal_next.find("input[name='key']").val(data.data.key);
                                modal_next.find("input[name='url']").val(data.data.url);
                                modal_next.find("input[name='data']").val(data.data.data);
                                modal_next.find("input[name='sign']").val(data.data.sign);
                                modal_next.find("input[name='ext1']").val(data.data.ext1);
                                modal_next.find("input[name='ext2']").val(data.data.ext2);
                                modal_next.modal('show');
                            }
                        }
                    });
                }else{
                    form.find('.form-group').addClass('inp-error').find('.invalid-feedback').css('display', 'block').find('strong').text('Ошибка запроса!');
                }
            },
            error:function (xhr){
                if( xhr.status === 422 ) {
                    let errors = $.parseJSON(xhr.responseText);
                    // console.log(errors.errors);
                    $.each(errors.errors, function (key, val) { // val[0]
                        form.find("input[name='"+ key +"']").parents('.form-group').addClass('inp-error').find('.invalid-feedback').css('display', 'block').find('strong').text(val[0]);
                    });
                }
            },
            complete: function(){
                form.find("input[type='submit']").removeClass('disabled');
            }
        });
    });


    $('#register_form').on('submit', function(e){
        e.preventDefault();
        let modal = $('#modal_reg'),
            modal_succes = $('#modal_success'),
            form = $(this),
            f = form[0],
            fd = new FormData(f);
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
                    let errors = $.parseJSON(xhr.responseText);
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
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', '.quickView', function() {
        let id = $(this).data('id')
        $.ajax({
            url: "{{ route('quickview')}}",
            type: 'post',
            data: {
                id: id,

            },
            success: function(data) {
                if (data) {
                    $('.data-quickview').empty();
                    $('.data-quickview').html(data);
                    $('#viewproduct-over').modal('show');
                }
            }
        });
    })
    $(document).on('click', '#addCart', function() {
        let id = $(this).data('id')
        let sl = $('input[name="addSl"]').val()
        let size = $('input[name="sizeRadio"]')
        let slSize = null;
        if (size.length > 0) {
            for (let i = 0; i < size.length; i++) {
                if (size[i].checked) {
                    slSize = size[i].value
                }
            }
        }
        if (sl > 0) {
            $.ajax({
                url: '{{ route("add.cart")}}',
                type: 'post',
                data: {
                    id: id,
                    sl: sl,
                    size: slSize
                },
                success(data) {
                    loadCart(data);
                    loadCartItem(data);
                    $('#viewproduct-over').modal('hide');
                    toastr.options.timeOut = 30;
                    toastr.success('Đã thêm món');

                }
            })
        }
    })
    $(document).on('click', '#delItemCart', function(e) {
        e.preventDefault();
        let keyCart = $(this).data('id');
        $.ajax({
            url: '{{ route("del.cart")}}',
            type: 'post',
            data: {
                keyCart: keyCart,
            },
            success(data) {
                loadCart(data);
                loadCartItem(data);
                toastr.options.timeOut = 30;
                toastr.options = {
                    "timeout" : 30,
                    "positionClass": "toast-top-left",
                }
                toastr.error('Đã xoá món');

            }
        })
    })

    function loadCart(data) {
        $(".item-cart").empty();
        $(".item-cart").html(data);
        if ($('#totalQuanty').val()) {
            $('.cart_counter').text($("#totalQuanty").val());
        } else {
            $('.cart_counter').text(0);
        }
        if ($('#totalPrice').data('price')) {
            $('.carsub').text($('#totalPrice').data('price'));
        } else {
            $('.carsub').text(' 0đ');
        }
    }

    function loadCartItem(data) {
        $("#cart").empty();
        $("#cart").html(data);
        if ($('#totalQuanty1').val()) {
            $('#priceTotal').text('(' + $("#totalQuanty1").val() + ' Món)');
        } else {
            $('#priceTotal').text(0);
        }
    }
    $(document).on('click', '#upCart', function(e) {
        e.preventDefault();
        let keyCart = $(this).data('key');
        $.ajax({
            url: '{{ route("get.upCart")}}',
            type: 'post',
            data: {
                keyCart: keyCart,
            },
            success(data) {
                if (data) {
                    $('.data-quickview').empty();
                    $('.data-quickview').html(data);
                    $('#viewproduct-over').modal('show');
                }
            }
        })
    })

    $(document).on('click', '#updateCart', function() {
        let id = $(this).data('id')
        let key = $(this).data('key')
        let sl = $('input[name="addSl"]').val()
        let size = $('input[name="sizeRadio"]')
        let slSize = null;
        if (size.length > 0) {
            for (let i = 0; i < size.length; i++) {
                if (size[i].checked) {
                    slSize = size[i].value
                }
            }
        }
        if (sl > 0) {
            $.ajax({
                url: '{{ route("postup.cart")}}',
                type: 'post',
                data: {
                    key: key,
                    id: id,
                    sl: sl,
                    size: slSize
                },
                success(data) {
                    loadCart(data);
                    loadCartItem(data);
                    $('#viewproduct-over').modal('hide');
                    toastr.options.timeOut = 30;
                    toastr.success('Đã cập nhật');
                }
            })
        }
    })


    //đăng nhập với tài khoản 
    $(document).on('click', '#loginAcc', function(e) {
        e.preventDefault();
        let email = $('.emailAcc').val();
        let password = $('.passwordAcc').val();
        $.ajax({
            url: "{{ route('post.login')}}",
            type: 'post',
            data: {
                email: email,
                password: password

            },
            success: function(data) {
                if (data == true) {
                    location.reload();
                    toastr.options.timeOut = 30;
                    toastr.success('Đăng nhập thành công');
                } else {
                    $('.massage').empty()
                    $('.massage').append(data.loginAcc)
                    $('.massage').show().delay(3000).fadeOut()
                }

            }
        });
    })

    $(document).on('click', '.sendComments', function(e) {
        e.preventDefault()
        let textContent = $('.content-commment')
        let content = textContent.val()
        let url = $(this).attr('href');
        let list_commment = $('.review-list');

        if(content) {
            $.ajax({
            url: url,
            type: 'post',
            data: {
                content: content,
            },
            success: function(data) {
              if(data) {
                  list_commment.html(data)
              }
            }
        });
        } else {
            textContent.addClass('danger')
            toastr.error('Bình luận không được bỏ trống.')
        }
    })

    $(document).on('click', '.reply_commment', function(e){
        e.preventDefault()
        let id_rep = $(this).data('id')
        $('.form-rep-'+id_rep).slideToggle()
    })

    $(document).on('click', '.sendCommentsReply',function(e){
        e.preventDefault()
        let id = $(this).data('id')
        let textContent = $('.content-'+id)
        let content = textContent.val()
        let url = $(this).attr('href');
        let list_commment = $('.review-list');

        if(content) {
            $.ajax({
            url: url,
            type: 'post',
            data: {
                content: content,
                id_reply : id
            },
            success: function(data) {
              if(data) {
                  console.log(data)
                  list_commment.html(data)
              }
            }
        });
        } else {
            textContent.addClass('danger')
            toastr.error('Bình luận không được bỏ trống.')
        }

    })

    $(document).on('click', '.filter .toolbar .search', function(){
        let form_search = $('#form-search').modal('show')
    })
    $(document).on('input ', '#keyword_search', function(e){
        e.preventDefault()
        let keyword = $(this).val()
        $.ajax({
            url:  "{{ route('get.search')}}",
            type: 'post',
            data: {
                keyword: keyword,
            },
            success: function(data) {
              if(data) {
                $('.list-search').empty()
                  $('.list-search').fadeIn('slow')
                  $('.list-search').html(data)
              }
            }
        });
    })
</script>
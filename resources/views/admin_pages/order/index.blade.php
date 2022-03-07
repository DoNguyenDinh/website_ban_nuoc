@extends('templates.admins.layout')
@section('content')
@if(session()->has('message'))
<script>
    alertify.success("{{session()->get('message')}}", 1);
</script>
@endif
<div class="container-fluid">
    <h4 class="h4 mb-2 text-gray-800 mg-tb">Quản Lý Đơn Hàng</h4>
    <div class="card shadow mb-4 ">
        <div class="card-body">
            <div class="row nav-p">
                <div class="col d-flex form-style">
                    <form class="">
                        <div class="dis-inline">
                            <input type="text" class="form-control" value="{{ Request::get('content')}}" name="content" placeholder="Tên hoặc mã đơn hàng">
                        </div>
                        <div class="dis-inline">
                            <select class="form-control" name="status">
                                <option value="10">Trạng thái</option>
                                <option value="1" {{Request::get('status') == 1 ? "selected" : ""}}>Tiếp nhận</option>
                                <option value="2" {{Request::get('status') == 2 ? "selected" : ""}}>Đang xử lí</option>
                                <option value="3" {{Request::get('status') == 3 ? "selected" : ""}}>Đang vận chuyển</option>
                                <option value="4" {{Request::get('status') == 4 ? "selected" : ""}}>Đã giao</option>
                                <option value="-1" {{Request::get('status') == -1 ? "selected" : ""}}>Đã huỷ</option>
                            </select>
                        </div>
                        <div class="dis-inline">
                            <select class="form-control" name="payment">
                                <option value="10">Thanh toán</option>
                                <option value="1" {{Request::get('payment') == 1 ? "selected" : ""}}>Chờ thanh toán</option>
                                <option value="2" {{Request::get('payment') == 2 ? "selected" : ""}}>Đã thanh toán</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                    </form>

                </div>
            </div>
            <div class="table-responsive">
                <table class="" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 28%;">Thông tin đơn hàng</th>
                            <th>Tổng tiền</th>
                            <th>Ngày mua</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="data-mat">
                        @if($order && count($order))
                        @foreach($order as $value)
                        <tr class="order-table">
                            <td colspan="6">
                                <div class="row-header">
                                    <span class="">+ {{$value->hoten}}</span>
                                    <div class="order-col">
                                        <span class="col-w"><i class="fa fa-tasks text-primary" aria-hidden="true"></i> Mã đơn hàng : <span class="text-danger">#{{$value->madh}}</span></span>
                                        <span class="col-w"><i class="fa fa-users text-danger"></i>
                                            Đơn hàng tạo bởi : <span class="text-primary">{{ ($value->id_nhanvien) ? $value->id_nhanvien : 'Mua Online'}}</span></span>
                                    </div>
                                </div>
                                <div class="border-body">
                                    <div class="col-w">
                                        <ul>
                                            <li>{{ $value->dienthoai}}</li>
                                            <li>{{ $value->email}}</li>
                                            <li>{{ $value->diachi}}</li>
                                        </ul>
                                    </div>
                                    <div class="col-w">
                                        {{currency_format($value->tongtien)}}
                                    </div>
                                    <div class="col-w">
                                        {{format_date($value->ngaytao)}}
                                    </div>
                                    <div class="col-w">
                                        @if($value->trangthaithanhtoan == 1)
                                        <div class="badge badge-warning">Chờ thanh toán</div>
                                        @else
                                        <div class="badge badge-success">Đã thanh toán</div>
                                        @endif
                                    </div>
                                    <div class="col-w">
                                        <div class="badge badge-{{ $value->getStatus($value->trangthai)['class']}} ">
                                            {{ $value->getStatus($value->trangthai)['name']}}
                                        </div>
                                    </div>
                                    <div class="col-w">

              

                                        <div class="dropdown action">
                                            <button class="btn btn-primary dropdown-toggle" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cập nhật</button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href=" {{ route('get.action', ['process', $value->id])}}"><i class="fa fa-refresh" aria-hidden="true"></i> Đã xử lí</a>
                                                <a class="dropdown-item" href="{{ route('get.action', ['transport', $value->id])}}"><i class="fa fa-truck" aria-hidden="true"></i> Đang vận chuyển</a>
                                                <a class="dropdown-item" href="{{ route('get.action', ['success', $value->id])}}"><i class="fa fa-check" aria-hidden="true"></i> Đã giao</a>
                                                <a class="dropdown-item" href="{{ route('get.action', ['cancel', $value->id])}}"><i class="fa fa-ban" aria-hidden="true"></i> Huỷ</a>
                                            </div>
                                        </div>

                                        <a data-madh="{{ $value->madh}}" href=" {{ route('get.viewDetail', $value->id)}}" data-pdf="{{ route('print.order',$value->madh)}}" class="btn btn-primary mgr-5" id="viewDetail">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('get.update', $value->madh)}}" class="btn btn-info mgr-5" id="edit">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>

                                        <a href=" {{ route('get.del',$value->id)}}" class="btn btn-danger mgr-5" id="edit">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>

                </table>
                {!! $order->appends($query)->links() !!}

            </div>

        </div>
    </div>




</div>

<!-- chi tiết đơn hàng  -->
<div class="modal fade " id="OrderDetail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đơn hàng <b id="madonhang"></b></h5>
                <button type="button " class="close" data-dismiss="modal" aria-label="Close">
                    <span class="close_modal" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <a href="" id="pdf" target="_blank" class="btn btn-warning close_modal"><i class="fa fa-print"></i> In đơn hàng</a>
                <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    //hiển thị chi tiết đơn hàng 
    $(document).on('click', '#viewDetail', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let madh = $(this).attr('data-madh');
        $('#madonhang').html('#' + madh);
        $('#pdf').attr('href', $(this).attr('data-pdf'))
        $.ajax({
                url: url,
            })
            .done(function(results) {
                $('#OrderDetail .modal-body').html(results.html);
                $('#OrderDetail').modal('show');
            });

    });
    $(document).on('click', '.close_modal', function(e) {

        $('#OrderDetail').modal('hide');

    });
</script>
@stop
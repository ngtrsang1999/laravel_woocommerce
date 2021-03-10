@extends('adminlte::page')

@section('title', 'WooCommerce')

@section('content_header')
@stop

@section('content')
    <div class="alert alert-success alert-dismissible fade show d-none" id="alertSyncWooCommerceSuccess">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success!</strong> <span class="contentAlertEditStudent">Sync orders is done</span>
    </div>

    <div class="alert alert-danger alert-dismissible fade show d-none" id="alertSyncWooCommerceWarning">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Warning!</strong> <span class="contentAlertSyncWooCommerce"></span>
    </div>
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> {{Session::get('success')}}
        </div>
    @endif
    @if(Session::has('warning'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Warning!</strong> {!!Session::get('warning') !!}.
        </div>
    @endif

    <div class="">
        <form action="/woocommerce/authorize" class="mb-3" style="max-width: 800px">
            <div class="form-group">
              <label for="title">Title Store:</label>
              <input type="text" class="form-control" placeholder="Enter title" id="title" name="title">
              @if($errors->has('title'))
                    <div class="mt-1 text-danger">{{ $errors->first('title') }}</div>
                @endif
            </div>
            <div class="form-group">
              <label for="url">Url Store:</label>
              <input type="text" class="form-control" placeholder="Enter url" id="url" name="url">
              @if($errors->has('url'))
                    <div class="mt-1 text-danger">{{ $errors->first('url') }}</div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Connect</button>
          </form>
    <table class="table table-bordered text-center table-striped">
        <thead class="">
        <tr>
            <th>STT</th>
            <th>Title</th>
            <th>Url</th>
            <th>Status</th>
            <th>Create_product</th>
            <th>Sync_order</th>
            <th>Tracking</th>
            <!-- <th>Connect at</th> -->
            <th>Sync at</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="myTable">
            @foreach ($stores as $store)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$store->title}}</td>
                    <td>{{$store->url}}</td>
                    <td>{{$store->status}}</td>
                    <td>{{$store->create_product ?? "none"}}</td>
                    <td>{{$store->sync_order ?? "none"}}</td>
                    <td>{{$store->tracking ?? "none"}}</td>
                    <!-- <td>{{date_format(date_create($store->updated_at),"d/m/Y H:i:s")}}</td> -->
                    <td>{{empty($store->sync_at)? 'Not yet synchronized' : date_format(date_create($store->sync_at),"d/m/Y H:i:s")}}</td>
                    <!-- <td>
                        <a class="text-reset button btn btn-outline-primary" href="{{route('woocommerce.sync', ['store_id' => $store->id])}}">
                            Sync<div class="spinner-border text-info ml-1 d-none spinner-sync" style="width: 1.3rem; height: 1.3rem;"></div>
                        </a>
                    </td> -->
                    <td>
                        <a class="text-reset button btn btn-outline-primary btnSyncWooCommerce" type="button" data-store-id= "{{$store->id}}">
                            Sync<div class="spinner-border text-info ml-1 d-none spinner-sync" style="width: 1.3rem; height: 1.3rem;"></div>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
  <script>
        $(document).ready(function () {
            $(".btnSyncWooCommerce").on("click", function () {
                $(this).children(".spinner-sync").removeClass('d-none');
                var storeId = $(this).attr("data-store-id");
                syncWooCommerce(storeId);
            });
        });


        function syncWooCommerce(storeId) {
            $.ajax({
                url: "/woocommerce/sync/" + storeId,
                success: function (data) {
                    var result = JSON.parse(data);
                    console.log(result);
                    if (Boolean(result.status)) {
                        $('#alertSyncWooCommerceSuccess').removeClass('d-none');
                        setTimeout(hideAlertSyncWooCommerce, 6000, true);
                    } else {
                        $('#alertSyncWooCommerceWarning .contentAlertSyncWooCommerce').text(result.msg);
                        $('#alertSyncWooCommerceWarning').removeClass('d-none')
                        setTimeout(hideAlertSyncWooCommerce, 6000, false);
                    }
                    $('.btnSyncWooCommerce[data-store-id=' + storeId + '] .spinner-sync').addClass('d-none');
                },
                error: function (e) {
                    $('#alertSyncWooCommerceWarning .contentAlertSyncWooCommerce').text(e.statusText);
                    $('#alertSyncWooCommerceWarning').removeClass('d-none')
                    setTimeout(hideAlertSyncWooCommerce, 6000, false);
                    $('.btnSyncWooCommerce[data-store-id=' + storeId + '] .spinner-sync').addClass('d-none');
                }
            });
        }

        function hideAlertSyncWooCommerce(status) {
            if (status) {
                $('#alertSyncWooCommerceSuccess').addClass('d-none');
            } else {
                $('#alertSyncWooCommerceWarning').addClass('d-none');
            }
        }
  </script>
@stop

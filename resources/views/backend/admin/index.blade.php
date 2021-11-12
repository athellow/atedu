@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
<div class="wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>账号管理</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">账号管理</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <form class="form-inline" id="searchForm" action="{{ route('admin.index') }}" method="GET">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-md" name="name" value="{{!empty($param['name']) ?  $param['name'] : ''}}" placeholder="name">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control form-control-md" name="email" value="{{!empty($param['email']) ?  $param['email'] : ''}}" placeholder="email">
                  </div>
                  <div class="form-group">
                    <button class="btn btn-md btn-primary" type="submit">
                      <i class="fa fa-search"></i> 查询
                    </button>
                  </div>
                  <div class="form-group">
                    <button onclick="clearSearchForm()" class="btn btn-md btn-default">
                      <i class="fa fa-eraser"></i> 清空
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header" id="button-card-header">
                <a title="添加" data-toggle="tooltip" class="btn btn-primary btn-md "  href="{{ route('admin.create') }}">
                    <i class="fa fa-plus"></i> 添加
                </a>
                <a class="btn btn-success btn-md ReloadButton" data-toggle="tooltip" title="刷新">
                    <i class="fa fa-spinner"></i> 刷新
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @include('backend.partials.errors')
                @include('backend.partials.success')
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{__('id')}}</th>
                    <th>{{__('name')}}</th>
                    <th>{{__('email')}}</th>
                    <th>{{__('created_at')}}</th>
                    <th>{{__('operate')}}</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($administrators as $index => $item)
                  <tr>
                    <td>{{$item['id']}}</td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['email']}}</td>
                    <td>{{$item['created_at']}}</td>
                    <td>
                    <a href="{{ route('admin.edit', $item['id']) }}"
                        class="btn btn-primary btn-xs" title="修改" data-toggle="tooltip">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-delete" 
                      title="删除" data-id="{{ $item['id'] }}" data-url="{{ route('admin.edit', $item['id']) }}"
                      onclick="modalValue({{$item['id']}})">
                        <i class="fa fa-trash"></i>
                    </button>
                    </td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="row card-footer clearfix">
                <div class="col-sm-12 col-md-5">
                  <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
                  Showing {{$administrators->firstItem()}} to {{$administrators->lastItem()}} of {{$administrators->total()}} entries
                  </div>
                </div>
                <div class="col-sm-12 col-md-7">
                  <div class="dataTables_paginate paging_simple_numbers float-right" id="example1_paginate">
                  {{ $administrators->links() }}
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    @include('backend.partials.modal')
</div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')

    <script>
        $(function () {
            $("#example1").DataTable({
                "paging": false,
                "info": false,
                "responsive": true, 
                "lengthChange": false, 
                "autoWidth": false,
                "searching": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#button-card-header');

            // $('#example2').DataTable({
            //     "paging": true,
            //     "lengthChange": false,
            //     "ordering": true,
            //     "info": true,
            //     "autoWidth": false,
            //     "responsive": true,
            // });
        });
    </script>
@stop

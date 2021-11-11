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
              <li class="breadcrumb-item"><a href="{{ url('admin')}}">首页</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin.index')}}">账号管理</a></li>
              <li class="breadcrumb-item active">编辑</li>
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
              <div class="card-header" id="button-card-header">
                <a title="返回" class="btn flat btn-md btn-default BackButton">
                  <i class="fa fa-arrow-left"></i>返回
                </a>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="{{ route('admin.update', $id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body offset-sm-1">
                  <div class="form-group">
                    <label for="role_ids" class="col-sm-2 col-form-label">角色</label>
                    <div class="col-sm-6">
                      <select multiple name="role_ids[]" class="custom-select {{ $errors->has('role_ids') ? 'is-invalid' : '' }}">
                        @foreach($roles as $index => $item)
                        <option value="{{ $item['id'] }}" @if(in_array($item['id'], old('role_ids', $admin->roles->pluck('id')->toArray()))) selected @endif>{{ $item['title'] }}</option>
                        @endforeach
                      </select>
                      @if($errors->has('role_ids'))
                      <div class="invalid-feedback">
                        <strong>{{ $errors->first('role_ids') }}</strong>
                      </div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="name" class="col-sm-2 col-form-label">姓名</label>
                    <div class="col-sm-6">
                      <input type="text" name="name" id="name" value="{{ old('name', $admin['name']) }}" placeholder="姓名"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                      @if($errors->has('name'))
                      <div class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                      </div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-2 col-form-label">邮箱</label>
                    <div class="col-sm-6">
                      <input type="email" name="email" id="email" value="{{ old('email', $admin['email']) }}" placeholder="邮箱"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" disabled>
                      @if($errors->has('email'))
                      <div class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                      </div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="password" class="col-sm-2 col-form-label">密码</label>
                    <div class="col-sm-6">
                      <input type="password" name="password" id="password" placeholder="密码"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" >
                      @if($errors->has('password'))
                      <div class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                      </div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 col-form-label" for="status">状态</label>
                    <div class="col-sm-6">
                      <div class="custom-control custom-switch .custom-switch-off-info">
                        <input type="checkbox" class="custom-control-input" name="status" id="status" value="1"
                          @if(old('status', $admin['status']) == 1) checked @endif>
                        <label class="custom-control-label" for="status">启用/禁用</label>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Submit</button>
                </div>
                <!-- /.card-footer -->
              </form>
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
</div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script>
        $(function () {
          
        });
    </script>
@stop

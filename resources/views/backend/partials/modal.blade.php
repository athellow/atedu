{{-- 确认删除 --}}
<div class="modal fade" id="modal-delete" tabIndex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">请确认</h4>
                <button type="button" class="close" data-dismiss="modal">
                    ×
                </button>
            </div>
            <div class="modal-body">
                <p class="lead">
                    <i class="fa fa-question-circle fa-lg"></i>
                </p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="modal-delete-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-times-circle"></i> 删除
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function modalValue(id) {
        $("#modal-delete-form").attr('action', "{{ url('admin/admin') }}" + '/' + id);
        $(".lead").html('<i class="fa fa-question-circle fa-lg"></i>确定要删除ID为 <b>' + id + '</b> 的数据吗?');
    }
</script>
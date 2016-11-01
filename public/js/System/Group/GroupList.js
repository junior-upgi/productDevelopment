$(function() {
    $("#AddForm").ajaxForm({
        url: url + '/SysOption/GroupSave',
        beforeSubmit: function() {
            //$('#btnSave').button('loading');
        },
        success: function(obj) {
            if (obj.success) {
                $('#AddModal').modal('hide');
                swal({
                        title: "儲存成功!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: false
                    },
                    function() {
                        $("#searchForm").submit();
                    });
            } else {
                swal("儲存失敗!", obj.msg, "error");
                $('#btnSave').button('reset');
            }
        },
        error: function(xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnAdd').button('reset');
        }
    });
});

function member(id, name) {
    $('#joinGroup').val(id);
    $('#JoinTitle').html('加入[' + name + ']群組成員');
    $('#memberTable').empty();
    getList(id);
    $('#JoinModal').modal('show');
}

function doAdd(json) {
    if (json == '') {
        $('#modalTitle').html('新增群組資料');
        $('#btnSave').html('新增');
        $('#type').val('add');
        $('#id').val('');
        $('#reference').val('');
    } else {
        json = JSON.parse(json);
        $('#modalTitle').html('編輯群組資料');
        $('#btnSave').html('更新');
        $('#type').val('edit');
        $('#id').val(json['ID']);
        $('#reference').val(json['reference']);
    }
    $('#AddModal').modal('show');
}

function doDelete(id) {
    swal({
            title: "刪除資料?",
            text: "此動作將會刪除資料!",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: '取消',
            confirmButtonClass: "btn-danger",
            confirmButtonText: "刪除",
            closeOnConfirm: false
        },
        function() {
            var data = {
                'type': 'delete',
                'id': id
            };
            $.ajax({
                url: url + '/SysOption/GroupSave',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                error: function(xhr) {
                    swal("刪除失敗!", xhr.statusText, "error");
                },
                success: function(result) {
                    if (result.success) {
                        swal({
                                title: "刪除資料成功!",
                                text: result.msg,
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: false
                            },
                            function() {
                                $("#searchForm").submit();
                            });
                    } else {
                        swal("刪除資料失敗!", result.msg, "error");
                    }
                }
            })
        });
}
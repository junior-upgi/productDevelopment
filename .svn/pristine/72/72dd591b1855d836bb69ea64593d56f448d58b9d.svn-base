function DoInsert() {
    $("#AddProjectForm").ajaxForm({
        url: url + '/Project/InsertProject',
        beforeSubmit: function () {
            //$('#BtnSave').attr('disabled', 'disabled');
            $('#BtnSave').button('loading');
            //$.blockUI({ message: '<div>送出資料中請稍候...</div>' });
        },
        success: function (obj) {
            if (obj.success) {
                swal({
                    title: "新增資料成功!",
                    text: obj.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                function()
                {
                    document.location.href = url + '/Project/ProjectList';
                });
            }
            else {
                swal("新增資料失敗!", obj.msg.errorInfo[2], "error");
                $('#BtnSave').button('reset');
            }
        },
        error: function (obj) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnSave').button('reset');
        }
    });
}
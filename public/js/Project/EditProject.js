function DoUpdate() {
    $("#EditProjectForm").ajaxForm({
        url: url + '/Project/UpdateProject/',
        beforeSubmit: function () {
            //$('#BtnSave').attr('disabled', 'disabled');
            //$.blockUI({ message: '<div>送出資料中請稍候...</div>' });
            $('#BtnSave').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                swal({
                    title: "更新資料成功!",
                    text: obj.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                function () {
                    document.location.href = url + '/Project/ProjectList';
                });
            } else {
                swal("新增資料失敗!", obj.msg.errorInfo[2], "error");
                $('#BtnSave').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnSave').button('reset');
        }
    });
}
function GetSales() {
    var NodeID = $("#NodeID").find(":selected").val();
    $.ajax({
        url: url + '/Project/GetStaffByNodeID/' + NodeID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            $("#SalesID option").remove();
            if (result.length > 0) {
                $("#SalesID").append($("<option></option>").attr("value", "").text("請選擇"));
                for (i = 0; i < result.length; i++) {
                    $("#SalesID").append($("<option></option>").attr("value", result[i].ID).text(result[i].name));
                }
            }
        }
    })
}
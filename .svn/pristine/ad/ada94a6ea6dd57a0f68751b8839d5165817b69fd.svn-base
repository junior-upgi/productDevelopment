function DoUpdate() {
    var ProjectID = $("#ProjectID").val();
    $("#EditProjectForm").ajaxForm({
        url: '/Project/UpdateProject/' + ProjectID,
        beforeSubmit: function () {
            //$('#BtnSave').attr('disabled', 'disabled');
            //$.blockUI({ message: '<div>送出資料中請稍候...</div>' });
            $('#BtnSave').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                alert(obj.msg);
                document.location.href = '/Project/ProjectList';
            }
            else {
                alert(obj.msg.errorInfo[2])
                $('#BtnSave').button('reset');
            }
        }, 
        error: function (obj) {
            alert('發生異常錯誤!!');
            $('#BtnSave').button('reset');
        }
    });
}
function GetSales() {
    var NodeID = $("#NodeID").find(":selected").val();
    $.ajax({
        url: '/Project/GetStaffByNodeID/' + NodeID,
        type: 'GET',
        dataType: 'JSON',
        error: function(xhr) {
            alert('取得業務員資料失敗!\n ERROR:' + xhr.statusText);
        },
        success: function(result) {
            $("#SalesID option").remove();
            if (result.length > 0) {
                $("#SalesID").append($("<option></option>").attr("value", "").text("請選擇"));
                for (i = 0; i < result.length ; i++)
                    $("#SalesID").append($("<option></option>").attr("value", result[i].id).text(result[i].name));
            }
        }
    })
}
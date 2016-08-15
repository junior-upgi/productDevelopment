$(document).ready(function(){
    var timeInMs = new Date();
    timInMs = timeInMs.getFullYear() + "-" + (timeInMs.getMonth()+1) + "-" + timeInMs.getDate();
    $(".date").datetimepicker({
        format: 'yyyy-mm-dd',
        startDate: timInMs,
        minView: 2,
        maxView: 4,
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        language: 'zh-TW'
    });
});
function DoInsert() {
    var ProjectID = $("#ProjectID").val();
    $("#AddProductForm").ajaxForm({
        url: '/Product/InsertProduct',
        beforeSubmit: function () {
            //$('#BtnSave').attr('disabled', 'disabled');
            $('#BtnSave').button('loading');
            //$.blockUI({ message: '<div>送出資料中請稍候...</div>' });
        },
        success: function (obj) {
            if (obj.success) {
                alert(obj.msg);
                document.location.href = '/Product/ProductList/' + ProjectID;
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
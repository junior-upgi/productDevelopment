function DoInsert() {
    $("#AddProjectForm").ajaxForm({
        url: '/Project/InsertProject',
        beforeSubmit: function () {
            //$('#BtnSave').attr('disabled', 'disabled');
            $('#BtnSave').button('loading');
            //$.blockUI({ message: '<div>送出資料中請稍候...</div>' });
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
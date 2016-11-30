$(document).ready(function () {
    var timeInMs = new Date();
    timInMs = timeInMs.getFullYear() + "-" + (timeInMs.getMonth() + 1) + "-" + timeInMs.getDate();
    $(".date").datetimepicker({
        format: 'yyyy-mm-dd',
        //startDate: timInMs,
        minView: 2,
        maxView: 4,
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        language: 'zh-TW'
    });
});
$(function () {
    $('#searchID').bsSuggest('init', {
        url: url + '/SysOption/SearchClient',
        //url: url + '/js/data.json',
        effectiveFields: ['name'],
        searchFields: ['name', 'sname'],
        effectiveFieldsAlias:{name: '顧客名稱'},
        ignorecase: true,
        showHeader: true,
        showBtn: false,
        delayUntilKeyup: true, //获取数据的方式为 firstByUrl 时，延迟到有输入/获取到焦点时才请求数据
        idField: 'ID',
        keyField: 'name'
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        //console.log('onSetSelectValue: ', keyword, data);
        $('#ClientID').val(keyword['id']);
    }).on('onUnsetSelectValue', function () {
        //console.log('onUnsetSelectValue');
        $('#ClientID').val(null);
    });

    $('#searchUser').bsSuggest('init', {
        url: url + '/SysOption/GetMobileUser',
        //url: url + '/js/data.json',
        effectiveFields: ['mobileSystemAccount', 'nodeName', 'name'],
        searchFields: ['mobileSystemAccount', 'nodeName', 'name'],
        effectiveFieldsAlias:{mobileSystemAccount: '員工編號', nodeName: '單位', name: '姓名'},
        ignorecase: true,
        showHeader: true,
        showBtn: false,
        delayUntilKeyup: true, //获取数据的方式为 firstByUrl 时，延迟到有输入/获取到焦点时才请求数据
        idField: 'mobileSystemAccount',
        keyField: 'name'
    }).on('onDataRequestSuccess', function (e, result) {
        //console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        //console.log('onSetSelectValue: ', keyword, data);
        $('#SalesID').val(keyword['id']);
    }).on('onUnsetSelectValue', function () {
        //console.log('onUnsetSelectValue');
        $('#SalesID').val('');
    });

    $("#EditProjectForm").ajaxForm({
        url: url + '/Project/UpdateProject',
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
                swal("新增資料失敗!", obj.msg, "error");
                $('#BtnSave').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnSave').button('reset');
        }
    });
});
function DoUpdate() {
    var client = $('#ClientID').val();
    var sales = $('#SalesID').val();
    if ( client == '') {
        swal({
            title: "請選擇正確的廠商名稱",
            text: "",
            type: "warning",
            showCancelButton: false,
            confirmButtonClass: "btn-warning",
            confirmButtonText: "確定",
            closeOnConfirm: true
        });
        return;
    } else if (sales == '') {
        swal({
            title: "請選擇正確的業務名稱",
            text: "",
            type: "warning",
            showCancelButton: false,
            confirmButtonClass: "btn-warning",
            confirmButtonText: "確定",
            closeOnConfirm: true
        });
        return;
    } else {
        $("#EditProjectForm").submit();
    }
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
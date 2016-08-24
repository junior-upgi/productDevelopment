$(function () {
    //表格托拽設定
    $("#tableSort").sortable({
        helper: fixWidthHelper
    }).disableSelection();
    //防止表格托拽後縮小修正程序
    function fixWidthHelper(e, ui) {
        ui.children().each(function () {
            $(this).width($(this).width());
        });
        return ui;
    };
    //設定 ajax token
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
    });
})

function SaveSort() {
    var sort = [];
    $("#tableSort tr").each(function (index, element) {
        //唯一編號
        var id = $(this).attr("id");
        //目前的排序
        var seq = index + 1;
        //var urstring = 'id=' + id + ",index=" + seq;
        //console.log(urstring);
        var data = {
            'pid': id,
            'index': seq,
        };
        sort.push(data);
    });
    var ProductID = $('#ProductID').val();
    if (sort.length > 0) {
        $.ajax({
            url: url + '/Process/SaveProcessSort/',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: JSON.stringify(sort),
            dataType: 'JSON',
            error: function (xhr) {
                swal("資料儲存失敗!", xhr.statusText, "error");
            },
            success: function (result) {
                if (result.success) {
                    swal({
                        title: "資料儲存成功!",
                        text: result.msg,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: false
                    },
                    function () {
                        document.location.href = url + '/Process/ProcessList/' + ProductID;
                    });
                } else {
                    swal("資料儲存錯誤!", result.msg, "error");
                }
            }
        })
    }
}

//呼叫新增程序視窗
function AddShow() {
    $('#AddProcessForm #ProcessNumber').val('');
    $('#AddProcessForm #ProcessName').val('');
    $("#AddProcessForm #PhaseID")[0].selectedIndex = 0;
    $('#AddProcessForm #TimeCost').val('');
    $("#AddProcessForm #NodeID")[0].selectedIndex = 0;
    $("#AddProcessForm #StaffID option").remove();
    $('#BtnAdd').button('reset');
    $('#AddModal').modal('show');
}

//呼叫編輯程序視窗
function EditShow(ID) {
    $.ajax({
        url: url + '/Process/GetProcessData/' + ID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            if (result.success) {
                var StaffList = result.StaffList;
                $('#EditProcessForm #ProcessID').val(result.ID);
                $('#EditProcessForm #ProcessNumber').val(result.ProcessNumber);
                $('#EditProcessForm #ProcessName').val(result.ProcessName);
                $("#EditProcessForm #PhaseID option[value=" + result.PhaseID + "]").attr('selected', true);
                $('#EditProcessForm #TimeCost').val(result.TimeCost);
                $("#EditProcessForm #NodeID option[value=" + result.NodeID + "]").attr('selected', true);
                if (StaffList.length > 0) {
                    $("#EditProcessForm #StaffID").empty();
                    $("#EditProcessForm #StaffID").append($("<option></option>").attr("value", "").text("請選擇"));
                    for (i = 0; i < StaffList.length; i++) {
                        $("#EditProcessForm #StaffID").append($("<option></option>").attr("value", StaffList[i].ID).text(StaffList[i].name));
                    }
                    $("#EditProcessForm #StaffID option[value=" + result.StaffID + "]").attr('selected', true);
                }
                $('#BtnEdit').button('reset');
                $('#EditModal').modal('show');
            } else {
                swal("取得資料錯誤!", result.msg, "error");
            }
        }
    })
}
function GetStaff(type) {
    var FormID = "#" + type + "ProcessForm";
    var NodeID = $(FormID + " #NodeID").find(":selected").val();
    $.ajax({
        url: url + '/Project/GetStaffByNodeID/' + NodeID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            $(FormID + " #StaffID option").remove();
            if (result.length > 0) {
                $(FormID + " #StaffID").append($("<option></option>").attr("value", "").text("請選擇"));
                for (i = 0; i < result.length; i++) {
                    $(FormID + " #StaffID").append($("<option></option>").attr("value", result[i].ID).text(result[i].name));
                }
            }
        }
    });
}
function DoInsert() {
    //var ProductID = $('#ProductID').val()
    $("#AddProcessForm").ajaxForm({
        url: url + '/Process/InsertProcess',
        beforeSubmit: function () {
            $('#BtnAdd').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                //swal("新增資料成功!", obj.msg, "success");
                //new process
                /*
                var msg = '<tr id="' + obj.ProcessID + '">'
                            + '<td><button type="button" class="btn btn-sm btn-default" onclick="EditShow(\'' + obj.ProcessID + '\')">編輯</button></td>'
                            + '<td>#</td>'
                            + '<td class="text-center">' + obj.PhaseName + '</td>'
                            + '<td>' + obj.ProcessNumber + '</td>'
                            + '<td>' + obj.ProcessName + '</td>'
                            + '<td>' + obj.NodeName + '</td>'
                            + '<td>' + obj.name + '</td>'
                            + '<td>' + obj.TimeCost + '</td>'
                            + '<td></td>'
                            + '<td></td>'
                            + '<td></td>'
                            + '<label for="">'

                            + '<span>' + obj.NodeName + '</span>'
                            + '<span>' + obj.name + '</span>'
                            + '<span>' + obj.TimeCost + '</span>'
                            + '</label>'
                        + '</tr>';
                $("#tableSort").append(msg);
                */
                $('#AddModal').modal('hide');
                swal({
                    title: "新增資料成功!",
                    text: obj.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                function () {
                    document.location.href = url + '/Process/ProcessList/' + $('#ProductID').val();
                });
            } else {
                swal("新增資料失敗!", obj.msg.errorInfo[2], "error");
                $('#BtnAdd').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnAdd').button('reset');
        }
    });
}
function DoUpdate(ProcessID) {
    var ProductID = $('#ProductID').val();
    var ProcessID = $('#ProcessID').val();
    $("#EditProcessForm").ajaxForm({
        url: url + '/Process/UpdateProcess/',
        beforeSubmit: function () {
            $('#BtnEdit').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                $('#EditModal').modal('hide');
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
                    document.location.href = url + '/Process/ProcessList/' + ProductID;
                });
            } else {
                swal("更新資料失敗!", obj.msg.errorInfo[2], "error");
                $('#BtnEdit').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnEdit').button('reset');
        }
    });
}
function Complete($ProcessID) {
    var ProductID = $('#ProductID').val();
    $.ajax({
        url: url + '/Process/ProcessComplete/' + $ProcessID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("更新資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            if (result.success) {
                swal({
                    title: "更新資料成功!",
                    text: result.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                function () {
                    document.location.href = url + '/Process/ProcessList/' + ProductID;
                });
            } else {
                swal("更新資料失敗!", obj.msg.errorInfo[2], "error");
            }
        }
    });
}
function Execute() {
    var ProductID = $('#ProductID').val();
    var Title = "執行產品開發？";
    var Message = "此動作無法再變更產品訊息!\n" 
                + "並且會開始發送推播訊息!\n"
                + "請問您確定要開始執行產品開發嗎？";
    swal({
        title: Title,
        text: Message,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "取消",
        confirmButtonClass: "btn-warning",
        confirmButtonText: "確定",
        closeOnConfirm: false
    },
    function(){
        ExcuteAjax(ProductID);
    });
}
function ExcuteAjax(ProductID) {
    $.ajax({
        url: url + '/Product/ProductExecute/' + ProductID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("操作失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            if (result.success) {
                swal({
                    title: result.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                function () {
                    document.location.href = url + '/Process/ProcessList/' + ProductID;
                });
            } else {
                swal("更新資料失敗!", obj.msg.errorInfo[2], "error");
            }
        }
    });
}
var ChSelect = [];
$(function () {
    $("[data-toggle='tooltip']").tooltip();
    //設定時間
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
    //表格托拽設定
    $("#tableSort_").sortable({
        helper: fixWidthHelper,
        update: function(event, ui) {
            
        }
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

    $("#AddProcessForm").ajaxForm({
        url: url + '/Process/InsertProcess',
        beforeSubmit: function () {
            $('#BtnAdd').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
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
                swal("新增資料失敗!", obj.msg, "error");
                $('#BtnAdd').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnAdd').button('reset');
        }
    });

    $("#EditProcessForm").ajaxForm({
        url: url + '/Process/UpdateProcess',
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
                    var ProductID = $('#ProductID').val();
                    document.location.href = url + '/Process/ProcessList/' + ProductID;
                });
            } else {
                swal("更新資料失敗!", obj.msg, "error");
                $('#BtnEdit').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnEdit').button('reset');
        }
    });

    $('#searchAddUser').bsSuggest('init', {
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
        $('#AddModal #StaffID').val(keyword['id']);
    }).on('onUnsetSelectValue', function () {
        //console.log('onUnsetSelectValue');
        $('#AddModal #StaffID').val('');
    });

    $('#searchEditUser').bsSuggest('init', {
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
        $('#EditModal #StaffID').val(keyword['id']);
    }).on('onUnsetSelectValue', function () {
        //console.log('onUnsetSelectValue');
        $('#EditModal #StaffID').val('');
    });

    
    $('[data-toggle="popover"]').mouseenter(function () {
        $('[data-toggle="popover"]').popover({
            html: true
        });
        $(this).popover('show');
    });
    $('[data-toggle="popover"]').mouseleave(function () {
        $(this).popover('hide');
    });
    
})
function showimage(source) {
    $("#PicModal").find("#img_show").html("<image src='"+source+"' class='carousel-inner img-responsive img-rounded' />");
    $("#PicModal").modal('show');
}
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
            url: url + '/Process/SaveProcessSort',
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
function AddShow(role) {
    $('#AddProcessForm #ProcessNumber').val('');
    $('#AddProcessForm #ProcessName').val('');
    $("#AddProcessForm #PhaseID")[0].selectedIndex = 0;
    $('#AddProcessForm #TimeCost').val('');
    $("#AddProcessForm #searchAddUser").val();
    $("#AddProcessForm #StaffID").val()
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
                $('#EditProcessForm #ProcessID').val(result.ID);
                $('#EditProcessForm #ProcessNumber').val(result.ProcessNumber);
                $('#EditProcessForm #ProcessName').val(result.ProcessName);
                $("#EditProcessForm #PhaseID option[value=" + result.PhaseID + "]").attr('selected', true);
                $('#EditProcessForm #ProcessStartDate').val(result.ProcessStartDate);
                $('#EditProcessForm #TimeCost').val(result.TimeCost);
                $("#EditProcessForm #searchEditUser").val(result.StaffName);
                $("#EditProcessForm #StaffID").val(result.StaffID)
                $('#EditProcessForm #note').val(result.note);
                if (result.processImg != null) {
                    var img = "<img src='" + result.processImg + "' class='kv-preview-data file-preview-image' style='width:auto;height:160px;'>";
                    $('#EditProcessForm #fileSet').val('true');
                } else {
                    var img = null;
                }
                $("#editImgDiv").empty();
                $("#editImgDiv").html("<input id='editImg' name='img' type='file' class='file-loading' data-show-upload='false' accept='image/*'>");
                $("#editImg").fileinput({
                    language: 'zh-TW',
                    previewFileType: "image",
                    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
                    previewClass: "bg-warning",
                    browseClass: "btn btn-success",
                    browseLabel: "選擇圖片",
                    browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
                    removeClass: "btn btn-danger",
                    removeLabel: "移除",
                    removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
                    fileActionSettings: {
                        showZoom: false,
                        showDrag: false,
                    },
                    initialPreview: 
                        [img]
                });
                $('#editImg').on('filebatchselected', function(event) {
                    resetModal('Edit');
                });
                $('#editImg').on('filecleared', function(event) {
                    resetModal('Edit');
                });
                $("#editPreview").attr("src", img);
                $('#BtnEdit').button('reset');
                $('#EditModal').modal('show');
            } else {
                swal("取得資料錯誤!", result.msg, "error");
            }
        }
    })
}
//呼叫設定前置流程
function SetPreparationShow(ProductID,ProcessID) {
    $.ajax({
        url: url + '/Process/GetPreparationList/' + ProductID + '/' + ProcessID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            if (result.success) {
                var List = result.PreparationList;
                var ReSelect = result.SelectList;
                ChSelect = [];
                $("#PreparationList").empty();
                $("#SetPreparationForm #ProductID").val(ProductID);
                $("#SetPreparationForm #ProcessID").val(ProcessID);
                for (i=0; i < List.length; i++) {
                    var chd = "";
                    var s = List[i].ID;
                    if(!(ReSelect.indexOf(List[i].ID) === -1)){
                        chd = "checked='checked'";
                        ChSelect.push(List[i].ID);
                    } 
                    $("#PreparationList").append(
                    "<tr>"+
                        "<td>" + "<input type='checkbox' class='ch' " + chd + " id='" + List[i].ID + "'>" + "</td>" +
                        "<td>" + List[i].sequentialIndex + "</td>" +
                        "<td>" + List[i].PhaseName + "</td>" +
                        "<td>" + List[i].referenceNumber + "</td>" +
                        "<td>" + List[i].referenceName + "</td>" +
                        "<td>" + List[i].nodeName + "_" + List[i].name + "</td>" +
                        "<td>" + List[i].timeCost + "</td>" +
                        "<td><span>" + $.datepicker.formatDate('yy-mm-dd', new Date(List[i].processStartDate)) + 
                        "~</span><br/>" + $.datepicker.formatDate('yy-mm-dd', new Date(List[i].processEndDate)) + "</td>" +
                    "</tr>");
                }
                $(".ch").click(function () {
                    if (ChSelect.indexOf(this.id) === -1)
                    {
                        ChSelect.push(this.id);
                    } else {
                        ChSelect.pop(this.id);
                    }
                });
                $('#PreparationModal').modal('show');
            } else {
                swal("取得資料錯誤!", result.msg, "error");
            }
        }
    })
}
function DoSetPreparation() {
    var ProductID = $("#SetPreparationForm #ProductID").val();
    var ProcessID = $("#SetPreparationForm #ProcessID").val();
    $.ajax({
        url: url + '/Process/SetPreparation/' + ProductID + '/' + ProcessID + '/' + JSON.stringify(ChSelect),
        type: 'GET',
        /*
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: JSON.stringify(sort),
        dataType: 'JSON',
        */
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
                    closeOnConfirm: true
                },
                function () {
                    $('#PreparationModal').modal('hide');
                    document.location.href = url + '/Process/ProcessList/' + ProductID;
                });
            } else {
                swal("資料儲存錯誤!", result.msg, "error");
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
    $("#AddProcessForm").submit();
}
function DoUpdate(ProcessID) {
    $("#EditProcessForm").submit();
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
                swal("更新資料失敗!", obj.msg, "error");
            }
        }
    });
}
function Execute(type) {
    var ProductID = $('#ProductID').val();
    if (type === 'run') {
        var Title = "執行產品開發？";
        var Message = "此動作無法再變更產品訊息!\n" 
                + "並且會開始發送推播訊息!\n"
                + "請問您確定要開始執行產品開發嗎？";
    } else {
        var Title = "停止產品開發？";
        var Message = "此動作會停止發送訊息!\n" 
                + "請問您確定要停止執行產品開發嗎？";
    }
    
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
                swal("更新資料失敗!", obj.msg, "error");
            }
        }
    });
}
function DoDelete(ProductID, ProcessID) {
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
        function(){
            Delete(ProductID, ProcessID);
        });
}

function Delete(ProductID, ProcessID) {
    $.ajax({
        url: url + '/Process/Delete/' + ProcessID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("刪除資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
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
                function () {
                    document.location.href = url + '/Process/ProcessList/' + ProductID;
                });
            } else {
                swal("刪除資料失敗!", obj.msg, "error");
                $('#BtnSave').button('reset');
            }
        }
    })
}

function resetModal(type) {
    var modal = '#' + type + 'Modal';
    var modalHeight = $(modal + ' .modal-dialog').height() + 30;
    if ($(modal).height() > modalHeight) {
        $(modal + ' .modal-backdrop').height($(modal).height());
    } else {
        $(modal + ' .modal-backdrop').height(modalHeight);
    }
}
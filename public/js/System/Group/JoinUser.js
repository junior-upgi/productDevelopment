$(function () {
    $("#joinID").bsSuggest('init', {
            url: url + "/SysOption/SearchMember",
            //url: url + "/js/data.json",
            effectiveFields: ["mobileSystemAccount", "nodeName", "name"],
            searchFields: ["mobileSystemAccount", "nodeName", "name"],
            effectiveFieldsAlias:{mobileSystemAccount: "員工編號", nodeName: "單位", name: "姓名"},
            ignorecase: true,
            showHeader: true,
            showBtn: false,
            delayUntilKeyup: true, //获取数据的方式为 firstByUrl 时，延迟到有输入/获取到焦点时才请求数据
            idField: "ID",
            keyField: "mobileSystemAccount"
        }).on('onDataRequestSuccess', function (e, result) {
            console.log('onDataRequestSuccess: ', result);
        }).on('onSetSelectValue', function (e, keyword, data) {
            console.log('onSetSelectValue: ', keyword, data);
        }).on('onUnsetSelectValue', function () {
            console.log('onUnsetSelectValue');
    });


});

function getList(id) {
    $.ajax({
        url: url + '/SysOption/GetMember',
        type: 'GET',
        data: {'groupID': id},
        error: function(xhr) {
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function(result) {
            if (result.success) {
                var data = result.data;
                for (i=0; i<data.length; i++) {
                    addTr(data[i]);
                }
            } else {
                swal("取得資料失敗!", result.msg, "error");
            }
        }
    })
}

function join() {
    var joinID = $("#joinID").val();
    var joinGroup = $('#joinGroup').val();
    var data = {'ID': joinID, 'groupID': joinGroup};
    if (joinID != "") {
        $.ajax({
            url: url + '/SysOption/UserJoin',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            error: function(xhr) {
                swal("加入失敗!", xhr.statusText, "error");
            },
            success: function(result) {
                if (result.success) {
                    swal({
                        title: "加入成功!",
                        text: result.msg,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function() {
                        //$('#JoinModal').modal('hide');
                        var data = result.data;
                        addTr(data);
                    });
                } else {
                    swal("加入失敗!", result.msg, "error");
                }
            }
        })
    }
}

function addTr(data) {
    $("#memberTable").append(
        '<tr id=tr_' + data.erpID + '><td>' + data.erpID + 
        '</td><td>' + data.nodeName + 
        '</td><td>' + data.name + 
        '</td><td><button type="button" class="btn btn-sm btn-danger text-center" onclick="doRemove(\'' + data.erpID + '\')">刪除</button></td></tr>'
    );
}

function doRemove(erpID) {
    var groupID = $('#joinGroup').val();
    var data = {'erpID': erpID, 'groupID': groupID};
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
        $.ajax({
            url: url + '/SysOption/RemoveUser',
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
                        closeOnConfirm: true
                    },
                    function() {
                        var data = 'tr_' + erpID;
                        $('#' + data).remove();
                    });
                } else {
                    swal("刪除資料失敗!", result.msg, "error");
                }
            }
        })
    });
}
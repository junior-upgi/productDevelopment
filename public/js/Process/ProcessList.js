$(function(){  
    $("#Sort .items").sortable({
        connectWith: "ul",
        opacity: 0.6,
        cursor: 'move',
        axis: 'y',
        start: function (event, ui) {
            var start_pos = ui.item.index();
            ui.item.data('start_pos', start_pos);
        },
        update: function (event, ui) {
            //var productOrder = $(this).sortable('toArray').toString();
            var oldIndex = ui.item.data('start_pos');
            var newIndex = ui.item.context.rowIndex;
            alert('oldIndex:' + oldIndex + '\n newIndex:' + newIndex);
            /*
            $.ajax({
                type: 'post',
                url: '/PeopleGroups/DropOrderItem',
                data: {
                    oldIndex: oldIndex + 1,
                    newIndex: newIndex + 1,
                    page: currPage,
                    pageSize: pageSize
                }
            });
            */
        }  
    });
    $('#AddModal').on('show.bs.modal', function (event) { 
        $('#AddProcessForm #ProcessNumber').val('');
        $('#AddProcessForm #ProcessName').val('');
        $("#AddProcessForm #PhaseID")[0].selectedIndex = 0;
        $('#AddProcessForm #TimeCost').val('');
        $("#AddProcessForm #NodeID")[0].selectedIndex = 0;
        $("#AddProcessForm #StaffID option").remove();
        $('#BtnAdd').button('reset');
    })
    $('#EditModal').on('show.bs.modal', function (event) { 
        $('#EditProcessForm #ProcessNumber').val('');
        $('#EditProcessForm #ProcessNumber').val('');
        $('#EditProcessForm #ProcessNumber').val('');
        $('#EditProcessForm #ProcessNumber').val('');
        $('#EditProcessForm #ProcessNumber').val('');
    })
});
function a() {
    $('.list').each(function(index, element) {

        //唯一編號
        var id = $(this).attr("id");

        //目前的排序
        var seq = index + 1;
        var urstring = 'id=' + id + ",index=" + seq;
        console.log(urstring);
    });
}
function AddShow(){
    $('#AddModal').modal('show');
}
function EditShow(){
    $('#EditModal').modal('show');
}
function GetStaff(type) {
    var FormID = "#" + type + "ProcessForm";
    var NodeID = $(FormID + " #NodeID").find(":selected").val();
    $.ajax({
        url: '/Project/GetStaffByNodeID/' + NodeID,
        type: 'GET',
        dataType: 'JSON',
        error: function(xhr) {
            alert('取得業務員資料失敗!\n ERROR:' + xhr.statusText);
        },
        success: function(result) {
            $(FormID + " #StaffID option").remove();
            if (result.length > 0) {
                $(FormID + " #StaffID").append($("<option></option>").attr("value", "").text("請選擇"));
                for (i = 0; i < result.length ; i++)
                    $(FormID + " #StaffID").append($("<option></option>").attr("value", result[i].id).text(result[i].name));
            }
        }
    })
}
function DoInsert() {
    //var ProductID = $('#ProductID').val()
    $("#AddProcessForm").ajaxForm({
        url: '/Process/InsertProcess',
        beforeSubmit: function () {
            $('#BtnAdd').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                alert(obj.msg);
                //new process
                $('#AddModal').modal('hide');
            }
            else {
                alert(obj.msg.errorInfo[2])
                $('#BtnAdd').button('reset');
            }
        },
        error: function (obj) {
            alert('發生異常錯誤!!');
            $('#BtnAdd').button('reset');
        }
    });
}
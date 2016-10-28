function joinInit(id) {
    var data = {
        'id': id
    };
    $.ajax({
        url: url + '/SysOption/getMember',
        type: 'GET',
        data: data,
        error: function(xhr) {
            swal("加入失敗!", xhr.statusText, "error");
        },
        success: function(result) {
            $('#tbody').html(
                '<tr>' +
                '<td>' +

                '<td>' +
                '<td>' +

                '<td>' +
                '<td>' +

                '<td>' +
                '<td>' +
                '<button type="button" onclick="delTr()">移除</button>' +
                '<td>' +
                '<tr>'
            );
        }
    });
}

function join() {

}

function addTr() {

}

function delTr() {

}
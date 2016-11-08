<div class="modal fade" id="NotifyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">發送推撥</h4>
            </div>
            <input type="hidden" id="joinGroup" name="joinGroup" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="joinID" class="control-label">發送給</label>
                        <div class="input-group">
                            <input type="hidden" id="notify_recipientID" value="">
                            <input type="text" class="form-control" id="searchID" style="width: 200px;">
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" style="height: 250px;">
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title" class="control-label">標題</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="form-group">
                        <label for="content" class="control-label">內容</label>
                        <textarea type="text" class="form-control" rows="3" id="content" name="content" style="resize: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="button" class="btn btn-primary" id="notifySend">發送</button>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $(function () {
        $('#NotifyModal').on('show.bs.modal', function () {
            $('#notify_recipientID').val('');
            $('#searchID').val('');
            $('#joinID').val('');
            $('#title').val('');
            $('#content').val('');
        });
        $('#searchID').bsSuggest('init', {
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
                console.log('onDataRequestSuccess: ', result);
            }).on('onSetSelectValue', function (e, keyword, data) {
                //console.log('onSetSelectValue: ', keyword, data);
                $('#notify_recipientID').val(keyword['id']);
            }).on('onUnsetSelectValue', function () {
                //console.log('onUnsetSelectValue');
                $('#notify_recipientID').val(null);
        });
        $('#notifySend').on('click', function () {
            var recipientID = $('#notify_recipientID').val();
            var title = $('#title').val();
            var content = $('#content').val();
            var send = {
                "title": title,
                "content": content,
                "messageID": 2,
                "systemID": 0,
                "uid": '{{ $user->ID }}',
                "recipientID": recipientID,
                "url": "",
                "audioFile": ""
            };
            var message = new Array();
            message.push(send);
            var data = JSON.stringify(message);
            if (recipientID != null) {
                $.ajax({
                    url: url + '/Service/SendMessage',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    error: function(xhr) {
                        swal("推播發送失敗!", xhr.statusText, "error");
                    },
                    success: function(result) {
                        if (result.success) {
                            swal({
                                title: "推播發送成功!",
                                text: result.msg,
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                            },
                            function() {
                                $('#NotifyModal').hide();
                            });
                        } else {
                            swal("推播發送失敗!", result.msg, "error");
                        }
                    }
                })
            } else {
                alert('請選擇人員');
            }
        });
    });
</script>
            
@extends('layouts.mobileLayout')
@section('content')
	<script>
        function send(type) {
            var message = getMessage();
            if (type == 'true') {
                var csrf = getCSRF();
                var sendurl = 'http://upgi.ddns.net/productDevelopment/Service/SendMessage';
            } else {
                var csrf = $('meta[name="csrf-token"]').attr('content');
                var sendurl = url +  '/Service/SendMessage';
            }
            $.ajax({
                url: sendurl,
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf }, //Laravel驗證表單用
                data: message,
                dataType: 'JSON',
                error: function (xhr) {
                    alert(xhr.statusText); //送出錯誤訊息
                },
                success: function (result) {
                    /*
                    {
                        'success': true/false
                        'msg': 'return message'
                    }
                    */
                    if (result.success) {
                        alert('true,' + result.msg);
                    } else {
                        alert('false,' + result.msg);
                    }
                }
            });
        }
        function getCSRF() {

        }
        function getMessage() {
            /*  傳送格式範例
            [
                {
                    "title": "title",
                    "content": "content",
                    "messageID": 1,                     //messageID 0=系統公告, 1=進度警訊, 2=用戶訊息, 3=一般通知, 999=測試訊息
                    "systemID": 1,                      //systemID 0=開發案系統, 1=逾期款系統
                    "uid": "",                          //發送者ID(職員編號) 可選填
                    "recipientID": "16080003",          //接收者id(職員編號)
                    "url": "http://www.google.com.tw",  //點選後導向的url
                    "audioFile": "warning.mp3"          //siren.mp3, beep.mp3, alarm.mp3, warning.mp3
                }
            ]
            */
            var send1 = {
                "title": "開始執行開發",
                "content": "990001產品開始執行開發",
                "messageID": 1,
                "systemID": 0,
                "uid": "",
                "recipientID": "16080003",
                "url": "",
                "audioFile": "warning.mp3"
            };
            var send2 = {
                "title": "[OP]噴漆加工 已延誤",
                "content": "[OP]噴漆加工 已延誤 3天",
                "messageID": 1,
                "systemID": 0,
                "uid": "",
                "recipientID": "16080003",
                "url": "upgi.ddns.net/productDevelopment/Mobile/UserSettingCost/F3E39738-3994-0469-F186-943368E6FF54/manager",
                "audioFile": "warning.mp3"
            };
            var message = new Array();
            message.push(send1);
            message.push(send2);
            return JSON.stringify(message);
        }
    </script>
    <form id="SetCostForm" class="form-horizontal" role="form" method="POST" style="margin-top:15px;">
        <button type="button" class="btn btn-primary btn-sm" id="BtnSend" name="BtnSend" onclick="send('false')">測試機測試</button>
        <button type="button" class="btn btn-primary btn-sm" id="BtnSend" name="BtnSend" onclick="send('true')">正試機測試</button>
    </form>
@endsection
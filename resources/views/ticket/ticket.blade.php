@extends('app')

@section('title', '抽獎券')

@section('css')
    <style>
        h1.ui.header {
            font-size: 72px;
        }

        .ui.segment.normal {
            background: none;
            box-shadow: none;
            border: none;
            border-radius: 0;
        }

        #ticket_info {
            padding-top: 30px;
            padding-bottom: 50px;
        }

        #ticket_info p {
            line-height: 50px;
        }

        p#ticket_number {
            font-size: 96px;
        }

        p#ticket_name {
            font-size: 120px;
        }

        p#ticket_class {
            font-size: 72px;
        }
    </style>
@endsection

@section('content')
    <h1 class="ui teal header center aligned">
        抽獎券
    </h1>
    <div class="ui segment center aligned" id="ticket_info">
        <p id="ticket_number">#???</p>
        <p id="ticket_name"></p>
        <p id="ticket_class"></p>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            var $ticketInfo = $('#ticket_info');
            var $searchIdInput = $('#ticket_search_id');
            $searchIdInput.focus();
            $('#ticket_search_form').submit(function () {
                var searchId = $searchIdInput.val();
                $searchIdInput.val('');
                $searchIdInput.focus();
                if(!searchId || searchId.length === 0){
                    return false;
                }
                //Loading
                $ticketInfo.addClass('loading');
                $('#ticket_name').text('');
                $('#ticket_class').text('');
                //透過Ajax查詢
                $.ajax({
                    url: '{{ route('ticket.info') }}',
                    type: 'GET',
                    data: {
                        id: searchId
                    },
                    error: function (xhr) {
                        $('#ticket_name').html('<span style="color: red">發生錯誤</span>');
                    },
                    success: function (response) {
                        $('#ticket_number').text('#' + response.id);
                        if (response.success) {
                            $('#ticket_name').text(response.name);
                            $('#ticket_class').text(response.class);
                        } else {
                            $('#ticket_name').html('<span style="color: red">查無此抽獎編號</span>');
                            $('#ticket_class').text('');
                        }
                    },
                    complete: function () {
                        $ticketInfo.removeClass('loading');
                    }
                });
                return false;
            });
        });
    </script>
@endsection

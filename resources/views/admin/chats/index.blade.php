@extends('admin.layouts.admin')

@section('title', 'مركز المحادثات والرسائل المباشرة')

@section('contentHeader')
    <i class="fas fa-comments text-primary"></i>
    المحادثات والرسائل المباشرة
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.chats.index') }}">المحادثات</a>
@endsection
@section('contentHeaderActive', 'مركز الرسائل')

@section('css')
<style>
    .chat-wrapper {
        display: flex;
        height: calc(100vh - 220px);
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .chat-sidebar {
        width: 320px;
        border-left: 1px solid #e9ecef;
        display: flex;
        flex-direction: column;
        background: #f8f9fa;
    }
    .chat-sidebar-search {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        background: #fff;
    }
    .chat-sidebar-users {
        flex: 1;
        overflow-y: auto;
    }
    .chat-user-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-bottom: 1px solid #f1f3f5;
        cursor: pointer;
        transition: all 0.2s;
    }
    .chat-user-item:hover {
        background: #e9ecef;
    }
    .chat-user-item.active {
        background: #e7f1ff;
        border-right: 4px solid #007bff;
    }
    .chat-user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-left: 12px;
        border: 2px solid #dee2e6;
    }
    .chat-user-info {
        flex: 1;
        min-width: 0;
    }
    .chat-user-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: #212529;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .chat-user-last-msg {
        font-size: 0.8rem;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }
    .chat-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        background: #fff;
    }
    .chat-messages-container {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f1f3f5;
    }
    .chat-input-area {
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
        background: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 15px;
        position: relative;
        font-size: 0.95rem;
        line-height: 1.4;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .message-bubble.incoming {
        background: #fff;
        color: #212529;
        align-self: flex-start;
        border-top-left-radius: 2px;
        margin-right: auto;
    }
    .message-bubble.outgoing {
        background: #007bff;
        color: #fff;
        align-self: flex-end;
        border-top-right-radius: 2px;
        margin-left: auto;
    }
    .message-time {
        font-size: 0.72rem;
        margin-top: 5px;
        display: block;
        text-align: left;
    }
    .message-bubble.incoming .message-time {
        color: #adb5bd;
    }
    .message-bubble.outgoing .message-time {
        color: #d1ecf1;
    }
    .chat-empty-state {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        background: #f8f9fa;
    }
    .chat-empty-state i {
        font-size: 4rem;
        margin-bottom: 15px;
        color: #dee2e6;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="chat-wrapper">
        <!-- Sidebar Contacts List -->
        <div class="chat-sidebar">
            <div class="chat-sidebar-search">
                <div class="input-group input-group-sm">
                    <input type="text" id="user-search-input" class="form-control" placeholder="ابحث عن زميل عمل...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
            <div class="chat-sidebar-users" id="contacts-list">
                @foreach($users as $user)
                    <div class="chat-user-item contact-item" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" class="chat-user-avatar" alt="avatar">
                        @else
                            <div class="d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded-circle chat-user-avatar font-weight-bold" style="width: 45px; height: 45px; margin-left: 12px; font-size: 1.2rem;">
                                {{ mb_substr($user->name, 0, 1, 'utf-8') }}
                            </div>
                        @endif
                        <div class="chat-user-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="chat-user-name">{{ $user->name }}</span>
                                <span class="badge badge-danger unread-badge {{ $user->unread_messages_count == 0 ? 'd-none' : '' }}">
                                    {{ $user->unread_messages_count }}
                                </span>
                            </div>
                            <div class="chat-user-last-msg">
                                {{ $user->last_message ? $user->last_message->message : 'لا توجد رسائل سابقة' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main" id="chat-main-section">
            <!-- Empty State -->
            <div class="chat-empty-state" id="chat-empty-state">
                <i class="far fa-comments"></i>
                <h5>ابدأ محادثة جديدة الآن</h5>
                <p class="text-sm">اختر أحد زملائك من القائمة الجانبية لبدء المراسلة الفورية.</p>
            </div>

            <!-- Header Profile info -->
            <div class="chat-header d-none" id="chat-active-header">
                <div class="d-flex align-items-center">
                    <div id="active-avatar-container"></div>
                    <div class="mr-3">
                        <h6 class="mb-0 font-weight-bold text-dark" id="active-user-name">---</h6>
                        <span class="text-success small"><i class="fas fa-circle text-xs mr-1"></i> متصل في النظام</span>
                    </div>
                </div>
            </div>

            <!-- Messages Stream -->
            <div class="chat-messages-container d-none" id="messages-container">
                <div class="d-flex flex-column" id="messages-stream-wrapper">
                    <!-- Dynamic messages load here -->
                </div>
            </div>

            <!-- Input area -->
            <div class="chat-input-area d-none" id="chat-input-wrapper">
                <input type="text" id="chat-message-input" class="form-control" placeholder="اكتب رسالتك هنا...">
                <button type="button" id="chat-send-btn" class="btn btn-primary px-4 shadow-sm">
                    <i class="fas fa-paper-plane"></i> إرسال
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var activeReceiverId = null;
    var pollInterval = null;

    $(document).ready(function() {
        // Search Contacts filter
        $('#user-search-input').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#contacts-list .contact-item').filter(function() {
                $(this).toggle($(this).data('name').toLowerCase().indexOf(value) > -1)
            });
        });

        // Contact Item Clicked
        $(document).on('click', '.contact-item', function() {
            var item = $(this);
            $('.contact-item').removeClass('active');
            item.addClass('active');

            activeReceiverId = item.data('id');
            var userName = item.data('name');
            var avatarHtml = item.find('.chat-user-avatar').prop('outerHTML');

            // Hide empty state, show chat elements
            $('#chat-empty-state').addClass('d-none');
            $('#chat-active-header').removeClass('d-none');
            $('#messages-container').removeClass('d-none');
            $('#chat-input-wrapper').removeClass('d-none');

            $('#active-user-name').text(userName);
            $('#active-avatar-container').html(avatarHtml);

            // Clear unread badge
            item.find('.unread-badge').addClass('d-none').text('0');

            loadMessages();

            // Setup polling interval every 4 seconds to get new messages automatically
            if (pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(loadMessages, 4000);
        });

        // Send Message action
        $('#chat-send-btn').on('click', function() {
            sendMessage();
        });

        $('#chat-message-input').on('keypress', function(e) {
            if (e.which == 13) {
                sendMessage();
            }
        });
    });

    function loadMessages() {
        if (!activeReceiverId) return;

        $.ajax({
            url: '{{ url("admin/chats/history") }}/' + activeReceiverId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var stream = $('#messages-stream-wrapper');
                var isScrolledToBottom = isScrollNearBottom();
                
                stream.html(response.html);

                if (isScrolledToBottom) {
                    scrollToBottom();
                }
            }
        });
    }

    function sendMessage() {
        var input = $('#chat-message-input');
        var message = input.val().trim();
        if (!message || !activeReceiverId) return;

        input.val('');

        $.ajax({
            url: '{{ route("admin.chats.send") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                receiver_id: activeReceiverId,
                message: message
            },
            dataType: 'json',
            success: function(response) {
                loadMessages();
                scrollToBottom();

                // Update contact item's last message label
                $('.contact-item[data-id="' + activeReceiverId + '"] .chat-user-last-msg').text(message);
            }
        });
    }

    function isScrollNearBottom() {
        var container = document.getElementById('messages-container');
        if (!container) return false;
        // Check if user is scrolled within 100px from the bottom
        return (container.scrollHeight - container.clientHeight - container.scrollTop) < 100;
    }

    function scrollToBottom() {
        var container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
</script>
@endsection

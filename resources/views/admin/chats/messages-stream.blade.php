@if($messages->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="far fa-comments fa-2x mb-2 d-block"></i>
        <span>لا توجد رسائل سابقة. أرسل رسالة لبدء المحادثة!</span>
    </div>
@else
    @foreach($messages as $msg)
        @php
            $isOutgoing = $msg->sender_id == auth()->id();
        @endphp
        <div class="message-bubble {{ $isOutgoing ? 'outgoing' : 'incoming' }}">
            <span class="message-text">{!! e($msg->message) !!}</span>
            <span class="message-time" title="{{ $msg->created_at->format('Y-m-d H:i') }}">
                {{ $msg->created_at->locale('ar')->diffForHumans() }}
            </span>
        </div>
    @endforeach
@endif

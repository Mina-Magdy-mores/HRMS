@if($latestUnread->isEmpty())
    <div class="dropdown-header text-center py-4">
        <i class="far fa-comments text-muted d-block fa-2x mb-2"></i>
        <span>لا توجد رسائل جديدة غير مقروءة</span>
    </div>
@else
    <span class="dropdown-header text-right font-weight-bold text-primary">لديك {{ $unreadCount }} رسائل جديدة</span>
    <div class="dropdown-divider"></div>
    @foreach($latestUnread as $msg)
        <a href="{{ route('admin.chats.index') }}" class="dropdown-item">
            <div class="media align-items-center">
                @if($msg->sender->image)
                    <img src="{{ asset('storage/' . $msg->sender->image) }}" alt="User Avatar" class="img-size-50 ml-2 img-circle" style="width: 40px; height: 40px; object-fit: cover;">
                @else
                    <div class="d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded-circle ml-2 font-weight-bold" style="width: 40px; height: 40px; font-size: 1.1rem; min-width: 40px;">
                        {{ mb_substr($msg->sender->name, 0, 1, 'utf-8') }}
                    </div>
                @endif
                <div class="media-body text-right min-width-0">
                    <h3 class="dropdown-item-title font-weight-bold text-dark text-truncate" style="font-size: 0.88rem; max-width: 170px;">
                        {{ $msg->sender->name }}
                    </h3>
                    <p class="text-sm text-secondary text-truncate" style="max-width: 170px; margin-bottom: 2px;">
                        {{ $msg->message }}
                    </p>
                    <p class="text-xs text-muted mb-0"><i class="far fa-clock ml-1 text-xs"></i> {{ $msg->created_at->locale('ar')->diffForHumans() }}</p>
                </div>
            </div>
        </a>
        <div class="dropdown-divider"></div>
    @endforeach
@endif
<a href="{{ route('admin.chats.index') }}" class="dropdown-item dropdown-footer text-center bg-light font-weight-bold text-primary">عرض جميع الرسائل</a>

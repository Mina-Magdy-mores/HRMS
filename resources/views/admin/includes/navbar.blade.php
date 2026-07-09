  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">الرئيسية</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.profile.edit') }}" class="nav-link">الملف الشخصي</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.logout') }}" class="nav-link">تسجيل الخروج</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge d-none" id="navbar-unread-messages-badge">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right text-right" id="navbar-unread-messages-dropdown">
          <!-- Loaded dynamically via AJAX -->
          <div class="dropdown-header text-center py-3">
              <i class="fas fa-spinner fa-spin mr-1"></i> جاري تحميل الرسائل...
          </div>
        </div>
      </li>
      <!-- Notifications Dropdown Menu (Employee Requests) -->
      @if(auth()->user()->is_employee == 0)
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-file-signature"></i>
          @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
            <span class="badge badge-danger navbar-badge">{{ $pendingRequestsCount }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">
            @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
              لديك {{ $pendingRequestsCount }} طلب معلق جديد
            @else
              لا توجد طلبات معلقة جديدة
            @endif
          </span>
          @if(isset($pendingRequests) && $pendingRequests->isNotEmpty())
            @foreach($pendingRequests as $pReq)
              <div class="dropdown-divider"></div>
              <a href="{{ route('admin.employee-requests.show', $pReq->id) }}" class="dropdown-item">
                <i class="fas fa-file-alt mr-2 text-info"></i>
                <span class="text-truncate d-inline-block" style="max-width: 160px; vertical-align: middle;">{{ $pReq->title }}</span>
                <span class="float-right text-muted text-xs">{{ $pReq->created_at ? $pReq->created_at->diffForHumans(null, true) : '' }}</span>
              </a>
            @endforeach
          @endif
          <div class="dropdown-divider"></div>
          <a href="{{ route('admin.employee-requests.index') }}" class="dropdown-item dropdown-footer">عرض كافة الطلبات</a>
        </div>
      </li>
      @endif
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
      </li>
    </ul>
  </nav>

<script>
    function loadNavbarUnreadCount() {
        if (typeof jQuery === 'undefined') return;
        jQuery.ajax({
            url: '{{ route("admin.chats.unreadCount") }}',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var badge = jQuery('#navbar-unread-messages-badge');
                if (response.unread_count > 0) {
                    badge.removeClass('d-none').text(response.unread_count);
                } else {
                    badge.addClass('d-none').text('0');
                }
                jQuery('#navbar-unread-messages-dropdown').html(response.html);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Run once on load
        setTimeout(loadNavbarUnreadCount, 500);
        // Poll every 12 seconds
        setInterval(loadNavbarUnreadCount, 12000);
    });
</script>

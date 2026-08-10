@forelse(auth()->user()->userNotifications() as $notification)
    <!-- item-->
    <a href="javascript:void(0);" data-toggle="modal" data-target="#notificationModal" onclick="showModal('{{ $notification->id }}',{{ json_encode($notification->data['text'] ?? '') }})" data-id="{{ $notification->id }}">
        <p class="notify-details p-2 border-bottom" style="color:#ccc">
            <b>{{ Str::words((string) ($notification->data['text'] ?? ''), 15) }}</b>
            <span id="text-{{ $notification->id }}" class="badge-success p-1 rounded text-light">
                new
            </span>
        </p>
    </a>
@empty
    <p class="text-center text-muted p-3 mb-0">No unread notifications.</p>
@endforelse

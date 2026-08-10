@extends('layouts.layout')

@section('title', 'Notifications')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mt-0 header-title">Notifications</h4>
                        <div class="d-flex align-items-center">
                            <div class="btn-group mr-2" role="group" aria-label="Notification filter">
                                <a href="{{ route('notification.index') }}"
                                   class="btn btn-sm {{ request()->boolean('unread') ? 'btn-outline-primary' : 'btn-primary' }}">
                                    All
                                </a>
                                <a href="{{ route('notification.index', ['unread' => 1]) }}"
                                   class="btn btn-sm {{ request()->boolean('unread') ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Unread
                                </a>
                            </div>
                            <form method="POST" action="{{ route('notification.read-all') }}" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-primary waves-effect waves-light btn-sm">
                                    <i class="mdi mdi-check-all mr-1"></i> Mark all as read
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Message</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Received</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($notifications as $notification)
                                @php $body = $notification->data['text'] ?? ''; @endphp
                                <tr id="row-{{ $notification->id }}"
                                    class="{{ $notification->read_at ? '' : 'table-active' }}"
                                    style="cursor:pointer;"
                                    data-toggle="modal" data-target="#notificationModal"
                                    onclick="showModal('{{ $notification->id }}', {{ json_encode($body) }}, this)">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($body, 80) }}</td>
                                    <td>
                                        @php
                                            $module = $notification->data['module'] ?? 'general';
                                            $moduleBadges = [
                                                'wallet_funding' => ['Wallet', 'badge-success'],
                                                'transaction'    => ['Transaction', 'badge-info'],
                                                'account'        => ['Account', 'badge-primary'],
                                                'team'           => ['Team', 'badge-warning'],
                                                'user'           => ['User', 'badge-secondary'],
                                                'company'        => ['Company', 'badge-secondary'],
                                                'general'        => ['General', 'badge-secondary'],
                                            ];
                                            [$moduleLabel, $moduleClass] = $moduleBadges[$module] ?? $moduleBadges['general'];
                                        @endphp
                                        <span class="badge {{ $moduleClass }}">{{ $moduleLabel }}</span>
                                    </td>
                                    <td>
                                        <span id="status-{{ $notification->id }}"
                                              class="badge {{ $notification->read_at ? 'badge-secondary' : 'badge-success' }}">
                                            {{ $notification->read_at ? 'Read' : 'New' }}
                                        </span>
                                    </td>
                                    <td>{{ $notification->created_at ? $notification->created_at->diffForHumans() : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No notifications yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div> <!-- end row -->
@stop

@section('after-scripts')
    <script>
        // Extend the nav's showModal() so clicking a row on this page also flips
        // the row's "New" badge to "Read" after the notification is marked read.
        (function () {
            var originalShowModal = window.showModal;
            window.showModal = function (id, body, rowEl) {
                if (typeof originalShowModal === 'function') {
                    originalShowModal(id, body);
                }
                if (rowEl) {
                    $(rowEl).removeClass('table-active');
                    $('#status-' + id).removeClass('badge-success').addClass('badge-secondary').text('Read');
                }
            };
        })();
    </script>
@stop

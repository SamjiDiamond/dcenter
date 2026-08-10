@extends('layouts.layout')

@section('title', 'Team Members')

@section('content')
    @php
        // Preserve the active search term across role-filter pills.
        $activeQuery = array_filter(['search' => request('search')]);
        $rolePill = request('role');
    @endphp

    <div class="row">
        <div class="col-12">
            @if($isAdmin)
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">
                            <i class="mdi mdi-account-plus-outline text-primary mr-1"></i> Invite a team member
                        </h4>
                        <p class="text-muted font-14">Invite someone by email and choose their role.</p>
                        <form method="POST" action="{{ route('team.invite') }}">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-4 mb-2">
                                    <label>Email address</label>
                                    <input type="email" name="email" class="form-control"
                                           placeholder="teammate@example.com" required>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label>First name</label>
                                    <input type="text" name="first_name" class="form-control" placeholder="Optional">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label>Last name</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Optional">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label>Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="admin">Admin</option>
                                        <option value="finance">Finance</option>
                                        <option value="viewer" selected>Viewer</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-block waves-effect waves-light">
                                        <i class="mdi mdi-account-plus-outline mr-1"></i> Invite
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <h4 class="mt-0 header-title mb-0">
                            <i class="mdi mdi-account-multiple-outline text-primary mr-1"></i> Team members
                        </h4>
                        <span class="text-muted">
                            {{ $totalMembers }} {{ Str::plural('person', $totalMembers) }} on this account
                        </span>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <form method="GET" action="{{ route('team.index') }}" class="form-inline mb-2 mr-3">
                            <input type="search" name="search" class="form-control form-control-sm mr-2"
                                   value="{{ request('search') }}" placeholder="Search name or email…"
                                   aria-label="Search team members" style="min-width: 220px;">
                            @if(request('role'))
                                <input type="hidden" name="role" value="{{ request('role') }}">
                            @endif
                            <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light">
                                <i class="mdi mdi-magnify mr-1"></i> Search
                            </button>
                            @if(request('search') || request('role'))
                                <a href="{{ route('team.index') }}"
                                   class="btn btn-sm btn-outline-secondary ml-2">Clear</a>
                            @endif
                        </form>

                        <div class="btn-group mb-2" role="group" aria-label="Filter by role">
                            <a href="{{ route('team.index', $activeQuery) }}"
                               class="btn btn-sm {{ ! $rolePill ? 'btn-primary' : 'btn-outline-primary' }}">
                                All <span class="badge badge-light ml-1">{{ $totalMembers }}</span>
                            </a>
                            @foreach(['admin', 'finance', 'viewer'] as $r)
                                <a href="{{ route('team.index', array_merge($activeQuery, ['role' => $r])) }}"
                                   class="btn btn-sm {{ $rolePill === $r ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{ ucfirst($r) }}
                                    @if(isset($roleCounts[$r]))
                                        <span class="badge badge-light ml-1">{{ $roleCounts[$r] }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="team-members-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                @if($isAdmin)
                                    <th class="text-right">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($members as $member)
                                <tr>
                                    <td class="text-muted">{{ $members->firstItem() + $loop->index }}</td>
                                    <td>
                                        <img src="{{ $member->profile_photo_url }}" class="rounded-circle mr-2"
                                             style="width:32px; height:32px; object-fit:cover;" alt="">
                                        <span class="member-name">{{ $member->first_name }} {{ $member->last_name }}</span>
                                        @if($member->is_self)
                                            <span class="badge badge-info ml-1">you</span>
                                        @endif
                                    </td>
                                    <td class="member-email">{{ $member->email }}</td>
                                    <td>
                                        @php
                                            $roleName = $member->role_name ?? null;
                                            $roleBadge = $roleName === 'admin' ? ['badge-danger', 'Admin']
                                                : ($roleName === 'finance' ? ['badge-warning', 'Finance']
                                                : ($roleName === 'viewer' ? ['badge-secondary', 'Viewer'] : null));
                                        @endphp
                                        @if($roleBadge)
                                            <span class="badge {{ $roleBadge[0] }}">{{ $roleBadge[1] }}</span>
                                        @else
                                            <span class="badge badge-light text-muted border"
                                                  title="No role assigned yet — roles are granted on invite or via the Roles screen.">No role</span>
                                        @endif
                                    </td>
                                    <td class="member-joined">{{ optional($member->created_at)->format('jS M, Y') ?? '—' }}</td>
                                    @if($isAdmin)
                                        <td class="text-right">
                                            @unless($member->is_self)
                                                <form method="POST" action="{{ route('team.remove', $member->uuid) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm waves-effect waves-light"
                                                            data-confirm
                                                            data-confirm-title="Remove team member?"
                                                            data-confirm-message="Remove <b>{{ $member->email }}</b> from the team? They will lose access to this account."
                                                            data-confirm-button="Remove member"
                                                            data-confirm-button-class="btn-danger">
                                                        <i class="mdi mdi-account-remove-outline mr-1"></i> Remove
                                                    </button>
                                                </form>
                                            @endunless
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isAdmin ? 6 : 5 }}" class="text-center text-muted py-4">
                                        @if(request('search') || request('role'))
                                            No team members match your filters.
                                        @else
                                            No team members found.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $members->links() }}
                    </div>

                    <p class="text-muted font-13 mt-2 mb-0">
                        <i class="mdi mdi-information-outline mr-1"></i>
                        Roles are granted on sign-up and team invites; abilities are managed on the
                        <a href="{{ route('role.list') }}" class="font-weight-medium">Roles</a> screen.
                    </p>
                </div>
            </div>
        </div>
    </div> <!-- end row -->
@stop

@section('after-styles')
    <style>
        /* Team table typography — cleaner, denser and more readable than the theme
           default (Open Sans at 13px). Uses the system font stack, so no webfont
           download is needed and it renders crisp on every OS. */
        #team-members-table {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                         "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 13.5px;
            line-height: 1.55;
            letter-spacing: 0.01em;
        }
        #team-members-table thead th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 14px;
            white-space: nowrap;
        }
        #team-members-table tbody td {
            padding: 13px 14px;
            vertical-align: middle;
            color: #64748b;
            border-top: 1px solid #f1f5f9;
        }
        /* No background tint on the header or on row hover — typography only. */
        #team-members-table.table-hover tbody tr:hover,
        #team-members-table.table-hover tbody tr:hover td {
            background-color: transparent;
        }
        #team-members-table tbody td.text-muted { color: #94a3b8; }
        #team-members-table .member-name {
            font-weight: 500;
            color: #64748b;
        }
        #team-members-table .member-email,
        #team-members-table .member-joined { color: #94a3b8; }
        #team-members-table .badge {
            font-family: inherit;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.02em;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
@stop

@extends('layouts.layout')

@section('title','Audit Trail')
@section('content')
    @component('partials.collapsible-filter', ['filterTitle' => 'Audit trail Search'])
        @slot('form')
            <form action="{{route('audit.trail.index')}}" method="GET">
                <p class="card-text">
                    Admin Id:
                    <input type="text" id="adminId" value="{{request('adminId')}}" name="adminId" class="form-control" placeholder="e.g. 12"/>
                </p>
                <p class="card-text">
                    Company Id:
                    <input type="text" id="companyId" value="{{request('companyId')}}" name="companyId" class="form-control" placeholder="Leave blank for your company"/>
                </p>
                <p class="card-text">
                    Action:
                    <input type="text" id="actionId" value="{{request('action')}}" name="action" class="form-control" placeholder="e.g. login, delete"/>
                </p>
                <p class="card-text">
                    Severity:
                    <select name="severity" id="severityId" class="form-control">
                        <option value="">All</option>
                        <option value="info" {{request('severity') === 'info' ? 'selected' : ''}}>Info</option>
                        <option value="warning" {{request('severity') === 'warning' ? 'selected' : ''}}>Warning</option>
                        <option value="critical" {{request('severity') === 'critical' ? 'selected' : ''}}>Critical</option>
                    </select>
                </p>
                <p class="card-text">
                    Date:
                    <input class="form-control" type="date" name="date" value="{{request('date')}}" id="dateId">
                </p>

                <button type="submit" class="btn btn-primary waves-effect waves-light">Search</button>
                <button type="button" class="btn btn-primary waves-effect waves-light" id="resetId">Reset</button>
                <a class="btn btn-primary waves-effect waves-light" onclick="print();">Print</a>
            </form>
        @endslot

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Initiator</th>
                            <th>Action</th>
                            <th>Severity</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Browser</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($auditTrails as $auditTrail)
                            <tr>
                                <td>{{ $sn++ }}</td>
                                <td>
                                    <b>{{ optional($auditTrail->admin)->first_name }} {{ optional($auditTrail->admin)->last_name ?: 'System' }}</b><br>
                                    <small class="text-muted">{{ optional($auditTrail->admin)->email ?? '—' }}</small><br>
                                    @if($auditTrail->role)
                                        <span class="badge badge-primary">{{ ucfirst($auditTrail->role) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $auditTrail->action }}</code>
                                    @if($auditTrail->subject_type)
                                        <br><small class="text-muted">{{ $auditTrail->subject_type }} #{{ $auditTrail->subject_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $sevClass = $auditTrail->severity === 'critical' ? 'badge-danger' : ($auditTrail->severity === 'warning' ? 'badge-warning' : 'badge-info');
                                    @endphp
                                    <span class="badge {{ $sevClass }}">{{ ucfirst($auditTrail->severity ?? 'info') }}</span>
                                </td>
                                <td>{{ $auditTrail->description }}</td>
                                <td><code>{{ $auditTrail->ip_address ?? '—' }}</code></td>
                                <td>{{ $auditTrail->browser_label ?? '—' }}</td>
                                <td>{{ $auditTrail->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No audit trail entries found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $auditTrails->links() }}
                </div>

            </div>
        </div>
    @endcomponent
@stop

@section('after-scripts')
    <script>
        $("#resetId").on('click', function(){
            $("#adminId").val('');
            $("#companyId").val('');
            $("#actionId").val('');
            $("#severityId").val('');
            $("#dateId").val('');
        });
    </script>

    @include('partials.collapsible-filter-scripts')
@stop

@section('before-styles')
@stop

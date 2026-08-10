{{-- Reusable user status pill. Expects: $status ('active' | 'disable') --}}
@php
    $isActive  = ($status ?? null) === 'active';
    $label     = $isActive ? 'Active' : 'Disabled';
    $badgeClass = $isActive ? 'badge-success' : 'badge-danger';
@endphp
<span class="badge badge-pill {{ $badgeClass }}">{{ $label }}</span>

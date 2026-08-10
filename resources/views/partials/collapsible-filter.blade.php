{{--
    Collapsible report filter sidebar.

    Renders the full .row: a collapsible filter card on the left and the
    table content on the right, which expands to the full width when the
    filter panel is hidden. The panel state persists across visits.

    Usage:
        @component('partials.collapsible-filter', ['filterTitle' => 'Report Search'])
            @slot('form')
                <form ...>...</form>
            @endslot

            <div class="card">... table ...</div>
        @endcomponent

    Also include partials.collapsible-filter-scripts inside the page's
    @section('after-scripts') to wire up the toggle behaviour.
--}}
<div class="row">
    <div class="col-md-3" id="filterCol">

        <!-- Simple card -->
        <div class="card m-b-30">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="card-title font-16 mt-0 mb-0">{{ $filterTitle ?? 'Search' }}</h4>
                    <button type="button" class="btn btn-outline-secondary btn-sm p-1 px-2" data-toggle="collapse"
                            data-target="#filterBody" aria-expanded="true" aria-controls="filterBody"
                            id="filterToggle" title="Collapse filters">
                        <i class="mdi mdi-chevron-up" id="filterToggleIcon"></i>
                    </button>
                </div>
                <div class="collapse show" id="filterBody">
                    {!! $form !!}
                </div>
            </div>
        </div>

    </div><!-- end col -->

    <div class="col-md-9" id="tableCol">
        <button type="button" id="showFiltersBtn" class="btn btn-outline-primary btn-sm mb-3 d-none"
                data-toggle="collapse" data-target="#filterBody" aria-expanded="false"
                aria-controls="filterBody">
            <i class="mdi mdi-filter-variant mr-1"></i> Show filters
        </button>
        {!! $slot !!}
    </div>
</div>

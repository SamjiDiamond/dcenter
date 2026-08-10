{{--
    Toggle behaviour for partials.collapsible-filter.
    Include inside the page's @section('after-scripts').
--}}
<script>
    function setReportFilterPanelState(collapsed) {
        // Force both col classes so exactly one is ever present, and hide the
        // whole left column (header included) when collapsed.
        $('#filterCol').toggleClass('d-none', collapsed);
        $('#tableCol').toggleClass('col-md-9', !collapsed).toggleClass('col-md-12', collapsed);
        $('#filterToggleIcon').toggleClass('mdi-chevron-up', !collapsed).toggleClass('mdi-chevron-down', collapsed);
        $('#showFiltersBtn').toggleClass('d-none', !collapsed);
    }

    $(function () {
        // Restore the user's last filter-panel state across visits. The visual
        // state is applied synchronously so there's no flash while the collapse
        // animation plays.
        var wasCollapsed = localStorage.getItem('reportFilterCollapsed') === '1';
        setReportFilterPanelState(wasCollapsed);
        if (wasCollapsed) {
            $('#filterBody').collapse('hide');
        }

        $('#filterBody')
            // hide/show fire immediately, so the table widens the moment the
            // panel starts moving instead of after the animation ends.
            .on('hide.bs.collapse', function () {
                setReportFilterPanelState(true);
            })
            .on('show.bs.collapse', function () {
                setReportFilterPanelState(false);
            })
            .on('hidden.bs.collapse', function () {
                localStorage.setItem('reportFilterCollapsed', '1');
            })
            .on('shown.bs.collapse', function () {
                localStorage.setItem('reportFilterCollapsed', '0');
            });
    });
</script>

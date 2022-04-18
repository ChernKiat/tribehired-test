@extends('layouts.app')

@section('page-title', __('International Marketplace SKUs'))
@section('page-heading', __('International Marketplace SKUs'))

@section('styles')
    {!! HTML::style('plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('International Marketplace SKUs')
    </li>
@stop

@section('content')
@include('partials.messages')

<div class="card">
    <div class="card-body">
        <div class="row flex-md-row flex-column">
            <div class="col-md-5">
                <button id="myFilterCollapse" class="filter-by-btn btn btn-outline-primary" data-toggle="collapse" data-target="#marketplaces-filter" aria-expanded="false">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-funnel mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"></path>
                    </svg>
                    Filter by
                </button>
            </div>

            <div class="col-md-7">
                <a href="{{ route('int-marketplace-skus.create') }}" class="btn btn-primary btn-rounded float-right">
                    <i class="fas fa-plus mr-2"></i>
                    @lang('Add International Marketplace SKU')
                </a>
            </div>
        </div>

        <form action="" method="GET" id="marketplaces-form">
            <div id="marketplaces-filter" class="bg-light-blue py-3 px-3 mt-2 border border-primary border-bottom-light collapse">
                <div class="row flex-md-row flex-column">
                    <div class="col-md-2">
                        <div class="form-group mt-2">
                            <label for="date_range">Created Time</label>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="calendar-wrapper input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            <input type="text" id="date_range" class="form-control daterange" value="" autocomplete="off">
                            <div id="myDateButton" class="input-group-append" style="cursor:pointer;">
                                <span class="input-group-text">
                                    <i class="fa fa-times daterangepicker-remove"></i>
                                </span>
                            </div>

                            <input type="hidden" id="appointment_start_date" name="selected_start_date" value="{{ Request::get('selected_start_date') ?? '' }}">
                            <input type="hidden" id="appointment_end_date" name="selected_end_date" value="{{ Request::get('selected_end_date') ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row flex-md-row flex-column">
                    <div class="col-md-10 offset-md-2">
                        <div class="calendar-wrapper input-group mb-3">
                            <input type="text"
                                id="mySearchField"
                                class="form-control input-solid"
                                name="search"
                                value="{{ Request::get('search') }}"
                                placeholder="@lang('Search for International Marketplace SKU...')">
                        </div>
                    </div>
                </div>

                <div class="row my-3 flex-md-row flex-column-reverse">
                    <div class="col-md-12">
                        <button id="myFilterButton" class="btn btn-light" type="submit">
                            <i class="fas fa-search text-muted"></i>
                            @lang('Filter')
                        </button>
                    </div>
                </div>
            </div>

            <div style="padding-top:50px;" class="d-flex justify-content-between">
                <div class="form-group flex-fill">
                    <label for="perPage">Items / page</label>
                    <div class="d-flex align-items-center">
                        <select class="form-control w-50 custom-select" id="perPage" name="perPage" onchange="changeItemsPerPage(this)">
                            <option {{ Request::get('perPage') == '5' ? 'selected' : '' }} >5</option>
                            <option {{ Request::get('perPage') == '10' ? 'selected' : '' }} >10</option>
                            <option {{ Request::get('perPage') == '15' ? 'selected' : '' }} >15</option>
                            <option {{ Request::get('perPage') == '20' ? 'selected' : (!Request::has('perPage') ? 'selected' : '') }} >20</option>
                            <option {{ Request::get('perPage') == '25' ? 'selected' : '' }} >25</option>
                            <option {{ Request::get('perPage') == '50' ? 'selected' : '' }} >50</option>
                            <option {{ Request::get('perPage') == '75' ? 'selected' : '' }} >75</option>
                            <option {{ Request::get('perPage') == '100' ? 'selected' : '' }} >100</option>
                        </select>
                        <label for="perPage" style="margin-left:10px; margin-top:-3px;"> <small class="font-weight-normal">of</small> {{ $skus->total() }}</label>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive" id="marketplaces-table-wrapper">
            {!! $skus->appends($_GET)->links() !!}
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th class="min-width-20">No</th>
                        <th class="min-width-80">Marketplace Name</th>
                        <th class="min-width-80">Model</th>
                        <th class="min-width-80">Created Time</th>
                        <th class="text-center min-width-150">@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($skus))
                        @foreach ($skus as $key => $sku)
                            @include('admin.international_marketplace_sku.partials.row')
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5"><em>@lang('No records found.')</em></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{ $skus->appends($_GET)->links() }}
        </div>
    </div>
</div>
@stop

@section('scripts')
{!! HTML::script('plugins/bootstrap-daterangepicker/moment.min.js') !!}
{!! HTML::script('plugins/bootstrap-daterangepicker/daterangepicker.js') !!}
{!! HTML::script('js/chernkiat/myDateTimeRange.js') !!}
<script>
$(document).ready(function() {
    $('#myFilterButton').click(function() {
        if ($('#mySearchField').val() == '') { $('#mySearchField').prop('disabled', true); }
        if ($('#appointment_start_date').val() == '') { $('#appointment_start_date').prop('disabled', true); }
        if ($('#appointment_end_date').val() == '') { $('#appointment_end_date').prop('disabled', true); }
    });
});

function changeItemsPerPage(source) {
    if ($('#mySearchField').val() == '') { $('#mySearchField').prop('disabled', true); }
    if ($('#appointment_start_date').val() == '') { $('#appointment_start_date').prop('disabled', true); }
    if ($('#appointment_end_date').val() == '') { $('#appointment_end_date').prop('disabled', true); }
    $('#marketplaces-form').submit();
}
</script>
@stop

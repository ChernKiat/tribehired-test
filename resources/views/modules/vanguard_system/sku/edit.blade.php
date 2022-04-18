@extends('layouts.app')

@section('page-title', __('Edit International Marketplace SKU'))
@section('page-heading', __('Edit International Marketplace SKU'))

@section('styles')
    {!! HTML::style('plugins/select2/css/select2.css') !!}
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('int-marketplace-skus.index') }}">@lang('International Marketplace SKUs')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Edit')
    </li>
@stop

@section('content')
@include('partials.messages')

{!! Form::open(['route' => array('int-marketplace-skus.update', $sku), 'id' => 'marketplace-form']) !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('International Marketplace SKU Details')
                    </h5>
                    <p class="text-muted font-weight-light">
                        @lang('A general international marketplace sku details information.')
                    </p>
                </div>
                <div class="col-md-9">
                    @include('admin.international_marketplace_sku.partials.form', ['edit' => true])
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
                @lang('Save International Marketplace SKU')
            </button>
        </div>
    </div>
{!! Form::close() !!}
<br>
@stop

@section('scripts')
    {!! HTML::script('plugins/select2/js/select2.js') !!}
    {!! JsValidator::formRequest('Vanguard\Http\Requests\International\SKURequest', '#marketplace-form') !!}
    <script>
    $(document).ready(function() {
        $('.select2').select2();
    });
    </script>
@stop

@extends('modules.app')

@section('page-title', __('View IPFS'))
@section('page-heading', __('View IPFS'))

@section('styles')
    {!! HTML::style('plugins/select2/css/select2.css') !!}
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('IPFS')
    </li>
@stop

@section('content')
@include('modules.message')

<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-info"><span class="glyphicon glyphicon-print" aria-hidden="true"></span>IPFS</button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        {!! Form::open(['files' => true, 'id' => 'ipfs-form']) !!}
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active"
                                id="info-tab"
                                data-toggle="tab"
                                href="#infos"
                                role="tab"
                                aria-controls="info-tab"
                                aria-selected="false">
                                @lang('Info')
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                                id="status-tab"
                                data-toggle="tab"
                                href="#statuses"
                                role="tab"
                                aria-controls="status-tab"
                                aria-selected="false">
                                @lang('Status')
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-4" id="nav-tabContent">
                        <div class="tab-pane fade show active px-2"
                            id="infos"
                            role="tabpanel"
                            aria-labelledby="nav-info-tab">
                            @include('modules.vanguard_system.ipfs.partials.info', ['edit' => false])
                        </div>

                        <div class="tab-pane fade px-2"
                            id="statuses"
                            role="tabpanel"
                            aria-labelledby="nav-status-tab">
                            @include('modules.vanguard_system.ipfs.partials.status', ['edit' => false])
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
<br>
@stop

@section('scripts')
    {!! HTML::script('plugins/select2/js/select2.js') !!}
    <script>
    $(function() {
        $('.select2').select2();
    });
    </script>
@stop

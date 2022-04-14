@extends('modules.app')

@section('page-title', __('View Delivery'))
@section('page-heading', __('View Delivery'))

@section('styles')
    {!! HTML::style('plugins/select2/css/select2.css') !!}
    <style>
        .photo-request-containerv2.primary p {
            font-size: 15px;
            color: #3a84c5;
        }

        .photo-request-containerv2 p {
            font-size: 15px;
            color: #ccc;
            position: absolute;
            top: 10px;
            width: 100%;
        }

        .photo-request-containerv2.primary {
            border-color: #ddd;
        }

        .photo-request-containerv2 {
            border: solid 1px #ddd;
            background-color: #fff;
            text-align: center;
            border-radius: 5px;
            position: relative;
            min-height: 150px;
        }

        .photo-request-containerv2 .image-container {
            position: relative;
            z-index: 9;
        }

        .photo-request-containerv2 .image-container img {
            max-width: 100%;
            height: auto;
            margin: 20px 0 0;
        }

        .photo-request-containerv2 i {
            display: block;
            font-size: 30px;
            margin: 25px auto 10px;
        }
    </style>
@stop

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('deliveries.index') }}">@lang('Deliveries')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('View')
    </li>
@stop

@section('content')
@include('partials.messages')

<div class="row">
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-info" onClick="print()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span>Print Delivery</button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        {!! Form::open(['files' => true, 'id' => 'delivery-form']) !!}
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
                            @include('admin.delivery.partials.info', ['edit' => true])
                        </div>

                        <div class="tab-pane fade px-2"
                            id="statuses"
                            role="tabpanel"
                            aria-labelledby="nav-status-tab">
                            @include('admin.delivery.partials.status', ['edit' => true])
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

        $('.myOrderImage').click(function() {
            $("#myImageModal").modal('show');
            $("#myImageImage").attr('src', $(this).data('image'));
        });
    });

    function print() {
        window.open('{{ route('deliveries.print', ['array[0]' => $order]) }}', '_blank');
    }
    </script>
@stop

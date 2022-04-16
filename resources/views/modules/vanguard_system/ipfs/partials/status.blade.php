<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <p class="text-dark font-weight-bold">@lang('DO Number')</p>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <p class="text-secondary font-weight-light">{{ $order->delivery_details->tracking_code ?? '-' }}</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <p class="text-dark font-weight-bold">@lang('Appointment Status')</p>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <p class="text-secondary font-weight-light">{{ $appointmentStatusDescriptionList[$order->appointments->appointment_status] ?? '-' }}</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <p class="text-dark font-weight-bold">@lang('Appointment Date')</p>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <p class="text-secondary font-weight-light">{{ $order->appointments->appointment_start ?? '-' }}</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <p class="text-dark font-weight-bold">@lang('Delivery Driver')</p>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <p class="text-secondary font-weight-light">{{ $order->delivery_details->delivery_agent->name ?? '-' }}</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <p class="text-dark font-weight-bold">@lang('Delivery Status')</p>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <p class="text-secondary font-weight-light">{{ $deliveryStatusDescriptionList[$order->delivery_details->current_delivery_status] ?? '-' }}</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <p class="text-dark font-weight-bold">@lang('Delivery Date')</p>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <p class="text-secondary font-weight-light">{{ $order->delivery_details->delivery_date ?? '-' }}</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3">
        <div class="row" id="photo-upload-container">
            @for ($i = 1; $i <= 5; $i++)
            <div class="col-md-4 pb-4">
                <div id="image_{{ $i }}" class="photo-request-containerv2 primary">
                    <div class="image-container" data-container="id_{{ $i }}">
                        @if (!empty($order->delivery_details->{'image_' . $i}))
                            <img src="{!! asset("storage/orders/{$order->id}/{$order->delivery_details->{'image_' . $i} }") !!}" width="150">
                        @endif
                    </div>
                    <p class="upload-image-text" style="{{ !empty($order->delivery_details->{'image_' . $i}) ? 'display:none;' : 'display:block;' }}">
                        <i class="far fa-image"></i>
                        @lang('Image')
                    </p>
                </div>
                <button type="button" class="btn btn-primary myOrderImage" style="width:100%" data-image="{{ !empty($order->delivery_details->{'image_' . $i}) ? asset("storage/orders/{$order->id}/{$order->delivery_details->{'image_' . $i} }") : '' }}"{{ empty($order->delivery_details->{'image_' . $i}) ? ' disabled' : '' }}>@lang('Show')</button>
            </div>
            @endfor
            <div class="col-md-4 pb-4">
                <div id="image_pod_sign" class="photo-request-containerv2 primary">
                    <div class="image-container" data-container="id_pod_sign">
                        @if (!empty($order->delivery_details->pod_sign))
                            <img src="{!! asset("storage/orders/{$order->id}/{$order->delivery_details->pod_sign}") !!}" width="150">
                        @endif
                    </div>
                    <p class="upload-image-text" style="{{ !empty($order->delivery_details->pod_sign) ? 'display:none;' : 'display:block;' }}">
                        <i class="far fa-image"></i>
                        @lang('POD')
                    </p>
                </div>
                <button type="button" class="btn btn-primary myOrderImage" style="width:100%" data-image="{{ !empty($order->delivery_details->pod_sign) ? asset("storage/orders/{$order->id}/{$order->delivery_details->pod_sign}") : '' }}"{{ empty($order->delivery_details->pod_sign) ? ' disabled' : '' }}>@lang('Show')</button>
            </div>
        </div>
    </div>
</div>

<div id="myImageModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Preview Image')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" id="myImageImage" height="" width="100%" alt="order image" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('OK')</button>
            </div>
        </div>
    </div>
</div>

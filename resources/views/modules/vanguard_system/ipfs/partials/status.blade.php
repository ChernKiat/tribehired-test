<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <p class="text-dark font-weight-bold">@lang('DO Number')</p>
        </div>
    </div>

    <div class="col-md-8">
        <div class="form-group">
            <p class="text-secondary font-weight-light">{{ $edit ? $order->delivery_details->tracking_code : '-' }}</p>
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
            <p class="text-secondary font-weight-light">{{ $edit ? $order->appointments->appointment_start : '-' }}</p>
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
            <p class="text-secondary font-weight-light">{{ $edit ? $order->delivery_details->delivery_agent->name : '-' }}</p>
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
            <p class="text-secondary font-weight-light">{{ $edit ? $order->delivery_details->delivery_date : '-' }}</p>
        </div>
    </div>
</div>

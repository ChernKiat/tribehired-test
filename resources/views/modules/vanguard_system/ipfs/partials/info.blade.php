@if ($edit)
<input type="hidden" name="_method" value="PUT" />
@endif

<div class="row">
    <div class="col-md-12">
        @if ($edit)
        <div class="form-group">
            <label for="tracking_code">@lang('DO Number')</label>
            <input type="text" id="tracking_code" class="form-control input-solid"
                   placeholder="" value="{{ $order->delivery_details->tracking_code ?? '-' }}" readonly>
        </div>
        @endif

        <div class="form-group">
            <label for="account_no">@lang('Job Number') <span class="required-icon">*</span></label>
            <input type="text" id="account_no" class="form-control input-solid"
                   placeholder="" name="account_no" value="{{ $edit ? $order->account_no : old('account_no', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="customer_name">@lang('Customer Name') <span class="required-icon">*</span></label>
            <input type="text" id="customer_name" class="form-control input-solid"
                   placeholder="" name="customer_name" value="{{ $edit ? $order->customer_name : old('customer_name', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="customer_phone">@lang('Customer Phone Number') <span class="required-icon">*</span></label>
            <input type="number" id="customer_phone" class="form-control input-solid"
                   placeholder="60" name="customer_phone" value="{{ $edit ? $order->customer_phone : old('customer_phone', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="delivery_postcode">@lang('Customer Postcode') <span class="required-icon">*</span></label>
            <input type="number" id="delivery_postcode" class="form-control input-solid"
                   placeholder="" name="delivery_postcode" value="{{ $edit && !empty($order->delivery_details) ? $order->delivery_details->delivery_postcode : old('delivery_postcode', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="delivery_address">@lang('Customer Address') <span class="required-icon">*</span></label>
            <textarea id="delivery_address" class="form-control input-solid"
                   name="delivery_address" {{ $edit ? 'readonly' : '' }}>{{ $edit && !empty($order->delivery_details) ? $order->delivery_details->delivery_address : old('delivery_address', '') }}</textarea>
        </div>
    </div>
</div>

<hr/>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="item_name">@lang('Item Name') <span class="required-icon">*</span></label>
            <input type="text" id="item_name" class="form-control input-solid"
                   placeholder="" name="item_name" value="{{ $edit ? $order->item->item_name : old('item_name', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="item_sku">@lang('Item SKU') <span class="required-icon">*</span></label>
            <input type="text" id="item_sku" class="form-control input-solid"
                   placeholder="" name="item_sku" value="{{ $edit ? $order->item->item_sku : old('item_sku', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="accessories">@lang('Accessories')</label>
            <input type="text" id="accessories" class="form-control input-solid"
                   placeholder="" name="accessories" value="{{ $edit ? $order->item->accessories : old('accessories', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="weight">@lang('Weight (kg)')</label>
            <input type="number" step=".01" id="weight" class="form-control input-solid"
                   placeholder="@lang('0')" name="weight" value="{{ $edit ? $order->item->weight : old('weight', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="height">@lang('Height (cm)')</label>
            <input type="number" step=".01" id="height" class="form-control input-solid"
                   placeholder="@lang('0')" name="height" value="{{ $edit ? $order->item->height : old('height', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="width">@lang('Width (cm)')</label>
            <input type="number" step=".01" id="width" class="form-control input-solid"
                   placeholder="@lang('0')" name="width" value="{{ $edit ? $order->item->width : old('width', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="length">@lang('Length (cm)')</label>
            <input type="number" step=".01" id="length" class="form-control input-solid"
                   placeholder="@lang('0')" name="length" value="{{ $edit ? $order->item->length : old('length', '') }}" {{ $edit ? 'readonly' : '' }}>
        </div>

        @if ($edit)
        <div class="form-group">
            <label for="serial_number">@lang('Serial Number')</label>
            <input type="text" id="serial_number" class="form-control input-solid"
                   placeholder="" value="{{ $order->item->serial_number }}" readonly>
        </div>
        @endif
    </div>
</div>

<hr/>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="delivery_remark">@lang('Remark')</label>
            <textarea id="delivery_remark" class="form-control input-solid"
                   name="delivery_remark" {{ $edit ? 'readonly' : '' }}>{{ $edit && !empty($order->delivery_details) ? $order->delivery_details->delivery_remark : old('delivery_remark', '') }}</textarea>
        </div>
    </div>
</div>

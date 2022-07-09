@if ($edit)
<input type="hidden" name="_method" value="PUT" />
@endif

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="exchange">@lang('Exchange') <span class="required-icon">*</span></label>
            <select id="exchange" class="select2 form-control input-solid" name="exchange">
                <option value="">Please Select</option>
                @foreach ($exchanges as $key => $exchange)
                    <option value="{{ $key }}">{{ $exchange }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="exchange">@lang('Exchange') <span class="required-icon">*</span></label>
            <select id="exchange" class="select2 form-control input-solid" name="exchange">
                <option value="">Please Select</option>
                @foreach ($exchanges as $key => $exchange)
                    <option value="{{ $key }}">{{ $exchange }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="currency">@lang('Currency') <span class="required-icon">*</span></label>
            <select id="currency" class="select2 form-control input-solid" name="currency">
                <option value="">Please Select</option>
                @foreach ($currencies as $key => $currency)
                    <option value="{{ $key }}">{{ $currency }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<hr/>

<div class="row">
    <div id="currency" class="col-md-12">
    </div>
</div>

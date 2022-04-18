@if ($edit)
    <input type="hidden" name="_method" value="PUT" />
@endif

<div class="row">
    <div class="col-md-12">
        <?php
            $marketplaceSourceDescriptionList = \Vanguard\InternationalMarketplaceSetting::INT_MARKETPLACE_SOURCE_DESCRIPTION_LIST;
        ?>
        <div class="form-group">
            <label for="int_marketplace_setting">@lang('Setting') <span class="required-icon">*</span></label>
            <select id="int_marketplace_setting" class="form-control input-solid select2" name="int_marketplace_setting">
                <option value="">Please Select</option>
                @foreach ($settingList as $setting)
                    <option value="{{ $setting->id }}"{{ $edit && $sku->int_marketplace_setting_id == $key ? ' selected' : '' }}>{{ "{$setting->int_marketplace_name} ({$marketplaceSourceDescriptionList[$setting->int_marketplace_source]})" }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="model">@lang('Model') <span class="required-icon">*</span></label>
            <select id="model" class="form-control input-solid select2" name="model">
                <option value="">Please Select</option>
                @foreach ($modelList as $key => $model)
                    <option value="{{ $key }}"{{ $edit && $sku->model_id == $key ? ' selected' : '' }}>{{ $model }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="remote_sku">@lang('Remote SKU') <span class="required-icon">*</span></label>
            <input type="text" class="form-control input-solid" id="remote_sku"
                   name="remote_sku" value="{{ $edit ? $marketplace->remote_sku : '' }}">
        </div>
    </div>
</div>

<tr>
    <td class="align-middle">{{ $key + 1 }}</td>
    <td class="align-middle">{{ $sku->setting->int_marketplace_name ?? '-' }}</td>
    <td class="align-middle">{{ $sku->model->model_name ?? '-' }}</td>
    <td class="align-middle">{{ $sku->created_at }}</td>
    <td class="text-center align-middle">
        <a href="{{ route('int-marketplace-skus.edit', $sku) }}"
        class="btn btn-icon edit"
        title="@lang('Edit Marketplace SKU')"
        data-toggle="tooltip" data-placement="top">
            <i class="fas fa-edit"></i>
        </a>

        <a href="{{ route('int-marketplace-skus.destroy', $sku) }}"
        class="btn btn-icon"
        title="@lang('Delete Marketplace SKU')"
        data-toggle="tooltip"
        data-placement="top"
        data-method="DELETE"
        data-confirm-title="@lang('Please Confirm')"
        data-confirm-text="@lang('Are you sure that you want to delete this marketplace sku?')"
        data-confirm-delete="@lang('Yes, delete him!')">
            <i class="fas fa-trash"></i>
        </a>
    </td>
</tr>

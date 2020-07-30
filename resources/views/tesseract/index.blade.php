@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form action="" method="GET" id="data-form" class="pb-2 mb-3 border-bottom-light">
                        <div class="row my-3 flex-md-row flex-column-reverse">
                            <div class="col-md-4">
                                <div class="input-group custom-search-form">
                                    <input type="text"
                                           class="form-control input-solid"
                                           name="search"
                                           value="{{ Request::get('search') }}"
                                           placeholder="{{ trans('tesseract.search_for') . ' documents ...' }}">

                                        <span class="input-group-append">
                                            @if (Request::has('search') && Request::get('search') != '')
                                                <a href="{{ route('documents.index') }}"
                                                       class="btn btn-light d-flex align-items-center text-muted"
                                                       role="button">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            @endif
                                            <button class="btn btn-light" type="submit" id="search-users-btn">
                                                <i class="fas fa-search text-muted"></i>
                                            </button>
                                        </span>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <a href="{{ route('documents.create') }}" class="btn btn-primary btn-rounded float-right">
                                    <i class="fas fa-plus mr-2"></i>
                                    {{ trans('tesseract.upload') . ' Document' }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="data-table-wrapper">
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <th class="col-xs-4">{{ trans('tesseract.name') }}</th>
                                <th class="col-xs-2">{{ trans('tesseract.extension') }}</th>
                                <th class="col-xs-2">{{ trans('tesseract.no_of_pages') }}</th>
                                <th class="col-xs-4">{{ trans('tesseract.operation') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if (!empty($data) && !$data->isEmpty())
                                    @foreach ($data as $row)
                                    <tr>
                                        <td class="align-middle">{{ $row->name }}</td>
                                        <td class="align-middle">{{ $row->extension }}</td>
                                        <td class="align-middle">{{ $row->page }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('documents.edit', $row) }}"
                                               class="btn btn-icon edit"
                                               title="{{ trans('tesseract.edit') . ' Document' }}"
                                               data-toggle="tooltip"
                                               data-placement="top">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="{{ route('documents.crop', $row) }}"
                                               class="btn btn-icon"
                                               title="{{ trans('tesseract.crop') . ' Document' }}"
                                               data-toggle="tooltip"
                                               data-placement="top">
                                                <i class="fas fa-cut"></i>
                                            </a>

                                            <a href="{{ route('documents.destroy', $row) }}"
                                               class="btn btn-icon"
                                               title="{{ trans('tesseract.delete') . ' Document' }}"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               data-method="DELETE"
                                               data-confirm-title="{{ trans('tesseract.please_confirm') }}"
                                               data-confirm-text="{{ trans('tesseract.are_you_sure_that_you_want_to_delete_this') . '?' }}"
                                               data-confirm-delete="{{ trans('tesseract.yes_comma_delete_it') . '!' }}">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="4" class="text-center"><em>{{ trans('tesseract.no_records_found_dot') }}</em></td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $data->appends($_GET)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form id="data-form" action="{{ !empty($edit) ? route('documents.update', $data->id) : route('documents.store') }}" method="POST" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-body">
                        {{ csrf_field() }}
                        @if (!empty($edit))
                            <input type="hidden" name="_method" value="PUT" />
                        @endif

                        <div class="form-group">
                            <label for="name">{{ 'Document ' . trans('tesseract.name') }} <span class="required-icon">*</span></label>
                            <input type="text" id="name" class="form-control input-solid"
                                   placeholder=""
                                   name="name"
                                   value="{{ !empty($edit) ? $data->name : '' }}">
                        </div>

                        <div class="form-group">
                            <label for="description">{{ 'Document ' . trans('tesseract.description') }}</label>
                            <textarea id="description" class="form-control input-solid"
                                      name="description"
                                      rows="4">{{ !empty($edit) ? $data->description : '' }}</textarea>
                        </div>

                        @if (empty($edit))
                        <div class="form-group">
                            <label for="file">{{ trans('tesseract.upload') . ' Document' }} <span class="required-icon">*</span></label>
                            <br/>
                            <input type="file" name="file" id="file">
                        </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            {{ !empty($edit) ?
                                trans('tesseract.edit') . ' Document' :
                                trans('tesseract.upload') . ' Document' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('modules.net_junkies.layouts.button')

            <div class="card">
                <div class="card-header">URL</div>

                <div class="card-body">
                    @include('layouts.alert')

                    <form action="{{ route('netjunkies.post.store') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="url">URL:</label>
                            <textarea id="url" name="url" class="form-control" placeholder="e.g https://www.facebook.com" rows="4"></textarea>
                        </div>
                        <button class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
});
</script>
@endsection

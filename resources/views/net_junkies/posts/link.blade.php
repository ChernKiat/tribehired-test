@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Register</div>

                <div class="card-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success">{!! Session::get('success') !!}</div>
                    @endif
                    @if(Session::has('fail'))
                        <div class="alert alert-danger">{!! Session::get('fail') !!}</div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger">{!! Session::get('error') !!}</div>
                    @endif
                    @if(count($errors) > 0)
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{!! $error !!}</div>
                        @endforeach
                    @endif

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

@section('footer')
<script>
$(function() {
});
</script>
@endsection

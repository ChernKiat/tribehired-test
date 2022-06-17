@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form>
                        <div class="form-group">
                            <label for="code">Room Code:</label>
                            <input type="text" class="form-control" id="myJSRoomCodeField">
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="myJSRoomPlayerField">
                        </div>
                        <button type="button" id="myJSRoomCodeButton" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! HTML::script('js/ws/client.js') !!}
<script>
$(function() {
});
</script>
@endsection

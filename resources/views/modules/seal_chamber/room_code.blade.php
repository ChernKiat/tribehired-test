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
<script>
$(function() {
    class GateOfBabylon {
        constructor() {
            this.Letter = arguments[0];
            this.Score = arguments[1];

            console.log(this);
            if (this.Letter == this.BlankLetter) {
                this.IsBlank = true;
            }
        }

        constructor() {
        }
    }

    $('#myJSRoomCodeButton').click(function() {
        var my_code = $('#myJSRoomCodeField').val();
        var my_player = $('#myJSRoomPlayerField').val();
        if (my_code.length == 5 && my_player) {
            $.ajax({
                url   : '{{ route("sealchamber.room.join") }}',
                type  : 'POST',
                data  : {
                    code    : my_code,
                    player  : my_player,
                    _token  : $('input[name=_token]').val()
                },
                success: function(res) {
                    if (orders.status == 'processing') {
                    } else {
                    }
                }
            });
        }
    });
});
</script>
@endsection

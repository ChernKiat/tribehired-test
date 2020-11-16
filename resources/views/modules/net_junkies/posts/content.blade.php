@extends('layouts.app')

@section('css')
<link href="/css/icheck/skins/flat/blue.css" rel="stylesheet">
<link href="/css/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
@stop

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('modules.net_junkies.layouts.button')

            <div class="card">
                <div class="card-header">Register</div>

                <div class="card-body">
                    @include('layouts.alert')

                    <table class="table table-hover table-bordered my-js-data-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Comment</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($post->comments as $row)
                                <tr>
                                    <td><input type="checkbox"{{ $row->is_selected ? ' checked' : '' }}></td>
                                    <td>
                                        <textarea class="form-control" rows="4" disabled>{{ $row->comment }}</textarea>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-link my-js-button"><span class="glyphicon glyphicon-ok"></span> Save</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="/js/plugin.js"></script>
<script>
$(function() {
    $(".my-js-data-table").DataTable({
        // "order": [[1, 'desc']]
    });

    // $('.my-js-data-table input[type="checkbox"]').iCheck({
    //     checkboxClass: 'icheckbox_flat-blue',
    //     radioClass: 'iradio_flat-blue'
    // });
});
</script>
@endsection

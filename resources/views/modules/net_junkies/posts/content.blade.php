@extends('layouts.app')

@section('css')
<link href="/css/icheck/skins/flat/blue.css" rel="stylesheet">
<link href="/css/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="/css/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
@stop

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @include('modules.net_junkies.layouts.button')

            <div class="list-group">
              <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>


              <a href="#" class="list-group-item list-group-item-action list-group-item-primary">This is a primary list group item</a>
              <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">This is a secondary list group item</a>
              <a href="#" class="list-group-item list-group-item-action list-group-item-success">This is a success list group item</a>
              <a href="#" class="list-group-item list-group-item-action list-group-item-danger">This is a danger list group item</a>
              <a href="#" class="list-group-item list-group-item-action list-group-item-warning">This is a warning list group item</a>
              <a href="#" class="list-group-item list-group-item-action list-group-item-info">This is a info list group item</a>
              <a href="#" class="list-group-item list-group-item-action list-group-item-light">This is a light list group item</a>
              <a href="#" class="list-group-item list-group-item-action list-group-item-dark">This is a dark list group item</a>
            </div>

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
<script src="/js/icheck/icheck.js"></script>
<script>
$(function() {
    $(".my-js-data-table").DataTable({
        // "order": [[1, 'desc']]
    });

    $('.my-js-data-table input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
});
</script>
@endsection

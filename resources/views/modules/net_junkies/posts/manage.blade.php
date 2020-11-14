@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('modules.net_junkies.layouts.button')

            <div class="card">
                <div class="card-header">List</div>

                <div class="card-body">
                    @include('layouts.alert')

                    <table class="table table-hover table-bordered my-js-data-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Title</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $row)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        @if ($row->main_type == \App\Models\NetJunkies\Post::MAIN_TYPE_TEXT)
                                        {{ $row->title }}
                                        @elseif ($row->main_type == \App\Models\NetJunkies\Post::MAIN_TYPE_IMAGE && !empty($row->image))
                                        <img src="{{ $row->image }}" width="100%" height="auto" alt="Post Main Image" />
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('netjunkies.post.edit', $row->id) }}" class="btn btn-link pull-left"><span class="glyphicon glyphicon-ok"></span> Edit</a>
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
<script>
$(function() {
    $(".my-js-data-table").DataTable({
        "order": [[1, 'desc']]
    });
});
</script>
@endsection

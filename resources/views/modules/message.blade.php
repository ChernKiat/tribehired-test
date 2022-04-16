@if (isset($errors) && count($errors) > 0)
    <div class="alert alert-danger alert-notification">
        <ul class="list-unstyled mb-0">
            @foreach($errors->all() as $error)
                <li><i class="fa fa-times"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (Session::get('success', false))
    <?php $data = Session::get('success'); ?>
    <div class="alert alert-success alert-notification">
    @if (is_array($data))
        <ul class="list-unstyled mb-0">
            @foreach ($data as $success)
                <li><i class="fa fa-check"></i> {{ $success }}</li>
            @endforeach
        </ul>
    @else
        <i class="fa fa-check"></i> {{ $data }}
    @endif
    </div>
@endif

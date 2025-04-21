<!-- Success Messages -->
@if(session('success'))
    <div class="container-fluid">
        <div class="alert alert-success alert-dismissable">
            <button class="close" aria-hidden="true" data-dismiss="alert">&times;</button>
            <ul>
                <li>{!! session('success') !!}</li>
            </ul>
        </div>
    </div>
@endif

<!-- Error Messages -->
@if(session('error'))
    <div class="container-fluid">
        <div class="alert alert-danger alert-dismissable">
            <button class="close" aria-hidden="true" data-dismiss="alert">&times;</button>
            <ul>
                <li>{!! session('error') !!}</li>
            </ul>
        </div>
    </div>
@endif

<!-- Validation Errors -->
@if($errors->any())
    <div class="container-fluid">
        <div class="alert alert-danger alert-dismissable">
            <button class="close" aria-hidden="true" data-dismiss="alert">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@extends('app')

@section('content')
    <main class="container">
        @if(request()->has('error'))
        <div class="row">
            <div class="col">
                <div class="alert alert-danger" role="alert">
                    @if(request()->get('error') == 'bad-key')
                    The specified key is incorrect!
                    @elseif(request()->get('error') == 'no-auth')
                    You must authenticate first!
                    @endif
                </div>
            </div>
        </div>
        @endif
        <form action="{{ route('key-auth.auth') }}" method="post">
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" name="key" placeholder="Secret Key">
                </div>
                <div class="col-4">
                    <div class="d-grid">
                        <button class="btn btn-success btn-block">Authenticate</button>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection

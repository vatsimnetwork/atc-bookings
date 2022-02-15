@extends('app')

@section('content')
    <main class="container">
        @if(isset($key))
            <form action="{{ route('key-management.update', ['id' => $key->id]) }}" method="post">
                <input type="hidden" name="_method" value="PUT">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="cid" placeholder="Owner CID" value="{{ $key->cid }}">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="name" placeholder="API Key Name" value="{{ $key->name }}">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="division" placeholder="Division Code" value="{{ $key->division }}">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="subdivision" placeholder="Sub Division Code" value="{{ $key->subdivision }}">
                    </div>
                    <div class="col-2">
                        <div class="d-grid">
                            <button class="btn btn-success btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <form action="{{ route('key-management.store') }}" method="post">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="cid" placeholder="Owner CID">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="name" placeholder="API Key Name">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="division" placeholder="Division Code">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="subdivision" placeholder="Sub Division Code">
                    </div>
                    <div class="col-2">
                        <div class="d-grid">
                            <button class="btn btn-success btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
        <hr>
        <div class="row">
            <div class="col">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Owner CID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Division</th>
                            <th scope="col">Subdivision</th>
                            <th scope="col">Key</th>
                            <th scope="col">Created</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($keys as $key)
                            <tr>
                                <th scope="row">{{ $key->id }}</th>
                                <td>{{ $key->cid }}</td>
                                <td>{{ $key->name }}</td>
                                <td>{{ $key->division }}</td>
                                <td>{{ $key->subdivision }}</td>
                                <td>{{ $key->key }}</td>
                                <td>{{ $key->created_at }}</td>
                                <td>
                                    <a class="btn btn-sm btn-info" href="{{ route('key-management.edit', ['id' => $key->id]) }}">EDIT</a>
                                    <form action="{{ route('key-management.destroy', ['id' => $key->id]) }}" method="post" style="display: inline;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-sm btn-danger">DELETE</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection

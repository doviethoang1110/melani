@extends('admin/master')
@section('title','Chi tiết vai trò')
@section('main')
    <div class="container">
        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <th>Role</th>
                    <th>Permission</th>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="btn btn-danger">{{ $role }}</span></td>
                        <td>
                            @if(!empty($rolePermission))
                                @foreach($rolePermission as $r)
                                    <span class="btn btn-warning">{{ $r }}</span>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('js')
@endsection

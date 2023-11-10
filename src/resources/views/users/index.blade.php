@extends('layouts.admin')

@section('content')

@endsection

<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Users</strong> Dashboard</h1>

    <div class="row">
        <div class="col-12 col-lg-8 col-xxl-9 d-flex">
            <div class="card flex-fill">
                <table class="table table-hover my-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th class="d-none d-xl-table-cell">Start Date</th>
                        <th class="d-none d-xl-table-cell">End Date</th>
                        <th>Status</th>
                        <th class="d-none d-md-table-cell">Assignee</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td class="d-none d-xl-table-cell">{{ $user->current_team_id }}</td>
                            <td class="d-none d-xl-table-cell">{{ $user->is_verified ? "Yes" : "No" }}</td>
                            <td><span class="badge bg-{{ $user->is_verified ? "success" : "default" }}">{{ $user->is_verified ? "Yes" : "No" }}</span></td>
                            <td class="d-none d-md-table-cell">{{ $user->is_verified ? "Yes" : "No" }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

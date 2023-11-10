@extends('layouts.admin')
@section('content')
    <div class="pagetitle">
        <h1>Users</h1>
        {{ \Breadcrumbs::render('users', $users) }}
    </div><!-- End Page Title -->

    <section class="section users-index">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <div class="card">
                        <h5 class="card-title"><i class="fa fa-pencil"></i>Active Users</h5>
                        <div class="card-body">
                            <!-- Active Table -->
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">&nbsp;</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Verified</th>
                                    <th scope="col">Team</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <th scope="row">{{ $user->id }}</th>
                                        <td>
                                            <img src="{{ $user->avatar }}" height="40px" width="40px" alt="" class="img-circle text-center"/>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}">
                                            {{ $user->name }}
                                            </a>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        @if($user->email_verified_at)
                                            <td class="text-center"><i class="bi bi-patch-check-fill text-primary"></i>&nbsp;</td>
                                        @else
                                            <td class="text-center"><i class="bi bi-patch-exclamation-fill text-danger"></i>&nbsp;</td>
                                        @endif

                                        @if($user->isTeamOwner())
                                            <td>
                                                <a href="#">
                                                    <strong>{{ $user->currentTeam->name }}</strong>
                                                </a>
                                            </td>
                                        @else
                                            <td>Unassigned</td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!-- End Tables without borders -->

                        </div>
                    </div>

                    <!-- Pagination with icons -->
                    <nav aria-label="Page navigation example">
                        {{ $users->links() }}
                    </nav><!-- End Pagination with icons -->
                </div>
            </div>
        </div>
    </section>
@endsection

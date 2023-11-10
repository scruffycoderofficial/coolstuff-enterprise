@extends('layouts.admin')

@section('content')
    <div class="pagetitle">
        <h1>Users</h1>
        {{ \Breadcrumbs::render('users_show', $user) }}
    </div><!-- End Page Title -->

    <section class="section users-index">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <div class="card">
                        <h5 class="card-title"><i class="fa fa-pencil"></i>User Details</h5>
                        <div class="card-body">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    </div>
@endsection

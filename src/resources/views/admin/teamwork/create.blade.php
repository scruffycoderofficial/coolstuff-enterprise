@extends('layouts.admin')

@section('content')

    <div class="pagetitle">
        <h1>Teams</h1>
        {{ Breadcrumbs::render('team_create') }}
    </div><!-- End Page Title -->

    <section class="section teams-create">

        <div class="row">

            <div class="col-lg-12">

                <div class="row">

                    <div class="card">

                        <div class="card-body">
                            <h5 class="card-title"><i class="fa fa-pencil"></i>Add New</h5>

                            <form  class="row g-3" method="post" action="{{route('teams.store')}}">
                                {!! csrf_field() !!}

                                <div class="col-sm-7">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingName" placeholder="Your Team Name" name="name" value="{{ old('name') }}">
                                        <label for="floatingName">Your Team Name</label>
                                    </div>
                                </div>

                                <div class="col-7">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Description" name="description" id="floatingTextarea" style="height: 100px;" value="{{ old('description') }}"></textarea>
                                        <label for="floatingTextarea">Description</label>
                                    </div>
                                </div>

                                <div class="col-7">
                                    <div class="form-floating">
                                        <input class="form-control" type="file" id="logoFile" name="logo_file" >
                                        <label for="inputNumber" class="col-sm-2 col-form-label">Upload Logo</label>
                                    </div>
                                </div>

                                <div class="col-7 d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary me-md-2" >Save</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

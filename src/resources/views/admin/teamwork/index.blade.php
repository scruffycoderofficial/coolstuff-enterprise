@extends('layouts.admin')

@section('content')
    <div class="pagetitle">
        <h1>Teams</h1>
        {{ \Breadcrumbs::render('teams', $teams) }}
    </div><!-- End Page Title -->

    <section class="section teams-index">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="card">
                        <h5 class="card-title"><i class="fa fa-pencil"></i>Add New</h5>
                        <div class="card-header text-end">
                            <a href="{{route('teams.create')}}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-pencil"></i> Create new
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($teams as $team)
                                    <tr>
                                        <td>{{$team->name}}</td>
                                        <td>
                                            @if(auth()->user()->isOwnerOfTeam($team))
                                                <span class="label label-success">Owner</span>
                                            @else
                                                <span class="label label-primary">Member</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(is_null(auth()->user()->currentTeam) || auth()->user()->currentTeam->getKey() !== $team->getKey())
                                                <a href="{{route('teams.switch', $team)}}" class="btn btn-sm btn-default">
                                                    <i class="fa fa-sign-in"></i> Switch
                                                </a>
                                            @else
                                                <span class="label label-default">Current team</span>
                                            @endif

                                            <a href="{{route('teams.members.show', $team)}}" class="btn btn-sm btn-default">
                                                <i class="fa fa-users"></i> Members
                                            </a>

                                            @if(auth()->user()->isOwnerOfTeam($team))

                                                <a href="{{route('teams.edit', $team)}}" class="btn btn-sm btn-default">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>

                                                <form style="display: inline-block;" action="{{route('teams.destroy', $team)}}" method="post">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" name="_method" value="DELETE" />
                                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Delete</button>
                                                </form>
                                            @endif
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
    </section>
@endsection
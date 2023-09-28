@extends('layouts.admin')

@section('content')
    <div class="pagetitle">
        <h1>Teams</h1>
        {{ \Breadcrumbs::render('team_members', $team->name) }}
    </div><!-- End Page Title -->

    <section class="section team-members-index">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-end py-3">
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#myModal">Invite to Team</button>
                                <a href="{{route('teams.index')}}" class="btn btn-outline-primary">
                                    <i class="fa fa-arrow-left"></i> My Teams
                                </a>
                            </div>
                            <div class="panel panel-default my-5">
                                <div class="panel-heading clearfix text-primary">Pending invitations</div>
                                <div class="panel-body">

                                    <h1 class="card-title"><i class="fa fa-pencil"></i>Membership Invites</h1>
                                    @if(!$team->invites->isEmpty())
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>E-Mail</th>
                                                <th>Initiated</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            @foreach($team->invites AS $invite)
                                                @if($invite->invite_type == "membership")
                                                    <tr>
                                                        <td>{{$invite->email}}</td>
                                                        <td>{{$invite->created_at->diffForHumans()}}</td>
                                                        <td>
                                                            @if($invite->send_invitation_reminder)
                                                                <a href="{{route('teams.members.resend_invite', $invite)}}" class="btn btn-sm btn-primary">
                                                                    <i class="fa fa-envelope-o"></i> Resend invite
                                                                </a>
                                                            @else
                                                                <a href="{{route('teams.members.revoke_invite', $invite)}}" class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-envelope-o"></i> Revoke invite
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                    @else
                                        <p>There are no membership invitations for this Team.</p>
                                    @endif


                                    <h1 class="card-title"><i class="fa fa-pencil"></i>Ownership Invites</h1>
                                    @if(!$team->invites->isEmpty())
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>E-Mail</th>
                                                <th>Initiated</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            @foreach($team->invites as $invite)
                                                @if($invite->invite_type == "ownership")
                                                    <tr>
                                                        <td>{{$invite->email}}</td>

                                                        <td>{{$invite->created_at->diffForHumans()}}</td>
                                                        <td>
                                                            @if($invite->send_invitation_reminder)
                                                                <a href="{{route('teams.members.resend_ownership_invite', $invite)}}" class="btn btn-sm btn-primary">
                                                                    <i class="fa fa-envelope-o"></i> Resend Invite
                                                                </a>
                                                            @else
                                                                <a href="{{route('teams.members.revoke_ownership_invite', $invite)}}" class="btn btn-sm btn-danger">
                                                                    <i class="fa fa-envelope-o"></i> Revoke Invite
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                    @else
                                        <p>There are no ownership invitations for this Team.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix">Invite to team "{{$team->name}}"</div>
                            <div class="panel-body">
                                <form class="form-horizontal" method="post" action="{{route('teams.members.invite', $team)}}">
                                    {!! csrf_field() !!}
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-btn fa-envelope-o"></i>Invite to Team
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

<script>
    $(document).ready(function(){
        $("#myModal").modal("show");

        $("#myBtn").click(function(){
            $("#myModal").modal("hide");
        });

        $("#myModal").on('hide.bs.modal', function(){
            alert('The modal is about to be hidden.');
        });
    });
</script>

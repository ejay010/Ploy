@extends('applayout')

@section('customcss')
   <link href="{{url('css/grayscale.css')}}" rel="stylesheet">
    <link href="{{url('css/userInterface.css')}}" rel="stylesheet">
    @stop

@section('custommeta')
    <meta name="theU" content="{{ Auth::user()->id }}">
    <meta name="_token" content="{{ csrf_token() }}">
    @stop

@section('content')
<body>
<div class="container-fluid">
    <nav class="nav navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main-collapse" aria-expanded="false">
                    <i class="fa fa-bars"></i>
                </button>
                <p class="navbar-text">@if (Auth::User()) {{Auth::User()->name}} @endif</p>
            </div>
            <div class="collapse navbar-collapse navbar-right" id="navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="page-scroll" href="{{url('/auth/logout')}}">Sign Out</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#Register">Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="row">
        <div class="col-lg-2 sidebar">
            <h4 style="margin-bottom: 10px;">Category:</h4>
            <ul class="nav nav-pills nav-stacked" id="navTabs">
                <li class="active" id="generalLI"><a id="general" href="#">General</a></li>
                <li id="foodLI"><a id="food" href="#">Food</a></li>
                <li id="viceLI"><a id="vice" href="#">Vice</a></li>
                <li id="transpLI"><a id="transport" href="#">Transport</a></li>
            </ul>
            <div class="Post-container">
                <div class="generalPost" style="display: block;">
                    {!! Form::open(['url' => '/saveGeneralPost', 'id' => 'generalPost']) !!}
                    {!! Form::label('title', 'I need a...') !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Personal trainer...']) !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Because I want to...']) !!}
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        {!! Form::number('cost', null, ['class' => 'form-control', 'placeholder' => 'Will pay..', 'aria-label' => 'Amount to the nearest cent', 'step' => '0.01']) !!}
                        <span class="input-group-addon">.00</span>
                        <span class="input-group-btn">
                            {!! Form::hidden('type', 'General') !!}
                            {!! Form::hidden('loc', 'location') !!}
                            {!! Form::close() !!}
                            <button type="button" class="form-control btn btn-default" onclick="submitAPost('#generalPost')">Post</button>
                        </span>
                    </div>
                </div>

                <div class="foodPost">
                    {!! Form::open(['id' => 'foodPost', 'url' => '/saveFoodPost']) !!}
                    {!! Form::label('title', 'Get Me...') !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Macdonalds please']) !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'I am hungry for...']) !!}
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        {!! Form::number('cost', null, ['class' => 'form-control', 'placeholder' => 'Will pay..', 'aria-label' => 'Amount to the nearest cent', 'step' => '0.01']) !!}
                        <span class="input-group-addon">.00</span>
                <span class="input-group-btn">
                    {!! Form::hidden('type', 'Food') !!}
                    {!! Form::hidden('loc', 'location') !!}
                    {!! Form::close() !!}
                    <button type="button" class="form-control btn btn-default" onclick="submitAPost('#foodPost')">Post</button>
                </span>
                    </div>

                </div>

                <div class="vicePost">
                    {!! Form::open(['id' => 'vicePost', 'url' => '/saveVicePost']) !!}
                    {!! Form::label('title', 'I want to...') !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Party my A** off']) !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'feeling to get...']) !!}
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        {!! Form::number('cost', null, ['class' => 'form-control', 'placeholder' => 'Will pay..', 'aria-label' => 'Amount to the nearest cent', 'step' => '0.01']) !!}
                        <span class="input-group-addon">.00</span>
                <span class="input-group-btn">
                    {!! Form::hidden('type', 'Vice') !!}
                    {!! Form::hidden('loc', 'location') !!}
                    <button type="button" class="form-control btn btn-default" onclick="submitAPost('#vicePost')">Post</button>
                </span>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="transportPost">
                    {!! Form::open(['id' => 'transportPost', 'url' => '/saveTransportPost']) !!}
                    {!! Form::label('title', 'Take me to...') !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Miami International airport']) !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'I need to go to...']) !!}
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        {!! Form::number('cost', null, ['class' => 'form-control', 'placeholder' => 'Will pay..', 'aria-label' => 'Amount to the nearest cent', 'step' => '0.01']) !!}
                        <span class="input-group-addon">.00</span>
                <span class="input-group-btn">
                    {!! Form::hidden('type', 'Transport') !!}
                    {!! Form::hidden('loc', 'location') !!}
                    <button type="button" class="form-control btn btn-default" onclick="submitAPost('#transportPost')">Post</button>
                </span>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>

            <!--Bid notification feed-->
            <div class="list-group" style="padding-top: 20px; padding-bottom: 20px;" id="bidAlertBox">

                <!--<a href="#" class="list-group-item">
                    <h4 class="list-group-item-heading">Big booty hoe</h4>
                    <p class="list-group-item-text">last bid:  $40.00</p>
                    <a href="#" onclick="accept("
                </a>-->
            </div>


        </div>
        <div class="col-lg-8">
            <div class="embed-responsive embed-responsive-16by9" id="mapper"></div>
        </div>
        <div class="col-lg-2">
            <div class="All-posts" id="postsContainer">
                @foreach($allPosts as $posts)
                    <div id="aPost" class="panel panel-default {!! $posts->type !!} ">
                        <div class="panel-heading">
                            @if(Auth::User()->id === $posts->user_id)
                                <h2 class="panel-title"><small>Your Job: </small><a href="#" onclick="watchBidTrack('#bidTrackingModal', '{{ $posts->jobpost_id }}'); return false;">{!! $posts->title !!}</a></h2>
                            @else
                            <h3 class="panel-title"><a href="#" onclick="showABiddingmodel('#biddingModal', '{{ $posts->jobpost_id }}'); return false;"> {!! $posts->title !!}</a></h3>
                        @endif
                        </div>
                        <div class="panel-body" style="color: #000000;">
                            <p>{!! $posts->content !!}</p>
                        </div>
                        <div class="panel-footer" style="color: #000;">
                            @if(Auth::User()->id === $posts->user_id)
                                <p style="font-size: 9pt; margin-bottom: auto;">Initial Payout: ${!! $posts->salary !!}</p>
                            @else
                            <p style="font-size: 9pt; margin-bottom: auto;">Initial Payout: ${!! $posts->salary !!}</p>
                            <a href="#" >Do It!</a> or <a href="#" onclick="showABiddingmodel('#biddingModal', '{{ $posts->jobpost_id }}'); return false;">Do it better!</a>
                        @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="modal fade" id="biddingModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color: #080808;">How much better....</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                        {!! Form::open(['id' => 'bidModal', 'url' => '/userlogin/bidpost']) !!}
                        {!! Form::label('bid', 'I bet: ', ['style' => 'color: #000000;']) !!}
                        {!! Form::number('bid', null, ['class' => 'form-control', 'step' => '0.01']) !!}
                        {!! Form::hidden('pid', 'something') !!}
                        {!! Form::textarea('comment', null, ['class' => 'form-control', 'placeholder' => 'let it be known']) !!}
                        {!! Form::close() !!}
                            <button type="button" class="btn btn-primary " onclick="submitABid('#bidModal')">Bet</button>
                        </div>
                        <div id="bidTrack">

                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <!--Job poster bid tracking modal-->
        <div class="modal fade" id="bidTrackingModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color: #080808;">"Goin once, Goin twice...."</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['id' => 'theflow', 'url' => '/userlogin/accept']) !!}
                        {!! Form::hidden('pid', 'something') !!}
                        {!! Form::hidden('bidid', 'somethingelse') !!}
                        {!! Form::close() !!}
                        <button type="button" class="btn btn-primary " onclick="accept('#theflow')">Oh my gosh Stop!!</button>
                        <div class="form-group" id="JobInfo">

                        </div>
                        <div id="bidTrack">

                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        <!-- chat modal-->
        <div class="modal fade" id="chatModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color: #080808;">I like your offer, So lets chat...</h4>
                    </div>
                    <div class="modal-body">
                        <!-- chat view -->
                        <div class="well-lg" id="log">

                        </div>
                        <div class="form-group">
                            {!! Form::open(['id' => 'chatModalForm', 'url' => '/userlogin/message']) !!}
                            {!! Form::textarea('message', null, ['class' => 'form-control', 'placeholder' => 'soo', 'rows' => '3', 'autofocus' => 'true', 'id' => 'message']) !!}
                            {!! Form::hidden('node', 'something', ['id' => 'node']) !!}
                            {!! Form::close() !!}
                            <button type="button" class="btn btn-primary " onclick="sendMessage('#chatModalForm')">Send</button>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
@stop

@section('scripts')
    <script src="{{url('/js/userInterface.js')}}" type="text/javascript"></script>
    <script async="true" defer="true"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCF3fFvAfs7uFcBDWAx7s9eYm2Bfvz1ur8&callback=initMap">
    </script>
@stop
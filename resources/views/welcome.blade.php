@extends('applayout')

@section('customcss')
    <link href="{{url('css/grayscale.css')}}" rel="stylesheet">
    @stop

@section('content')
<body>
    <nav class="nav navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="page-scroll" href="#SignIn">Sign In</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#Register">Join Us</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Reach Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<header id="SignIn" class="intro">
    <div class="intro-body">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h1 class="brand-heading">
                        Ploy
                    </h1>
                    <p class="intro-text">
                        The world is connected, life should be easier
                    </p>
                    <div class="signIn">
                    {!! Form::open(['url' => '/auth/login']) !!}
                        <div class="form-group">
                        {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                        </div>
                            {!! Form::submit('Sign In', ['class' => 'form-control btn btn-default']) !!}
                    {!! Form::close() !!}
                        </div>
                </div>
            </div>
        </div>
    </div>
</header>

<section id="Register" class="container content-section text-center">
    <div class="row">
        <div class="col-md-6 col-lg-offset-3">
            <h2>Join the Movement</h2>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="register">
                {!! Form::open(['url' => '/auth/register']) !!}
                <div class="form-group">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Whats your Name?']) !!}
                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'An Email address helps a lot']) !!}
                </div>
                <div class="form-group">
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter a Password']) !!}
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Match your password']) !!}
                </div>
                    {!! Form::submit('Join Us', ['class' => 'form-control btn btn-default']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

</body>
@stop

@section('scripts')
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{url('js/bootstrap.min.js')}}"></script>

    <!-- Plugin JavaScript -->
    <script src="{{url('js/jquery.easing.min.js')}}"></script>


    <!-- Custom Theme JavaScript -->
    <script src="{{url('js/grayscale.js')}}"></script>
@stop
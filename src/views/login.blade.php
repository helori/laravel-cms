@extends('laravel-cms::layout')
@section('content')
<div id="login">
    <div class="container">

        <div class="row">
            <div class="col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
                
                <h1>Connexion Administrateur</h1>

                <form class="form" role="form" method="POST" action="{{ route('admin-post-login') }}">
        
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" autofocus>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" name="password" placeholder="Mot de passe">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa fa-btn fa-sign-in"></i> Connexion
                        </button>
                        <a class="btn btn-block btn-default" href="{{ route('admin-password-forgot') }}">
                            <i class="fa fa-btn fa-question-mark"></i> Mot de passe perdu ?
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection

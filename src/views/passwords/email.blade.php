@extends('laravel-cms::layout')
@section('content')

<div id="login">
    <div class="container">
    
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6">
                
                <form class="form" role="form" method="post" action="{{ route('admin-password-email') }}">

                    <h1>Récupération du mot de passe</h1>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Envoyer le lien de ré-initialisation</button>
                </form>
                
            </div>
        </div>

    </div>
</div>

@endsection

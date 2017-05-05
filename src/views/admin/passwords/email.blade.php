@extends('laravel-cms::admin.layout')
@section('content')

    <div class="container">
        
        <div id="login">
            
            <form class="form" role="form" method="post" action="{{ route('admin-password-email') }}">

                <h1>Récupération du mot de passe</h1>
                <div class="separator"></div>

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

                <hr>
                <button type="submit" class="btn btn-primary btn-block">Envoyer le lien de ré-initialisation</button>
            </form>
            
        </div>

    </div>

@endsection

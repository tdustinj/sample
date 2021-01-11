@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <a href="{{ url('auth/google') }}" class="btn btn-lg btn-primary">
                        Login With Google
                    </a> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

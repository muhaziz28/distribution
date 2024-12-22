@extends('layouts.app')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Admin</b>LTE</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>

            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
                {{ session('error') }}
            </div>
            @endif
            <form action="{{ route('otp.getlogin') }}" method="post">
                @csrf
                <input type="hidden" name="user_id" value="{{$user_id}}">
                <div class="input-group mb-3">
                    <input type="text" name="otp" class="form-control @error('otp') is-invalid @enderror" placeholder="Kode OTP">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-number"></span>
                        </div>
                    </div>
                    @error('otp')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="row">


                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>
@endsection
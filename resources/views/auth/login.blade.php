@extends('auth.auth-layout')

@section('content')
<div class="container sm:px-10">
    <div class="block xl:grid grid-cols-2 gap-4">
        <!-- BEGIN: Login Info -->
        <div class="hidden xl:flex flex-col min-h-screen">
            <a href="" class="-intro-x flex items-center pt-5">
                <img alt="Midone Tailwind HTML Admin Template" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
                <span class="text-white text-lg ml-3"> Mid<span class="font-medium">One</span> </span>
            </a>
            <div class="my-auto">
                <img alt="Midone Tailwind HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="{{ asset('dist/images/illustration.svg') }}">
                <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                    A few more clicks to
                    <br>
                    sign in to your account.
                </div>
                <div class="-intro-x mt-5 text-lg text-white">Manage all your e-commerce accounts in one place</div>
            </div>
        </div>
        <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
            <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                    Sign In
                </h2>
                <div class="min-h-[64px]">
                    @if (session('success'))
                    <div class="rounded-md flex items-center px-5 py-4 bg-theme-9 text-white mt-4">
                        <i data-feather="check-circle" class="w-6 h-6 mr-2"></i>
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="rounded-md flex items-center px-5 py-4 bg-theme-6 text-white mt-4">
                        <i data-feather="check-circle" class="w-6 h-6 mr-2"></i>
                        {{ session('error') }}
                    </div>
                    @endif
                </div>
                <form action="{{ route('otp.generate') }}" method="POST">
                    @csrf
                    <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">
                        A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place
                    </div>
                    <div class="intro-x mt-8">
                        <input type="text"
                            class="intro-x login__input input input--lg border border-gray-300 block @error('email') border-theme-6 @enderror"
                            name="email"
                            placeholder="Email"
                            value="{{ old('email') }}">
                        @error('email')
                        <div class="text-theme-6 mt-2 text-sm">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                        <button type="submit" class="button button--lg w-full text-white bg-theme-1 xl:mr-3">Login</button>
                    </div>
                    <div class="intro-x mt-10 xl:mt-24 text-gray-700 text-center xl:text-left">
                        By signing up, you agree to our
                        <br>
                        <a class="text-theme-1" href="">Terms and Conditions</a> & <a class="text-theme-1" href="">Privacy Policy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
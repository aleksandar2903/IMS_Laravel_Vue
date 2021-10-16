@extends('layouts.app')

@section('content')
<div class="row" style="background-image:url({{asset('/storage/images/Wave.svg')}}); background-size:cover;">
    <div class="container h-100vh">
        <div class="py-4 d-flex justify-content-end">
            @if(count(config('app.languages')) > 1)
            <div class="nav-item dropdown d-md-down-none">
                <a class="nav-link border rounded" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    {{ strtoupper(app()->getLocale()) }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach(config('app.languages') as $langLocale => $langName)
                    <a class="dropdown-item fw-bold"
                        href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }}
                        ({{ $langName }})</a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <div class="row justify-content-center h-100">
            <div class="col-md-8 my-auto">
                <div class="card">
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                        @endif

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit"
                                class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

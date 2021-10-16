@extends('layouts.app')
@section('content')
    <div class="container-fluid mt--6">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-10 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{__('Register Purchase')}}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-primary">{{ __('Back to List')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('purchases.store') }}" autocomplete="off">
                            @csrf
                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title')}}</label>
                                    <input type="text" name="title" id="input-title" class="form-control form-control-alternative{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title')}}" value="{{ old('title') }}" required autofocus>
                                    {{-- @include('alerts.feedback', ['field' => 'title']) --}}
                                </div>

                                <div class="form-group{{ $errors->has('provider_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-provider">{{__('Supplier')}}</label>
                                    <select name="provider_id" id="input-provider" class="form-select form-control-alternative{{ $errors->has('provider') ? ' is-invalid' : '' }}">
                                        <option value="">{{__('Not Specified')}}</option>
                                        @foreach ($providers as $provider)
                                            @if($provider['id'] == old('provider_id'))
                                                <option value="{{$provider['id']}}" selected>{{$provider['name']}}</option>
                                            @else
                                                <option value="{{$provider['id']}}">{{$provider['name']}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    {{-- @include('alerts.feedback', ['field' => 'client_id']) --}}
                                </div>

                                <button type="submit" class="btn btn-primary mt-4">{{__('Continue')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){
        new SlimSelect({
            select: '.form-select'
        });
    });
    </script>
@endpush

@extends('layouts.app')

@section('content')
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8 col-lg-6 col-xl-6">
                            <h2 class="card-title m-0 p-0 font-weight-600">{{__('Supplier List')}}</h2>
                            <span class="m-0 p-0 h5 font-weight-300">{{__('Suppliers')}} | {{__('Supplier List')}}</span>
                        </div>
                        <div class="my-1 col-4 col-lg-6 col-xl-6 text-right">
                            <a href="{{ route('providers.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ __('Create')}}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    @include('alerts.error')
                    @include('alerts.deleteConfirmation')

                    <div class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm mt-2">
                        <table class="table datatable-basic" id="">
                            <thead class=" text-primary">
                                <th scope="col">{{ __('Name')}}</th>
                                <th scope="col">{{ __('Description')}}</th>
                                <th scope="col">{{__('Email')}}</th>
                                <th scope="col">{{__('Telephone')}}</th>
                                <th scope="col">{{__('Payments')}}</th>
                                <th scope="col">{{__('Total Amount')}}</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($providers as $provider)
                                    <tr>
                                        <td>{{ $provider->name }}</td>
                                        <td>{{ $provider->description }}</td>

                                        <td>
                                            <a href="mailto:{{ $provider->email }}">{{ $provider->email }}</a>
                                        </td>
                                        <td>{{ $provider->phone }}</td>
                                        <td>{{ $provider->transactions->count() }}</td>
                                        <td>{{ format_money(abs($provider->transactions->sum('amount'))) }}</td>
                                        <td class="">
                                            <div class="btn-group dropleft">
                                                <a
                                                    class="btn-link text-dark"
                                                    type="button"
                                                    id="triggerId"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    aria-expanded="false"
                                                >
                                                    <i class="fas fa-ellipsis-v    "></i>
                                                </a>
                                                <div
                                                    class="dropdown-menu"
                                                    aria-labelledby="triggerId"
                                                >
                                                <a
                                                        class="dropdown-item text-darker border-0" aria-pressed="true"
                                                        href="{{ route('providers.show', $provider) }}"
                                                    >
                                                        {{ __('Show')}}
                                                    </a>
                                                    <a
                                                        class="dropdown-item text-darker border-0" aria-pressed="true"
                                                        href="{{ route('providers.edit', $provider) }}"
                                                    >
                                                        {{ __('Edit')}}
                                                    </a>
                                                    <button
                                                        class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#deleteConfirmationModal"
                                                        data-action="{{ route('providers.destroy', $provider) }}"
                                                    >
                                                        {{ __('Delete')}}
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

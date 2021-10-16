@extends('layouts.app')

@section('content')
    @include('alerts.success')
    @include('alerts.error')
    @include('alerts.deleteConfirmation')
    <div class="container-fluid mt--6">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h2 class="card-title m-0 p-0 font-weight-600">{{__('Payment Method')}} List</h2>
                            <span class="m-0 p-0 h5 font-weight-300">Payment Methods | Bank Accounts</span>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ route('methods.create') }}" class="btn btn-primary"><i class="fas fa-plus-circle    "></i>  {{ __('Create')}}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm">
                        <table class="table datatable-basic" id="datatable-basic">
                            <thead class=" text-primary">
                                <th scope="col">#</th>
                                <th scope="col">{{__('Payment Method')}}</th>
                                <th scope="col">{{ __('Description')}}</th>
                                <th scope="col">Monthly Transactions</th>
                                <th scope="col">Monthly Balance</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($methods as $key => $method)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $method->name }}</td>
                                        <td>{{ $method->description }}</td>
                                        <td>{{ $method->transactions->count() }}</td>
                                        <td>{{ format_money($method->transactions->sum('amount')) }}</td>
                                        <td>
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
                                                        href="{{ route('methods.show', $method) }}"
                                                    >
                                                        {{ __('Show')}}
                                                    </a>
                                                    <a
                                                        class="dropdown-item text-darker border-0" aria-pressed="true"
                                                        href="{{ route('methods.edit', $method) }}"
                                                    >
                                                        {{ __('Edit')}}
                                                    </a>
                                                    <button
                                                        class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#deleteConfirmationModal"
                                                        data-action="{{ route('methods.destroy', $method) }}"
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
@endsection

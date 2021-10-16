@extends('layouts.app')

@section('content')
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h2 class="card-title m-0 p-0 font-weight-300">{{__('Client List')}}</h2>
                            <span class="m-0 p-0 h5 font-weight-300">{{__('Clients')}} | {{__('Client List')}}</span>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ route('clients.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ __('Create')}}</a>
                        </div>
                    </div>
                </div>
                    @include('alerts.success')
                    @include('alerts.error')
                    @include('alerts.deleteConfirmation')
                    <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm mt-2">
                        <table class="table datatable-basic" id="">
                            <thead class=" text-primary">
                                <th>{{ __('Name')}}</th>
                                <th>{{__('Email')}} / {{__('Telephone')}}</th>
                                <th>{{__('Purchases')}}</th>
                                <th>{{__('Total Amount')}}</th>
                                <th>{{__('Last Purchase')}}</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>{{ $client->name }}<br>{{ $client->document_type }}-{{ $client->document_id }}</td>
                                        <td>
                                            <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                                            <br>
                                            {{ $client->phone }}
                                        </td>
                                        <td>{{ $client->sales->count() }}</td>
                                        <td>{{ format_money($client->transactions->sum('amount')) }}</td>
                                        <td>{{ ($client->sales->sortByDesc('created_at')->first()) ? date('d-m-y', strtotime($client->sales->sortByDesc('created_at')->first()->created_at)) : 'N/A' }}</td>
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
                                                        href="{{ route('clients.show', $client) }}"
                                                    >
                                                        {{ __('Show')}}
                                                    </a>
                                                    <a
                                                        class="dropdown-item text-darker border-0" aria-pressed="true"
                                                        href="{{ route('clients.edit', $client) }}"
                                                    >
                                                    {{ __('Edit')}}
                                                    </a>
                                                    <button
                                                        class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#deleteConfirmationModal"
                                                        data-action="{{ route('clients.destroy', $client) }}"
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

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
                    <div class="col-8 col-lg-6 col-xl-6">
                        <h2 class="card-title m-0 p-0 font-weight-600">{{__('Sale List')}}</h2>
                        <span class="m-0 p-0 h5 font-weight-300">{{ __('Sales')}} | {{__('Sale List')}}</span>
                    </div>
                    <div class="my-1 col-4 col-lg-6 col-xl-6 text-right">
                        <a href="{{ route('sales.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"
                                aria-hidden="true"></i> {{ __('Register')}}</a>
                    </div>
                </div>
            </div>
            <div
                class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm">
                <table class="table datatable-basic" id="">
                    <thead>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Client')}}</th>
                        <th>{{__('User')}}</th>
                        <th>{{ __('Products')}}</th>
                        <th>{{ __('Stock')}}</th>
                        <th>{{ __('Total Amount')}}</th>
                        <th>{{ __('Paid')}}</th>
                        <th>{{ __('Due')}}</th>
                        <th>{{__('Payment Status')}}</th>
                        <th>Status</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                        <tr>
                            <td>{{ date('d-m-y', strtotime($sale->created_at)) }}</td>
                            <td><a href="{{ route('clients.show', $sale->client) }}">{{ $sale->client->name }}<br>{{
                                    $sale->client->document_type }}-{{ $sale->client->document_id }}</a></td>
                            <td>{{ ($sale->user ? $sale->user->name : '-') }}</td>
                            <td>{{ $sale->products->count() }}</td>
                            <td>{{ $sale->products->sum('qty') }}</td>
                            <td>{{ $sale->products->sum('total_amount') }}</td>
                            <td>{{ format_money($sale->paid ) }}</td>
                            <td>{{ format_money($sale->due) }}</td>
                            <td>{{ $sale->status }}</td>
                            <td>
                                @if (!$sale->finalized_at)
                                <span class="text-danger">{{__('To Finalize')}}</span>
                                @else
                                <span class="text-success">{{__('Finalized')}}</span>
                                @endif
                            </td>
                            <td class="">
                                <div class="btn-group dropleft">
                                    <a class="btn-link text-dark" type="button" id="triggerId" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v    "></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="triggerId">
                                        <a class="dropdown-item text-darker border-0" aria-pressed="true"
                                            href="{{ route('sales.show', $sale) }}">
                                            {{ __('Show')}}
                                        </a>
                                        <button class="dropdown-item text-danger" type="button" data-toggle="modal"
                                            data-target="#deleteConfirmationModal"
                                            data-action="{{ route('sales.destroy', $sale) }}">
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

@extends('layouts.app')

@section('content')
    <div class="container-fluid mt--6">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                <div class="row">
                    <div class="col-8 col-lg-6 col-xl-6">
                        <h2 class="card-title m-0 p-0 font-weight-600">{{__('Transaction List')}}</h2>
                        <span class="m-0 p-0 h5 font-weight-300">{{__('Transactions')}} | {{__('Transaction List')}}</span>
                    </div>
                    <div class="my-1 col-4 col-lg-6 col-xl-6 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#transactionModal"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ __('Create')}}</button>
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    @include('alerts.error')
                    @include('alerts.deleteConfirmation')
                    <div class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table-flush">
                        <table class="table datatable-basic">
                            <thead class=" text-primary">
                                <th>{{__('Date')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{ __('Title')}}</th>
                                <th>{{__('Payment Method')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Reference')}}</th>
                                <th>{{__('Client')}}</th>
                                <th>{{__('Supplier')}}</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ date('d-m-y', strtotime($transaction->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('transactions.type', ['type' => $transaction->type]) }}">{{ $transactionname[$transaction->type] }}</a>
                                        </td>
                                        <td style="max-width:150px">{{ $transaction->title }}</td>
                                        <td><a href="{{ route('methods.show', $transaction->method) }}">{{ $transaction->method->name }}</a></td>
                                        <td>{{ format_money($transaction->amount) }}</td>
                                        <td>{{ $transaction->reference }}</td>
                                        <td>
                                            @if ($transaction->client)
                                                <a href="{{ route('clients.show', $transaction->client) }}">{{ $transaction->client->name }}<br>{{ $transaction->client->document_type }}-{{ $transaction->client->document_id }}</a>
                                            @else
                                                {{__('Does not apply')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($transaction->provider)
                                                <a href="{{ route('providers.show', $transaction->provider) }}">{{ $transaction->provider->name }}</a>
                                            @else
                                            {{__('Does not apply')}}
                                            @endif
                                        </td>
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
                                                @if ($transaction->sale_id)
                                                <a href="{{ route('sales.show', $transaction->sale_id) }}" class="dropdown-item text-darker border-0">
                                                   {{ __('Show')}}
                                                </a>
                                                @elseif($transaction->receipt_id)
                                                <a href="{{ route('purchases.show', $transaction->receipt_id) }}" class="dropdown-item text-darker border-0">
                                                    {{ __('Show')}}
                                                 </a>
                                                @endif
                                                    <a
                                                        class="dropdown-item text-darker border-0" aria-pressed="true"
                                                        href="{{ route('transactions.edit', $transaction) }}"
                                                    >
                                                        {{ __('Edit')}}
                                                    </a>
                                                    <button
                                                        class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#deleteConfirmationModal"
                                                        data-action="{{ route('transactions.destroy', $transaction) }}"
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
    <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('Create Transaction')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transactions.create', ['type' => 'payment']) }}" class="btn btn-sm btn-primary">Payment</a>
                        <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="btn btn-sm btn-primary">Income</a>
                        <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="btn btn-sm btn-primary">Expense</a>
                        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">{{ __('Sale')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{__('Payment Method')}} information</h4>
                </div>
                <div class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table-flush">
                    <table class="table">
                        <thead>
                            <th>ID</th>
                            <th>{{ __('Name')}}</th>
                            <th>{{ __('Description')}}</th>
                            <th>{{__('Transactions')}}</th>
                            <th>Daily Balance</th>
                            <th>Weekly Balance</th>
                            <th>Quarterly Balance</th>
                            <th>Monthly Balance</th>
                            <th>Annual balance</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $method->id }}</td>
                                <td>{{ $method->name }}</td>
                                <td>{{ $method->description }}</td>
                                <td>{{ $method->transactions->count() }}</td>
                                <td>{{ format_money($balances['daily']) }}</td>
                                <td>{{ format_money($balances['weekly']) }}</td>
                                <td>{{ format_money($balances['quarter']) }}</td>
                                <td>{{ format_money($balances['monthly']) }}</td>
                                <td>{{ format_money($balances['annual']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{__('Transactions')}}: {{ $transactions->count() }}</h4>
                </div>
                <div class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table-flush">
                    <table class="table datatable-basic" id="">
                        <thead>
                            <th>ID</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Type')}}</th>
                            <th>{{ __('Title')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('Reference')}}</th>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ date('d-m-y', strtotime($transaction->created_at)) }}</td>
                                    <td><a href="{{ route('transactions.type', $transaction->type) }}">{{ $transactionname[$transaction->type] }}</a></td>
                                    <td>{{ $transaction->title }}</td>
                                    <td>{{ format_money($transaction->amount) }}</td>
                                    <td>{{ $transaction->reference }}</td>
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
@endsection

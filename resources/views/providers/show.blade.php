@extends('layouts.app')

@section('content')
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{__('Supplier Information')}}</h4>
                </div>
                <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm">
                    <table class="table" id="">
                        <thead>
                            <th>ID</th>
                            <th>{{ __('Name')}}</th>
                            <th>{{ __('Description')}}</th>
                            <th>{{__('Email')}}</th>
                            <th>{{__('Telephone')}}</th>
                            <th>{{__('Payment Information')}}</th>
                            <th>{{__('Payments')}}</th>
                            <th>{{__('Total Amount')}}</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $provider->id }}</td>
                                <td>{{ $provider->name }}</td>
                                <td>{{ $provider->description }}</td>
                                <td>{{ $provider->email }}</td>
                                <td>{{ $provider->phone }}</td>
                                <td style="max-width: 175px">{{ $provider->paymentinfo }}</td>
                                <td>{{ $provider->transactions->count() }}</td>
                                <td>{{ format_money(abs($provider->transactions->sum('amount'))) }}</td>
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
                    <h4 class="card-title">{{__('Latest Transactions')}}</h4>
                </div>
                <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm">
                    <table class="table datatable-basic" id="">
                        <thead>
                            <th>{{__('Date')}}</th>
                            <th>ID</th>
                            <th>{{ __('Title')}}</th>
                            <th>{{__('Payment Method')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('Reference')}}</th>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ date('d-m-y', strtotime($transaction->created_at)) }}</td>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->title }}</td>
                                    <td><a href="{{ route('methods.show', $transaction->method) }}">{{ $transaction->method->name }}</a></td>
                                    <td>{{ format_money($transaction->amount) }}</td>
                                    <td>{{ $transaction->reference }}</td>
                                </tr>
                            @endforeach
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
                    <h4 class="card-title">{{ __('Latest Purchases')}}</h4>
                </div>
                <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm">
                    <table class="table datatable-basic" id="">
                        <thead>
                            <th>{{__('Date')}}</th>
                            <th>ID</th>
                            <th>{{ __('Title')}}</th>
                            <th>{{__('Products')}}</th>
                            <th>{{ __('Stock')}}</th>
                            <th>{{ __('Defective Stock')}}</th>
                            <th>{{ __('Stock')}}</th>
                            <th>{{ __('Total Amount')}}</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($receipts as $receipt)
                                <tr>
                                    <td>{{ date('d-m-y', strtotime($receipt->created_at)) }}</td>
                                    <td><a href="{{ route('purchases.show', $receipt) }}">{{ $receipt->id }}</a></td>
                                    <td>{{ $receipt->title }}</td>
                                    <td>{{ $receipt->products->count() }}</td>
                                    <td>{{ $receipt->products->sum('stock') }}</td>
                                    <td>{{ $receipt->products->sum('stock_defective') }}</td>
                                    <td>{{ $receipt->products->sum('stock') + $receipt->products->sum('stock_defective') }}</td>
                                    <td>{{ format_money($receipt->products->sum('total_amount')) }}</td>
                                    <td class="">
                                        <a href="{{ route('purchases.show', $receipt) }}" class="btn btn-link m-0 p-0" data-toggle="tooltip" data-placement="bottom" title="{{__('Show')}}">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </a>
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

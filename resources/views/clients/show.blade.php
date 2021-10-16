@extends('layouts.app')

@section('content')
    @include('alerts.error')
    <div class="container-fluid mt--6">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{__('Client Information')}}</h4>
                </div>
                <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm ">
                    <table class="table" id="">
                        <thead>
                            <th>ID</th>
                            <th>{{ __('Name')}}</th>
                            <th>{{ __('Document')}}</th>
                            <th>{{__('Telephone')}}</th>
                            <th>{{ __('Email')}}</th>
                            <th>{{__('Purchases')}}</th>
                            <th>{{__('Total Amount')}}</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->document_type }}-{{ $client->document_id }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->sales->count() }}</td>
                                <td>{{ format_money($client->transactions->sum('amount')) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">{{__('Latest Transactions')}}</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('clients.transactions.add', $client) }}" class="btn btn-sm btn-primary">{{__('New Transaction')}}</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm ">
                    <table class="table datatable-basic" id="">
                        <thead>
                            <th>ID</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Payment Method')}}</th>
                            <th>{{__('Amount')}}</th>
                        </thead>
                        <tbody>
                            @foreach ($client->transactions->reverse()->take(25) as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ date('d-m-y', strtotime($transaction->created_at)) }}</td>
                                    <td><a href="{{ route('methods.show', $transaction->method) }}">{{ $transaction->method->name }}</a></td>
                                    <td>{{ format_money($transaction->amount) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">{{ __('Latest Purchases')}}</h4>
                        </div>
                        <div class="col-4 text-right">
                            <form method="post" action="{{ route('sales.store') }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                <button type="submit" class="btn btn-sm btn-primary">{{__('New Purchase')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm ">
                    <table class="table datatable-basic" id="">
                        <thead>
                            <th>ID</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Products')}}</th>
                            <th>{{ __('Stock')}}</th>
                            <th>{{ __('Total Amount')}}</th>
                            <th>Status</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($client->sales->reverse()->take(25) as $sale)
                                <tr>
                                    <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->id }}</a></td>
                                    <td>{{ date('d-m-y', strtotime($sale->created_at)) }}</td>
                                    <td>{{ $sale->products->count() }}</td>
                                    <td>{{ $sale->products->sum('qty') }}</td>
                                    <td>{{ format_money($sale->products->sum('total_amount')) }}</td>
                                    <td>{{ ($sale->finalized_at) ? __('FINISHED') : __('ON HOLD') }}</td>
                                    <td class="td-actions text-right">
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-link m-0 p-0" data-toggle="tooltip" data-placement="bottom" title="{{__('Show')}}">
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

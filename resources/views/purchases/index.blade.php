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
                        <h2 style="" class="card-title m-0 p-0 font-weight-600">{{__('Purchase List')}}</h2>
                        <span class="m-0 p-0 h5 font-weight-300">{{__('Purchases')}} | {{__('Purchase List')}}</span>
                    </div>
                    <div class="my-1 col-4 col-lg-6 col-xl-6 text-right">
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary p-2 px-lg-3"><i class="fa fa-plus-circle" aria-hidden="true"></i> {{ __('Register')}}</a>
                    </div>
                </div>
            </div>
                <div class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm mt-2">
                    <table class="table datatable-basic" id="">
                        <thead>
                            <th>{{__('Date')}}</th>
                            <th>{{ __('Title')}}</th>
                            <th>{{__('Supplier')}}</th>
                            <th>{{__('Products')}}</th>
                            <th>{{ __('Stock')}} ({{ __('Unit')}})</th>
                            <th>{{ __('Defective Stock')}} ({{ __('Unit')}})</th>
                            <th>{{ __('Total Amount')}}</th>
                            <th>{{ __('Paid')}}</th>
                            <th>{{ __('Due')}}</th>
                            <th>{{__('Pyment Status')}}</th>
                            <th>Status</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)
                                <tr>
                                    <td>{{ date('d-m-y', strtotime($purchase->created_at)) }}</td>
                                    <td style="max-width:150px">{{ $purchase->title }}</td>
                                    <td>
                                        @if($purchase->provider_id)
                                            <a href="{{ route('providers.show', $purchase->provider) }}">{{ $purchase->provider->name }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $purchase->products->count() }}</td>
                                    <td>{{ $purchase->products->sum('stock') }}</td>
                                    <td>{{ $purchase->products->sum('stock_defective') }}</td>
                                    <td>{{ format_money($purchase->products->sum('total_amount'))}}</td>
                                    <td>{{ format_money($purchase->paid) }}</td>
                                    <td>{{ format_money($purchase->due) }}</td>
                                    <td>{{ $purchase->status }}</td>
                                    <td>
                                        @if (!$purchase->finalized_at)
                                        <span class="text-danger">{{__('To Finalize')}}</span>
                                        @else
                                        <span class="text-success">{{__('Finalized')}}</span>
                                        @endif
                                    </td>
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
                                                    href="{{ route('purchases.show', $purchase) }}"
                                                >
                                                    {{ __('Show')}}
                                                </a>
                                                <button
                                                    class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#deleteConfirmationModal"
                                                    data-action="{{ route('purchases.destroy', $purchase) }}"
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
@endsection

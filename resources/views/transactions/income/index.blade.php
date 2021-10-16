@extends('layouts.app')

@section('content')
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">{{__('Revenues')}}</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="btn btn-sm btn-primary">{{__('Create')}}</a>
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
                                <th scope="col">{{__('Date')}}</th>
                                <th scope="col">{{ __('Title')}}</th>
                                <th scope="col">{{__('Payment Method')}}</th>
                                <th scope="col">{{__('Amount')}}</th>
                                <th scope="col">{{__('Reference')}}</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td> {{ date('d-m-y', strtotime($transaction->created_at)) }}</td>
                                        <td> {{ $transaction->title }}</td>
                                        <td><a href="{{ route('methods.show', $transaction->method) }}">{{ $transaction->method->name }}</a></td>
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
</div>
@endsection

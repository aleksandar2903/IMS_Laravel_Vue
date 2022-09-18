@extends('layouts.app')
@section('content')
@include('alerts.success')
@include('alerts.error')
@include('alerts.paymentsSaleModals')
<div class="container-fluid mt--6">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">{{__('Sale Summary')}}</h4>
                    </div>
                    @if (!$sale->finalized_at)
                    <div class="col-4 text-right">
                        @if ($sale->products->count() == 0)
                        <form action="{{ route('sales.destroy', $sale) }}" method="post" class="d-inline">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-primary">
                                {{__('Delete Sale')}}
                            </button>
                        </form>
                        @else
                        <a class="btn btn-sm btn-primary" href="{{route('sales.finalize', $sale)}}">
                            {{__('Finalize Sale')}}
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            <div
                class="table-responsive table-responsive-xl w-100 display compact table-responsive-lg table-responsive-md table-responsive-sm">
                <table class="table" id="sale-table">
                    <thead>
                        <th>ID</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('User')}}</th>
                        <th>{{__('Client')}}</th>
                        <th>{{__('Products')}}</th>
                        <th>{{ __('Stock')}}</th>
                        <th>{{__('Total Amount')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Payments')}}</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->created_at->format('d-M-Y') }}</td>
                            <td>{{ ($sale->user ? $sale->user->name : '-') }}</td>
                            <td><a href="{{ route('clients.show', $sale->client) }}">{{ $sale->client->name }}<br>{{
                                    $sale->client->document_type }}-{{ $sale->client->document_id }}</a>
                            </td>
                            <td>{{ $sale->products->count() }}</td>
                            <td>{{ $sale->products->sum('qty') }}</td>
                            <td>{{ format_money($sale->products->sum('total_amount')) }}</td>
                            <td>{!! $sale->finalized_at ? 'Completed at<br>'.date('d-m-y',
                                strtotime($sale->finalized_at)) : (($sale->products->count() > 0) ? 'TO FINALIZE' : 'ON
                                HOLD') !!}</td>

                            <td>
                                @if($sale->finalized_at == null)
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#createPaymentModal">
                                    <i class="fas fa-plus    "></i>
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#showPaymentsModal">
                                    <i class="fas fa-search    "></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="card-title">{{__('Products')}}: {{ $sale->products->count() }}</h4>
                    </div>
                    @if (!$sale->finalized_at)
                    <div class="col-6 text-right">
                        <button class="btn btn-sm  btn-primary" type="button" data-toggle="modal"
                            data-target="#modalProduct">{{__('Add')}}</button>
                    </div>
                    @endif
                </div>
            </div>
            <div
                class="table-responsive table-responsive-xl w-100 display compact table-responsive-lg table-responsive-md table-responsive-sm">
                <table class="table datatable-basic">
                    <thead>
                        <th>ID</th>
                        <th>{{ __('Category')}}</th>
                        <th>{{ __('Product')}}</th>
                        <th>{{ __('Quantity')}}</th>
                        <th>{{ __('Price')}}</th>
                        <th>{{__('Total Amount')}}</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($sale->products as $sold_product)
                        <tr>
                            <td>{{ $sold_product->product->id }}</td>
                            <td><a href="{{ route('categories.show', $sold_product->product->category) }}">{{
                                    $sold_product->product->category->name }}</a>
                            </td>
                            <td><a href="{{ route('products.show', $sold_product->product) }}">{{
                                    $sold_product->product->name }}</a>
                            </td>
                            <td>
                                {{ $sold_product->qty }}
                            </td>
                            <td>{{ format_money($sold_product->price) }}</td>
                            <td>{{ format_money($sold_product->total_amount) }}</td>
                            <td class="td-actions text-right">
                                @if(!$sale->finalized_at)
                                <form
                                    action="{{ route('sales.product.destroy', ['sale' => $sale, 'soldproduct' => $sold_product]) }}"
                                    method="post" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" title="{{__('Delete Product')}}"
                                        class="btn border-0 p-0 text-primary mr-4">
                                        <i class="fas fa-trash    "></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalProduct" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <h3 class="mb-0">{{__('Add Product')}}</h3>
                            </div>
                            <div class="col-8 text-right">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fas fa-times    "></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('sales.product.store', $sale) }}" autocomplete="off">
                            @csrf
                            <div class="p-2">
                                <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-product">{{ __('Product')}}</label>
                                    <select name="product_id" id="input-product"
                                        class="form-select form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}"
                                        required>
                                        @foreach ($products as $product)
                                        @if($product['id'] == old('product_id'))
                                        <option price="{{$product->price}}" value="{{$product['id']}}" selected>
                                            [{{ $product->category->name }}] {{ $product->name }} - {{__('Price')}}:
                                            {{ $product->price }} - {{__('On Stock')}}: {{$product->stock}}</option>
                                        @else
                                        <option price="{{$product->price}}" value="{{$product['id']}}"
                                            name="{{$product->price}}">[{{ $product->category->name }}]
                                            {{ $product->name }} - {{__('Price')}}: {{ $product->price }} - {{__('On
                                            Stock')}}:
                                            {{$product->stock}} {{__('Unit')}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-price">{{ __('Price')}} ({{
                                        __('Unit')}})</label>
                                    <input type="number" name="price" id="input-price" step=".01"
                                        class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}"
                                        value="1" required>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-qty">{{ __('Quantity')}}</label>
                                    <input type="number" name="qty" id="input-qty"
                                        class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}"
                                        min="1" value="1" required>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-total">{{ __('Total Amount')}}</label>
                                    <input type="text" name="total_amount" id="input-total"
                                        class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}"
                                        min="1" value="1$" disabled>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Submit')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function (){
        new SlimSelect({
            select: '#input-method'
        });
        new SlimSelect({
            select: '#input-product'
        });
        let input_qty = document.getElementById('input-qty');
        let input_price = document.getElementById('input-price');
        let input_total = document.getElementById('input-total');
        let input_product = document.getElementById('input-product');
        input_qty.addEventListener('input', updateTotal);
        input_price.addEventListener('input', updateTotal);
        input_product.addEventListener('change', updatePrice);
        input_price.value = input_product.options[input_product.selectedIndex].getAttribute('price');
        input_total.value = (parseInt(input_qty.value) * parseFloat(input_price.value))+"$";
        function updateTotal () {
            input_total.value = (parseInt(input_qty.value) * parseFloat(input_price.value))+"$";
        }
        function updatePrice (){
            input_price.value = input_product.options[input_product.selectedIndex].getAttribute('price');
        }
    });
</script>
@endpush

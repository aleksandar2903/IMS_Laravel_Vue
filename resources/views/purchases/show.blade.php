@extends('layouts.app')
@section('content')
    @include('alerts.success')
    @include('alerts.error')
    @include('alerts.paymentsPurchaseModals')
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
                        <form method="post" action="{{ route('purchases.product.store', $purchase) }}"
                            autocomplete="off">
                            @csrf
                            <div class="p-2">
                                <input type="hidden" name="receipt_id" value="{{ $purchase->id }}">
                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-product">Product</label>
                                    <select name="product_id" id="product_purchase"
                                        class="form-select form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}"
                                        required>
                                        @foreach ($products as $product)
                                        @if($product['id'] == old('product_id'))
                                        <option price="{{$product->price}}" value="{{$product['id']}}" selected>
                                            [{{ $product->category->name }}] {{ $product->name }} - {{__('Price')}}:
                                            {{ $product->price }} - {{__('Stock')}}: {{$product->stock}}</option>
                                        @else
                                        <option price="{{$product->price}}" value="{{$product['id']}}"
                                            name="{{$product->price}}">[{{ $product->category->name }}]
                                            {{ $product->name }} - {{__('Price')}}: {{ $product->price }} - {{__('Stock')}}:
                                            {{$product->stock}} {{__('Unit')}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-price">{{__('Price')}} ({{ __('Unit')}})</label>
                                    <input type="number" name="price" id="input-price" step=".01"
                                        class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}"
                                        value="1" required>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-qty">{{__('Stock')}}</label>
                                    <input type="number" name="stock" id="input-qty"
                                        class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}"
                                        min="1" value="1" required>
                                    @include('alerts.feedback', ['field' => 'product_id'])
                                </div>

                                <div class="form-group{{ $errors->has('product_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-qty">{{ __('Defective Stock')}}</label>
                                    <input type="number" name="stock_defective" id="input-qty-def"
                                        class="form-control form-control-alternative{{ $errors->has('product_id') ? ' is-invalid' : '' }}"
                                        min="0" value="0" required>
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
<div class="container-fluid mt--6">
    <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">{{__('Purchase Summary')}}</h4>
                        </div>
                        @if (!$purchase->finalized_at)
                            <div class="col-4 text-right">
                                @if ($purchase->products->count() === 0)
                                    <form action="{{ route('purchases.destroy', $purchase) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            {{ __('Delete Purchase')}}
                                        </button>
                                    </form>
                                @else
                                <form action="{{ route('purchases.finalize', $purchase) }}" method="get">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        {{__('Finalize Purchase')}}
                                    </button>
                                </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="table-responsive table-responsive-xl w-100 table-responsive-lg table-responsive-md table-responsive-sm">
                    <table
                        class="table">
                        <thead>
                            <th>ID</th>
                            <th>{{__('Date')}}</th>
                            <th>{{ __('Title')}}</th>
                            <th>{{__('User')}}</th>
                            <th>{{__('Supplier')}}</th>
                            <th>{{ __('Products')}}</th>
                            <th>{{ __('Stock')}}</th>
                            <th>{{ __('Defective Stock')}}</th>
                            <th>{{ __('Total Amount')}}</th>
                            <th>Status</th>
                            <th>{{__('Payments')}}</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $purchase->id }}</td>
                                <td>{{ date('d-m-y', strtotime($purchase->created_at)) }}</td>
                                <td style="max-width:150px;">{{ $purchase->title }}</td>
                                <td>{{ $purchase->user->name }}</td>
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
                                <td>{{ format_money($purchase->products->sum('total_amount')) }}</td>
                                <td>{!! $purchase->finalized_at ? 'Finalized' : '<span style="color:red; font-weight:bold;">TO FINALIZE</span>' !!}</td>

                                <td>
                                    @if($purchase->finalized_at == null)
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
                            <h4 class="card-title">{{__('Products')}}: {{ $purchase->products->count() }}</h4>
                        </div>
                        @if (!$purchase->finalized_at)
                        <div class="col-6 text-right">
                            <button class="btn btn-sm  btn-primary" type="button" data-toggle="modal"
                                data-target="#modalProduct">{{__('Add')}}</button>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="table-responsive table-responsive-xl w-100 table-responsive-lg table-responsive-md table-responsive-sm">
                    <table
                        class="table datatable-basic" id="">
                        <thead>
                            <th>{{ __('Category')}}</th>
                            <th>{{ __('Product')}}</th>
                            <th>{{__('Stock')}}</th>
                            <th>{{ __('Defective Stock')}}</th>
                            <th>{{ __('Stock')}}</th>
                            <th>{{ __('Price')}}</th>
                            <th>{{ __('Total Amount')}}</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($purchase->products as $received_product)
                                <tr>
                                    <td><a href="{{ route('categories.show', $received_product->product->category) }}">{{ $received_product->product->category->name }}</a></td>
                                    <td><a href="{{ route('products.show', $received_product->product) }}">{{ $received_product->product->name }}</a></td>
                                    <td>{{ $received_product->stock }}</td>
                                    <td>{{ $received_product->stock_defective }}</td>
                                    <td>{{ $received_product->stock + $received_product->stock_defective }}</td>
                                    <td>{{ format_money($received_product->price) }}</td>
                                    <td>{{ format_money($received_product->stock * $received_product->price) }}</td>
                                    <td class="td-actions text-right">
                                        @if(!$purchase->finalized_at)
                                            <form action="{{ route('purchases.product.destroy', ['purchase' => $purchase, 'receivedproduct' => $received_product]) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-link" title="{{__('Delete Product')}}">
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
    </div>
@endsection
@push('js')
<script>
    $(document).ready(function (){
        new SlimSelect({
            select: '#product_purchase'
        });
        let input_price = document.getElementById('input-price');
        let input_qty = document.getElementById('input-qty');
        let input_total = document.getElementById('input-total');
        let input_product = document.getElementById('product_purchase');
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

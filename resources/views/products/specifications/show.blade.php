@extends('layouts.app')
@section('content')
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h2 class="card-title m-0 p-0 font-weight-600">{{$specification->name}}</h2>
                    <span class="m-0 p-0 h5 font-weight-300">{{ __('Products')}} | {{__('Product List')}}</span>
                </div>
                <div class="col-6 text-right">
                    <a href="/products/specifications" class="btn btn-sm btn-primary">{{__('Back to List')}}</a>
                </div>
            </div>
        </div>
        <div
            class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm">
            <table class="table datatable-basic" id="datatable-basic">
                <thead class="table-flush text-primary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{__('Specification')}}</th>
                        <th scope="col">{{__('Product')}}</th>
                        <th scope="col">{{__('Price')}}</th>
                        <th scope="col">{{ __('Stock')}}</th>
                        <th scope="col">{{__('Faulty')}}</th>
                        <th scope="col">{{__('Total Sold')}}</th>
                        <th scope="col">{{__('Created at')}}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{$product->id}}</a></td>
                        <td>{{$product->attributes[0]->value}}</a></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->stock_defective }}</td>
                        <td>{{ $product->solds->sum('qty') }}</td>
                        <td>{{ $product->updated_at}}</td>
                        <td>
                            <div class="btn-group dropleft">
                                <a class="btn-link text-dark" type="button" id="triggerId" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v    "></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="triggerId">
                                    <a class="dropdown-item text-darker border-0" aria-pressed="true"
                                        href="{{ route('products.show', $product) }}">
                                        {{ __('Show')}}
                                    </a>
                                    <a class="dropdown-item text-darker border-0" aria-pressed="true"
                                        href="{{ route('products.edit', $product) }}">
                                        {{ __('Edit')}}
                                    </a>
                                    <button class="dropdown-item text-danger" type="button" data-toggle="modal"
                                        data-target="#deleteConfirmationModal"
                                        data-action="{{ route('products.destroy', $product) }}">
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
@endsection

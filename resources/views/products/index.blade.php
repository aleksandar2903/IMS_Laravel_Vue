@extends('layouts.app')
@section('content')
@include('alerts.error')
@include('alerts.success')
<div class="container-fluid mt--6">
    @include('alerts.deleteConfirmation')
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h2 class="card-title m-0 p-0 font-weight-600">{{__('Product List')}}</h2>
                        <span class="m-0 p-0 h5 font-weight-300">{{__('Products')}} | {{__('Product List')}}</span>
                    </div>
                    <div class="col-6 text-right">
                        <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"
                                aria-hidden="true"></i> {{ __('Create')}}</a>
                    </div>
                </div>
            </div>
            <div
                class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table-flush">
                <table class="table datatable-basic">
                    <thead class="table-flush text-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Category')}}</th>
                            <th scope="col">{{ __('Product')}}</th>
                            <th scope="col">{{ __('Base Price')}}</th>
                            <th scope="col">{{ __('Stock')}}</th>
                            <th scope="col">{{ __('Faulty')}}</th>
                            <th scope="col">{{ __('Total Sold')}}</th>
                            <th scope="col">{{ __('Created at')}}</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                        <tr>
                            <td>{{++$key}}</td>
                            <td><a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name
                                    }}</a>
                            </td>
                            <td>
                                @if($product->brand)
                                <a href="{{ route('brands.show', $product->brand) }}">{{ $product->brand->name }}</a>
                                @else
                                NULL
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->stock_defective }}</td>
                            <td>{{ $product->solds->sum('qty') }}</td>
                            <td>{{ $product->updated_at->format('d-M-Y')}}</td>
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
</div>
@endsection

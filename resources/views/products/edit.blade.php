@extends('layouts.app')
@section('content')
<div class="container mt--6">
    <div class="col-xl-12 order-xl-1 mt--6">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h2 class="mb-0 font-weight-600">{{ __('Edit Product')}}</h2>
                    </div>
                    <div class="col-4 text-right">
                        <a href="/products" class="btn btn-sm btn-primary">{{ __('Back to List')}}</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" action="{{ route('products.update', $product) }}"
                    autocomplete="off">
                    @method('patch')
                    @csrf
                    <h6 class="heading-small text-muted mb-4">{{ __('Product Information')}}</h6>
                    <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-name">{{ __('Name')}}</label>
                            <input type="text" name="name" id="input-name"
                                class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Name')}}" value="{{ old('name', $product->name) }}" required
                                autofocus>
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>
                        <div class="form-group{{ $errors->has('product_category_id') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-name">{{ __('Category')}}</label>
                            <select name="product_category_id" id="input-category"
                                class="form-select {{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                                @foreach ($categories as $category)
                                @if($category['id'] == old('product_category_id', $product->product_category_id))
                                <option value="{{$category['id']}}" selected>{{$category['name']}}</option>
                                @else
                                <option value="{{$category['id']}}">{{$category['name']}}</option>
                                @endif
                                @endforeach
                            </select>
                            @include('alerts.feedback', ['field' => 'product_category_id'])
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-description">{{ __('Description')}}</label>
                            <textarea style="resize: none;" type="text" name="description" id="input-description"
                                class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Description')}}"
                                rows="4">{{old('description', $product->description)}}</textarea>
                            @include('alerts.feedback', ['field' => 'description'])
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-4 col-md-4 col-sm-4 col-xl-4">
                                <div class="form-group{{ $errors->has('stock') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-stock">{{ __('Stock')}}</label>
                                    <input type="number" name="stock" id="input-stock"
                                        class="form-control {{ $errors->has('stock') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('Stock')}}" value="{{ old('stock', $product->stock) }}"
                                        required>
                                    @include('alerts.feedback', ['field' => 'stock'])
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 col-md-4 col-sm-4 col-xl-4">
                                <div class="form-group{{ $errors->has('stock_defective') ? ' has-danger' : '' }}">
                                    <label class="form-control-label"
                                        for="input-stock_defective">{{ __('Defective Stock')}}</label>
                                    <input type="number" name="stock_defective" id="input-stock_defective"
                                        class="form-control {{ $errors->has('stock_defective') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('Defective Stock')}}"
                                        value="{{ old('stock_defective', $product->stock_defective) }}" required>
                                    @include('alerts.feedback', ['field' => 'stock_defective'])
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 col-md-4 col-sm-4 col-xl-4">
                                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-price">{{ __('Price')}}</label>
                                    <input type="number" step=".01" name="price" id="input-price"
                                        class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('Price')}}" value="{{ old('price', $product->price) }}"
                                        required>
                                    @include('alerts.feedback', ['field' => 'price'])
                                </div>
                            </div>
                        </div>
                        <div class="pl-0 col-lg-4 col-md-8 col-sm-12 col-xl-4">
                            <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="image">{{ __('Image')}}</label>
                                <input type="file" name="image" id="image"
                                    class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}">
                                @include('alerts.feedback', ['field' => 'image'])
                            </div>
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
@endsection
@push('js')
<script>
    $(document).ready(function(){
        new SlimSelect({
        select: '.form-select'
    });
    })
</script>
@endpush

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
                        <div class="form-group{{ $errors->has('product_subcategory_id') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-subcategory">{{ __('Subategory')}}</label>
                            <select name="product_subcategory_id" id="input-subcategory"
                                class="form-select {{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                                @foreach ($categories as $category)
                                @if($category['id'] == old('product_subcategory_id'))
                                <option value="{{$category['id']}}" selected>
                                    {{$category->category->name}} &#8594; {{$category['name']}}</option>
                                @else
                                <option value="{{$category['id']}}">{{$category->category->name}} &#8594;
                                    {{$category['name']}}</option>
                                @endif
                                @endforeach
                            </select>
                            @include('alerts.feedback', ['field' => 'product_subcategory_id'])
                        </div>

                        <div class="form-group{{ $errors->has('product_brand_id') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-brand">{{ __('Brand')}}</label>
                            <select name="product_brand_id" id="input-brand"
                                class="form-select-brands {{ $errors->has('name') ? ' is-invalid' : '' }}">
                                @foreach ($brands as $brand)
                                @if($brand['id'] == old('product_brand_id'))
                                <option value="{{$brand['id']}}" selected>{{$brand['name']}}</option>
                                @else
                                <option value="{{$brand['id']}}">{{$brand['name']}}</option>
                                @endif
                                @endforeach
                            </select>
                            @include('alerts.feedback', ['field' => 'product_brand_id'])
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
                                    <label class="form-control-label" for="input-stock_defective">{{ __('Defective
                                        Stock')}}</label>
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
                        {{-- <div class="pl-0 col-lg-4 col-md-8 col-sm-12 col-xl-4">
                            <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="image">{{ __('Image')}}</label>
                                <input type="file" name="image" id="image"
                                    class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}">
                                @include('alerts.feedback', ['field' => 'image'])
                            </div>
                        </div> --}}

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-4">{{ __('Submit')}}</button>
                        </div>
                    </div>
                </form>
                <label class="mt-5 mb-2 form-control-label">{{ __('Specification Attributes')}}</label>
                <div class="form-group mt-2">
                    @foreach ($product->attributes as $attribute)
                    <form method="POST"
                        action="{{ route('products.product.removeSpecificationAttribute', $attribute) }}" class="row">
                        @csrf
                        @method('DELETE')
                        <div class="col-6">
                            <p class="font-weight-bold m-auto">{{ $attribute->attribute->name }}</p>
                        </div>
                        <div class="col-6 d-flex">
                            <p class="font-weight-normal m-auto">{{ $attribute->value }}</p>
                            <button class="btn text-danger" type="submit"><i
                                    class="m-auto fa fa-times    "></i></button>
                        </div>
                    </form>
                    @endforeach
                    @if($product->attributes->count() == 0)
                    <span class="mx-auto">{{ __(
                        'No attributes added yet.')}}</span>
                    @endif
                </div>
                @if ($attributes != null && $attributes->count() > 0)
                <form method="POST" action="{{ route('products.product.addSpecificationAttribute', $product) }}"
                    class="mt-5 row{{ $errors->has('attribute_id') ? ' has-danger' : '' }}">
                    @csrf
                    <div class="col-12 col-lg-6 col-md-6 col-sm-6 col-xl-6">
                        <select name="attribute_id" id="input-attribute"
                            class="form-select-attributes {{ $errors->has('name') ? ' is-invalid' : '' }}">
                            @foreach ($attributes as $attribute)
                            <option value="{{$attribute['id']}}">{{$attribute['name']}}</option>
                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'attribute_id'])
                    </div>
                    <div class="col-12 col-lg-6 col-md-6 col-sm-6 col-xl-6">
                        <input type="text" name="value" id="input-value"
                            class="form-control {{ $errors->has('value') ? ' is-invalid' : '' }}"
                            placeholder="{{ __('Value')}}" value="{{ old('value') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 mx-auto">{{ __('Add attribute')}}</button>
                </form>
                @endif
                <label class="mt-5 mb-2 form-control-label">{{ __('Product Images')}}</label>
                <div class="row">
                    @if ($product->images->count() == 0)
                    <span class="mx-auto">{{ __(
                        'No images uploaded yet.')}}</span>
                    @endif
                    @foreach ($product->images as $image)
                    <div class="col-3">
                        <img src="{{asset('storage/images/'.$image->w500)}}" alt="{{$image->w500}}"
                            class="img-thumbnail m-2 h-50">
                        <div class="d-flex justify-content-center">
                            <form method="post" action="{{ route('products.product.selectImage', $product) }}">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="image_id" value="{{$image->id}}">
                                <button class="btn {{ $product->image_id == $image->id ? 'text-success' : '' }}"
                                    type="submit"><i class="fa fa-check    "></i></button>
                            </form>
                            <form method="post" action="{{ route('images.destroy', $image) }}">
                                @csrf
                                @method('delete')
                                <button class="btn text-danger" type="submit"><i class="fa fa-times    "></i></button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                <form class="mt-5" method="post" enctype="multipart/form-data" action="{{ route('images.store') }}"
                    autocomplete="off">
                    @csrf
                    <input type="hidden" name="product_id" value="{{$product->id}}">
                    <div class="col-12 col-lg-4 col-md-4 col-sm-4 col-xl-4">
                        <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="images">{{ __('Add Images')}}</label>
                            <input type="file" name="images[]" id="images"
                                class="form-control {{ $errors->has('images') ? ' is-invalid' : '' }}"
                                accept="image/png, image/jpeg, image/jpg" multiple>
                            @include('alerts.feedback', ['field' => 'images'])
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-4">{{ __('Upload')}}</button>
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
        select: '#input-subcategory',
    });
    new SlimSelect({
        select: '#input-brand',
    });
    new SlimSelect({
        select: '#input-attribute',
    });
    });
</script>
@endpush

@extends('layouts.app')
@section('content')
<div class="container mt--6 mb-4">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">{{__('Edit Subategory')}}</h3>
                </div>
                <div class="col-4 text-right">
                    <a href="/products/categories" class="btn btn-sm btn-primary">{{__('Back to List')}}</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{"/products/subcategories/$category->id"}}" autocomplete="off">
                @method('patch')
                @csrf
                <h6 class="heading-small text-muted mb-4">{{__('Category Information')}}</h6>
                <div class="pl-lg-4">
                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-name">{{__('Name')}}</label>
                        <input type="text" name="name" id="input-name"
                            class="form-control form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                            placeholder="Category name" value="{{ old('name', $category->name) }}" required autofocus>
                        @include('alerts.feedback', ['field' => 'name'])
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-4">{{ __('Submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

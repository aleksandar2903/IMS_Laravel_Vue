@extends('layouts.app')
@section('content')
<div class="container-fluid mt--6">
    @include('alerts.success')
    @include('alerts.error')
    @include('alerts.deleteConfirmation')
    <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form method="post" action="" autocomplete="off">
                        @method('patch')
                        @csrf
                        <div class="row align-items-center mb-4">
                            <div class="col-8">
                                <p class="mb-0 h2">{{__('Edit Specification')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true" data-dismiss="modal">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="pl-4 pr-4">
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-name">{{__('Name')}}</label>
                                <input type="text" name="name" id="input-name"
                                    class="form-control align-center {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    placeholder="{{__('Name')}}" value="{{ old('name') }}" required autofocus />
                                @include('alerts.feedback',['field'=>'name'])
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="my-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form method="post" action="{{route('specifications.store')}}" autocomplete="off">
                        @csrf
                        <div class="row align-items-center mb-4">
                            <div class="col-8">
                                <p class="mb-0 h2">{{__('Create Specification')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <button type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true" data-dismiss="modal">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="pl-4 pr-4">
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-name">{{__('Name')}}</label>
                                <input type="text" name="name" id="input-name"
                                    class="form-control align-center {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    placeholder="{{__('Name')}}" value="{{ old('name') }}" required autofocus />
                                @include('alerts.feedback',['field'=>'name'])
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h2 class="card-title m-0 p-0 font-weight-600">{{__('Specification List')}}</h2>
                    <span class="m-0 p-0 h5 font-weight-300">{{ __('Specifications')}} | {{__('Specification
                        List')}}</span>
                </div>
                <div class="col-6 text-right">
                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#my-modal"><i
                            class="fa fa-plus-circle" aria-hidden="true"></i> {{__('Create')}}</button>
                </div>
            </div>
        </div>
        <div
            class="table-responsive table-responsive-xl display compact table-responsive-lg table-responsive-md table-responsive-sm py-4">
            <table class="table datatable-basic" id="datatable-basic">
                <thead class="thead-primary text-primary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Name')}}</th>
                        <th scope="col">{{ __('Products')}}</th>
                        <th scope="col">{{__('Date')}}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($specifications as $key => $specification)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $specification->name }}</td>
                        <td>{{ $specification->products->count() }}</td>
                        <td>{{ $specification->updated_at }}</td>
                        <td>
                            <div class="btn-group dropleft">
                                <a class="btn-link text-dark" type="button" id="triggerId" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v    "></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="triggerId">
                                    <a class="dropdown-item text-darker border-0" aria-pressed="true"
                                        href="{{ route('specifications.show', $specification, app()->getLocale()) }}">
                                        {{ __('Show')}}
                                    </a>
                                    {{-- <a class="dropdown-item text-darker border-0" aria-pressed="true"
                                        href="{{ route('specifications.edit', $specification) }}">
                                        {{ __('Edit')}}
                                    </a> --}}
                                    <button class="dropdown-item text-darker" type="button" data-toggle="modal"
                                        data-target="#editModal"
                                        data-action="{{ route('specifications.update',$specification) }}"
                                        data-name="{!!$specification->name!!}">
                                        {{ __('Edit')}}
                                    </button>
                                    <button class="dropdown-item text-danger" type="button" data-toggle="modal"
                                        data-target="#deleteConfirmationModal"
                                        data-action="{{ route('specifications.destroy', $specification) }}">
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
@push('js')
<script>
    $(document).ready(function(){
$('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var action = button.data('action');
                var name = button.data('name');
                var modal = $(this);
                modal.find('form').attr('action', action);
                modal.find('#input-name').val(name)
    });
});
$(document).ready(function(){
        new SlimSelect({
        select: '.form-select'
    });
    })

    $(document).ready(function(){
        new SlimSelect({
        select: '.form-select-create'
    });
    })
</script>
@endpush

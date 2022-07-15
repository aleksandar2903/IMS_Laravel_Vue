@extends('layouts.app')
@section('content')
<div class="container-fluid mt--6">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Product Information')}}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <table class="table border-0">
                            <tbody class="border-0">
                                <tr>
                                    <td class="font-weight-600 border-top-0">{{__('Product')}}: </td>
                                    <td class="border-top-0">{{$product->name}} </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-600">{{ __('Category')}}: </td>
                                    <td>{{$product->category->category->name}} &#8594; {{$product->category->name}}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-600">{{ __('Brand')}}: </td>
                                    <td>{{$product->brand ? $product->brand->name : 'NULL'}}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-600">{{__('Product description')}}: </td>
                                    <td class="text-wrap text-break">{{$product->description}}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-600">{{ __('Stock')}}: </td>
                                    <td>{{$product->stock}} </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-600">{{ __('Defective Stock')}}: </td>
                                    <td>{{$product->stock_defective}} </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-600">{{ __('Price')}}: </td>
                                    <td>{{format_money($product->price)}} </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-600">{{ __('Created at')}}: </td>
                                    <td>{{$product->created_at->format('d-M-Y')}} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 col-lg-6  d-flex">
                        <div class="row">
                            <div class="col-12">
                                @if($product->image)
                                <img class="mx-auto my-auto w-75" id="selectedImage"
                                    src="{{asset('storage/images/'.$product->image->original)}}" alt="">
                                @else
                                <img class="mx-auto my-auto w-75" src="{{asset('storage/images/noimageavailable.png')}}"
                                    alt="No image available">
                                @endif
                            </div>
                            <div class="col-12">
                                @foreach ($product->images as $image)
                                <img onclick="imageClick(this.src)" src="{{asset('storage/images/'.$image->w500)}}"
                                    alt="{{$image->w500}}" class="img-thumbnail m-1" style="height: 100px">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Product Specifications')}}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <table class="table border-0">
                            <tbody class="border-0">
                                @foreach ($product->attributes as $key => $value)
                                <tr>
                                    <td class="font-weight-600 border-top-0">{{__($value->attribute->name)}}: </td>
                                    <td class="border-top-0 text-wrap text-break">{{$value->value}} </td>
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
</div>
@php(
$text = array('Traffic'=>__('Traffic'), 'Overview'=>__('Overview'),
'Purchases'=>__('Purchases'), 'Sales'=>__('Sales')))
<productchart-component :text="{{ json_encode($text) }}" :product-id="{{$product->id}}"></productchart-component>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ __('Latest Sales')}}</h4>
            </div>
            <div class="card-body">
                <div
                    class="table-responsive  table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table-flush">
                    <table class="table datatable-basic" id="">
                        <thead class="table-flush text-primary">
                            <th>{{__('Date')}}</th>
                            <th>{{ __('Sale')}} ID</th>
                            <th>{{ __('Quantity')}}</th>
                            <th>{{ __('Price')}} {{ __('Unit')}}</th>
                            <th>{{ __('Total Amount')}}</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($solds as $sold)
                            <tr>
                                <td>{{ date('d-m-y', strtotime($sold->created_at)) }}</td>
                                <td><a href="{{ route('sales.show', $sold->sale_id) }}">{{ $sold->sale_id }}</a></td>
                                <td>{{ $sold->qty }}</td>
                                <td>{{ format_money($sold->price) }}</td>
                                <td>{{ format_money($sold->total_amount) }}</td>
                                <td class="td-actions text-right">
                                    <a href="{{ route('sales.show', $sold->sale_id) }}" class="btn btn-link m-0 p-0"
                                        data-toggle="tooltip" data-placement="bottom" title="{{__('Show')}}">
                                        <i class="fa fa-search"></i>
                                    </a>
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

<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ __('Latest Purchases')}}</h4>
            </div>
            <div class="card-body">
                <div
                    class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table-flush">
                    <table class="table datatable-basic" id="">
                        <thead class="table-flush text-primary">
                            <th>{{__('Date')}}</th>
                            <th>{{ __('Purchase')}} ID</th>
                            <th>{{ __('Title')}}</th>
                            <th>{{ __('Stock')}}</th>
                            <th>{{ __('Defective Stock')}}</th>
                            <th>{{ __('Stock')}}</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($receiveds as $received)
                            <tr>
                                <td>{{ date('d-m-y', strtotime($received->created_at)) }}</td>
                                <td><a href="{{ route('purchases.show', $received->receipt) }}">{{ $received->receipt_id
                                        }}</a>
                                </td>
                                <td style="max-width:150px;">{{ $received->receipt->title }}</td>
                                <td>{{ $received->stock }}</td>
                                <td>{{ $received->stock_defective }}</td>
                                <td>{{ $received->stock + $received->stock_defective }}</td>
                                <td class="td-actions text-right">
                                    <a href="{{ route('purchases.show', $received->receipt) }}"
                                        class="btn btn-link m-0 p-0" data-toggle="tooltip" data-placement="bottom"
                                        title="{{__('Show')}}">
                                        <i class="fa fa-search"></i>
                                    </a>
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
@endsection
@push('js')
<script>
    $(document).ready( function () {
    $('#datatable').DataTable();
    } );

    function imageClick(e) { document.getElementById("selectedImage").src = e.replace("w500", "original");; }

</script>
@endpush

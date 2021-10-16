<div id="createPaymentModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{__('Create Payment')}}</h3>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="close" aria-label="Close">
                                <span aria-hidden="true" data-dismiss="modal">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post"
                        action="{{ route('sales.transaction.store', $sale) }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                        <input type="hidden" name="client_id" value="{{ $sale->client_id }}">
                        <input type="hidden" name="user_id" value="1">

                        <h6 class="heading-small text-muted mb-4">{{__('Transaction Information')}}</h6>
                        <div class="pl-lg-4">
                            <div class="form-group{{ $errors->has('payment_method_id') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-method">{{__('Payment Method')}}</label>
                                <select name="payment_method_id" id="input-method"
                                    class="form-control form-control-alternative{{ $errors->has('payment_method_id') ? ' is-invalid' : '' }}"
                                    required>
                                    @foreach ($payment_methods as $payment_method)
                                    @if($payment_method['id'] == old('payment_method_id'))
                                    <option value="{{$payment_method['id']}}" selected>{{$payment_method['name']}}
                                    </option>
                                    @else
                                    <option value="{{$payment_method['id']}}">{{$payment_method['name']}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'payment_method_id'])
                            </div>

                            <div class="form-group{{ $errors->has('amount') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-amount">{{__('Amount')}}</label>
                                <input type="number" step=".01" name="amount" id="input-amount"
                                    class="form-control form-control-alternative" placeholder="{{__('Amount')}}"
                                    value="{{ old('amount') }}" required>
                                @include('alerts.feedback', ['field' => 'amount'])

                            </div>

                            <div class="form-group{{ $errors->has('reference') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-reference">{{__('Reference')}}</label>
                                <input type="text" name="reference" id="input-reference"
                                    class="form-control form-control-alternative{{ $errors->has('reference') ? ' is-invalid' : '' }}"
                                    placeholder="{{__('Reference')}}" value="{{ old('reference') }}">
                                @include('alerts.feedback', ['field' => 'reference'])
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
<div id="showPaymentsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-lg modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{__('Payments')}}</h3>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="close" aria-label="Close">
                                <span aria-hidden="true" data-dismiss="modal">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table-flush">
                    <table class="table">
                    <thead class="table-flush text-primary">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Payment Method')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Date')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->transactions as $transaction)
                            <tr>
                                <td>{{$transaction->id}}</td>
                                <td>{{$transaction->type}}</td>
                                <td>{{$transaction->method->name}}</td>
                                <td>{{format_money($transaction->amount)}}</td>
                                <td>{{$transaction->created_at->format('d-M-Y H:i')}}</td>
                                <td>
                                    @if($sale->finalized_at == null)
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-link mb-0 mt-0 p-0" data-toggle="tooltip" data-placement="bottom" title="{{ __('Edit Product')}}">
                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                    </a>
                                    <form action="{{ route('sales.transaction.destroy', [$sale,$transaction]) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-link mb-0 mt-0 p-0" data-toggle="tooltip" data-placement="bottom" title="{{ __('Delete')}} Product" onclick="confirm('Are you sure you want to remove this payment? The records that contain it will continue to exist.') ? this.parentElement.submit() : ''">
                                            <i class="fa fa-trash"></i>
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
</div>

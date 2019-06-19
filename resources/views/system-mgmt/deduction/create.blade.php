@extends('system-mgmt.deduction.base')

@section('action-content')
<div class="container">
    
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Debit Account</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('deduction.store') }}">
                    
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('civilian_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Full Name</label>
                            <div class="col-md-6">
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select Single or Multiples" name="civilian_id"  style="width: 100%;">
                                   
                                    @foreach ($civilian as $civilian)

                                        <option value="{{$civilian->id}}">{{$civilian->firstname}} {{$civilian->lastname}}</option>
                                    @endforeach
                                </select>
                                 @if ($errors->has('civilian_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('civilian_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('deduction_type') ? ' has-error' : '' }}">
                            <label for="credit_type" class="col-md-4 control-label">Deduction Type</label>

                            <div class="col-md-6">
                                <input id="deduction_type" type="text" class="form-control" name="deduction_type" value="{{ old('deduction_type') }}" placeholder="Deduction Type" required autofocus>

                                @if ($errors->has('deduction_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('deduction_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="amount" class="col-md-4 control-label">Debit Amount</label>

                            <div class="col-md-6">
                                <input id="amount" type="number" class="form-control" name="amount" value="{{ old('amount') }}" placeholder=" Amount"required>

                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('organization') ? ' has-error' : '' }}">
                            <label for="organization" class="col-md-4 control-label">Financial Institution</label>

                            <div class="col-md-6">
                                <input id="organization" type="text" class="form-control" name="organization" value="{{ old('organization') }}" placeholder=" Financial Institution"required>

                                @if ($errors->has('organization'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('organization') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                
                          <div class="form-group">
                            <label class="col-md-4 control-label"> Starting Date</label>
                            <div class="col-md-6">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" value="{{ old('start_date') }}" name="start_date" class="form-control pull-right" id="hiredDate" placeholder="Starting Date" required>
                                </div>
                            </div>
                        </div>
                            <div class="form-group">
                            <label class="col-md-4 control-label"> Ending Date</label>
                            <div class="col-md-6">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" value="{{ old('end_date') }}" name="end_date" class="form-control pull-right" id="endDate" placeholder="End date" required>
                                </div>
                            </div>
                        </div>
                            <div class="form-group{{ $errors->has('purpose') ? ' has-error' : '' }}">
                            <label for="purpose" class="col-md-4 control-label">Deduction Purpose</label>

                            <div class="col-md-6">
                                 <textarea class="textarea" input id="purpose" name="purpose" value="{{ old('purpose') }}"placeholder="Enter text" ></textarea>
                                

                                @if ($errors->has('purpose'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('purpose') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                
                        
                       
                        
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
}
</script>

@endsection


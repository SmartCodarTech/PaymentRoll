@extends('system-mgmt.allowance.base')

@section('action-content')
<div class="container">
    
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Civilians Allowance</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('allowance.store') }}">
                    
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('civilian_id') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Full Name</label>
                            <div class="col-md-6">
                                <select class="form-control select2"  multiple="multiple" data-placeholder="Select a Employee's name" name="civilian_id"  style="width: 100%;">
                                   
                                    @foreach ($civilians as $civilian)

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

                        <div class="form-group{{ $errors->has('allowance_type') ? ' has-error' : '' }}">
                            <label for="allowance_type" class="col-md-4 control-label">Allowance Type</label>

                            <div class="col-md-6">
                                <input id="allowance_type" type="text" class="form-control" name="allowance_type" value="{{ old('allowance_type') }}" placeholder="Allowance Type" required autofocus>

                                @if ($errors->has('allowance_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('allowance_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="amount" class="col-md-4 control-label">Allowance Amount</label>

                            <div class="col-md-6">
                                <input id="amount" type="text" class="form-control" name="amount" value="{{ old('amount') }}" placeholder=" Allowance Amount"required>

                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                
                          <div class="form-group">
                            <label class="col-md-4 control-label"> Date</label>
                            <div class="col-md-6">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" value="{{ old('allowance_date') }}" name="allowance_date" class="form-control pull-right" id="hiredDate" placeholder="Allowance Date" required>
                                </div>
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


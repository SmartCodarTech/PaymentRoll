@extends('mail-mgmt.base')

@section('action-content')
  
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="{{ route('mail-management.index') }}" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="mailbox.html"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">12</span></a></li>
                <li><a href="{{ route('mail-management.index') }}"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li><a href="{{ route('mail-management.index') }}"><i class="fa fa-file-text-o"></i> Drafts</a></li>
                <li><a href="{{ route('mail-management.index') }}"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a>
                </li>
                <li><a href="{{ route('mail-management.index') }}"><i class="fa fa-trash-o"></i> Trash</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>
            </div>
           
            <div class="box-body">
              @if ($body= Session::get('success'))
             <div class="alert alert-success alert-block">
             <button type="button" class="close" data-dismiss="alert">×</button>
               <strong>{{ $body }}</strong>
                </div>
               @endif
               <form class="sendemail-form" role="form" method="POST" action="{{ route('mail-management.store') }}">
                 {{ csrf_field() }}
              <div class="form-group">

                  <select class="form-control select2"  multiple="multiple" data-placeholder="To" name="email"  style="width: 100%;">
                    @foreach($employees as $employees)
                   <option value="{{$employees->id}}"> {{$employees->email}}</option>
                                    @endforeach
                </select>
              </div>

              <div class="form-group">
                <input class="form-control" name="subject" placeholder="Subject:">
              </div>
              <div class="form-group">
                    <textarea id="compose-textarea" name="body" class="form-control" style="height: 300px">
                      
                    </textarea>
              </div>
              <div class="form-group">
                <div class="btn btn-default btn-file">
                  <i class="fa fa-paperclip"></i> Attachment
                  <input type="file" name="attachment">
                </div>
                <p class="help-block">Max. 32MB</p>
              </div>
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
                <button type="submit" class="btn btn-primary"><i name="submit" class="fa fa-envelope-o" ></i> Send</button>
              </div>
              <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
            </div>

            <!-- /.box-footer -->
         

          </div>
            </form>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
@endsection

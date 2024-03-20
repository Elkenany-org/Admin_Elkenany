@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-body">
                <div class="card card-primary card-outline">
                    <div class="card-header">

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                        <h5>{{$message->title}}</h5>
                        
                        @if($message->User)
                        <h6>رسالة من: {{$message->User->email}}
                            <span class="mailbox-read-time float-right">{{Date::parse($message->created_at)->diffForHumans()}}</span></h6>
                        </div>
                        @else
                        <h6>رسالة من: {{$message->user_email}}
                            <span class="mailbox-read-time float-right">{{Date::parse($message->created_at)->diffForHumans()}}</span></h6>
                        </div>
                        @endif
                        <!-- /.mailbox-read-info -->
                        <div class="mailbox-controls with-border text-center">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Print">
                        </div>

                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message text-center" style="padding: 25px">
                            {{$message->subject}}
                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>


            </div>
         </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">

</script>
@endsection


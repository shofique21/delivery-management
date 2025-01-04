@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title','Complain')
@section('content')
<div class="profile-edit mrt-30">
    <div class="col-sm-12">
        <div class="profile-edit mrt-30">
            <div class="row">
                <div class="col-lg-6 mx-auto col-md-10 col-sm-10 mt-5 mb-5">
                        <form action="{{url('merchant/addcomplain')}}" method="POST" name="complainForm">
                            @csrf

                            {{-- 'subject','type_issue','issue', 'details','status', --}}
                        <div class="form-group">
                            <input type="text" name="subject" class="form-control" placeholder="Subject">
                           <label class="form-label d-block mt-2">Please select an issue type</label>
                       
                           <select class="form-control demo-select2-placeholder" name="type_issue" id="issue_id" required>
                            <option value="">Select Issue</option>
                            @foreach($issues as $issue)
                                <option value="{{$issue->id}}">{{__($issue->name)}}</option>
                            @endforeach
                        </select>
                           <div class="mt-2">
                            <select class="form-control demo-select2-placeholder" name="issue" id="issueDetail">
                            </select>
                           </div>
                           <textarea id="message" name="details" placeholder="Enter issue details" class="form-control mt-2" name="message"></textarea>
                           <button type="submit" class="mt-3 w-100 btn btn-primary common-btn btn-lg">Submit Issue</button>
                       </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="submitted-issues">
                        <table class="table table-striped table-sm">
                          <thead class="thead-dark">
                            <tr>
                              <th scope="col">Subject</th>
                              <th scope="col">ID</th>
                              <th scope="col">Type</th>
                              <th scope="col">Issue</th>
                              <th scope="col">Details</th>
                              <th scope="col">Created</th>
                              <th scope="col">Status</th>
                            </tr>
                          </thead>
                          <tbody>
                                @php
                                    if(!empty($allComplain)){ @endphp
                                        @foreach($allComplain as $issue)
                                        <tr>

                                        <th class="align-middle">  {{$issue->subject}}</th>
                                        <td class="align-middle">#{{$issue->id}}</td>
                                        <td class="align-middle">{{$issue->issuetype}}</td>
                                        <td class="align-middle">{{$issue->issue}}</td>
                                        <td class="align-middle">{{$issue->details}}</td>
                                        <td class="align-middle"><small>{{\Carbon\Carbon::createFromTimestamp(strtotime($issue->created_at))->format('g:ia \o\n l jS F Y')}}</small></td>
                                        @if($issue->status==1)
                                        <td class="align-middle"><button class="btn btn-info btn-sm">Pending</button></td>
                                        @else
                                        <td class="align-middle">
                                  <button type="button" data-toggle="modal" data-target="#replyId{{$issue->id}}" class="btn btn-success btn-sm">Solved</button>
                                  <div class="modal fade" id="replyId{{$issue->id}}" tabindex="-1" aria-labelledby="id7363Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="id7363Label">Complain Replay on time</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                         
                                            @php
                                            $issuedetail = DB::table('reply_complains')->where('complain_id',$issue->id)->get();
                                            @endphp
                                            @if(!empty($issuedetail))
                                                @foreach($issuedetail as $key => $value) 
                                                    {{$value->user_name}} <small>({{\Carbon\Carbon::createFromTimestamp(strtotime($value->created_at))->format('g:ia d M Y')}})</small>
                                            <div class="p-3 bg-light mb-3 shadow rounded border">
                                                <p>{{$value->details}}</p>
                                            </div>                                                
                                             @endforeach
                                             @endif
                                         
                                        
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                              </td>
                                        @endif
                                    </tr>

                            @endforeach

                            @php
                                       }
                                @endphp
                             
                        
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
    </div>
    </div>
    <!-- row end -->
</div>
<script>
    function getIssueDetalisByIssue(){
		var issue_id = $('#issue_id').val();
        // alert(issue_id);
		$.post('{{ route('issuedetails.getIssueDetalisByIssue') }}',{_token:'{{ csrf_token() }}', issue_id:issue_id}, function(data){
		    $('#issueDetail').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#issueDetail').append($('<option>', {
		            value: data[i].id,
		            text: data[i].details
		        }));
		        $('.demo-select2').select2();
		    }
		});
	}

    $('#issue_id').on('change', function() {
	    getIssueDetalisByIssue();
	});

    
</script>
@endsection
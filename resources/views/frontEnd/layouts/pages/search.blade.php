 <div class="row">
                 @foreach($sBrances as $key=>$value)
                <div class="col-md-4">
                    <div class="shadow-sm p-2 mb-4 bg-white rounded agent-card">
                        <img src="{{asset($value->images)}}" class="img-responsive" style="max-width: 100%;"/>
                        <h5 class="mt-3">{{$value->name}}</h5>
                        <p>Phone: <a href="tel:{{$value->phone}}">{{$value->phone}}</a><br>Address: {{$value->address}}<br> Area : {{$value->zonename}}</p>
                    </div>
                </div>
                 @endforeach
                </div>
                
                <script type="text/javascript">
    $(document).ready(function () {
        window.setTimeout(function () {

            window.location.href = '/branches'; // "/queue" is the url route for wintwo.blade.php

        }, 2000);

    }
</script>

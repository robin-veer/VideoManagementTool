<form method="get" action="" class="col-12">
    <div class="row">
        @isset($count)
            <h3 class="col-2"><span class="color-primary-light">{{$count}}</span> <span class="small">videos</span></h3>
        @endisset

        <select name='order' class="col-2 offset-9 form-control">
            <option value="rc" {{request()->order == 'rc' ? 'selected' : ''}} >Most Recent</option>
            <option value="mv" {{request()->order == 'mv' ? 'selected' : ''}} >Most Viewed</option>
            <option value="lg" {{request()->order == 'lg' ? 'selected' : ''}} >Longest</option>
        </select>

        <div class="col-1">
            <button class="btn btn-block btn-outline-primary" type="submit">Sort</button>
        </div>
    </div>
</form>


@section('footer')
    <script>
        $(function () {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function (start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });
    </script>
@append
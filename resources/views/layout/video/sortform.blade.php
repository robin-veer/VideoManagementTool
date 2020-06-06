<div class="row">
    <form method="post" class="col-md-4" action="{{route('video.update')}}"
          enctype="application/x-www-form-urlencoded">
        @csrf
        @method('PATCH')

        <input type="text" class="form-control" name="category" value="">
        <input type="hidden" name="video" value="{{$video->id}}">

        <button type="submit" class="my-2 btn btn-outline-info">Update</button>
    </form>
</div>
<hr class="w-100">
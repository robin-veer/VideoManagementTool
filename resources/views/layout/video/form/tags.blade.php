<form id='createTags' class="bg-white tag-form flex-column"
      action="{{route('tag.store')}}"
      method="post">
    @csrf
    <div class="form-group">
        <p class="text-black-50 ">Add a new tag</p>

        <select id="tags" class="form-control tags-selector w-100 mb-2" style="width:100%;" name="tags[]"
                multiple="multiple">
            @foreach($tags as $tag)
                <option value="{{$tag->name}}" {{in_array($tag->name, $video->tags->pluck('name')->toArray()) ? 'selected' : ''}}> {{$tag->name}}</option>
            @endforeach
        </select>

        <input type="hidden" name="id" value="{{$video->id}}">
        <button type="submit" class="btn btn-primary btn-block mt-2">Send</button>
    </div>
</form>

@section('footer')
    <script>
        // init select2
        $(document).ready(function () {
            $('.tags-selector').select2({
                tags: true
            });
        });


        // Send the tags
        let form = document.querySelector('#createTags');

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            let options = document.querySelector('#tags').selectedOptions;

            let data = [];
            for (let option of options) {
                data.push(option.value);
            }

            axios.post( "{{route('tag.store')}}" , {
                tags: data,
                id: {{$video->id}},
            }).then(response => response.json())
                .then(response => console.log(response))
        });
    </script>
@append
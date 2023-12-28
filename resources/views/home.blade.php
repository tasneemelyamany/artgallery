@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class "d-flex justify-content-between">
                        <div>Egypt's Art</div>
                        <div>
                            <form class= "form-inline" onchange="sort_by(this.value)">
                                <select class = "form-control">
                                    <option value="oldest" {{ (Request::query('sort_by')
                                    && Request::query('sort_by')== 'oldest' || !Request::query('sort_by')) ? 'selected':''  }}>Oldest</option>
                                    <option value="latest" {{ (Request::query('sort_by')
                                    && Request::query('sort_by')== 'latest' || !Request::query('sort_by')) ? 'selected':''  }}> Latest</option>

                                </select>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-3">
                            <ul class="list-group">
                                <a href="javascript:filter_image('')" class="list-group-item list-group-item-action"
                                {{(!Request::query('category'))?'active':''}}>ALL</a>
                                <a href="javascript:filter_image('predynastic')" class="list-group-item list-group-item-action"
                                {{(!Request::query('category')== 'predynastic')?'active':''}}>Predynastic period</a>
                                <a href="javascript:filter_image('dynastic')" class="list-group-item list-group-item-action"
                                {{(!Request::query('category')== 'dynastic')?'active':''}}>Dynastic Egypt</a>
                                <a href="javascript:filter_image('roman')" class="list-group-item list-group-item-action"
                                {{(!Request::query('category')== 'roman')?'active':''}}>Greco-Roman Egypt</a>
                                <a href="javascript:filter_image('other')" class="list-group-item list-group-item-action"
                                {{(!Request::query('category')== 'other')?'active':''}}>Other</a>
                              </ul>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    @if($errors->any())
                                     @foreach ( $errors->all() as $error)
                                     <div class="alert alert-danger">
                                        <strong>Error!</strong> {{ $error }}
                                      </div>

                                     @endforeach
                                    @endif
                                    <button data-bs-toggle="collapse" class="btn-success" data-bs-target="#demo">Add Image</button>

                                    <div id="demo" class="collapse">
                                        <form action="{{ route('image_store') }}" method="POST" id="image_upload_form" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3 mt-3">
                                              <label for="email" class="form-label">Email:</label>
                                              <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                                            </div>
                                            <div class="mb-3">
                                              <label for="image" class="form-label">Image Caption</label>
                                              <input type="text" name="caption" class="form-control" id="caption" placeholder="Enter Caption" >
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Select Category</label>
                                                <select name="category" class="form-select" id="category">
                                                    <option value="predynastic">Predynastic period</option>
                                                    <option value="dynastic">Dynastic Egypt</option>
                                                    <option value="roman">Greco-Roman Egypt</option>
                                                    <option value="other">Other</option>
                                                  </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Upload File</label>
                                                <div class="preview-zone hidden">
                                                  <div class="box box-solid">
                                                   <div class="box-body"></div>
                                                  </div>
                                                </div>
                                                <div class="dropzone-wrapper">
                                                  <div class="dropzone-desc">
                                                    <i class="glyphicon glyphicon-download-alt"></i>
                                                    <p>Choose an image file or drag it here.</p>
                                                  </div>
                                                  <input type="file" name="image" class="dropzone">
                                                </div>
                                              </div>
                                              <button type="submit" class="btn btn-primary">Submit</button>
                                          </form>
                                    </div>

                                </div>
                                <div class="col-md-12 mt-4">
                                    <div class="row">
                                        @if(count($images))
                                        @foreach($images as $image)
                                        <div class="col-md-3 mb-4">
                                            <a href="#">
                                                <img src={{ asset('user_images/thumb/'.$image->image) }} height="100%" width="100%">
                                                </a>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="col-md-12">
                                        <p>No images have been uploaded yet</p>
                                        </div>
                                        @endif
                                        @if(count($images))
                                        <div class="col-md-12">
                                        {{$images->appends(Request::query())->links()}}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')


<script type="text/javascript">

var query = {};
function filter_image(value){
    Object.assign(query, {'category': value})
    window.location.href = "{{route('home')}}" + '?' + $.param(query);
}

function sort_by(value){
    Object.assign(query, {'sort_by': value})
    window.location.href = "{{route('home')}}" + '?' + $.param(query);
}


$("#image_upload_form").validate({
    rules: {
        email:{
            required:true,
            email:true
        }
      caption: {
        required: true,
        maxlength: 255
      },
      category: {
        required: true,
      },
      image:{
        required: true,
        extension: "png|jpeg|jpg|bmp"
      }
    },
    messages: {

    email:{
        required:"Please enter your Email",
        email:"Please enter a valid Email Address"
    }
    caption: {
        required: "We need the image caption to add the image to the gallery",
        maxlength: "Max 255 char allowed"
     },
     category: {
        required: "We need the image category to add the image to the gallery"
     },
     image: {
        required: "We need the image caption to add the image to the gallary",
        extension: "only png or jpeg or jpg or bmp"
     }
    }
  });





    function readFile(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        var htmlPreview =
          '<img width="200" src="' + e.target.result + '" />' +
          '<p>' + input.files[0].name + '</p>';
        var wrapperZone = $(input).parent();
        var previewZone = $(input).parent().parent().find('.preview-zone');
        var boxZone = $(input).parent().parent().find('.preview-zone').find('.box').find('.box-body');

        wrapperZone.removeClass('dragover');
        previewZone.removeClass('hidden');
        boxZone.empty();
        boxZone.append(htmlPreview);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  function reset(e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
  }

  $(".dropzone").change(function() {
    readFile(this);
  });

  $('.dropzone-wrapper').on('dragover', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).addClass('dragover');
  });

  $('.dropzone-wrapper').on('dragleave', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
  });

  $('.remove-preview').on('click', function() {
    var boxZone = $(this).parents('.preview-zone').find('.box-body');
    var previewZone = $(this).parents('.preview-zone');
    var dropzone = $(this).parents('.form-group').find('.dropzone');
    boxZone.empty();
    previewZone.addClass('hidden');
    reset(dropzone);
  });

</script>

@endsection


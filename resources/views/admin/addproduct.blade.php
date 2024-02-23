@extends('layouts.admin')

@section('main')
    <div>
        <h5 class="center">Product</h5>
        @error('name')
            <div class="red-text">{{ $message }}</div>
        @enderror
        <div class="mp-card">
            <form action="{{ route('addprod') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col s12">
                        <label>Name :</label><input name="name" type="text" class="browser-default inp"
                            placeholder="Name" required>
                    </div>
                    <div class="col m6 s12">
                        <div id="input-field">
                            <label>Brand :</label>
                            <select id="MySelct" class=" pop" searchname="myselectsearch" name="brand_id"
                                searchable="Search Brand" required>
                                <option value="" disabled selected>Select Brand</option>
                                @foreach ($brands as $brd)
                                    <option value="{{ $brd->id }}">{{ $brd->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col m6 s12">
                        <div id="input-field">
                            <label>Category :</label>
                            <select id="MySelct" class=" pop" searchname="myselectsearch" name="category_id"
                                searchable="Search Category" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach ($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col m6 s12">
                        <label>
                            <input type="checkbox" name="stock"/>
                            <span>Out of Stock</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="hide"/>
                            <span>Hidden</span>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="featured"/>
                            <span>Featured</span>
                        </label>
                    </div>
                    <div class="col m6 s12">
                        <label>Price :</label><input name="price" type="number" class="browser-default inp"
                            placeholder="Price" required>
                    </div>
                    <div class="col s12" style="margin-top: 20px;">
                        <label>Details :</label>
                        <textarea name="details" class="browser-default inp" style="resize: vertical;" placeholder="Details"></textarea>
                    </div>
                    <div class="col s12 file-field input-field">
                        <div class="btn">
                            <span>Select Images</span>
                            <input type="file" id="files" multiple name="images[]">
                        </div>
                    </div>
                    <div id="filespreview"></div>
                </div>
                <div class="fixed-action-btn">
                    <button class="btn-large red">
                        Submit <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            if (window.File && window.FileList && window.FileReader) {
                $("#files").on("change", function(e) {
                    var files = e.target.files,
                        filesLength = files.length;
                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i]
                        var fileReader = new FileReader();
                        fileReader.onload = (function(e) {
                            var file = e.target;
                            $("<div class=\"col s12 m6 valign-wrapper pip\" style='margin-top:20px;'>" +
                                "<img class=\"imageThumb\" src=\"" + e.target.result +
                                "\" title=\"" + file.name + "\" style='height: 200px;'/>"+
                                "</span>").insertAfter("#filespreview");
                            $(".remove").click(function() {
                                $(this).parent(".pip").remove();
                            });

                            // Old code here
                            /*$("<img></img>", {
                              class: "imageThumb",
                              src: e.target.result,
                              title: file.name + " | Click to remove"
                            }).insertAfter("#files").click(function(){$(this).remove();});*/

                        });
                        fileReader.readAsDataURL(f);
                    }
                });
            } else {
                alert("Your browser doesn't support to File API")
            }
        });
    </script>
@endsection

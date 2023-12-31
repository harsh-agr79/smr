@extends('layouts.customer')

@section('main')
    {{-- <div class='input-field' style="margin-top:10px; padding: 5px;">
        <input class='validate browser-default search inp black-text z-depth-1' onkeyup="searchFun()" autocomplete="off"
            type='search' id='search' />
        <span class="field-icon" id="close-search"><span class="material-icons" id="cs-icon">search</span></span>
    </div>

    <div class="mp-card" style="padding: 10px;">
        <form id="filterform">
            @foreach ($brands as $item)
                <span style="margin-right: 10px">
                    <label>
                        <input type="checkbox" name="{{ $item->id }}brd" value="{{ $item->id }}brd"
                            onclick="Filter()" />
                        <span>{{ $item->name }}</span>
                    </label>
                </span>
            @endforeach
        </form>
            <br>
        <form id="filformcat">
            @foreach ($category as $item)
                <span style="margin-right: 10px">
                    <label>
                        <input type="checkbox" name="{{ $item->id }}cat" value="{{ $item->id }}cat"
                            onclick="Filter()" />
                        <span>{{ $item->category }}</span>
                    </label>
                </span>
            @endforeach
      <form>
    </div> --}}
    <div class="row" style="margin: 0;">
            <div class='col s10 input-field' style="margin-top: 14px;">
                <input class='validate browser-default search inp black-text z-depth-1' onkeyup="searchFun()" autocomplete="off"
                    type='search' id='search' />
                <span class="field-icon" id="close-search"><span class="material-icons" style="font-size: 15px;" id="cs-icon">search</span></span>
            </div>
        <div class="col s2">
            <div class="btn green modal-trigger" href="#modal1" style="margin-top: 16px;"><i class="material-icons">filter_list</i></div>
        </div>
    </div>

    <div id="modal1" class="modal">
        <div class="center">
            <h5>Filter By company and category</h5>
        </div>
        <div class="row" style="padding: 10px;">
            <div class="col s6">
                <form id="filterform">
                    @foreach ($brands as $item)
                        <div>
                            <label>
                                <input type="checkbox" name="{{ $item->id }}brd" value="{{ $item->id }}brd"
                                    onclick="Filter()" />
                                <span>{{ $item->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="col s6">
                <form id="filformcat">
                    @foreach ($category as $item)
                        <div>
                            <label>
                                <input type="checkbox" name="{{ $item->id }}cat" value="{{ $item->id }}cat"
                                    onclick="Filter()" />
                                <span>{{ $item->category }}</span>
                            </label>
                        </div>
                    @endforeach
              <form>
            </div>
        </div>
      </div>
    <div class="product-container">
        @foreach ($prods as $item)
            <div class="prod-box searchable center {{ $item->brand_id }}brd {{ $item->category_id }}cat">
                <div class="prod-img" style="background: url('@if($item->images != "" || $item->images != NULL){{ asset(explode('|', $item->images)[0]) }}@else{{ asset('images/prod.jpg') }}@endif') no-repeat center center; background-size: cover;">
                    <div>
                        <span class="company-title left" style="margin: 3px;">
                            {{$item->brand}}
                        </span>
                        <span class="company-title right" style="margin: 3px;">
                            {{$item->category}}
                        </span>
                    </div>
                    
                </div>
                <div class="prod-det">
                    <span style="margin: 0; padding: 0; font-weight: 600; font-size: 15px">{{ $item->name }}</span><br>
                    <span style="margin: 0; padding: 0; font-weight: 600; font-size: 12px">Rs. {{ $item->price }}</span>
                    
                </div>
                <div class="add-to-cart container" style="margin-top: 5px;">
                    <div class="row container">
                            <span class="col s3 prod-btn" style="border-radius: 5px 0 0 5px;" onclick="minus('{{$item->id}}')"><i class="material-icons">remove</i></span>
                            <input type="text" class="col s6 browser-default inp" id="{{$item->id}}cartinp" style="height: 32px; text-align:center; border-radius:0;" value="0">
                            <span class="col s3 prod-btn" style="border-radius: 0 5px 5px 0; " onclick="plus('{{$item->id}}')"><i class="material-icons">add</i></span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function Filter() {
            $('.prod-box').hide()
            $('.prod-box').removeClass('searchable');
            clsnames = "";
            var formData = $('#filterform').serializeArray()
            var formData2 = $('#filformcat').serializeArray()
            if (formData.length > 0) {
                for (let i = 0; i < formData.length; i++) {
                    if(formData2.length > 0){
                        for (let j = 0; j < formData2.length; j++) {
                            clsname = ""
                            clsname = "."+formData[i].name + "."+formData2[j].name
                            // console.log(clsname)
                            $(`${clsname}`).addClass('searchable')
                            $(`${clsname}`).show();
                        }
                    }
                    else{
                        $(`.${formData[i].name}`).addClass('searchable')
                        $(`.${formData[i].name}`).show();
                    }
                }
            } else {
                if(formData2.length > 0){
                    for (let j = 0; j < formData2.length; j++) {
                            $(`.${formData2[j].name}`).addClass('searchable')
                            $(`.${formData2[j].name}`).show();
                        }
                }
                else{
                    $('.prod-box').addClass('searchable')
                    $('.prod-box').show();
                }
            }

        }
        const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let cont = document.getElementsByClassName('product-container');
            var prod = $('.searchable')
            if (filter === '') {
                $('#cs-icon').text('search')
            } else {
                $('#cs-icon').text('close')
            }
            for (var i = 0; i < prod.length; i++) {
                let span = prod[i].getElementsByTagName('span');
                // console.log(td);
                for (var j = 0; j < span.length; j++) {
                    if (span[j]) {
                        let textvalue = span[j].textContent || span[j].innerHTML;
                        if (textvalue.toLowerCase().indexOf(filter) > -1) {
                            prod[i].style.display = "";
                            break;
                        } else {
                            prod[i].style.display = "none"
                        }
                    }
                }
            }
        }
        function plus(id){
            a = parseInt($(`#${id}cartinp`).val())
            a = a + 1
            $(`#${id}cartinp`).val(a)
        }
        function minus(id){
            a = parseInt($(`#${id}cartinp`).val())
            if(a!=0){
                a = a - 1
                $(`#${id}cartinp`).val(a)
            }
        }
    </script>
@endsection

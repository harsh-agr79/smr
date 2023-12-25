@extends('layouts.customer')

@section('main')
    <div class='input-field' style="margin-top:10px; padding: 5px;">
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
                            onclick="Filter('{{ $item->id }}brd')" />
                        <span>{{ $item->name }}</span>
                    </label>
                </span>
            @endforeach
            <br>
            @foreach ($category as $item)
                <span style="margin-right: 10px">
                    <label>
                        <input type="checkbox" name="{{ $item->id }}cat" value="{{ $item->id }}cat"
                            onclick="Filter('{{ $item->id }}cat')" />
                        <span>{{ $item->category }}</span>
                    </label>
                </span>
            @endforeach
        </form>
    </div>
    <div class="product-container">
        @foreach ($prods as $item)
            <div class="prod-box center {{ $item->brand_id }}brd {{ $item->category_id }}cat">
                <div>
                    @if ($item->images != "" || $item->images != NULL)
                    <img class="prod-img" src="{{ asset(explode('|', $item->images)[0]) }}" alt="">
                    @else
                    <img class="prod-img" src="{{ asset('images/prod.jpg') }}" alt="">
                    @endif

                </div>
                <div class="prod-det">
                    <span style="font-weight: 600; font-size: 15px">{{ $item->name }}</span><br>
                    <span style="font-weight: 600; font-size: 14px">Rs. {{ $item->price }}</span><br>
                    <span style="font-size: 10px">{{ $item->brand }}</span>
                    <span style="font-size: 10px">{{ $item->category }}</span>
                </div>
                <div class="add-to-cart">

                </div>
            </div>
        @endforeach
    </div>

    <script>
        function Filter(cat) {
            $('.prod-box').hide();
            clsnames = "";
            var formData = $('#filterform').serializeArray()
            if (formData.length > 0) {
                for (let i = 0; i < formData.length; i++) {
                    $(`.${formData[i].name}`).show();
                }
            } else {
                $('.prod-box').show();
            }

        }
        const searchFun = () => {
            var filter = $('#search').val().toLowerCase();
            const a = document.getElementById('search');
            const clsBtn = document.getElementById('close-search');
            let cont = document.getElementsByClassName('product-container');
            var prod = $('.prod-box')
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
    </script>
@endsection

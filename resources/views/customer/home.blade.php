@extends('layouts.customer')

@section('main')
    @php
        $cart = $user->cart;
        if ($cart != null) {
            $break = explode(':', $cart);
            $prod = explode(',', $break[0]);
            $qty = explode(',', $break[1]);
        } else {
            $break = [];
            $prod = [];
            $qty = [];
        }
    @endphp
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
            <span class="field-icon" id="close-search"><span class="material-icons" style="font-size: 15px;"
                    id="cs-icon">search</span></span>
        </div>
        <div class="col s2">
            <div class="btn green accent-4 modal-trigger" href="#modal1" style="margin-top: 16px;"><i
                    class="material-icons">filter_list</i></div>
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
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large green accent-4" onclick="getcart()"><i class="material-icons">shopping_cart</i></a>
    </div>
    <div id="cart-modal" class="modal">
        <div class="modal-content">
            <h4>Cart</h4>
            <table>
                <thead>
                    <th>SN</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </thead>
                <tbody id="cart-table-body">

                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: 600; font-size: 12px;">Total</td>
                        <td style="font-weight: 600; font-size: 12px;" id="cart-total"></td>
                    </tr>
                </tfoot>
            </table>
            <div class="modal-footer row" style="margin: 0; padding: 0;">
                <div class="col s6">

                </div>
                <div class="col s3">
                    <a href="{{ url('/user/savecart') }}" class="btn-small amber darken-2">Save Basket</a>
                </div>
                <div class="col s3">
                    <a href="{{ url('/user/confirmcart') }}" class="btn-small green accent-4">Confirm Order</a>
                </div>

            </div>
        </div>
    </div>
    <form id="form-main-cart">
        <div class="product-container">
            @foreach ($prods as $item)
                <div class="prod-box searchable center {{ $item->brand_id }}brd {{ $item->category_id }}cat">
                    <div class="prod-img" onclick="details({{ $item->id }})"
                        style="background: url('@if ($item->images != '' || $item->images != null) {{ asset(explode('|', $item->images)[0]) }}@else{{ asset('images/prod.jpg') }} @endif') no-repeat center center; background-size: cover;">
                        <div>
                            <span class="company-title left" style="margin: 3px;">
                                {{ $item->brand }}
                            </span>
                            <span class="company-title right" style="margin: 3px;">
                                {{ $item->category }}
                            </span>
                        </div>

                    </div>
                    <div class="prod-det">
                        <span
                            style="margin: 0; padding: 0; font-weight: 600; font-size: 13px">{{ $item->name }}</span><br>
                        <span style="margin: 0; padding: 0; font-weight: 600; font-size: 11px">Rs.
                            {{ $item->price }}</span>

                    </div>
                    <div class="add-to-cart container" style="margin-top: 5px;">
                        <div class="row container">
                            <span class="col s3 prod-btn" style="border-radius: 5px 0 0 5px;"
                                onclick="minus('{{ $item->id }}')"><i class="material-icons">remove</i></span>
                            <input type="hidden" class="prodids" name="prodid[]" value="{{ $item->id }}">
                            <input type="number" class="col s6 browser-default inp qtys" id="{{ $item->id }}cartinp"
                                onkeyup="updatecart()" style="height: 32px; text-align:center; border-radius:0;"
                                name="qty[]"
                                @if (in_array($item->id, $prod)) value="{{ getqty($item->id, $prod, $qty) }}"
                                @else
                                    value="0" @endif>
                            <span class="col s3 prod-btn" style="border-radius: 0 5px 5px 0; "
                                onclick="plus('{{ $item->id }}')"><i class="material-icons">add</i></span>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="hide">
                <button>Submit</button>
            </div>
        </div>
    </form>
    <div id="details" class="modal bottom-sheet bg-content">
        <div class="modal-content bg-content">
            <div class="row bg-content">
                <div class="row col s12" style="margin:0; padding: 0 30px 30px 30px; height: 30% !important;">
                    <div class="carousel carousel-slider" id="mod-caro">
                    </div>
                </div>
                <div class="col s12">
                    <h5 id="mod-name"></h5>
                </div>
                <div class="col s6">
                    <span id="mod-price" style="font-weight: 600;"></span>
                </div>
                <div class="col s6">
                    <span id="mod-category" style="font-weight: 600;"></span>
                </div>
                <div class="col s12" style="margin-top: 10px;">
                    <span style="font-weight: 600;">Details:</span>
                    <div style="white-space: pre-wrap" id="mod-details"></div>
                </div>
            </div>
        </div>
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
                    if (formData2.length > 0) {
                        for (let j = 0; j < formData2.length; j++) {
                            clsname = ""
                            clsname = "." + formData[i].name + "." + formData2[j].name
                            // console.log(clsname)
                            $(`${clsname}`).addClass('searchable')
                            $(`${clsname}`).show();
                        }
                    } else {
                        $(`.${formData[i].name}`).addClass('searchable')
                        $(`.${formData[i].name}`).show();
                    }
                }
            } else {
                if (formData2.length > 0) {
                    for (let j = 0; j < formData2.length; j++) {
                        $(`.${formData2[j].name}`).addClass('searchable')
                        $(`.${formData2[j].name}`).show();
                    }
                } else {
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

        function plus(id) {
            a = parseInt($(`#${id}cartinp`).val())
            a = a + 1
            $(`#${id}cartinp`).val(a)
            updatecart()
        }

        function minus(id) {
            a = parseInt($(`#${id}cartinp`).val())
            if (a != 0) {
                a = a - 1
                $(`#${id}cartinp`).val(a)
            }
            updatecart()
        }

        function updatecart() {
            var prodid = $('.prodids')
            var qty = $('.qtys')
            prod = []
            qt = []
            for (let i = 0; i < prodid.length; i++) {
                prod.push(parseInt(prodid[i].value))
                qt.push(parseInt(qty[i].value))
            }
            var formdata = new FormData()
            formdata.append('prod', prod)
            formdata.append('qt', qt)
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/user/updatecart",
                data: formdata,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    console.log(response)
                }
            })
        }

        function getcart() {
            $.ajax({
                url: "/user/getcart",
                type: "GET",
                success: function(response) {
                    $('#cart-modal').modal("open");
                    $('#cart-table-body').text('');
                    a = 0
                    t = 0
                    $.each(response, function(key, item) {
                        if (item.image == null) {
                            image = '/images/prod.jpg'
                        } else {
                            image = "/" + item.image
                        }
                        a = a + 1
                        t = t + item.total
                        $('#cart-table-body').append(`
                        <tr>
                            <td>${a}</td>
                            <td><img src="${image}" class="table-dp"></td>
                            <td>${item.name}</td>
                            <td>${item.price}</td>
                            <td>${item.quantity}</td>
                            <td>${item.total}</td>
                        </tr>
                        `)
                    })
                    $('#cart-total').text(t);
                }
            })
        }

        function details(id) {
            $.ajax({
                type: "GET",
                url: "/user/finditem/" + id,
                dataType: "json",
                success: function(response) {
                    $("#mod-caro").text("")
                    var images = response.images.split("|")
                    for (let i = 0; i < images.length; i++) {
                        $("#mod-caro").append(
                            `<a class="carousel-item" href="#one!"><img src="/${images[i]}"></a>`)
                    }
                    $('#mod-name').text(response.name)
                    $('#mod-price').text('Rs.' + response.price)
                    $('#mod-category').text(response.category)
                    $('#mod-details').text(response.details)
                    $('#mod-img1').attr('src', '/storage/media/' + response.img)
                    $('#mod-img2').attr('src', '/storage/media/' + response.img2)
                    $('#details').modal('open');
                    history.pushState(null, document.title, location.href);
                    $('.carousel.carousel-slider').carousel({
                        fullWidth: true
                    });
                }
            })
        }
        $(document).ready(function() {
            $('.carousel.carousel-slider').carousel({
                fullWidth: true
            });
        });
    </script>
@endsection

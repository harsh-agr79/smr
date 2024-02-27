@extends('layouts/customer')

@section('main')
    <script type="text/javascript">
        function preback() {
            window.history.forward();
        }
        setTimeout("preback()", 0);
        window.onunload = function() {
            null
        };
    </script>
    <style>
        .prod-admin-img {
            height: 10vh;
        }

        .prod-admin-title {
            font-size: 15px;
            font-weight: 600;
        }

        .prod-admin-det {
            font-size: 12px;
            padding: 10px;
            font-weight: 500;
        }

        .price-line {
            position: relative;
            top: 7px;
        }

        .prod-admin-price {
            padding: 3px 10px;
            background: rgb(0, 194, 0);
            border-radius: 5px;
            color: black;
        }

        .prod-admin-inp {
            color: black;
            outline: none;
            padding: 5px;
            border-radius: 5px;
            outline: none;
            border: 1px solid rgb(170, 170, 170);
            width: 10vh;
        }

        .prod-admin-container::-webkit-scrollbar {
            display: none;
        }

        .prod-admin-container {
            margin-left: 20vw;
            margin-right: 20vw;
            height: 65vh;
            overflow-y: scroll;
            margin-top: 10px;
        }

        @media screen and (max-width: 720px) {
            .prod-admin-container {
                margin: 0;
            }

            .mp-caro-item {
                height: 56vw;
                width: 100vw;
            }
        }

        @media screen and (max-width: 900px) {
            .mp-caro-item {
                height: 50vh;
                width: 100vw;
            }
        }
    </style>
    <form enctype="multipart/form-data" id="createform" action="{{ route('user.editorder') }}" method="post">
        @csrf
        <div class="mp-card" style="margin-top: 20px;">
            <div class="row" style="margin:0; padding: 0;">
                <input type="hidden" name="date" value="{{$order[0]->date}}">
                <input type="hidden" name="orderid" value="{{$order[0]->order_id}}">
                <div class="row col s12" style="margin:0; padding: 0;">
                    <div class="col s2" style="margin:0; padding: 5px;">
                        <div class="btn green accent-4 modal-trigger" href="#modal1"><i
                                class="material-icons">filter_list</i></div>
                    </div>

                    <div class='input-field col s8' style="margin:0; padding: 5px;">
                        <input class='validate browser-default inp search black-text z-depth-1' onkeyup="searchFun()"
                            autocomplete="off" type='search' id='search' />
                        <span class="field-icon" id="close-search"><span class="material-icons"
                                id="cs-icon">search</span></span>
                    </div>
                    <div class="col s2 center" style="margin:0; padding: 5px;">
                        <a class="btn green accent-4 modal-trigger" href="#cart"><i
                                class="material-icons">shopping_cart</i>
                        </a>
                    </div>

                    <div class="col s12 center" style="margin:0; padding: 0;">
                        Bill Amount: <span id="totalamt"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="cart" class="modal">
            <div class="modal-content bg-content">
                <div class="right">
                    Bill Amount: <span id="totalamt2"></span>
                </div>
                <div class="center">
                    <h5>Cart</h5>
                </div>
                <table>
                    <thead>
                        <th>Name</th>
                        <th>price</th>
                        <th class="center">Quantity</th>
                    </thead>
                    <tbody>
                        @foreach ($order as $item)
                            <tr id={{ $item->id . 'list' }}>
                                <td>{{ $item->item }}</td>
                                <td class="gtprice">{{ $item->price }}</td>
                                <td class="center"><input type="number" id="{{ $item->id . 'listinp' }}" name="quantity[]"
                                        inputmode="numeric" pattern="[0-9]*" placeholder="Quantity"
                                        class="browser-default prod-admin-inp gtquantity"
                                        onkeyup="changequantity2({{ $item->id }})"
                                        onchange="changequantity2({{ $item->id }})"
                                        onfocusout="changequantity2({{ $item->id }})" @if ($item->status == "approved" || $item->status == "rejected")
                                            readonly
                                        @endif value="{{$item->quantity}}"></td>
                                <input type="hidden" name="prodid[]" value="{{ $item->product_id }}">
                                <input type="hidden" name="id[]" value="{{ $item->id }}">
                            </tr>
                        @endforeach
                        @foreach ($data as $item)
                        <tr style="display: none;"id={{ $item->id . 'list' }}>
                            <td>{{ $item->name }}</td>
                            <td class="gtprice">{{ $item->price }}</td>
                            <td class="center"><input type="number" id="{{ $item->id . 'listinp' }}" name="quantity[]"
                                    inputmode="numeric" pattern="[0-9]*" placeholder="Quantity"
                                    class="browser-default prod-admin-inp gtquantity"
                                    onkeyup="changequantity2({{ $item->id }})"
                                    onchange="changequantity2({{ $item->id }})"
                                    onfocusout="changequantity2({{ $item->id }})"></td>
                            <input type="hidden" name="prodid[]" value="{{ $item->id }}">
                            <input type="hidden" name="id[]" value="newitem">
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-content">
                <a class="btn red modal-close">
                    Edit
                </a>
                <button class="btn amber" type="submit">
                    Submit
                </button>
            </div>
        </div>
    </form>
    <div id="modal1" class="modal">
        <div class="center">
            <h5>Filter By company and category</h5>
        </div>
        <div class="row" style="padding: 10px;">
            <div class="col s6">
                <form id="filformbrd">
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
                </form>
            </div>
        </div>
    </div>
    <div style="height: 65vh; overflow-y: scroll; margin-top: 10px;" class="prod-admin-container">
        @foreach ($order as $item)
            <div class="mp-card row prod-admin searchable {{ $item->brand_id }}brd {{ $item->category_id }}cat"
                style="margin: 3px; padding: 10px;">
                <div class="col s4" style="padding: 0;  margin: 0;">
                    <img src="{{ asset(explode('|', $item->images)[0]) }}" class="prod-admin-img materialboxed"
                        alt="">
                </div>
                <div class="col s8 row" style="padding: 0; margin: 0;">
                    <div class="col s12" style=" margin: 0; padding: 0;">
                        <span class="prod-admin-title">{{ $item->item }}</span>
                    </div>
                    <div class="col s12 row" style="padding: 0;  margin: 0;">
                        <span class="prod-admin-det col s6">{{ $item->brand }} {{ $item->category }}</span>
                        <span class="prod-admin-det col s6">
                            @if ($item->stock == 'on')
                                <span class="red-text right">Out of Stock</span>
                            @else
                                <span class="green-text right">In Stock</span>
                            @endif
                        </span>
                    </div>
                    <div class="row col s12 price-line valign-wrapper" style="padding: 0;  margin: 0;">
                        <div class="col s4 center"><span class="prod-admin-price">Rs.{{ $item->price }}</span></div>
                        <div class="col s8"><input type="number" id="{{ $item->id . 'viewinp' }}" inputmode="numeric"
                                pattern="[0-9]*" placeholder="Quantity" class="browser-default prod-admin-inp right"
                                onkeyup="changequantity({{ $item->id }})" onchange="changequantity({{ $item->id }})" value="{{$item->quantity}}" @if ($item->status == "approved" || $item->status == "rejected")
                                readonly
                            @endif></div>
                    </div>
                </div>
            </div>
        @endforeach
        @foreach ($data as $item)
            <div class="mp-card row prod-admin searchable {{ $item->brand_id }}brd {{ $item->category_id }}cat"
                style="margin: 3px; padding: 10px;">
                <div class="col s4" style="padding: 0;  margin: 0;">
                    <img src="{{ asset(explode('|', $item->images)[0]) }}" class="prod-admin-img materialboxed"
                        alt="">
                </div>
                <div class="col s8 row" style="padding: 0; margin: 0;">
                    <div class="col s12" style=" margin: 0; padding: 0;">
                        <span class="prod-admin-title">{{ $item->name }}</span>
                    </div>
                    <div class="col s12 row" style="padding: 0;  margin: 0;">
                        <span class="prod-admin-det col s6">{{ $item->brand }} {{ $item->category }}</span>
                        <span class="prod-admin-det col s6">
                            @if ($item->stock == 'on')
                                <span class="red-text right">Out of Stock</span>
                            @else
                                <span class="green-text right">In Stock</span>
                            @endif
                        </span>
                    </div>
                    <div class="row col s12 price-line valign-wrapper" style="padding: 0;  margin: 0;">
                        <div class="col s4 center"><span class="prod-admin-price">Rs.{{ $item->price }}</span></div>
                        <div class="col s8"><input type="number" id="{{ $item->id . 'viewinp' }}" inputmode="numeric"
                                pattern="[0-9]*" placeholder="Quantity" class="browser-default prod-admin-inp right"
                                onkeyup="changequantity({{ $item->id }})" onchange="changequantity({{ $item->id }})"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="fixed-action-btn">
        <a class="btn btn-large red modal-trigger" href="#cart" style="border-radius: 10px;">
            Order
            <i class="left material-icons">send</i>
        </a>
    </div>

    <script>
        getTotal();
        function changequantity(id) {
            var qval = $(`#${id}viewinp`).val();
            if (qval < 1 || qval == null) {
                $(`#${id}list`).hide();
                $(`#${id}listinp`).val('');
            } else {
                $(`#${id}list`).show();
                $(`#${id}listinp`).val(qval);
            }
            getTotal();
        }

        function changequantity2(id) {
            var qval = $(`#${id}listinp`).val();
            if (qval < 1 || qval == null) {
                if ($(`#${id}listinp`).is(":focus")) {
                    // console.log('focus')
                    // $(`#${id}list`).hide();
                    $(`#${id}listinp`).val('');
                    $(`#${id}viewinp`).val(qval);
                } else {
                    // console.log('notfocus')
                    $(`#${id}list`).hide();
                    $(`#${id}listinp`).val('');
                    $(`#${id}viewinp`).val(qval);
                }
            } else {
                $(`#${id}list`).show();
                $(`#${id}listinp`).val(qval);
                $(`#${id}viewinp`).val(qval);
            }
            getTotal()
        }

        function getTotal() {
            var price = $('.gtprice');
            var quantity = $('.gtquantity');
            var total = 0;
            for (let i = 0; i < price.length; i++) {
                if (quantity[i].value > 0) {
                    total = total + price[i].innerHTML * quantity[i].value;
                }
            }
            $('#totalamt').text(total);
            $('#totalamt2').text(total);
        }

        function Filter() {
            $('.prod-admin').hide()
            $('.prod-admin').removeClass('searchable');
            var formData = $('#filformbrd').serializeArray()
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
                    $('.prod-admin').addClass('searchable')
                    $('.prod-admin').show();
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
    </script>
@endsection

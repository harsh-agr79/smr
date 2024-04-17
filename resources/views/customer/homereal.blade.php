@extends('layouts/customer')

@section('main')
    <style>
        @media screen and (max-width: 720px) {
            .prod-container {
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

        .home-btn {
            /* width: 200px !important; */
            background: #00c853;
            color: black;
            border-radius: 10px;
            padding: 15px;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .home-btn i {
            margin-left: 3vw;
            color: black !important;
        }

        .home-btn:hover {
            background: #00e676;
        }

        .spc {
            transform: scale(1.04);
        }

        .mp-caro-item {
            height: 30vh;
            width: 100%;
        }

        .scroll-text {
            display: flex;
            flex-wrap: nowrap;
            white-space: nowrap;
            min-width: 100%;
            overflow: hidden;
        }

        .news-message {
            display: flex;
            flex-shrink: 0;
            height: 30px;
            align-items: center;
            animation: slide-left 15s linear infinite;
        }

        .news-message p {
            font-size: 1.5em;
            font-weight: 600;
            padding-left: 1em;
            color: var(--textcol);
        }

        @keyframes slide-left {
            from {
                -webkit-transform: translateX(0);
                transform: translateX(0);
            }

            to {
                -webkit-transform: translateX(-100%);
                transform: translateX(-100%);
            }
        }
    </style>

    <div class="row" style="padding: 0; margin: 0;">
        <div class="col l6 m12 s12" style="padding: 0; margin: 0;">
            <div class="mp-caro-cont">
                @for ($i = 0; $i < count($data); $i++)
                    <div class="mp-caro-item valign-wrapper @if ($i != 0) hide @endif"
                        style="background: url('{{ asset($data[$i]->image) }}'); background-size: cover; background-position: center; background-repeat: no-repeat; ">
                        <div style="width: 100vw;">
                            <div class="btn-floating left"
                                style="margin: 5px; background: rgba(0, 0, 0, 0.219); border-radius: 50%" onclick="prev()">
                                <i class="material-icons white-text center">arrow_back</i>
                            </div>
                            <div class="btn-floating right"
                                style="margin: 5px; background: rgba(0, 0, 0, 0.219); border-radius: 50%" onclick="next()">
                                <i class="material-icons white-text center">arrow_forward</i>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="scroll-text">
                <section class="news-message bg-content">
                    @foreach ($data2 as $item)
                        <p>{{ $item->message }}</p>
                    @endforeach
                </section>
                <section class="news-message bg-content">
                    @foreach ($data2 as $item)
                        <p>{{ $item->message }}</p>
                    @endforeach
                </section>
            </div>
        </div>
        <div class="col l6 m12 s12 row center" style="margin-top: 5px;">
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/createorder') }}" class="home-btn spc">Create A New Order<i
                        class="material-icons">add</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/oldorders') }}" class="home-btn">Previous Orders<i
                        class="material-icons">shopping_basket</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/savedorders') }}" class="home-btn">Saved Baskets<i class="material-icons">save</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/mainanalytics') }}" class="home-btn">Analytics<i
                        class="material-icons">equalizer</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/summary') }}" class="home-btn">Summary <i
                        class="material-icons">multiline_chart</i></a>
            </div>
            <div class="col s12" style="margin-top: 10px;">
                <a href="{{ url('user/statement') }}" class="home-btn">Statement <i class="material-icons">web</i></a>
            </div>
        </div>
        {{-- <div class="col l6 m12 s12 center hide-on-med-and-down" id="balpop-pc" onclick="closefunc()">
            @php
                $bal = explode('|', $user->balance);
            @endphp
            <div class="center mp-card">
                <div class="center amber white-text" style="border-radius: 10px; padding: 10px;">
                    @if ($bal[0] == 'red')
                        <h5>Amount To Pay: {{ money($bal[1]) }}</h5>
                    @else
                        <h5>Amount To Recieve: {{ money($bal[1]) }}</h5>
                    @endif
                </div>
                <div>
                    <table>
                        <thead>
                            <th>Outstanding Amount In Days</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>30 Days</td>
                                <td>{{ $user->thirdays }}</td>
                            </tr>
                            <tr>
                                <td>45 Days</td>
                                <td>{{ $user->fourdays }}</td>
                            </tr>
                            <tr>
                                <td>60 Days</td>
                                <td>{{ $user->sixdays }}</td>
                            </tr>
                            <tr>
                                <td>90 Days</td>
                                <td>{{ $user->nindays }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        
        </div> --}}
    </div>
@endsection

@if (time() - session()->get('USER_TIME') < 20)
    {{-- <div class="bal-popup hide-on-large-only" id="balpop" onclick="closefunc()">
    @php
        $bal = explode('|', $user->balance);
    @endphp
    <div class="center mp-card bal-popcard">
        <div class="center amber white-text" style="border-radius: 10px; padding: 10px;">
            @if ($bal[0] == 'red')
                <h5>Amount To Pay: {{ money($bal[1]) }}</h5>
            @else
                <h5>Amount To Recieve: {{ money($bal[1]) }}</h5>
            @endif
        </div>
        <div>
            <table>
                <thead>
                    <th>Outstanding Amount In Days</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td>30 Days</td>
                        <td>{{ $user->thirdays }}</td>
                    </tr>
                    <tr>
                        <td>45 Days</td>
                        <td>{{ $user->fourdays }}</td>
                    </tr>
                    <tr>
                        <td>60 Days</td>
                        <td>{{ $user->sixdays }}</td>
                    </tr>
                    <tr>
                        <td>90 Days</td>
                        <td>{{ $user->nindays }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div> --}}
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
    function closefunc() {
        $('#balpop').remove();
    }
    carousel()

    function carousel() {
        var caroItem = $('.mp-caro-item');
        var next = 0;
        for (let i = 0; i < caroItem.length; i++) {
            if (!caroItem[i].classList.contains('hide')) {
                var next = i + 1;
                if (next > caroItem.length - 1) {
                    var next = 0;
                }
            }
        }
        $('.mp-caro-item').addClass('hide');
        caroItem[next].classList.remove('hide');
        setTimeout(carousel, 5000);
    }

    function next() {
        var caroItem = $('.mp-caro-item');
        var next = 0;
        for (let i = 0; i < caroItem.length; i++) {
            if (!caroItem[i].classList.contains('hide')) {
                var next = i + 1;
                if (next > caroItem.length - 1) {
                    var next = 0;
                }
            }

        }
        $('.mp-caro-item').addClass('hide');
        caroItem[next].classList.remove('hide');
    }

    function prev(i) {
        var caroItem = $('.mp-caro-item');
        var next = 0;
        for (let i = 0; i < caroItem.length; i++) {
            if (!caroItem[i].classList.contains('hide')) {
                var next = i - 1;
                if (next == -1) {
                    var next = caroItem.length - 1;
                }
            }
        }
        $('.mp-caro-item').addClass('hide');
        caroItem[next].classList.remove('hide');
    }
</script>

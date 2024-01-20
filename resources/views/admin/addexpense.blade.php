@extends('layouts/admin')

@section('main')
    <div>
        <div class="mp-card" style="margin-top: 5vh;">
            <div>
                <h6 class="center">Expense</h6>
            </div>
            <form action="{{route('addexp')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col s12 row">
                        <div class="col s6">
                            date:
                        </div>
                        <div class="col s6">
                            <input type="date" value="{{ $date }}" name="date"
                                class="inp black-text browser-default" placeholder="Date" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Name:
                        </div>
                        <div class="input-field col s6" style="margin-top: 0;">
                            <input type="text" name="name" value="{{$name}}" id="customer" placeholder="Customer"
                                class="autocomplete browser-default inp black-text" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Amount:
                        </div>
                        <div class="col s6">
                            <input type="number" name="amount" value="{{$amount}}" class="inp black-text browser-default"
                                placeholder="Amount" required>
                        </div>
                    </div>
                    <div class="col s12 row">
                        <div class="col s6">
                            Particular:
                        </div>
                        <div class="col s6">
                            <input type="text" name="particular" value="{{$particular}}" class="inp black-text browser-default"
                                placeholder="Particular">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="expid" value="{{$expid}}">
                <div class="fixed-action-btn">
                    <button class="btn btn-large red" onclick="M.toast({html: 'Please wait...'})"
                        style="border-radius: 10px;">
                        Submit
                        <i class="left material-icons">send</i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'get',
                url: '{!! URL::to('findcustomer') !!}',
                success: function(response2) {

                    var custarray2 = response2;
                    var datacust2 = {};
                    for (var i = 0; i < custarray2.length; i++) {

                        datacust2[custarray2[i].name] = null;
                    }
                    // console.log(datacust2)
                    $('input#customer').autocomplete({
                        data: datacust2,
                    });
                }
            })
        })
    </script>
@endsection

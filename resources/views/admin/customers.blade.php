@extends('layouts.admin')

@section('main')
    <div>
        <h5 class="center">Customers List</h5>

        <div class="mp-card">
            <table>
                <thead>
                    <th>SN</th>
                    <th>DP</th>
                    <th>Name</th>
                    <th>Shopname</th>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Contact</th>
                </thead>
                <tbody>
                    @php
                        $a = 0;
                    @endphp
                    @foreach ($data as $item)
                        <tr  oncontextmenu="rightmenu({{ $item->id }}); return false;">
                            <td>{{$a = $a + 1}}</td>
                            <td>
                                @if ($item->profileimg == NULL)
                                <img src="{{asset('images/user.png')}}" style="border-radius:50%; height: 60px;" alt="">
                                @else
                                <img src="{{asset($item->profileimg)}}" style="border-radius:50%; height: 60px;" alt="">
                                @endif</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->shopname}}</td>
                            <td>{{$item->userid}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->address}}</td>
                            <td>{{$item->contact}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="rightmenu" class="rmenu">
        <ul>
            <a id="rmeditlink">
                <li>Edit</li>
            </a>
            <a id="rmdeletelink">
                <li>Delete</li>
            </a>
        </ul>
    </div>
    <script>
         function rightmenu(id) {
            // console.log(orderid)
            var rmenu = document.getElementById("rightmenu");
                rmenu.style.display = 'block';
                rmenu.style.top = mouseY(event) + 'px';
                rmenu.style.left = mouseX(event) + 'px';
                $('#rmeditlink').attr('href', "/customers/edit/"+id);
                $('#rmdeletelink').attr('href', "/customers/delete/"+id);
        }

        $(document).bind("click", function(event) {
            var rmenu = document.getElementById("rightmenu");
            rmenu.style.display = 'none';
        });

        function mouseX(evt) {
            if (evt.pageX) {
                return evt.pageX;
            } else if (evt.clientX) {
                return evt.clientX + (document.documentElement.scrollLeft ?
                    document.documentElement.scrollLeft :
                    document.body.scrollLeft);
            } else {
                return null;
            }
        }

        // Set Top Style Proparty
        function mouseY(evt) {
            if (evt.pageY) {
                return evt.pageY;
            } else if (evt.clientY) {
                return evt.clientY + (document.documentElement.scrollTop ?
                    document.documentElement.scrollTop :
                    document.body.scrollTop);
            } else {
                return null;
            }
        }
    </script>
@endsection
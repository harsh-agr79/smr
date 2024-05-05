<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <style>
        td {
            padding: 2px;
            font-size: 10px;
            font-weight: 600;
        }

        .cont {
            margin-left: 30vw;
            margin-right: 30vw;
        }

        @media screen and (max-width: 1100px) {
            .cont {
                margin: 0;
            }
        }
    </style>

    <div id="invoice" style="padding: 10px;">
        <table>
            <thead>
                <th>Name</th>
                <th>Brand</th>
                <th>category</th>
                <th>Image</th>
                <th>Price</th>
                <th>Details</th>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->brand}}</td>
                        <td>{{$item->category}}</td>
                        @php
                            $a = explode('|', $item->images);
                        @endphp
                        <td><img src="@if ($item->images != '' || $item->images != null) {{ asset(explode('|', $item->images)[count($a) - 1]) }}@else{{ asset('images/prod.jpg') }} @endif" style="height: 150px;"  alt=""></td>
                        <td>{{$item->price}}</td>
                        <td><div style="white-space: pre-wrap">{{$item->details}}</div></td>
                    </tr>
                   
                @endforeach
            </tbody>
           </table>
    </div>



    <!--JavaScript at end of body for optimized loading-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script>
         $(document).ready(function() {
            var inoice = $('#invoice');
            html2pdf(invoice, {
                filename: 'catalog.pdf',
                pagebreak: {mode: ['avoid-all', 'css', 'legacy'] }
            });
            setTimeout(function() { window.close() }, 5000);
        })
    </script>
</body>

</html>


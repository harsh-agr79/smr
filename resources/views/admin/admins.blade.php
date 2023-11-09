@extends('layouts.admin')

@section('main')
    <div>
        <div class="right">
            <span class="btn green darken-3" onclick="$('#addmodal').modal('open')">Add Admin</span>
        </div>
        <div class="center">
            <h4>Admins List</h4>
        </div>
        <div class="mp-card">
            <table>
                <thead>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Id</th>
                    <th>Type</th>
                </thead>
                <tbody id="admin-tbody">
                    @foreach ($data as $item)
                        <tr>
                            <td >{{ $item->name }}</td>
                            <td >{{ $item->email }}</td>
                            <td >{{ $item->userid }}</td>
                            <td >{{ $item->type }}</td>
                            <td><span onclick="editadmin({{$item->id}})" class="green darken-3 white-text btn-small"><i class="material-icons">edit</i></span></td>
                            <td><span onclick="deladmin({{$item->id}})" class="red btn-small white-text"><i class="material-icons">delete</i></span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="editmodal" class="modal">
        <div class="modal-content">
            <h5 class="center">Edit Details of: <span id="edtitle"></span></h5>
            <form id="editadmin">
                <div class="row">
                    <div class="col s12"><label>Name :</label><input id="edname" name="name" type="text"
                            class="browser-default inp" placeholder="Name" required></div>
                    <div class="col s12"><label>Email :</label><input id="edemail" name="email" type="text"
                            class="browser-default inp" placeholder="Email" required></div>
                    <div class="col s12"><label>User ID :</label><input id="eduserid" name="userid" type="text"
                            class="browser-default inp" placeholder="User ID" required></div>
                    <div class="col s12"><label>Password :</label><input id="edpassword" name="password" type="password"
                            class="browser-default inp" placeholder="Edit Password"></div>
                    <input type="hidden" name="id" id="edid">
                    <div class="col s12 center" style="margin-top: 20px;">
                        <button class="btn green darken-3">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="addmodal" class="modal">
        <div class="modal-content">
            <h5 class="center">Add Admin</h5>
            <form id="addadmin">
                <div class="row">
                    <div class="col s12"><label>Name :</label><input id="adname" name="name" type="text"
                            class="browser-default inp" placeholder="Name" required></div>
                    <div class="col s12"><label>Email :</label><input id="ademail" name="email" type="text"
                            class="browser-default inp" placeholder="Email" required></div>
                    <div class="col s12"><label>User ID :</label><input id="aduserid" name="userid" type="text"
                            class="browser-default inp" placeholder="User ID" required></div>
                    <div class="col s12"><label>Password :</label><input id="adpassword" name="password" type="password"
                            class="browser-default inp" placeholder="Create Password" required></div>
                    <div class="col s12 center" style="margin-top: 20px;">
                        <button class="btn green darken-3">ADD</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function getadmindata(){
            $.ajax({
                url: "/admin/getadmindata",
                type: "GET",
                success: function(response){
                    // console.log(response);
                    $('#admin-tbody').text('');
                    $.each(response, function(key, item){
                        $('#admin-tbody').append(`
                        <tr>
                            <td>${item.name}</td>
                            <td>${item.email}</td>
                            <td>${item.userid}</td>
                            <td>${item.type}</td>
                            <td><span onclick="editadmin(${item.id})" class="green darken-3 white-text btn-small"><i class="material-icons">edit</i></span></td>
                            <td><span onclick="deladmin(${item.id})" class="red btn-small white-text"><i class="material-icons">delete</i></span></td>
                        </tr>
                        `)
                    })
                }
            })
        }
        function editadmin(id) {
            $('#editmodal').modal('open');
            $.ajax({
                url: "/admin/getdata/" + id,
                type: "GET",
                success: function(response) {
                    // console.log(response);
                    $('#edname').val(response.name)
                    $('#edtitle').text(response.name)
                    $('#edemail').val(response.email)
                    $('#eduserid').val(response.userid)
                    $('#edid').val(response.id)
                }
            })
        }

        $('#editadmin').on('submit', (e) => {
            e.preventDefault();
            let formData = new FormData($("#editadmin")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/admin/editadmin",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    M.toast({html: 'Admin Updated!'});
                    getadmindata()
                    $('#editmodal').modal('close');
                    $('#edname').val('')
                    $('#edtitle').text('')
                    $('#edemail').val('')
                    $('#eduserid').val('')
                    $('#edpassword').val('')
                    $('#edid').val('')
                }
            })
        })
        $('#addadmin').on('submit', (e) => {
            e.preventDefault();
            let formData = new FormData($("#addadmin")[0]);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "/admin/addadmin",
                data: formData,
                contentType: false,
                processData: false,
                type: "POST",
                success: function(response) {
                    M.toast({html: 'Admin Added!'});
                    getadmindata()
                    $('#addmodal').modal('close');
                    $('#adname').val('')
                    $('#ademail').val('')
                    $('#aduserid').val('')
                    $('#adpassword').val('')
                },
                error: function(error){
                    M.toast({html: error.responseJSON.message})
                }
            })
        })
        function deladmin(id){
            $.ajax({
                url: "/admin/deladmin/"+id,
                type: "GET",
                success: function(response){
                    M.toast({html: response});
                    getadmindata();
                }
            })
        }
    
    </script>
@endsection

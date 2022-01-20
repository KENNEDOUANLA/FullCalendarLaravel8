<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ url('css/app.css') }}">
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <title>NEW EVENT</title>
</head>
<body>
    <div class="popup-box" id="form">
        <div class="box">
            <span class="close-icon" onclick="myFunction()">X</span>
            <form action="javascript:;" onsubmit="gestionForm(this)" >
                <label for="title">Titre :</label><br>
                <input type="text"  class="title" id="title"><br><br>
                <label for="description">Description :</label><br>
                <textarea name="description" id="description" cols="45" rows="15"></textarea><br><br>
                <div class="time">
                    <div>
                    <label for="startTime">startTime :</label><br>
                    <input type="time"   id="startTime">
                    </div>
                    <div>
                    <label for="endtime" >endTime :</label><br>
                    <input type="time" id='endtime'>
                    </div>
                    <input type="submit" value="valider" class="submit">
                </div>    
                
            </form>
        </div>
    <div>    
</body>
</html>





<script>
    const date = window.location.href.split("=")[1];    
function myFunction() {
    window.location = "/calendar";
}

function gestionForm(form){
    
    const title=document.getElementById("title").value;
    const description=document.getElementById("description").value;
    let startTime=document.getElementById("startTime").value;
    let endTime=document.getElementById("endtime").value;

    $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });
    if(title && description && startTime && endTime){
        startTime=date+"T"+startTime+":00"
        endTime=date+"T"+endTime+":00"
        $.ajax({
            type: "POST",
            url: "{{ route('create') }}",
            data: {title:title,description:description,start:startTime,end:endTime},
            success: function (response) {
                window.location = "/calendar";
            }
        });
    }
}
</script>

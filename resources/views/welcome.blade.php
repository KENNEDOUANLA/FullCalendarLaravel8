<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8' />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href='css/main.css' rel='stylesheet' />
    <link href='css/app.css' rel='stylesheet' />
    <script src='js/main.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<body>

<div id='calendar'></div>
</body>
</html>


<script>
        document.addEventListener('DOMContentLoaded', function () {
            var CALENDARID='{{env('CALENDARID')}}';
            var APIKEY='{{env('APIKEY')}}';
            var calendarEl = document.getElementById('calendar');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var calendar = new FullCalendar.Calendar(calendarEl, {
                googleCalendarApiKey:APIKEY ,
                initialView: 'dayGridMonth',
                editable: true,
                selectable: true,
                headerToolbar: { center: 'title', left: 'dayGridMonth,timeGridWeek,timeGridDay' }, // buttons for switching between views
                events: {
                    googleCalendarId: CALENDARID
                },
                //creation de tache.
                dateClick: (date)=> {
                    window.location = "/newevent/date="+date.dateStr;
                },
                //redimentionemt de tache.
                eventResize: (event) => UPDATE(event),
                //supprimer
                eventClick: (event) => {
                    event.jsEvent.preventDefault();
                    if(confirm("Cliquez Sur OK Pour supprimer "+event.event._def.title+"   Description : "+event.event.extendedProps.description))
                    $.ajax({
                        type: "POST",
                        url: "{{ route('delete') }}",
                        data: {id:event.event._def.publicId},
                        success: function (response) {
                            window.location = "/calendar";
                        }
                    });
                },
                //deplacer
                eventDrop: (event) => UPDATE(event)
            });
            calendar.render();
        });

    </script>
<script>
    const UPDATE=(NewEvent)=>{
        console.log(NewEvent.event._def.publicId);
        const startdateTime=formatDate(NewEvent.event._instance.range.start,
                                        'YYYY-MM-DDTHH:MM:SS');
        const enddateTime=formatDate(NewEvent.event._instance.range.end,
                                        'YYYY-MM-DDTHH:MM:SS');
        $.ajax({
            type: "POST",
            url: "{{ route('update') }}",
            data: {id:NewEvent.event._def.publicId,start:startdateTime,end:enddateTime},
            success: function (response) {
                window.location = "/calendar";
            }
        });
    }

    const formatDate=(d)=>{
        console.log(d);
        var datestring = d.getFullYear()+ "-" + (d.getMonth()+1) + "-"+ (d.getDate()) +
                        "T" + (d.getHours()-1) + ":" + d.getMinutes()+":"+d.getSeconds();
        return datestring;
    }
</script>
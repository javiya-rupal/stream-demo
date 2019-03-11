@extends('layouts.app')

@section('content')
@php
    $i = 1;
@endphp
<div id="dialog-success" class="hide" title="Stream saved!">
    <p>Congratulations, your favorite stream is set now! enjoy its events and chat!</p>
</div>
<div id="dialog-fail" class="hide" title="Stream save failed!">
    <p>Sorry, select another stream. there's issue in setting up your favorite!</p>
</div>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-body">
                <h1>Set your favorite stream</h1>
                    @foreach($streams as $stream)
                      <div class="row">
                            <div class="col-md-4 vertical-align">
                              <h5>{{ $i }}. {{ $stream['channel']['display_name'] }}</h5>
                            </div>
                            <div class="col-md-5">
                              <b>Followers:</b> {{ $stream['channel']['followers'] }}<br />
                              <b>Views:</b> {{ $stream['channel']['views'] }}<br />
                              <img src="{{ $stream['channel']['logo'] }}" height="150" width="150">
                            </div>
                            <div class="col-md-3 vertical-align">
                            <span onclick="$('#cover-spin').show(0)" class="stream" data-channel-name="{{ $stream['channel']['name'] }}" data-channel-id="{{ $stream['channel']['_id'] }}"></span>
                          </div>
                      </div>
                      @php
                        $i++;
                      @endphp
                    @endforeach
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
  //Display view details button if stream is already added in favorite list
  var channels = [];
  if (localStorage.getItem("favorites") !== null) {
    channels = JSON.parse(localStorage.getItem("favorites"));
  }
  $(".stream").each(function( index, element ) {
    var channelId = $(this).data("channelId");
    if (jQuery.inArray( channelId, channels ) >= 0) {
      $(this).removeClass("stream");
      $(this).html('<input type="button" class="btn view-btn" onclick="location.href=\'stream/'+$(this).data("channelName")+'\';" value="Watch Now!">');
    }
    else {
      $(this).html('<input type="button" class="btn" value="Add To Favorite">');
    }
  });

  // Handle success/fail dialogs after adding stream as favorite
  $( "#dialog-success" ).dialog({
      autoOpen: false,
      modal: true,
      buttons: {
        Ok: function() {
          $( this ).dialog( "close" );
        }
      }
    });
  $( "#dialog-fail" ).dialog({
      autoOpen: false,
      modal: true,
      buttons: {
        Ok: function() {
          $( this ).dialog( "close" );
        }
      }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Handle add to favorite action on stream
    $(".stream").click(function() {
        var channelId = $(this).data("channelId");
        var channelName = $(this).data("channelName");
        var currentStream = $(this);

        $.ajax({
           type:'POST',
           url:"{{ \URL::to('ajaxRequest')}}",
           data:{channel_id:channelId},
           success:function(response) {
              $("#cover-spin").hide();
              if (response.success === true) {
                if (response.status_code === 200) {
                    //Stored favotires
                    var channel1 = channelId;
                    channels.push(channel1);
                    localStorage.setItem("favorites", JSON.stringify(channels));                    

                    currentStream.html('<input type="button" class="btn view-btn" onclick="location.href=\'stream/'+channelName+'\';" value="Watch Now!">');
                    $("#dialog-success").removeClass("hide").dialog('open');
                }
                else {
                    $("#dialog-fail").removeClass("hide").dialog('open');
                }
              }
           }
        });
    })
});
</script>
@endsection


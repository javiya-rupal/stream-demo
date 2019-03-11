@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>You are viewing <b>{{ $channelName }}</b> </h2>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-3">
            <h3 class="mr-t-25"><a href="{{ \URL::to('/')}}"><< Back to stream listing</a></h2>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row justify-content-center">        
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12" style="text-align: center;">                            
                  <!-- Add a placeholder for the Twitch embed -->
                    <div id="twitch-embed"></div>

                    <!-- Load the Twitch embed script -->
                    <script src="https://embed.twitch.tv/embed/v1.js"></script>

                    <!-- Create a Twitch.Embed object that will render within the "twitch-embed" root element. -->
                    <script type="text/javascript">
                      new Twitch.Embed("twitch-embed", {
                        width: 1280,
                        height: 560,
                        channel: "<?php echo $channelName ?>"
                      });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

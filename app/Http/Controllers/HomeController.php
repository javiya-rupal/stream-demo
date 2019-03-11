<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use TwitchApi\TwitchApi;
use TwitchApi\Api\Streams;
use NewTwitchApi\NewTwitchApi;
use NewTwitchApi\HelixGuzzleClient;

class HomeController extends Controller
{
    protected $client_id;

    protected $client_secret;

    protected $scopes;

    protected $state;

    protected $redirect_uri;

    protected $twitchApi;

    protected $newTwitchApi;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client_id = env('TWITCH_CLIENT_ID', 'uo6dggojyb8d6soh92zknwmi5ej1q2');
        
        $this->client_secret = env('TWITCH_SECRET', 's3cRe7');

        $this->scopes = [
                'channel:moderate',
                'chat:edit',
                'chat:read',
                'whispers:read',
                'whispers:edit'];
        $this->state = true;

        $this->redirect_uri = env('TWITCH_REDIRECT_URI', 'http://streamtest.com');
        
        // Instantiate NewTwitchApi. Can be done in a service layer and injected as well.
        $helixGuzzleClient = new HelixGuzzleClient($this->client_id);        
        $this->newTwitchApi = new NewTwitchApi($helixGuzzleClient, $this->client_id, $this->client_secret);

        //Way to create an object for work with v5 old API
        $options = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'scopes' => $this->scopes,
            'state' => $this->state,
            'api_version' => 5,
        ];
        $this->twitchApi = new \TwitchApi\TwitchApi($options);
    }

    /**
     * Redirect to Twitch login
     */
    public function redirectToProvider()
    {        
        return redirect($this->newTwitchApi->getOauthApi()->getAuthUrl($this->redirect_uri, 'code', implode(' ', $this->scopes), false, $this->state));
    }

    /**
     * Handle callback of Twitch login
     */
    public function handleProviderCallback(Request $request)
    {
        // Request Twitch token from Twitch
        $code = $request->input('code');

        //Get user access token
        $response = $this->newTwitchApi->getOauthApi()->getUserAccessToken($code, $this->redirect_uri, $this->state);
        $responseData = json_decode($response->getBody()->getContents());

        $access_token = $responseData->access_token;
        $request->session()->put('accessToken', $access_token);

        try {
            // Make the API call. A ResponseInterface object is returned.
            $response = $this->newTwitchApi->getUsersApi()->getUserByAccessToken($access_token);
        } catch (GuzzleException $e) {
            // Handle error appropriately for your application
        }

        // Get and decode the actual content sent by Twitch.
        $responseContent = json_decode($response->getBody()->getContents());        
        $request->session()->put('userId', $responseContent->data['0']->id);
        $request->session()->put('userName', $responseContent->data['0']->login);
      
        //Redirect user back to home page
        return redirect()->action('HomeController@index');
    }

    /**
     * Show the application landing page/stream listing
     */
    public function index(Request $request)
    {
        if ($request->session()->has('accessToken')) {
            $all_streams_data = $this->twitchApi->getLiveStreams();
            return view('index', ["streams" => $all_streams_data["streams"]]);
        }
        else {            
            return view('welcome');
        }
    }  

    /**
     * Method to handle ajax for webhook subscription
     *
     * @return json
     */
    public function ajaxRequestPost(Request $request)
    {
        $channel_id = $request->get('channel_id');

        //Subscribe user for an events on stream
        $subscriptionResponse = $this->newTwitchApi->getWebhooksSubscriptionApi()->subscribeToStream($channel_id, $request->session()->get('accessToken'), \URL::to("twitch/event/callback/".time()), 45000);

        return response()->json(['success'=>true, 'data'=>null, 'status_code' => 200]);
    }

    /**
     * Callback for webhook event subscription
     */
    public function webhookEventSubscribeCallback(Request $request) {
        echo $_GET['hub.challenge'];
    }

    /**
     * Dispaly stream detail page with video and chat
     */
    public function readStream($channelName, Request $request) {
        if (!$request->session()->has('accessToken')) {
            return redirect()->action('HomeController@index');
        }
        return view('stream', ["channelName" => $channelName]);
    }
}

<?php

return [
    'client_id' => env('TWITCH_KEY', 'xjpk6w9chdsj7abmis10z833z6ivd5'),
    'client_secret' => env('TWITCH_SECRET', '5nr4nj28olu627gkl34lz9e9fnf3fe'),
    'redirect_url' => env('TWITCH_REDIRECT_URI', 'http://127.0.0.1/stream/public'),
    'scopes' => [
        'channel_check_subscription',
        'channel_commercial',
        'channel_editor',
        'channel_feed_edit',
        'channel_feed_read',
        'channel_read',
        'channel_stream',
        'channel_subscriptions',
        'chat_login',
        'collections_edit',
        'communities_edit',
        'communities_moderate',
        'openid',
        'user_blocks_edit',
        'user_blocks_read',
        'user_follows_edit',
        'user_read',
        'user_subscriptions',
        'viewing_activity_read'
    ],
];
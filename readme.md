# stream-demo
Basic implementation of Twitch API, It include features likes login on Twitch, Stream listing, Stream event subscription, Embedding live stream with Chat etc.

## Installation
* Clone git repository in your server
* Copy .env.example and create new file named .env in root folder.
* Set Twitch API credentilas on .env file and do other configuration parameters set.
* Set write permission to storage folder.
* In Console, go to your project directory, install composer and run command "composer install".
* Now Run the project in your machine e.g. http://localhost/stream-demo
* This will display you welcome page with "Login" to twitch link.

## Write me if you have any queries
* Write to rupal.javiya@gmail.com

## Live application link
http://rupaljaviya.com/stream-demo/

## Integrated Twitch API:
* Twitch Login with new Twitch API (https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/#oauth-authorization-code-flow).
* Twitch get streams API Integration (https://dev.twitch.tv/docs/v5/reference/streams/#get-live-streams) Its using old kraken API because with new twitch API, we don't able to get all detailed information like channel name on response.
* Twitch webhook API integration (https://dev.twitch.tv/docs/api/webhooks-guide/). which sends an email to user when any events happends on subscribed stream.
![Twitch notification email](https://github.com/javiya-rupal/stream-demo/blob/master/public/docs/twitch-notification.png)
* Twitch API for embed live stream on page with chat.

## How would you deploy the above on AWS? (ideally a rough architecture diagram will help)
* For this application, I created free tier account on AWS.
* Created an instance on AWS and deployed my application on it as mentioned with above steps.

![Deployment Diagram](https://github.com/javiya-rupal/stream-demo/blob/master/public/docs/AWS-deploy.png)

## Where do you see bottlenecks in your proposed architecture and how would you approach scaling this app starting from 100 reqs/day to 900MM reqs/day over 6 months?
* I can see I have an API integration which will run in background, the architecture is fesiable currently to handle limited number of users requests on application. so I can see the bottleneck is that when there will be pick time and high flow on application, at that time application may run slow or not able to handle request.
* To solve this, I will use few of the good concept/features of AWS server and below is the rough diagram of same which may help to serve concurrent user request from server.

![Scale Application Approach](https://github.com/javiya-rupal/stream-demo/blob/master/public/docs/AWS-scale.png)

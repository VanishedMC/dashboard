# Dashboard

### About this project
This project aims to combine various services into one convenient dashboard. Instead of using different third party services for image hosting, file hosting, text pastes, url shortening and so on, everything is available in one place.  
I am making this mostly for myself, though anyone is free to use this as they wish. A live production version of this site is hosted [here](http://vanishedmc.com).  
This project is using Laravel for the backend, and VueJS for the frontend. Not making use of any css framework, as one of my main reasons for developing this is to challenge my front-end skills.  
<br/>
The authentication uses discord, for a few reasons
* I don't have to bother handling with accounts
* It allows me to integrate discord features, such as setting a reminder which is sent to your DMs
* I personally use discord a lot, so its convenient for me

#### Setup
If you wish to deploy an instance of this suite for yourself, you can do so rather easily
* clone the repository
* install laravel dependencies with ```composer install```
* install npm dependencies with ```npm install```
* copy the `.env.example` file to `.env`, and fill everything in
  * App name, environment, url
  * Database login details
  * `SITE_URL` is the url returned for uploads made through the api (in my personal case the dashboard is hosted on `http://vanishedmc.com`, while the url for screenshots returned in `http://stu.pidme.me`. This can also be set to `${APP_URL}`)
  * Pusher app details, for the real-time notifications and updating
  * Discord OAuth details, for authentication
* compile VueJS with ```npm run prod```
* Run at least 2 laravel queue listeners with ```php artisan queue:listen``` and ```php artisan queue:listen --queue=high``` (the normal queue is used for youtube downloading, the `high` queue is used for notifications)
* if you want to make use of the youtube downloader, make sure you have [youtube-dl](https://github.com/ytdl-org/youtube-dl/) installed

#### Contact
If you want to ask me any qeustions about this project, you can reach me on discord `VanishedMC#6507`
<br/><br/>

#### Please note
This site is in development, and even though I am using the master branchs, there will be bugs / issues. Any use is of your own risk
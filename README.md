# !!ARCHIVED!!
Laravel has native Slack logging intergrations that are better maintained. **Use those!**

# Slacker-Logs
Provides interface to Slack chat from Laravel/Lumen logging.
# Installation
## Download
### Via Composer
To install via Composer first make sure Composer is installed.  
Then need to simply run the command to require the package.  
`composer require exposuresoftware/slackerlogs`
## Register with the Container
In order to make using this package you are only required to write a very simple class and register it
with your application.
### Create a Provider
You `Provider` class can be named whatever you wish but **must** extend the `ExposureSoftware\SlackLogs\Providers\LoggerProvider`
class.  
This can be as simple as
```
<?php

namespace App\Providers;

use ExposureSoftware\SlackLogs\Providers\LoggerProvider;

class SlackLogProvider extends LoggerProvider {
    protected $channel = ...;
    protected $user = ...;
    protected $hook = ...;
    protected $level = ...;
}
```
Where each of the values are set to to the values for your application.  
`$channel` should be the channel name you wish to direct logs to. It should include the `#` that precedes all Slack 
channels.  
`$user` is the user that the message will appear to come from. This does not have to be an actual user on your team.  
`$hook` is the [webhook](https://api.slack.com/incoming-webhooks) your integration will use. Please see the Slack 
documentation in the link regarding how to set this up and get your webhook.  
`$level` is an the integer value of the log level you wish to report. This and any higher log levels will be sent to
Slack. See `Monolog\Logger` for these values and constants available.  
The available log levels are:
> DEBUG     = 100  
> INFO      = 200  
> NOTICE    = 250  
> WARNING   = 300  
> ERROR     = 400  
> CRITICAL  = 500  
> ALERT     = 550  
> EMERGENCY = 600  

### Register the Provider with the application
Add the line to your providers array as follows:
```
'providers' => [
   ...,
   'App\Providers\SlackLogProvider'
},
```
Changing the fully namespaced class to the name of your `Provider`.

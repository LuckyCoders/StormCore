# StormCore (PHP)

![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)

# PHP Framework 

  - Work with DB (SQL)
  - User crypto encryptop/decryptor
  - Logger
  - Routing 
  - Template manager
  - Telegram notifications (add-on module)
  - Localization manager (add-on module)


### Installation

StormCore requires PHP 5+ to run.
Install the dependencies (composer.json) and go!

```sh
 cd StormCore
 composer install
```

### Use

1. Set project settings in `./app/settings.php`
2. Start create/edit template `./templates/site`
3. Add some php code in middleware script file `./app/app.php` _(processing before template generation)_
4. It's work!

### Plugins

Will be described later.
You can connect/remove it by throwing in `./engine/libs`

| Plugin | File |
| ------ | ------ |
| Telegram Notification | SC_TelegramAPI.php |



### Development

Want to contribute? Great!


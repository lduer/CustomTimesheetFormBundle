# CustomTimesheetFormBundle

A Kimai 2 plugin, which adds/removes form fields to the timesheet form.

## Installation

First clone it to your Kimai installation `plugins` directory:
```
cd /kimai/var/plugins/
git clone https://gitlab.com/lduer/CustomTimesheetFormBundle.git
```

Install the assets (a JS file is loaded via `src`)
```
cd /kimai/
$ bin/console assets:install --symlink
``` 

And then rebuild the cache: 
```
bin/console cache:clear
bin/console cache:warmup
```

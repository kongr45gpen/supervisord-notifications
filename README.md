# supervisord-notifications
PHP script that feed notifications from supervisord events into various services

## Supported links
- IRC via the [supybot-github](https://github.com/kongr45gpen/supybot-github) plugin
- Mattermost via [incoming webhooks](https://docs.mattermost.com/developer/webhooks-incoming.html)

## Usage
1. Edit the files you want, according to your specifications, after setting up your wanted link.

2. Clone the repository:
```bash
git clone https://github.com/kongr45gpen/supervisord-notifications.git
```

3. Add the following instructions to your `supervisord.conf`:

```ini
[eventlistener:notifications]
command=php --define display_errors="stderr" /path/to/event.php
redirect_stderr=true
events=PROCESS_STATE
```

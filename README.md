# Silex Thumbnail So Example

## Launch the Demo Application

1. copy .env.example to .env
```
cp .env.example .env
```

2. Add environment variables.

```
AWS_DEFAULT_REGION=""
AWS_BUCKET=""
AWS_ACCESS_KEY_ID=""
AWS_SECRET_ACCESS_KEY=""
DROPBOX_CLIENT_ID=""
DROPBOX_CLIENT_SECRET=""
DROPBOX_ACCESS_TOKEN=""

```

3. Run Application

```
composer update

php -S localhost:8080 -t web web/index_dev.php

//or
php -S localhost:8080 -t web web/index.php

```
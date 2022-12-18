# mastodon-poster

Create status posts on Mastodon via REST API.

## Setup

Copy `.env.dist` as `.env.local` and populate with values from https://YOUR_MASTODON_SERVER/settings/applications

```
composer install
bin/console mastodon:post 'Your status post via REST API'
```

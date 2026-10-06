# Deploy TreeLink on Vercel

TreeLink is Laravel/PHP. Vercel can run it through the `vercel-php` community runtime configured in `vercel.json`. This is not an official Vercel runtime, so keep the runtime version pinned and verify preview deployments after upgrades.

## Security blocker

The current `composer.lock` uses Laravel 11, and `composer audit` reports a high-severity Laravel advisory (along with other advisories). The current `^11.9` constraint prevents installing the patched major version. Do not route public production traffic to this deployment until Laravel is upgraded to a maintained, patched version and the application is retested.

## 1. Import the correct folder

Connect the Git repository to Vercel. If the repository root contains this project inside a `linkbio` folder, set **Root Directory** to `linkbio`. The selected root must contain `artisan`, `composer.json`, `package.json`, and `vercel.json`.

Use these project settings:

- Framework Preset: Other
- Build Command: `npm run build`
- Output Directory: `public`
- Install Command: leave automatic

The PHP runtime installs Composer dependencies for the PHP function. Do not add `vendor` to Git.

## 2. Create production services

Vercel functions are stateless and their filesystem is read-only except for temporary files. Before deploying, create:

- A managed MySQL database reachable from Vercel, such as a managed MySQL service. Do not use `127.0.0.1` or the Laragon database host.
- An S3-compatible object bucket for profile photos, product images, and background images. Cloudflare R2 is one option.

Run `php artisan migrate --force` against the production database once, from a trusted machine or deployment job with the production `DB_*` variables set. Do not run migrations automatically on every Vercel build.

Existing files in Laragon's `storage/app/public` are not copied by the deploy. Upload/migrate them to the object bucket or re-upload them after deployment.

The local `.env.example` uses `MAIL_MAILER=log`, which does not deliver password-reset emails. Configure an SMTP provider in Vercel before relying on password reset or email notifications.

## 3. Set Vercel environment variables

Set these for Production and Preview in **Project Settings → Environment Variables**. Keep credentials out of Git and never use `APP_DEBUG=true` in public environments.

```text
APP_NAME=TreeLink
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.vercel.app
APP_KEY=base64:...generated-key...
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=...managed-mysql-host...
DB_PORT=3306
DB_DATABASE=...database-name...
DB_USERNAME=...database-user...
DB_PASSWORD=...database-password...

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
CACHE_STORE=database
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
LOG_LEVEL=error

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...bucket-access-key...
AWS_SECRET_ACCESS_KEY=...bucket-secret...
AWS_DEFAULT_REGION=auto
AWS_BUCKET=...bucket-name...
AWS_ENDPOINT=https://...account-id....r2.cloudflarestorage.com
AWS_URL=https://...public-bucket-domain...
AWS_USE_PATH_STYLE_ENDPOINT=true
```

Generate `APP_KEY` locally with `php artisan key:generate --show`, then paste the result into Vercel. Use the bucket's public custom domain for `AWS_URL` if product/profile images should be publicly viewable. `AWS_ENDPOINT` is the S3 API endpoint; it is not the public image URL.

Vercel supplies the `VERCEL` environment variable automatically. The Laravel bootstrap uses it to place compiled views, file cache, sessions fallback, and logs under `/tmp`; persistent sessions and cache are configured to use MySQL above.

## 4. Deploy and verify

Push the project to the connected Git branch or deploy from the Vercel CLI. Open the deployment logs and confirm the PHP function builds with `vercel-php@0.7.4`. Then verify:

1. `/` loads and CSS/JS assets under `/build/` load.
2. Register/login and dashboard sessions persist across requests.
3. An image upload appears after a new request, proving it reached object storage.
4. Public profiles and product links resolve on the Vercel domain.

If deployment still fails, check the first build/runtime error in **Vercel → Deployments → Build Logs / Runtime Logs**. Common blockers are an incorrect Root Directory, missing environment variables, an unreachable MySQL host, or missing S3 credentials/bucket URL.

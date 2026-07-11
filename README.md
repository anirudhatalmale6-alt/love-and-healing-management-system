# Love and Healing Management System

A complete, ready-to-install copy of the management system: people, households, groups,
attendance, check-in, services, follow-up, departments, documents, finance (giving,
expenses, pledges, budgets, chart of accounts, statements), reports, communication
(email/SMS), clip generator and the installable web app (PWA).

It is a **stand-alone system**. It shares no database, no files and no settings with any
other system - installing this one changes nothing anywhere else.

---

## What is inside

```
love-and-healing/
  api/        PHP 8 backend (one file per feature) + install.php
  public/     the built app that the browser loads (this is what people open)
  frontend/   the React source code, only needed to change the design later
  schema.sql  the database tables
  .htaccess   URL rules
```

You only need `api/`, `public/`, `.htaccess` and `schema.sql` to run it. The `frontend/`
folder is the source code, kept so the system can be modified in the future.

---

## Installing it on a website (about 15 minutes)

**1. Create a database**

In the hosting control panel (cPanel / hPanel), create:
- a MySQL database, e.g. `love_healing_mgmt`
- a database user with a password, and give that user all privileges on the database

**2. Upload the files**

Upload the folder to the website so it sits in a folder called `system`:

```
public_html/
  system/
    .htaccess
    api/
    public/
    schema.sql
```

**3. Point the system at the database**

Open `api/config.php` and fill in these lines:

```php
define('DB_NAME', getenv('DB_NAME') ?: 'love_healing_mgmt');     // database name
define('DB_USER', getenv('DB_USER') ?: 'love_healing_user');     // database user
define('DB_PASS', getenv('DB_PASS') ?: 'CHANGE_ME_DB_PASSWORD'); // database password
```

In the same file, put the real website addresses in the `$allowed_origins` list
(replace `yourdomain.org`).

**4. Set the login key**

Copy `api/config.secret.example.php` to `api/config.secret.php` and put a long random
string in it. That key signs the logins - keep it private, never share it.

**5. Run the installer**

Open in a browser:

```
https://yourdomain.org/system/public/install
```

It creates all the tables and asks for the first administrator account (name, email,
password). That is the account used to log in afterwards.

**6. Log in**

```
https://yourdomain.org/system/public/
```

Then open Settings and set the organisation name, address, logo, currency and the rest.
The system starts completely empty - no people, no giving, no history.

---

## Changing the design or the code (optional)

Node.js 20+ is needed:

```bash
cd frontend
npm install
npm run build      # writes the updated app into ../public
```

Then upload the new `public/` folder to the server.

---

## Notes

- The logos in `public/` (`logo.png`, `logo-system.png`, `icon-192.png`, `icon-512.png`,
  `favicon.svg`) are plain placeholders. Replace them with real artwork using the same
  file names and the system picks them up.
- Email and SMS: enter the SMTP / Twilio details under Communication -> Settings.
- The follow-up reminder script (`api/cron_followup_reminders.php`) needs a daily cron job
  and its own key - set `CHANGE_ME_CRON_KEY` in that file before using it.
- Requires PHP 8.0 or newer and MySQL/MariaDB.

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

It reads `schema.sql`, creates all 49 tables and creates the first administrator account.
The page then shows the login details:

```
email:    admin@yourdomain.org
password: Admin123!
```

Change that password straight after the first login (Settings -> Account).

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

## Groups and departments

Groups are sorted into three kinds, and a person can be in as many groups as needed:

- **Serving teams** - the teams that run a department and file a report after each service.
  Each one is tied to the department it reports for (Choir -> Worship Team, and so on).
- **Leadership & governance** - the boards and offices that oversee the church.
- **Ministries & fellowship** - the groups people simply belong to. They do not file a
  service report.

Add, rename, re-file and delete groups from the Groups page. Deleting a group removes only
that group from its people - everything else they belong to is kept.

---

## Notes

- The logos in `public/` (`logo.png`, `logo-system.png`, `icon-192.png`, `icon-512.png`,
  `favicon.svg`) are plain placeholders. Replace them with real artwork using the same
  file names and the system picks them up.
- Email and SMS: enter the SMTP / Twilio details under Communication -> Settings.
- The follow-up reminder script (`api/cron_followup_reminders.php`) needs a daily cron job
  and its own key - set `CHANGE_ME_CRON_KEY` in that file before using it.
- Requires PHP 8.0 or newer and MySQL/MariaDB.
- **Set your timezone.** The system stores every time in UTC and shows it in the church's
  local zone. Open `api/config.php` and set both to your area:

  ```php
  date_default_timezone_set('America/New_York');   // near the top
  const CHURCH_TZ = 'America/New_York';            // a few lines below
  ```

  Use any name from the standard timezone list (`America/Chicago`, `Europe/London`, ...).
  Daylight saving is then handled automatically - there is nothing to change twice a year.

---

## What is included in this build

Everything the system currently does, including the newest additions:

- **Report Cards** (Finance -> Statements -> Report Cards) - pick any number of people and
  print or save a one-page card each, showing giving and attendance for a chosen period,
  with a note you type per person. You can limit it to particular services, and switch the
  detail rows off to print totals only. The PDF matches the print-out exactly.
- **Loans / accounts receivable** - record money lent or borrowed with full details and
  track repayments.
- **Pledge follow-up call sheet** - print a list of everyone behind on their pledge, with
  phone numbers and blank "Called / Notes" columns.
- **Per-section permissions** - give a user view-only access to some sections and full
  editing on others, and optionally hide phone numbers and addresses from them.
- **Expense grouping** - group categories (electricity, gas, water...) under one heading
  such as Utilities, which then rolls up on the reports.

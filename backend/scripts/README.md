# Scripts

## import-data.php

Imports categories and products from `data.json` into the database.

**Usage:**

```bash
# From backend directory:
php scripts/import-data.php [path/to/data.json]
```

If no path is given, uses `scripts/data.json`. Example with external file:

```bash
php scripts/import-data.php /Users/you/Downloads/data.json
```

**Requires:** MySQL running, `.env` configured, schema already created (run `database/schema.sql` first).

**Effect:** Clears existing categories, attributes, attribute_items, products, prices, gallery, product_attributes (and orders/order_items), then inserts data from the JSON file.

### Running on Railway

`mysql.railway.internal` only resolves **inside Railway’s network**. You can’t run the import from your laptop with that host.

**Option A – Run the import on Railway (recommended)**

From your machine, in the `api` directory, run the script in Railway’s environment (uses the **linked service’s** env vars):

```bash
cd api
railway run php scripts/import-data.php scripts/data.json
```

**If you see “No database selected”:** the linked service has no `DB_NAME`. Do one of the following:

1. **Link to the MySQL service for this run** (so the script gets `DB_NAME=railway`, `DB_HOST`, etc. from MySQL):
   ```bash
   railway link   # select the **MySQL** service (not API)
   railway run php scripts/import-data.php scripts/data.json
   ```
2. Or add `DB_NAME=railway` (and other DB_* vars or `MYSQL_URL`) to your **API** service in Railway → Variables, then `railway link` back to the API and run again.

The app falls back to `MYSQL_DATABASE` or `railway` when `DB_NAME` is empty. Database and tables must exist (run `database/schema.sql` once; Railway’s default DB is `railway`).

**Option B – Run the import locally against Railway’s DB**

Use the **public** MySQL URL from Railway (Dashboard → your MySQL service → Connect → Public URL / “Connect with public URL”). Put that host/port/user/password in a local `.env` (e.g. `DB_HOST=roundhouse.proxy.rlwy.net`, `DB_PORT=12345`, etc.) and run:

```bash
php scripts/import-data.php scripts/data.json
```

Enable public networking for the MySQL service in Railway if the public URL is not available.

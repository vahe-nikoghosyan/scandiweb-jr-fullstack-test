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

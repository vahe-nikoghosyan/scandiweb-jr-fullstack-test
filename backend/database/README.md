# Database

## Setup

1. Ensure MySQL is running.
2. Create the database and import the schema:

```bash
# With password prompt:
mysql -u root -p < backend/database/schema.sql

# Or without password (local dev):
mysql -u root < backend/database/schema.sql
```

3. Verify tables:

```bash
mysql -u root -p scandiweb_store -e "SHOW TABLES;"
```

## Schema

- **categories** – Product categories
- **products** – Products (references categories)
- **prices** – Product prices with currency
- **gallery** – Product images
- **attributes** – Attribute definitions (text/swatch)
- **product_attributes** – Product–attribute links
- **attribute_items** – Attribute option values
- **orders** – Orders
- **order_items** – Order line items (with selected_attributes JSON)

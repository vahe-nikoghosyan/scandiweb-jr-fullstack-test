# Manual Test Checklist (Step 7.7)

Run with backend on `http://localhost:8000` and frontend on `http://localhost:5173`.

## Happy path
- [ ] **Browse categories** – Click category links in header; PLP updates.
- [ ] **View products** – Product list shows cards with image, name, price.
- [ ] **View PDP** – Click a product; PDP shows gallery, info, attributes, add to cart.
- [ ] **Select attributes** – Choose size/color (or other attributes) on PDP.
- [ ] **Add to cart (PDP)** – With attributes selected, Add to cart; cart count updates.
- [ ] **Quick shop** – On PLP, open quick shop; select options and add to cart.
- [ ] **View cart** – Open cart overlay; items, quantities, totals visible.
- [ ] **Change quantities** – In cart overlay, use +/- on a line; total updates.
- [ ] **Place order** – Click “Place order”; overlay closes or shows confirmation.
- [ ] **Verify order in database** – Run:
  ```bash
  mysql -u root -p scandiweb_store -e "SELECT * FROM orders ORDER BY id DESC LIMIT 5; SELECT * FROM order_items ORDER BY order_id DESC, id LIMIT 20;"
  ```
- [ ] **Verify cart cleared** – After placing order, cart is empty (count 0, overlay empty).
- [ ] **Refresh page (cart persists)** – Add item, refresh; cart still has the item (localStorage).

## Edge cases
- [ ] **Empty cart** – Open cart with no items; empty state shown; place order disabled or N/A.
- [ ] **Out of stock** – Product with `in_stock: false` shows overlay/disabled add; cannot add.
- [ ] **No attributes selected** – On PDP with required attributes, add to cart disabled or validation message until all selected.
- [ ] **Same product + different options** – Add same product with e.g. Color A, then Color B; cart shows two separate lines.

## Automated tests
```bash
cd frontend && npm run test
```
Expect: all tests pass (e.g. `cartHelpers.test.ts`).

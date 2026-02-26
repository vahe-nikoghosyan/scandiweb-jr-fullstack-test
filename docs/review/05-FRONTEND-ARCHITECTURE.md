# Frontend Architecture Review

## 1. Build Tool & Framework

| Requirement | Status | Evidence |
|-------------|--------|----------|
| Vite + React | ✅ | `vite.config.ts` uses `@vitejs/plugin-react`; `package.json` scripts: `"dev": "vite"`, `"build": "tsc -b && vite build"` |
| Not Next.js | ✅ | No `next.config`, no `next/` imports |
| Not Remix | ✅ | No Remix framework; `@remix-run/router` is a transitive dependency of `react-router-dom`, not the Remix app framework |

---

## 2. Component Model

| Requirement | Status | Evidence |
|-------------|--------|----------|
| Functional components | ✅ | Header, ProductListPage, ProductDetailsPage, CartOverlay, ProductCard, CartItem, CartTotal, etc. are all function components |
| Class-based only when allowed | ✅ | `ErrorBoundary` is the only class component—required by React for `componentDidCatch` and `getDerivedStateFromError` |

---

## 3. Styling

| Requirement | Status | Evidence |
|-------------|--------|----------|
| Plain CSS / CSS-in-JS / preprocessors | ✅ | Uses **CSS Modules** (`.module.css`) throughout; `index.css` and `App.css` for global styles |
| No MUI / Chakra | ✅ | No `@mui/*`, `MuiThemeProvider`, `ChakraProvider`, or `chakra` imports |
| Approach | ✅ | CSS Modules + global CSS; variables in `App.css` (`--color-primary`, `--color-accent`, etc.); Google Fonts (Raleway) via `@import` |

---

## 4. GraphQL Integration

| Requirement | Status | Evidence |
|-------------|--------|----------|
| GraphQL for backend data | ✅ | All product, category, and order data fetched via GraphQL |
| Apollo Client | ✅ | `@apollo/client` in `package.json`; `ApolloProvider` in `main.tsx`; `ApolloClient` + `HttpLink` in `graphql/client.ts` |
| Clean implementation | ✅ | Hooks (`useCategories`, `useProducts`, `useProduct`, `usePlaceOrder`) wrap `useQuery`/`useMutation`; queries/mutations in `graphql/` folder |

**Queries:**
- `getCategories.ts` – categories
- `getProducts.ts` – products (id, name, inStock, gallery, prices, category, attributes)
- `getProduct.ts` – single product by id

**Mutations:**
- `placeOrder.ts` – create order

**Environment:**
- `VITE_GRAPHQL_URI` (default: `http://localhost:8000/graphql`)

---

## 5. Major Component Breakdown

### Header (`components/layout/Header/Header.tsx`)

| Criterion | Status | Summary |
|-----------|--------|---------|
| Functional component | ✅ | `function Header()` |
| Styling | ✅ | `Header.module.css` (CSS Modules) |
| GraphQL | ✅ | Uses `useCategories()` (Apollo `useQuery`) for navigation links |
| Role | ✅ | Renders nav with category links, logo, cart button; opens `CartOverlay`; highlights active category via `useLocation()` |

---

### ProductList (ProductListPage) (`components/pages/ProductListPage/ProductListPage.tsx`)

| Criterion | Status | Summary |
|-----------|--------|---------|
| Functional component | ✅ | `function ProductListPage()` |
| Styling | ✅ | `ProductListPage.module.css` (CSS Modules) |
| GraphQL | ✅ | Uses `useProducts(categoryId)` (Apollo `useQuery`) for products |
| Role | ✅ | Renders grid of `ProductCard`; handles loading (skeletons), error, and empty states; filters by category via `useParams()` |

---

### PDP (ProductDetailsPage) (`components/pages/ProductDetailsPage/ProductDetailsPage.tsx`)

| Criterion | Status | Summary |
|-----------|--------|---------|
| Functional component | ✅ | `function ProductDetailsPage()` |
| Styling | ✅ | `ProductDetailsPage.module.css` (CSS Modules) |
| GraphQL | ✅ | Uses `useProduct(id)` (Apollo `useQuery`) for single product |
| Role | ✅ | Renders `ImageGallery`, `ProductInfo`, `AddToCartButton`, `ProductDescription`; manages `selectedAttributes` state; enforces attribute selection before add-to-cart |

---

### CartOverlay (`components/layout/CartOverlay/CartOverlay.tsx`)

| Criterion | Status | Summary |
|-----------|--------|---------|
| Functional component | ✅ | `function CartOverlay({ isOpen, onClose })` |
| Styling | ✅ | `CartOverlay.module.css` (CSS Modules) |
| GraphQL | ✅ | Uses `usePlaceOrder()` (Apollo `useMutation`) for placing orders |
| Role | ✅ | Renders slide-over panel with `CartItem` list, `CartTotal`, and "Place order" button; uses `CartContext` for cart state; closes on successful order |

---

## 6. Supporting Structure

| Item | Description |
|------|-------------|
| **Routing** | `react-router-dom` (`BrowserRouter`, `Routes`, `Route`); paths: `/`, `/category/:id`, `/product/:id` |
| **Cart state** | `CartContext` (React Context) in `context/CartContext.tsx` |
| **Data hooks** | `useCategories`, `useProducts`, `useProduct`, `usePlaceOrder` in `hooks/` |
| **GraphQL folder** | `graphql/client.ts`, `graphql/queries/`, `graphql/mutations/` |
| **Entry** | `main.tsx` → `createRoot` + `StrictMode` + `ErrorBoundary` + `ApolloProvider` + `BrowserRouter` + `CartProvider` + `App` |

---

## 7. Data-testid & UI Behavior Validation

| Attribute / Behavior | Found | Status | Notes |
|----------------------|-------|--------|-------|
| `category-link` | Yes | ✅ | Header.tsx |
| `active-category-link` | Yes | ✅ | Same location |
| `product-${product name in kebab case}` | Yes | ✅ | ProductCard.tsx |
| `cart-btn` | Yes | ✅ | CartButton.tsx |
| `cart-total` | Yes | ✅ | CartTotal.tsx |
| `product-gallery` | Yes | ✅ | ImageGallery.tsx |
| `product-description` | Yes | ✅ | ProductDescription.tsx |
| `add-to-cart` | Yes | ✅ | AddToCartButton.tsx, QuickShopButton.tsx |
| 1 Item / X Items in overlay | Yes | ✅ | Overlay header |
| Page grey-out when overlay opens | Yes | ✅ | `.backdrop` |
| Total logic in overlay | Yes | ✅ | CartTotal |

---

## 8. Summary Checklist

| Requirement | Result |
|-------------|--------|
| Vite + React (not Next.js/Remix) | ✅ |
| Functional components (class only when needed) | ✅ |
| Plain CSS / CSS-in-JS / preprocessors (no MUI/Chakra) | ✅ |
| GraphQL via fetch or Apollo | ✅ (Apollo Client) |
| Header, ProductList, PDP, CartOverlay align with task | ✅ |

# GraphQL Structure Review

## Queries

| Query | Type | Resolver | Status |
|-------|------|----------|--------|
| `test` | String | Inline | ✅ |
| `categories` | [Category!]! | CategoryResolver | ✅ |
| `products` | [Product!]! | ProductResolver | ✅ |
| `product(id: ID!)` | Product | ProductResolver | ✅ |

## Mutations

| Mutation | Input | Output | Status |
|----------|-------|--------|--------|
| `placeOrder` | OrderInput! | OrderResult! | ✅ |

## Types

- Category, Product, Price, Currency, Attribute (interface), TextAttribute, SwatchAttribute, AttributeItem, OrderResult, OrderInput, OrderItemInput, SelectedAttributeInput.

## Product Attributes

- Implemented as separate `Attribute` interface; resolved via `AttributeResolver` (not inline in Product).

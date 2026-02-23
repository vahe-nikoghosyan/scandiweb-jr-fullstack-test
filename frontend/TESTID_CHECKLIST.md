# data-testid Audit Checklist

Required attributes from project steps and common automation selectors.

## Header
| testid | Location | Status |
|--------|----------|--------|
| `cart-btn` | CartButton | ✅ |
| `category-link` | Header category links | ✅ |
| `active-category-link` | Active category link | ✅ |

## Product list (PLP)
| testid | Location | Status |
|--------|----------|--------|
| `product-{kebab-name}` | ProductCard root (per product) | ✅ |
| `add-to-cart` | ProductCard add button | ✅ |
| `quick-shop` | QuickShopButton | ✅ |
| `out-of-stock-overlay` | ProductCard when out of stock | ✅ |

## Product details (PDP)
| testid | Location | Status |
|--------|----------|--------|
| `product-gallery` | ImageGallery root | ✅ |
| `gallery-main-image` | Main image | ✅ |
| `gallery-prev` | Previous image button | ✅ |
| `gallery-next` | Next image button | ✅ |
| `gallery-thumb-{i}` | Thumbnail buttons | ✅ |
| `product-info` | ProductInfo root | ✅ |
| `product-attribute-{kebab}` | AttributeSelector (e.g. size, color) | ✅ |
| `product-attribute-{kebab}-value` | Selected value (AttributeSelector) | ✅ |
| `text-option-{value}` | TextSelector option | ✅ |
| `swatch-option-{value}` | SwatchSelector option | ✅ |
| `add-to-cart` | PDP AddToCartButton | ✅ |
| `product-description` | ProductDescription root | ✅ |
| `selected-attributes` | PDP selected state (hidden) | ✅ |

## Cart overlay
| testid | Location | Status |
|--------|----------|--------|
| `cart-overlay-backdrop` | Backdrop button | ✅ |
| `cart-overlay-items` | Cart items list | ✅ |
| `cart-overlay-place-order` | Place order button | ✅ |
| `cart-overlay-total` | Total section wrapper | ✅ |
| `cart-overlay-close` | Close (×) button | ✅ |
| `cart-overlay-empty` | Empty cart message | ✅ |
| `cart-total` | CartTotal component | ✅ |

## Cart item (in overlay)
| testid | Location | Status |
|--------|----------|--------|
| `cart-item` | CartItem root | ✅ |
| `cart-item-name` | Product name | ✅ |
| `cart-item-price` | Price | ✅ |
| `cart-item-image` | Image | ✅ |
| `cart-item-attribute-{kebab}` | Attribute row | ✅ |
| `cart-item-attribute-{kebab}-value` | Attribute value | ✅ |
| `cart-item-quantity` | Quantity display | ✅ |
| `cart-item-amount-decrease` | Decrease button | ✅ |
| `cart-item-amount-increase` | Increase button | ✅ |

## Optional / compatibility
| testid | Location | Status |
|--------|----------|--------|
| `image-gallery` | Alias for product-gallery | ➖ (use product-gallery) |

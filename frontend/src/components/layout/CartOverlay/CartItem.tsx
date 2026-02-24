import { useCart } from "../../../context/CartContext";
import type { CartItem as CartItemType } from "../../../types/CartItem";
import { formatPrice } from "../../../utils/priceFormatter";
import { getSwatchStyle, toKebab } from "../../../utils/attributeHelpers";
import styles from "./CartItem.module.css";

interface CartItemProps {
  item: CartItemType;
}

function CartItem({ item }: CartItemProps) {
  const { updateQuantity, removeFromCart } = useCart();
  const { product, quantity } = item;
  const imageUrl = product.gallery[0] ?? "";
  const price = product.prices[0];
  const priceLabel = price
    ? formatPrice(price.amount, price.currency.symbol)
    : "";

  const getAttributeInfo = (attrId: string, value: string) => {
    const attr = product.attributes?.find((a) => a.id === attrId);
    if (!attr) return { name: attrId, displayValue: value, type: "text" };
    const itemOption = attr.items.find((i) => i.value === value);
    return {
      name: attr.name,
      displayValue: itemOption?.displayValue ?? value,
      type: attr.type?.toLowerCase() ?? "text",
    };
  };

  return (
    <li className={styles.root} data-testid="cart-item">
      <div className={styles.main}>
        <div className={styles.info}>
          <h3 className={styles.name} data-testid="cart-item-name">
            {product.name}
          </h3>
          <p className={styles.price} data-testid="cart-item-price">
            {priceLabel}
          </p>
          {item.selectedAttributes.length > 0 && (
            <div className={styles.attributes} aria-label="Selected attributes">
              {item.selectedAttributes.map((sel) => {
                const { name, displayValue, type } = getAttributeInfo(
                  sel.id,
                  sel.value
                );
                const kebab = toKebab(name);
                const isSwatch = type === "swatch";
                return (
                  <div
                    key={`${sel.id}-${sel.value}`}
                    className={styles.attrRow}
                    data-testid={`cart-item-attribute-${kebab}`}
                  >
                    <span className={styles.attrName}>{name}:</span>
                    {isSwatch ? (
                      <span
                        className={styles.attrSwatch}
                        style={getSwatchStyle(displayValue)}
                        data-testid={`cart-item-attribute-${kebab}-value`}
                        title={displayValue}
                        aria-hidden
                      >
                        {displayValue.toLowerCase() === "white" && (
                          <span className={styles.swatchBorder} aria-hidden />
                        )}
                      </span>
                    ) : (
                      <span
                        className={styles.attrValue}
                        data-testid={`cart-item-attribute-${kebab}-value`}
                      >
                        {displayValue}
                      </span>
                    )}
                  </div>
                );
              })}
            </div>
          )}
        </div>
        <div className={styles.quantityBlock}>
          <button
            type="button"
            className={styles.qtyBtn}
            onClick={() =>
              quantity > 1
                ? updateQuantity(item.id, quantity - 1)
                : removeFromCart(item.id)
            }
            aria-label="Decrease quantity"
            data-testid="cart-item-amount-decrease"
          >
            −
          </button>
          <span className={styles.quantity} data-testid="cart-item-quantity">
            {quantity}
          </span>
          <button
            type="button"
            className={styles.qtyBtn}
            onClick={() => updateQuantity(item.id, quantity + 1)}
            aria-label="Increase quantity"
            data-testid="cart-item-amount-increase"
          >
            +
          </button>
        </div>
        <img
          src={imageUrl}
          alt=""
          className={styles.image}
          data-testid="cart-item-image"
        />
      </div>
    </li>
  );
}

export default CartItem;

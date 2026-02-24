import { useCart } from "../../../context/CartContext";
import type { Product } from "../../../types/Product";
import styles from "./AddToCartButton.module.css";

interface AddToCartButtonProps {
  disabled?: boolean;
  product: Product;
  selectedAttributes: Map<string, string>;
}

function AddToCartButton({
  disabled = false,
  product,
  selectedAttributes,
}: AddToCartButtonProps) {
  const { addToCart, openCartOverlay } = useCart();

  const handleClick = () => {
    const selected = Array.from(selectedAttributes.entries()).map(
      ([id, value]) => ({ id, value })
    );
    addToCart(product, 1, selected);
    openCartOverlay();
  };

  return (
    <button
      type="button"
      className={styles.button}
      disabled={disabled}
      onClick={handleClick}
      data-testid="add-to-cart"
      aria-label="Add to cart"
    >
      Add to cart
    </button>
  );
}

export default AddToCartButton;

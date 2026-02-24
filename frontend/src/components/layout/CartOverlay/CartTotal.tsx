import { useCart } from "../../../context/CartContext";
import { calculateTotal } from "../../../utils/cartHelpers";
import styles from "./CartTotal.module.css";

function CartTotal() {
  const { cartItems } = useCart();
  const total = calculateTotal(cartItems);
  const formatted = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
    minimumFractionDigits: 2,
  }).format(total);

  return (
    <div className={styles.root} data-testid="cart-total">
      <span className={styles.label}>Total:</span>
      <span className={styles.value}>{formatted}</span>
    </div>
  );
}

export default CartTotal;

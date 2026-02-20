import { useCart } from '../../../context/CartContext'
import styles from './CartButton.module.css'

function CartButton() {
  const { itemCount } = useCart()
  const label = itemCount === 1 ? '1 Item' : `${itemCount} Items`

  return (
    <button
      type="button"
      className={styles.cartButton}
      aria-label="Cart"
      title="Cart"
      data-testid="cart-btn"
    >
      <span className={styles.cartIcon} aria-hidden>
        🛒
      </span>
      {itemCount > 0 && (
        <span className={styles.bubble} aria-hidden>
          {itemCount}
        </span>
      )}
      <span className={styles.text}>{itemCount > 0 ? label : 'Cart'}</span>
    </button>
  )
}

export default CartButton

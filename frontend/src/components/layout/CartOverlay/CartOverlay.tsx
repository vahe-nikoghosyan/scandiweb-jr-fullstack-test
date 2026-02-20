import { useCart } from '../../../context/CartContext'
import styles from './CartOverlay.module.css'

interface CartOverlayProps {
  isOpen: boolean
  onClose: () => void
}

function CartOverlay({ isOpen, onClose }: CartOverlayProps) {
  const { cartItems, total } = useCart()

  if (!isOpen) return null

  return (
    <>
      <button
        type="button"
        className={styles.backdrop}
        onClick={onClose}
        aria-label="Close cart"
        data-testid="cart-overlay-backdrop"
      />
      <aside className={styles.panel} role="dialog" aria-label="Cart">
        <div className={styles.panelHeader}>
          <h2 className={styles.title}>Cart</h2>
          <button
            type="button"
            className={styles.closeBtn}
            onClick={onClose}
            aria-label="Close cart"
          >
            ×
          </button>
        </div>
        <ul className={styles.items} data-testid="cart-overlay-items">
          {cartItems.length === 0 ? (
            <li className={styles.empty}>Your cart is empty.</li>
          ) : (
            cartItems.map((item) => (
              <li key={item.id} className={styles.itemPlaceholder}>
                {/* Placeholder: full item UI later */}
                {item.product.name} × {item.quantity}
              </li>
            ))
          )}
        </ul>
        <div className={styles.footer}>
          <div className={styles.total}>
            <span>Total:</span>
            <span data-testid="cart-overlay-total">
              {new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
              }).format(total)}
            </span>
          </div>
          <button
            type="button"
            className={styles.placeOrder}
            disabled={cartItems.length === 0}
            data-testid="cart-overlay-place-order"
          >
            Place order
          </button>
        </div>
      </aside>
    </>
  )
}

export default CartOverlay

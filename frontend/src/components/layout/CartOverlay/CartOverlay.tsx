import { useCart } from '../../../context/CartContext'
import { usePlaceOrder } from '../../../hooks/usePlaceOrder'
import CartItem from './CartItem'
import CartTotal from './CartTotal'
import styles from './CartOverlay.module.css'

interface CartOverlayProps {
  isOpen: boolean
  onClose: () => void
}

function CartOverlay({ isOpen, onClose }: CartOverlayProps) {
  const { cartItems, clearCart } = useCart()
  const { placeOrder, loading } = usePlaceOrder()
  const handlePlaceOrder = async () => {
    const result = await placeOrder()
    if (result?.success) {
      clearCart()
      window.alert('Order placed successfully!')
      onClose()
    }
  }

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
            cartItems.map((item) => <CartItem key={item.id} item={item} />)
          )}
        </ul>
        <div className={styles.footer}>
          <CartTotal />
          <button
            type="button"
            className={styles.placeOrder}
            disabled={cartItems.length === 0 || loading}
            onClick={handlePlaceOrder}
            data-testid="cart-overlay-place-order"
          >
            {loading ? 'Placing order…' : 'Place order'}
          </button>
        </div>
      </aside>
    </>
  )
}

export default CartOverlay

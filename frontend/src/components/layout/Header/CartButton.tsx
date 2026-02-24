import { useCart } from '../../../context/CartContext'
import cartIconSrc from '../../../assets/cart-icon.svg'
import styles from './CartButton.module.css'

interface CartButtonProps {
  onCartClick?: () => void
}

function CartButton({ onCartClick }: CartButtonProps) {
  const { itemCount } = useCart()

  const ariaLabel = itemCount > 0 ? `Cart, ${itemCount} item${itemCount === 1 ? '' : 's'}` : 'Cart'

  return (
    <button
      type="button"
      className={styles.cartButton}
      aria-label={ariaLabel}
      title={ariaLabel}
      data-testid="cart-btn"
      onClick={onCartClick}
    >
      <span className={styles.cartIconWrap} aria-hidden>
        <img
          src={cartIconSrc}
          alt=""
          width={20}
          height={20}
          className={styles.cartIcon}
        />
        {itemCount > 0 && (
          <span className={styles.bubble} aria-hidden>
            {itemCount}
          </span>
        )}
      </span>
    </button>
  )
}

export default CartButton

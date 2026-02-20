import { useCart } from '../../../context/CartContext'
import type { Product } from '../../../types/Product'
import { getDefaultAttributes } from '../../../utils/attributeHelpers'
import styles from './QuickShopButton.module.css'

interface QuickShopButtonProps {
  product: Product
  disabled?: boolean
  wrapperClassName?: string
}

function QuickShopButton({ product, disabled = false, wrapperClassName }: QuickShopButtonProps) {
  const { addToCart, openCartOverlay } = useCart()

  const handleClick = (e: React.MouseEvent) => {
    e.preventDefault()
    e.stopPropagation()
    if (disabled) return
    const selected = getDefaultAttributes(product)
    addToCart(product, 1, selected)
    openCartOverlay()
  }

  if (disabled) return null

  return (
    <div className={`${styles.wrapper} ${wrapperClassName ?? ''}`.trim()}>
      <button
        type="button"
        className={styles.button}
        onClick={handleClick}
        aria-label="Quick add to cart"
        data-testid="quick-shop"
      >
        <span className={styles.icon} aria-hidden>
          🛒
        </span>
      </button>
    </div>
  )
}

export default QuickShopButton

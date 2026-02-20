import styles from './AddToCartButton.module.css'

interface AddToCartButtonProps {
  disabled?: boolean
}

function AddToCartButton({ disabled = false }: AddToCartButtonProps) {
  return (
    <button
      type="button"
      className={styles.button}
      disabled={disabled}
      data-testid="add-to-cart"
      aria-label="Add to cart"
    >
      Add to cart
    </button>
  )
}

export default AddToCartButton

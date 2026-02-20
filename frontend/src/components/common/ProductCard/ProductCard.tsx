import { Link } from 'react-router-dom'
import { useCart } from '../../../context/CartContext'
import type { Product } from '../../../types/Product'
import { formatPrice } from '../../../utils/priceFormatter'
import styles from './ProductCard.module.css'

function toKebabCase(str: string): string {
  return str
    .toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^a-z0-9-]/g, '')
}

interface ProductCardProps {
  product: Product
}

function ProductCard({ product }: ProductCardProps) {
  const { addToCart } = useCart()
  const imageUrl = product.gallery[0] ?? ''
  const price = product.prices[0]
  const priceLabel = price
    ? formatPrice(price.amount, price.currency.symbol)
    : ''
  const testId = `product-${toKebabCase(product.name)}`

  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault()
    e.stopPropagation()
    addToCart(product, 1, [])
  }

  return (
    <article
      className={styles.card}
      data-testid={testId}
    >
      <Link to={`/product/${product.id}`} className={styles.link}>
        <div className={styles.imageWrap}>
          {imageUrl ? (
            <img
              src={imageUrl}
              alt={product.name}
              className={styles.image}
            />
          ) : (
            <div className={styles.placeholder}>No image</div>
          )}
        </div>
        <h2 className={styles.name}>{product.name}</h2>
        <p className={styles.price}>{priceLabel}</p>
      </Link>
      <button
        type="button"
        className={styles.addButton}
        onClick={handleAddToCart}
        data-testid="add-to-cart"
      >
        Add to cart
      </button>
    </article>
  )
}

export default ProductCard

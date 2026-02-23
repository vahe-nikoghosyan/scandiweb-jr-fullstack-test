import Skeleton from '../Skeleton/Skeleton'
import styles from './ProductCardSkeleton.module.css'

function ProductCardSkeleton() {
  return (
    <article className={styles.card} data-testid="product-card-skeleton">
      <Skeleton className={styles.image} />
      <Skeleton className={styles.name} />
      <Skeleton className={styles.price} />
      <Skeleton className={styles.button} />
    </article>
  )
}

export default ProductCardSkeleton

import { useParams } from 'react-router-dom'
import { useProducts } from '../hooks/useProducts'
import ProductCard from '../components/common/ProductCard/ProductCard'
import styles from './ProductListPage.module.css'

function ProductListPage() {
  const { id: categoryId } = useParams<{ id: string }>()
  const { products, loading, error } = useProducts(categoryId)

  if (loading) return <div className={styles.wrap}>Loading…</div>
  if (error) return <div className={styles.wrap}>Error loading products.</div>

  return (
    <div className={styles.wrap}>
      <ul className={styles.grid}>
        {products.map((p) => (
          <li key={p.id}>
            <ProductCard product={p} />
          </li>
        ))}
      </ul>
    </div>
  )
}

export default ProductListPage

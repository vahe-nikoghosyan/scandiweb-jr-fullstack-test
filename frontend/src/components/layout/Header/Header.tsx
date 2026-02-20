import { useEffect } from 'react'
import { Link, useLocation, useParams } from 'react-router-dom'
import { useCart } from '../../../context/CartContext'
import { useCategories } from '../../../hooks/useCategories'
import styles from './Header.module.css'

function Header() {
  const { categories, loading, error } = useCategories()
  const { cartItems } = useCart()
  const { pathname } = useLocation()
  const { id: categoryParam } = useParams<{ id: string }>()
  const activeCategoryId = pathname === '/' ? 'all' : categoryParam ?? null

  useEffect(() => {
    console.log('Cart state:', cartItems)
  }, [cartItems])

  return (
    <header className={styles.header}>
      <nav className={styles.nav}>
        <Link to="/" className={styles.logo}>
          Scandi
        </Link>
        <ul className={styles.categories}>
          {loading && <li className={styles.categoryMuted}>Loading…</li>}
          {error && <li className={styles.categoryMuted}>Error loading categories</li>}
          {!loading && !error && categories.map((cat) => {
            const isActive = activeCategoryId === cat.id
            return (
              <li key={cat.id}>
                <Link
                  to={cat.id === 'all' ? '/' : `/category/${cat.id}`}
                  className={isActive ? styles.categoryLinkActive : styles.categoryLink}
                  data-testid={isActive ? 'active-category-link' : 'category-link'}
                >
                  {cat.name}
                </Link>
              </li>
            )
          })}
        </ul>
        <button
          type="button"
          className={styles.cartButton}
          aria-label="Cart"
          title="Cart"
        >
          <span className={styles.cartIcon} aria-hidden>🛒</span>
        </button>
      </nav>
    </header>
  )
}

export default Header

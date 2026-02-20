import { Link } from 'react-router-dom'
import styles from './Header.module.css'

const CATEGORIES = [
  { id: 'all', name: 'All' },
  { id: 'clothes', name: 'Clothes' },
  { id: 'tech', name: 'Tech' },
]

function Header() {
  return (
    <header className={styles.header}>
      <nav className={styles.nav}>
        <Link to="/" className={styles.logo}>
          Scandi
        </Link>
        <ul className={styles.categories}>
          {CATEGORIES.map((cat) => (
            <li key={cat.id}>
              <Link
                to={cat.id === 'all' ? '/' : `/category/${cat.id}`}
                className={styles.categoryLink}
              >
                {cat.name}
              </Link>
            </li>
          ))}
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

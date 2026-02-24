import { useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom";
import { useCart } from "../../../context/CartContext";
import CartOverlay from "../CartOverlay/CartOverlay";
import CartButton from "./CartButton";
import { useCategories } from "../../../hooks/useCategories";
import logoSrc from "../../../assets/logo.svg";
import styles from "./Header.module.css";

function Header() {
  const [isCartOpen, setIsCartOpen] = useState(false);
  const { openCartOverlayRef } = useCart();
  const { categories, loading, error } = useCategories();

  useEffect(() => {
    openCartOverlayRef.current = () => setIsCartOpen(true);
    return () => {
      openCartOverlayRef.current = null;
    };
  }, [openCartOverlayRef]);
  const { pathname } = useLocation();
  const activeCategoryId =
    pathname === "/"
      ? "all"
      : pathname.startsWith("/category/")
        ? pathname.replace(/^\/category\//, "").split("/")[0] ?? null
        : null;

  return (
    <>
      <header className={styles.header}>
        <nav className={styles.nav}>
          <div className={styles.navLeft}>
            <ul className={styles.categories}>
              {loading && <li className={styles.categoryMuted}>Loading…</li>}
              {error && (
                <li className={styles.categoryMuted}>
                  Error loading categories
                </li>
              )}
              {!loading &&
                !error &&
                categories.map((cat) => {
                  const isActive = activeCategoryId === cat.id;
                  return (
                    <li key={cat.id}>
                      <Link
                        to={cat.id === "all" ? "/" : `/category/${cat.id}`}
                        className={
                          isActive
                            ? styles.categoryLinkActive
                            : styles.categoryLink
                        }
                        data-testid={
                          isActive ? "active-category-link" : "category-link"
                        }
                      >
                        {cat.name}
                      </Link>
                    </li>
                  );
                })}
            </ul>
          </div>
          <Link to="/" className={styles.logo} aria-label="Home">
            <img
              src={logoSrc}
              alt=""
              width={41}
              height={41}
              className={styles.logoImg}
              fetchPriority="high"
            />
          </Link>
          <div className={styles.navRight}>
            <CartButton onCartClick={() => setIsCartOpen((prev) => !prev)} />
          </div>
        </nav>
      </header>
      <CartOverlay isOpen={isCartOpen} onClose={() => setIsCartOpen(false)} />
    </>
  );
}

export default Header;

import { useEffect, useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { useCart } from "../../../context/CartContext";
import CartOverlay from "../CartOverlay/CartOverlay";
import CartButton from "./CartButton";
import { useCategories } from "../../../hooks/useCategories";
import logoSrc from "../../../assets/logo.svg";
import styles from "./Header.module.css";

const FALLBACK_CATEGORIES = [
  { id: "all", name: "all" },
  { id: "clothes", name: "clothes" },
  { id: "tech", name: "tech" },
];

function Header() {
  const [isCartOpen, setIsCartOpen] = useState(false);
  const navigate = useNavigate();
  const { openCartOverlayRef } = useCart();
  const { categories, loading, error } = useCategories();

  useEffect(() => {
    openCartOverlayRef.current = () => setIsCartOpen(true);
    return () => {
      openCartOverlayRef.current = null;
    };
  }, [openCartOverlayRef]);
  const displayCategories =
    categories.length > 0 ? categories : FALLBACK_CATEGORIES;
  const { pathname } = useLocation();
  const activeCategoryId =
    pathname === "/" || pathname === "/all"
      ? "all"
      : pathname.startsWith("/product/")
        ? null
        : pathname.replace(/^\//, "").split("/")[0] || null;

  return (
    <>
      <header className={styles.header}>
        <nav className={styles.nav}>
          <div className={styles.navLeft}>
            <ul className={styles.categories}>
              {loading && displayCategories === FALLBACK_CATEGORIES && (
                <li className={styles.categoryMuted}>Loading…</li>
              )}
              {error && (
                <li className={styles.categoryMuted}>
                  Error loading categories
                </li>
              )}
              {displayCategories.map((cat) => {
                  const path = cat.id === "all" ? "/all" : `/${cat.id}`;
                  const isActive = activeCategoryId === cat.id;
                  return (
                    <li key={cat.id}>
                      <a
                        href={path}
                        className={
                          isActive
                            ? styles.categoryLinkActive
                            : styles.categoryLink
                        }
                        data-testid={
                          isActive ? "active-category-link" : "category-link"
                        }
                        onClick={(e) => {
                          e.preventDefault();
                          navigate(path);
                        }}
                      >
                        {cat.name}
                      </a>
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

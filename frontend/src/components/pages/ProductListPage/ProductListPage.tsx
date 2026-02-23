import { useParams } from "react-router-dom";
import { useProducts } from "../../../hooks/useProducts";
import ProductCard from "../../common/ProductCard/ProductCard";
import ProductCardSkeleton from "../../common/ProductCard/ProductCardSkeleton";
import styles from "./ProductListPage.module.css";

const SKELETON_COUNT = 6;

function ProductListPage() {
  const { id: categoryId } = useParams<{ id: string }>();
  const { products, loading, error } = useProducts(categoryId);

  if (loading) {
    return (
      <div className={styles.wrap} data-testid="plp-loading">
        <ul className={styles.grid}>
          {Array.from({ length: SKELETON_COUNT }, (_, i) => (
            <li key={i}>
              <ProductCardSkeleton />
            </li>
          ))}
        </ul>
      </div>
    );
  }

  if (error) {
    return (
      <div className={styles.wrap} data-testid="plp-error">
        <p className={styles.errorMessage}>Error loading products. Please try again.</p>
      </div>
    );
  }

  if (products.length === 0) {
    return (
      <div className={styles.wrap} data-testid="plp-empty">
        <p className={styles.emptyMessage}>No products in this category.</p>
      </div>
    );
  }

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
  );
}

export default ProductListPage;

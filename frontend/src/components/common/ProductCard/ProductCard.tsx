import { Link } from "react-router-dom";
import type { Product } from "../../../types/Product";
import { formatPrice } from "../../../utils/priceFormatter";
import QuickShopButton from "./QuickShopButton";
import styles from "./ProductCard.module.css";

function toKebabCase(str: string): string {
  return str
    .toLowerCase()
    .replace(/\s+/g, "-")
    .replace(/[^a-z0-9-]/g, "");
}

interface ProductCardProps {
  product: Product;
}

function ProductCard({ product }: ProductCardProps) {
  const imageUrl = product.gallery[0] ?? "";
  const price = product.prices[0];
  const priceLabel = price
    ? formatPrice(price.amount, price.currency.symbol)
    : "";
  const testId = `product-${toKebabCase(product.name)}`;
  const inStock = product.inStock;

  return (
    <article
      className={`${styles.card} ${!inStock ? styles.cardOutOfStock : ""}`.trim()}
      data-testid={testId}
    >
      <Link to={`/product/${product.id}`} className={styles.link}>
        <div className={styles.imageArea}>
          <div
            className={inStock ? styles.imageWrap : styles.imageWrapOutOfStock}
          >
            {imageUrl ? (
              <img src={imageUrl} alt={product.name} className={styles.image} />
            ) : (
              <div className={styles.placeholder}>No image</div>
            )}
            {!inStock && (
              <span
                className={styles.outOfStockOverlay}
                data-testid="out-of-stock-overlay"
              >
                OUT OF STOCK
              </span>
            )}
          </div>
          {inStock && (
            <QuickShopButton
              product={product}
              wrapperClassName={styles.quickShop}
            />
          )}
        </div>
        <h2 className={styles.name}>{product.name}</h2>
        <p className={styles.price}>{priceLabel}</p>
      </Link>
    </article>
  );
}

export default ProductCard;

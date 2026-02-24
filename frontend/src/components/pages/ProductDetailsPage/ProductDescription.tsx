import { parseHTML } from "../../../utils/htmlParser";
import type { Product } from "../../../types/Product";
import styles from "./ProductDescription.module.css";

interface ProductDescriptionProps {
  product: Product;
}

function ProductDescription({ product }: ProductDescriptionProps) {
  const description = product.description ?? "";

  return (
    <div className={styles.root} data-testid="product-description">
      {description ? parseHTML(description) : null}
    </div>
  );
}

export default ProductDescription;

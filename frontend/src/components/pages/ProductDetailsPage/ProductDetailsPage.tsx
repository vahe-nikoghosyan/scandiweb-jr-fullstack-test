import { useState } from "react";
import { useParams } from "react-router-dom";
import ImageGallery from "../../common/ImageGallery/ImageGallery";
import { useProduct } from "../../../hooks/useProduct";
import ProductDetailsPageSkeleton from "./ProductDetailsPageSkeleton";
import AddToCartButton from "./AddToCartButton";
import ProductDescription from "./ProductDescription";
import ProductInfo from "./ProductInfo";
import styles from "./ProductDetailsPage.module.css";

function ProductDetailsPage() {
  const { id } = useParams<{ id: string }>();
  const { product, loading, error } = useProduct(id);
  const [selectedAttributes, setSelectedAttributes] = useState<
    Map<string, string>
  >(new Map());

  const handleAttributeChange = (attrId: string, value: string) => {
    setSelectedAttributes((prev) => {
      const next = new Map(prev);
      next.set(attrId, value);
      return next;
    });
  };

  if (loading) return <ProductDetailsPageSkeleton />;
  if (error) {
    return (
      <div className={styles.wrap} data-testid="pdp-error">
        <p className={styles.errorMessage}>Error loading product. Please try again.</p>
      </div>
    );
  }
  if (!product) {
    return (
      <div className={styles.wrap} data-testid="pdp-not-found">
        <p className={styles.errorMessage}>Product not found.</p>
      </div>
    );
  }

  const canAddToCart =
    product.inStock &&
    (product.attributes ?? []).every((attr) => selectedAttributes.has(attr.id));

  return (
    <div className={styles.wrap}>
      <div className={styles.layout}>
        <section className={styles.gallery}>
          <ImageGallery images={product.gallery} alt={product.name} />
        </section>
        <section className={styles.details}>
          <div
            data-testid="selected-attributes"
            data-selected={JSON.stringify(
              Object.fromEntries(selectedAttributes),
            )}
            aria-hidden
          >
            {/* Selected state for tests */}
          </div>
          <ProductInfo
            product={product}
            selectedAttributes={selectedAttributes}
            onAttributeChange={handleAttributeChange}
          />
          <AddToCartButton
            disabled={!canAddToCart}
            product={product}
            selectedAttributes={selectedAttributes}
          />
          <ProductDescription product={product} />
        </section>
      </div>
    </div>
  );
}

export default ProductDetailsPage;

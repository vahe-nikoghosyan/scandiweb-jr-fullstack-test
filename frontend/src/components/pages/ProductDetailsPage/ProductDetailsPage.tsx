import { useState } from "react";
import { useParams } from "react-router-dom";
import ImageGallery from "../../common/ImageGallery/ImageGallery";
import { useProduct } from "../../../hooks/useProduct";
import AddToCartButton from "./AddToCartButton";
import ProductDescription from "./ProductDescription";
import ProductInfo from "./ProductInfo";
import styles from "./ProductDetailsPage.module.css";

function ProductDetailsPage() {
  const { id } = useParams<{ id: string }>();
  const { product, loading, error } = useProduct(id);
  const [selectedAttributes, setSelectedAttributes] = useState<Map<string, string>>(new Map());

  const handleAttributeChange = (attrId: string, value: string) => {
    setSelectedAttributes((prev) => {
      const next = new Map(prev);
      next.set(attrId, value);
      return next;
    });
  };

  if (loading) return <div className={styles.wrap}>Loading…</div>;
  if (error) return <div className={styles.wrap}>Error loading product.</div>;
  if (!product) return <div className={styles.wrap}>Product not found.</div>;

  const canAddToCart = (product.attributes ?? []).every((attr) =>
    selectedAttributes.has(attr.id)
  );

  return (
    <div className={styles.wrap}>
      <div className={styles.layout}>
        <section className={styles.gallery}>
          <ImageGallery images={product.gallery} alt={product.name} />
        </section>
        <section className={styles.details}>
          <div data-testid="selected-attributes" data-selected={JSON.stringify(Object.fromEntries(selectedAttributes))} aria-hidden>
            {/* Selected state for tests */}
          </div>
          <ProductInfo
            product={product}
            selectedAttributes={selectedAttributes}
            onAttributeChange={handleAttributeChange}
          />
          <AddToCartButton disabled={!canAddToCart} />
          <ProductDescription />
        </section>
      </div>
    </div>
  );
}

export default ProductDetailsPage;

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

  if (loading) return <div className={styles.wrap}>Loading…</div>;
  if (error) return <div className={styles.wrap}>Error loading product.</div>;
  if (!product) return <div className={styles.wrap}>Product not found.</div>;

  return (
    <div className={styles.wrap}>
      <div className={styles.layout}>
        <section className={styles.gallery}>
          <ImageGallery images={product.gallery} alt={product.name} />
        </section>
        <section className={styles.details}>
          <ProductInfo />
          <AddToCartButton />
          <ProductDescription />
        </section>
      </div>
    </div>
  );
}

export default ProductDetailsPage;

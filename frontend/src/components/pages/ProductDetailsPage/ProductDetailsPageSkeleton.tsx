import Skeleton from "../../common/Skeleton/Skeleton";
import styles from "./ProductDetailsPage.module.css";

function ProductDetailsPageSkeleton() {
  return (
    <div className={styles.wrap} data-testid="pdp-skeleton">
      <div className={styles.layout}>
        <section className={styles.gallery}>
          <Skeleton className={styles.gallerySkeleton} />
        </section>
        <section className={styles.details}>
          <Skeleton height={32} width="70%" style={{ marginBottom: "1rem" }} />
          <Skeleton
            height={24}
            width="40%"
            style={{ marginBottom: "1.5rem" }}
          />
          <Skeleton
            height={120}
            width="100%"
            style={{ marginBottom: "1.5rem" }}
          />
          <Skeleton height={48} width="100%" />
        </section>
      </div>
    </div>
  );
}

export default ProductDetailsPageSkeleton;

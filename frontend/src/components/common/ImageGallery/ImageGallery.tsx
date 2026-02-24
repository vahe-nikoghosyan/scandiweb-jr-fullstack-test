import { useState } from "react";
import styles from "./ImageGallery.module.css";

interface ImageGalleryProps {
  images: string[];
  alt?: string;
}

function ImageGallery({ images, alt = "" }: ImageGalleryProps) {
  const [selectedIndex, setSelectedIndex] = useState(0);
  const hasImages = images.length > 0;
  const mainSrc = hasImages ? images[selectedIndex] : "";
  const canPrev = hasImages && images.length > 1;
  const canNext = hasImages && images.length > 1;

  const goPrev = () => {
    if (!canPrev) return;
    setSelectedIndex((i) => (i === 0 ? images.length - 1 : i - 1));
  };

  const goNext = () => {
    if (!canNext) return;
    setSelectedIndex((i) => (i === images.length - 1 ? 0 : i + 1));
  };

  if (!hasImages) {
    return (
      <div className={styles.root} data-testid="product-gallery">
        <div className={styles.placeholder}>No images</div>
      </div>
    );
  }

  return (
    <div className={styles.root} data-testid="product-gallery">
      <div className={styles.thumbnails}>
        {images.map((src, i) => (
          <button
            key={`${src}-${i}`}
            type="button"
            className={selectedIndex === i ? styles.thumbActive : styles.thumb}
            onClick={() => setSelectedIndex(i)}
            aria-label={`View image ${i + 1}`}
            data-testid={`gallery-thumb-${i}`}
          >
            <img src={src} alt="" />
          </button>
        ))}
      </div>
      <div className={styles.mainWrap}>
        <button
          type="button"
          className={styles.arrow}
          onClick={goPrev}
          disabled={!canPrev}
          aria-label="Previous image"
          data-testid="gallery-prev"
        >
          ‹
        </button>
        <img
          src={mainSrc}
          alt={alt}
          className={styles.mainImage}
          data-testid="gallery-main-image"
        />
        <button
          type="button"
          className={styles.arrow}
          onClick={goNext}
          disabled={!canNext}
          aria-label="Next image"
          data-testid="gallery-next"
        >
          ›
        </button>
      </div>
    </div>
  );
}

export default ImageGallery;

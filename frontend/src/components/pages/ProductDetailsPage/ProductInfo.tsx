import AttributeSelector from "../../common/AttributeSelector/AttributeSelector";
import { formatPrice } from "../../../utils/priceFormatter";
import type { Product } from "../../../types/Product";
import styles from "./ProductInfo.module.css";

interface ProductInfoProps {
  product: Product;
  selectedAttributes: Map<string, string>;
  onAttributeChange: (attrId: string, value: string) => void;
}

function ProductInfo({
  product,
  selectedAttributes,
  onAttributeChange,
}: ProductInfoProps) {
  const attributes = product.attributes ?? [];

  const price = product.prices[0];
  const priceLabel = price
    ? formatPrice(price.amount, price.currency.symbol)
    : null;

  return (
    <div className={styles.root} data-testid="product-info">
      <h1 className={styles.name}>{product.name}</h1>
      {priceLabel && (
        <p className={styles.price} data-testid="product-price">
          {priceLabel}
        </p>
      )}
      {attributes.map((attr) => (
        <AttributeSelector
          key={attr.id}
          attribute={attr}
          selectedValue={selectedAttributes.get(attr.id)}
          onSelect={(value) => onAttributeChange(attr.id, value)}
        />
      ))}
    </div>
  );
}

export default ProductInfo;

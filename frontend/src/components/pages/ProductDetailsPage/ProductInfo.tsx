import AttributeSelector from "../../common/AttributeSelector/AttributeSelector";
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

  return (
    <div className={styles.root} data-testid="product-info">
      <h1 className={styles.name}>{product.name}</h1>
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

import { useState } from 'react'
import AttributeSelector from '../../common/AttributeSelector/AttributeSelector'
import type { Product } from '../../../types/Product'
import styles from './ProductInfo.module.css'

interface ProductInfoProps {
  product: Product
}

function ProductInfo({ product }: ProductInfoProps) {
  const [selectedAttributes, setSelectedAttributes] = useState<Record<string, string>>({})

  const handleSelect = (attrId: string, value: string) => {
    setSelectedAttributes((prev) => ({ ...prev, [attrId]: value }))
  }

  const attributes = product.attributes ?? []

  return (
    <div className={styles.root} data-testid="product-info">
      <h1 className={styles.name}>{product.name}</h1>
      {attributes.map((attr) => (
        <AttributeSelector
          key={attr.id}
          attribute={attr}
          selectedValue={selectedAttributes[attr.id]}
          onSelect={(value) => handleSelect(attr.id, value)}
        />
      ))}
    </div>
  )
}

export default ProductInfo

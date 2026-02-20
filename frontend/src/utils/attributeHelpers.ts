import type { Product } from '../types/Product'
import type { SelectedAttribute } from '../types/CartItem'

export function getDefaultAttributes(product: Product): SelectedAttribute[] {
  const attrs = product.attributes ?? []
  return attrs
    .filter((attr) => attr.items.length > 0)
    .map((attr) => ({
      id: attr.id,
      value: attr.items[0].value,
    }))
}

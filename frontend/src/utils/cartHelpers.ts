import type { CartItem, SelectedAttribute } from '../types/CartItem'

export function generateCartItemId(
  productId: string,
  selectedAttributes: SelectedAttribute[]
): string {
  const attrs = [...selectedAttributes].sort((a, b) => a.id.localeCompare(b.id))
  const suffix = attrs.length ? JSON.stringify(attrs) : ''
  return `${productId}${suffix}`
}

export function findCartItem(
  cartItems: CartItem[],
  productId: string,
  selectedAttributes: SelectedAttribute[]
): CartItem | undefined {
  const id = generateCartItemId(productId, selectedAttributes)
  return cartItems.find((item) => item.id === id)
}

export function calculateTotal(cartItems: CartItem[]): number {
  return cartItems.reduce((sum, item) => {
    const price = item.product.prices[0]?.amount ?? 0
    return sum + price * item.quantity
  }, 0)
}

export function getItemCount(cartItems: CartItem[]): number {
  return cartItems.reduce((count, item) => count + item.quantity, 0)
}

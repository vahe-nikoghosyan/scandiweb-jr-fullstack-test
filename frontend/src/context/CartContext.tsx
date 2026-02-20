import {
  createContext,
  useCallback,
  useContext,
  useMemo,
  useState,
  type ReactNode,
} from 'react'
import type { CartItem, SelectedAttribute } from '../types/CartItem'
import type { Product } from '../types/Product'

function makeCartItemId(productId: string, selectedAttributes: SelectedAttribute[]): string {
  const attrs = [...selectedAttributes].sort((a, b) => a.id.localeCompare(b.id))
  const suffix = attrs.length ? JSON.stringify(attrs) : ''
  return `${productId}${suffix}`
}

interface CartContextValue {
  cartItems: CartItem[]
  addToCart: (product: Product, quantity: number, selectedAttributes: SelectedAttribute[]) => void
  removeFromCart: (id: string) => void
  updateQuantity: (id: string, quantity: number) => void
  clearCart: () => void
}

const CartContext = createContext<CartContextValue | null>(null)

interface CartProviderProps {
  children: ReactNode
}

export function CartProvider({ children }: CartProviderProps) {
  const [cartItems, setCartItems] = useState<CartItem[]>([])

  const addToCart = useCallback(
    (product: Product, quantity: number, selectedAttributes: SelectedAttribute[]) => {
      const id = makeCartItemId(product.id, selectedAttributes)
      setCartItems((prev) => {
        const existing = prev.find((item) => item.id === id)
        if (existing) {
          return prev.map((item) =>
            item.id === id ? { ...item, quantity: item.quantity + quantity } : item
          )
        }
        return [...prev, { id, product, quantity, selectedAttributes }]
      })
    },
    []
  )

  const removeFromCart = useCallback((id: string) => {
    setCartItems((prev) => prev.filter((item) => item.id !== id))
  }, [])

  const updateQuantity = useCallback((id: string, quantity: number) => {
    if (quantity < 1) {
      setCartItems((prev) => prev.filter((item) => item.id !== id))
      return
    }
    setCartItems((prev) =>
      prev.map((item) => (item.id === id ? { ...item, quantity } : item))
    )
  }, [])

  const clearCart = useCallback(() => {
    setCartItems([])
  }, [])

  const value = useMemo<CartContextValue>(
    () => ({ cartItems, addToCart, removeFromCart, updateQuantity, clearCart }),
    [cartItems, addToCart, removeFromCart, updateQuantity, clearCart]
  )

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>
}

export function useCart(): CartContextValue {
  const ctx = useContext(CartContext)
  if (ctx == null) {
    throw new Error('useCart must be used within a CartProvider')
  }
  return ctx
}

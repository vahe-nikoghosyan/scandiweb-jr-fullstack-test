import {
  createContext,
  useCallback,
  useContext,
  useMemo,
  useRef,
  type MutableRefObject,
  type ReactNode,
} from "react";
import type { CartItem, SelectedAttribute } from "../types/CartItem";
import type { Product } from "../types/Product";
import { useLocalStorage } from "../hooks/useLocalStorage";
import {
  calculateTotal,
  findCartItem,
  generateCartItemId,
  getItemCount,
} from "../utils/cartHelpers";

const CART_STORAGE_KEY = "cart";

interface CartContextValue {
  cartItems: CartItem[];
  addToCart: (
    product: Product,
    quantity: number,
    selectedAttributes: SelectedAttribute[]
  ) => void;
  removeFromCart: (id: string) => void;
  updateQuantity: (id: string, quantity: number) => void;
  clearCart: () => void;
  itemCount: number;
  total: number;
  openCartOverlayRef: MutableRefObject<(() => void) | null>;
  openCartOverlay: () => void;
}

const CartContext = createContext<CartContextValue | null>(null);

interface CartProviderProps {
  children: ReactNode;
}

export function CartProvider({ children }: CartProviderProps) {
  const [cartItems, setCartItems] = useLocalStorage<CartItem[]>(
    CART_STORAGE_KEY,
    [],
    (parsed) => (Array.isArray(parsed) ? parsed : [])
  );

  const addToCart = useCallback(
    (
      product: Product,
      quantity: number,
      selectedAttributes: SelectedAttribute[]
    ) => {
      setCartItems((prev) => {
        // Check if item exists (same product + attributes) via generateCartItemId uniqueness
        const existing = findCartItem(prev, product.id, selectedAttributes);
        if (existing) {
          const id = existing.id;
          return prev.map((item) =>
            item.id === id
              ? { ...item, quantity: item.quantity + quantity }
              : item
          );
        }
        // If not: add new item
        const id = generateCartItemId(product.id, selectedAttributes);
        return [...prev, { id, product, quantity, selectedAttributes }];
      });
    },
    [setCartItems]
  );

  const removeFromCart = useCallback((id: string) => {
    setCartItems((prev) => prev.filter((item) => item.id !== id));
  }, [setCartItems]);

  const updateQuantity = useCallback((id: string, quantity: number) => {
    if (quantity < 1) {
      setCartItems((prev) => prev.filter((item) => item.id !== id));
      return;
    }
    setCartItems((prev) =>
      prev.map((item) => (item.id === id ? { ...item, quantity } : item))
    );
  }, [setCartItems]);

  const clearCart = useCallback(() => {
    setCartItems([]);
  }, [setCartItems]);

  const openCartOverlayRef = useRef<(() => void) | null>(null);
  const openCartOverlay = useCallback(() => {
    openCartOverlayRef.current?.();
  }, []);

  const value = useMemo<CartContextValue>(
    () => ({
      cartItems,
      addToCart,
      removeFromCart,
      updateQuantity,
      clearCart,
      itemCount: getItemCount(cartItems),
      total: calculateTotal(cartItems),
      openCartOverlayRef,
      openCartOverlay,
    }),
    [
      cartItems,
      addToCart,
      removeFromCart,
      updateQuantity,
      clearCart,
      openCartOverlay,
    ]
  );

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

/** Context hook co-located with provider (react-refresh expects only components). */
// eslint-disable-next-line react-refresh/only-export-components
export function useCart(): CartContextValue {
  const ctx = useContext(CartContext);
  if (ctx == null) {
    throw new Error("useCart must be used within a CartProvider");
  }
  return ctx;
}

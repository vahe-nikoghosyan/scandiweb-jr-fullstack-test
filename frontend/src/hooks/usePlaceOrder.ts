import { useMutation } from "@apollo/client";
import { useCart } from "../context/CartContext";
import { PLACE_ORDER } from "../graphql/mutations/placeOrder";
import type { CartItem } from "../types/CartItem";

export interface OrderItemInput {
  productId: string;
  quantity: number;
  selectedAttributes: { id: string; value: string }[];
}

export interface OrderInput {
  items: OrderItemInput[];
}

export interface PlaceOrderResult {
  success: boolean;
  orderId: string | null;
}

function cartItemsToOrderInput(cartItems: CartItem[]): OrderInput {
  return {
    items: cartItems.map((item) => ({
      productId: item.product.id,
      quantity: item.quantity,
      selectedAttributes: item.selectedAttributes.map((a) => ({
        id: a.id,
        value: a.value,
      })),
    })),
  };
}

interface PlaceOrderMutationData {
  placeOrder: PlaceOrderResult;
}

export function usePlaceOrder() {
  const { cartItems } = useCart();
  const [mutate, { loading, error }] =
    useMutation<PlaceOrderMutationData>(PLACE_ORDER);

  const placeOrder = async (): Promise<PlaceOrderResult | null> => {
    if (cartItems.length === 0) return null;
    const input = cartItemsToOrderInput(cartItems);
    const result = await mutate({ variables: { input } });
    return result.data?.placeOrder ?? null;
  };

  return { placeOrder, loading, error };
}

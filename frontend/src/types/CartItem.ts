import type { Product } from "./Product";

export interface SelectedAttribute {
  id: string;
  value: string;
}

export interface CartItem {
  id: string;
  product: Product;
  quantity: number;
  selectedAttributes: SelectedAttribute[];
}

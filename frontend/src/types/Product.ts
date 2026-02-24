import type { Category } from "./Category";
import type { Attribute } from "./Attribute";

export interface Currency {
  label: string;
  symbol: string;
}

export interface Price {
  amount: number;
  currency: Currency;
}

export interface Product {
  id: string;
  name: string;
  inStock: boolean;
  description: string | null;
  category: Category | null;
  brand: string | null;
  prices: Price[];
  gallery: string[];
  attributes: Attribute[] | null;
}

import { useQuery } from "@apollo/client";
import { GET_PRODUCTS } from "../graphql/queries/getProducts";
import type { Product } from "../types/Product";

interface GetProductsData {
  products: Product[];
}

/**
 * Fetches products, optionally filtered by category.
 * @param categoryId - When undefined or 'all', returns all products. Otherwise filters by category.
 */
export function useProducts(categoryId?: string | null) {
  const { data, loading, error } = useQuery<GetProductsData>(GET_PRODUCTS);
  const allProducts = data?.products ?? [];

  const products =
    categoryId && categoryId !== "all"
      ? allProducts.filter((p) => p.category?.id === categoryId)
      : allProducts;

  return { products, loading, error };
}

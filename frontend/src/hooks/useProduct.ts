import { useQuery } from '@apollo/client'
import { GET_PRODUCT } from '../graphql/queries/getProduct'
import type { Product } from '../types/Product'

interface GetProductData {
  product: Product | null
}

export function useProduct(id: string | undefined) {
  const { data, loading, error } = useQuery<GetProductData>(GET_PRODUCT, {
    variables: { id: id ?? '' },
    skip: !id,
  })
  const product = data?.product ?? null
  return { product, loading, error }
}

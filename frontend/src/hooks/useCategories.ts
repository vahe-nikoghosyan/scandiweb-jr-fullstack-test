import { useQuery } from '@apollo/client'
import { GET_CATEGORIES } from '../graphql/queries/getCategories'
import type { Category } from '../types/Category'

interface GetCategoriesData {
  categories: Category[]
}

export function useCategories() {
  const { data, loading, error } = useQuery<GetCategoriesData>(GET_CATEGORIES)
  const categories = data?.categories ?? []
  return { categories, loading, error }
}

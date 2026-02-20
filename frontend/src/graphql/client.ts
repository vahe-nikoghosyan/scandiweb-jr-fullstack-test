import { ApolloClient, InMemoryCache, HttpLink } from '@apollo/client'

const uri =
  import.meta.env.VITE_GRAPHQL_URI ?? 'http://localhost:8000/graphql'

export const client = new ApolloClient({
  link: new HttpLink({ uri }),
  cache: new InMemoryCache(),
})

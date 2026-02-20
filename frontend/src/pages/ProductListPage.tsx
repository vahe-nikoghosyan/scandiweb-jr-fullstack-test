import { useQuery, gql } from '@apollo/client'

const TEST_QUERY = gql`
  query Test {
    test
  }
`

function ProductListPage() {
  const { data, loading, error } = useQuery(TEST_QUERY)
  if (data) console.log('GraphQL test response:', data)
  if (error) console.error('GraphQL error:', error)

  return (
    <div>
      <div>Product List</div>
      {loading && <p>Loading…</p>}
      {data?.test != null && <p data-testid="graphql-test">Backend: {data.test}</p>}
    </div>
  )
}

export default ProductListPage

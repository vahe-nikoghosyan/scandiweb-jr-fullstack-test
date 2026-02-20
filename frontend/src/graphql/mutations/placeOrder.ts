import { gql } from '@apollo/client'

export const PLACE_ORDER = gql`
  mutation PlaceOrder($input: OrderInput!) {
    placeOrder(input: $input) {
      success
      orderId
    }
  }
`

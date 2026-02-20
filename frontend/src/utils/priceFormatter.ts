export function formatPrice(amount: number, symbol: string): string {
  return `${symbol}${amount.toFixed(2)}`
}

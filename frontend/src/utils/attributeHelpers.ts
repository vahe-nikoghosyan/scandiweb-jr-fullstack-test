import type { CSSProperties } from 'react'
import type { Product } from '../types/Product'
import type { SelectedAttribute } from '../types/CartItem'

export function toKebab(str: string): string {
  return str
    .toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^a-z0-9-]/g, '')
}

export function getSwatchStyle(displayValue: string): CSSProperties {
  const v = displayValue.trim()
  if (/^#[0-9A-Fa-f]{3,8}$/.test(v)) return { backgroundColor: v }
  const cssColors: Record<string, string> = {
    black: '#000',
    white: '#fff',
    green: '#0f0',
    cyan: '#0ff',
    blue: '#00f',
    red: '#f00',
    yellow: '#ff0',
    grey: '#888',
    gray: '#888',
  }
  const lower = v.toLowerCase()
  if (cssColors[lower]) return { backgroundColor: cssColors[lower] }
  return { backgroundColor: 'var(--swatch-fallback, #ccc)' }
}

export function getDefaultAttributes(product: Product): SelectedAttribute[] {
  const attrs = product.attributes ?? []
  return attrs
    .filter((attr) => attr.items.length > 0)
    .map((attr) => ({
      id: attr.id,
      value: attr.items[0].value,
    }))
}

import type { Attribute } from '../../../types/Attribute'
import SwatchSelector from './SwatchSelector'
import TextSelector from './TextSelector'
import styles from './AttributeSelector.module.css'

function toKebab(str: string): string {
  return str
    .toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^a-z0-9-]/g, '')
}

interface AttributeSelectorProps {
  attribute: Attribute
  selectedValue: string | undefined
  onSelect: (value: string) => void
}

function AttributeSelector({ attribute, selectedValue, onSelect }: AttributeSelectorProps) {
  const kebab = toKebab(attribute.id)
  const isSwatch = attribute.type?.toLowerCase() === 'swatch'

  return (
    <div
      className={styles.root}
      data-testid={`product-attribute-${kebab}`}
      data-selected={selectedValue ?? ''}
      role="group"
      aria-label={attribute.name}
    >
      <span className={styles.label}>{attribute.name}:</span>
      {isSwatch ? (
        <SwatchSelector
          items={attribute.items}
          selectedValue={selectedValue}
          onSelect={onSelect}
        />
      ) : (
        <TextSelector
          items={attribute.items}
          selectedValue={selectedValue}
          onSelect={onSelect}
        />
      )}
    </div>
  )
}

export default AttributeSelector

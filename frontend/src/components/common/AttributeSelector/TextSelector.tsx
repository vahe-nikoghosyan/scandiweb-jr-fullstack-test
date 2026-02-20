import type { AttributeItem } from '../../../types/Attribute'
import styles from './TextSelector.module.css'

interface TextSelectorProps {
  items: AttributeItem[]
  selectedValue: string | undefined
  onSelect: (value: string) => void
}

function TextSelector({ items, selectedValue, onSelect }: TextSelectorProps) {
  return (
    <div className={styles.root}>
      {items.map((item) => (
        <button
          key={item.id}
          type="button"
          className={selectedValue === item.value ? styles.btnActive : styles.btn}
          onClick={() => onSelect(item.value)}
          data-testid={`text-option-${item.value}`}
        >
          {item.displayValue}
        </button>
      ))}
    </div>
  )
}

export default TextSelector

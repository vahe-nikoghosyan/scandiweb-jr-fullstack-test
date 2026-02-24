import type { CSSProperties } from "react";
import type { AttributeItem } from "../../../types/Attribute";
import styles from "./SwatchSelector.module.css";

function getSwatchStyle(displayValue: string): CSSProperties {
  const v = displayValue.trim();
  if (/^#[0-9A-Fa-f]{3,8}$/.test(v)) return { backgroundColor: v };
  const cssColors: Record<string, string> = {
    black: "#000",
    white: "#fff",
    green: "#0f0",
    cyan: "#0ff",
    blue: "#00f",
    red: "#f00",
    yellow: "#ff0",
    grey: "#888",
    gray: "#888",
  };
  const lower = v.toLowerCase();
  if (cssColors[lower]) return { backgroundColor: cssColors[lower] };
  return { backgroundColor: "var(--swatch-fallback, #ccc)" };
}

interface SwatchSelectorProps {
  items: AttributeItem[];
  selectedValue: string | undefined;
  onSelect: (value: string) => void;
}

function SwatchSelector({
  items,
  selectedValue,
  onSelect,
}: SwatchSelectorProps) {
  return (
    <div className={styles.root}>
      {items.map((item) => (
        <button
          key={item.id}
          type="button"
          className={
            selectedValue === item.value ? styles.swatchActive : styles.swatch
          }
          style={getSwatchStyle(item.displayValue)}
          onClick={() => onSelect(item.value)}
          title={item.displayValue}
          aria-label={item.displayValue}
          data-testid={`swatch-option-${item.value}`}
        >
          {item.displayValue.toLowerCase() === "white" && (
            <span className={styles.border} aria-hidden />
          )}
        </button>
      ))}
    </div>
  );
}

export default SwatchSelector;

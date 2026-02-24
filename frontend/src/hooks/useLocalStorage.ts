import { useCallback, useEffect, useState } from "react";

const STORAGE_PREFIX = "scandiweb_";

/**
 * Generic hook that syncs state with localStorage.
 * Loads from localStorage on mount; saves on every value change.
 * @param reviver - Optional: validate/transform parsed value (e.g. ensure array); return initialValue if invalid.
 */
export function useLocalStorage<T>(
  key: string,
  initialValue: T,
  reviver?: (parsed: unknown) => T
): [T, (value: T | ((prev: T) => T)) => void] {
  const storageKey = `${STORAGE_PREFIX}${key}`;

  const [value, setValue] = useState<T>(() => {
    if (typeof window === "undefined") return initialValue;
    try {
      const raw = window.localStorage.getItem(storageKey);
      if (raw != null) {
        const parsed: unknown = JSON.parse(raw);
        return reviver ? reviver(parsed) : (parsed as T);
      }
    } catch {
      // ignore invalid or missing data
    }
    return initialValue;
  });

  useEffect(() => {
    try {
      window.localStorage.setItem(storageKey, JSON.stringify(value));
    } catch {
      // ignore quota or other errors
    }
  }, [storageKey, value]);

  const setValueAndPersist = useCallback((next: T | ((prev: T) => T)) => {
    setValue((prev) =>
      typeof next === "function" ? (next as (prev: T) => T)(prev) : next
    );
  }, []);

  return [value, setValueAndPersist];
}

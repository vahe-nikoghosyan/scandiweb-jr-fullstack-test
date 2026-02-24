import { describe, it, expect } from "vitest";
import { generateCartItemId, findCartItem } from "./cartHelpers";
import type { CartItem, SelectedAttribute } from "../types/CartItem";
import type { Product } from "../types/Product";

function mockProduct(overrides: Partial<Product> = {}): Product {
  return {
    id: "test-product",
    name: "Test Product",
    inStock: true,
    description: null,
    category: null,
    brand: null,
    prices: [{ amount: 10, currency: { label: "USD", symbol: "$" } }],
    gallery: [],
    attributes: null,
    ...overrides,
  };
}

function mockCartItem(
  product: Product,
  quantity: number,
  selectedAttributes: SelectedAttribute[]
): CartItem {
  return {
    id: generateCartItemId(product.id, selectedAttributes),
    product,
    quantity,
    selectedAttributes,
  };
}

describe("generateCartItemId", () => {
  const productId = "prod-1";

  it("returns same id for same product and same attributes", () => {
    const attrs1: SelectedAttribute[] = [
      { id: "Color", value: "Green" },
      { id: "Size", value: "40" },
    ];
    const attrs2: SelectedAttribute[] = [
      { id: "Color", value: "Green" },
      { id: "Size", value: "40" },
    ];
    expect(generateCartItemId(productId, attrs1)).toBe(
      generateCartItemId(productId, attrs2)
    );
  });

  it("returns same id regardless of attribute order (sorts by id)", () => {
    const attrsSizeFirst: SelectedAttribute[] = [
      { id: "Size", value: "40" },
      { id: "Color", value: "Green" },
    ];
    const attrsColorFirst: SelectedAttribute[] = [
      { id: "Color", value: "Green" },
      { id: "Size", value: "40" },
    ];
    expect(generateCartItemId(productId, attrsSizeFirst)).toBe(
      generateCartItemId(productId, attrsColorFirst)
    );
  });

  it("returns different ids for same product with different options", () => {
    const green40: SelectedAttribute[] = [
      { id: "Color", value: "Green" },
      { id: "Size", value: "40" },
    ];
    const blue40: SelectedAttribute[] = [
      { id: "Color", value: "Blue" },
      { id: "Size", value: "40" },
    ];
    const idGreen = generateCartItemId(productId, green40);
    const idBlue = generateCartItemId(productId, blue40);
    expect(idGreen).not.toBe(idBlue);
  });

  it("returns different id for empty attributes vs no attributes", () => {
    const withAttrs = generateCartItemId(productId, [
      { id: "Size", value: "40" },
    ]);
    const noAttrs = generateCartItemId(productId, []);
    expect(withAttrs).not.toBe(noAttrs);
  });
});

describe("findCartItem", () => {
  const product = mockProduct({ id: "prod-1" });

  it("finds existing item with same product and same attributes", () => {
    const attrs: SelectedAttribute[] = [
      { id: "Color", value: "Green" },
      { id: "Size", value: "40" },
    ];
    const cart: CartItem[] = [mockCartItem(product, 1, attrs)];
    const found = findCartItem(cart, product.id, attrs);
    expect(found).toBeDefined();
    expect(found?.quantity).toBe(1);
  });

  it("returns undefined for same product with different attributes", () => {
    const green40: SelectedAttribute[] = [
      { id: "Color", value: "Green" },
      { id: "Size", value: "40" },
    ];
    const blue40: SelectedAttribute[] = [
      { id: "Color", value: "Blue" },
      { id: "Size", value: "40" },
    ];
    const cart: CartItem[] = [mockCartItem(product, 1, green40)];
    const found = findCartItem(cart, product.id, blue40);
    expect(found).toBeUndefined();
  });
});

describe("cart item uniqueness (addToCart logic)", () => {
  const product = mockProduct({ id: "prod-1" });

  it("same product + Color:Green Size:40 and Color:Blue Size:40 → 2 separate items", () => {
    const green40: SelectedAttribute[] = [
      { id: "Color", value: "Green" },
      { id: "Size", value: "40" },
    ];
    const blue40: SelectedAttribute[] = [
      { id: "Color", value: "Blue" },
      { id: "Size", value: "40" },
    ];

    let cart: CartItem[] = [];

    // Simulate first add: Green + 40
    const existing1 = findCartItem(cart, product.id, green40);
    if (existing1) {
      cart = cart.map((item) =>
        item.id === existing1.id
          ? { ...item, quantity: item.quantity + 1 }
          : item
      );
    } else {
      cart = [
        ...cart,
        {
          id: generateCartItemId(product.id, green40),
          product,
          quantity: 1,
          selectedAttributes: green40,
        },
      ];
    }

    // Simulate second add: Blue + 40
    const existing2 = findCartItem(cart, product.id, blue40);
    if (existing2) {
      cart = cart.map((item) =>
        item.id === existing2.id
          ? { ...item, quantity: item.quantity + 1 }
          : item
      );
    } else {
      cart = [
        ...cart,
        {
          id: generateCartItemId(product.id, blue40),
          product,
          quantity: 1,
          selectedAttributes: blue40,
        },
      ];
    }

    expect(cart).toHaveLength(2);
    expect(cart[0].selectedAttributes).toEqual(green40);
    expect(cart[1].selectedAttributes).toEqual(blue40);
    expect(cart[0].quantity).toBe(1);
    expect(cart[1].quantity).toBe(1);
  });

  it("same product + Color:Green Size:40 twice → 1 item with quantity 2", () => {
    const green40: SelectedAttribute[] = [
      { id: "Color", value: "Green" },
      { id: "Size", value: "40" },
    ];

    let cart: CartItem[] = [];

    const add = () => {
      const existing = findCartItem(cart, product.id, green40);
      if (existing) {
        cart = cart.map((item) =>
          item.id === existing.id
            ? { ...item, quantity: item.quantity + 1 }
            : item
        );
      } else {
        cart = [
          ...cart,
          {
            id: generateCartItemId(product.id, green40),
            product,
            quantity: 1,
            selectedAttributes: green40,
          },
        ];
      }
    };

    add();
    add();

    expect(cart).toHaveLength(1);
    expect(cart[0].quantity).toBe(2);
    expect(cart[0].selectedAttributes).toEqual(green40);
  });
});

<?php
/**
 * CartItem — Quản lý giỏ hàng trong session (và hỗ trợ thao tác bảng cart_items).
 */
class CartItem extends BaseModel
{
    protected string $table = 'cart_items';

    public function getSessionItems(): array
    {
        return isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    public function addToSession(array $item): string
    {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $cartId = (string)$item['cart_id'];
        if (isset($_SESSION['cart'][$cartId])) {
            $_SESSION['cart'][$cartId]['quantity'] += (int)$item['quantity'];
        } else {
            $_SESSION['cart'][$cartId] = $item;
        }
        return $cartId;
    }

    public function updateSessionQuantity(string $cartId, int $quantity): bool
    {
        if (!isset($_SESSION['cart'][$cartId])) {
            return false;
        }
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$cartId]);
        } else {
            $_SESSION['cart'][$cartId]['quantity'] = $quantity;
        }
        return true;
    }

    public function removeFromSession(string $cartId): void
    {
        if ($cartId !== '' && isset($_SESSION['cart'][$cartId])) {
            unset($_SESSION['cart'][$cartId]);
        }
    }

    public function clearSession(): void
    {
        $_SESSION['cart'] = [];
    }

    public function getSessionCount(): int
    {
        $count = 0;
        foreach ($this->getSessionItems() as $it) {
            $count += (int)($it['quantity'] ?? 1);
        }
        return $count;
    }

    public function getSessionSubtotal(): float
    {
        $subtotal = 0.0;
        foreach ($this->getSessionItems() as $item) {
            $price = (float)($item['total_price'] ?? ($item['unit_price'] ?? 0));
            $qty = (int)($item['quantity'] ?? 1);
            $subtotal += $price * $qty;
        }
        return $subtotal;
    }
}

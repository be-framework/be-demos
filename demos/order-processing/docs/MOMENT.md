# Moment

Be Frameworkにおける「Moment」の哲学的概念とコードでの役割。

## 三位一体

Momentは三つの側面を持つ：

```text
Moment
  │
  ├── デュナミス（δύναμις）── 実現可能な潜在力
  │
  ├── 契機（モーメント）──── 全体の成立に必要な要素
  │
  └── 自己の一部 ─────────── 外部ではなく内なるもの
```

### 1. デュナミス（潜在態）

アリストテレス的な意味での「δύναμις」。実現を待つ可能態。

- Moment = 潜在態（できる状態）
- `be()` = 現実態への移行（ἐνέργεια）

### 2. 契機

ヘーゲル的な意味での「Moment」。全体を構成する必要不可欠な要素。

```text
OrderConfirmed (Final/全体)
    ├── InventoryReserved  (Moment/契機)
    ├── PaymentCompleted   (Moment/契機)
    └── ShippingArranged   (Moment/契機)
```

全ての契機が揃わなければ、全体は成立しない。

### 3. 自己の一部

Momentは外部の存在ではなく、**Finalの内なる部分**。

```text
Final ≠ 司令官 → 兵士（命令）
Final = 全体が自身の部分を通じて自己完成する
```

## 自己生成としてのbe()

Be Frameworkは自己生成のフレームワーク。Momentへの`be()`呼び出しは**命令ではなく自己完成**。

```php
final readonly class OrderConfirmed
{
    public function __construct(
        public InventoryReserved $inventory,  // 自分の一部
        public PaymentCompleted $payment,     // 自分の一部
        public ShippingArranged $shipping,    // 自分の一部
    ) {
        // 外部への命令ではない
        // 自分自身の完成
        $this->inventory->be();
        $this->payment->be();
        $this->shipping->be();
    }
}
```

### 人の例え：「話す」

```text
「話す」という全体（Final）
    │
    ├── 呼吸（Moment）─── 自分の一部、発声の潜在力
    ├── 声帯（Moment）─── 自分の一部、音の潜在力
    └── 舌唇（Moment）─── 自分の一部、言葉の潜在力

    └── 全てが同時にbe() → 「話す」が成立
```

呼吸・声帯・舌唇は外部ではなく自分自身の一部。
「話す」は外部への命令ではなく、自己の部分の協調による自己実現。

## 同時成立

Momentの`be()`は同時に成立する必要がある。

```text
在庫確保 ─┐
決済完了 ─┼── 全て揃わないと「注文」として存在できない
配送手配 ─┘
```

どれか一つが欠けると、全体（Final）は存在できない。
部分的な実現は全体の不在を意味する。

## Being と Moment の違い

| | Being | Moment |
|---|-------|--------|
| 役割 | 変容の途中経過 | Finalの部分（契機）|
| デュナミス | なし | あり得る |
| `be()` | なし | オプション |

```php
// Being - 変容の途中経過
final readonly class PaymentAuthorized
{
    // コンストラクタで変容完了
}

// Moment（Potentialあり）- be()を持つ
final readonly class PaymentCompleted implements MomentInterface
{
    public function be(): void
    {
        $this->capture->be();
    }
}

// Moment（Potentialなし）- be()不要
final readonly class CustomerInfo
{
    // 純粋なデータ部分、be()なし
}
```

**MomentInterfaceはオプション。** Potentialを持つMomentだけが実装する。

## ライフサイクル

```text
生まれて        → Reasonから生成される
目的が与えられ  → クラス名が本質を定義
成る            → be()で実現（Finalの自己完成の一環として）
```

**「存在が実現に先立つ」**

Momentは存在する。目的（本質）はクラス名で定義されている。
しかしまだ実現していない。Finalの生成時に`be()`で初めて現実になる。

## コードでの表現

### MomentInterface

```php
interface MomentInterface
{
    public function be(): void;
}
```

`be()` - たった一つのメソッドで「実現する」を表現。

### Momentクラス

```php
final readonly class InventoryReserved implements MomentInterface
{
    public InventoryReservation $reservation;

    public function __construct(
        #[ProductId] public string $productId,
        #[Quantity] public int $quantity,
        #[WarehouseId] public string $warehouseId,
        #[Inject] InventoryReserver $reserver,
    ) {
        $this->reservation = $reserver->lock($warehouseId, $productId, $quantity);
    }

    public function be(): void
    {
        $this->reservation->be();
    }
}
```

## 状態管理

状態は変数に格納されない。**オブジェクトの存在そのもの**が状態を表現する。

- Momentが存在する = 準備完了（潜在態）
- Finalが存在する = 実現完了（現実態）

## まとめ

| 概念 | 意味 | コード |
|------|------|--------|
| Moment | 契機 + デュナミス + 自己の一部 | `implements MomentInterface` |
| be() | 自己完成 | Finalの生成時に呼ばれる |
| Final | 全体の自己実現 | 全Momentの`be()`で完成 |

Be Frameworkにおいて、Momentは外部への命令対象ではない。
**自己の一部であり、全体の自己実現における契機**。

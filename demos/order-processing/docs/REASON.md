# Reason

Be Frameworkにおける「Reason」の役割と設計指針。

## Reasonとは

Reasonは**イマナンス（内在）が出会う超越**。システム内部のデータが外部世界と接触する点。

```text
イマナンス（内在）          超越
─────────────────────────────────────
システム内部のデータ    ←→    外部システム / ドメインルール
#[Input]で流入          │
cardNumber, amount      │
                        │
                     Reason
                    （門）
```

## 二種類の超越

Reasonが接続する「超越」には二種類ある。

### 1. 外部システム

システムの「外側」にある技術的境界。

```php
final class PaymentGateway        // 決済API
final class InventoryReserver     // 在庫管理システム
final class ShippingArranger      // 配送業者API
```

### 2. ドメインルール

個別インスタンスから独立して存在する普遍的規則。

```php
final class JTASProtocol          // 医療トリアージプロトコル
final class TaxCalculator         // 税計算ルール
final class CreditPolicy          // 与信ポリシー
```

## 命名パターン

役割に応じた命名を推奨：

| 役割 | パターン | 例 |
|------|----------|-----|
| 外部API接続 | Gateway, Client | PaymentGateway, InventoryClient |
| 判定・評価 | Protocol, Policy, Evaluator | JTASProtocol, CreditPolicy |
| 変換・計算 | Calculator, Resolver, Converter | TaxCalculator, AddressResolver |
| 検証 | Validator | AddressValidator |

**統一するより、役割を表す名前**が適切。

## 設計原則

### 1. ステートレス

Reasonは状態を持たない。状態はMomentが持つ。

```php
// 良い例 - ステートレス
final class PaymentGateway
{
    public function authorize(string $cardNumber, int $amount): PaymentCapture
    {
        // 状態を持たない
        // Momentを返す
    }
}

// 悪い例 - 状態を持つ
final class PaymentGateway
{
    private array $authorizations = [];  // NG: 状態

    public function authorize(...): string { ... }
    public function capture(string $authCode): string { ... }
}
```

### 2. Momentを返す

外部システムとの対話の結果として、Momentを返す。

```php
public function authorize(string $cardNumber, int $amount): PaymentCapture
{
    $authCode = $this->api->authorize($cardNumber, $amount);

    return new PaymentCapture(
        $authCode,
        $amount,
        fn () => $this->capture($authCode, $amount),
    );
}
```

Momentには`be()`のためのコールバックが含まれる。

### 3. インターフェースでバインド

テスタビリティのため、インターフェースを経由してDIコンテナでバインド。

```php
// インターフェース
interface PaymentGatewayInterface
{
    public function authorize(string $cardNumber, int $amount): PaymentCapture;
}

// 実装
final class PaymentGateway implements PaymentGatewayInterface { ... }

// バインド
$this->bind(PaymentGatewayInterface::class)->to(PaymentGateway::class);

// テストではモックに差し替え可能
```

## Type Match

Reasonはドメインロジックによる型決定にも使われる。

```php
final class JTASProtocol
{
    public function assess(float $temperature, int $heartRate): string
    {
        if ($temperature > 39.0 || $heartRate > 120) {
            return 'emergency';
        }
        return 'observation';
    }
}
```

戻り値の型が次のBeingの型を決定する（Type IS Capability）。

## Reasonの位置づけ

```text
Input
  │
  ├── Being ← Reasonを注入（変容に必要なロジック）
  │     │
  │     └── Moment ← Reasonから生成（潜在態）
  │           │
  └───────────┴── Final ← Momentのbe()で完成
```

- **Being**: Reasonを使って変容
- **Moment**: Reasonから生まれる（Reasonのメソッドが返す）

## まとめ

| 特性 | 説明 |
|------|------|
| 役割 | イマナンスと超越の接点 |
| 状態 | ステートレス |
| 戻り値 | Moment（潜在態） |
| バインド | インターフェース経由 |
| 命名 | 役割に応じて選択 |

Reasonは「門」であり、内と外を繋ぐ。しかし自身は何も保持しない。

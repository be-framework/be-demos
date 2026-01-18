# FAQ

Be Frameworkに関するよくある質問と回答。

## 設計に関する質問

### Q: Reasonに状態を持たせるべきですか？

**A: いいえ。Reasonはステートレスであるべきです。**

Reasonは「超越への門」であり、状態を保持しません。状態はMomentが持ちます。

```php
// NG: Reasonが状態を持つ
final class PaymentGateway
{
    private array $authorizations = [];

    public function authorize(...): string
    {
        $this->authorizations[$authCode] = [...];  // 状態保持
    }
}

// OK: Momentが状態を持つ
final class PaymentGateway
{
    public function authorize(...): PaymentCapture
    {
        return new PaymentCapture($authCode, fn () => $this->capture(...));
    }
}
```

---

### Q: 仮の状態（provisional state）はどこにあるべきですか？

**A: Momentの存在自体が仮の状態です。**

「仮予約」「仮決済」などの状態は、変数やフラグではなく**オブジェクトの存在**で表現します。

- Momentが存在する = 準備完了（潜在態）
- Finalが存在する = 実現完了（現実態）

---

### Q: ロールバック処理はどう実装しますか？

**A: ロールバックではなく、「仮状態 → 実現」のパターンを使います。**

Be Frameworkでは状態を巻き戻すのではなく、Momentの`be()`が呼ばれなければ実現されません。

```
Moment生成 → 仮の状態（デュナミス）
be()呼び出し → 実現（エネルゲイア）
be()呼ばれず → 実現されない（自然消滅）
```

---

### Q: `#[Input]`と`#[Inject]`の違いは何ですか？

**A: データの出所が違います。**

| 属性 | 出所 | 意味 |
|------|------|------|
| `#[Input]` | ソースオブジェクト | イマナンス（内在）からの流入 |
| `#[Inject]` | DIコンテナ | 超越（Reason等）の注入 |

```php
final readonly class PaymentCompleted
{
    public function __construct(
        #[Input] public string $cardNumber,   // Inputから流れてくる
        #[Input] public int $amount,          // Inputから流れてくる
        #[Inject] PaymentGateway $gateway,    // DIコンテナから注入
    ) { }
}
```

---

### Q: ALPSは制約を強制しますか？

**A: いいえ。ALPSはセマンティクスを定義します。制約ではありません。**

ALPSは語彙と関係性を定義するオントロジー。制約やバリデーションは別のレイヤーで行います。

---

### Q: 遅延評価はどう実装しますか？

**A: Momentとして表現します。**

遅延評価したい処理をMomentとして表現し、`be()`で実行します。

```php
final class LazyComputation implements MomentInterface
{
    private $compute;

    public function __construct(callable $compute)
    {
        $this->compute = $compute;
    }

    public function be(): void
    {
        ($this->compute)();
    }
}
```

---

## 概念に関する質問

### Q: BeingとMomentの違いは何ですか？

**A: Momentはデュナミス（潜在力）を持ちうる存在です。**

| | Being | Moment |
|---|-------|--------|
| デュナミス | なし | あり得る |
| `be()` | なし | オプション |
| 性質 | 純粋な変容 | Finalの部分 |

Beingは変容の途中経過。MomentはFinalの部分（契機）であり、Potentialを持つ場合は`be()`で実現できます。

---

### Q: MomentInterfaceは必須ですか？

**A: いいえ。オプションです。**

Potentialを持つMomentだけがMomentInterfaceを実装します。

```php
// Potentialを持つMoment → MomentInterface実装
final readonly class InventoryReserved implements MomentInterface
{
    public function be(): void { ... }
}

// Potentialを持たないMoment → インターフェース不要
final readonly class CustomerInfo
{
    // be()なし - 純粋なデータ部分
}
```

全てのMomentが`be()`を必要とするわけではありません。

---

### Q: Momentは外部への命令ですか？

**A: いいえ。Momentは自己の一部です。**

Be Frameworkは自己生成のフレームワーク。Finalの`be()`呼び出しは命令ではなく自己完成です。

```
Final ≠ 司令官 → 兵士（命令）
Final = 全体が自身の部分を通じて自己完成する
```

「話す」時に呼吸・声帯・舌は外部ではなく自分自身の一部。同様に、OrderConfirmedにとってInventory・Payment・Shippingは自己の一部です。

---

### Q: なぜ`be()`という名前ですか？

**A: 「存在する」「成る」を一語で表現するためです。**

- クラス名が「何になるか」を定義（PaymentCapture = 決済を確定する存在）
- `be()`は「それになれ」を命じる

```php
$payment->capture->be();  // 決済確定として「成れ」
```

「存在が実現に先立つ」- 生まれて、目的が与えられ、そして成る。

---

### Q: Reasonの命名規則は？

**A: 役割に応じて選択します。**

| 役割 | パターン | 例 |
|------|----------|-----|
| 外部API接続 | Gateway, Client | PaymentGateway |
| 判定・評価 | Protocol, Policy | JTASProtocol |
| 変換・計算 | Calculator, Resolver | TaxCalculator |
| 検証 | Validator | AddressValidator |

統一するより、役割を表す名前が適切です。

---

## テストに関する質問

### Q: テスタビリティは大丈夫ですか？

**A: Reasonはインターフェースでバインドされ、モック可能です。**

```php
// インターフェース定義
interface PaymentGatewayInterface
{
    public function authorize(...): PaymentCapture;
}

// テスト時
$mock = $this->createMock(PaymentGatewayInterface::class);
$mock->method('authorize')->willReturn(new PaymentCapture(...));
```

Being/Moment/FinalはReasonに依存し、Reasonはインターフェースでバインドされるため、テスト時に差し替え可能です。

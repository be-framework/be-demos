# BE Framework デモ集

BE Frameworkの存在論的プログラミングアプローチを示すデモプロジェクト集です。

## 哲学

BE Frameworkは「Be, Don't Do（するな、あれ）」の原則を体現しています。ソフトウェアを動作の連続ではなく、存在の変容（メタモルフォーシス）としてモデル化します。各デモは6つの哲学的レイヤーを通じて異なる変容パターンを示します：

| レイヤー | 語源 | 役割 |
|---------|------|------|
| **Input** | δύναμις（デュナミス） | システムに入る生の可能態 |
| **Being** | Dasein（現存在） | 計算されたプロパティを持つ存在状態 |
| **Moment** | 契機 | 遅延Potentialを持つ過渡的段階 |
| **Final** | ἐνέργεια（エネルゲイア） | 完全に現実化された結果 |
| **Semantic** | Sinn（意味） | ドメイン検証ルール |
| **Reason** | 充足理由律 | ビジネスロジックと外部連携 |

## デモカタログ

### 初級

#### [hello-world](./demos/hello-world/)
**パターン:** 最小変換
**フロー:** `Input → Final`

最もシンプルなBE Frameworkデモ。BeingやMomentレイヤーを持たない挨拶変換です。

```text
HelloInput → Hello
```

#### [contact-form](./demos/contact-form/)
**パターン:** 線形変換
**フロー:** `Input → Being → Final`

最もシンプルなBE Frameworkパターン。基本的な入力検証、メール正規化、受領証生成を示すお問い合わせフォームです。

```text
ContactInput → EmailNormalized → ContactReceived
```

#### [user-registration](./demos/user-registration/)
**パターン:** 連鎖チェーン
**フロー:** `Input → Being(A) → Being(B) → Being(C) → Final`

Being変換を連鎖させたユーザー登録：メール検証、パスワードハッシュ化、プロフィール拡充。

```text
RegistrationInput → EmailVerified → PasswordHashed → ProfileEnriched → UserRegistered
```

### 中級

#### [order-processing](./demos/order-processing/)
**パターン:** ダイヤモンドメタモルフォーシス
**フロー:** `Input → [並列Beings] → [並列Moments] → Final`

並列Beingチェーン（在庫、決済、配送）がMomentを生成し、Final状態で収束するECオーダー処理。

```text
OrderInput ─┬→ StockLocated → QuantityChecked   → InventoryReserved ─┬→ OrderConfirmed
            ├→ CardValidated → PaymentAuthorized → PaymentCompleted  ─┤
            └→ AddressValidated → CarrierSelected → ShippingArranged ─┘
```

#### [blog-publishing](./demos/blog-publishing/)
**パターン:** 連鎖変換（3 Being、2 Moment）
**フロー:** `Input → Moment → Being → Being → Moment → Being → Final`

マークダウンレンダリング、スラグ生成、抜粋抽出、著者解決を段階的に処理する記事公開デモ。BeingクラスはArticlePrepared、MarkdownRendered、SlugGenerated。MomentクラスはContentPreparedとMetadataResolved。

```text
ArticleInput → ContentPrepared → ArticlePrepared → MarkdownRendered → MetadataResolved → SlugGenerated → ArticlePublished
```

### 上級

#### [medical-triage](./demos/medical-triage/)
**パターン:** 分岐メタモルフォーシス
**フロー:** `Input → Being → [分岐] → Final(A) | Final(B) | Final(C)`

JTASプロトコルを実装した救急トリアージ。1つの入力が型付き`$being`識別子を通じて3つの異なるFinalに分岐し、各分岐の振る舞いはReason戦略クラスが保持します。

```text
PatientInput → TriageLevelDetermined
                      │
       ┌──────────────┼──────────────┐
       ↓              ↓              ↓
  [ImmediateCase] [UrgentCase] [NonUrgentCase]
       ↓              ↓              ↓
EmergencyAdmitted  UrgentQueued  OutpatientReferred
```

#### [loan-application](./demos/loan-application/)
**パターン:** カスケードダイヤモンド（2段階）
**フロー:** `Input → Stage1(並列 → 収束) → Stage2(並列 → Final)`

段階的Moment実現を伴う住宅ローン申請。Stage 1のMomentは適格性確認時に実現、Stage 2のMomentは最終承認時に実現。

```text
LoanInput → IdentityVerified ─┬→ CreditScored   → CreditApproved   ─┬→ EligibilityConfirmed
                              └→ IncomeAssessed → IncomeApproved   ─┘
                                                                     ↓
                              ┌→ PropertyAppraised → CollateralValued ─┬→ LoanApproved
                              └→ InsuranceQuoted   → InsurancePrepared ─┘
```

#### [insurance-claim](./demos/insurance-claim/)
**パターン:** 複合収束
**フロー:** `Input(A) + Input(B) → 収束 → 並列(3) → 分岐 → Final(A) | Final(B)`

複数入力の収束、3方向並列評価、分岐Finalを持つ保険請求処理。

```text
ClaimInput ──┬→ ClaimRegistered ─┬→ ClaimValidated ─┬→ DamageAssessed  ─┬→ [閾値判定] → ClaimSettled
PolicyInput ─┴→ PolicyVerified  ─┘                  ├→ AdjusterAssigned ─┤               または
                                                    └→ FraudScreened   ─┘               ClaimEscalated
```

## パターン一覧

| パターン | デモ | 入力数 | Being数 | Moment数 | Final数 |
|---------|------|--------|---------|----------|---------|
| 最小 | hello-world | 1 | 0 | 0 | 1 |
| 線形 | contact-form | 1 | 1 | 0 | 1 |
| 連鎖 | user-registration | 1 | 3 | 0 | 1 |
| ダイヤモンド | order-processing | 1 | 6 | 6 | 1 |
| 連鎖 | blog-publishing | 1 | 3 | 2 | 1 |
| 分岐 | medical-triage | 1 | 1 | 0 | 3 |
| カスケード | loan-application | 1 | 5 | 4 | 1 |
| 複合 | insurance-claim | 2 | 5 | 3 | 2 |

## テスト実行

各デモには以下をカバーする包括的なテストが含まれます：
- 正常系統合テスト
- Semantic検証単体テスト
- Reasonレイヤーロジックテスト
- Potential冪等性テスト（該当する場合）

```bash
# 全テスト実行
composer test

# 特定デモのテスト実行
./demos/vendor/bin/phpunit demos/medical-triage/tests/
```

## 要件

- PHP 8.2+
- [Ray.Di](https://ray-di.github.io/)（依存性注入）

## ライセンス

MIT

---

[English version](./README.md)
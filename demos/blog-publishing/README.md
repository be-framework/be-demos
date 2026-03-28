# Blog Publishing Demo

**Level:** Intermediate
**Concepts:** Moment WITHOUT Potential (pure data Moment), mini-diamond merge pattern

## Flow

```
ArticleInput -> MarkdownRendered(Being) -> SlugGenerated(Being)
    -> [ContentPrepared(Moment) + MetadataResolved(Moment)] -> ArticlePublished(Final)
```

## Key Concept: Pure Data Moments

In the order-processing demo, Moments implement `MomentInterface` and carry **Potential** -- deferred side effects that are realized via `be()` in the Final stage (e.g., capturing a payment, dispatching a shipment).

This demo shows the other kind of Moment: **pure data Moments**. These are "Moments" in the Hegelian sense only -- they are *parts of a whole* (ArticlePublished) that aggregate data from upstream Beings without carrying any Potential.

### What makes them different

| | Order Processing (with Potential) | Blog Publishing (pure data) |
|---|---|---|
| Implements `MomentInterface` | Yes | **No** |
| Has `be()` method | Yes | **No** |
| Carries Potential object | Yes (PaymentCapture, etc.) | **No** |
| Final calls `be()` | Yes | **No** |
| Role | Part + deferred action | Part only (data aggregation) |

### Why use pure data Moments?

When the transformation from Input to Final involves no side effects -- no payments to capture, no reservations to make, no external calls to finalize -- a Moment serves purely as a structural grouping. It gathers related data from multiple upstream Beings into a coherent "part" that the Final can consume.

In this demo:
- **ContentPrepared** aggregates title, markdown, HTML body, and excerpt
- **MetadataResolved** aggregates slug, author info, and tags

The Final (ArticlePublished) simply reads their data directly, with no `be()` calls needed.

## Layer Breakdown

- **Input:** ArticleInput (title, markdownBody, authorId, tags)
- **Being:** MarkdownRendered (produces htmlBody), SlugGenerated (produces slug)
- **Moment:** ContentPrepared (aggregates content data), MetadataResolved (aggregates metadata)
- **Final:** ArticlePublished (merges both Moments, stamps publication)
- **Semantic:** ArticleTitle, MarkdownBody, AuthorId, Tag (validation)
- **Reason:** MarkdownRenderer, SlugGenerator, ExcerptExtractor, AuthorResolver, PublishTimestamper

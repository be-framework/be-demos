<?php

declare(strict_types=1);

namespace Be\Demo\BlogPublishing\Reason;

/**
 * Markdown Renderer - Reason (stateless service)
 *
 * Converts markdown text to HTML.
 * Simple demo implementation handling paragraphs, headings, bold, and italic.
 */
final class MarkdownRenderer
{
    public function render(string $markdown): string
    {
        $lines = explode("\n", $markdown);
        $html = '';
        $inParagraph = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Empty line closes paragraph
            if ($trimmed === '') {
                if ($inParagraph) {
                    $html .= "</p>\n";
                    $inParagraph = false;
                }
                continue;
            }

            // Headings
            if (preg_match('/^(#{1,6})\s+(.+)$/', $trimmed, $matches)) {
                if ($inParagraph) {
                    $html .= "</p>\n";
                    $inParagraph = false;
                }
                $level = strlen($matches[1]);
                $text = $this->renderInline($matches[2]);
                $html .= "<h{$level}>{$text}</h{$level}>\n";
                continue;
            }

            // Paragraph text
            if (!$inParagraph) {
                $html .= '<p>';
                $inParagraph = true;
            } else {
                $html .= ' ';
            }
            $html .= $this->renderInline($trimmed);
        }

        if ($inParagraph) {
            $html .= "</p>\n";
        }

        return trim($html);
    }

    private function renderInline(string $text): string
    {
        // Bold: **text**
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text) ?? $text;

        // Italic: *text*
        $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text) ?? $text;

        return $text;
    }
}

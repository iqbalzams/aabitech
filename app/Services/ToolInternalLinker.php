<?php

namespace App\Services;

use App\Models\Tool;
use DOMDocument;
use DOMElement;
use DOMText;
use DOMXPath;

class ToolInternalLinker
{
    protected int $maxLinks = 5;

    protected int $insertedCount = 0;

    protected array $insertedTargets = [];

    /**
     * Reset page-level linking state.
     */
    public function reset(): void
    {
        $this->insertedCount = 0;
        $this->insertedTargets = [];
    }

    /**
     * Link a single SEO section while preserving
     * the page-wide link limit.
     */
    public function link(string $html, ?Tool $currentTool = null): string
    {
        if (! $currentTool || trim($html) === '') {
            return $html;
        }

        if ($this->insertedCount >= $this->maxLinks) {
            return $html;
        }

        $rules = config("tool_internal_links.{$currentTool->slug}", []);

        if (empty($rules)) {
            return $html;
        }

        $targets = $this->resolveTargets($rules, $currentTool);

        if (empty($targets)) {
            return $html;
        }

        return $this->processHtml($html, $targets);
    }

    protected function resolveTargets(array $rules, Tool $currentTool): array
    {
        $slugs = array_keys($rules);

        if (empty($slugs)) {
            return [];
        }

        $tools = Tool::query()
            ->where('status', true)
            ->whereIn('slug', $slugs)
            ->whereKeyNot($currentTool->id)
            ->get()
            ->keyBy('slug');

        $targets = [];

        foreach ($rules as $slug => $anchors) {
            if (! isset($tools[$slug])) {
                continue;
            }

            if (empty($anchors)) {
                continue;
            }

            /*
             * A destination is only allowed to receive
             * one contextual link per page.
             */
            if (isset($this->insertedTargets[$slug])) {
                continue;
            }

            $targets[$slug] = [
                'tool' => $tools[$slug],
                'anchors' => $anchors,
            ];
        }

        return $targets;
    }

    protected function processHtml(string $html, array $targets): string
    {
        if ($this->insertedCount >= $this->maxLinks) {
            return $html;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');

        libxml_use_internal_errors(true);

        $wrappedHtml =
            '<div id="aabitech-internal-link-root">' .
            $html .
            '</div>';

        $dom->loadHTML(
            '<?xml encoding="UTF-8">' . $wrappedHtml,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        $root = $dom->getElementById(
            'aabitech-internal-link-root'
        );

        if (! $root) {
            return $html;
        }

        $textNodes = $xpath->query(
            './/text()[
                not(ancestor::a)
                and not(ancestor::code)
                and not(ancestor::pre)
                and not(ancestor::script)
                and not(ancestor::style)
                and not(ancestor::h1)
                and not(ancestor::h2)
                and not(ancestor::h3)
                and not(ancestor::h4)
                and not(ancestor::h5)
                and not(ancestor::h6)
            ]',
            $root
        );

        if (! $textNodes) {
            return $html;
        }

        foreach ($textNodes as $textNode) {

            if ($this->insertedCount >= $this->maxLinks) {
                break;
            }

            if (! $textNode instanceof DOMText) {
                continue;
            }

            $text = $textNode->nodeValue;

            if (trim($text) === '') {
                continue;
            }

            foreach ($targets as $slug => $target) {

                if ($this->insertedCount >= $this->maxLinks) {
                    break 2;
                }

                if (isset($this->insertedTargets[$slug])) {
                    continue;
                }

                foreach ($target['anchors'] as $anchor) {

                    if (
                        ! is_string($anchor) ||
                        trim($anchor) === ''
                    ) {
                        continue;
                    }

                    if (! $this->containsPhrase($text, $anchor)) {
                        continue;
                    }

                    $link = $this->createLink(
                        $dom,
                        $target['tool'],
                        $anchor
                    );

                    if (! $link) {
                        continue;
                    }

                    if (! $this->replaceFirstOccurrence(
                        $textNode,
                        $anchor,
                        $link
                    )) {
                        continue;
                    }

                    $this->insertedTargets[$slug] = true;
                    $this->insertedCount++;

                    break;
                }
            }
        }

        $output = '';

        foreach ($root->childNodes as $child) {
            $output .= $dom->saveHTML($child);
        }

        return $output ?: $html;
    }

    protected function containsPhrase(
        string $text,
        string $phrase
    ): bool {
        return preg_match(
            '/' . preg_quote($phrase, '/') . '/iu',
            $text
        ) === 1;
    }

    protected function createLink(
        DOMDocument $dom,
        Tool $tool,
        string $anchor
    ): ?DOMElement {
        $link = $dom->createElement('a');

        $link->setAttribute(
            'href',
            route('tools.tool', [
                'slug' => $tool->slug,
            ])
        );
        $link->setAttribute(
            'class',
            'aabitech-contextual-link'
        );

        $link->appendChild(
            $dom->createTextNode($anchor)
        );

        return $link;
    }

    protected function replaceFirstOccurrence(
        DOMText $textNode,
        string $phrase,
        DOMElement $link
    ): bool {
        $text = $textNode->nodeValue;

        $pattern =
            '/' . preg_quote($phrase, '/') . '/iu';

        if (
            ! preg_match(
                $pattern,
                $text,
                $matches,
                PREG_OFFSET_CAPTURE
            )
        ) {
            return false;
        }

        $matchedText = $matches[0][0];
        $offset = $matches[0][1];

        $before = substr(
            $text,
            0,
            $offset
        );

        $after = substr(
            $text,
            $offset + strlen($matchedText)
        );

        $parent = $textNode->parentNode;

        if (! $parent) {
            return false;
        }

        if ($before !== '') {
            $parent->insertBefore(
                $textNode->ownerDocument->createTextNode($before),
                $textNode
            );
        }

        $parent->insertBefore(
            $link,
            $textNode
        );

        if ($after !== '') {
            $parent->insertBefore(
                $textNode->ownerDocument->createTextNode($after),
                $textNode
            );
        }

        $parent->removeChild($textNode);

        return true;
    }
}
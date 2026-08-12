<?php

namespace App\Console\Prompts;

use Laravel\Prompts\Themes\Default\TextPromptRenderer;

/**
 * Renders the text prompt box across the full terminal width.
 *
 * DrawsBoxes clamps the box to `min($minWidth, cols - 6)`, so a very large
 * minimum width always resolves to the widest box the terminal allows.
 */
class FullWidthTextPromptRenderer extends TextPromptRenderer
{
    protected int $minWidth = PHP_INT_MAX;
}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment;

#[CoversNothing]
final class MarkdownRenderingTest extends KernelTestCase
{
    public function testMarkdownIsRendered(): void
    {
        $this->assertSame("<p><strong>Warsaw</strong></p>\n", $this->render('**Warsaw**'));
    }

    public function testRawHtmlFromModelIsEscaped(): void
    {
        $html = $this->render('<img src=x onerror=alert(1)>');

        $this->assertStringNotContainsString('<img', $html);
        $this->assertStringContainsString('&lt;img src=x onerror=alert(1)&gt;', $html);
    }

    public function testUnsafeLinksAreDropped(): void
    {
        $this->assertStringNotContainsString('javascript:', $this->render('[click](javascript:alert(1))'));
    }

    private function render(string $content): string
    {
        $twig = static::getContainer()->get('twig');
        \assert($twig instanceof Environment);

        return $twig->createTemplate('{{ content|markdown_to_html }}')->render(['content' => $content]);
    }
}

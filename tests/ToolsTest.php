<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../toolbox/tools.php';

class ToolsTest extends TestCase
{
    public function testEscapesHtmlTags()
    {
        $this->assertSame('&lt;script&gt;', e('<script>'));
    }

    public function testEscapesAmpersandAndQuotes()
    {
        $this->assertSame('Tom &amp; Jerry', e('Tom & Jerry'));
        $this->assertSame('&quot;test&quot;', e('"test"'));
    }

    public function testEscapeReturnsEmptyString()
    {
        $this->assertSame('', e(''));
    }

    public function testPreviewKeepsShortText()
    {
        $text = 'Short text';
        $this->assertSame($text, preview($text));
    }

    public function testPreviewTruncatesLongText()
    {
        $result = preview(str_repeat('a', 150), 100);

        $this->assertSame(103, strlen($result)); // 100 + "..."
        $this->assertStringEndsWith('...', $result);
    }

    public function testPreviewUsesCustomLength()
    {
        $this->assertSame('abcde...', preview('abcdefghij', 5));
    }

    public function testVehicleTypeReturnsSaleLabel()
    {
        $this->assertSame('Vente', vehicleType('sale'));
    }

    public function testVehicleTypeReturnsRentLabel()
    {
        $this->assertSame('Location', vehicleType('rent'));
    }

    public function testAppStatusTranslatesKnownStatuses()
    {
        $this->assertSame('En attente', appStatus('pending'));
        $this->assertSame('Accepté', appStatus('accepted'));
        $this->assertSame('Refusé', appStatus('refused'));
    }

    public function testAppStatusReturnsRawValueWhenUnknown()
    {
        $this->assertSame('unknown', appStatus('unknown'));
    }
}

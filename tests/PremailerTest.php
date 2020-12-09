<?php
/**
 * PremailerTest
 *
 * Created 10/7/15 12:29 PM
 * Testing Premailer function
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Pbc\Premailer
 * @subpackage Subpackage
 */

namespace Pbc;


use PHPUnit\Framework\TestCase;

class PremailerTest extends TestCase
{

    protected $htmlUrl = 'http://www.quackit.com/html/templates/download/preview.cfm?template=/html/templates/layout_templates/2_column_left_menu.cfm';

    public function testPremailerFromHtmlReturnsHtml()
    {
        $faker = \Faker\Factory::create();
        $paragraph = $faker->paragraph;
        $css  = 'font-weight: 700; font-size: 12px;';
        $htmlIn = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">\n<html>\n<head><style>.paragraph {".$css."}</style></head>\n<body>\n<p class=\"paragraph\">".$paragraph."</p>\n</body>\n</html>";
        $htmlOut = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">\n<html>\n<head><style>.paragraph {".$css."}</style></head>\n<body>\n<p class=\"paragraph\" style=\"". $css ."\">".$paragraph."</p>\n</body>\n</html>";

        $premailer = new Premailer();
        $premailed = $premailer::html($htmlIn);
        $this->assertArrayHasKey('html', $premailed, 'response from premailer has key html');
        $this->assertEquals(trim($htmlOut), trim($premailed['html']));
    }


    public function testPremailerFromHtmlReturnsPainTextVersion()
    {
        $faker = \Faker\Factory::create();
        $paragraph = $faker->paragraph;
        $css  = 'font-weight: 700; font-size: 12px;';
            $htmlIn = "<html>\n<head><style>.paragraph {".$css."}</style></head>\n<body>\n<p class=\"paragraph\">".$paragraph."</p>\n</body>\n</html>";

        $premailed = Premailer::html($htmlIn);
        $this->assertArrayHasKey('plain', $premailed, 'response from premailer has key plain');
        $this->assertEquals(wordwrap($paragraph, 65), trim($premailed['plain']));
    }

    public function testPremailerFromUrlReturnsHtmlVersion()
    {
        $htmlIn = file_get_contents($this->htmlUrl);
        $premailedHtml = Premailer::html($htmlIn);

        // now get from url and verify returns as the same
        $premailedUrl = Premailer::url($this->htmlUrl);
        $this->assertArrayHasKey('html', $premailedUrl);
        $this->assertEquals($premailedUrl['html'], $premailedHtml['html']);

    }

    public function testPremailerFromUrlReturnsPlainTextVersion()
    {
        $htmlIn = file_get_contents($this->htmlUrl);
        $premailedHtml = Premailer::html($htmlIn);

        // now get from url and verify returns as the same
        $premailedUrl = Premailer::url($this->htmlUrl);
        $this->assertArrayHasKey('plain', $premailedUrl);
        $this->assertEquals($premailedUrl['plain'], $premailedHtml['plain']);

    }
}

<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\Formatter;

use Chgk\ChgkDb\Parser\Result\Package;

class HtmlFormatter implements FormatterInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public static function create()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__.'/twig');
        $cache = sys_get_temp_dir().'/chgkdb-formatter-cache';
        $twig = new \Twig_Environment($loader, ['cache' => $cache]);

        return new self($twig);
    }

    public function __construct(\Twig_Environment $twigEnvironment)
    {
        $this->twig = $twigEnvironment;
        $this->twig->addFilter(new \Twig_Filter('chgkdb_field', [$this, 'fieldFilter'], array('is_safe' => array('all'))));
    }

    public function format(Package $package)
    {
        try {
            return $this->twig->render('package.html.twig', ['package' => $package]);
        } catch (\Twig_Error $e) {
            throw new \RuntimeException('Twig error: '.$e->getMessage(), 0, $e);
        }
    }

    public function fieldFilter(string $string)
    {
        $lines = preg_split('/\r\n|\r|\n/', $string);
        $p = "";
        $r = "";
        $result = '';
        $inRazdatka = false;
        $inPre = false;
        foreach ($lines as $line) {
            if (preg_match('/^(.*?)<раздатка>(.*)$/u', $line, $matches)){
                $p.=$matches[1];
                $this->finishParagraph($result, $p, false, $inPre);
                $inRazdatka = true;
                $line = preg_replace('/^\s+/', '', $matches[2]);
            }
            if (preg_match('#(.*)</раздатка>(.*)$#', $line, $matches)) {
                $p.=$matches[1];
                $this->finishParagraph($r, $p, true, $inPre);
                $result.="<div class=\"razdatka\">$r</div>";
                $line = preg_replace('/^\s+/', '', $matches[2]);
                $inRazdatka = false;
            }

            if (preg_match('/^\|/', $line)) {
                if (!$inPre) {
                    $this->finishParagraph($result, $p, $inRazdatka, $inPre);
                }
                $inPre = true;
                $line = preg_replace('/^\|/', '', $line);
            } else {
                $inPre = false;
            }

            if (preg_match('/^\s+/', $line) && !$inPre) {
                $this->finishParagraph($result, $p, $inRazdatka);
                if (!$result) {
                    $result = "\n";
                }
            }

            if (preg_match('/^(.*)?\(aud:\s*(\S*)\s*\)(.*)$/', $line, $matches)) {
                $p.=$matches[1];
                $sound = $matches[2];
                $line = $matches[3];
                if (!preg_match('/^https?:/', $sound)) {
                    $sound = 'http://db.chgk.info/sounds/db/'.$sound;
                }

                $this->finishParagraph($result, $p, $inRazdatka, $inPre);

                $result.=sprintf("<p><audio src=\"%s\" controls></audio></p>\n", $sound);
            }

            if (preg_match_all('/\(pic:\s*(\S*)\s*\)/', $line, $matches)) {
                if (!$result) {
                    $result = "\n";
                }
                $pics = $matches[1];
                foreach ($pics as &$pic) {
                    if (!preg_match('/^https?:/', $pic)) {
                        $pic = 'http://db.chgk.info/images/db/'.$pic;
                    }
                    $pic = "<img src=\"$pic\" alt=\"\"/>";

                }
                foreach ($pics as $pic) {
                    $line = preg_replace('/\(pic:\s*(\S*)\s*\)/', $pic, $line, 1);
                }
            }
            $line = preg_replace('/(\s+)-+(\s+|$)/','\1&mdash;$2', $line);

            $p.="$line\n";
        }

        $this->finishParagraph($result, $p, $inRazdatka, $inPre);

        return $result;
    }

    /**
     * @param $result
     * @param $p
     * @param bool $inRazdatka
     */
    private function finishParagraph(&$result, &$p, $inRazdatka = false, $inPre = false)
    {
        if (!$p) {
            return;
        }
        if ($inPre) {
            $inRazdatka = false;
            $result.="<pre>\n$p</pre>\n";
        } else {
            $result .= "<p".($result && !$inRazdatka ? '' : ' class="first"').">$p</p>\n";
        }
        $p = '';
    }
}

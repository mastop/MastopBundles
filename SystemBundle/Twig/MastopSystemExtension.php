<?php

namespace Mastop\SystemBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_Function_Method;
use Twig_Filter_Method;
use DateTime;
use IntlDateFormatter;

class MastopSystemExtension extends Twig_Extension {

    protected $container;
    protected $dateFormatter;

    /**
     * Constructor.
     *
     * @param Router $router A Router instance
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions() {
        $mappings = array(
            'mastop_session' => 'getSession',
            'mastop_user_text' => 'userText',
            'mastop_shorten' => 'shorten',
            'mastop_current_url' => 'getCurrentUrl',
            'mastop_date' => 'formatDate',
            'mastop_param' => 'getParam',
            'mastop_cache' => 'getCache',
            'mastop_protocol' => 'getProtocol',
            'debug' => 'debug',
            'gravatar' => 'gravatar'
        );

        $functions = array();
        foreach ($mappings as $twigFunction => $method) {
            $functions[$twigFunction] = new Twig_Function_Method($this, $method, array('is_safe' => array('html')));
        }

        return $functions;
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters() {
        $filters = array(
            // formatting filters
            'date' => new Twig_Filter_Method($this, 'formatDate'),
            'money' => new Twig_Filter_Method($this, 'formatMoney'),
        );

        return $filters;
    }

    public function formatDate($date, $format = null) {
        if (!$date instanceof DateTime) {
            $date = new DateTime((ctype_digit($date) ? '@' : '') . $date);
        }
        if ($format) {
            return $date->format($format);
        }
        if (null === $this->dateFormatter) {
            $this->dateFormatter = new IntlDateFormatter(
                            $this->container->get('session')->getLocale(),
                            IntlDateFormatter::MEDIUM,
                            IntlDateFormatter::SHORT
            );
        }

        // for compatibility with PHP 5.3.3
        $date = $date->getTimestamp();

        return $this->dateFormatter->format($date);
    }
    public function formatMoney($money, $format = '%.2n') {
        // @TODO: Ver se esta é a melhor maneira de exibir dados monetários
        setlocale(LC_MONETARY, $this->container->getParameter('locale'));
        return money_format($format, $money);
    }

    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    protected function autoLink($text) {
        return preg_replace_callback('~
            (                       # leading text
                <\w+.*?>|             #   leading HTML tag, or
                [^=!:\'"/]|           #   leading punctuation, or
                ^                     #   beginning of line
            )
            (
                (?:https?://)|        # protocol spec, or
                (?:www\.)             # www.*
            )
            (
                [-\w]+                   # subdomain or domain
                (?:\.[-\w]+)*            # remaining subdomains or domain
                (?::\d+)?                # port
                (?:/(?:(?:[\~\w\+%-\@]|(?:[,.;:][^\s$]))+)?)* # path
                (?:\?[\w\+%&=.;-]+)?     # query string
                (?:\#[\w\-]*)?           # trailing anchor
            )
            ([[:punct:]]|\s|<|$)    # trailing text
            ~x', function($matches) {
                    if (preg_match("/<a\s/i", $matches[1])) {
                        return $matches[0];
                    } else {
                        return $matches[1] . '<a href="' . ($matches[2] == 'www.' ? 'http://www.' : $matches[2]) . $matches[3] . '" target="_blank">' . $matches[2] . $matches[3] . '</a>' . $matches[4];
                    }
                }, $text
        );
    }

    public function userText($text) {
        return nl2br($this->autoLink($this->escape($text)));
    }

    public function shorten($text, $length = 140) {
        return mb_substr(str_replace("\n", ' ', $this->escape($text)), 0, $length);
    }

    public function getCurrentUrl() {
        return isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'http://test/';
    }

    public function getSession($key, $default = null) {
        return $this->container->get('session')->get($key, $default);
    }

    public function getParam($key) {
        return $this->container->get('mastop')->param($key);
    }

    public function getCache($key, $default = null) {
        $cache = $this->container->get('mastop')->getCache($key, $default);
        if (is_array($cache)) {
            return '<pre>' . print_r($cache, true) . '</pre>';
        } elseif (is_string($cache)) {
            return (empty($cache)) ? '{vazio}' : $cache;
        } elseif (is_object($cache)) {
            return '{objeto ' . get_class($cache) . '}';
        }
        return $cache;
    }

    public function debug($var) {
        return '<pre>' . print_r($var, true) . '</pre>';
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public function gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
        $url = ($this->container->get('request')->isSecure()) ? 'https://secure.gravatar.com/avatar/' : 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }
    /**
     * Retorna o protocolo atual (http:// ou https://)
     * @return string
     */
    public function getProtocol(){
        return $this->container->get('request')->isSecure() ? 'https://' : 'http://';
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName() {
        return 'mastop';
    }

}

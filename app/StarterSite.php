<?php

declare(strict_types=1);

namespace App;

use Performing\TwigComponents\Configuration;
use Timber\Site;
use Timber\Timber;
use Twig\Environment;

class StarterSite extends Site
{
    public function __construct()
    {
        foreach (include ROOT_DIR . '/bootstrap/providers.php' as $provider) {
            $provider = app($provider);
            method_exists($provider, 'register') && $provider->register();
            method_exists($provider, 'boot') && $provider->boot();
        }

        add_filter('timber/context', $this->add_to_context(...));
        add_filter('timber/twig', $this->add_to_twig(...));
        add_filter('timber/twig/filters', $this->add_filters_to_twig(...));
        add_filter('timber/twig/functions', $this->add_functions_to_twig(...));
        add_filter('timber/twig/environment/options', $this->update_twig_environment_options(...));
        add_filter('template_include', $this->route(...), PHP_INT_MAX);

        parent::__construct();
    }

    /**
     * Add custom wordpress routing
     */
    public function route(string $template): string
    {
        if (str_starts_with($template, WP_PLUGIN_DIR)) {
            app()->instance('starter.plugin_template', $template);
        }

        return ROOT_DIR . '/routes/web.php';
    }

    /**
     * Configure the twig environment.
     */
    protected function add_to_twig(Environment $twig)
    {
        Configuration::make($twig)
            ->setTemplatesPath('/_components')
            ->useCustomTags()
            ->setup();

        return $twig;
    }

    /**
     * This is where you add some context.
     *
     * @param array $context context['this'] Being the Twig's {{ this }}
     */
    public function add_to_context($context)
    {
        $context['foo'] = 'bar';
        $context['stuff'] = 'I am a value set in your functions.php file';
        $context['notes'] = 'These values are available everytime you call Timber::context();';
        $context['menu'] = Timber::get_menu('primary_navigation');
        $context['site'] = $this;

        return $context;
    }

    /**
     * This would return 'foo bar!'.
     *
     * @param string $text being 'foo', then returned 'foo bar!'
     */
    public function myfoo($text)
    {
        $text .= ' bar!';

        return $text;
    }

    /**
     * This is where you can add your own functions to twig.
     *
     * @link https://timber.github.io/docs/v2/hooks/filters/#timber/twig/filters
     * @param array $filters an array of Twig filters.
     */
    public function add_filters_to_twig($filters)
    {
        $additional_filters = [
            'myfoo' => [
                'callable' => [$this, 'myfoo'],
            ],
        ];

        return array_merge($filters, $additional_filters);
    }

    /**
     * This is where you can add your own functions to twig.
     *
     * @link https://timber.github.io/docs/v2/hooks/filters/#timber/twig/functions
     * @param array $functions an array of existing Twig functions.
     */
    public function add_functions_to_twig($functions)
    {
        $additional_functions = [
            'get_theme_mod' => [
                'callable' => 'get_theme_mod',
            ],
            'dd' => [
                'callable' => 'dd',
            ],
        ];

        return array_merge($functions, $additional_functions);
    }

    /**
     * Updates Twig environment options.
     *
     * @see https://twig.symfony.com/doc/2.x/api.html#environment-options
     *
     * @param array $options an array of environment options
     *
     * @return array
     */
    public function update_twig_environment_options($options)
    {
        // $options['autoescape'] = true;

        return $options;
    }
}

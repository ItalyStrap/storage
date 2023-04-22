<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\TestCase\WPTestCase as WPUnit;

class WPTestCase extends WPUnit
{

    /**
     * @var \WPUnitTester
     */
    protected $tester;

    protected string $cache_key;

    public function setUp(): void
    {
        // Before...
        parent::setUp();

        $this->assertTrue(\is_plugin_active('storage/index.php'), 'Plugin must be active, check the name');
        $this->assertTrue((bool)\did_action('plugins_loaded'), 'Assert WordPress Event "plugins_loaded" is fired');
        $this->assertFalse((bool)\did_action('not_valid_event_name'), '');

        $this->cache_key = 'widget_list';

        // Your set up methods here.
    }

    public function tearDown(): void
    {
        // Your tear down methods here.

        \delete_transient($this->cache_key);
        $this->cache_key = '';

        global $_wp_using_ext_object_cache;
        $_wp_using_ext_object_cache = false;

        // Then...
        parent::tearDown();
    }

    /**
     * @test
     */
    public function canCreatePost(): void
    {
        $post = static::factory()->post->create_and_get(['post_excerpt' => 'Lorem Ipsum']);
        $this->assertInstanceOf(\WP_Post::class, $post);
    }

    protected function prepareSetMultipleReturnFalse(): void
    {
        // Preparation for transient
        global $_wp_using_ext_object_cache;
        $_wp_using_ext_object_cache = true;

        // Preparation for Theme Mods
        $styleSheet = \wp_get_theme()->get_stylesheet();
        \add_filter("pre_update_option_theme_mods_{$styleSheet}", function ($value, $old_value, $option) {
            return $old_value;
        }, 10, 3);
    }
}

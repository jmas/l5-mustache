<?php namespace Mayconbordin\L5Mustache;

use Illuminate\Contracts\View\Engine as EngineInterface;
use Illuminate\Filesystem\Filesystem;
use Mustache_Engine;

use Lang;

class MustacheEngine implements EngineInterface {

	public function __construct(Filesystem $files)
	{
		$this->files = $files;
	}
	
	public function get($path, array $data = array())
	{
		$view = $this->files->get($path);
		$app = app();

        $config = $app['config']->get('l5-mustache');

        $config['helpers'] = array_merge($this->loadLaravelHelpers(), isset($config['helpers']) ? $config['helpers'] : []);

		$m = new Mustache_Engine($config);

 		$data = array_map(function($item){
			return (is_object($item) && method_exists($item, 'toArray')) ? $item->toArray() : $item;
		}, $data);
 
		return $m->render($view, $data);
	}

    protected function loadLaravelHelpers()
    {
        return [
            'lang' => function($key) {
                return Lang::get($key);
            },

            'choice' => function($key) {
                $args = explode('|', $key);

                if (sizeof($args) < 2) {
                    $args[1] = 1;
                }

                return Lang::choice($args[0], $args[1]);
            }
        ];
    }
}


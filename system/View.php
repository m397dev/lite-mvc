<?php
/**
 * @project     lite-mvc
 * @author      M397
 * @email       m397.dev@gmail.com
 * @date        10/15/2024
 * @time        12:44 AM
 */

namespace app\system;

/**
 * Class View.
 *
 * This class handles render view page.
 */
class View {

	/**
	 * @var string $title Page title
	 */
	public string $title = '';
	/**
	 * @var array $params Web parameters configuration
	 */
	public mixed $params = [];
	/**
	 * @var array $assets Web assets configuration
	 */
	public mixed $assets = [];

	/**
	 * View Constructor.
	 */
	public function __construct( string $rootDir ) {
		$this->params = require $rootDir . '/config/params.php';
		$this->assets = require $rootDir . '/config/assets.php';
	}

	/**
	 * Register assets.
	 *
	 * @return void
	 */
	public function registerAssets(): void {
		$cssTags = '';
		$jsTags  = '';

		if ( ! empty( $this->assets ) ) {
			foreach ( $this->assets['css'] as $css ) {
				$cssTags .= '<link rel="stylesheet" type="text/css" href="/assets/' . $css . '">';
			}

			foreach ( $this->assets['js'] as $js ) {
				$jsTags .= '<script src="/assets/' . $js . '" defer></script>';
			}

			foreach ( $this->assets['jquery'] as $jquery ) {
				$jsTags .= '<script src="/assets/' . $jquery . '"></script>';
			}
		}

		echo $cssTags;
		echo $jsTags;
	}

	/**
	 * Register optional css.
	 *
	 * @param  string  $style
	 *
	 * @return void
	 */
	public function registerCss( string $style ): void {
		echo '<style>' . $style . '</style>';
	}

	/**
	 * Register optional javascript.
	 *
	 * @param  string  $script
	 *
	 * @return void
	 */
	public function registerJs( string $script ): void {
		echo '<script>' . $script . '</script>';
	}

	/**
	 * Render a view page.
	 *
	 * @param  string  $view
	 * @param  array  $params
	 *
	 * @return array|bool|string
	 */
	public function renderView(
		string $view,
		array $params
	): array|bool|string {
		$layoutName = App::$app->layout;

		if ( App::$app->controller ) {
			$layoutName = App::$app->controller->layout;
		}

		$viewContent = $this->renderContent( $view, $params );
		ob_start();
		require App::$rootDir . "/views/layouts/$layoutName.php";
		$layoutContent = ob_get_clean();

		return str_replace( '{{content}}', $viewContent, $layoutContent );
	}

	/**
	 * Render a view page without layout.
	 *
	 * @param  string  $view
	 * @param  array  $params
	 *
	 * @return false|string
	 */
	public function renderContent( string $view, array $params ): bool|string {
		foreach ( $params as $key => $value ) {
			$$key = $value;
		}
		ob_start();
		require App::$rootDir . "/views/$view.php";

		return ob_get_clean();
	}

}


<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css?v=0.0.2',
        'css/dataTables.bootstrap4.min.css',
        'css/bootstrap-datepicker.min.css?v=0.0.1',
        'css/select2.min.css?v=0.0.4'
    ];
    public $js = [
        'js/jquery-ui.min.js',
        'js/jquery.dataTables.min.js',
        'js/dataTables.bootstrap4.min.js',
        'js/bootstrap-datepicker.min.js?v=0.0.1',
        'js/select2.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}

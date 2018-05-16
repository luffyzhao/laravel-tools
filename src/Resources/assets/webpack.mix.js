let mix = require('laravel-mix');

const webpack = require('webpack')

const path = require('path')

function resolve(dir) {
    return path.join(__dirname, '.', dir)
}
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    output: {
        // 依据该路径进行编译以及异步加载
        publicPath: '',
        // 注意开发期间不加 hash，以免自动刷新失败
        chunkFilename: `js/luffyzhao/chunk[name].${ mix.inProduction() ? '[chunkhash].' : '' }js`,
        path: resolve('../../../public')
    },
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            'vue$': 'vue/dist/vue.common.js',
            '@': resolve('js')
        }
    },
    plugins: [
        // 不打包 moment.js 的语言文件以减小体积
        new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
    ]
})


mix.js('js/app.js', 'public/js/luffyzhao')
    .extract([
        'axios',
        'lodash',
        'vue',
        'vue-router',
        'iview'
    ])
    .autoload({
        vue: ['Vue']
    });

mix.copyDirectory('node_modules/iview/dist/styles/', '../../../public/css/luffyzhao');

mix.copyDirectory('images/', '../../../public/images/luffyzhao');

mix.sass('sass/app.scss', 'public/css/luffyzhao').options({
    processCssUrls: true
});

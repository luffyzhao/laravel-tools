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
        chunkFilename: `js/admin/chunk[name].${ mix.inProduction() ? '[chunkhash].' : '' }js`
    },
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            'vue$': 'vue/dist/vue.common.js',
            '@': resolve('resources/assets/luffyzhao')
        }
    },
    plugins: [
        // 不打包 moment.js 的语言文件以减小体积
        new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
    ]
})


mix.js('resources/assets/luffyzhao/app.js', 'public/js/admin')
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

mix.copyDirectory('node_modules/iview/dist/styles/', 'public/css/admin');

mix.copyDirectory('resources/assets/images/', 'public/images/admin');

mix.sass('resources/assets/sass/app.scss', 'public/css/admin').options({
    processCssUrls: true
});

// mix.sass('resources/assets/sass/github-markdown.scss', 'public/css/admin')

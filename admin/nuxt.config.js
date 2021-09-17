const { join } = require('path')
const { copySync, removeSync } = require('fs-extra')
const TerserPlugin = require('terser-webpack-plugin');
const HappyPack = require('happypack');
const os = require('os');
const happyThreadPool = HappyPack.ThreadPool({ size: os.cpus().length });

export default {
  ssr: false,
  dev: process.env.NODE_ENV == 'dev',
  /*
   * Headers of the page
   */
  rootDir: __dirname,
  head: {
    title: process.env.APP_NAME || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: process.env.DESCRIPTION || '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },
  /*
   * Customize the progress-bar color
   */
  loading: { color: '#4BA4FF' },
  /*
   * Global CSS
   */
  css: [
    { src: '@/assets/css/main.scss', lang: "scss" },
    '@/assets/css/iconfont.css',
    { src: '@/assets/css/page-transition.scss', lang: "scss" },
  ],
  /*
   * Plugins to load before mounting the App
   */
  plugins: [
    '@/plugins/apis',
    '@/plugins/axios',
    '@/plugins/onError',
    // '@/plugins/mqtt',
    '@/plugins/element',
    '@/plugins/common',
    '@/plugins/qiniu',
    // '@/plugins/i18n',
  ],
  /*
   * Nuxt.js modules
   */
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/style-resources',
    ['@nuxtjs/dotenv',
      {
        path: process.env.NODE_ENV == 'development' ? "./" : "../",
        only: process.env.NODE_ENV == 'development' ? "" : ["APP_URL", "NODE_ENV", "APP_LOCALE", "APP_NAME", "TINY_KEY", "DESCRIPTION", "MQTT_SERVER", "MQTT_CLIENTID", "MQTT_USERNAME", "MQTT_PASSWORD"]
      }
    ]
  ],
  // 编译配置 使用自定义路由
  buildModules: [
    ['@nuxtjs/router', { path: "./routers", fileName: "index.js" }]
  ],
  // scss
  styleResources: {
    scss: '@/assets/css/main.scss'
  },
  // 路由配置
  router: {
    middleware: 'auth'
  },
  /*
   * Build configuration
   */
  build: {
    // analyze: false,
    extractCSS: process.env.NODE_ENV == 'production',
    transpile: [/^element-ui/],
    babel: {
      // 单独引用
      "plugins": [
        [
          "component",
          {
            "libraryName": "element-ui",
            "styleLibraryName": "theme-chalk"
          }
        ]
      ]
    },
    optimization: { // 配置代码压缩规则
      minimizer: [
        // webpack4 使用的压缩插件，用来替代webpack3的 UglifyJsPlugin
        new TerserPlugin({
          terserOptions: {
            warnings: false,
            compress: {
              drop_console: true, // 可选：false,生产移除 console.log
              pure_funcs: ['console.log']
            },
            output: {
              // 是否保留代码注释
              comments: false
            },
            cache: true,
            parallel: false,
            sourceMap: process.env.NODE_ENV !== 'production'
          }
        })
      ],
      splitChunks: { // 代码打包分割规则
        chunks: 'all',
        cacheGroups: {
          libs: {
            name: 'chunk-libs',
            test: /[\\/]node_modules[\\/]/,
            priority: 10,
            chunks: 'initial' // only package third parties that are initially dependent
          },
          elementUI: {
            name: 'chunk-ui',
            priority: 20,
            test: /[\\/]node_modules[\\/]_?element-ui(.*)/
          }
        }
      },
      usedExports: true,
      sideEffects: true,
    },
    extend(config) {
      config.module.rules.push({
        test: /\.scss$/,
        oneOf: [
          {
            resourceQuery: /module/,
            use: ['style-loader', 'css-loader', 'sass-loader']
          }
        ]
      });
      if (process.env.NODE_ENV == 'production') {
        config.module.rules.push(
          {
            test: /\.js$/,
            // 把对.js 的文件处理交给id为happyBabel 的HappyPack 的实例执行
            loader: 'happypack/loader?id=happyBabel',
            // 排除node_modules 目录下的文件
            exclude: /node_modules/
          }
        );
        config.plugins.push(
          new HappyPack({
            // 用id来标识 happypack处理那里类文件
            id: 'happyBabel',
            // 如何处理  用法和loader 的配置一样
            loaders: [{
              loader: 'babel-loader?cacheDirectory=true',
            }],
            // 共享进程池
            threadPool: happyThreadPool,
            // 允许 HappyPack 输出日志
            verbose: false,
          })
        );
      }
    }
  },

  hooks: {
    generate: {
      done(generator) {
        // 编译完成复制文件 Copy dist files to resources/views/admin/_nuxt
        if (generator.nuxt.options.dev === false && generator.nuxt.options.mode === "spa") {
          const publicDir = join(generator.nuxt.options.rootDir, "..", "resources", "views", "admin")
          removeSync(publicDir)
          copySync(generator.nuxt.options.generate.dir, publicDir)
          copySync(join(generator.nuxt.options.generate.dir, '200.html'), join(publicDir, 'index.html'))
          removeSync(join(publicDir, '200.html'))
          removeSync(generator.nuxt.options.generate.dir)
        }
      }
    }
  }
}

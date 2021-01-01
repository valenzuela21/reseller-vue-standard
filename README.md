# Estandar Tendero Master Woocommerce

A WordPress master tienda woocommerce reseller with Vue.js 😍

## webpack.dev.js

- Modify the proxy field by the development url
<pre>
    plugins: [
        new BrowserSyncPlugin({
            proxy: 'http://localhost:8888/tiendaubicacion/', //Modify url input
            files: [
                './**/*.php',
                './src-js/*'
            ],
            port: 3000
        })
    ]
</pre>


## Post Installation
1. Run `npm install`
1. To start developing, run `npm run dev`
1. For production build, run `npm run build`


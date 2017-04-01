let _ = require('lodash');

/*

 $userData = $this->renderView(':partials:user_data.html.twig', [
     'user' => [
        'api_token' => $this->getUser()->getApiToken(),
     ],
 ]);
 $content = $response->getContent();
 $content = str_replace(
     [
         '/static/js/fos_js_routes.js',
         '<head>',
     ],
     [
         '/static/js/fos_js_routes_'.$routeParams['subdomain'].'.js',
         '<head>'.$userData,
     ],
     $content
 );
 $response->setContent($content);
 */
class DevLoader
{
    constructor(options) {
        this.options = _.extend({
            user: null,
            subdomain: null,
        }, options);
    }
    apply(compiler) {
        let {user, subdomain} = this.options;
        compiler.plugin('compilation', (compilation) => {
            compilation.plugin('html-webpack-plugin-before-html-processing', (htmlPluginData, callback) => {
                if (_.isObject(user)) {
                    htmlPluginData.html = htmlPluginData.html.replace(
                        /\<head\>/ig,
                        '<head><script type="text/javascript">var user = ' + JSON.stringify(user) + ';</script>'
                    );
                }
                if (_.isString(subdomain)) {
                    htmlPluginData.html = htmlPluginData.html.replace(
                        /\/static\/js\/fos_js_routes\.js/ig,
                        '/static/js/fos_js_routes_' + subdomain + '.js'
                    );
                }
                callback(null, htmlPluginData);
            });
        });
    }
}

module.exports = DevLoader;

<?php

require 'recipe/symfony3.php';

// Set configurations
set('repository', 'git@lab.trisoft.ro:campr/campr.git');
set('shared_files', ['app/config/parameters.yml']);
set('shared_dirs', ['var/logs', 'vendor', 'web/uploads', 'front/node_modules']);
set('writable_dirs', ['var/cache', 'var/logs']);
set('http_user', 'www-data');

// Set options
option('provision', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Run provision scripts on the server');
option('reset-db', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Recreates empty DB on the server');

// Set environment
env('deploy_path', '/var/www/{{domain}}');
env('env_vars', 'SYMFONY_ENV={{env}}');
env('release_path', function () {
    return str_replace("\n", '', run("if [ -L {{deploy_path}}/release ]; then readlink {{deploy_path}}/release; else readlink {{deploy_path}}/current; fi"));
});
env('symfony_console', function () {
    return sprintf('%s %s/%s%s', env('bin/php'), env('release_path'), trim(get('bin_dir'), '/'), '/console');
});
env('symfony_console_options', function () {
   return sprintf(
       '--env=%s%s %s',
       env('env'),
       env('env') === 'prod' ? ' --no-debug' : '',
       '--no-interaction'
   );
});
env('bin/php', function () {
    $php = run('which php')->toString();

    return sprintf('%s -dmemory_limit=-1', $php);
});
env('bin/composer', function () {
    run("cd {{release_path}} && wget -q https://getcomposer.org/composer.phar && chmod +x composer.phar");
    return '{{bin/php}} {{release_path}}/composer.phar';
});
env('cron_domain', function () {
    return strtr(env('domain'), ['.' => '_']);
});
env('bin/mysql', function () {
    return '`which mysql` -u{{mysql_user}} -p{{mysql_password}} {{mysql_database}}';
});

// Configure servers
server('prod1', '138.201.187.161')
    ->user('root')
    ->stage('prod')
    ->pemFile(__DIR__.'/app/config/deploy/prod.key')
    ->env('env', 'prod')
    ->env('branch', 'production')
    ->env('domain', 'campr.biz')
    ->env('mysql_user', 'root')
    ->env('mysql_password', 'campr')
    ->env('mysql_database', 'campr')
;
server('qa1', '138.201.187.161')
    ->user('root')
    ->stage('qa')
    ->pemFile(__DIR__.'/app/config/deploy/qa.key')
    ->env('env', 'qa')
    ->env('branch', 'master')
    ->env('domain', 'qa.campr.biz')
    ->env('mysql_user', 'root')
    ->env('mysql_password', 'campr')
    ->env('mysql_database', 'campr')
;

// Set tasks
task('deploy:assetic:dump', function () {
    run('{{symfony_console}} assetic:dump {{symfony_console_options}}');
})->desc('Dump assets');
task('project:supervisor:restart', function () {
    run('sudo /etc/init.d/supervisor stop');
    run('sudo systemctl daemon-reload');
    run('sudo /etc/init.d/supervisor start');
});
task('project:apache:restart', function () {
    run('sudo service apache2 restart');
});
task('project:dizda:backup', function () {
   run('{{symfony_console}} dizda:backup:start {{symfony_console_options}}');
});
task('project:fos:js-routing:dump', function () {
    run('{{symfony_console}} fos:js-routing:dump {{symfony_console_options}}');
});
task('project:ln-console-env', function () {
    run('cd {{release_path}} && rm -rf app/env.php && ln -s env/env_{{env}}.php app/env.php');
});
task('project:copy-parameters', function () {
    run('cp {{release_path}}/app/config/parameters_{{env}}.yml.dist {{release_path}}/app/config/parameters.yml');
});
task('project:enable-cron', function () {
    run('sudo rm -rf /etc/cron.d/{{cron_domain}} && sudo cp {{release_path}}/app/config/cron/{{env}} /etc/cron.d/{{cron_domain}} && sudo service cron restart');
});
task('project:apache:enable-config', function () {
    run('if [ -L /etc/apache2/sites-enabled/{{domain}}.conf ]; then sudo rm -rf /etc/apache2/sites-enabled/{{domain}}.conf; fi && sudo ln -s {{release_path}}/app/config/apache/{{env}}.conf /etc/apache2/sites-enabled/{{domain}}.conf');
});
task('database:cleanup', function () {
    if (input()->getOption('reset-db')) {
        run('{{symfony_console}} doctrine:database:drop --force {{symfony_console_options}}');
        run('{{symfony_console}} doctrine:database:create {{symfony_console_options}}');
    } else {
        writeln('<info>Skipping...</info>');
    }
});
task('database:migrate', function () {
    run('{{symfony_console}} doctrine:migrations:migrate --allow-no-migration {{symfony_console_options}}');
});
task('server:provision', function () {
    if (input()->getFirstArgument() == 'server:provision' || input()->getOption('provision')) {
        upload('bin/install/deps-provision', '/tmp/deps-provision');
        run('chmod +x /tmp/deps-provision && /tmp/deps-provision {{env}} {{deploy_path}}/current && rm -f /tmp/deps-provision');
    } else {
        writeln('<info>Skipping...</info>');
    }
});
task('project:build:front', function () {
    run('cd {{release_path}}/front && npm install && npm run build');
});
task('hivebot:deploy-whois', function () {
    env('localUser', sprintf(
        '%s',
        runLocally('git config --get user.name')
    ));
});
task('hivebot:deploy-start', function () {
    run('curl --header "X-Deploy-Token: 5I-DQdWaizEjI-yaP-4a_zunaATeYKC_k3gF_-zd2bM" -X POST -d "{\"status\":\"started\", \"env\":\"{{env}}\", \"branch\":\"{{branch}}\", \"domain\":\"{{domain}}\", \"by\":\"{{localUser}}\"}" https://hive.trisoft.ro/api/deploy');
});
task('hivebot:deploy-failed', function () {
    run('curl --header "X-Deploy-Token: 5I-DQdWaizEjI-yaP-4a_zunaATeYKC_k3gF_-zd2bM" -X POST -d "{\"status\":\"failed\", \"env\":\"{{env}}\", \"branch\":\"{{branch}}\", \"domain\":\"{{domain}}\", \"by\":\"{{localUser}}\"}" https://hive.trisoft.ro/api/deploy');
});
task('hivebot:deploy-success', function () {
    run('curl --header "X-Deploy-Token: 5I-DQdWaizEjI-yaP-4a_zunaATeYKC_k3gF_-zd2bM" -X POST -d "{\"status\":\"success\", \"env\":\"{{env}}\", \"branch\":\"{{branch}}\", \"domain\":\"{{domain}}\", \"by\":\"{{localUser}}\", \"in\":\"{{deployTime}}\"}" https://hive.trisoft.ro/api/deploy');
});
task('deploy:start-time', function () {
    set('startTime', time());
});
task('deploy:end-time', function () {
    env('deployTime', time()-get('startTime'));
});

// Set flows
//before('deploy:symlink', 'project:dizda:backup');

before('deploy', 'deploy:start-time');
before('hivebot:deploy-success', 'deploy:end-time');
before('hivebot:deploy-start', 'hivebot:deploy-whois');
before('deploy:prepare', 'hivebot:deploy-start');
after('success', 'hivebot:deploy-success');
after('deploy:prepare', 'server:provision');
after('deploy:vendors', 'project:copy-parameters');
before('deploy:vendors', 'project:ln-console-env');
before('database:migrate', 'database:cleanup');
after('deploy:symlink', 'database:migrate');
after('deploy:symlink', 'project:apache:restart');
after('deploy:symlink', 'project:enable-cron');
after('deploy:symlink', 'project:build:front');
before('project:apache:restart', 'project:apache:enable-config');
after('project:apache:restart', 'project:supervisor:restart');
after('deploy:assetic:dump', 'project:fos:js-routing:dump');

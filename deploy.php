<?php

namespace Deployer;

require 'recipe/symfony3.php';

// Set configurations
set('repository', 'git@lab.trisoft.ro:campr/campr.git');
set('shared_files', []);
set('shared_dirs', [
    'var/logs',
    'backend/vendor',
    'web/uploads',
    'frontend/node_modules',
    'ssr/node_modules',
    'var/profiler',
]);
set('writable_dirs', ['var/cache', '../../shared/var/logs', '../../shared/web/uploads', '../../shared/var/profiler']);
set('http_user', 'www-data');

// Set options
option('provision', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Run provision scripts on the server');
option('reset-db', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Recreates empty DB on the server');
option('key', 'k', \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL, 'Override private key');

// Set environment
set('use_relative_symlink', false);
set('deploy_path', '/var/www/{{domain}}');
set('env_vars', 'SYMFONY_ENV={{env}}');
set('release_path', function () {
    return str_replace("\n", '', run('if [ -L {{deploy_path}}/release ]; then readlink {{deploy_path}}/release; else readlink {{deploy_path}}/current; fi'));
});
set('symfony_console', function () {
    return sprintf('%s %s/%s%s', get('bin/php'), get('release_path'), trim(get('bin_dir'), '/'), '/console');
});
set('symfony_console_options', function () {
    return sprintf(
       '--env=%s%s %s',
       get('env'),
       get('env') === 'prod' ? ' --no-debug' : '',
       '--no-interaction'
   );
});
set('bin/php', function () {
    $php = run('which php')->toString();

    return sprintf('%s -dmemory_limit=-1', $php);
});
set('bin/composer', function () {
    if (commandExist('composer')) {
        $composer = run('which composer')->toString();
        run('sudo composer self-update');
    }
    if (empty($composer)) {
        run('cd {{release_path}} && curl -sS https://getcomposer.org/installer | {{bin/php}}');
        $composer = '{{bin/php}} {{release_path}}/composer.phar';
    }

    return $composer;
});
set('cron_domain', function () {
    return strtr(get('domain'), ['.' => '_']);
});
set('bin/mysql', function () {
    return '`which mysql` -u{{mysql_user}}{{mysql_password_usage}} {{mysql_database}}';
});
set('mysql_password_usage', function () {
    return empty(get('mysql_password')) ? '' : ' -p{{mysql_password}}';
});
set('bin/mysqldump', function () {
    return '`which mysqldump` -u{{mysql_user}}{{mysql_password_usage}} --routines --databases `mysql -u{{mysql_user}}{{mysql_password_usage}} -Bse "show databases like \'{{mysql_database}}%\'"`';
});
set('bin/mc', function () {
    return '{{release_path}}/bin/mc --config-folder={{release_path}}/config/minio/';
});
set('bin/rush', function () {
    return '{{release_path}}/bin/rush';
});
set('database_backup_path', function () {
    return sprintf('campr/{{domain}}/%s/%s/%s/release_{{release_name}}/{{domain}}_%s_release_{{release_name}}.sql', date('Y'), date('m'), date('d'), date('YmdHis'));
});
set('database_rollback_path', function () {
    return sprintf('/tmp/{{domain}}_%s_release_{{release_name}}.sql', date('YmdHis'));
});
set('assets_backup_path', function () {
    return 'campr/{{domain}}/assets';
});

// Configure servers
server('prod', '195.201.102.226')
    ->user('root')
    ->stage('prod')
    ->pemFile(null)
    ->set('pemFile', '~/.ssh/id_rsa')
    ->set('env', 'prod')
    ->set('branch', 'master')
    ->set('domain', 'campr.biz')
    ->set('mysql_user', 'root')
    ->set('mysql_password', 'campr')
    ->set('mysql_database', 'campr')
;
server('preprod', '195.201.29.2')
    ->user('root')
    ->stage('preprod')
    ->pemFile(null)
    ->set('pemFile', __DIR__.'/config/deploy/preprod.key')
    ->set('env', 'preprod')
    ->set('branch', 'master')
    ->set('domain', 'prod.campr.biz')
    ->set('mysql_user', 'root')
    ->set('mysql_password', 'campr')
    ->set('mysql_database', 'campr')
;
server('qa', '195.201.29.2')
    ->user('root')
    ->stage('qa')
    ->pemFile(null)
    ->set('pemFile', __DIR__.'/config/deploy/qa.key')
    ->set('env', 'qa')
    ->set('branch', 'develop')
    ->set('domain', 'qa.campr.biz')
    ->set('mysql_user', 'root')
    ->set('mysql_password', 'campr')
    ->set('mysql_database', 'campr')
;
localServer('qa-local')
    ->user('root')
    ->stage('qa-local')
    ->set('env', 'qa')
    ->set('branch', 'develop')
    ->set('domain', 'qa.campr.biz')
    ->set('mysql_user', 'root')
    ->set('mysql_password', 'campr')
    ->set('mysql_database', 'campr')
;
localServer('prod-local')
    ->user('root')
    ->stage('prod-local')
    ->set('env', 'prod')
    ->set('branch', 'master')
    ->set('domain', 'campr.biz')
    ->set('mysql_user', 'root')
    ->set('mysql_password', 'campr')
    ->set('mysql_database', 'campr')
;


// Set tasks
task('pemFile', function () {
    \Deployer\Task\Context::get()
        ->getServer()
        ->getConfiguration()
        ->setPemFile(
            !empty(\Deployer\Task\Context::get()->getInput()->getOption('key'))
                ? \Deployer\Task\Context::get()->getInput()->getOption('key')
                : (has('pemFile') ? get('pemFile') : null)
        )
    ;

    $bash = 'if [ "`pgrep gnome-keyring-daemon`" ];
    then
        echo "true";
    elif [ "`echo $SSH_AGENT_PID`" ]; 
    then 
        if [ "`ps -p $SSH_AGENT_PID | grep $SSH_AGENT_PID`" ]; 
        then 
            echo "true"; 
        fi; 
    fi';

    if (runLocally($bash)->toBool()) {
        writeln('<info>SSH Agent detected, using it.</info>');
        \Deployer\Task\Context::get()
            ->getServer()
            ->getConfiguration()
            ->setAuthenticationMethod(\Deployer\Server\Configuration::AUTH_BY_AGENT)
        ;
    }
})->setPrivate()->onlyOn(['qa', 'prod']);
task('ifconfig', function () {
    run('cd {{release_path}}; ifconfig');
});
task('open:ports', function () {
    run('netstat -ntlp | grep LISTEN');
});
task('rtop', function () {
    runLocally(sprintf('rtop -i {{pemFile}} %s@%s 1', \Deployer\Task\Context::get()->getServer()->getConfiguration()->getUser(), \Deployer\Task\Context::get()->getServer()->getConfiguration()->getHost()));
})->desc('Server metrics retrieved each 1 sec')->onlyOn(['qa', 'prod']);
task('project:backup', function () {
    run('{{bin/mc}} mb campr/{{domain}}');
    run('{{bin/mysqldump}} | {{bin/mc}} pipe {{database_backup_path}}');
    run('echo "created" | {{bin/mc}} pipe {{assets_backup_path}}/.create');
    run('if [ -d /var/www/{{domain}}/shared/web/uploads ]; then {{bin/mc}} mirror --quiet /var/www/{{domain}}/shared/web/uploads {{assets_backup_path}}; fi');
    run('{{bin/mc}} rm --force {{assets_backup_path}}/.create');
})->onlyOn(['qa', 'prod', 'prod-local', 'qa-local']);
task('database:backup', function () {
    run('{{bin/mc}} mb campr/{{domain}}');
    run('{{bin/mysqldump}} | {{bin/mc}} pipe {{database_backup_path}}');
})->onlyOn(['qa', 'prod', 'prod-local', 'qa-local']);
task('database:rollback', function () {
    run('{{bin/mc}} cp {{database_backup_path}} {{database_rollback_path}}; {{bin/mysql}} < {{database_rollback_path}}');
})->onlyOn(['qa', 'prod']);
task('deploy:assetic:dump', function () {
    run('{{symfony_console}} assetic:dump {{symfony_console_options}}');
})->desc('Dump assets');
task('project:supervisor:restart', function () {
    run('sudo service supervisor stop');
    run('sudo systemctl daemon-reload');
    // Sometimes supervisor can have a mind of it's own for some reason, and therefore have to murder it!
    run('ps aux | grep supervisord | awk \'{{ print $2 }}\' | xargs -l1 sudo kill -9');
    sleep(2);
    run('sudo service supervisor start');
});
task('project:apache:restart', function () {
    run('sudo service apache2 restart');
    run('if service --status-all | grep "php7.1-fpm"; then service php7.1-fpm restart; fi');
});
task('project:front-static', function () {
    run('cd {{release_path}} && bin/front-static');
});
//task('project:front:dump-routes', function () {
//    run('cd {{release_path}} && bin/console app:dump-routes');
//});
task('project:ln-console-env', function () {
    run('cd {{release_path}}/backend && rm -rf app/env.php && ln -s env/env_{{env}}.php app/env.php');
});
task('project:copy-parameters', function () {
    run('cp {{release_path}}/backend/app/config/parameters_{{env}}.yml.dist {{release_path}}/backend/app/config/parameters.yml');
});
task('project:enable-cron', function () {
    run('sudo rm -rf /etc/cron.d/{{cron_domain}} && sudo cp {{release_path}}/config/cron/{{env}} /etc/cron.d/{{cron_domain}} && sudo service cron restart');
});
task('project:apache:enable-config', function () {
    run('if [ -L /etc/apache2/sites-enabled/{{domain}}.conf ]; then sudo rm -rf /etc/apache2/sites-enabled/{{domain}}.conf; fi && sudo ln -s {{release_path}}/config/apache/{{env}}.conf /etc/apache2/sites-enabled/{{domain}}.conf');
});
task('project:supervisor:enable-config', function () {
    run('if [ -L /etc/supervisor/conf.d/{{domain}}.conf ]; then sudo rm -rf /etc/supervisor/conf.d/{{domain}}.conf; fi && sudo ln -s {{release_path}}/config/supervisor/{{env}}.conf /etc/supervisor/conf.d/{{domain}}.conf');
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
    run('{{symfony_console}} app:migrate:all-databases {{symfony_console_options}}');
});
task('server:provision', function () {
    if (input()->getFirstArgument() == 'server:provision' || input()->getOption('provision')) {
        upload('bin/install/deps-provision', '/tmp/deps-provision');
        run('chmod +x /tmp/deps-provision && /tmp/deps-provision {{env}} {{deploy_path}}/current && rm -f /tmp/deps-provision');
    } else {
        writeln('<info>Skipping...</info>');
    }
});
task('project:build:frontend_and_ssr', function () {
    run("echo '>cd {{release_path}}/frontend && yarn install --check-files && yarn run build>cd {{release_path}}/ssr && yarn install --check-files && yarn run build' | {{bin/rush}} -D '>' {} -e");
});
task('hivebot:deploy-whois', function () {
    set('localUser', sprintf(
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
    run('curl --header "X-Deploy-Token: 5I-DQdWaizEjI-yaP-4a_zunaATeYKC_k3gF_-zd2bM" -X POST -d "{\"status\":\"success\", \"env\":\"{{env}}\", \"branch\":\"{{branch}}\", \"domain\":\"{{domain}}\", \"by\":\"{{localUser}}\", \"in\":\"{{runTime}}\"}" https://hive.trisoft.ro/api/deploy');
});
task('run:start-time', function () {
    set('startTime', time());
})->setPrivate();
task('run:end-time', function () {
    set('runTime', time() - get('startTime'));
})->setPrivate();
task('onStart', [
    'run:start-time',
    'pemFile',
])->setPrivate();
task('onEnd', function () {
    if (!has('runTime')) {
        set('runTime', time() - get('startTime'));
    }
    writeln(sprintf('<info>Finished in %s sec!</info>', get('runTime')));
})->setPrivate();

// Set flows
task('deploy', [
    'hivebot:deploy-whois',
    'hivebot:deploy-start',
    'deploy:prepare',
    'server:provision',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'project:backup',
    'project:copy-parameters',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:assets',
    'project:ln-console-env',
    'deploy:vendors',
    'deploy:writable',
    'deploy:symlink',
    'project:front-static',
    'project:build:frontend_and_ssr',
    'deploy:cache:warmup',
    'database:cleanup',
    'database:migrate',
    'project:apache:enable-config',
    'project:apache:restart',
    'project:supervisor:enable-config',
    'project:supervisor:restart',
    'project:enable-cron',
    'cleanup',
    'deploy:unlock',
    'run:end-time',
    'hivebot:deploy-success',
]);

task('deploy:failed', [
    'deploy:unlock',
    'hivebot:deploy-failed',
])->setPrivate();

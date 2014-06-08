set :application, "groenlinkszeist"
set :domain,      "85.17.249.199"
set :deploy_to,   "/var/www/groenlinkszeist.nl"
set :app_path,    "app"
set :web_path,    "web"

set :repository,  "https://github.com/sdijkx/campagne-site.git"
set :scm,         :git

set :model_manager, "doctrine"
set :copy_vendors, true


role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

set :shared_files, ["app/config/parameters.yml"] # This stops us from having to recreate the parameters file on every deploy.
set :shared_children, [app_path + "/logs", app_path + "/uploads", app_path + "/site", web_path + "/uploads", "vendor"]
set :permission_method, :acl
set :use_composer, true

set :interactive_mode, false
 
# The following line tells Capifony to deploy the last Git tag.
# Since Jenkins creates and pushes a tag following a successful build this should always be the last tested version of the code.
set :branch, `git tag`.split("\n").last

set :user, "deploy"
set :use_sudo, false
ssh_options[:port] = "9753"

 
before 'symfony:composer:update', 'symfony:copy_vendors'

namespace :symfony do
  desc "Copy vendors from previous release"
  task :copy_vendors, :except => { :no_release => true } do
    if Capistrano::CLI.ui.agree("Do you want to copy last release vendor dir then do composer install ?: (y/N)")
      capifony_pretty_print "--> Copying vendors from previous release"

      run "cp -a #{previous_release}/vendor #{latest_release}/"
      capifony_puts_ok
    end
  end
end
  
# Run migrations before warming the cache
#before "symfony:cache:warmup", "symfony:doctrine:migrations:migrate"

#after "symfony:composer:install" do 
#    run "git apply --whitespace=nowarn --directory=#{release_path}/vendor/doctrine/orm #{release_path}/doctrine_object_hydrator.patch"
#end

after "symfony:composer:install", "symfony:doctrine:schema:update"

after "symfony:doctrine:schema:update" do
        require 'yaml'
        file = capture "cat #{shared_path}/app/config/parameters.yml"
        db_config = YAML.load(file)
        run "mysql -u #{db_config['parameters']['database_user']} --password=#{db_config['parameters']['database_password']} #{db_config['parameters']['database_name']} < #{release_path}/app/sql/create_fulltext_table.sql" do |channel, stream, data|
            if data =~ /^Enter password:/
                channel.send_data "#{db_config['parameters']['database_password']}\n"
            end
            puts data
        end
end

after "symfony:doctrine:schema:update", "symfony:assetic:dump"
after "symfony:doctrine:schema:update", "symfony:assets:install"

 
set :mysql_admin_user, "glzeist"
set :mysql_db, "glzeist"

# Custom(ised) tasks
namespace :deploy do
# Apache needs to be restarted to make sure that the APC cache is cleared.
# This overwrites the :restart task in the parent config which is empty.

    desc "Restart Apache"
        task :restart, :except => { :no_release => true }, :roles => :app do
        run "sudo apachectl restart"
        puts "--> Apache successfully restarted".green
    end


end


# Uncomment this if you need more verbose output from Capifony
logger.level = Logger::MAX_LEVEL

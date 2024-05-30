<?php

namespace MeeeetDev\LaravelDocker;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LaravelDocker extends Command
{
    protected $signature = 'docker:install 
                         {image : The image name}
                         {network : The bridge network to use}
                         {--f|force : Whether to overwrite published files}
                        ';
    protected $description = "Run an install to publish all the resources needed to run docker";

    public function handle()
    {
        $this->info("We are here");
        $force = $this->hasOption('force');
        $php = $this->choice("Choose your version of PHP", ["php7.4", "php8.0", "php8.1", "php8.2", "php8.3", "octane"], "php8.2");
        if (!$php) {
            $this->error("You must specify a php version");
            die();
        }
        $image = $this->argument('image');
        $network = $this->argument('network') ?? $image . "-network";
        $this->prepareEnv();
        $this->publishConfig($php, $force);
        if ($network !== 'bridge' && $network !== 'docker' && $this->confirm("would you like to create the $network bridge network?")) {
            $this->cmd("docker network create $network");
        }
        if ($this->confirm("would you like to create the {$image}-db docker volume for the database?")) {
            $this->cmd("docker volume create {$image}-db");
        }
        if ($this->confirm("My Work here is done. Do you want to uninstall me now?")) {
            $this->cleanUp();
        }
    }
    private function cmd($cmd)
    {
        $process = Process::fromShellCommandline($cmd, null, array_merge($_SERVER, $_ENV), null, null);
        $process->run(function ($type, $line) {
            $this->line($line);
        });
    }
    protected function replaceInFile($filename, $search, $replace)
    {
        $content = file_get_contents($filename);
        $newContent = str_replace($search, $replace, $content);
        file_put_contents($filename, $newContent);
    }
    protected function prepareEnv()
    {
        $image = $this->argument('image');
        $network = $this->argument('network');
        $env = __DIR__ . "/../env/.env.docker";
        $dest = base_path(".env.docker");
        copy($env, $dest);
        $this->replaceInFile($dest, ":image:", $image);
        $this->replaceInFile($dest, ":network:", $network);

        // Append the .env.docker to .env with a new line
        $this->info('Appending .env.docker to .env...');

        // Get the contents of .env.docker
        $content = file_get_contents($dest);

        // Append the contents of .env.docker to .env
        $fp = fopen(base_path('.env'), 'a');
        fwrite($fp, "\n");
        fwrite($fp, $content);
        fclose($fp);

        // Remove .env.docker file after appending
        unlink($dest);
    }
    protected function publishConfig(string $phpVersion, bool $force = false)
    {
        $this->info('Publishing docker Config...');
        $this->call('vendor:publish', [
            '--provider' => 'MeeeetDev\LaravelDocker\LaravelDockerServiceProvider',
            '--force' => $force
        ]);

        //Configure the right version
        $this->info("Configuring $phpVersion docker Config...");
        copy(base_path("docker/$phpVersion/docker-compose.yml"), base_path("./docker-compose.yml"));
        $this->copyDirectory(base_path("docker/$phpVersion/.docker"), base_path("./.docker"));

        // Remove Directory if it exists
        $dirname = 'docker';
        if (is_dir($dirname)) {
            $this->info("Removing $dirname directory...");
            $this->delTree($dirname);
        }
    }
    private function cleanUp(): void
    {
        $this->info('Cleaning up and removing LaravelDocker...');
        $this->cmd('composer remove meeeet-dev/laravel-docker --ignore-platform-reqs');
    }
    private function copyDirectory($source, $destination)
    {
        $this->info("Copying $source to $destination...");

        if (File::isDirectory($source)) {
            // Create destination directory if it doesn't exist
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            // Recursively copy the directory
            File::copyDirectory($source, $destination);

            $this->info("Directory copied successfully.");
            return true;
        } else {
            $this->info("Source directory does not exist.");
            return false;
        }
    }

    private function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}

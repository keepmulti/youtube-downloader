
## PROJECT

Project is a tool that allows users to download videos on youtube

### Download video full-option

- [Keepmulti Youtube Downloader](https://keepmulti.com/download-youtube-video-and-mp3)

## USAGE

### TESTS

Basic test code, to run:
```$bash
vendor/bin/phpunit
```

### SETUP

With docker

Step 1:
- Download project and run:
```bash
cp .env.example .env
php artisan key:generate
```
Step 2:
- Run
```bash
docker run --rm -v $(pwd):/app composer install
```

Step 3:
- set permisions on the project
```bash
sudo chown -R $USER:$USER *
```
Step 4:
- run bash
```bash
docker-compose up -d
```

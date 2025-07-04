# PHP app from scratch - FrankenPHP demo

This app features configuration, a DI container and a simple router.

This projects was built to have a simple demo php app that can be run with FrankenPHP, to test it out.

## Trying it out

If you would like to try it, clone the repo run the following command (Docker required):

```bash
docker-compose up -d
```

Set the `SERVER_NAME` environment variable in your `.env` file to the domain you want to use, or leave it as `localhost` for local testing.

You can also control the `SERVER_PORT` variable in your `.env` file to change the port Caddy will listen to HTTPS requests on. Note, that you must always connect via HTTPS, even in local development, since FrankenPHP requires it.

## Development

When developing, set the `FRANKENPHP_CONFIG` variable to an empty string in your .env file to use classic mode.

## FrankenPHP worker mode

You can also use FrankenPHP worker mode, by setting the `FRANKENPHP_CONFIG` environment variable to `worker public/index.php` in your .env file.

Note, when using worker mode, you need to restart the container to apply code changes, since the application is kept in memory.

## FrankenPHP static build

Build the static binary using the provided Dockerfile:

```bash
docker build -t frankenphp-demo-static-build -f static-build.Dockerfile .
```

Extract the binary from the image:

```bash
docker cp $(docker create --name frankenphp-demo-static-app-tmp frankenphp-demo-static-app):/go/src/app/dist/frankenphp-linux-x86_64 frankenphp-demo-static-app ; docker rm frankenphp-demo-static-app-tmp
```

And run it:

```bash
./static-app
```

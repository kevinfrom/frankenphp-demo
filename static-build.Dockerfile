FROM dunglas/frankenphp:static-builder

COPY . /go/src/app/dist/app

WORKDIR /go/src/app

RUN EMBED=dist/app/ ./build-static.sh

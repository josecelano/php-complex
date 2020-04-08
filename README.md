# PHP Complex

I'm working on a PHP Fractal kata:

[PHP Mandelbrot Kata](https://github.com/HyveInnovate/mandelbrot-kata/tree/php-solution1)

In order to get be able to zoom in the final Mandelbrot Set image as much as I want I need arbitrary precision for some complex number operations. In this package I'have implemented only the operations needed.

This is the arbitrary precision package being used:

[php-decimal.io](https://php-decimal.io/)

### Prerequisites

PHP
```
PHP 7.4
```

### Installation

```
docker build -t php-complex .
docker run -it --rm \
	-v "$PWD":/usr/src/app \
	-w /usr/src/app \
	-u $(id -u ${USER}):$(id -g ${USER}) \
	php-complex \
    composer install
```

## Running the tests

```
docker run -it --rm \
	-v "$PWD":/usr/src/app \
	-w /usr/src/app \
	-u $(id -u ${USER}):$(id -g ${USER}) \
	php-complex
```

## Acknowledgments

* https://github.com/MarkBaker/PHPComplex

## TODO

* pow operation
* allow to define precision for each complex instance

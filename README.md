# PHP - Refactoring - Pser

## Getting Started

### Before start
I've tried not to use any third-party libraries or frameworks at all, except PSR contracts.

The code has been refactored to incorporate OOP principles, making it more organized and maintainable. Some code snippets have been wrapped in functions for better structure and development speed improvement.

Acceptance tests have been included to ensure that the core functionality of the application works as expected. These tests provide end-to-end verification of the application's behavior.

Additionally, API client tests have been implemented to demonstrate how to use mocks for testing interactions with external services.

Please note that more tests could be added for comprehensive coverage, but the focus here is on demonstrating the testing approach within a limited timeframe.

### Requirements
Check provided **gist** file to see full task requirements

### Installation

#### Clone repository to the common place:

```bash
git clone git@github.com:ValentinNikolaev/test_pser_refactoring.git ~/workspace/test_pser_refactoring
```

### Build application image
Basic php:7.4-fpm-alpine & composer:latest are used to assemble the service image.

build image as follows:

```bash
make build
```

### Start application

Then start project using command as follows:
```bash
make up
```

### Usage
### Commands
```apacheconf
Usage: make [target] [ENV_VARIABLE=ENV_VALUE ...]

Available targets:

### App ###
  test           Full tests run.
  calculation    Run calculation.

### Docker ###
  build         Build or rebuild services
  down          Stop, kill and purge project containers.
  up            Starts and attaches to containers for a service
  reset         down + up alias
  start         Start containers.
  stop          Stop containers.

### Bash ###
  bash          Go to the application container (if any)

```


# PHPUnit test case splitter

Have you got a slow running test suite?
Are existing test parallelisation tools (e.g. [paratest](https://github.com/paratestphp/paratest)) not suitable as you need separate database instances?

If so, PHPUnit test case splitter might help.
It splits tests into batches in a deterministic way.
Each batch of tests can run in separate instances (e.g. by using a matrix in GitHub actions).

## Usage

Install via [Composer](https://getcomposer.org):

```bash
composer require --dev dave-liddament/test-splitter
```

This package provides an executable under `vendor/bin/tsplit` that takes two arguments: batch, and number of batches.
It accepts a list of tests piped into `stdin` and outputs the tests for the specified batch to `stdout`.

To split the tests into 4 batches and run the first batch you can do:

```bash
vendor/bin/phpunit --filter `vendor/bin/phpunit --list-tests | vendor/bin/tsplit 1 4`
```

To run the second batch out of 4 you'd use:

```bash
vendor/bin/phpunit --filter `vendor/bin/phpunit --list-tests | vendor/bin/tsplit 2 4`
```

## GitHub actions

Add this to your GitHub actions:

```yaml
jobs:
  tests:
  
    strategy:
      fail-fast: false
      matrix: 
        test-batch: [1, 2, 3, 4]

    steps: 
      # Steps to checkout code, setup environment, etc.

      - name: "Tests batch ${{ matrix.test--batch }}"
        run: vendor/bin/phpunit --filter `vendor/bin/phpunit --list-tests | vendor/bin/tsplit ${{ matrix.test-batch }} 4`
```

This will split the tests over 4 different jobs.

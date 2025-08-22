<?php

const IS_PRODUCTION = false;

const ARE_ERRORS_LOGGED = true;

const USE_OUTPUT_BUFFERING = true;

set_error_handler(function (
    int $errorLevel,
    string $errorMessage,
    ?string $errorFileName,
    ?int $errorFileLine,
    ?array $errorContext = null
): bool {
    throw new ErrorException(
        $errorMessage,
        filename: $errorFileName,
        line: $errorFileLine
    );
});

try {

    if (USE_OUTPUT_BUFFERING) {
        ob_start();
    }

    echo <<<HTML
    <!doctype html>
    <html>
        <head>
            <title>Code examples: Output buffering and error handling</title>
        </head>
        <body>
            <h1>Code examples: Output buffering and error handling example.</h1>

            <p>This is the beginning of the success page...</p>
    HTML;

    if (array_key_exists('mockError', $_REQUEST) && $_REQUEST['mockError']) {
        throw new Exception('Mock error.');
    }

    echo "Success page.";

    echo <<<HTML
        </body>
    </html>
    HTML;

    if (USE_OUTPUT_BUFFERING) {
        $outputBufferContents = ob_get_clean();

        if ($outputBufferContents === false) {
            throw new Exception('No data found in the output buffer at response time.');
        }

        echo $outputBufferContents;
    }
} catch (Throwable $error) {

    if (USE_OUTPUT_BUFFERING) {
        ob_end_clean();
    }

    echo <<<HTML
    <!doctype html>
    <html>
        <head>
            <title>Error page</title>
        </head>
        <body>
            <h1>Error page.</h1>
            <p>This is the beginning of the error page...</p>
    HTML;

    if (IS_PRODUCTION) {
        echo "Error page.";
    } else {
        echo "Error page. Details: {$error}";
    }

    echo <<<HTML
        </body>
    </html>
    HTML;

    if (ARE_ERRORS_LOGGED) {
        file_put_contents(__DIR__ . '/error_log.txt', $error . "\n\n", FILE_APPEND);
    }
}

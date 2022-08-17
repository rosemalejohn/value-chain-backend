<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Traits\Conditionable;
use JsonSerializable;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * App Rederable HttpException
 */
class HttpException extends RuntimeException implements JsonSerializable, HttpExceptionInterface
{
    use Conditionable;

    /**
     * The friendly code for this exception.
     *
     * @var string
     */
    protected $errorCode;

    /**
     * Default http status code when the exception is rendered.
     *
     * @var int
     */
    protected $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * Default http headers to be used when the exception is rendered.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * A data to be merge with in the body of our error.
     *
     * @var array
     */
    protected $mergeData = [];

    /**
     * A static method to create the instance of exception
     *
     * @return $this
     */
    public static function make()
    {
        return new self();
    }

    public static function with($message, $statusCode = 400, $errorCode = null, $headers = [])
    {
        return self::make()
            ->withMessage($message)
            ->when($statusCode, function ($exception) use ($statusCode) {
                $exception->withStatusCode($statusCode);
            })
            ->when($errorCode, function ($exception) use ($errorCode) {
                $exception->withErrorCode($errorCode);
            })
            ->when($headers, function ($exception) use ($headers) {
                $exception->withHeaders($headers);
            });
    }

    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * The friendly error code for this exception.
     *
     * @return void
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Set the message of the exception.
     *
     * @param  string  $message
     * @return self
     */
    public function withMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set the error code for this exception.
     *
     * @param  string  $errorCode
     * @return self
     */
    public function withErrorCode(string $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Set the http status code of the exception.
     *
     * @param  int  $statusCode
     * @return self
     */
    public function withStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Set the http header of the exception.
     *
     * @param  array  $headers
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Merge additional error data into our error body.
     *
     * @param  array  $mergeData
     * @return self
     */
    public function withAddionalErrorData(array $data = [])
    {
        $this->mergeData = $data;

        return $this;
    }

    /**
     * Return the error body
     *
     * @return array
     */
    public function getErrorData()
    {
        return array_merge(
            [
                'success' => false,
                'http_status' => $this->getStatusCode(),
                'error_code' => $this->getErrorCode(),
                'message' => empty($this->getMessage()) ? 'Something went wrong!' : $this->getMessage(),
            ],
            $this->mergeData
        );
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode
     *
     * @return void
     */
    public function jsonSerialize(): mixed
    {
        return $this->getErrorData();
    }

    /**
     * Create an exception instance from validator.
     *
     * @param [type] $validator
     * @return void
     */
    public static function fromValidator(Validator $validator)
    {
        return self::make()
            ->withMessage($validator->errors()->first())
            ->withAddionalErrorData(['errors' => $validator->errors()->toArray()])
            ->withErrorCode('HTTP_UNPROCESSABLE_ENTITY')
            ->withStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Create an \App\Exception\HttpException instance from Exception.
     *
     * @param  Exception  $exception
     * @return void
     */
    public static function fromException(Exception $exception)
    {
        return self::make()
            ->withStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->withMessage($exception->getMessage());
    }
}

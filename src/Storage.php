<?php

declare(strict_types=1);

namespace CannaPress\GcpTables;


use Psr\Http\Message\StreamInterface;
use RuntimeException;

abstract class Storage
{

    public abstract function write(string $path, StreamInterface|string $data, array $metadata = []): void;
    public abstract function exists(string $path): bool;
    public abstract function read(string $path): StreamInterface;
    public abstract function list(string $prefix = '', string $delimiter = '', int $pageSize = 1000, string $pagingToken = ''): StorageListResult;
    public abstract function delete(string $path): void;


    public static function data_stream(string $content)
    {
        return Storage::file_stream('data://text/plain,' . urlencode($content), 'r');
    }
    public static function file_stream(string $path, $mode): StreamInterface
    {
        return new class($path, $mode) implements StreamInterface
        {
            private $handle;
            private bool $canRead = false;
            private bool $canWrite = false;

            public function __construct(private string $path, $mode)
            {
                $this->handle = fopen($this->path, $mode);
                $mode = trim(strtolower($mode));
                if ($mode === 'r') {
                    $this->canRead = true;
                    $this->canWrite = false;
                } else if (strpos($mode, '+')) {
                    $this->canRead = true;
                    $this->canWrite = true;
                } else {
                    $this->canRead = false;
                    $this->canWrite = true;
                }
            }
            public function __destruct()
            {
                $this->close();
            }
            /**
             * Reads all data from the stream into a string, from the beginning to end.
             *
             * This method MUST attempt to seek to the beginning of the stream before
             * reading data and read the stream until the end is reached.
             *
             * Warning: This could attempt to load a large amount of data into memory.
             *
             * This method MUST NOT raise an exception in order to conform with PHP's
             * string casting operations.
             *
             * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
             * @return string
             */
            public function __toString()
            {
                try {
                    return $this->getContents();
                } catch (RuntimeException $ex) {
                    return '';
                }
            }

            /**
             * Closes the stream and any underlying resources.
             *
             * @return void
             */
            public function close()
            {
                if ($this->handle) {
                    fclose($this->handle);
                }
                $this->handle = false;
            }

            /**
             * Separates any underlying resources from the stream.
             *
             * After the stream has been detached, the stream is in an unusable state.
             *
             * @return resource|null Underlying PHP stream, if any
             */
            public function detach()
            {
                $handle = null;
                if ($this->handle) {
                    $handle = $this->handle;
                }
                $this->handle = false;
                return $handle;
            }

            /**
             * Get the size of the stream if known.
             *
             * @return int|null Returns the size in bytes if known, or null if unknown.
             */
            public function getSize()
            {
                if (!$this->handle) {
                    return null;
                }
                $stat = fstat($this->handle);
                return $stat['size'];
            }

            /**
             * Returns the current position of the file read/write pointer
             *
             * @return int Position of the file pointer
             * @throws \RuntimeException on error.
             */
            public function tell()
            {
                if (!$this->handle) {
                    throw new RuntimeException("The stream is closed");
                }
                return ftell($this->handle);
            }

            /**
             * Returns true if the stream is at the end of the stream.
             *
             * @return bool
             */
            public function eof()
            {
                if ($this->handle) {
                    return feof($this->handle);
                }
                return true;
            }

            /**
             * Returns whether or not the stream is seekable.
             *
             * @return bool
             */
            public function isSeekable()
            {
                return $this->handle !== false;
            }

            /**
             * Seek to a position in the stream.
             *
             * @link http://www.php.net/manual/en/function.fseek.php
             * @param int $offset Stream offset
             * @param int $whence Specifies how the cursor position will be calculated
             *     based on the seek offset. Valid values are identical to the built-in
             *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
             *     offset bytes SEEK_CUR: Set position to current location plus offset
             *     SEEK_END: Set position to end-of-stream plus offset.
             * @throws \RuntimeException on failure.
             */
            public function seek(int $offset, int $whence = SEEK_SET)
            {
                if (!$this->isSeekable()) {
                    throw new RuntimeException("The stream is closed");
                }
                fseek($this->handle, $offset, $whence);
            }

            /**
             * Seek to the beginning of the stream.
             *
             * If the stream is not seekable, this method will raise an exception;
             * otherwise, it will perform a seek(0).
             *
             * @see seek()
             * @link http://www.php.net/manual/en/function.fseek.php
             * @throws \RuntimeException on failure.
             */
            public function rewind()
            {
                if ($this->isSeekable()) {
                    throw new RuntimeException("The stream is closed");
                }
                fseek($this->handle, 0);
            }

            /**
             * Returns whether or not the stream is writable.
             *
             * @return bool
             */
            public function isWritable()
            {
                return $this->handle !== false && $this->canWrite;
            }

            /**
             * Write data to the stream.
             *
             * @param string $string The string that is to be written.
             * @return int Returns the number of bytes written to the stream.
             * @throws \RuntimeException on failure.
             */
            public function write(string $string)
            {
                if (!$this->isWritable()) {
                    throw new RuntimeException("The stream is closed or not writable");
                }

                while (strlen($string) > 0) {
                    $written = fwrite($this->handle, $string, strlen($string));
                    if (!$written) {
                        throw new RuntimeException("Error writing file");
                    }
                    if ($written < strlen($string)) {
                        $string = substr($string, $written);
                    } else {
                        break;
                    }
                }
            }

            /**
             * Returns whether or not the stream is readable.
             *
             * @return bool
             */
            public function isReadable()
            {
                return $this->handle !== false && $this->canRead;
            }

            /**
             * Read data from the stream.
             *
             * @param int $length Read up to $length bytes from the object and return
             *     them. Fewer than $length bytes may be returned if underlying stream
             *     call returns fewer bytes.
             * @return string Returns the data read from the stream, or an empty string
             *     if no bytes are available.
             * @throws \RuntimeException if an error occurs.
             */
            public function read(int $length)
            {
                if (!$this->isReadable()) {
                    throw new RuntimeException("The stream is closed or not readable");
                }
                $data = fread($this->handle, $length);
                if ($data) {
                    return $data;
                }
                return '';
            }

            /**
             * Returns the remaining contents in a string
             *
             * @return string
             * @throws \RuntimeException if unable to read or an error occurs while
             *     reading.
             */
            public function getContents()
            {
                if (!$this->isSeekable() || !$this->isReadable()) {
                    throw new RuntimeException("The stream is closed or not readable");
                }
                $current = $this->tell();
                $this->seek(0);
                $parts = [];
                while (!$this->eof()) {
                    $parts[] = $this->read(4096);
                }
                $this->seek($current);
                return implode('', $parts);
            }

            /**
             * Get stream metadata as an associative array or retrieve a specific key.
             *
             * The keys returned are identical to the keys returned from PHP's
             * stream_get_meta_data() function.
             *
             * @link http://php.net/manual/en/function.stream-get-meta-data.php
             * @param string|null $key Specific metadata to retrieve.
             * @return array|mixed|null Returns an associative array if no key is
             *     provided. Returns a specific key value if a key is provided and the
             *     value is found, or null if the key is not found.
             */
            public function getMetadata(?string $key = null)
            {
                if (!$this->handle) {
                    return null;
                }
                $hash = stream_get_meta_data($this->handle);
                if (!is_null($key)) {
                    if (isset($hash[$key])) {
                        return $hash[$key];
                    }
                    return null;
                }
                return $hash;
            }
        };
    }

    public static function in_memory(): Storage
    {
        return new class() extends Storage
        {
            private array $objects = [];

            public function delete(string $path): void
            {
                if (isset($this->objects[$path])) {
                    unset($this->objects[$path]);
                }
            }

            public function write(string $path, StreamInterface|string $data, array $metadata = []): void
            {
                $this->objects[$path] = [
                    'data' => is_string($data) ? $data : $data->getContents(),
                    'metadata' => $metadata
                ];
            }
            public function exists(string $path): bool
            {
                return isset($this->objects[$path]);
            }
            public function read(string $path): StreamInterface
            {
                if (!$this->exists($path)) {
                    throw new \RuntimeException("$path doesnt exist");
                }
                return Storage::data_stream($this->objects[$path]['data']);
            }

            public function list(string $prefix = '', string $delimiter = '', int $pageSize = 1000, string $pagingToken = ''): StorageListResult
            {
                $all = array_keys($this->objects);
                $offset = !empty($pagingToken) ? intval($pagingToken) : 0;
                if ($offset < 0) {
                    $offset = 0;
                }
                if (!empty($prefix)) {
                    $all = array_filter($all, fn ($path) => str_starts_with($path, $prefix));
                }
                if (!empty($delimiter)) {
                    $all = array_filter($all, fn ($path) => strpos($path, $delimiter, strlen($prefix) + strlen(($delimiter))) === false);
                }
                $length = min($pageSize, count($all) - $offset);
                $slice = array_slice($all, $offset, $length);
                $resultPageToken = '';
                if ($offset + $length < count($all)) {
                    $resultPageToken = strval($offset + $length);
                }
                return new StorageListResult($slice, $resultPageToken);
            }
        };
    }
}

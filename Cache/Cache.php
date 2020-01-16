<?php
namespace Mezon\Cache;

/**
 * Class Cache
 *
 * @package Mezon
 * @subpackage Cache
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/17)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class for caching data on disk.
 * For now we use one cache file for all pages of the service.
 * Cache drops each hour.
 *
 * @author Dodonov A.A.
 */
class Cache extends \Mezon\Singleton\Singleton
{

    /**
     * Cache data
     *
     * @var string
     */
    protected $Data = null;

    /**
     * Cache file path
     *
     * @var string
     */
    protected $CachePath = './cache/';

    /**
     * Fetching cache data
     *
     * @param string $FilePath
     *            path to cache file
     * @return string cache file content
     * @codeCoverageIgnore
     */
    protected function fileGetContents(string $FilePath): string
    {
        return (@file_get_contents($FilePath));
    }

    /**
     * Method inits cache
     */
    protected function init()
    {
        if ($this->Data === null) {
            $this->Data = $this->fileGetContents($this->CachePath . date('YmdH') . '.cache');

            if ($this->Data === false) {
                $this->Data = [];
            } else {
                $this->Data = $this->Data == '' ? [] : json_decode($this->Data, true);
            }
        }
    }

    /**
     * Method adds data to cache
     *
     * @param string $Key
     *            Key
     * @param mixed $Data
     *            Data to be stored in cache
     */
    public function set(string $Key, $Data)
    {
        $this->init();

        \Mezon\Functional\Functional::setField(
            $this->Data,
            $Key,
            [
                // giving us an ability to break reference of the object wich was passed in $Data
                'data' => json_decode(json_encode($Data))
            ]);
    }

    /**
     * Checking cache for data
     *
     * @param string $Key
     *            Data key
     * @return bool True if the data was found, false otherwise
     */
    public function exists(string $Key): bool
    {
        $this->init();

        return isset($this->Data[$Key]);
    }

    /**
     * Method gets data from cache
     *
     * @param string $Key
     *            Key of the requested data
     * @return mixed Data from cache
     */
    public function get(string $Key)
    {
        $this->init();

        if (\Mezon\Functional\Functional::fieldExists($this->Data, $Key, false) === false) {
            throw (new \Exception("The key $Key does not exist"));
        }

        $KeyValue = \Mezon\Functional\Functional::getField($this->Data, $Key, false);

        $Result = \Mezon\Functional\Functional::getField($KeyValue, 'data', false);

        // preventing external code from writing directly to cache
        return json_decode(json_encode($Result));
    }

    /**
     * Method stores data on disk
     *
     * @param string $Path
     * @param string $Content
     */
    protected function filePutContents(string $Path, string $Content): void
    {
        @file_put_contents($Path, $Content);
    }

    /**
     * Method flushes data on disk
     */
    public function flush()
    {
        if ($this->Data !== null) {
            $this->filePutContents($this->CachePath . date('YmdH') . '.cache', json_encode($this->Data));

            $this->Data = null;
        }
    }
}
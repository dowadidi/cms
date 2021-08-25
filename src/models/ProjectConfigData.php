<?php
declare(strict_types=1);
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\models;

use craft\base\Model;

/**
 * ProjectConfigData model class represents an instance of a project config data structure.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 4.0.0
 */
class ProjectConfigData extends Model
{
    private array $_data;

    public function __construct(array $data = []) {
        $this->_data = $data;
        parent::__construct();
    }

    public function set($path, $value) {
        if ($value === null) {
            $this->delete($path);
        }

        $this->_traverseDataArray($this->_data, $path, $value);
    }

    public function get($path) {
        return $this->_traverseDataArray($this->_data, $path);
    }

    public function delete($path) {
        return $this->_traverseDataArray($this->_data, $path, null, true);
    }

    public function export()
    {
        return $this->_data;
    }

    /**
     * Traverse a nested data array according to path and perform an action depending on parameters.
     *
     * @param array $data A nested array of data to traverse
     * @param array|string $path Path used to traverse the array. Either an array or a dot.based.path
     * @param mixed $value Value to set at the destination. If null, will return the value, unless deleting
     * @param bool $delete Whether to delete the value at the destination or not.
     * @return mixed
     */
    private function _traverseDataArray(array &$data, $path, $value = null, bool $delete = false)
    {
        if (is_string($path)) {
            $path = explode('.', $path);
        }

        $nextSegment = array_shift($path);

        // Last piece?
        if (count($path) === 0) {
            if ($delete) {
                unset($data[$nextSegment]);
            } else if ($value === null) {
                return $data[$nextSegment] ?? null;
            } else {
                $data[$nextSegment] = $value;
            }
        } else {
            if (!isset($data[$nextSegment])) {
                // If the path doesn't exist, it's fine if we wanted to delete or read
                if ($delete || $value === null) {
                    return null;
                }

                $data[$nextSegment] = [];
            } else if (!is_array($data[$nextSegment])) {
                // If the next part is not an array, but we have to travel further, make it an array.
                $data[$nextSegment] = [];
            }

            return $this->_traverseDataArray($data[$nextSegment], $path, $value, $delete);
        }

        return null;
    }
}
<?php

/*
 * This file is part of YaEtl.
 *     (c) Fabrice de Stefanis / https://github.com/fab2s/YaEtl
 * This source file is licensed under the MIT license which you will
 * find in the LICENSE file or at https://opensource.org/licenses/MIT
 */

namespace fab2s\YaEtl\Extractors;

/**
 * Class ExtractorBatchLimitAbstract
 */
abstract class ExtractorBatchLimitAbstract extends ExtractorLimitAbstract
{
    /**
     * query offset
     *
     * @var int
     */
    protected $offset = 0;

    /**
     * Number of records to fetch at once
     *
     * @var int
     */
    protected $batchSize = 1337;

    /**
     * makes sure that offset + batchsize does not exceed limit
     * by setting $this->batchSize to 0 when goign beyond $this->limit
     *
     * @return $this
     */
    public function enforceBatchSize()
    {
        if ($this->limit && ($this->numRecords + $this->batchSize > $this->limit)) {
            $this->batchSize = max(0, $this->limit - $this->numRecords);
        }

        return $this;
    }

    /**
     * can be used to set a specific offset prior to start the scan
     *
     * @param int $offset
     *
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = max(0, (int) $offset);

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return (int) $this->offset;
    }

    /**
     * @param int $batchSize
     *
     * @return $this
     */
    public function setBatchSize($batchSize)
    {
        $this->batchSize = max(1, (int) $batchSize);

        return $this;
    }

    /**
     * @return int
     */
    public function getBatchSize()
    {
        return $this->batchSize;
    }

    /**
     * adds limit to offset, to be invoqued
     * each time extract() is executed
     *
     * @return $this
     */
    public function incrementOffset()
    {
        if ($this->limit) {
            $this->offset += $this->batchSize;
        } else {
            // set limit to numRecords as we got all record at once
            // without limit. This is a bit hackish I know but is so far
            // required to handle the, no limit case whith a limited
            // extractor
            $this->limit = $this->numRecords + 1;
        }

        return $this;
    }
}
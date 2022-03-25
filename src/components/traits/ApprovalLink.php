<?php

namespace eluhr\shop\components\traits;

trait ApprovalLink
{
    /**
     * @var string
     */
    private $_approvalLink;

    /**
     * @return string
     */
    public function getApprovalLink(): string
    {
        return $this->_approvalLink;
    }

    /**
     * @param string $approvalLink
     * @return void
     */
    public function setApprovalLink(string $approvalLink): void
    {
        $this->_approvalLink = $approvalLink;
    }
}
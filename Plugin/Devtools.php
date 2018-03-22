<?php

namespace DNAFactory\Devtools\Plugin;

use Magento\Framework\App\RequestInterface;

class Devtools
{
    protected $request;

    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    public function afterGetEnabled($subject, $result)
    {
        if ($this->request->getParam('devtools', 'no') == 'yes') {
            $this->isEnabled = true;
        } else {
            $this->isEnabled = false;
        }

        return $this->isEnabled;
    }
}

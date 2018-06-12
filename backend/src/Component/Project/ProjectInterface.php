<?php

namespace Component\Project;

use Component\Currency\Model\CurrencyAwareInterface;
use Component\Resource\Model\FileSystemAwareInterface;

interface ProjectInterface extends CurrencyAwareInterface, FileSystemAwareInterface
{
}

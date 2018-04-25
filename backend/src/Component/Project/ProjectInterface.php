<?php

namespace Component\Project;

use Component\Currency\CurrencyAwareInterface;
use Component\Model\FileSystemAwareInterface;

interface ProjectInterface extends CurrencyAwareInterface, FileSystemAwareInterface
{
}

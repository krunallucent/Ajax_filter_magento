<?php
namespace Lucent\Hello\Controller\Search;
class Result extends \Magento\CatalogSearch\Controller\Result\Index
{
	public function execute($coreRoute = null)
	{
		return parent::execute($coreRoute);
	}
}

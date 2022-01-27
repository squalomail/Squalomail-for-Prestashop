<?php
/**
 * PrestaChamps
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Commercial License
 * you can't distribute, modify or sell this code
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file
 * If you need help please contact leo@prestachamps.com
 *
 * @author    Squalomail
 * @copyright PrestaChamps
 * @license   commercial
 */

namespace PrestaChamps\SqualomailModule\Formatters;

/**
 * Class CustomerFormatter
 *
 * @package PrestaChamps\SqualomailModule\Formatters
 */
class CategoryFormatter
{
    public $category;
    public $context;

    /**
     * CustomerFormatter constructor.
     *
     * @param \Category $category
     * @param \Context  $context
     */
    public function __construct(\Category $category, \Context $context)
    {
        $this->category = $category;
        $this->context = $context;
    }

    /**
     * @return array
     * @throws \PrestaShopDatabaseException
     * @todo Improve this spaghetti
     *
     */
    public function format()
    {
        $categoryId = (string)$this->category->id;
        $products = \Product::getProducts($this->context->language->id, 0, NULL, 'id_product', 'ASC', $categoryId);

        if ($categoryId == 37) {
            print_r("<pre>");
            print_r($products);
            print_r("</pre>");
        }

        $data = array(
            'id' => $categoryId,
            'title' => $this->category->name,
            'handle' => $this->category->link_rewrite,
            'product_ids' => array_column($products, "id_product")
        );

        return $data;
    }
}
